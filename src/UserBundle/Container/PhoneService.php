<?php

namespace UserBundle\Container;

use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

use BaseBundle\Container\BaseService;
use UtilBundle\Container\UtilService;
use UtilBundle\Container\TimeUtilService;

use BaseBundle\Container\BaseConst;
use UserBundle\Container\PhoneConst;

require_once __DIR__ . '/SmsSdk/include/Client.php';

class PhoneService extends BaseService {
    private $phoneAuthCodeRepo;

    public function __construct(EntityManager $em, Logger $logger, ContainerInterface $container, RequestStack $requestStack) {
        parent::__construct($em, $logger, $container, $requestStack);
        $this->phoneAuthCodeRepo = $this->em->getRepository('UserBundle:PhoneAuthCode');
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-18
     *
     * 存储验证码
     */
    public function savePhoneAuthCode($telephone, $authCode) {
        return $this->em->getRepository('UserBundle:PhoneAuthCode')->insertPhoneAuthCode($telephone, $authCode);
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-18
     *
     * 发送验证码
     */
    public function sendAuthCode($telephone, $authCode) {
        $content = "【知应】验证码：" . $authCode . "，本验证码5分钟内有效，感谢您的使用！";
        $code = $this->sendSmsToUser($telephone, $content);

        $this->printInfoLog('send auth code sms to ' . $telephone . ', content: ' . $content . ', status:' . $code);
        if (0 != intval($code)) {
            $code = $this->sendSmsToUser($telephone, $content);
            $this->printErrorLog("sms error resend result " . strval($telephone) . " code: " . $code);
        }
        return $code;
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-18
     *
     * 发送短信给用户
     */
    public function sendSmsToUser($telephone, $content) {
        $client = $this->getYiMeiClient(true);

        if (!strstr($content, "【知应】")) {
            $content = "【知应】" . $content;
        }

        $statusCode = $client->sendSMS(array($telephone), $content);

        return $statusCode;
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-18
     *
     * 获取亿美客户端
     */
    public function getYiMeiClient($isAuthCode = true) {
        $gwUrl          = PhoneConst::YIMEI_GATEWAY_URL;
        $serialNumber   = PhoneConst::YIMEI_SERIAL_NUMBER_AUTH_CODE;
        $password       = PhoneConst::YIMEI_PASSWORD;
        $sessionKey     = PhoneConst::YIMEI_SESSION_KEY;
        $connectTimeOut = PhoneConst::YIMEI_CONNECT_TIME_OUT;
        $readTimeOut    = PhoneConst::YIMEI_READ_TIME_OUT;
        $proxyhost      = false;
        $proxyport      = false;
        $proxyusername  = false;
        $proxypassword  = false;

        if (!$isAuthCode) {
            $serialNumber = PhoneConst::YIMEI_SERIAL_NUMBER_MARKET;
            $gwUrl = PhoneConst::YIMEI_MARKET_URL;
            $sessionKey = PhoneConst::YIMEI_MARKET_SESSION_KEY;
        }

        $client = new \Client($gwUrl, $serialNumber, $password, $sessionKey, $proxyhost, $proxyport, $proxyusername, $proxypassword, $connectTimeOut, $readTimeOut);
        $client->setOutgoingEncoding("UTF-8");
        $client->login();

        return $client;
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-18
     *
     * 统计一天内一个手机号码的验证次数
     */
    public function countRecentPACNumberByTel($telephone) {
        $number = 0;
        $dayBeforeTime = TimeUtilService::timeToStr(TimeUtilService::getDateTimeAfterHours("-24"));
        $result = $this->phoneAuthCodeRepo->countPhoneAuthCodeByTelephone($telephone, $dayBeforeTime);
        if (!empty($result)) {
            $number = $result[0]['Number'];
        }

        return $number;
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-18
     *
     * 获取手机号码获取的最后一个验证码
     */
    public function getLastPACByTel($telephone) {
        $result = $this->em->getRepository('UserBundle:PhoneAuthCode')->findBy(
            array('telephone' => $telephone, 'isValid' => BaseConst::IS_VALID_TRUE),
            array('createTime' => 'DESC'),
            1,
            0
        );
        if (UtilService::isValidArr($result)) {
            return $result[0];
        }
        return null;
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-18
     *
     * 根据手机号和验证码查找验证法发送表记录
     */
    public function getPACByTelAndCode($tel, $code) {
        $result = $this->em->getRepository('UserBundle:PhoneAuthCode')->findOneBy(array(
            'telephone' => $tel,
            'authCode' => $code,
            'isValid' => BaseConst::IS_VALID_TRUE
        ));
        return $result;
    }
}
