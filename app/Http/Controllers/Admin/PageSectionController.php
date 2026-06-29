<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageSection;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;

class PageSectionController extends Controller
{
    private const TEXT_FIELDS = [
        'hero_title',
        'hero_subtitle',
        'top_picks_title',
        'top_picks_subtitle',
        'flavours_title',
        'flavours_subtitle',
        'about_title',
        'about_content_1',
        'about_content_2',
    ];

    private const IMAGE_FIELDS = [
        'hero_image' => 'pages/hero',
        'about_image' => 'pages/about',
    ];

    public function __construct(private CloudinaryService $cloudinary)
    {
    }

    public function index()
    {
        $sections = PageSection::where('page', 'home')
            ->orderBy('sort_order')
            ->get();

        if (request()->wantsJson() || request()->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $sections,
            ]);
        }

        $sections = $sections->groupBy('section_key');

        return view('admin.pages.index', compact('sections'));
    }

    public function edit($page = 'home')
    {
        $sections = PageSection::where('page', $page)
            ->orderBy('sort_order')
            ->get();

        return view('admin.pages.edit', compact('sections', 'page'));
    }

    public function update(Request $request, $page = 'home')
    {
        try {
            foreach (self::TEXT_FIELDS as $key) {
                if (! $request->has($key)) {
                    continue;
                }

                PageSection::updateOrCreate(
                    ['page' => $page, 'section_key' => $key],
                    [
                        'section_type' => 'text',
                        'content' => $request->input($key),
                        'sort_order' => 0,
                    ]
                );
            }

            foreach (self::IMAGE_FIELDS as $key => $folder) {
                $existing = PageSection::where('page', $page)
                    ->where('section_key', $key)
                    ->value('content');

                $imageUrl = $request->input($key.'_existing', $existing);

                if ($request->hasFile($key)) {
                    $this->cloudinary->delete($existing);
                    $imageUrl = $this->cloudinary->upload(
                        $request->file($key),
                        config('cloudinary.folder').'/'.$folder
                    );
                }

                if ($imageUrl) {
                    PageSection::updateOrCreate(
                        ['page' => $page, 'section_key' => $key],
                        [
                            'section_type' => 'image',
                            'content' => $imageUrl,
                            'sort_order' => 0,
                        ]
                    );
                }
            }
        } catch (\Throwable $e) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 422);
            }

            return back()->withErrors(['image' => $e->getMessage()])->withInput();
        }

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Sayfa içerikleri başarıyla güncellendi.',
            ]);
        }

        return redirect()->route('admin.pages.index')
            ->with('success', 'Sayfa içerikleri başarıyla güncellendi.');
    }
}
