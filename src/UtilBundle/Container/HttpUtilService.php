<?php

namespace UtilBundle\Container;

use Symfony\Component\HttpFoundation\Response;
use UtilBundle\Container\UtilConst;

class HttpUtilService {

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 向目的url的get请求
     */
    static public function curlGet($url = '', $header = '', $optionArr = array()) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, UtilConst::HTTP_CURLOPT_TIMEOUT_SET);
        if (!empty($header)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }   
        if (!empty($optionArr)) {
            curl_setopt_array($ch, $optionArr);
        }   
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }   

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 向目的url的post请求
     */
    static public function curlPost($url = '', $data = '', $header = '', $optionArr = array()) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_TIMEOUT, UtilConst::HTTP_CURLOPT_TIMEOUT_SET);

        if (!empty($header)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        if (!empty($optionArr)) {
            curl_setopt_array($ch, $optionArr);
        }
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 向目的url的post请求,传参xml
     */
    static function curlXmlPostResult($url, $data = '', $headerArr = array()) {
        $code = 0;
        $reason = '';
        $ch = curl_init();
        $header[] = "Content-type: text/xml";//定义content-type为xml  
        curl_setopt($ch, CURLOPT_URL, $url); //定义表单提交地址  
        curl_setopt($ch, CURLOPT_POST, 1);   //定义提交类型 1：POST ；0：GET  
        curl_setopt($ch, CURLOPT_HEADER, 1); //定义是否显示状态头 1：显示 ； 0：不显示  
        curl_setopt($ch, CURLOPT_NOBODY, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
        curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 120);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArr);//定义请求类型  
        #curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);//定义是否直接输出返回流  
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data); //定义提交的数据，这里是XML文件  
        $response = curl_exec($ch);
        if (curl_getinfo($ch, CURLINFO_HTTP_CODE) == '200') {
            $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $response = substr($response, 0, $headerSize);
            #$body = substr($response, $headerSize);
        }
        curl_close($ch);//关闭
        $arr = explode('result-code: ', $response);
        if (count($arr) > 1) {
            $arr = explode('TimeStamp:', $arr[1]);
            if (count($arr) > 0) {
                $code = intval($arr[0]);
            }
        }
        $arr = explode('result-info: ', $response);
        if (count($arr) > 1) {
            $reason = $arr[1];
        }

        return array('Code' => $code, 'Reason' => $reason);
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 向目的url的get请求,解析返回的header
     */
    static public function curlXmlGetResult($url = '', $header = '', $optionArr = array()) {
        $code = 0;
        $reason = '';
        $content = '';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_URL, $url); //定义表单提交地址  
        #curl_setopt($ch, CURLOPT_POST, 1);   //定义提交类型 1：POST ；0：GET  
        curl_setopt($ch, CURLOPT_HEADER, 1); //定义是否显示状态头 1：显示 ； 0：不显示  
        curl_setopt($ch, CURLOPT_NOBODY, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
        curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 120);
        if (!empty($header)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        if (!empty($optionArr)) {
            curl_setopt_array($ch, $optionArr);
        }
        $response = curl_exec($ch);
        $headerResponse = '';
        if (curl_getinfo($ch, CURLINFO_HTTP_CODE) == '200') {
            $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $headerResponse = substr($response, 0, $headerSize);
            $content = substr($response, $headerSize);
        }
        curl_close($ch);//关闭
        $arr = explode('result-code: ', $headerResponse);
        if (count($arr) > 1) {
            $arr = explode('TimeStamp:', $arr[1]);
            if (count($arr) > 0) {
                $code = intval($arr[0]);
            }
        }
        $arr = explode('result-info: ', $headerResponse);
        if (count($arr) > 1) {
            $reason = urldecode($arr[1]);
        }

        return array('Code' => $code, 'Reason' => $reason, 'Content' => $content);
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 向目的url的post请求，并获取httpcode.
     */
    static public function curlPostCode($url = '', $data = '', $header = '', $optionArr = array()) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_TIMEOUT, UtilConst::HTTP_CURLOPT_TIMEOUT_SET);

        if (!empty($header)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        if (!empty($optionArr)) {
            curl_setopt_array($ch, $optionArr);
        }
        $data = curl_exec($ch);
        $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return $http;
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 向目的url的delete请求，并获取httpcode
     */
    static public function curlDeleteCode($url = '', $header = '', $optionArr = array()) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_TIMEOUT, UtilConst::HTTP_CURLOPT_TIMEOUT_SET);

        if (!empty($header)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        if (!empty($optionArr)) {
            curl_setopt_array($ch, $optionArr);
        }
        $data = curl_exec($ch);
        $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $http;
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 向目的url的https get 请求
     */
    static public function curlGetHttps($url = '', $header = '', $optionArr = array()) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, UtilConst::HTTP_CURLOPT_TIMEOUT_SET);

        if (!empty($header)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        if (!empty($optionArr)) {
            curl_setopt_array($ch, $optionArr);
        }
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
}
