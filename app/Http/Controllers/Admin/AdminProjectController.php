<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;

class AdminProjectController extends Controller
{
    public function index()
    {
        $projects = Project::latest()->paginate(15);
        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        return view('admin.projects.create');
    }

    public function store(Request $request)
    {
        // Prepend https:// if scheme missing so 'url' rule passes
        $request->merge([
            'live_url' => $this->normalizeUrl($request->input('live_url')),
            'repo_url' => $this->normalizeUrl($request->input('repo_url')),
        ]);

        $data = $request->validate([
            'title'       => 'required|max:150',
            'slug'        => 'nullable|max:160',
            'description' => 'nullable|max:5000',
            'tech_stack'  => 'nullable|max:255',

            // either upload a file OR supply an external URL in image_path
            'image'       => 'nullable|file|mimes:jpeg,jpg,png,gif,webp,bmp|max:3072', // 3MB
            'image_path'  => 'nullable|string|max:2048',

            'live_url'    => 'nullable|url',
            'repo_url'    => 'nullable|url',
            'featured'    => 'nullable|boolean',
        ]);

        $data['slug']     = $data['slug'] ?: Str::slug($data['title']);
        $data['featured'] = (bool)($data['featured'] ?? false);

        if ($request->hasFile('image')) {
            // Force the PUBLIC disk so files land in storage/app/public/projects
            $path = $this->handleFileUpload($request->file('image'), 'projects');
            if ($path) {
                $data['image_path'] = $path; // e.g. "projects/abc.jpg"
            } else {
                return redirect()->back()->withInput()->withErrors(['image' => 'Failed to upload image. Please try again.']);
            }
        }

        Project::create($data);

        return redirect()->route('admin.projects.index')->with('success', 'Project created.');
    }

    public function edit(Project $project)
    {
        return view('admin.projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $request->merge([
            'live_url' => $this->normalizeUrl($request->input('live_url')),
            'repo_url' => $this->normalizeUrl($request->input('repo_url')),
        ]);

        $data = $request->validate([
            'title'       => 'required|max:150',
            'slug'        => 'required|max:160|unique:projects,slug,'.$project->id,
            'description' => 'nullable|max:5000',
            'tech_stack'  => 'nullable|max:255',

            'image'       => 'nullable|file|mimes:jpeg,jpg,png,gif,webp,bmp|max:3072',
            'image_path'  => 'nullable|string|max:2048',
            'remove_image'=> 'nullable|boolean',

            'live_url'    => 'nullable|url',
            'repo_url'    => 'nullable|url',
            'featured'    => 'nullable|boolean',
        ]);

        $data['featured'] = (bool)($data['featured'] ?? false);

        // Remove stored file if requested and it was stored on our disk (not an external URL)
        if ($request->boolean('remove_image') && $project->image_path && !Str::startsWith($project->image_path, ['http://','https://','/'])) {
            $this->deleteFile($project->image_path);
            $data['image_path'] = null;
        }

        // Replace with newly uploaded file
        if ($request->hasFile('image')) {
            if ($project->image_path && !Str::startsWith($project->image_path, ['http://','https://','/'])) {
                $this->deleteFile($project->image_path);
            }
            $path = $this->handleFileUpload($request->file('image'), 'projects');
            if ($path) {
                $data['image_path'] = $path;
            } else {
                return redirect()->back()->withInput()->withErrors(['image' => 'Failed to upload image. Please try again.']);
            }
        }

        $project->update($data);

        return redirect()->route('admin.projects.index')->with('success', 'Project updated.');
    }

    public function destroy(Project $project)
    {
        if ($project->image_path && !Str::startsWith($project->image_path, ['http://','https://','/'])) {
            $this->deleteFile($project->image_path);
        }
        $project->delete();
        return redirect()->route('admin.projects.index')->with('success', 'Project deleted.');
    }

    private function normalizeUrl(?string $url): ?string
    {
        if (!$url) return null;
        $url = trim($url);
        if ($url === '') return null;
        if (!Str::startsWith($url, ['http://','https://'])) {
            $url = 'https://'.$url;
        }
        return $url;
    }

    /**
     * Handle file upload without relying on finfo extension or Laravel Storage
     */
    private function handleFileUpload(UploadedFile $file, string $directory): ?string
    {
        try {
            // Get file extension from original name
            $extension = strtolower($file->getClientOriginalExtension());
            
            // Validate file extension manually
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];
            if (!in_array($extension, $allowedExtensions)) {
                throw new \InvalidArgumentException('Invalid file type. Only image files are allowed.');
            }
            
            // Generate unique filename
            $filename = Str::random(40) . '.' . $extension;
            $relativePath = $directory . '/' . $filename;
            
            // Create full paths
            $storagePath = storage_path('app/public');
            $directoryPath = $storagePath . DIRECTORY_SEPARATOR . $directory;
            $fullFilePath = $directoryPath . DIRECTORY_SEPARATOR . $filename;
            
            // Ensure directory exists
            if (!is_dir($directoryPath)) {
                mkdir($directoryPath, 0755, true);
            }
            
            // Get file contents and write directly
            $tempPath = $file->getRealPath();
            if (!$tempPath || !file_exists($tempPath)) {
                throw new \RuntimeException('Uploaded file not found');
            }
            
            $fileContents = file_get_contents($tempPath);
            if ($fileContents === false) {
                throw new \RuntimeException('Failed to read uploaded file');
            }
            
            $result = file_put_contents($fullFilePath, $fileContents);
            if ($result === false) {
                throw new \RuntimeException('Failed to write file to storage');
            }
            
            // Set file permissions
            chmod($fullFilePath, 0644);
            
            return $relativePath;
            
        } catch (\Exception $e) {
            // Log error without using Laravel's Log facade (in case it also has issues)
            error_log('File upload failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Delete file without using Laravel Storage
     */
    private function deleteFile(string $relativePath): bool
    {
        try {
            $fullPath = storage_path('app/public/' . $relativePath);
            if (file_exists($fullPath)) {
                return unlink($fullPath);
            }
            return true; // File doesn't exist, consider it "deleted"
        } catch (\Exception $e) {
            error_log('File deletion failed: ' . $e->getMessage());
            return false;
        }
    }
}
