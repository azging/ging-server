<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use BaseBundle\Controller\ApiBaseController;

use UtilBundle\Container\StringUtilService;
use UtilBundle\Container\TimeUtilService;
use UtilBundle\Container\UtilService;

use BaseBundle\Container\BaseConst;
use UserBundle\Container\PhoneConst;

class UserController extends ApiBaseController {
    public function __construct() {
        parent::__construct(__FILE__);
    }

    /** 
     * @ApiDoc(
     *  resource = true,
     *  section = "User",
     *  description = "根据UUID获取用户",
     *  tags = {
     *      "stable" = "#23fd09",
     *      "cyy" = "#607f8d"
     *  },
     *  requirements = {
     *      {
     *          "name" = "uuid",
     *          "requirement" = "true",
     *          "dataType" = "string",
     *          "description" = "用户对外标识",
     *      },
     *  },
     *  output = {
     *      "class" = "UserBundle\Entity\Wrapper\UserWrapper",
     *  },
     *  views = {"version1", "default"},
     * )
     *
     * @Route("/api/v1/user/info/{uuid}", methods="GET")
     */
    public function userInfoAction($uuid) {
        $userService = $this->get('user.userservice');
        $wrapperService = $this->get('user.wrapperservice');

        try {
            $this->checkIfLogin(false);

            $user = $userService->getUserByUuid($uuid);
            if (!UtilService::isValidObj($user)) {
                $this->throwNewException(BaseConst::STATUS_ERROR_COMMON, "不存在该用户");
            }

            $this->status = BaseConst::STATUS_SUCCESS;
            $this->data = $wrapperService->getUserWrapper($user);
            $this->msg = "获取用户信息成功";
        } catch (\Exception $e) {
            $this->printExceptionToLog($e);
        }

        return $this->getJsonResponse();
    }
    /** 
     * @ApiDoc(
     *  resource = true,
     *  section = "User",
     *  description = "发送短信验证码",
     *  tags = {
     *      "stable" = "#23fd09",
     *      "cyy" = "#607d8b"
     *  },
     *  parameters = {
     *      {
     *          "name" = "Telephone",
     *          "dataType" = "string",
     *          "required" = true,
     *          "format" = "11 digital.",
     *          "description" = "电话号码",
     *      },
     *  },
     *  views = {"version1", "default"},
     * )
     *
     * @Route("/api/v1/user/phone/authcode/send/", methods="POST")
     */
    public function sendAuthCodeAction() {
        $telephone = $this->getPost('Telephone');

        $phoneService = $this->get('user.phoneservice');

        try {
            if (false === UtilService::isTelephone($telephone)) {
                $this->throwNewException(BaseConst::STATUS_ERROR_COMMON, "请输入正确的手机号码");
            }
            $number = $phoneService->countRecentPACNumberByTel($telephone);
            if ($number >= PhoneConst::AUTH_CODE_MAX_TIME) {
                $this->throwNewException(BaseConst::STATUS_ERROR_COMMON, "您今天获取验证码次数已达上限");
            }
            $phoneAuthCodeObj = $phoneService->getLastPACByTel($telephone);
            if (!empty($phoneAuthCodeObj)) {
                $createTime = $phoneAuthCodeObj->getCreateTime();
                $minuteBefore = TimeUtilService::getDateTimeAfterMinutes("-1");
                if ($createTime >= $minuteBefore) {
                    $this->throwNewException(BaseConst::STATUS_ERROR_COMMON, "获取验证码频率过于频繁，请等一分钟后再尝试");
                }
            }

            $authCode = StringUtilService::getRandomCode(PhoneConst::AUTH_CODE_LENGTH);
            $phoneAuthCode = $phoneService->savePhoneAuthCode($telephone, $authCode);
            $result = $phoneService->sendAuthCode($telephone, $authCode);

            $this->status = BaseConst::STATUS_SUCCESS;
            $this->data = array(
                    'AuthCode' => 'xxxxxx',
                    'ExpireTime' => PhoneConst::AUTH_CODE_EXPIRE_SECONDS,
                    'SMSResult' => $result,
                    );
            $this->msg = '验证码发送成功';
        } catch (\Exception $e) {
            if (-2 != $e->getCode()) {
                $this->printExceptionToLog($e);
            }
        }

        return $this->getJsonResponse();
    }

