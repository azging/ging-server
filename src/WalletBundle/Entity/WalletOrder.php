<?php

namespace WalletBundle\Entity;

/**
 * WalletOrder
 */
class WalletOrder
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
     * @var float
     */
    private $amountPayment;

    /**
     * @var float
     */
    private $onlinePayment;

    /**
     * @var float
     */
    private $balancePayment;

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
     * Set amountPayment
     *
     * @param float $amountPayment
     *
     * @return WalletOrder
     */
    public function setAmountPayment($amountPayment)
    {
        $this->amountPayment = $amountPayment;

        return $this;
    }

    /**
     * Get amountPayment
     *
     * @return float
     */
    public function getAmountPayment()
    {
        return $this->amountPayment;
    }

    /**
     * Set onlinePayment
     *
     * @param float $onlinePayment
     *
     * @return WalletOrder
     */
    public function setOnlinePayment($onlinePayment)
    {
        $this->onlinePayment = $onlinePayment;

        return $this;
    }

    /**
     * Get onlinePayment
     *
     * @return float
     */
    public function getOnlinePayment()
    {
        return $this->onlinePayment;
    }

    /**
     * Set balancePayment
     *
     * @param float $balancePayment
     *
     * @return WalletOrder
     */
    public function setBalancePayment($balancePayment)
    {
        $this->balancePayment = $balancePayment;

        return $this;
    }

    /**
     * Get balancePayment
     *
     * @return float
     */
    public function getBalancePayment()
    {
        return $this->balancePayment;
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
}

