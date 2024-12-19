<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Photo;
use Illuminate\Support\Facades\Auth;


class UploadImageController extends Controller
{
    public function index()
    {
        return view('upload');
    }

    public function save(Request $request)
    {
        $request->validate([
            'images.*' => 'nullable|file|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'videos.*' => 'nullable|file|mimes:mp4,avi,mkv|max:20480',
            'files.*' => 'nullable|file|max:20480',
            'users_id' => ['required', 'string', 'max:255'],
        ]);

        // Simpan gambar
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . uniqid() . '.' . $image->extension();
                $image->move(public_path('uploads/images'), $imageName);

                $photo = new Photo;
                $photo->name = $imageName;
                $photo->path = 'uploads/images/' . $imageName;
                $photo->users_id = Auth::id();
                $photo->save();
            }
        }

        // Simpan video
        if ($request->hasFile('videos')) {
            foreach ($request->file('videos') as $video) {
                $videoName = time() . '_' . uniqid() . '.' . $video->extension();
                $video->move(public_path('uploads/videos'), $videoName);

                $photo = new Photo;
                $photo->name = $videoName;
                $photo->path = 'uploads/videos/' . $videoName;
                $photo->users_id = Auth::id();
                $photo->save();
            }
        }

        // Simpan file lainnya
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $fileName = time() . '_' . uniqid() . '.' . $file->extension();
                $file->move(public_path('uploads/files'), $fileName);

                $photo = new Photo;
                $photo->name = $fileName;
                $photo->path = 'uploads/files/' . $fileName;
                $photo->users_id = Auth::id();
                $photo->save();
            }
        }

        return back()->with('success', 'Files uploaded successfully.');
    }
}
