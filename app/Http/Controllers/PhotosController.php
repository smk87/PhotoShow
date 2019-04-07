<?php

namespace App\Http\Controllers;

use App\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhotosController extends Controller
{
    public function create($album_id)
    {
        return view('photos.create')->with('album_id', $album_id);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'photo' => 'image|max:1999',
        ]);

// Get Filename with extension
        $filenamewithext = $request->file('photo')->getClientOriginalName();

// Get just the filename
        $filenanme = pathinfo($filenamewithext, PATHINFO_FILENAME);

// Get Extension
        $extension = $request->file('photo')->getClientOriginalExtension();

// Create new filename
        $filenameToStore = $filenanme . '_' . time() . '.' . $extension;

// Upload Image
        $path = $request->file('photo')->storeAs('public/photos/' . $request->input('album_id'), $filenameToStore);

// Create Album
        $photo = new Photo;
        $photo->album_id = $request->input('album_id');
        $photo->title = $request->input('title');
        $photo->description = $request->input('description');
        $photo->size = $request->file('photo')->getClientSize();
        $photo->photo = $filenameToStore;

        $photo->save();

        return redirect('/albums/' . $request->input('album_id'))->with('success', "Photo Uploaded.");

    }

    public function show($id)
    {
        $photo = Photo::find($id);
        return view('photos.show')->with('photo', $photo);
    }

    public function destroy($id)
    {
        $photo = Photo::find($id);

        if (Storage::delete('public/photos/' . $photo->album_id . '/' . $photo->photo)) {
            $photo->delete();

            return redirect('/')->with('success', 'Photo Deleted.');
        }
    }
}