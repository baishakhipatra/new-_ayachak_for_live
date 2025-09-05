<?php

namespace App\Interfaces;

interface ProductInterface
{
    public function listAll();
    public function getSearchProducts(string $term);
    public function filteredProducts(string $catagoryfilter = '', string $rangefilter = '', string $term = '');
    public function categoryList();
    public function subCategoryList();
    public function collectionList();
    public function colorList();
    public function colorListByName();
    public function sizeList();
    public function listById($id);
    public function listBySlug($slug);
    public function relatedProducts($id);
    public function listImagesById($id);
    public function create(array $data);
    public function update($id, array $data);
    public function toggle($id);
    public function sale($id);
    public function delete($id);
    public function deleteSingleImage($id);
    public function wishlistCheck($productId);
    public function primaryColorSizes($productId);
}
