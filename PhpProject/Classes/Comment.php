<?php

class Comment {

    private $commentId;
    private $authorId;
    private $pictureId;
    private $commentText;
    private $date;
    
    function __construct($commentId, $authorId, $pictureId, $commentText, $date) {
        $this->comment = $commentId;
        $this->authorId = $authorId;
        $this->pictureId = $pictureId;
        $this->commentText = $commentText;
        $this->date = $date;
    }

    function getCommentId() {
        return $this->commentId;
    }

    function getAuthorId() {
        return $this->authorId;
    }

    function getPictureId() {
        return $this->pictureId;
    }

    function getCommentText() {
        return $this->commentText;
    }

    function getDate() {
        return $this->date;
    }

    function setCommentId($commentId): void {
        $this->commentId = $commentId;
    }

    function setAuthorId($authorId): void {
        $this->authorId = $authorId;
    }

    function setPictureId($pictureId): void {
        $this->pictureId = $pictureId;
    }

    function setCommentText($commentText): void {
        $this->commentText = $commentText;
    }

    function setDate($date): void {
        $this->date = $date;
    }

}
?>
