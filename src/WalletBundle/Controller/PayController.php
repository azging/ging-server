<?php

namespace WalletBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use BaseBundle\Controller\ApiBaseController;

use UtilBundle\Container\UtilService;

use BaseBundle\Container\BaseConst;

class PayController extends ApiBaseController
{
    /**
     * @ApiDoc(
     *  resource = true,
     *  section = "Wallet",
     *  description = "微信支付回调",
     *  tags = {
     *      "stable" = "#23fd09",
     *      "cyy" = "#607d8b"
     *  },
     *  requirements = {
     *  },
     *  views = {"version1", "default"},
     * )
     *
     * @Route("api/v1/pay/wx/notify/", methods="POST")
     */
    function wxNotifyAction() {
        $this->printInfoLog('WxPay Notify Received');
        // 通知消息
        try {
            $wxPayService = $this->get('pay.wxservice');
            $wxPayService->Handle(false);
            $this->printInfoLog('WxPay Notify Success');
            $this->status = BaseConst::STATUS_SUCCESS;
            $this->msg = 'Success';
        } catch (\Exception $e) {
            $this->printExceptionToLog($e);
        }
        return $this->getJsonResponse();
    }

    /**
     * @ApiDoc(
     *  resource = true,
     *  section = "Wallet",
     *  description = "余额支付回调",
     *  tags = {
     *      "stable" = "#23fd09",
     *      "cyy" = "#607d8b"
     *  },
     *  parameters = {
     *      {
     *          "name" = "Ouid",
     *          "dataType" = "string",
     *          "required" = true,
     *          "format" = "32位",
     *          "description" = "订单唯一标识"
     *      },
     *  },
     *  views = {"version1", "default"},
     * )
     *
     * @Route("api/v1/pay/balance/notify/", methods="POST")
     */
    function balanceNotifyAction() {
        $this->printInfoLog('BalancePay Notify Received');

        $ouid = $this->getPost('Ouid');

        $balancePayService = $this->get('pay.balanceservice');
        $orderService = $this->get('wallet.orderservice');

        try {
            $order = $orderService->getOrderByOuid($ouid);
            if (!UtilService::isValidObj($order)) {
                $this->throwNewException(BaseConst::STATUS_ERROR_COMMON, '支付失败，订单不存在');
            }
            $balancePayService->notifySuccess($order);

            $this->printInfoLog('WxPay Notify Success');
            $this->status = BaseConst::STATUS_SUCCESS;
            $this->msg = 'Success';
        } catch (\Exception $e) {
            $this->printExceptionToLog($e);
        }
        return $this->getJsonResponse();
    }
}
