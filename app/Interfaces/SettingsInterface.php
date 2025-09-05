<?php

namespace App\Interfaces;

interface SettingsInterface
{
    public function listAll();
    public function getSearchSettings(string $term);
    public function listById($id);
    public function update($id, array $data);
}