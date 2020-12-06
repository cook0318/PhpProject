<?php

class Album {

    private $albumId;
    private $title;
    private $description;
    private $dateUpdated;
    private $ownerId;
    private $accessibilityCode;
    
    function __construct($albumId, $title, $description, $dateUpdated, $ownerId, $accessibilityCode) {
        $this->albumId = $albumId;
        $this->title = $title;
        $this->description = $description;
        $this->dateUpdated = $dateUpdated;
        $this->ownerId = $ownerId;
        $this->accessibilityCode = $accessibilityCode;
    }
    
    function getAlbumId() {
        return $this->albumId;
    }

    function getTitle() {
        return $this->title;
    }

    function getDescription() {
        return $this->description;
    }

    function getDateUpdated() {
        return $this->dateUpdated;
    }

    function getOwnerId() {
        return $this->ownerId;
    }

    function getAccessibilityCode() {
        return $this->accessibilityCode;
    }

    function setAlbumId($albumId): void {
        $this->albumId = $albumId;
    }

    function setTitle($title): void {
        $this->title = $title;
    }

    function setDescription($description): void {
        $this->description = $description;
    }

    function setDateUpdated($dateUpdated): void {
        $this->dateUpdated = $dateUpdated;
    }

    function setOwnerId($ownerId): void {
        $this->ownerId = $ownerId;
    }

    function setAccessibilityCode($accessibilityCode): void {
        $this->accessibilityCode = $accessibilityCode;
    }
}
?>