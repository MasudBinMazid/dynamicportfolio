<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ContactMessage;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'projects' => Project::count(),
            'messages' => ContactMessage::count(),
            'featured' => Project::where('featured', true)->count(),
        ];
        return view('admin.dashboard', compact('stats'));
    }
}
