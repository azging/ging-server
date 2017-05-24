<?php

namespace WalletBundle\Container;

use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

use BaseBundle\Container\BaseService;
use UtilBundle\Container\TimeUtilService;
use UtilBundle\Container\UtilService;

use QuestionBundle\Container\QuestionConst;
use QuestionBundle\Container\AnswerConst;

class OrderService extends BaseService {
    private $questionService;

    private $orderRepo;

    public function __construct(EntityManager $em, Logger $logger, ContainerInterface $container, RequestStack $requestStack) {
        parent::__construct($em, $logger, $container, $requestStack);
        $this->questionService = $this->container->get('question.questionservice');

        $this->orderRepo = $this->em->getRepository('WalletBundle:WalletOrder');
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-23
     *
     * 根据id查找Order
     */
    public function getOrderById($id, $isValid = true) {
        return $this->orderRepo->selectOneOrderByProp('id', $id, $isValid);
    }
    
    /**
     * cyy, since 1.0
     *
     * 2017-05-23
     *
     * 根据ouid查找Order
     */
    public function getOrderByOuid($ouid, $isValid = true) {
        return $this->orderRepo->selectOneOrderByProp('ouid', $ouid, $isValid);
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-23
     *
     * 新建订单
     */
    public function addOrder($userId, $targetUserId, $questionId, $answerId, $tradeType, $amount, $paymentType) {
        $infoArr = array(
            'UserId' => $userId,
            'TargetUserId' => $targetUserId,
            'QuestionId' => $questionId,
            'AnswerId' => $answerId,
            'TradeType' => $tradeType,
            'Amount' => $amount,
            'PaymentType' => $paymentType,
        );
        return $this->orderRepo->insertOrder($infoArr);
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-24
     *
     * 更新支付成功的订单
     */
    public function saveOrderPayed($order, $tradeNo, $paymentType) {
        $nowTime = TimeUtilService::getCurrentDateTime();
        $infoArr = array(
            'TradeNo' => $tradeNo,
            'paymentType' => $paymentType,
            'TradeTime' => $nowTime,
        );

        $questionService = $this->getQuestionService();
        $answerService = $this->getAnswerService();

        $this->em->getConnection()->beginTransaction();
        try {
            $order = $this->orderRepo->updateOrder($order, $infoArr);

            switch ($order->getTradeType()) {
                case OrderConst::ORDER_TRADE_TYPE_QUESTION:
                    $question = $questionService->getQuestionById($order->getQuestionId());
                    if (UtilService::isValidObj($question)) {
                        $questionService->updateStatus($question, QuestionConst::QUESTION_STATUS_ANSWERING);
                    }
                    break;
                case OrderConst::ORDER_TRADE_TYPE_ADOPT_ANSWER:
                    $question = $questionService->getQuestionById($order->getQuestionId());
                    $answer = $answerService->getAnswerById($order->getAnswerId());
                    if (UtilService::isValidObj($question) && UtilService::isValidObj($answer)) {
                        $questionService->updateStatus($question, QuestionConst::QUESTION_STATUS_ADOPTED_PAID_BEST);
                        $answerService->updateStatus($answer, AnswerConst::ANSWER_STATUS_ADOPTED_PAID);
                    }
                    break;
                default:
                    break;
            }

            $this->em->getConnection()->commit();
        } catch (\Exception $e) {
            $this->em->getConnection()->rollBack();
            $this->em->close();
            $this->printErrorLog(__FILE__ . '   ' . $e);
            return null;
        }
        return $order;
    }
}
