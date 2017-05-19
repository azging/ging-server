<?php

namespace UserBundle\Entity;

use UtilBundle\Container\TimeUtilService;

use UserBundle\Container\PhoneConst;

/**
 * PhoneAuthCode
 */
class PhoneAuthCode
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $telephone;

    /**
     * @var string
     */
    private $authCode;

    /**
     * @var \DateTime
     */
    private $expireTime;

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
     * Set telephone
     *
     * @param string $telephone
     *
     * @return PhoneAuthCode
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone
     *
     * @return string
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set authCode
     *
     * @param string $authCode
     *
     * @return PhoneAuthCode
     */
    public function setAuthCode($authCode)
    {
        $this->authCode = $authCode;

        return $this;
    }

    /**
     * Get authCode
     *
     * @return string
     */
    public function getAuthCode()
    {
        return $this->authCode;
    }

    /**
     * Set expireTime
     *
     * @param \DateTime $expireTime
     *
     * @return PhoneAuthCode
     */
    public function setExpireTime($expireTime)
    {
        $this->expireTime = $expireTime;

        return $this;
    }

    /**
     * Get expireTime
     *
     * @return \DateTime
     */
    public function getExpireTime()
    {
        return $this->expireTime;
    }

    /**
     * Set isValid
     *
     * @param integer $isValid
     *
     * @return PhoneAuthCode
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
     * @return PhoneAuthCode
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
     * @return PhoneAuthCode
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

    function __construct() {
        $nowTime = TimeUtilService::getCurrentDateTime();
        $expireTime = TimeUtilService::getDateTimeAfterMinutes(PhoneConst::AUTH_CODE_EXPIRE_MINUTES);
        $this->setTelephone('');
        $this->setAuthCode('111111');
        $this->setExpireTime($expireTime);
        $this->setIsValid(0);
        $this->setCreateTime($nowTime);
        $this->setUpdateTime($nowTime);
    }
}

