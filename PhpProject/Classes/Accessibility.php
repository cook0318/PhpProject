<?php

class Accessibility {
    
    private $accessibilityCode;
    private $description;
    
    function __construct($accessibilityCode, $description) {
        $this->accessibilityCode = $accessibilityCode;
        $this->description = $description;
    }
    
    function getAccessibilityCode() {
        return $this->accessibilityCode;
    }

    function getDescription() {
        return $this->description;
    }

    function setAccessibilityCode($accessibilityCode): void {
        $this->accessibilityCode = $accessibilityCode;
    }

    function setDescription($description): void {
        $this->description = $description;
    }
 
}
