<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function __construct(private CloudinaryService $cloudinary)
    {
    }

    public function index()
    {
        $products = Product::orderBy('sort_order')->get();

        if (request()->wantsJson() || request()->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $products,
            ]);
        }

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $validator = $this->makeValidator($request, true);

        if ($validator->fails()) {
            return $this->validationErrorResponse($request, $validator);
        }

        try {
            $imageUrl = $this->cloudinary->upload(
                $request->file('image'),
                config('cloudinary.folder').'/products'
            );
        } catch (\Throwable $e) {
            return back()->withErrors(['image' => 'Ana görsel yüklenemedi: '.$e->getMessage()])->withInput();
        }

        try {
            $galleryImages = $this->uploadGalleryImages($request);
            $flavors = $this->buildFlavors($request);
        } catch (\Throwable $e) {
            return back()->withErrors(['image' => $e->getMessage()])->withInput();
        }

        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'base_price' => $request->base_price,
            'image' => $imageUrl,
            'images' => ! empty($galleryImages) ? $galleryImages : null,
            'sizes' => $this->buildSizes($request) ?: null,
            'flavors' => ! empty($flavors) ? $flavors : null,
            'badge' => $request->badge,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Ürün başarıyla oluşturuldu.',
                'data' => $product,
            ], 201);
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Ürün başarıyla oluşturuldu.');
    }

    public function show(string $id)
    {
        $product = Product::findOrFail($id);

        return redirect()->route('admin.products.edit', $product);
    }

    public function edit(string $id)
    {
        $product = Product::findOrFail($id);

        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);
        $validator = $this->makeValidator($request, false);

        if ($validator->fails()) {
            return $this->validationErrorResponse($request, $validator);
        }

        $imageUrl = $product->image;
        $images = $product->images ?? [];

        if ($request->hasFile('image')) {
            try {
                $this->cloudinary->delete($product->image);
                $imageUrl = $this->cloudinary->upload(
                    $request->file('image'),
                    config('cloudinary.folder').'/products'
                );
            } catch (\Throwable $e) {
                return back()->withErrors(['image' => 'Ana görsel yüklenemedi: '.$e->getMessage()])->withInput();
            }
        }

        try {
            if ($request->hasFile('images')) {
                $newImages = $this->uploadGalleryImages($request);
                $images = array_values(array_merge($images, $newImages));
            }

            $flavors = $this->buildFlavors($request);
        } catch (\Throwable $e) {
            return back()->withErrors(['image' => $e->getMessage()])->withInput();
        }

        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'base_price' => $request->base_price,
            'image' => $imageUrl,
            'images' => ! empty($images) ? $images : null,
            'sizes' => $this->buildSizes($request) ?: null,
            'flavors' => ! empty($flavors) ? $flavors : null,
            'badge' => $request->badge,
            'sort_order' => $request->sort_order ?? $product->sort_order,
            'is_active' => $request->has('is_active'),
        ]);

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Ürün başarıyla güncellendi.',
                'data' => $product->fresh(),
            ]);
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Ürün başarıyla güncellendi.');
    }

    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);

        $this->cloudinary->delete($product->image);

        if ($product->images) {
            foreach ($product->images as $image) {
                $this->cloudinary->delete($image);
            }
        }

        if ($product->flavors) {
            foreach ($product->flavors as $flavor) {
                $this->cloudinary->delete($flavor['image'] ?? null);
            }
        }

        $product->delete();

        if (request()->wantsJson() || request()->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Ürün başarıyla silindi.',
            ]);
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Ürün başarıyla silindi.');
    }

    private function makeValidator(Request $request, bool $isCreate)
    {
        return Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'image' => ($isCreate ? 'required' : 'nullable').'|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'badge' => 'nullable|string|max:50',
            'sizes' => 'nullable|array',
            'sizes.*' => 'nullable|string|in:s,m,l',
            'flavors' => 'nullable|array',
            'flavors.*.name' => 'nullable|string|max:255',
            'flavors.*.existing_image' => 'nullable|string|max:500',
            'flavors.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'sort_order' => 'nullable|integer',
        ]);
    }

    private function validationErrorResponse(Request $request, $validator)
    {
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        return back()->withErrors($validator)->withInput();
    }

    private function uploadGalleryImages(Request $request): array
    {
        $paths = [];

        if (! $request->hasFile('images')) {
            return $paths;
        }

        foreach ($request->file('images') as $image) {
            if (! $image) {
                continue;
            }

            try {
                $paths[] = $this->cloudinary->upload(
                    $image,
                    config('cloudinary.folder').'/products/gallery'
                );
            } catch (\Throwable $e) {
                throw new \RuntimeException('Galeri görseli yüklenemedi: '.$e->getMessage());
            }
        }

        return $paths;
    }

    private function buildSizes(Request $request): array
    {
        $sizesData = [];
        $availableSizes = [
            's' => ['id' => 's', 'name' => 'S size (1.5 kg - 5-6 kişilik)', 'price' => (float) $request->base_price, 'description' => '5-6 kişilik'],
            'm' => ['id' => 'm', 'name' => 'M size (2.5 kg - 9-10 kişilik)', 'price' => (float) $request->base_price * 1.2, 'description' => '9-10 kişilik'],
            'l' => ['id' => 'l', 'name' => 'L size (3.5 kg - 12-15 kişilik)', 'price' => (float) $request->base_price * 1.5, 'description' => '12-15 kişilik'],
        ];

        foreach ($request->input('sizes', []) as $sizeId) {
            if (isset($availableSizes[$sizeId])) {
                $sizesData[] = $availableSizes[$sizeId];
            }
        }

        return $sizesData;
    }

    private function buildFlavors(Request $request): array
    {
        $flavorsData = [];

        foreach ($request->input('flavors', []) as $index => $flavor) {
            if (empty($flavor['name'])) {
                continue;
            }

            $imageUrl = $flavor['existing_image'] ?? null;

            if ($request->hasFile("flavors.{$index}.image")) {
                try {
                    if ($imageUrl) {
                        $this->cloudinary->delete($imageUrl);
                    }

                    $imageUrl = $this->cloudinary->upload(
                        $request->file("flavors.{$index}.image"),
                        config('cloudinary.folder').'/products/flavors'
                    );
                } catch (\Throwable $e) {
                    throw new \RuntimeException('Lezzet görseli yüklenemedi: '.$e->getMessage());
                }
            }

            $flavorsData[] = [
                'id' => Str::slug($flavor['name'], '_'),
                'name' => $flavor['name'],
                'image' => $imageUrl,
            ];
        }

        return $flavorsData;
    }
}
