<?php
namespace BaseBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use UtilBundle\Container\UtilService;
use BaseBundle\Controller\BaseController;

use BaseBundle\Container\BaseConst;

class ApiBaseController extends BaseController {
    protected $status;
    protected $msg;

    function __construct($filePath = __FILE__) {
        parent::__construct($filePath);
        $this->status = BaseConst::STATUS_ERROR_SERVER;
        $this->msg = BaseConst::MSG_ERROR_SERVER;
    }

    protected function getJsonResponse() {
        $arrResult = array();
        $arrResult['Status'] = $this->status;
        $arrResult['Data'] = $this->data;
        $arrResult['Msg'] = $this->msg;
        $arrResult = UtilService::getNotNullValueArray($arrResult);

        $response = new Response(json_encode($arrResult));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    protected function getCityId() {
        $cityId = $this->getCookieData('CityId');
        // for postman test
        if (empty($cityId)) {
            $cityId = $this->getPost('CityId');
        }
        if (empty($cityId)) {
            $cityId = 0;
        }
        return $cityId;
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 将异常信息输出到日志中
     */
    protected function printExceptionToLog($e) {
        $code = $e->getCode();
        $this->status = $code;
        $this->msg = $e->getMessage();
        parent::printExceptionToLog($e);
    }
}
