<?php

namespace App\Repositories;

use App\Interfaces\BannerInterface;
use App\Models\BannerTitle;

class BannerRepository implements BannerInterface 
{
    public function listAll() 
    {
        return BannerTitle::orderBy('id','desc')->get();
    }

    public function listById($id) 
    {
        return BannerTitle::findOrFail($id);
    }

    public function create(array $data) 
    {
        $upload_path = "uploads/banner/";
        $banner = new BannerTitle;

        $banner->title       = $data['title'] ?? null;
        $banner->sub_title   = $data['sub_title'] ?? null;
        $banner->description = $data['description'] ?? null;

        if (isset($data['banner_image'])) {
            $image      = $data['banner_image'];
            $imageName  = time()."_".uniqid().".".$image->getClientOriginalExtension();
            $image->move(public_path($upload_path), $imageName);
            $banner->banner_image = $upload_path.$imageName;
        }

  
        if (!empty($data['banner_videos'])) {
            $video      = $data['banner_videos'];
            $videoName  = time()."_".uniqid().".".$video->getClientOriginalExtension();
            $video->move(public_path($upload_path), $videoName);
            $banner->banner_videos = $upload_path.$videoName;
        }

        $banner->save();

        return $banner;
    }

    public function update($id, array $newDetails) 
    {
        $upload_path = "uploads/banner/";
        $banner = BannerTitle::findOrFail($id);

        $banner->title       = $newDetails['title'] ?? $banner->title;
        $banner->sub_title   = $newDetails['sub_title'] ?? $banner->sub_title;
        $banner->description = $newDetails['description'] ?? $banner->description;

        // Update banner image if uploaded
        if (isset($newDetails['banner_image'])) {
            $image      = $newDetails['banner_image'];
            $imageName  = time()."_".uniqid().".".$image->getClientOriginalExtension();
            $image->move(public_path($upload_path), $imageName);
            $banner->banner_image = $upload_path.$imageName;
        }

        // Update banner video if uploaded
        if (isset($newDetails['banner_videos'])) {
            $video      = $newDetails['banner_videos'];
            $videoName  = time()."_".uniqid().".".$video->getClientOriginalExtension();
            $video->move(public_path($upload_path), $videoName);
            $banner->banner_videos = $upload_path.$videoName;
        }

        $banner->save();

        return $banner;
    }

    public function delete($id) 
    {
        BannerTitle::destroy($id);
    }

}