<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomepageFlavour extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image',
        'link',
        'link_text',
        'col_size',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'col_size' => 'integer',
    ];

    /**
     * Get active flavours ordered by sort_order
     */
    public static function getActive()
    {
        return self::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }
}
