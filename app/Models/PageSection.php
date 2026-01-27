<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'page',
        'section_key',
        'section_type',
        'content',
        'metadata',
        'sort_order',
    ];

    protected $casts = [
        'metadata' => 'array',
        'sort_order' => 'integer',
    ];

    /**
     * Get section by page and key
     */
    public static function getSection($page, $key, $default = null)
    {
        $section = self::where('page', $page)
            ->where('section_key', $key)
            ->first();
        
        if (!$section) {
            return $default;
        }

        return match($section->section_type) {
            'json' => json_decode($section->content, true),
            'html' => $section->content,
            default => $section->content,
        };
    }

    /**
     * Set section content
     */
    public static function setSection($page, $key, $content, $type = 'text', $metadata = null)
    {
        $contentValue = $type === 'json' ? json_encode($content) : $content;
        
        return self::updateOrCreate(
            ['page' => $page, 'section_key' => $key],
            [
                'section_type' => $type,
                'content' => $contentValue,
                'metadata' => $metadata,
            ]
        );
    }
}
