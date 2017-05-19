<?php

namespace UtilBundle\Container;

use BaseBundle\Container\BaseConst;
use BaseBundle\Container\ServerConst;

use UtilBundle\Container\TimeUtilService;
use UserBundle\Container\UserConst;

class StringUtilService {

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 格式化DeviceToken
     */
    static public function formatDeviceToken($deviceTokenID){
        $deviceTokenID = str_replace('<', '', $deviceTokenID);
        $deviceTokenID = str_replace('>', '', $deviceTokenID);
        $deviceTokenID = str_replace(' ', '', $deviceTokenID);
        return $deviceTokenID;
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 检查是否合规的昵称
     */
    static public function isValidNick($nick, &$reason) {
        if (!isset($nick)) {
            return false;
        }
        if (mb_strlen($nick, 'utf8') <= 0) {
            $reason = '昵称不能为空';
            return false;
        }
        if (mb_strlen($nick, 'utf8') > UserConst::USER_NICK_MAX_LENGTH) {
            $reason = '昵称字符过长';
            return false;
        }
        return true;
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 检查是否合规的Guid
     */
    static public function isValidGuid($guid) {
        if (empty($guid)) {
            return false;
        }
        if (32 != strlen($guid)) {
            return false;
        }
        return true;
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 生成GUID（加密）
     */
    static public function getGuidStr($prefix, $id) {
        return md5($prefix . time() . $id);
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 生成全球唯一GUID
     */
    static public function getGuid() {
        $guid = '';
        if (function_exists('com_create_guid')) {
            $guid = strval(com_create_guid());
            $guid = str_replace('{', '', $guid);
            $guid = str_replace('}', '', $guid);
        } else {
            mt_srand((double)microtime() * 10000);
            $guid = md5(uniqid(rand(), true));
        }
        return $guid;
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 生成随机n位数字
     */
    static public function getRandomCode($length = 6) {
        $rightLimit = pow(10, $length) - 1;
        $leftLimit = pow(10, $length - 1);
        return rand($leftLimit, $rightLimit);
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 检查字符串是否有效
     */
    static public function checkIsValidString($data, $msg = "信息填写不全") {
        if (false === isset($data) || '' == $data) {
            throw new \Exception($msg, BaseConst::STATUS_ERROR_COMMON);
        }
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 获取加密后的密码
     */
    static public function getEncryptedPassword($password) {
        return md5(BaseConst::DUCKR_PASSWORD_PREFIX . $password);
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 生成聊天室标题
     */
    static public function getChatRoomTitle($time, $title) {
        $dateArr = explode(' ', $time);
        $title = $dateArr[0] . ' ' . $title;

        return $title;
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 获取用户Jid
     */
    static public function getJidAccount($chatAccount) {
        return $chatAccount . '@' . ServerConst::CHAT_SERVER_NAME;
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 获取聊天室Jid
     */
    static public function getJidRoom($roomName) {
        return $roomName . '@conference.' . ServerConst::CHAT_SERVER_NAME;
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 获取无#后缀的昵称
     */
    static public function getPureNick($nick) {
        if (strstr($nick, '#')) {
            $tmpArr = explode('#', $nick);
            $nick = $tmpArr[0];
        }
        return $nick;
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 获取OrderStr
     */
    static public function getOrderStrArr($orderStr) {
        $orderStrArr = explode('#', $orderStr);

        return $orderStrArr;
    }

}
