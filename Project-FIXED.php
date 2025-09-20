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

        // If admin saved an absolute URL, return as-is
        if (Str::startsWith($this->image_path, ['http://','https://'])) {
            return $this->image_path;
        }

        // If it's already a path starting with /, return as-is
        if (Str::startsWith($this->image_path, '/')) {
            return $this->image_path;
        }

        // Find the correct URL for the image
        return $this->findImageUrl($this->image_path);
    }

    /**
     * Find the correct URL for an image - SIMPLIFIED VERSION
     */
    private function findImageUrl(string $imagePath): ?string
    {
        $filename = basename($imagePath);
        
        // PRIORITY 1: Try direct access to common directories
        $directPaths = [
            '/storage/projects/' . $filename,
            '/images/projects/' . $filename, 
            '/uploads/projects/' . $filename
        ];
        
        foreach ($directPaths as $url) {
            $fullPath = public_path(ltrim($url, '/'));
            if (file_exists($fullPath) && filesize($fullPath) > 0) {
                return $url;
            }
        }
        
        // PRIORITY 2: Check storage/app/public and use image viewer
        $storagePath = storage_path('app/public/projects/' . $filename);
        if (file_exists($storagePath) && filesize($storagePath) > 0) {
            return '/image-view.php?img=' . urlencode($filename);
        }
        
        // PRIORITY 3: Use image viewer as universal fallback
        $possiblePaths = [
            public_path('storage/projects/' . $filename),
            public_path('images/projects/' . $filename),
            public_path('uploads/projects/' . $filename),
            storage_path('app/public/' . $filename),
            storage_path('app/public/projects/' . $filename)
        ];
        
        foreach ($possiblePaths as $path) {
            if (file_exists($path) && filesize($path) > 0) {
                return '/image-view.php?img=' . urlencode($filename);
            }
        }
        
        // FINAL FALLBACK: Check if image_path includes subdirectory
        if (!Str::contains($imagePath, '/') && !Str::contains($imagePath, 'projects')) {
            return $this->findImageUrl('projects/' . $imagePath);
        }

        // Return null if no image found anywhere
        return null;
    }

    /**
     * Check if the image file actually exists anywhere
     */
    public function hasValidImage(): bool
    {
        if (!$this->image_path) return false;

        // If it's an external URL, assume it's valid
        if (Str::startsWith($this->image_path, ['http://','https://'])) {
            return true;
        }

        $filename = basename($this->image_path);
        
        // Check all possible locations
        $checkPaths = [
            public_path('storage/projects/' . $filename),
            public_path('images/projects/' . $filename),
            public_path('uploads/projects/' . $filename),
            storage_path('app/public/projects/' . $filename),
            storage_path('app/public/' . $filename)
        ];
        
        foreach ($checkPaths as $path) {
            if (file_exists($path) && filesize($path) > 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get debug info about image paths (for troubleshooting)
     */
    public function getImageDebugInfo(): array
    {
        $filename = basename($this->image_path);
        $debug = [
            'original_path' => $this->image_path,
            'filename' => $filename,
            'generated_url' => $this->image_url,
            'has_valid_image' => $this->hasValidImage(),
            'file_locations' => []
        ];
        
        $checkPaths = [
            'public/storage/projects/' . $filename => public_path('storage/projects/' . $filename),
            'public/images/projects/' . $filename => public_path('images/projects/' . $filename),
            'public/uploads/projects/' . $filename => public_path('uploads/projects/' . $filename),
            'storage/app/public/projects/' . $filename => storage_path('app/public/projects/' . $filename),
        ];
        
        foreach ($checkPaths as $label => $path) {
            $debug['file_locations'][$label] = [
                'exists' => file_exists($path),
                'size' => file_exists($path) ? filesize($path) : 0,
                'path' => $path
            ];
        }
        
        return $debug;
    }
}