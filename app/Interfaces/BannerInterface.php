<?php

namespace App\Interfaces;

interface BannerInterface 
{
    public function listAll();
    public function listById($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}