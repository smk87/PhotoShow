<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AlbumsController extends Controller
{
    public function index()
    {
        return view('albums.index');
    }

    public function create()
    {
        return view('albums.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'cover_image' => 'image|max:1999',
        ]);

        // Get Filename with extension
        $filenamewithext = $request->file('cover_image')->getClientOriginalName();

        // Get just the filename
        $filenanme = pathinfo($filenamewithext, PATHINFO_FILENAME);

        // Get Extension
        $extension = $request->file('cover_image')->getClientOriginalExtension();

        // Create new filename
        $filenameToStore = $filenanme . '_' . time() . '.' . $extension;

        // Upload Image
        $path = $request->file('cover_image')->storeAs('public/album_covers', $filenameToStore);

        return $path;
    }
}