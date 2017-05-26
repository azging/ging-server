<?php

namespace QuestionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use BaseBundle\Controller\ApiBaseController;

use UtilBundle\Container\StringUtilService;
use UtilBundle\Container\TimeUtilService;
use UtilBundle\Container\UtilService;

use BaseBundle\Container\BaseConst;
use QuestionBundle\Container\AnswerConst;
use QuestionBundle\Container\QuestionConst;
use WalletBundle\Container\OrderConst;

use BaseBundle\Entity\Wrapper\BoolWrapper;

class AnswerController extends ApiBaseController {
    public function __construct() {
        parent::__construct(__FILE__);
    }

    /**
     * @ApiDoc(
     *  resource = true,
     *  section = "Question",
     *  description = "添加回答",
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
     *          "description" = "问题唯一标识",
     *      },
     *      {
     *          "name" = "Content",
     *          "dataType" = "string",
     *          "required" = true,
     *          "format" = "1000个字符以内",
     *          "description" = "回答的内容",
     *      },
     *      {
     *          "name" = "Type",
     *          "dataType" = "integer",
     *          "required" = true,
     *          "format" = "0",
     *          "description" = "0为文字回答",
     *      },
     *  },
     *  output = {
     *      "class" = "QuestionBundle\Entity\Wrapper\QuestionAnswerWrapper",
     *  },
     *  views = {"version1", "default"},
     * )
     *
     * @Route("/api/v1/question/answer/add/", methods="POST")
     */
    public function addAction() {
        $quid = $this->getPost('Quid');
        $content = $this->getPost('Content');
        $type = $this->getPost('Type');

        $questionService = $this->get('question.questionservice');
        $answerService = $this->get('question.answerservice');
        $wrapperService = $this->get('question.wrapperservice');
        $userService = $this->get('user.userservice');

        $em = $this->getDoctrine()->getManager();
        try {
            $em->getConnection()->beginTransaction();
            $this->checkIfLogin(true);

            if (empty($type)) {
                $type = 0;
            }
            // TODO: 检查问题是否已经到期
            $question = $questionService->getQuestionByQuid($quid);
            if (!UtilService::isValidObj($question)) {
                $this->throwNewException(BaseConst::STATUS_ERROR_COMMON, '问题不存在');
            }
            StringUtilService::checkIsValidString($content, '评论内容不能为空');

            $answer = $answerService->addAnswer($question->getId(), $this->userId, $content, $type);
            $answerNum = $question->getAnswerNum();
            $question = $questionService->updateQuestionAnswerNum($question, ++$answerNum);

            $em->getConnection()->commit();

            $this->status = BaseConst::STATUS_SUCCESS;
            $this->data = $wrapperService->getAnswerWrapper($answer);
            $this->msg = '添加回答成功';
        } catch (\Exception $e) {
            $em->getConnection()->rollback();
            $em->close();
            $this->printExceptionToLog($e);
        }

        return $this->getJsonResponse();
    }

    /** 
     * @ApiDoc(
     *  resource = true,
     *  section = "Question",
     *  description = "根据AUID获取问题回答",
     *  tags = {
     *      "stable" = "#23fd09",
     *      "cyy" = "#607f8d"
     *  },
     *  requirements = {
     *      {
     *          "name" = "auid",
     *          "requirement" = "true",
     *          "dataType" = "string",
     *          "description" = "回答对外标识",
     *      },
     *  },
     *  output = {
     *      "class" = "QuestionBundle\Entity\Wrapper\QuestionAnswerWrapper",
     *  },
     *  views = {"version1", "default"},
     * )
     *
     * @Route("/api/v1/question/answer/detail/{auid}", methods="GET")
     */
    public function detailAction($auid) {
        $answerService = $this->get('question.answerservice');
        $wrapperService = $this->get('question.wrapperservice');

        try {
            $this->checkIfLogin(false);

            $answer = $answerService->getAnswerByAuid($auid);
            if (!UtilService::isValidObj($answer)) {
                $this->throwNewException(BaseConst::STATUS_ERROR_COMMON, "不存在该回答");
            }

            $this->status = BaseConst::STATUS_SUCCESS;
            $this->data = $wrapperService->getAnswerWrapper($answer);
            $this->msg = "获取问题回答详情成功";
        } catch (\Exception $e) {
            $this->printExceptionToLog($e);
        }

        return $this->getJsonResponse();
    }

    /**
     * @ApiDoc(
     *  resource = true,
     *  section = "Question",
     *  description = "问题回答列表",
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
     *          "description" = "问题唯一标识",
     *      },
     *      {
     *          "name" = "OrderStr",
     *          "dataType" = "string",
     *          "required" = false,
     *          "format" = "空或者后台传的字符串",
     *          "description" = "为空是刷新，不为空则为加载更多"
     *      },
     *  },
     *  output = {
     *      "class" = "QuestionBundle\Entity\Wrapper\AnswerListWrapper",
     *  },
     *  views = {"version1", "default"},
     * )
     *
     * @Route("api/v1/question/answer/list/", methods="POST")
     */
    public function listAction() {
        $quid = $this->getPost('Quid');
        $orderStr = $this->getPost('OrderStr');

        $answerService = $this->get('question.answerservice');
        $questionService = $this->get('question.questionservice');
        $wrapperService = $this->get('question.wrapperservice');

        try {
            $question = $questionService->getQuestionByQuid($quid);
            if (!UtilService::isValidObj($question)) {
                $this->throwNewException(BaseConst::STATUS_ERROR_COMMON, '问题不存在');
            }

            $answerList = $answerService->getNewAnswerList($question->getId(), $orderStr);

            $this->status = BaseConst::STATUS_SUCCESS;
            $this->data = $wrapperService->getAnswerListWrapper($answerList, $orderStr);
            $this->msg = "获取问题回答列表成功";
        } catch (\Exception $e) {
            $this->printExceptionToLog($e);
        }
        return $this->getJsonResponse();
    }

