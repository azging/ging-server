<?php

namespace WalletBundle\Entity\Wrapper;

use UtilBundle\Container\UtilService;

use UserBundle\Entity\User;
use WalletBundle\Entity\WalletOrder;

class OrderWrapper implements \JsonSerializable {
    /**
    * @var WalletOrder
    */
    private $order;
    
    /**
    * @var String
    */
    private $balancePrepay;
    
    /**
    * @var WxPrepay
    */
    private $wxPrepay;
    
    /**
    * @var String
    */
    private $aliOrderString;
    
    /**
    * @var User
    */
    private $user;

    public function __construct() {
        $this->order = new WalletOrder();
        $this->balancePrepay = "";
        $this->aliOrderString = "";
    }

    public function setOrder($order) {
        $this->order = $order;

        return $this;
    }

    public function setWxPrepay($wxPrepay) {
        $this->wxPrepay = $wxPrepay;

        return $this;
    }

    public function setBalancePrepay($balancePrepay) {
        $this->balancePrepay = $balancePrepay;

        return $this;
    }

    public function setAliOrderString($aliOrderString) {
        $this->aliOrderString = $aliOrderString;

        return $this;
    }

    public function setUser($user) {
        $this->user = $user;

        return $this;
    }

    public function jsonSerialize() {
        $arr = array(
            'Order' => $this->order,
            'WxPrepay' => $this->wxPrepay,
            'BalancePrepay' => $this->balancePrepay,
            'CreateUser' => $this->user,
        );
        return UtilService::getNotNullValueArray($arr);
    }
}
