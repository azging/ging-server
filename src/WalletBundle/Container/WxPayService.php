<?php
namespace WalletBundle\Container;

use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

use BaseBundle\Container\BaseService;
use UtilBundle\Container\UtilService;
use UtilBundle\Container\StringUtilService;

use WalletBundle\Container\OrderConst;
use WalletBundle\Container\PayConst;

require_once "WxPaySdk/WxPay.Notify.php";

/**
 * 类名：WxPayService
 * 功能：微信通知处理类
 * 详细：处理微信各接口通知返回
 * 版本：1.0
 * 日期：2017-05-23
 */
class WxPayService extends \WxPayNotify {
    private $em;
    private $logger;
    private $container;
    private $requestStack;

    private $orderService;

    public function __construct(EntityManager $em, Logger $logger, ContainerInterface $container, RequestStack $requestStack) {
        $this->em = $em;
        $this->logger = $logger;
        $this->container = $container;
        $this->requestStack = $requestStack;

        $this->orderService = $this->container->get('wallet.orderservice');
    }
    
    /**
     * cyy, since 1.0
     *
     * 2017-05-23
     *
     * 调用微信支付
     */
    public function callWxPay($order, $question) {
        $unifiedOrder = new \WxPayUnifiedOrder();
        $amountCent = $order->getAmountCent();
        $ouid = '';
        if (UtilService::isValidObj($order)) {
            $ouid = $order->getOuid();
        }
        $wxBody = '';
        if (UtilService::isValidObj($question)) {
            $wxBody = $question->getTitle();
        }
        // 填充数据
        $unifiedOrder->SetOut_trade_no($ouid);
        $unifiedOrder->SetBody($wxBody);
        $unifiedOrder->SetAttach($wxBody);
        $unifiedOrder->SetGoods_tag($wxBody);
        $unifiedOrder->SetTotal_fee($amountCent);
        $unifiedOrder->SetTrade_type(PayConst::PAY_TYPE_APP);
        $result = \WxPayApi::unifiedOrder($unifiedOrder);
        return $result;
    }
    
    /**
     * cyy, since 1.0
     *
     * 2017-05-23
     *
     * 重写微信支付成功回调函数
     */
    public function NotifyProcess($data, &$msg) {
        $this->logger->info('WxPay Notify Process: ' . print_r($data, true));
        $result = false;
        $msg = 'NOTOK';

        $returnCode = $data['return_code'];

        if ('SUCCESS' == $returnCode) {
            $ouid = $data['out_trade_no'];
            $order = $this->orderService->getOrderByOuid($ouid, true);
            try {
                if (UtilService::isValidObj($order)) {
                    $paymentType = OrderConst::ORDER_PAYMENT_TYPE_WECHAT;
                    if (OrderConst::ORDER_PAYMENT_TYPE_UNPAY == $order->getPaymentType() && empty($tradeNo)) {
                        $tradeNo = $data['transaction_id'];
                        $notifyMsg = $this->getNotifyMsg($data);
                        //TODO:设置微信id
                        //$order->setWxOpenId($data['openid']);
                        $totalFee = floatval($data['total_fee']);
                        $orderPay = $order->getOrderFeeCent();
                        if ($orderPay != $totalFee) {
                            $this->logger->error('WxPay Check: ShouldPay [' . $orderPay . '] RealPay [' . $totalFee . ']');
                            $paymentType = OrderConst::ORDER_PAYMENT_TYPE_UNPAY;
                        }
                        $order = $this->orderService->saveOrderPayed($order, $tradeNo, $notifyMsg, $paymentType);
                        if (!UtilService::isValidObj($order)) {
                            $msg = '无法更新订单';
                        } else {
                            $result = true;
                            $msg = 'OK';
                        }
                    }
                } else {
                    $msg = '订单不存在';
                }
            } catch (\Exception $e) {
                $msg = 'NOTOK';
            }
        } else {
            $msg = 'NOTOK';
        }
        return $result;
    }
    
    /**
     * cyy, since 1.0
     *
     * 2017-05-23
     *
     * 获取微信支付的通知信息
     */
    private function getNotifyMsg($para) {
        $arg = "";
        while (list ($key, $val) = each($para)) {
            $arg .= $key . "=" . $val . "&";
        }
        //去掉最后一个&字符
        $arg = substr($arg, 0, count($arg) - 2);

        //如果存在转义字符，那么去掉转义
        if (get_magic_quotes_gpc()) {
            $arg = stripslashes($arg);
        }

        return $arg;
    }
}
