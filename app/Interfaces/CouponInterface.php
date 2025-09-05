<?php

namespace App\Interfaces;

interface CouponInterface
{
    public function listAllCoupons();
    public function getSearchCoupons(string $term);
    public function listAllVouchers();
    public function getSearchVouchers(string $term);
    public function listById($id);
    public function usageById($id);
    public function create(array $data);
    public function createVoucher(array $data);
    public function update($id, array $data);
    public function toggle($id);
    public function delete($id);
}