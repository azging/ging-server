<?php

namespace WalletBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use BaseBundle\Controller\ApiBaseController;

use UtilBundle\Container\StringUtilService;
use UtilBundle\Container\UtilService;

use BaseBundle\Container\BaseConst;
use WalletBundle\Container\OrderConst;
use QuestionBundle\Container\QuestionConst;

class OrderController extends ApiBaseController
{
    /**
     * @ApiDoc(
     *  resource = true,
     *  section = "Wallet",
     *  description = "新建订单",
     *  tags = {
     *      "stable" = "#23fd09",
     *      "cyy" = "#607d8b"
     *  },
     *  parameters = {
     *      {
     *          "name" = "Quid",
     *          "dataType" = "string",
     *          "required" = true,
     *          "format" = "32位",
     *          "description" = "问题对外标识"
     *      },
     *      {
     *          "name" = "Auid",
     *          "dataType" = "string",
     *          "required" = false,
     *          "format" = "32位",
     *          "description" = "回答对外标识"
     *      },
     *      {
     *          "name" = "Amount",
     *          "dataType" = "float",
     *          "required" = true,
     *          "format" = "6.66",
     *          "description" = "支付金额"
     *      },
     *      {
     *          "name" = "PaymentType",
     *          "dataType" = "integer",
     *          "required" = true,
     *          "format" = "0, 1, 2",
     *          "description" = "付款方式：0未知，1为余额，2为微信"
     *      },
     *      {
     *          "name" = "TradeType",
     *          "dataType" = "integer",
     *          "required" = true,
     *          "format" = "1, 2, 3",
     *          "description" = "交易类型：0未知，1为提问支付，2为采纳答案，3为提现"
     *      },
     *  },
     *  output = {
     *      "class" = "WalletBundle\Entity\Wrapper\OrderWrapper",
     *  },
     *  views = {"version1", "default"},
     * )
     *
     * @Route("api/v1/wallet/order/add/", methods="POST")
     */
    public function addAction() {
        $quid = $this->getPost('Quid');
        $auid = $this->getPost('Auid');
        $amount = floatval($this->getPost('Amount'));
        $paymentType = intval($this->getPost('PaymentType'));
        $tradeType = intval($this->getPost('TradeType'));;

        $userService = $this->get('user.userservice');
        $questionService = $this->get('question.questionservice');
        $answerService = $this->get('question.answerservice');
        $orderService = $this->get('wallet.orderservice');
        $wrapperService = $this->get('wallet.wrapperservice');
        $wxPayService = $this->get('pay.wxservice');
        $balancePayService = $this->get('pay.balanceservice');

        $questionId = 0;
        $answerId = 0;
        $targetUserId = 0;
        $question = null;
        $answer = null;
        $targetUser = null;

        try {
            $this->checkIfLogin(true);

            if (0 == $paymentType) {
                $this->throwNewException(BaseConst::STATUS_ERROR_COMMON, '请选择付款方式');
            }
            if (0 == $tradeType) {
                $this->throwNewException(BaseConst::STATUS_ERROR_COMMON, '请选择交易类型');
            }
            if (0 >= $amount) {
                $this->throwNewException(BaseConst::STATUS_ERROR_COMMON, '请输入金额');
            }
            $question = $questionService->getQuestionByQuid($quid);
            if (!UtilService::isValidObj($question)) {
                $this->throwNewException(BaseConst::STATUS_ERROR_COMMON, '问题不存在');
            }
            $questionId = $question->getId();
            if (OrderConst::ORDER_TRADE_TYPE_QUESTION == $tradeType) {
                if (QuestionConst::QUESTION_STATUS_ANSWERING <= $question->getStatus()) {
                    $this->throwNewException(BaseConst::STATUS_ERROR_COMMON, '已经支付');
                }
            }

            if (OrderConst::ORDER_TRADE_TYPE_ADOPT_ANSWER == $tradeType) {
                if (!StringUtilService::isValidGuid($auid)) {
                    $this->throwNewException(BaseConst::STATUS_ERROR_COMMON, '请选择要采纳的回答');
                }
                $answer = $answerService->getAnswerByAuid($auid);
                if (!UtilService::isValidObj($answer)) {
                    $this->throwNewException(BaseConst::STATUS_ERROR_COMMON, '回答不存在');
                }
                if ($answer->getQuestionId() != $question->getId()) {
                    $this->throwNewException(BaseConst::STATUS_ERROR_COMMON, '回答与问题不符合');
                }
                $targetUser = $userService->getUserById($answer->getUserId());
                if (!UtilService::isValidObj($targetUser)) {
                    $this->throwNewException(BaseConst::STATUS_ERROR_COMMON, '回答者不存在');
                }
                $answerId = $answer->getId();
                $targetUserId = $targetUser->getId();
                if (QuestionConst::QUESTION_STATUS_ADOPTED_PAID_BEST == $question->getStatus()
                    && AnswerConst::ANSWER_STATUS_ADOPTED_PAID == $answer->getStatus()) {
                    $this->throwNewException(BaseConst::STATUS_ERROR_COMMON, '已经支付');
                }
            }

            $order = $orderService->addOrder($this->userId, $targetUserId, $questionId, $answerId, $tradeType, $amount, OrderConst::ORDER_PAYMENT_TYPE_UNPAY);
            //$order = $orderService->getOrderById(1);
            if (!UtilService::isValidObj($order)) {
                $this->throwNewException(BaseConst::STATUS_ERROR_COMMON, '新建订单失败');
            }

            $wxPrepay = null;
            $balancePay = null;
            if (OrderConst::ORDER_PAYMENT_TYPE_BALANCE == $paymentType) {
                if ($this->user->getWalletBalance() < $amount) {
                    $this->throwNewException(BaseConst::STATUS_ERROR_COMMON, '余额不足，请使用其他支付方式');
                }
                $balancePay = $balancePayService->callBalancePay($order, $this->user, $targetUser, $tradeType, $question, $answer);
            } else if (OrderConst::ORDER_PAYMENT_TYPE_WECHAT == $paymentType) {
                $wxPrepay = $wxPayService->callWxPay($order, $question);
            }

            $this->status = BaseConst::STATUS_SUCCESS;
            $this->data = $wrapperService->getOrderWrapper($order, $balancePay, $wxPrepay);
            $this->msg = "新建订单成功";
        } catch (\Exception $e) {
            $this->printExceptionToLog($e);
        }
        return $this->getJsonResponse();
    }

