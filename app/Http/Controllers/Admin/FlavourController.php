<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageFlavour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FlavourController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $flavours = HomepageFlavour::orderBy('sort_order')->get();
        
        if (request()->wantsJson() || request()->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $flavours
            ]);
        }
        
        return view('admin.flavours.index', compact('flavours'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.flavours.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|url|max:500',
            'link' => 'nullable|url|max:500',
            'link_text' => 'nullable|string|max:100',
            'col_size' => 'required|integer|in:1,2,3,4',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable',
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

        $flavour = HomepageFlavour::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $request->image,
            'link' => $request->link,
            'link_text' => $request->link_text ?? 'Keşfet',
            'col_size' => $request->col_size,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Lezzet başarıyla oluşturuldu.',
                'data' => $flavour
            ], 201);
        }

        return redirect()->route('admin.flavours.index')
            ->with('success', 'Lezzet başarıyla oluşturuldu.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $flavour = HomepageFlavour::findOrFail($id);
        
        if (request()->wantsJson() || request()->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $flavour
            ]);
        }
        
        return view('admin.flavours.show', compact('flavour'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $flavour = HomepageFlavour::findOrFail($id);
        return view('admin.flavours.edit', compact('flavour'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $flavour = HomepageFlavour::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|url|max:500',
            'link' => 'nullable|url|max:500',
            'link_text' => 'nullable|string|max:100',
            'col_size' => 'required|integer|in:1,2,3,4',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable',
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

        $flavour->update([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $request->image,
            'link' => $request->link,
            'link_text' => $request->link_text ?? 'Keşfet',
            'col_size' => $request->col_size,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Lezzet başarıyla güncellendi.',
                'data' => $flavour
            ]);
        }

        return redirect()->route('admin.flavours.index')
            ->with('success', 'Lezzet başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $flavour = HomepageFlavour::findOrFail($id);
        $flavour->delete();

        if (request()->wantsJson() || request()->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Lezzet başarıyla silindi.'
            ]);
        }

        return redirect()->route('admin.flavours.index')
            ->with('success', 'Lezzet başarıyla silindi.');
    }
}
