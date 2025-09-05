<?php

namespace App\Interfaces;

interface ColorInterface
{
    /**
     * This method is to fetch list of all collections
     */
    public function getAllColor();

    /**
     * This method is to fetch list of all search collections
     * @param str $term
     */
    public function getSearchColor(string $term);
    /**
     * This method is to fetch list of all sizes
     */
    public function getAllSizes();

    /**
     * This method is to fetch list of all colors
     */
    public function getAllColors();

    /**
     * This method is to get collection details by id
     * @param str $collectionId
     */
    public function getColorById($collectionId);

   

    /**
     * This method is to create collection
     * @param arr $collectionDetails
     */
    public function createColor(array $collectionDetails);

    /**
     * This method is to update collection details
     * @param str $collectionId
     * @param arr $newDetails
     */
    public function updateColor($collectionId, array $newDetails);

    /**
     * This method is to toggle collection status
     * @param str $collectionId
     */
    public function toggleStatus($collectionId);

    
}