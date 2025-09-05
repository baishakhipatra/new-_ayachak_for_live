<?php

namespace App\Repositories;

use App\Interfaces\FaqInterface;
use App\Models\Faq;
use Illuminate\Support\Facades\Hash;

class FaqRepository implements FaqInterface 
{
    public function listAll() 
    {
        return Faq::all();
    }

    public function listById($id) 
    {
        return Faq::findOrFail($id);
    }

    public function create(array $data) 
    {
        $collectedData = collect($data);
        $newEntry = new Faq;
        $newEntry->question = $collectedData['question'];
        $newEntry->answer = $collectedData['answer'];
        $newEntry->save();

        return $newEntry;
    }

    public function update($id, array $newDetails) 
    {
        $updatedEntry = Faq::findOrFail($id);
        $collectedData = collect($newDetails);
        $updatedEntry->question = $collectedData['question'];
        $updatedEntry->answer = $collectedData['answer'];
        $updatedEntry->save();

        return $updatedEntry;
    }

    public function toggle($id){
        $updatedEntry = Faq::findOrFail($id);

        $status = ( $updatedEntry->status == 1 ) ? 0 : 1;
        $updatedEntry->status = $status;
        $updatedEntry->save();

        return $updatedEntry;
    }

    public function delete($id) 
    {
        Faq::destroy($id);
    }
}