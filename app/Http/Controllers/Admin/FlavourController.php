<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageFlavour;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FlavourController extends Controller
{
    public function __construct(private CloudinaryService $cloudinary)
    {
    }

    public function index()
    {
        $flavours = HomepageFlavour::orderBy('sort_order')->get();

        if (request()->wantsJson() || request()->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $flavours,
            ]);
        }

        return view('admin.flavours.index', compact('flavours'));
    }

    public function create()
    {
        return view('admin.flavours.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'link' => 'nullable|url|max:500',
            'link_text' => 'nullable|string|max:100',
            'col_size' => 'required|integer|in:1,2,3,4',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($request, $validator);
        }

        try {
            $imageUrl = $this->cloudinary->upload(
                $request->file('image'),
                config('cloudinary.folder').'/flavours'
            );
        } catch (\Throwable $e) {
            return back()->withErrors(['image' => 'Görsel yüklenemedi: '.$e->getMessage()])->withInput();
        }

        $flavour = HomepageFlavour::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imageUrl,
            'link' => $request->link,
            'link_text' => $request->link_text ?? 'Keşfet',
            'col_size' => $request->col_size,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Lezzet başarıyla oluşturuldu.',
                'data' => $flavour,
            ], 201);
        }

        return redirect()->route('admin.flavours.index')
            ->with('success', 'Lezzet başarıyla oluşturuldu.');
    }

    public function show(string $id)
    {
        return redirect()->route('admin.flavours.edit', $id);
    }

    public function edit(string $id)
    {
        $flavour = HomepageFlavour::findOrFail($id);

        return view('admin.flavours.edit', compact('flavour'));
    }

    public function update(Request $request, string $id)
    {
        $flavour = HomepageFlavour::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'link' => 'nullable|url|max:500',
            'link_text' => 'nullable|string|max:100',
            'col_size' => 'required|integer|in:1,2,3,4',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($request, $validator);
        }

        $imageUrl = $flavour->image;

        if ($request->hasFile('image')) {
            try {
                $this->cloudinary->delete($flavour->image);
                $imageUrl = $this->cloudinary->upload(
                    $request->file('image'),
                    config('cloudinary.folder').'/flavours'
                );
            } catch (\Throwable $e) {
                return back()->withErrors(['image' => 'Görsel yüklenemedi: '.$e->getMessage()])->withInput();
            }
        }

        $flavour->update([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imageUrl,
            'link' => $request->link,
            'link_text' => $request->link_text ?? 'Keşfet',
            'col_size' => $request->col_size,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Lezzet başarıyla güncellendi.',
                'data' => $flavour->fresh(),
            ]);
        }

        return redirect()->route('admin.flavours.index')
            ->with('success', 'Lezzet başarıyla güncellendi.');
    }

    public function destroy(string $id)
    {
        $flavour = HomepageFlavour::findOrFail($id);
        $this->cloudinary->delete($flavour->image);
        $flavour->delete();

        if (request()->wantsJson() || request()->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Lezzet başarıyla silindi.',
            ]);
        }

        return redirect()->route('admin.flavours.index')
            ->with('success', 'Lezzet başarıyla silindi.');
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
}
