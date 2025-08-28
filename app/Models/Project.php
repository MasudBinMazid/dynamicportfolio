<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Project extends Model
{
    protected $fillable = [
        'title','slug','description','tech_stack','image_path','live_url','repo_url','featured'
    ];

    protected $casts = ['featured' => 'boolean'];

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image_path) return null;

        // If admin saved an absolute URL or absolute public path, return as-is
        if (Str::startsWith($this->image_path, ['http://','https://','/'])) {
            return $this->image_path;
        }

        // Files stored on the PUBLIC disk -> /storage/...
        return Storage::disk('public')->url($this->image_path);
    }
}
