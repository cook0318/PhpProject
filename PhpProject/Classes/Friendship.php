<?php

class Friendship {
    
    private $friendRequesterId;
    private $friendRequesteeId;
    private $status;
    
    function __construct($friendRequesterId, $friendRequesteeId, $status) {
        $this->friendRequesterId = $friendRequesterId;
        $this->friendRequesteeId = $friendRequesteeId;
        $this->status = $status;
    }
    
    function getFriendRequesterId() {
        return $this->friendRequesterId;
    }

    function getFriendRequesteeId() {
        return $this->friendRequesteeId;
    }

    function getStatus() {
        return $this->status;
    }

    function setFriendRequesterId($friendRequesterId): void {
        $this->friendRequesterId = $friendRequesterId;
    }

    function setFriendRequesteeId($friendRequesteeId): void {
        $this->friendRequesteeId = $friendRequesteeId;
    }

    function setStatus($status): void {
        $this->status = $status;
    }

}
?>
