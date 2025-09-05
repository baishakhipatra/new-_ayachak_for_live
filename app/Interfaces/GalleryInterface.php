<?php

namespace App\Interfaces;

interface GalleryInterface 
{
    /**
     * This method is to fetch list of all images from gallery
     */
    public function listAll();

    /**
     * This method is to get image details by id
     * @param str $id
     */
    public function listById($id);

    /**
     * This method is to add new image to gallery
     * @param arr $data
     */
    public function create(array $data);

    /**
     * This method is to update gallery details
     * @param str $id
     * @param arr $newDetails
     */
    public function update($id, array $newDetails);

    /**
     * This method is to toggle gallery status
     * @param str $id
     */
    public function toggle($id);

    /**
     * This method is to delete gallery
     * @param str $id
     */
    public function delete($id);
}