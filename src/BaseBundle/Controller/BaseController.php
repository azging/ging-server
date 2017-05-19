<?php

namespace BaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

use UtilBundle\Container\UtilService;

use BaseBundle\Container\BaseConst;
use UserBundle\Container\UserConst;

class BaseController extends Controller {
    protected $uuid;
    protected $cid;
    protected $data;

    protected $user;
    protected $userId;
    private $errorFilePath;
    private $request;

    function __construct($filePath = __FILE__) {
        $this->data = new \StdClass();
        $this->errorFilePath = $filePath;
        $this->userId = 0;
        $this->request = Request::createFromGlobals();
    }

    protected function getCid() {
        $cid = $this->getCookieData('CID');
        // for postman test
        if (empty($cid)) {
            $cid = $this->getPost('CID');
        }
        return $cid;
    }

    protected function getUuid() {
        $uuid = $this->getCookieData('UUID');
        // for postman test
        if (empty($uuid)) {
            $uuid = $this->getPost('UUID');
        }
        return $uuid;
    }

    protected function getCookieData($key) {
        $value = $this->request->cookies->get($key);
        if (empty($value)) {
            $value = $this->getPost($key);
        }
        if (empty($value)) {
            $value = $this->request->query->get($key);
        }
        return trim($value);
    }

    protected function getPost($key) {
        $value = $this->request->request->get($key);
        return trim($value);
    }

    protected function getPostList($key) {
        $value = $this->getPost($key);
        $valueArr = json_decode($value, true);

        return $valueArr;
    }

    protected function checkIfLogin($isValid = true) {
        $userService = $this->get("user.userservice");

        $this->uuid = $this->getUuid();
        $this->cid = $this->getCid();

        if(strstr($this->uuid, '#')){
            $this->throwNewException(BaseConst::STATUS_ERROR_NO_CID_LOGIN, "请退出登录，重新登录");
        }
        $this->user = $userService->getUserByUuid($this->uuid, true);
        if ($isValid) {
            if (!UtilService::isValidObj($this->user)) {
                $this->throwNewException(BaseConst::STATUS_ERROR_NO_CID_LOGIN, "请退出登录，重新登录");
            }
            if ($this->cid != $this->user->getCid()) {
                $this->throwNewException(BaseConst::STATUS_ERROR_NO_CID_LOGIN, "用户登录失败");
            }
        }
        if (UtilService::isValidObj($this->user)) {
            $this->userId = $this->user->getId();
        }
    }

    protected function checkIfAdminLogin() {
        $this->checkIfLogin(true);
        if (UserConst::USER_TYPE_SUPER_ADMIN != $this->user->getType()) {
            $this->throwNewException(BaseConst::STATUS_ERROR_NO_CID_LOGIN, "用户非管理员");
        }
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 抛出异常
     */
    protected function throwNewException($status, $msg) {
        throw new \Exception($msg, $status);
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 在日志中输出错误
     */
    protected function printErrorLog($msg) {
        $this->get('app.logger')->error($msg);
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 在日志中输出信息
     */
    protected function printInfoLog($msg) {
        $this->get('app.logger')->info($msg);
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 将异常信息输出到日志中
     */
    protected function printExceptionToLog($e) {
        $deviceType = $this->getCookieData("DeviceType");
        $deviceUuid = $this->getCookieData("DeviceUUID");
        $uuid       = $this->getCookieData("UUID");
        $cid        = $this->getCookieData("CID");
        $appVer     = $this->getCookieData("AppVer");

        $deviceStr = 'iOS';
        if (BaseConst::CLIENT_OS_TYPE_ANDROID == $deviceType) {
            $deviceStr = 'Android';
        }
        $cookieData = 'device: ' . $deviceStr . ', deviceUuid: ' . $deviceUuid . ', uuid: ' . $uuid . ', cid: ' . $cid . ', appVersion: ' . $appVer;
        $headers = array();
        while (list($key, $value) = each($_SERVER)) {
            if ('HTTP_' == substr($key, 0, 5)) {
                $headers[str_replace('_', '-', substr($key, 5))] = $value;
            }
        }
        $headerInfo = print_r($headers, true);
        if (BaseConst::STATUS_ERROR_NO_CID_LOGIN != $e->getCode()) {
            $msg = $this->errorFilePath . '    ' . $e . '    CookieData: ' . $cookieData . '    Header: ' . $headerInfo;
            $this->printErrorLog($msg);
        }
    }
}
