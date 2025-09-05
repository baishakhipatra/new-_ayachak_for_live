<?php

namespace App\Repositories;

use App\Interfaces\SizeInterface;
// use App\Models\Collection;
// use App\Models\Color;
// use App\Models\Size;
// use App\Models\Product;
// use App\Traits\UploadAble;
// use Illuminate\Http\UploadedFile;
// use Illuminate\Support\Str;

class SizeRepository implements SizeInterface
{
    // use UploadAble;

    public function getAllSize()
    {
        return Size::all();
    }

    // public function getSearchSize(string $term)
    // {
    //     return Size::where([['name', 'LIKE', '%' . $term . '%']])->orWhere([['code', 'LIKE', '%' . $term . '%']])->get();
    // }

    // // public function getAllSizes()
    // // {
    // //     return Size::all();
    // // }

    // public function getAllSizes()
    // {
    //     return Size::all();
    // }

    // public function getSizeById($sizeId)
    // {
    //     return Size::findOrFail($sizeId);
    // }
    
    // public function createSize(array $data)
    // {
    //     $collection = collect($data);

    //     $modelDetails = new Size;
    //     $modelDetails->name = $collection['name'];
    //     $modelDetails->code = $collection['code'];
    //     $modelDetails->save();

    //     return $modelDetails;
    // }

    // public function updateSize($id, array $newDetails)
    // {
    //     $modelDetails = Size::findOrFail($id);
    //     $collection = collect($newDetails);
    //     // dd($newDetails);

    //     $modelDetails->name = $collection['name'];
    //     $modelDetails->code = $collection['code'];

    //     $modelDetails->save();

    //     return $modelDetails;
    // }

    public function toggleStatus($id)
    {
        $collection = Size::findOrFail($id);

        $status = ($collection->status == 1) ? 0 : 1;
        $collection->status = $status;
        $collection->save();

        return $collection;
    }
}
