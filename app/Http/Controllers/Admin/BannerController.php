<?php

namespace App\Http\Controllers\Admin;

use App\Interfaces\BannerInterface;
use App\Models\Banner;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class BannerController extends Controller
{
    public function __construct(BannerInterface $bannerInterface) 
    {
        $this->bannerRepository = $bannerInterface;
    }

    public function index(Request $request) 
    {
        $data = $this->bannerRepository->listAll();
        return view('admin.banner.index', compact('data'));
    }

    public function store(Request $request) 
    {
        $request->validate([
            "title"         => "required|string|max:255",
            "sub_title"     => "nullable|string|max:255",
            "description"   => "nullable|string",
            "banner_image"  => "required|mimes:jpg,jpeg,png,svg,gif,webp|max:10000000",
            "banner_videos" => "nullable|mimes:mp4,avi,mov,webm|max:200000", 
        ]);

        $params = $request->except('_token');
        $storeData = $this->bannerRepository->create($params);

        if ($storeData) {
            return redirect()->route('admin.banner.index')->with('success', 'Banner created successfully!');
        } else {
            return redirect()->route('admin.banner.create')->withInput($request->all())->with('error', 'Something went wrong.');
        }
    }

    public function show(Request $request, $id)
    {
        $data = $this->bannerRepository->listById($id);
        return view('admin.banner.detail', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            "title"         => "nullable|string|max:255",
            "sub_title"     => "nullable|string|max:255",
            "description"   => "nullable|string",
            "banner_image"  => "nullable|mimes:jpg,jpeg,png,svg,gif,webp|max:10000000",
            "banner_videos" => "nullable|mimes:mp4,avi,mov,webm|max:200000",
        ]);

        $params = $request->except('_token');
        $updateData = $this->bannerRepository->update($id, $params);

        if ($updateData) {
            return redirect()->route('admin.banner.index')->with('success', 'Banner updated successfully!');
        } else {
            return redirect()->back()->withInput($request->all())->with('error', 'Update failed!');
        }
    }


    public function destroy(Request $request)
    {
        $id = $request->id;

        $this->bannerRepository->delete($id);

        return response()->json([
            'status' => 200,
            'message' => 'Banner deleted successfully!'
        ]);
    }

}
