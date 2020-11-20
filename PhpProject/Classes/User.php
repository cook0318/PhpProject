<?php

class User {
    
    private $userID;
    private $name;
    private $phone;
    private $password;
    
    function __construct($UserId, $Name, $Phone, $Password) {
        $this->userID = $UserId;
        $this->name = $Name;
        $this->phone = $Phone;
        $this->password = $Password;
    }

    function getUserId() {
        return $this->userID;
    }

    function getName() {
        return $this->name;
    }

    function getPhone() {
        return $this->phone;
    }

    function getPassword() {
        return $this->password;
    }

    function setUserId($UserId): void {
        $this->userID = $UserId;
    }

    function setName($Name): void {
        $this->name = $Name;
    }

    function setPhone($Phone): void {
        $this->phone = $Phone;
    }

    function setPassword($Password): void {
        $this->password = $Password;
    }

}    
?>
