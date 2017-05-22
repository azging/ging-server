<?php

namespace QuestionBundle\Entity\Wrapper;

use UtilBundle\Container\UtilService;

class AnswerListWrapper implements \JsonSerializable {
    /**
     * @var array
     */
    private $wrapperList;

    /**
     * @var $string
     */
    private $orderStr;

    public function __construct() {
        $this->wrapperList = array();
        $this->orderStr = '';
    }

    public function setAnswerWrapperList($list) {
        $this->wrapperList = $list;

        return $this;
    }

    public function setOrderStr($orderStr) {
        $this->orderStr = $orderStr;

        return $this;
    }

    public function jsonSerialize() {
        $arr = array(
            "AnswerWrapperList" => $this->wrapperList,
            'OrderStr' => strval($this->orderStr),
        );
        return UtilService::getNotNullValueArray($arr);
    }
}
