<?php

namespace App\Repositories;

use App\Interfaces\TransactionInterface;
use App\Models\Transaction;
use App\Traits\UploadAble;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class TransactionRepository implements TransactionInterface 
{
    use UploadAble;

    public function listAll() 
    {
        return Transaction::latest('id')->paginate(25);
    }

    public function listById($id) 
    {
        return Transaction::findOrFail($id);
    }

    public function create(array $data) 
    {
        DB::beginTransaction();

        try {
            $collectedData = collect($data);
            $newEntry = new Transaction;
            $newEntry->user_id = $collectedData['user_id'];
            $newEntry->order_id = $collectedData['order_id'];
            $newEntry->transaction = $collectedData['transaction'];
            $newEntry->amount = $collectedData['amount'];
            $newEntry->currency = $collectedData['currency'];
            $newEntry->method = $collectedData['method'];
            $newEntry->description = $collectedData['description'];
            $newEntry->bank = $collectedData['bank'];
            $newEntry->upi = $collectedData['upi'];

            DB::commit();
            return $newEntry;
        } catch (\Throwable $th) {
            // throw $th;
            DB::rollback();
        }
    }
}