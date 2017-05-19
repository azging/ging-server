<?php

namespace QuestionBundle\Entity;

/**
 * Question
 */
class Question
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $quid;

    /**
     * @var int
     */
    private $userId;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $description;

    /**
     * @var int
     */
    private $isAnonymous;

    /**
     * @var string
     */
    private $photoUrls;

    /**
     * @var float
     */
    private $redPacket;

    /**
     * @var float
     */
    private $lng;

    /**
     * @var float
     */
    private $lat;

    /**
     * @var int
     */
    private $status;

    /**
     * @var int
     */
    private $payStatus;

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
     * Set quid
     *
     * @param string $quid
     *
     * @return Question
     */
    public function setQuid($quid)
    {
        $this->quid = $quid;

        return $this;
    }

    /**
     * Get quid
     *
     * @return string
     */
    public function getQuid()
    {
        return $this->quid;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return Question
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
     * Set title
     *
     * @param string $title
     *
     * @return Question
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Question
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set isAnonymous
     *
     * @param integer $isAnonymous
     *
     * @return Question
     */
    public function setIsAnonymous($isAnonymous)
    {
        $this->isAnonymous = $isAnonymous;

        return $this;
    }

    /**
     * Get isAnonymous
     *
     * @return int
     */
    public function getIsAnonymous()
    {
        return $this->isAnonymous;
    }

    /**
     * Set photoUrls
     *
     * @param string $photoUrls
     *
     * @return Question
     */
    public function setPhotoUrls($photoUrls)
    {
        $this->photoUrls = $photoUrls;

        return $this;
    }

    /**
     * Get photoUrls
     *
     * @return string
     */
    public function getPhotoUrls()
    {
        return $this->photoUrls;
    }

    /**
     * Set redPacket
     *
     * @param float $redPacket
     *
     * @return Question
     */
    public function setRedPacket($redPacket)
    {
        $this->redPacket = $redPacket;

        return $this;
    }

    /**
     * Get redPacket
     *
     * @return float
     */
    public function getRedPacket()
    {
        return $this->redPacket;
    }

    /**
     * Set lng
     *
     * @param float $lng
     *
     * @return Question
     */
    public function setLng($lng)
    {
        $this->lng = $lng;

        return $this;
    }

    /**
     * Get lng
     *
     * @return float
     */
    public function getLng()
    {
        return $this->lng;
    }

    /**
     * Set lat
     *
     * @param float $lat
     *
     * @return Question
     */
    public function setLat($lat)
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     * Get lat
     *
     * @return float
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return Question
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set payStatus
     *
     * @param integer $payStatus
     *
     * @return Question
     */
    public function setPayStatus($payStatus)
    {
        $this->payStatus = $payStatus;

        return $this;
    }

    /**
     * Get payStatus
     *
     * @return int
     */
    public function getPayStatus()
    {
        return $this->payStatus;
    }

    /**
     * Set isValid
     *
     * @param integer $isValid
     *
     * @return Question
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
     * @return Question
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
     * @return Question
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

