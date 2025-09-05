<?php

namespace App\Interfaces;

interface OrderInterface
{
    public function listAll();
    public function listById($id);
    public function listByStatus($status);
    public function searchOrder(string $term, string $from, string $to,string  $ptype, string $status = null);
    public function create(array $data);
    public function update($id, array $data);
    public function toggle($id, $status);
    // public function delete($id);
}