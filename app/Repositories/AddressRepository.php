<?php

namespace App\Repositories;

use App\Interfaces\AddressInterface;
use App\Models\Address;
use App\User;

class AddressRepository implements AddressInterface
{
    public function listAll()
    {
        return Address::all();
    }
    public function getSearchAddress(string $term)
    {
        return Address::where('address', 'LIKE', '%' . $term . '%')
            ->orWhere('state', 'LIKE', '%' . $term . '%')
            ->orWhere('city', 'LIKE', '%' . $term . '%')
            ->orWhere('landmark','LIKE', '%'.$term.'%')
            ->get();
    }

    public function listUsers()
    {
        return User::all();
    }

    public function listById($id)
    {
        return Address::findOrFail($id);
    }

    public function create(array $data)
    {
        $collectedData = collect($data);
        $newEntry = new Address;
        $newEntry->user_id = $collectedData['user_id'];
        $newEntry->address = $collectedData['address'];
        $newEntry->landmark = $collectedData['landmark'];
        $newEntry->state = $collectedData['state'];
        $newEntry->city = $collectedData['city'];
        $newEntry->pin = $collectedData['pin'];
        $newEntry->save();

        return $newEntry;
    }

    public function update($id, array $newDetails)
    {
        $updatedEntry = Address::findOrFail($id);
        $collectedData = collect($newDetails);
        $updatedEntry->user_id = $collectedData['user_id'];
        $updatedEntry->address = $collectedData['address'];
        $updatedEntry->landmark = $collectedData['landmark'];
        $updatedEntry->state = $collectedData['state'];
        $updatedEntry->city = $collectedData['city'];
        $updatedEntry->pin = $collectedData['pin'];
        $updatedEntry->save();

        return $updatedEntry;
    }

    public function toggle($id)
    {
        $updatedEntry = Address::findOrFail($id);

        $status = ($updatedEntry->status == 1) ? 0 : 1;
        $updatedEntry->status = $status;
        $updatedEntry->save();

        return $updatedEntry;
    }

    public function delete($id)
    {
        Address::destroy($id);
    }
}