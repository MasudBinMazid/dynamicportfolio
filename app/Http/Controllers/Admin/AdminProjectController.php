<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
            'image'       => 'nullable|image|max:3072', // 3MB
            'image_path'  => 'nullable|string|max:2048',

            'live_url'    => 'nullable|url',
            'repo_url'    => 'nullable|url',
            'featured'    => 'nullable|boolean',
        ]);

        $data['slug']     = $data['slug'] ?: Str::slug($data['title']);
        $data['featured'] = (bool)($data['featured'] ?? false);

        if ($request->hasFile('image')) {
            // Force the PUBLIC disk so files land in storage/app/public/projects
            $path = $request->file('image')->store('projects', 'public');
            $data['image_path'] = $path; // e.g. "projects/abc.jpg"
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

            'image'       => 'nullable|image|max:3072',
            'image_path'  => 'nullable|string|max:2048',
            'remove_image'=> 'nullable|boolean',

            'live_url'    => 'nullable|url',
            'repo_url'    => 'nullable|url',
            'featured'    => 'nullable|boolean',
        ]);

        $data['featured'] = (bool)($data['featured'] ?? false);

        // Remove stored file if requested and it was stored on our disk (not an external URL)
        if ($request->boolean('remove_image') && $project->image_path && !Str::startsWith($project->image_path, ['http://','https://','/'])) {
            Storage::disk('public')->delete($project->image_path);
            $data['image_path'] = null;
        }

        // Replace with newly uploaded file
        if ($request->hasFile('image')) {
            if ($project->image_path && !Str::startsWith($project->image_path, ['http://','https://','/'])) {
                Storage::disk('public')->delete($project->image_path);
            }
            $path = $request->file('image')->store('projects', 'public');
            $data['image_path'] = $path;
        }

        $project->update($data);

        return redirect()->route('admin.projects.index')->with('success', 'Project updated.');
    }

    public function destroy(Project $project)
    {
        if ($project->image_path && !Str::startsWith($project->image_path, ['http://','https://','/'])) {
            Storage::disk('public')->delete($project->image_path);
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
}
