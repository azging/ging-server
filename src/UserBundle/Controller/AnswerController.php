<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use BaseBundle\Controller\ApiBaseController;

use BaseBundle\Container\BaseConst;
use QuestionBundle\Container\AnswerConst;

class AnswerController extends ApiBaseController {
    public function __construct() {
        parent::__construct(__FILE__);
    }

    /**
     * @ApiDoc(
     *  resource = true,
     *  section = "User",
     *  description = "我的回答列表",
     *  tags = {
     *      "stable" = "#23fd09",
     *      "cyy" = "#607d8b"
     *  },
     *  parameters = {
     *      {
     *          "name" = "StatusType",
     *          "dataType" = "integer",
     *          "required" = true,
     *          "format" = "0, 1",
     *          "description" = "回答状态：0为全部，1为被采纳获得红包"
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
     * @Route("api/v1/user/answer/list/", methods="POST")
     */
    public function answerListAction() {
        $statusType = intval($this->getPost('StatusType'));
        $orderStr = $this->getPost('OrderStr');

        $answerService = $this->get('question.answerservice');
        $wrapperService = $this->get('question.wrapperservice');

        try {
            $this->checkIfLogin(true);

            if (empty($statusType)) {
                $statusType = AnswerConst::ANSWER_STATUS_TYPE_ALL;
            }

            $answerList = $answerService->getUserAnswerListByStatusType($this->userId, $statusType, $orderStr);

            $this->status = BaseConst::STATUS_SUCCESS;
            $this->data = $wrapperService->getAnswerListWrapper($answerList, $orderStr);
            $this->msg = "获取我的回答列表成功";
        } catch (\Exception $e) {
            $this->printExceptionToLog($e);
        }
        return $this->getJsonResponse();
    }
}
