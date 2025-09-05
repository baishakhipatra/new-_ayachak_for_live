<?php

namespace App\Repositories;

use App\Interfaces\CouponInterface;
use App\Models\Coupon;
use App\Models\CouponUsage;
use Illuminate\Support\Str;

class CouponRepository implements CouponInterface
{
    public function listAllCoupons()
    {
        return Coupon::where('is_coupon', 1)->latest('id')->get();
    }

    public function getSearchCoupons(string $term)
    {
        return Coupon::where('is_coupon', 1)->where([['name', 'LIKE', '%' . $term . '%']])->orWhere('coupon_code', '=', $term)->get();
    }

    public function listAllVouchers()
    {
        return Coupon::where('is_coupon', 0)->where('name', 'not like', 'dummy%')->latest('id')->groupBy('name')->get();
        // return Coupon::where('is_coupon', 0)->latest('id')->groupBy('name')->get();
    }

    public function getSearchVouchers(string $term)
    {
        return Coupon::where('is_coupon', 0)->where([['name', 'LIKE', '%' . $term . '%']])->get();
    }

    public function listById($id)
    {
        return Coupon::findOrFail($id);
    }

    public function usageById($id)
    {
        return CouponUsage::where('coupon_code_id', $id)->get();
    }

    public function create(array $data)
    {
        $collectedData = collect($data);

        $newEntry = new Coupon;
        $newEntry->name = $collectedData['name'];
        $newEntry->coupon_code = $collectedData['coupon_code'];
        $newEntry->is_coupon = $collectedData['is_coupon'];
        if (!empty($collectedData['type'])) {
            $newEntry->type = $collectedData['type'];
        }
        $newEntry->amount = $collectedData['amount'];
        $newEntry->max_time_of_use = $collectedData['max_time_of_use'];
        $newEntry->max_time_one_can_use = $collectedData['max_time_one_can_use'];
        $newEntry->start_date = $collectedData['start_date'];
        $newEntry->end_date = $collectedData['end_date'];
        $newEntry->save();

        return $newEntry;
    }

    public function createVoucher(array $data)
    {
        function generateUniqueAlphaNumeric($length = 10) {
            $random_string = '';
            for ($i = 0; $i < $length; $i++) {
                $number = random_int(0, 36);
                $character = base_convert($number, 10, 36);
                $random_string .= $character;
            }
            return $random_string;
        }

        $collectedData = collect($data);

        $noOfEntries = $collectedData['generate_number'];

        // generate slug
        $slug = Str::slug($collectedData['name'], '-');
        $slugExistCount = Coupon::where('slug', $slug)->count();
        if ($slugExistCount > 0) $slug = $slug . '-' . ($slugExistCount + 1);

        for($i = 0; $i < $noOfEntries; $i++) {
            $newEntry = new Coupon;
            $newEntry->name = $collectedData['name'];
            $newEntry->coupon_code = strtoupper(generateUniqueAlphaNumeric(10));
            $newEntry->is_coupon = $collectedData['is_coupon'];
            if (!empty($collectedData['type'])) {
                $newEntry->type = $collectedData['type'];
            }
            $newEntry->amount = $collectedData['amount'];
            $newEntry->max_time_of_use = $collectedData['max_time_of_use'];
            $newEntry->max_time_one_can_use = $collectedData['max_time_one_can_use'];
            $newEntry->start_date = $collectedData['start_date'];
            $newEntry->end_date = $collectedData['end_date'];

            $newEntry->slug = $slug;

            $newEntry->save();
        }

        return true;
    }

    public function update($id, array $newDetails)
    {
        $updatedEntry = Coupon::findOrFail($id);
        $collectedData = collect($newDetails);
        // dd($newDetails);

        $updatedEntry->name = $collectedData['name'];
        $updatedEntry->coupon_code = $collectedData['coupon_code'];
        if (!empty($collectedData['type'])) {
            $updatedEntry->type = $collectedData['type'];
        }
        $updatedEntry->amount = $collectedData['amount'];
        $updatedEntry->max_time_of_use = $collectedData['max_time_of_use'];
        $updatedEntry->max_time_one_can_use = $collectedData['max_time_one_can_use'];
        $updatedEntry->start_date = $collectedData['start_date'];
        $updatedEntry->end_date = $collectedData['end_date'];

        $updatedEntry->save();

        return $updatedEntry;
    }

    public function toggle($id)
    {
        $updatedEntry = Coupon::findOrFail($id);

        $status = ($updatedEntry->status == 1) ? 0 : 1;
        $updatedEntry->status = $status;
        $updatedEntry->save();

        return $updatedEntry;
    }

    public function delete($id)
    {
        Coupon::destroy($id);
    }
}