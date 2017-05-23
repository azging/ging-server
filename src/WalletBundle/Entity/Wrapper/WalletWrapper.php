<?php

namespace WalletBundle\Entity\Wrapper;

use UtilBundle\Container\UtilService;

class WalletWrapper implements \JsonSerializable {
    /** 
    * @var float
    */
    private $balance;

    public function __construct() {
        $this->balance = 0;
    }

    public function setBalance($balance) {
        $this->balance = $balance;

        return $this;
    }

    public function jsonSerialize() {
        $arr = array(
            'Balance' => $this->balance,
        );
        return UtilService::getNotNullValueArray($arr);
    }
}
