<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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

        // Try different storage locations in order of preference
        return $this->findImageUrl($this->image_path);
    }

    /**
     * Find the correct URL for an image - FIXED for your hosting
     */
    private function findImageUrl(string $imagePath): ?string
    {
        $filename = basename($imagePath);
        
        // IMPORTANT: Based on diagnosis, direct storage access is blocked
        // Use Image Viewer script which is proven to work
        
        // Check if image file exists in any location
        $imagePaths = [
            public_path('storage/projects/' . $filename),
            storage_path('app/public/projects/' . $filename),
            public_path('images/projects/' . $filename),
            public_path('uploads/projects/' . $filename)
        ];
        
        foreach ($imagePaths as $path) {
            if (file_exists($path) && filesize($path) > 0) {
                // File exists - use Image Viewer script (proven to work)
                return '/image-view.php?img=' . urlencode($filename);
            }
        }
        
        // Fallback: Check if the image_path already includes the projects directory
        if (!Str::startsWith($imagePath, 'projects/')) {
            return $this->findImageUrl('projects/' . $imagePath);
        }

        // Final fallback: return null (no image found)
        return null;
    }

    /**
     * Check if the image file actually exists
     */
    public function hasValidImage(): bool
    {
        if (!$this->image_path) return false;

        // If it's an external URL, assume it's valid
        if (Str::startsWith($this->image_path, ['http://','https://'])) {
            return true;
        }

        $filename = basename($this->image_path);
        
        // Check all possible locations where images are stored
        $checkPaths = [
            public_path('storage/projects/' . $filename),
            storage_path('app/public/projects/' . $filename),
            public_path('images/projects/' . $filename),
            public_path('uploads/projects/' . $filename)
        ];
        
        foreach ($checkPaths as $path) {
            if (file_exists($path) && filesize($path) > 0) {
                return true;
            }
        }

        return false;
    }
}
