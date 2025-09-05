<?php

namespace App\Interfaces;

interface FaqInterface 
{
    public function listAll();
    public function listById($id);
    public function create(array $data);
    public function update($id, array $data);
    public function toggle($id);
    public function delete($id);
}