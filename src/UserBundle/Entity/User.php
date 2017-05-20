<?php

namespace UserBundle\Entity;

use UtilBundle\Container\TimeUtilService;
use UtilBundle\Container\StringUtilService;
use UtilBundle\Container\UtilService;

use UserBundle\Container\UserConst;

/**
 * User
 */
class User implements \JsonSerializable {
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $uuid;

    /**
     * @var string
     */
    private $cid;

    /**
     * @var string
     */
    private $telephone;

    /**
     * @var string
     */
    private $wechatId;

    /**
     * @var string
     */
    private $nick;

    /**
     * @var string
     */
    private $avatarUrl;

    /**
     * @var int
     */
    private $gender;

    /**
     * @var int
     */
    private $liveCityId;

    /**
     * @var string
     */
    private $liveCityName;

    /**
     * @var int
     */
    private $baseWeight;

    /**
     * @var int
     */
    private $tempWeight;

    /**
     * @var int
     */
    private $weight;

    /**
     * @var int
     */
    private $type;

    /**
     * @var string
     */
    private $deviceUuid;

    /**
     * @var int
     */
    private $osType;

    /**
     * @var string
     */
    private $appVersion;

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
     * Set uuid
     *
     * @param string $uuid
     *
     * @return User
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * Get uuid
     *
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * Set cid
     *
     * @param string $cid
     *
     * @return User
     */
    public function setCid($cid)
    {
        $this->cid = $cid;

        return $this;
    }

    /**
     * Get cid
     *
     * @return string
     */
    public function getCid()
    {
        return $this->cid;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     *
     * @return User
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
     * Set wechatId
     *
     * @param string $wechatId
     *
     * @return User
     */
    public function setWechatId($wechatId)
    {
        $this->wechatId = $wechatId;

        return $this;
    }

    /**
     * Get wechatId
     *
     * @return string
     */
    public function getWechatId()
    {
        return $this->wechatId;
    }

    /**
     * Set nick
     *
     * @param string $nick
     *
     * @return User
     */
    public function setNick($nick)
    {
        $this->nick = $nick;

        return $this;
    }

    /**
     * Get nick
     *
     * @return string
     */
    public function getNick()
    {
        return $this->nick;
    }

    /**
     * Set avatarUrl
     *
     * @param string $avatarUrl
     *
     * @return User
     */
    public function setAvatarUrl($avatarUrl)
    {
        $this->avatarUrl = $avatarUrl;

        return $this;
    }

    /**
     * Get avatarUrl
     *
     * @return string
     */
    public function getAvatarUrl()
    {
        return $this->avatarUrl;
    }

    /**
     * Set gender
     *
     * @param integer $gender
     *
     * @return User
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return int
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set liveCityId
     *
     * @param integer $liveCityId
     *
     * @return User
     */
    public function setLiveCityId($liveCityId)
    {
        $this->liveCityId = $liveCityId;

        return $this;
    }

    /**
     * Get liveCityId
     *
     * @return int
     */
    public function getLiveCityId()
    {
        return $this->liveCityId;
    }

    /**
     * Set liveCityName
     *
     * @param string $liveCityName
     *
     * @return User
     */
    public function setLiveCityName($liveCityName)
    {
        $this->liveCityName = $liveCityName;

        return $this;
    }

    /**
     * Get liveCityName
     *
     * @return string
     */
    public function getLiveCityName()
    {
        return $this->liveCityName;
    }

    /**
     * Set baseWeight
     *
     * @param integer $baseWeight
     *
     * @return User
     */
    public function setBaseWeight($baseWeight)
    {
        $this->baseWeight = $baseWeight;

        return $this;
    }

    /**
     * Get baseWeight
     *
     * @return int
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
     * @return User
     */
    public function setTempWeight($tempWeight)
    {
        $this->tempWeight = $tempWeight;

        return $this;
    }

    /**
     * Get tempWeight
     *
     * @return int
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
     * @return User
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get weight
     *
     * @return int
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set type
     *
     * @param integer $type
     *
     * @return User
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set deviceUuid
     *
     * @param string $deviceUuid
     *
     * @return User
     */
    public function setDeviceUuid($deviceUuid)
    {
        $this->deviceUuid = $deviceUuid;

        return $this;
    }

    /**
     * Get deviceUuid
     *
     * @return string
     */
    public function getDeviceUuid()
    {
        return $this->deviceUuid;
    }

    /**
     * Set osType
     *
     * @param integer $osType
     *
     * @return User
     */
    public function setOsType($osType)
    {
        $this->osType = $osType;

        return $this;
    }

    /**
     * Get osType
     *
     * @return int
     */
    public function getOsType()
    {
        return $this->osType;
    }

    /**
     * Set appVersion
     *
     * @param string $appVersion
     *
     * @return User
     */
    public function setAppVersion($appVersion)
    {
        $this->appVersion = $appVersion;

        return $this;
    }

    /**
     * Get appVersion
     *
     * @return string
     */
    public function getAppVersion()
    {
        return $this->appVersion;
    }

    /**
     * Set lng
     *
     * @param float $lng
     *
     * @return User
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
     * @return User
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
     * Set isValid
     *
     * @param integer $isValid
     *
     * @return User
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
     * @return User
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
     * @return User
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
        $this->setUuid('');
        $this->setCid('');
        $this->setTelephone('');
        $this->setWechatId('');
        $this->setNick('');
        $this->setAvatarUrl('http://download.duckr.cn/DuckrDefaultPhoto.png');
        $this->setGender(0);
        $this->setLiveCityId(0);
        $this->setLiveCityName('');
        $this->setBaseWeight(0);
        $this->setTempWeight(0);
        $this->setWeight(0);
        $this->setType(0);
        $this->setloginType(0);
        $this->setDeviceUuid('');
        $this->setOsType(0);
        $this->setAppVersion('');
        $this->setLat(0);
        $this->setLng(0);
        $this->setIsValid(0);
        $this->setCreateTime($nowTime);
        $this->setUpdateTime($nowTime);
    }

    function jsonSerialize() {
        $thumbAvatarUrl = '';
        if (!empty($this->avatarUrl)) {
            $thumbAvatarUrl = $this->avatarUrl . UserConst::USER_AVATAR_THUMB_SUFFIX;
        }
        $result = array(
            'Uuid' => $this->uuid,
            'Cid' => $this->cid,
            'LoginType' => intval($this->loginType),
            'Telephone' => $this->telephone,
            'WechatId' => $this->wechatId,
            'Nick' => strval(StringUtilService::getPureNick($this->nick)),
            'AvatarUrl' => $this->avatarUrl,
            'ThumbAvatarUrl' => $thumbAvatarUrl,
            'Gender' => intval($this->gender),
            'LiveCityId' => intval($this->liveCityId),
            'LiveCityName' => $this->liveCityName,
            'Type' => intval($this->type),
            'UpdateTime' => TimeUtilService::timeToStr($this->updateTime),
        );
        return UtilService::getNotNullValueArray($result);
    }
    /**
     * @var integer
     */
    private $loginType;


    /**
     * Set loginType
     *
     * @param integer $loginType
     *
     * @return User
     */
    public function setLoginType($loginType)
    {
        $this->loginType = $loginType;

        return $this;
    }

    /**
     * Get loginType
     *
     * @return integer
     */
    public function getLoginType()
    {
        return $this->loginType;
    }
}
