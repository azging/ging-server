<?php

namespace UtilBundle\Container;

use UtilBundle\Container\UtilService;

class TimeUtilService {

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 比较两个时间的大小, $time1-$time2
     */
    static public function isBiggerTime($time1, $time2) {
        $interval = $time1->diff($time2);
        $diff = $interval->format('%R');
        if ('-' == $diff) {
            return true;
        }   
        return false;
    }   

    /**
     * cyy, since 1.0
     *
     * 2017-05-24
     *
     * 计算两个时间的间隔, $time1-$time2
     */
    static public function timeDiff($time1, $time2) {
        $interval = $time1->diff($time2);
        $diff = $interval->format('%r%h小时');
        if ('0小时' == $diff) {
            $diff = $interval->format('%r%i分钟');
        } elseif ('-' == substr($diff, 0, 1)) {
            $diff = '已过期';
        }
        return $diff;
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-24
     *
     * 计算当前时间与给定时间的间隔
     */
    static public function nowTimeDiff($time) {
        $nowTime = self::getCurrentDateTime();
        return self::timeDiff($nowTime, $time);
    }   

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 获取当前日期（年-月-日）
     */
    static public function getCurrentDate() {
        return date_create(date("Y-m-d"));
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 获取当前日期时间（年-月-日 时-分-秒）
     */
    static public function getCurrentDateTime() {
        return date_create(date("Y-m-d H:i:s"));
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 将日期转换为字符串
     */
    static public function dateToStr($date) {
        if (empty($date)) {
            return '';
        }
        return $date->format('Y-m-d');
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 将日期时间转换为字符串
     */
    static public function timeToStr($timeObj) {
        if (empty($timeObj)) {
            return '';
        }
        return $timeObj->format('Y-m-d H:i:s');
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 将日期时间转换为日期字符串
     */
    static public function timeToDateStr($timeObj) {
        if (empty($timeObj)) {
            return '';
        }
        return $timeObj->format('Y-m-d');
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 将日期时间转换为Unix字符串
     */
    static public function timeToUnixStr($timeObj) {
        if (empty($timeObj)) {
            return '';
        }
        $timeStr = TimeUtilService::timeToStr($timeObj);
        return strtotime($timeStr);
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 将时间转换为字符串
     */
    static public function pureTimeToStr($timeObj) {
        if (empty($timeObj)) {
            return '';
        }
        return $timeObj->format('H:i:s');
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 将时间转换为字符串
     */
    static public function pureMinuteToStr($timeObj) {
        if (empty($timeObj)) {
            return '';
        }
        return $timeObj->format('H:i');
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 将字符串转换为日期
     */
    static public function strToDate($str) {
        if (empty($str)) {
            return NULL;
        }
        return date_create(date("Y-m-d", strtotime($str)));
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 将字符串转换为日期时间
     */
    static public function strToDateTime($str) {
        if (empty($str)) {
            return NULL;
        }
        return date_create(date("Y-m-d H:i:s", strtotime($str)));
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 获取minutes分钟偏移的时间，minutes格式为"+5"，即正负号+数字的字符串
     */
    static public function getDateTimeAfterMinutes($minutes, $dateTime = null) {
        if (empty($dateTime)) {
            return date_create(date("Y-m-d H:i:s", strtotime($minutes . " minutes")));
        }
        $nowUnixTime = strtotime($datetime);
        $afterUnixTime = $nowUnixTime + $minutes * 60;
        return date_create(date("Y-m-d H:i:s", $afterUnixTime));
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 获取hours小时偏移的时间，hours格式为"-24"，即正负号+数字的字符串
     */
    static public function getDateTimeAfterHours($hours, $dateTime = null) {
        if (empty($dateTime)) {
            return date_create(date("Y-m-d H:i:s", strtotime($hours . " hours")));
        }
        $nowUnixTime = strtotime($dateTime);
        $afterUnixTime = $nowUnixTime + $hours * 3600;
        return date_create(date("Y-m-d H:i:s", $afterUnixTime));
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 获取两个时间间隔的天数,$dateTime2-$dateTime1
     */
    static public function getDaysBetweenTwoTimeStr($timeStr1, $timeStr2) {
        $second1 = strtotime($timeStr1);
        $second2 = strtotime($timeStr2);

        return intval(($second2 - $second1) / 86400) + 1;
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 获取days天偏移的时间
     */
    static public function getDateAfterDays($days, $dateTime = null) {
        if (empty($dateTime)) {
            return date_create(date("Y-m-d", strtotime($days . " days")));
        }
        $nowUnixTime = strtotime($dateTime);
        $afterUnixTime = $nowUnixTime + $days * 86400;
        return date_create(date("Y-m-d", $afterUnixTime));
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 根据日期获取周几
     */
    static public function getWeekDayByDateStr($dateStr) {
        $timeStr = strtotime($dateStr);
        $weekday = date("w", $timeStr);

        switch ($weekday) {
        case 0:
            $weekday = '星期天';
            break;
        case 1:
            $weekday = '星期一';
            break;
        case 2:
            $weekday = '星期二';
            break;
        case 3:
            $weekday = '星期三';
            break;
        case 4:
            $weekday = '星期四';
            break;
        case 5:
            $weekday = '星期五';
            break;
        case 6:
            $weekday = '星期六';
            break;
        }

        return $weekday;
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 根据日期获取中式日历偏移
     */
    static public function getWeekDayOffsetByDateStr($dateStr) {
        $time = strtotime($dateStr);
        $weekDay = date("w", $time);
        if (0 == $weekDay) {
            $weekDay = 7;
        }
        return $weekDay;
    }


    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 将日期转换为英文
     */
    static public function convertDateStrToEnglishSytle($dateStr, $showYear = false) {
        $dateArr = explode('-', $dateStr);

        $result = '';
        if (UtilService::isValidArr($dateArr)) {
           $year = $dateArr[0];
           $month = intval($dateArr[1]);
           $day = intval($dateArr[2]);
           if ($showYear) {
               $result .= $year . '.';
           }
           $result .= $month . '.' . $day;
        }

        return $result;
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 将日期转换为中文
     */
    static public function convertDateStrToChineseSytle($dateStr, $showYear = false) {
        $dateArr = explode('-', $dateStr);

        $result = '';
        if (UtilService::isValidArr($dateArr)) {
           $year = $dateArr[0];
           $month = intval($dateArr[1]);
           $day = intval($dateArr[2]);
           if ($showYear) {
               $result .= $year . '年';
           }
           $result .= $month . '月' . $day . '日';
        }

        return $result;
    }

}
