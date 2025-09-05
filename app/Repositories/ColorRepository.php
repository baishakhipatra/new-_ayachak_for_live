<?php

namespace App\Repositories;

use App\Interfaces\ColorInterface;
use App\Models\Collection;
use App\Models\Size;
use App\Models\Color;
use App\Models\Product;
use App\Traits\UploadAble;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ColorRepository implements ColorInterface
{
    use UploadAble;

    public function getAllColor()
    {
        return Color::all();
    }

    public function getSearchColor(string $term)
    {
        return Color::where([['name', 'LIKE', '%' . $term . '%']])->orWhere([['code', 'LIKE', '%' . $term . '%']])->get();
    }

    public function getAllSizes()
    {
        return Size::all();
    }

    public function getAllColors()
    {
        return Color::all();
    }

    public function getColorById($colorId)
    {
        return Color::findOrFail($colorId);
    }

    
    public function createColor(array $data)
    {
        $collection = collect($data);

        $modelDetails = new Color;
        $modelDetails->name = $collection['name'];
        $modelDetails->code = $collection['code'];
        $modelDetails->save();

        return $modelDetails;
    }

    public function updateColor($id, array $newDetails)
    {
        $modelDetails = Color::findOrFail($id);
        $collection = collect($newDetails);
        // dd($newDetails);

        $modelDetails->name = $collection['name'];
        $modelDetails->code = $collection['code'];

        $modelDetails->save();

        return $modelDetails;
    }

    public function toggleStatus($id)
    {
        $collection = Color::findOrFail($id);

        $status = ($collection->status == 1) ? 0 : 1;
        $collection->status = $status;
        $collection->save();

        return $collection;
    }

}
