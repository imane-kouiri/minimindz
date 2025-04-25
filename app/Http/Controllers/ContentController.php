<?php

namespace App\Http\Controllers;

use App\Models\Content;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // Show all uploaded contents
    public function index()
    {
        $contents = \App\Models\Content::all()->groupBy('category');       
        return view('contents.index', compact('contents'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    return view('contents.create');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    $request->validate([
        'title' => 'required',
        'category' => 'required',
        'media_type' => 'required',
        'media_path' => 'required|file',
    ]);

    $filePath = $request->file('media_path')->store('uploads', 'public');

    Content::create([
        'title' => $request->title,
        'category' => $request->category,
        'media_type' => $request->media_type,
        'media_path' => $filePath,
    ]);

    return redirect()->route('contents.index')->with('success', 'Content added successfully!');
    }


    /**
     * Display the specified resource.
     */
    public function show(Content $content)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
    $content = Content::findOrFail($id);
    return view('contents.edit', compact('content'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
    $request->validate([
        'title' => 'required',
        'category' => 'required',
        'media_type' => 'required',
    ]);

    $content = Content::findOrFail($id);
    $content->update($request->only(['title', 'category', 'media_type']));

    return redirect()->route('contents.index')->with('success', 'Content updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
    $content = Content::findOrFail($id);
    $content->delete();
    return redirect()->route('contents.index')->with('success', 'Content deleted');
    }
}
