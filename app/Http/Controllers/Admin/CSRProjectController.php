<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CSRProject;
use App\Models\Tag;

class CSRProjectController extends Controller
{

    public function index()
    {
        $projects = CSRProject::with('tags')->get();
        return view('admin.csr_projects.index', compact('projects'));
    }

    public function create()
    {
        $tags = Tag::all();
        return view('admin.csr_projects.create', compact('tags'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required',
            'description' => 'nullable',
            'image' => 'nullable|image',
            'file' => 'nullable|file',
            'tags' => 'array'
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('images', 'public');
        }

        if ($request->hasFile('file')) {
            $data['file'] = $request->file('file')->store('files', 'public');
        }

        $project = CSRProject::create($data);

        if ($request->tags) {
            $project->tags()->sync($request->tags);
        }

        return redirect()->route('admin.csr_projects.index')->with('success', 'Project created successfully!');
    }

    public function edit($id)
    {
        $project = CSRProject::with('tags')->findOrFail($id);
        $tags = Tag::all();
        return view('admin.csr_projects.edit', compact('project', 'tags'));
    }

    public function update(Request $request, $id)
    {
        $project = CSRProject::findOrFail($id);

        $data = $request->validate([
            'title' => 'required',
            'description' => 'nullable',
            'image' => 'nullable|image',
            'file' => 'nullable|file',
            'tags' => 'array'
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('images', 'public');
        }

        if ($request->hasFile('file')) {
            $data['file'] = $request->file('file')->store('files', 'public');
        }

        $project->update($data);

        if ($request->tags) {
            $project->tags()->sync($request->tags);
        } else {
            $project->tags()->detach();
        }

        return redirect()->route('admin.csr_projects.index')->with('success', 'Project updated successfully!');
    }

    public function delete(Request $request){
        $user = CSRProject::find($request->id); 
    
        if (!$user) {
            return response()->json([
                'status'    => 404,
                'message'   => 'CSR Project not found.',
            ]);
        }
    
        $user->delete(); 
        return response()->json([
            'status'    => 200,
            'message'   => 'CSR Project deleted successfully.',
        ]);
    }
    
}
