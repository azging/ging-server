<?php

namespace QuestionBundle\Entity;

use UtilBundle\Container\TimeUtilService;
use UtilBundle\Container\StringUtilService;
use UtilBundle\Container\UtilService;

use QuestionBundle\Container\QuestionConst;

/**
 * Question
 */
class Question implements \JsonSerializable {
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
    /**
     * @var integer
     */
    private $baseWeight;

    /**
     * @var integer
     */
    private $tempWeight;

    /**
     * @var integer
     */
    private $weight;


    /**
     * Set baseWeight
     *
     * @param integer $baseWeight
     *
     * @return Question
     */
    public function setBaseWeight($baseWeight)
    {
        $this->baseWeight = $baseWeight;

        return $this;
    }

    /**
     * Get baseWeight
     *
     * @return integer
     */
    public function getBaseWeight()
    {
        return $this->baseWeight;
    }

    /**
     * Set tempWeight
     *
     * @param integer $tempWeight
     *
     * @return Question
     */
    public function setTempWeight($tempWeight)
    {
        $this->tempWeight = $tempWeight;

        return $this;
    }

    /**
     * Get tempWeight
     *
     * @return integer
     */
    public function getTempWeight()
    {
        return $this->tempWeight;
    }

    /**
     * Set weight
     *
     * @param integer $weight
     *
     * @return Question
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

    function __construct() {
        $nowTime = TimeUtilService::getCurrentDateTime();
        $this->setQuid('');
        $this->setUserId(0);
        $this->setTitle('');
        $this->setDescription('');
        $this->setPhotoUrls('');
        $this->setIsAnonymous(0);
        $this->setReward(0);
        $this->setCityId(0);
        $this->setLat(0);
        $this->setLng(0);
        $this->setBaseWeight(0);
        $this->setTempWeight(0);
        $this->setWeight(0);
        $this->setStatus(0);
        $this->setPayStatus(0);
        $this->setExpireTime($nowTime);
        $this->setAnswerNum(0);
        $this->setIsValid(0);
        $this->setCreateTime($nowTime);
        $this->setUpdateTime($nowTime);
    }

    function jsonSerialize() {
        $photoUrlArr = json_decode($this->photoUrls);
        $num = count($photoUrlArr);
        $thumbPhotoUrlArr = array();
        if (UtilService::isValidArr($photoUrlArr)) {
            foreach ($photoUrlArr as $photoUrl) {
                $thumbPhotoUrl = $photoUrl . QuestionConst::PHOTO_THUMB_SUFFIX;
                $thumbPhotoUrlArr[] = $thumbPhotoUrl;
            }
        }
        $result = array(
            'Quid' => $this->quid,
            'Title' => $this->title,
            'Description' => $this->description,
            'IsAnonymous' => intval($this->isAnonymous),
            'Reward' => floatval($this->reward),
            'PhotoUrls' => $photoUrlArr,
            'ThumbPhotoUrls' => $thumbPhotoUrlArr,
            'CityId' => intval($this->cityId),
            'Status' => intval($this->status),
            'PayStatus' => intval($this->payStatus),
            'AnswerNum' => intval($this->answerNum),
            'ExpireTime' => TimeUtilService::timeToStr($this->expireTime),
            'ExpireTimeStr' => TimeUtilService::nowTimeDiff($this->expireTime),
            'CreateTime' => TimeUtilService::timeToStr($this->createTime),
            'UpdateTime' => TimeUtilService::timeToStr($this->updateTime),
        );
        return UtilService::getNotNullValueArray($result);
    }
    /**
     * @var float
     */
    private $reward;

    /**
     * @var integer
     */
    private $cityId;


    /**
     * Set reward
     *
     * @param float $reward
     *
     * @return Question
     */
    public function setReward($reward)
    {
        $this->reward = $reward;

        return $this;
    }

    /**
     * Get reward
     *
     * @return float
     */
    public function getReward()
    {
        return $this->reward;
    }

    /**
     * Set cityId
     *
     * @param integer $cityId
     *
     * @return Question
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
     * @var \DateTime
     */
    private $expireTime;


    /**
     * Set expireTime
     *
     * @param \DateTime $expireTime
     *
     * @return Question
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
     * @var integer
     */
    private $answerNum;


    /**
     * Set answerNum
     *
     * @param integer $answerNum
     *
     * @return Question
     */
    public function setAnswerNum($answerNum)
    {
        $this->answerNum = $answerNum;

        return $this;
    }

    /**
     * Get answerNum
     *
     * @return integer
     */
    public function getAnswerNum()
    {
        return $this->answerNum;
    }
}
