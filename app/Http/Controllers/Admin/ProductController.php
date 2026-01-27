<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::orderBy('sort_order')->get();
        
        if (request()->wantsJson() || request()->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $products
            ]);
        }
        
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'badge' => 'nullable|string|max:50',
            'sizes' => 'nullable|array',
            'sizes.*' => 'nullable|string',
            'flavors' => 'nullable|array',
            'is_active' => 'nullable',
            'sort_order' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            try {
                $imagePath = $request->file('image')->store('products', 'public');
            } catch (\Exception $e) {
                return back()->withErrors(['image' => 'Görsel yüklenirken bir hata oluştu: ' . $e->getMessage()])->withInput();
            }
        } else {
            return back()->withErrors(['image' => 'Ana görsel zorunludur!'])->withInput();
        }
        
        // Handle multiple images upload
        $imagesPaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagesPaths[] = $image->store('products', 'public');
            }
        }
        
        // Process sizes (checkbox values)
        $sizesData = [];
        if ($request->has('sizes') && is_array($request->sizes)) {
            $availableSizes = [
                's' => ['id' => 's', 'name' => 'S size (1.5 kg - 5-6 kişilik)', 'price' => $request->base_price],
                'm' => ['id' => 'm', 'name' => 'M size (2.5 kg - 9-10 kişilik)', 'price' => $request->base_price * 1.2],
                'l' => ['id' => 'l', 'name' => 'L size (3.5 kg - 12-15 kişilik)', 'price' => $request->base_price * 1.5],
            ];
            
            foreach ($request->sizes as $sizeId) {
                if (isset($availableSizes[$sizeId])) {
                    $sizesData[] = $availableSizes[$sizeId];
                }
            }
        }
        
        // Process flavors (from form array)
        $flavorsData = [];
        if ($request->has('flavors') && is_array($request->flavors)) {
            foreach ($request->flavors as $flavor) {
                if (!empty($flavor['name'])) {
                    $flavorsData[] = [
                        'id' => strtolower(str_replace(' ', '_', $flavor['name'])),
                        'name' => $flavor['name'],
                        'image' => $flavor['image'] ?? null,
                    ];
                }
            }
        }

        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'base_price' => $request->base_price,
            'image' => $imagePath ? Storage::url($imagePath) : null,
            'images' => !empty($imagesPaths) ? array_map(fn($path) => Storage::url($path), $imagesPaths) : null,
            'sizes' => !empty($sizesData) ? $sizesData : null,
            'flavors' => !empty($flavorsData) ? $flavorsData : null,
            'badge' => $request->badge,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Ürün başarıyla oluşturuldu.',
                'data' => $product
            ], 201);
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Ürün başarıyla oluşturuldu.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::findOrFail($id);
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'badge' => 'nullable|string|max:50',
            'sizes' => 'nullable|array',
            'sizes.*' => 'nullable|string',
            'flavors' => 'nullable|array',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        // Handle image upload
        $imagePath = $product->image;
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image) {
                $oldPath = str_replace('/storage/', '', parse_url($product->image, PHP_URL_PATH));
                Storage::disk('public')->delete($oldPath);
            }
            $imagePath = Storage::url($request->file('image')->store('products', 'public'));
        }
        
        // Handle multiple images upload
        $imagesPaths = $product->images ?? [];
        if ($request->hasFile('images')) {
            // Delete old images if exists
            if ($product->images) {
                foreach ($product->images as $oldImage) {
                    $oldPath = str_replace('/storage/', '', parse_url($oldImage, PHP_URL_PATH));
                    Storage::disk('public')->delete($oldPath);
                }
            }
            $imagesPaths = [];
            foreach ($request->file('images') as $image) {
                $imagesPaths[] = Storage::url($image->store('products', 'public'));
            }
        }
        
        // Process sizes (checkbox values)
        $sizesData = $product->sizes ?? [];
        if ($request->has('sizes') && is_array($request->sizes)) {
            $availableSizes = [
                's' => ['id' => 's', 'name' => 'S size (1.5 kg - 5-6 kişilik)', 'price' => $request->base_price],
                'm' => ['id' => 'm', 'name' => 'M size (2.5 kg - 9-10 kişilik)', 'price' => $request->base_price * 1.2],
                'l' => ['id' => 'l', 'name' => 'L size (3.5 kg - 12-15 kişilik)', 'price' => $request->base_price * 1.5],
            ];
            
            $sizesData = [];
            foreach ($request->sizes as $sizeId) {
                if (isset($availableSizes[$sizeId])) {
                    $sizesData[] = $availableSizes[$sizeId];
                }
            }
        }
        
        // Process flavors (from form array)
        $flavorsData = $product->flavors ?? [];
        if ($request->has('flavors') && is_array($request->flavors)) {
            $flavorsData = [];
            foreach ($request->flavors as $flavor) {
                if (!empty($flavor['name'])) {
                    $flavorsData[] = [
                        'id' => strtolower(str_replace(' ', '_', $flavor['name'])),
                        'name' => $flavor['name'],
                        'image' => $flavor['image'] ?? null,
                    ];
                }
            }
        }

        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'base_price' => $request->base_price,
            'image' => $imagePath,
            'images' => !empty($imagesPaths) ? $imagesPaths : null,
            'sizes' => !empty($sizesData) ? $sizesData : null,
            'flavors' => !empty($flavorsData) ? $flavorsData : null,
            'badge' => $request->badge,
            'sort_order' => $request->sort_order ?? $product->sort_order,
            'is_active' => $request->has('is_active') || ($request->is_active ?? false),
        ]);

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Ürün başarıyla güncellendi.',
                'data' => $product
            ]);
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Ürün başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        if (request()->wantsJson() || request()->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Ürün başarıyla silindi.'
            ]);
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Ürün başarıyla silindi.');
    }
}
