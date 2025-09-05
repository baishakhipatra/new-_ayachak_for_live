<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface CategoryInterface
{
    /**
     * This method is to fetch list of all categories
     */
    public function getAllCategories();

    /**
     * This method is to find searched data
     * @param str $term
     */
    public function getSearchCategories(string $term);

    // public function deleteCategories(string $delete_ids);

    /**
     * This method is to fetch list of all sizes
     */
    public function getAllSizes();

    /**
     * This method is to fetch list of all colors
     */
    public function getAllColors();

    /**
     * This method is to get category details by id
     * @param str $categoryId
     */
    public function getCategoryById($categoryId);

    /**
     * This method is to get category details by slug
     * @param str $slug
     */
    public function getCategoryBySlug($slug);

    /**
     * This method is to create category
     * @param arr $categoryDetails
     */
    public function createCategory(array $categoryDetails);

    /**
     * This method is to update category details
     * @param int $categoryId
     * @param arr $newDetails
     */
    public function updateCategory($categoryId, array $newDetails);

    /**
     * This method is to toggle category status
     * @param int $categoryId
     */
    public function toggleStatus($categoryId);

    /**
     * This method is to delete category
     * @param int $categoryId
     */
    public function deleteCategory($categoryId);

    /**
     * This method is to delete category
     * @param int $categoryId
     * @param array $filter
     */
    public function productsByCategory(int $categoryId, array $filter = null);
}