<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use BaseBundle\Controller\ApiBaseController;

use BaseBundle\Container\BaseConst;
use QuestionBundle\Container\QuestionConst;

class QuestionController extends ApiBaseController {
    public function __construct() {
        parent::__construct(__FILE__);
    }

    /**
     * @ApiDoc(
     *  resource = true,
     *  section = "User",
     *  description = "我的提问列表",
     *  tags = {
     *      "stable" = "#23fd09",
     *      "cyy" = "#607d8b"
     *  },
     *  parameters = {
     *      {
     *          "name" = "StatusType",
     *          "dataType" = "integer",
     *          "required" = true,
     *          "format" = "0, 1, 2",
     *          "description" = "问题状态：0为全部，1为未解决，2为已解决"
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
     *      "class" = "QuestionBundle\Entity\Wrapper\QuestionListWrapper",
     *  },
     *  views = {"version1", "default"},
     * )
     *
     * @Route("api/v1/user/question/list/", methods="POST")
     */
    public function questionListAction() {
        $statusType = intval($this->getPost('StatusType'));
        $orderStr = $this->getPost('OrderStr');

        $questionService = $this->get('question.questionservice');
        $wrapperService = $this->get('question.wrapperservice');

        try {
            $this->checkIfLogin(true);

            if (empty($statusType)) {
                $statusType = QuestionConst::QUESTION_STATUS_TYPE_ALL;
            }

            $questionList = $questionService->getUserQuestionListByStatusType($this->userId, $statusType, $orderStr);

            $this->status = BaseConst::STATUS_SUCCESS;
            $this->data = $wrapperService->getQuestionListWrapper($questionList, $orderStr);
            $this->msg = "获取我的提问列表成功";
        } catch (\Exception $e) {
            $this->printExceptionToLog($e);
        }
        return $this->getJsonResponse();
    }
}
