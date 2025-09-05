<?php

namespace App\Repositories;

use App\Interfaces\SettingsInterface;
use App\Models\Settings;
use Illuminate\Support\Facades\Hash;

class SettingsRepository implements SettingsInterface
{
    public function listAll()
    {
        return Settings::all();
    }
    public function getSearchSettings(string $term)
    {
        return Settings::where([['page_heading', 'LIKE', '%' . $term . '%']])->get();
    }

    public function listById($id)
    {
        return Settings::findOrFail($id);
    }

    public function update($id, array $newDetails)
    {
        $updatedEntry = Settings::findOrFail($id);
        $collectedData = collect($newDetails);
        $updatedEntry->content = $collectedData['content'];
        $updatedEntry->save();

        return $updatedEntry;
    }
}