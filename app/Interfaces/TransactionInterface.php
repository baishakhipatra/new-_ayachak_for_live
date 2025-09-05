<?php

namespace App\Interfaces;

interface TransactionInterface 
{
    public function listAll();
    public function listById($id);
    public function create(array $data);
}