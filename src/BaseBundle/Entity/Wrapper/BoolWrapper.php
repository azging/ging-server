<?php

namespace BaseBundle\Entity\Wrapper;

class BoolWrapper implements \JsonSerializable {
    /**
    * @var bool
    */
    protected $isPass;

    public function __construct() {
        $this->isPass = false;
    }

    public function setIsPass($isPass) {
        $this->isPass = $isPass;

        return $this;
    }

    public function jsonSerialize() {
        return array("IsPass" => (bool)$this->isPass);
    }
}
