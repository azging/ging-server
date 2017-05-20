<?php

namespace PlaceBundle\Entity;

use UtilBundle\Container\TimeUtilService;

/**
 * PlaceProvince
 */
class PlaceProvince implements \JsonSerializable
{
    /**
     * @var int
     */
    private $id;


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
     * @var integer
     */
    private $provinceId = 0;

    /**
     * @var string
     */
    private $provinceName = '';

    /**
     * @var integer
     */
    private $isValid = 0;

    /**
     * @var \DateTime
     */
    private $createTime;

    /**
     * @var \DateTime
     */
    private $updateTime;


    /**
     * Set provinceId
     *
     * @param integer $provinceId
     *
     * @return PlaceProvince
     */
    public function setProvinceId($provinceId)
    {
        $this->provinceId = $provinceId;

        return $this;
    }

    /**
     * Get provinceId
     *
     * @return integer
     */
    public function getProvinceId()
    {
        return $this->provinceId;
    }

    /**
     * Set provinceName
     *
     * @param string $provinceName
     *
     * @return PlaceProvince
     */
    public function setProvinceName($provinceName)
    {
        $this->provinceName = $provinceName;

        return $this;
    }

    /**
     * Get provinceName
     *
     * @return string
     */
    public function getProvinceName()
    {
        return $this->provinceName;
    }

    /**
     * Set isValid
     *
     * @param integer $isValid
     *
     * @return PlaceProvince
     */
    public function setIsValid($isValid)
    {
        $this->isValid = $isValid;

        return $this;
    }

    /**
     * Get isValid
     *
     * @return integer
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
     * @return PlaceProvince
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
     * @return PlaceProvince
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
        $this->setProvinceId(0);
        $this->setProvinceName('');
        $this->setIsValid(0);
        $this->setCreateTime($nowTime);
        $this->setUpdateTime($nowTime);
    }

    function jsonSerialize() {
        return array(
            'ProvinceId' => $this->provinceId,
            'ProvinceName' => $this->provinceName,
            'CreateTime' => TimeUtilService::timeToStr($this->createTime),
            'UpdateTime' => TimeUtilService::timeToStr($this->updateTime),
        );
    }
}

