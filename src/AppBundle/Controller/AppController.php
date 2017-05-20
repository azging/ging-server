<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use BaseBundle\Controller\ApiBaseController;

use UtilBundle\Container\UtilService;

use BaseBundle\Container\BaseConst;
use PlaceBundle\Container\PlaceConst;

class AppController extends ApiBaseController
{
    /**
     * @ApiDoc(
     *  resource = true,
     *  section = "App",
     *  description = "设置应用初始化数据",
     *  tags = {
     *      "stable" = "#23fd09",
     *      "cyy" = "#607d8b"
     *  },
     *  parameters = {
     *  },
     *  output = {
     *      "class" = "PlaceBundle\Entity\PlaceCity",
     *  },
     *  views = {"version1", "default"},
     * )
     *
     * @Route("/api/v1/app/config/set/", methods="POST")
     */
    public function setConfigAction() {
        $deviceUuid = $this->getCookieData('DeviceUUID');
        $osType = intval($this->getCookieData('DeviceType'));
        $appVersion = $this->getCookieData('AppVer');
        $lng = floatval($this->getCookieData('LocLng'));
        $lat = floatval($this->getCookieData('LocLat'));
        $cityId = $this->getCookieData('CityId');

        $userService = $this->get('user.userservice');
        $placeService = $this->get('place.placeservice');

        try {
            $this->checkIfLogin(false);

            $cityName = $placeService->getCityNameByLngAndLat($lng, $lat);
            $city = null;
            if (!empty($cityName)) {
                $city = $placeService->getCityByCityName($cityName);
            }
            if (!UtilService::isValidObj($city)) {
                if (empty($cityId)) {
                    $cityId = PlaceConst::DEFAULT_CITY_ID;
                }
                $city = $placeService->getCityByCityId($cityId);
            }

            $userInfoArr = array();
            if (UtilService::isPositiveInteger($this->userId)) {
                $userInfoArr = array(
                    'DeviceUuid' => $deviceUuid,
                    'OsType' => $osType,
                    'AppVersion' => $appVersion,
                    'Lng' => $lng,
                    'Lat' => $lat,
                    'LiveCityId' => $city->getCityId(),
                    'LiveCityName' => $city->getCityName(),
                );
                $userService->updateUserInfo($this->user, $userInfoArr);
            }

            $this->status = BaseConst::STATUS_SUCCESS;
            $this->data = $city;
            $this->msg = '设置应用数据成功';
        } catch (\Exception $e) {
            $this->printExceptionToLog($e);
        }

        return $this->getJsonResponse();
    }
}
