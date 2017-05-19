<?php

namespace UserBundle\Entity\Wrapper;

use UserBundle\Entity\User;
use UtilBundle\Container\UtilService;

class UserWrapper implements \JsonSerializable {
    /** 
    * @var User
    */
    private $user;

    public function __construct() {
        $this->user = new User();
    }

    public function setUser($user) {
        $this->user = $user;

        return $this;
    }

    public function jsonSerialize() {
        $arr = array(
            'User' => $this->user,
        );
        return UtilService::getNotNullValueArray($arr);
    }
}
