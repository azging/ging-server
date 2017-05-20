<?php

namespace PlaceBundle\Container;

use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

use BaseBundle\Container\BaseService;
use UtilBundle\Container\HttpUtilService;
use UtilBundle\Container\UtilService;

use BaseBundle\Container\BaseConst;
use PlaceBundle\Container\PlaceConst;

class PlaceService extends BaseService {
    private $provinceRepo;
    private $cityRepo;

    public function __construct(EntityManager $em, Logger $logger, ContainerInterface $container, RequestStack $requestStack) {
        parent::__construct($em, $logger, $container, $requestStack);
        $this->provinceRepo = $this->em->getRepository('PlaceBundle:PlaceProvince');
        $this->cityRepo = $this->em->getRepository('PlaceBundle:PlaceCity');
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-20
     *
     * 根据城市ID获取城市
     */
    public function getCityByCityId($cityId = 110000, $isValid = true) {
        if ($isValid) {
            $result = $this->cityRepo->findOneBy(array('cityId' => $cityId, 'isValid' => BaseConst::IS_VALID_TRUE));
        } else {
            $result = $this->cityRepo->findOneBy(array('cityId' => $cityId));
        }

        return $result;
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-20
     *
     * 根据城市名称获取城市对象
     */
    public function getCityByCityName($cityName, $isValid = true) {
        if ($isValid) {
            $result = $this->cityRepo->findOneBy(array('cityName' => $cityName, 'isValid' => BaseConst::IS_VALID_TRUE));
        } else {
            $result = $this->cityRepo->findOneBy(array('cityName' => $cityName));
        }

        if (!UtilService::isValidObj($result)) {
            if ($isValid) {
                $result = $this->cityRepo->findOneBy(array('cityShortName' => $cityName, 'isValid' => BaseConst::IS_VALID_TRUE));
            } else {
                $result = $this->cityRepo->findOneBy(array('cityShortName' => $cityName));
            }
        }

        return $result;
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-20
     *
     * 根据经纬度获取城市名称
     */
    public function getCityNameByLngAndLat($lng, $lat) {
        $cityName = '';
        if (!UtilService::checkIsLoc($lng, $lat)) {
            return $cityName;
        }
        $jsonData = $this->getBaiduPlaceJsonByLngAndLat($lng, $lat);
        if (!empty($jsonData) && property_exists($jsonData, 'result')) {
            $cityName = $jsonData->result->addressComponent->city;
        } else {
            $this->printErrorLog(__FILE__ . "    " . "ErrorLocationCity empty jsonData longitude is " . $lng . " and latitude is " . $lat);
        }

        return $cityName;
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-20
     *
     * 根据经纬度获取地址数据
     */
    public function getBaiduPlaceJsonByLngAndLat($lng, $lat) {
        $url = PlaceConst::BAIDU_API_URL;
        $url .= 'ak=' . PlaceConst::BAIDU_API_AK;
        $url .= '&callback=' . PlaceConst::BAIDU_API_CALLBACK;
        $url .= '&location=' . $lat . ',' . $lng;
        $url .= '&output=' . PlaceConst::BAIDU_API_OUTPUT;
        $url .= '&pois=' . PlaceConst::BAIDU_API_POIS;
        $dataJson = HttpUtilService::curlGetHttps($url);

        $replaceStr = PlaceConst::BAIDU_API_CALLBACK . '&&' . PlaceConst::BAIDU_API_CALLBACK . '(';
        $dataJson = substr($dataJson, strlen($replaceStr), strlen($dataJson) - strlen($replaceStr) - 1);
        $dataObj = json_decode($dataJson);
        return $dataObj;
    }
}
