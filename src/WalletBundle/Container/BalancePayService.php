<?php

namespace WalletBundle\Container;

use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

use BaseBundle\Container\BaseService;

use BaseBundle\Container\BaseConst;
use WalletBundle\Container\OrderConst;

class BalancePayService extends BaseService {
    private $orderRepo;

    public function __construct(EntityManager $em, Logger $logger, ContainerInterface $container, RequestStack $requestStack) {
        parent::__construct($em, $logger, $container, $requestStack);
        $this->orderRepo = $this->em->getRepository('WalletBundle:WalletOrder');
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-23
     *
     * 调用余额支付
     */
    public function callBalancePay($order, $user, $targetUser, $tradeType, $question, $answer) {
        $result = array(
            'return_code' => 'FAIL',
            'return_msg' =>'未知错误',
        );

        $userService = $this->getUserService();
        $orderService = $this->getOrderService();
        $questionService = $this->container->get('question.questionservice');
        $answerService = $this->container->get('question.answerservice');

        $this->em->getConnection()->beginTransaction();
        try {
            switch ($tradeType) {
                case OrderConst::ORDER_TRADE_TYPE_QUESTION:
                    $userService->deductBalance($user, $order->getAmount());
                    break;
                case OrderConst::ORDER_TRADE_TYPE_ADOPT_ANSWER:
                    $userService->deductBalance($user, $order->getAmount());
                    $userService->increaseBalance($targetUser, $order->getAmount());
                    break;
                default:
                    break;
            }
            $this->em->getConnection()->commit();

            $order = $orderService->saveOrderPayed($order, $tradeNo = '', $paymentType = OrderConst::ORDER_PAYMENT_TYPE_BALANCE);
            $result = array(
                'return_code' => 'SUCCESS',
                'return_msg' =>'成功',
            );
        } catch (\Exception $e) {
            // 产生异常，数据库回滚
            $this->em->getConnection()->rollBack();
            $this->em->close();
            $this->printErrorLog(__FILE__ . '   ' . $e);
        }
        return $result;
    }

    public function notifySuccess($order) {
        
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
}