    /**
     * @ApiDoc(
     *  resource = true,
     *  section = "Wallet",
     *  description = "查询订单详情",
     *  tags = {
     *      "stable" = "#23fd09",
     *      "cyy" = "#607d8b"
     *  },
     *  requirements = {
     *      {
     *          "name" = "Ouid",
     *          "dataType" = "string",
     *          "required" = true,
     *          "format" = "32位",
     *          "description" = "问题对外标识"
     *      },
     *  },
     *  output = {
     *      "class" = "WalletBundle\Entity\Wrapper\OrderWrapper",
     *  },
     *  views = {"version1", "default"},
     * )
     *
     * @Route("api/v1/wallet/order/detail/{ouid}", methods="GET")
     */
    public function detailAction($ouid) {
        $orderService = $this->get('wallet.orderservice');
        $wrapperService = $this->get('wallet.wrapperservice');

        try {
            $this->checkIfLogin(true);

            $order = $orderService->getOrderByOuid($ouid);
            if (!UtilService::isValidObj($order)) {
                $this->throwNewException(BaseConst::STATUS_ERROR_COMMON, '查询订单出错');
            }
            if ($order->getUserId() != $this->userId) {
                $this->throwNewException(BaseConst::STATUS_ERROR_COMMON, '不能查询别人的订单');
            }

            $this->status = BaseConst::STATUS_SUCCESS;
            $this->data = $wrapperService->getOrderWrapper($order);
            $this->msg = "查询订单成功";
        } catch (\Exception $e) {
            $this->printExceptionToLog($e);
        }
        return $this->getJsonResponse();
    }
}
