<?php

class Picture {
    
    private $pictureId;
    private $albumId;
    private $fileName;
    private $title;
    private $description;
    private $dateAdded;
    
    function __construct($pictureId, $albumId, $fileName, $title, $description, $dateAdded) {
        $this->pictureId = $pictureId;
        $this->albumId = $albumId;
        $this->fileName = $fileName;
        $this->title = $title;
        $this->description = $description;
        $this->dateAdded = $dateAdded;
    }
    
    function getPictureId() {
        return $this->pictureId;
    }

    function getAlbumId() {
        return $this->albumId;
    }

    function getFileName() {
        return $this->fileName;
    }

    function getTitle() {
        return $this->title;
    }

    function getDescription() {
        return $this->description;
    }

    function getDateAdded() {
        return $this->dateAdded;
    }

    function setPictureId($pictureId): void {
        $this->pictureId = $pictureId;
    }

    function setAlbumId($albumId): void {
        $this->albumId = $albumId;
    }

    function setFileName($fileName): void {
        $this->fileName = $fileName;
    }

    function setTitle($title): void {
        $this->title = $title;
    }

    function setDescription($description): void {
        $this->description = $description;
    }

    function setDateAdded($dateAdded): void {
        $this->dateAdded = $dateAdded;
    }
    
    function getAlbumFilePath(){
        return ALBUM_PICTURES_DIR."/".$this->fileName;
    }
    
    function getThumbnailFilePath(){
        return ALBUM_THUMBNAILS_DIR."/".$this->fileName;
    }

    function getOriginalFilePath(){
        return ORIGINAL_PICTURES_DIR."/".$this->fileName;
    }
}
?>