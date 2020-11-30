<?php

class FriendshipStatus {

    private $statusCode;
    private $description;
    
    function __construct($statusCode, $description) {
        $this->statusCode = $statusCode;
        $this->description = $description;
    }

    function getStatusCode() {
        return $this->statusCode;
    }

    function getDescription() {
        return $this->description;
    }

    function setStatusCode($statusCode): void {
        $this->statusCode = $statusCode;
    }

    function setDescription($description): void {
        $this->description = $description;
    }

}
?>