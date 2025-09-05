<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\GalleryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
// use Illuminate\Support\Str;
// use App\Models\Gallery;

class GalleryController extends Controller
{
    // private GalleryInterface $galleryRepository;

    public function __construct(GalleryInterface $galleryRepository) 
    {
        $this->galleryRepository = $galleryRepository;
    }

    public function index(Request $request) 
    {
        $data = $this->galleryRepository->listAll();
        return view('admin.gallery.index', compact('data'));
    }

    public function store(Request $request) 
    {
        $request->validate([
            "image" => "required|mimes:jpg,jpeg,png,svg,gif|max:10000000",
        ]);

        $params = $request->except('_token');

        $galleryStore = $this->galleryRepository->create($params);

        if ($galleryStore) {
            return redirect()->route('admin.gallery.index');
        } else {
            return redirect()->route('admin.gallery.create')->withInput($request->all());
        }
    }

    public function show(Request $request, $id)
    {
        $data = $this->galleryRepository->listById($id);
        return view('admin.gallery.detail', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            "image" => "nullable|mimes:jpg,jpeg,png,svg,gif|max:10000000",
        ]);

        $params = $request->except('_token');

        $galleryStore = $this->galleryRepository->update($id, $params);

        if ($galleryStore) {
            return redirect()->route('admin.gallery.index');
        } else {
            return redirect()->route('admin.gallery.create')->withInput($request->all());
        }
    }

    public function status(Request $request, $id)
    {
        $galleryStat = $this->galleryRepository->toggle($id);

        if ($galleryStat) {
            return redirect()->route('admin.gallery.index');
        } else {
            return redirect()->route('admin.gallery.create')->withInput($request->all());
        }
    }

    public function destroy(Request $request, $id) 
    {
        $this->galleryRepository->delete($id);

        return redirect()->route('admin.gallery.index');
    }
}
