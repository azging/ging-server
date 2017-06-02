<?php

namespace UtilBundle\Entity\Wrapper;

use UtilBundle\Container\UtilService;

class TokenWrapper implements \JsonSerializable {
    /**
    * @var string
    */
    protected $upToken;

    /**
    * @var string
    */
    protected $photoKey;

    public function __construct() {
        $this->upToken = "";
        $this->photoKey = "";
    }

    public function setUpToken($str) {
        $this->upToken = $str;

        return $this;
    }
    
    public function setPhotoKey($str) {
        $this->photoKey = $str;

        return $this;
    }

    public function jsonSerialize() {
        $arr = array(
            "UpToken" => $this->upToken, 
            "PhotoKey" => $this->photoKey
        );
        return UtilService::getNotNullValueArray($arr);
    }
}