    /**
     * @ApiDoc(
     *  resource = true,
     *  section = "User",
     *  description = "使用手机号注册或登录",
     *  tags = {
     *      "stable" = "#23fd09",
     *      "cyy" = "#607d8b"
     *  },
     *  parameters = {
     *      {
     *          "name" = "Telephone",
     *          "dataType" = "string",
     *          "required" = true,
     *          "format" = "11 digital.",
     *          "description" = "电话号码",
     *      },
     *      {
     *          "name" = "AuthCode",
     *          "dataType" = "string",
     *          "required" = true,
     *          "format" = "6位",
     *          "description" = "验证码"
     *      },
     *  },
     *  output = {
     *      "class" = "UserBundle\Entity\Wrapper\UserWrapper",
     *  },
     *  views = {"version1", "default"},
     * )
     *
     * @Route("/api/v1/user/login/phone/", methods="POST")
     */
    public function loginByTelephoneAction() {
        $telephone = $this->getPost('Telephone');
        $authCode = $this->getPost('AuthCode');

        $userService = $this->get('user.userservice');
        $phoneService = $this->get('user.phoneservice');
        $wrapperService = $this->get('user.wrapperservice');

        try {
            StringUtilService::checkIsValidString($authCode, "验证码有误");
            if (false === UtilService::isTelephone($telephone)) {
                $this->throwNewException(BaseConst::STATUS_ERROR_COMMON, "请输入正确的手机号码");
            }
            $pacObj = $phoneService->getPACByTelAndCode($telephone, $authCode);

            if (UtilService::isValidObj($pacObj)) {
                $expireTime = $pacObj->getExpireTime();
                if (TimeUtilService::isBiggerTime(new \DateTime(), $expireTime)) {
                    $this->throwNewException(BaseConst::STATUS_ERROR_COMMON, "验证码已过期");
                }
            } else {
                $this->throwNewException(BaseConst::STATUS_ERROR_COMMON, "验证码验证失败");
            }

            $user = $userService->getUserByTelephone($telephone, true);
            if (!UtilService::isValidObj($user)) {
                $user = $userService->registerByTelephone($telephone);
            }

            $this->status = BaseConst::STATUS_SUCCESS;
            $this->data = $wrapperService->getUserWrapper($user);
            $this->msg = '手机号登录成功';
        } catch (\Exception $e) {
            $this->printExceptionToLog($e);
        }

        return $this->getJsonResponse();
    }

    /**
     * @ApiDoc(
     *  resource = true,
     *  section = "User",
     *  description = "使用第三方注册或登录",
     *  tags = {
     *      "stable" = "#23fd09",
     *      "cyy" = "#607d8b"
     *  },
     *  parameters = {
     *      {
     *          "name" = "Type",
     *          "dataType" = "integer",
     *          "required" = true,
     *          "format" = "2",
     *          "description" = "0为未知，2为微信"
     *      },
     *      {
     *          "name" = "SocialId",
     *          "dataType" = "string",
     *          "required" = true,
     *          "format" = "",
     *          "description" = "用户第三方账号唯一id",
     *      },
     *      {
     *          "name" = "Nick",
     *          "dataType" = "string",
     *          "required" = true,
     *          "format" = "昵称",
     *          "description" = "昵称"
     *      },
     *      {
     *          "name" = "AvatarUrl",
     *          "dataType" = "string",
     *          "required" = true,
     *          "format" = "http://download.duckr.cn/DuckrDefaultPhoto.png",
     *          "description" = "头像url"
     *      },
     *      {
     *          "name" = "Gender",
     *          "dataType" = "integer",
     *          "required" = true,
     *          "format" = "0为未知，1为男，2为女",
     *          "description" = "性别"
     *      },
     *  },
     *  output = {
     *      "class" = "UserBundle\Entity\Wrapper\UserWrapper",
     *  },
     *  views = {"version1", "default"},
     * )
     *
     * @Route("/api/v1/user/login/social/", methods="POST")
     */
    public function loginBySocialAction() {
        $type = intval($this->getPost('Type'));
        $socialId = $this->getPost('SocialId');
        $nick = $this->getPost('Nick');
        $avatarUrl = $this->getPost('AvatarUrl');
        $gender = intval($this->getPost('Gender'));

        $userService = $this->get('user.userservice');
        $wrapperService = $this->get('user.wrapperservice');

        try {
            if ($type <= 0) {
                $this->throwNewException(BaseConst::STATUS_ERROR_COMMON, "未知的登录类型");
            }
            StringUtilService::checkIsValidString($socialId, "第三方ID错误");
            StringUtilService::checkIsValidString($nick, "昵称为空");
            StringUtilService::checkIsValidString($avatarUrl, "头像为空");

            $user = $userService->getUserBySocialId($type, $socialId, true);
            if (!UtilService::isValidObj($user)) {
                $user = $userService->registerBySocial($type, $socialId, $nick, $avatarUrl, $gender);
            }

            $this->status = BaseConst::STATUS_SUCCESS;
            $this->data = $wrapperService->getUserWrapper($user);
            $this->msg = '使用第三方账号登录成功';
        } catch (\Exception $e) {
            $this->printExceptionToLog($e);
        }

        return $this->getJsonResponse();
    }

    /**
     * @ApiDoc(
     *  resource = true,
     *  section = "User",
     *  description = "用户退出登录接口",
     *  tags = {
     *      "stable" = "#23fd09",
     *      "cyy" = "#607d8b"
     *  },
     *  parameters = {
     *  },
     *  output = {
     *      "class" = "UserBundle\Entity\Wrapper\UserWrapper",
     *  },
     *  views = {"version1", "default"},
     * )
     *
     * @Route("/api/v1/user/logout/", methods="POST")
     */
    public function logoutAction() {
        $wrapperService = $this->get('user.wrapperservice');

        try {
            $this->checkIfLogin(true);

            $this->status = BaseConst::STATUS_SUCCESS;
            $this->data = $wrapperService->getUserWrapper($this->user);
            $this->msg = "退出登录成功";
        } catch (\Exception $e) {

            $this->printExceptionToLog($e);
        }

        return $this->getJsonResponse();
    }
}
