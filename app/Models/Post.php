<?php

namespace App\Models;

use Illuminate\Support\Facades\Http;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'outline',
        'status',
        'meta_title',
        'meta_description',
        'keywords',
        'media',
        'published_at',
        'created_by'
    ];

    protected $casts = [
        'media_assets' => 'array',
        'scheduled_for' => 'datetime',
    ];

    /**
     * Relationship with the User who created the post.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
