<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ContactInfo;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function home()    { return view('pages.home'); }
    public function about()   { return view('pages.about'); }
    public function skills()  { return view('pages.skills'); }

    public function projects()
    {
        $projects = Project::orderByDesc('featured')->orderBy('title')->paginate(9);
        return view('pages.projects', compact('projects'));
    }

    public function projectShow(string $slug)
    {
        $project = Project::where('slug', $slug)->firstOrFail();
        return view('pages.project-show', compact('project'));
    }

    public function contact()
    {
        $info = ContactInfo::first();
        return view('pages.contact', compact('info'));
    }

    public function sendContact(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|max:100',
            'email' => 'required|email',
            'subject' => 'nullable|max:150',
            'message' => 'required|max:2000',
        ]);

        ContactMessage::create($data);
        return back()->with('success', 'Thanks! Your message has been sent.');
    }
}
