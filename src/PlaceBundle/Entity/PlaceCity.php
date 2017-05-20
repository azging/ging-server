<?php

namespace PlaceBundle\Entity;

use UtilBundle\Container\TimeUtilService;

/**
 * PlaceCity
 */
class PlaceCity implements \JsonSerializable
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
     * @var integer
     */
    private $cityId = 0;

    /**
     * @var string
     */
    private $cityName = '';

    /**
     * @var string
     */
    private $cityShortName = '';

    /**
     * @var integer
     */
    private $isOpen = 0;

    /**
     * @var integer
     */
    private $weight = 0;

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
     * @return PlaceCity
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
     * Set cityId
     *
     * @param integer $cityId
     *
     * @return PlaceCity
     */
    public function setCityId($cityId)
    {
        $this->cityId = $cityId;

        return $this;
    }

    /**
     * Get cityId
     *
     * @return integer
     */
    public function getCityId()
    {
        return $this->cityId;
    }

    /**
     * Set cityName
     *
     * @param string $cityName
     *
     * @return PlaceCity
     */
    public function setCityName($cityName)
    {
        $this->cityName = $cityName;

        return $this;
    }

    /**
     * Get cityName
     *
     * @return string
     */
    public function getCityName()
    {
        return $this->cityName;
    }

    /**
     * Set cityShortName
     *
     * @param string $cityShortName
     *
     * @return PlaceCity
     */
    public function setCityShortName($cityShortName)
    {
        $this->cityShortName = $cityShortName;

        return $this;
    }

    /**
     * Get cityShortName
     *
     * @return string
     */
    public function getCityShortName()
    {
        return $this->cityShortName;
    }

    /**
     * Set isOpen
     *
     * @param integer $isOpen
     *
     * @return PlaceCity
     */
    public function setIsOpen($isOpen)
    {
        $this->isOpen = $isOpen;

        return $this;
    }

    /**
     * Get isOpen
     *
     * @return integer
     */
    public function getIsOpen()
    {
        return $this->isOpen;
    }

    /**
     * Set weight
     *
     * @param integer $weight
     *
     * @return PlaceCity
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get weight
     *
     * @return integer
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set isValid
     *
     * @param integer $isValid
     *
     * @return PlaceCity
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
     * @return PlaceCity
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
     * @return PlaceCity
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
        $this->setCityId(0);
        $this->setCityName('');
        $this->setCityShortName('');
        $this->setIsOpen(0);
        $this->setWeight(0);
        $this->setIsValid(0);
        $this->setCreateTime($nowTime);
        $this->setUpdateTime($nowTime);
    }

    function jsonSerialize() {
        return array(
            'ProvinceId' => $this->provinceId,
            'CityId' => $this->cityId,
            'CityName' => $this->cityName,
            'CityShortName' => $this->cityShortName,
            'IsOpen' => $this->isOpen,
            'CreateTime' => TimeUtilService::timeToStr($this->createTime),
            'UpdateTime' => TimeUtilService::timeToStr($this->updateTime),
        );
    }
}

