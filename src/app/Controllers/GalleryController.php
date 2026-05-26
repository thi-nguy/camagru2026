<?php

class GalleryController
{
    public function __construct(private PhotoModel $photoModel) {}

    public function showGallery()
    {
        $currentPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
        $perPage = 20;
        $totalPhoto = $this->photoModel->countAllPhotos();
        $totalPages = ceil($totalPhoto / $perPage);
        if ($currentPage === false || $currentPage === null || $currentPage <= 0 || $currentPage > $totalPages) {
            $currentPage = 1;
        }
        $photoOnOnePage = $this->photoModel->getAllPhotos($currentPage, $perPage);
        $dataForView = ['photos' => $photoOnOnePage, 'numberOfPage' => $totalPages, 'page' => $currentPage];
        render("GalleryView", $dataForView);
    }
}
