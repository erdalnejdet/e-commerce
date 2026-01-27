<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageSection;
use Illuminate\Http\Request;

class PageSectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sections = PageSection::where('page', 'home')
            ->orderBy('sort_order')
            ->get();
        
        if (request()->wantsJson() || request()->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $sections
            ]);
        }
        
        $sections = $sections->groupBy('section_key');
        return view('admin.pages.index', compact('sections'));
    }

    /**
     * Show the form for editing page sections
     */
    public function edit($page = 'home')
    {
        $sections = PageSection::where('page', $page)
            ->orderBy('sort_order')
            ->get();
        
        return view('admin.pages.edit', compact('sections', 'page'));
    }

    /**
     * Update page sections
     */
    public function update(Request $request, $page = 'home')
    {
        $data = $request->except(['_token', '_method']);
        
        foreach ($data as $key => $value) {
            if ($value === null) continue;
            
            $type = 'text';
            
            // Determine type based on key or value
            if (is_array($value)) {
                $type = 'json';
                $value = json_encode($value);
            } elseif (filter_var($value, FILTER_VALIDATE_URL) || (is_string($value) && str_starts_with($value, 'http'))) {
                $type = 'image';
            }
            
            PageSection::updateOrCreate(
                ['page' => $page, 'section_key' => $key],
                [
                    'section_type' => $type,
                    'content' => is_array($value) ? json_encode($value) : $value,
                    'sort_order' => 0,
                ]
            );
        }

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Sayfa içerikleri başarıyla güncellendi.'
            ]);
        }

        return redirect()->route('admin.pages.index')
            ->with('success', 'Sayfa içerikleri başarıyla güncellendi.');
    }
}