    /**
     * @ApiDoc(
     *  resource = true,
     *  section = "Question",
     *  description = "挑选最佳回答",
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
     *          "description" = "问题唯一标识",
     *      },
     *      {
     *          "name" = "Auid",
     *          "dataType" = "string",
     *          "required" = true,
     *          "format" = "32位",
     *          "description" = "回答唯一标识",
     *      },
     *  },
     *  output = {
     *      "class" = "BaseBundle\Entity\Wrapper\BoolWrapper",
     *  },
     *  views = {"version1", "default"},
     * )
     *
     * @Route("/api/v1/question/answer/adopt/", methods="POST")
     */
    public function adoptAction() {
        $quid = $this->getPost('Quid');
        $auid = $this->getPost('Auid');

        $userService = $this->get('user.userservice');
        $questionService = $this->get('question.questionservice');
        $answerService = $this->get('question.answerservice');
        $wrapperService = $this->get('base.wrapperservice');
        $orderService = $this->get('wallet.orderservice');

        $em = $this->getDoctrine()->getManager();
        try {
            $em->getConnection()->beginTransaction();
            $this->checkIfLogin(true);

            $question = $questionService->getQuestionByQuid($quid);
            if (!UtilService::isValidObj($question)) {
                $this->throwNewException(BaseConst::STATUS_ERROR_COMMON, '问题不存在');
            }
            if ($this->userId != $question->getUserId()) {
                $this->throwNewException(BaseConst::STATUS_ERROR_COMMON, '不是自己提问的问题');
            }
            if (TimeUtilService::isBiggerTime(TimeUtilService::getCurrentDateTime(), $question->getExpireTime())) {
                $this->throwNewException(BaseConst::STATUS_ERROR_COMMON, '问题已过期，不能采纳回答');
            }
            if (QuestionConst::QUESTION_STATUS_ADOPTED == $question->getStatus() or QuestionConst::QUESTION_STATUS_ADOPTED_PAID_BEST == $question->getStatus()) {
                $this->throwNewException(BaseConst::STATUS_ERROR_COMMON, '已经采纳过回答');
            }
            $answer = $answerService->getAnswerByAuid($auid);
            if (!UtilService::isValidObj($answer)) {
                $this->throwNewException(BaseConst::STATUS_ERROR_COMMON, "回答不存在");
            }
            if ($question->getId() != $answer->getQuestionId()) {
                $this->throwNewException(BaseConst::STATUS_ERROR_COMMON, '回答与问题不符');
            }
            if ($this->userId == $answer->getUserId()) {
                $this->throwNewException(BaseConst::STATUS_ERROR_COMMON, '不能选择自己的回答');
            }
            if (AnswerConst::ANSWER_STATUS_ADOPTED == $answer->getStatus() or AnswerConst::ANSWER_STATUS_ADOPTED_PAID == $answer->getStatus()) {
                $this->throwNewException(BaseConst::STATUS_ERROR_COMMON, '已经采纳过回答');
            }

            $answer = $answerService->updateStatus($answer, AnswerConst::ANSWER_STATUS_ADOPTED);
            $question = $questionService->updateStatus($question, QuestionConst::QUESTION_STATUS_ADOPTED);

            $order = $orderService->addOrder($this->userId, $answer->getUserId(), $question->getId(), $answer->getId(), OrderConst::ORDER_TRADE_TYPE_ADOPT_ANSWER, $question->getReward(), OrderConst::ORDER_PAYMENT_TYPE_UNPAY);
            $targetUser = $userService->getUserById($answer->getUserId());
            if (!UtilService::isValidObj($targetUser)) {
                $this->throwNewException(BaseConst::STATUS_ERROR_COMMON, "回答者不存在");
            }
            $userService->increaseBalance($targetUser, $question->getReward());
            $answer = $answerService->updateStatus($answer, AnswerConst::ANSWER_STATUS_ADOPTED_PAID);
            $question = $questionService->updateStatus($question, QuestionConst::QUESTION_STATUS_ADOPTED_PAID_BEST);

            $em->getConnection()->commit();

            $this->status = BaseConst::STATUS_SUCCESS;
            $this->data = $wrapperService->getBoolWrapper(true);
            $this->msg = '选择最佳回答成功';
        } catch (\Exception $e) {
            $em->getConnection()->rollback();
            $em->close();
            $this->printExceptionToLog($e);
        }

        return $this->getJsonResponse();
    }
}
