<?php

namespace UtilBundle\Container;

use UtilBundle\Container\TimeUtilService;

use BaseBundle\Container\BaseConst;
use UtilBundle\Container\UtilConst;

require_once "Email/email.class.php";

class UtilService {

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 获取Ip
     */
    static function getIP() {
        global $ip;
        if (getenv("HTTP_CLIENT_IP")) {
            $ip = getenv("HTTP_CLIENT_IP");
        } else if (getenv("HTTP_X_FORWARDED_FOR")) {
            $ip = getenv ("HTTP_X_FORWARDED_FOR");
        } else if (getenv("REMOTE_ADDR")) {
            $ip = getenv("REMOTE_ADDR");
        } else {
            $ip = "Unknow";
        }   
        return $ip;
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 发邮件
     */
    static function sendEmail($to, $mailnick, $mailsubject, $mailbody) {
        // $smtpserverport =25;//SMTP服务器端口
        // $smtpserver = "smtp.exmail.qq.com";//SMTP服务器
        // $smtpuser = "service@linkcity.cc";
        // $smtppass = "Dr_mail15152";

        // $smtpusermail = $smtpuser; //SMTP服务器的用户邮箱
        // $mailtype = "TXT"; //邮件格式（HTML/TXT）,TXT为文本邮件
        // $smtp = new \smtp($smtpserver, $smtpserverport, true, $smtpuser, $smtppass);//这里面的一个true是表示使用身份验证,否则不使用身份验证.
        // $smtp->debug = false;
        // $smtp->sendmail($to, $smtpusermail, $mailnick, $mailsubject, $mailbody, $mailtype);
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 获取年龄
     */
    static function getAgeByBirthday($birthday) {
        if (empty($birthday)) {
            return -1;
        }
        $time = time();
        $birthday = TimeUtilService::dateToStr($birthday);
        $age = date('Y', $time) - date('Y', strtotime($birthday)) - 1;
        if (date('m', $time) == date('m', strtotime($birthday))) {
            if (date('d', $time) > date('d', strtotime($birthday))) {
                $age++;
            }
        } else if (date('m', $time) > date('m', strtotime($birthday))) {
            $age++;
        }
        if ($age <= 0 && $age >= 100) {
            $age = -1;
        }
        return $age;
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 获取金币对应的价钱
     */
    static function getAmountByCoins($coins) {
        return $coins * UtilConst::COINS_CONVER_AMOUNT_COEF;
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 检查手机号码格式
     */
    static public function isTelephone($telephone) {
        if (mb_strlen($telephone) == UtilConst::TELEPHONE_NUMBER_LENGTH && is_numeric($telephone)) {
            /*
            if (preg_match("/1[34578]\d{9}/", $telephone)) {
                return true;
            }
             */
            return true;
        }
        return false;
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 返回对象不为null的数组
     */
    static function getNotNullValueArray($arr) {
        if (UtilService::isValidArr($arr)) {
            foreach ($arr as &$value) {
                if (is_null($value)) {
                    $value = array();
                }
                if (UtilService::isValidArr($value)) {
                    $value = UtilService::getNotNullValueArray($value);
                }
            }
        } else {
            return array();
        }
        return $arr;
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 检查是否是合法的经纬度
     */
    static public function checkIsLoc($lng, $lat) {
        if ($lng > 73.0 && $lng < 136.0 && $lat > 3.0 && $lat < 54) {
            return true;
        }
        return false;
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 返回两个经纬度之间的距离，单位m
     */
    static public function getDistance($lng1, $lat1, $lng2, $lat2) {
        if (!UtilService::checkIsLoc($lng1, $lat1)) {
            return 0;
        }
        if (!UtilService::checkIsLoc($lng2, $lat2)) {
            return 0;
        }
        $earthRadius = 6367000; //approximate radius of earth in meters

        $lng1 = ($lng1 * pi()) / 180;
        $lat1 = ($lat1 * pi()) / 180;

        $lng2 = ($lng2 * pi()) / 180;
        $lat2 = ($lat2 * pi()) / 180;

        $calcLongitude = $lng2 - $lng1;
        $calcLatitude = $lat2 - $lat1;
        $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);
        $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
        $calculatedDistance = $earthRadius * $stepTwo;

        return round($calculatedDistance);
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 调整距离的单位 初始为m
     */
    static public function getAutoDistance($distance) {
        $distance /= 1000;
        $distance = round($distance, 1);
        if ($distance < 0.1) {
            $distance = 0.1;
        }
        $distance .= 'km';
        return $distance;
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 是否一个正整数
     */
    static public function isPositiveInteger($obj) {
        if (is_int($obj) && $obj > 0) {
            return true;
        }
        return false;
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * check is a valid obj
     */
    static public function isValidObj($obj) {
        if (is_object($obj)) {
            return true;
        }
        return false;
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 检查数组的有效性
     */
    static public function isValidArr($arr) {
        if (is_array($arr) && !empty($arr) && count($arr) > 0) {
            return true;
        }
        return false;
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 检查JSON数组是否有效
     */
    static public function checkIsValidJsonArr($jsonArr, $msg = "信息填写不全") {
        if (!empty($jsonArr)) {
            $arr = json_decode($jsonArr, true);
            if (UtilService::isValidArr($arr)) {
                return true;
            }
        }
        throw new \Exception($msg, BaseConst::STATUS_ERROR_COMMON);
        return false;
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 检查变量是否为数字
     */
    static public function checkIsValidNumber($number) {
        if (is_numeric($number)) {
            return true;
        }
        return false;
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 检查数组是否包含某元素，若包含则返回其对应的值，若不包含则返回空
     */
    static public function checkArrExistsKey($arr, $key) {
        if (array_key_exists($key, $arr)) {
            return $arr[$key];
        }

        return '';
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 数组转化成xml
     */
    static function arrayToXml(array $arr, \SimpleXMLElement $xml) {
        foreach ($arr as $k => $v) {
            is_array($v)
                ? UtilService::arrayToXml($v, $xml->addChild($k))
                : $xml->addChild($k, $v);
        }
        return $xml;
    }
}
