<?php

namespace WalletBundle\Entity;

use UtilBundle\Container\TimeUtilService;
use UtilBundle\Container\UtilService;

/**
 * WalletOrder
 */
class WalletOrder implements \JsonSerializable
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $userId;

    /**
     * @var int
     */
    private $targetUserId;

    /**
     * @var int
     */
    private $questionId;

    /**
     * @var int
     */
    private $tradeType;

    /**
     * @var int
     */
    private $paymentType;

    /**
     * @var string
     */
    private $tradeNo;

    /**
     * @var \DateTime
     */
    private $tradeTime;

    /**
     * @var int
     */
    private $isValid;

    /**
     * @var \DateTime
     */
    private $createTime;

    /**
     * @var \DateTime
     */
    private $updateTime;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return WalletOrder
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set targetUserId
     *
     * @param integer $targetUserId
     *
     * @return WalletOrder
     */
    public function setTargetUserId($targetUserId)
    {
        $this->targetUserId = $targetUserId;

        return $this;
    }

    /**
     * Get targetUserId
     *
     * @return int
     */
    public function getTargetUserId()
    {
        return $this->targetUserId;
    }

    /**
     * Set questionId
     *
     * @param integer $questionId
     *
     * @return WalletOrder
     */
    public function setQuestionId($questionId)
    {
        $this->questionId = $questionId;

        return $this;
    }

    /**
     * Get questionId
     *
     * @return int
     */
    public function getQuestionId()
    {
        return $this->questionId;
    }

    /**
     * Set tradeType
     *
     * @param integer $tradeType
     *
     * @return WalletOrder
     */
    public function setTradeType($tradeType)
    {
        $this->tradeType = $tradeType;

        return $this;
    }

    /**
     * Get tradeType
     *
     * @return int
     */
    public function getTradeType()
    {
        return $this->tradeType;
    }

    /**
     * Set paymentType
     *
     * @param integer $paymentType
     *
     * @return WalletOrder
     */
    public function setPaymentType($paymentType)
    {
        $this->paymentType = $paymentType;

        return $this;
    }

    /**
     * Get paymentType
     *
     * @return int
     */
    public function getPaymentType()
    {
        return $this->paymentType;
    }

    /**
     * Set tradeNo
     *
     * @param string $tradeNo
     *
     * @return WalletOrder
     */
    public function setTradeNo($tradeNo)
    {
        $this->tradeNo = $tradeNo;

        return $this;
    }

    /**
     * Get tradeNo
     *
     * @return string
     */
    public function getTradeNo()
    {
        return $this->tradeNo;
    }

    /**
     * Set tradeTime
     *
     * @param \DateTime $tradeTime
     *
     * @return WalletOrder
     */
    public function setTradeTime($tradeTime)
    {
        $this->tradeTime = $tradeTime;

        return $this;
    }

    /**
     * Get tradeTime
     *
     * @return \DateTime
     */
    public function getTradeTime()
    {
        return $this->tradeTime;
    }

    /**
     * Set isValid
     *
     * @param integer $isValid
     *
     * @return WalletOrder
     */
    public function setIsValid($isValid)
    {
        $this->isValid = $isValid;

        return $this;
    }

    /**
     * Get isValid
     *
     * @return int
     */
    public function getIsValid()
    {
        return $this->isValid;
    }

    /**
     * Set createTime
     *
     * @param \DateTime $createTime
     *
     * @return WalletOrder
     */
    public function setCreateTime($createTime)
    {
        $this->createTime = $createTime;

        return $this;
    }

    /**
     * Get createTime
     *
     * @return \DateTime
     */
    public function getCreateTime()
    {
        return $this->createTime;
    }

    /**
     * Set updateTime
     *
     * @param \DateTime $updateTime
     *
     * @return WalletOrder
     */
    public function setUpdateTime($updateTime)
    {
        $this->updateTime = $updateTime;

        return $this;
    }

    /**
     * Get updateTime
     *
     * @return \DateTime
     */
    public function getUpdateTime()
    {
        return $this->updateTime;
    }
    /**
     * @var string
     */
    private $ouid;


    /**
     * Set ouid
     *
     * @param string $ouid
     *
     * @return WalletOrder
     */
    public function setOuid($ouid)
    {
        $this->ouid = $ouid;

        return $this;
    }

    /**
     * Get ouid
     *
     * @return string
     */
    public function getOuid()
    {
        return $this->ouid;
    }
    /**
     * @var float
     */
    private $amount;


    /**
     * Set amount
     *
     * @param float $amount
     *
     * @return WalletOrder
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Get amount cent
     *
     * @return integer
     */
    public function getAmountCent() {
        return round(100 * $this->amount);
    }

    /**
     * @var integer
     */
    private $answerId;


    /**
     * Set answerId
     *
     * @param integer $answerId
     *
     * @return WalletOrder
     */
    public function setAnswerId($answerId)
    {
        $this->answerId = $answerId;

        return $this;
    }

    /**
     * Get answerId
     *
     * @return integer
     */
    public function getAnswerId()
    {
        return $this->answerId;
    }

    function __construct() {
        $nowTime = TimeUtilService::getCurrentDateTime();
        $this->setOuid('');
        $this->setUserId(0);
        $this->setTargetUserId(0);
        $this->setQuestionId(0);
        $this->setAnswerId(0);
        $this->setTradeType(0);
        $this->setAmount(0.0);
        $this->setPaymentType(0);
        $this->setTradeNo('');
        $this->setTradeTime(null);
        $this->setIsValid(0);
        $this->setCreateTime($nowTime);
        $this->setUpdateTime($nowTime);
    }

    function jsonSerialize() {
        $arr = array(
            'Ouid' => $this->ouid,
            'Amount' => $this->amount,
            'TradeType' => $this->tradeType,
            'PaymentType' => $this->paymentType,
            'CreateTime' => TimeUtilService::timeToStr($this->createTime),
            'UpdateTime' => TimeUtilService::timeToStr($this->updateTime),
        );
        return UtilService::getNotNullValueArray($arr);
    }
}
