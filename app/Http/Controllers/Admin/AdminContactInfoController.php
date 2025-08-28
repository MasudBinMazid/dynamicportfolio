<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactInfo;
use Illuminate\Http\Request;

class AdminContactInfoController extends Controller
{
    public function edit()
    {
        $info = ContactInfo::first();
        return view('admin.contact_info.edit', compact('info'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'email' => 'nullable|email',
            'phone' => 'nullable|max:50',
            'address' => 'nullable|max:255',
            'github' => 'nullable|url',
            'linkedin' => 'nullable|url',
            'twitter' => 'nullable|url',
            'map_embed' => 'nullable',
            'cv_url' => 'nullable|url'
        ]);

        $info = ContactInfo::first();
        if (!$info) $info = new ContactInfo();
        $info->fill($data)->save();

        return back()->with('success', 'Contact info updated.');
    }
}
