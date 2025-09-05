<?php

namespace App\Repositories;

use App\Interfaces\GalleryInterface;
use App\Models\Gallery;
use App\Traits\UploadAble;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class GalleryRepository implements GalleryInterface 
{
    use UploadAble;

    public function listAll() 
    {
        return Gallery::all();
    }

    public function listById($id) 
    {
        return Gallery::findOrFail($id);
    }

    public function delete($id) 
    {
        Gallery::destroy($id);
    }

    public function create(array $data) 
    {
        $upload_path = "public/uploads/gallery/";
        $collection = collect($data);

        $gallery = new Gallery;

        // image
        $image = $collection['image'];
        $imageName = time().".".mt_rand().".".$image->getClientOriginalName();
        $image->move($upload_path, $imageName);
        $uploadedImage = $imageName;
        $gallery->image = $upload_path.$uploadedImage;

        $gallery->save();

        return $gallery;
    }

    public function update($id, array $newDetails) 
    {
        $upload_path = "public/uploads/gallery/";
        $gallery = Gallery::findOrFail($id);
        $collection = collect($newDetails); 

        if (isset($newDetails['image'])) {
            // dd('here');
            $image = $collection['image'];
            $imageName = time().".".mt_rand().".".$image->getClientOriginalName();
            $image->move($upload_path, $imageName);
            $uploadedImage = $imageName;
            $gallery->image = $upload_path.$uploadedImage;
        }

        $gallery->save();

        return $gallery;
    }

    public function toggle($id){
        $gallery = Gallery::findOrFail($id);

        $status = ( $gallery->status == 1 ) ? 0 : 1;
        $gallery->status = $status;
        $gallery->save();

        return $gallery;
    }
}