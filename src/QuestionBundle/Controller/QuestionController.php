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
use QuestionBundle\Container\QuestionConst;

class QuestionController extends ApiBaseController {
    public function __construct() {
        parent::__construct(__FILE__);
    }

    /**
     * @ApiDoc(
     *  resource = true,
     *  section = "Question",
     *  description = "发布问题接口",
     *  tags = {
     *      "stable" = "#23fd09",
     *      "cyy" = "#607d8b"
     *  },
     *  parameters = {
     *      {
     *          "name" = "Title",
     *          "dataType" = "string",
     *          "required" = true,
     *          "format" = "问题标题",
     *          "description" = "问题标题"
     *      },
     *      {
     *          "name" = "Description",
     *          "dataType" = "string",
     *          "required" = true,
     *          "format" = "问题描述",
     *          "description" = "问题描述"
     *      },
     *      {
     *          "name" = "QuestionUrls",
     *          "dataType" = "string",
     *          "required" = true,
     *          "format" = "最多9张",
     *          "description" = "问题的图片url列表"
     *      },
     *      {
     *          "name" = "Reward",
     *          "dataType" = "float",
     *          "required" = true,
     *          "format" = "6.66",
     *          "description" = "报酬金额"
     *      },
     *      {
     *          "name" = "IsAnonymous",
     *          "dataType" = "integer",
     *          "required" = true,
     *          "format" = "0, 1",
     *          "description" = "是否匿名，0为不匿名，1为匿名"
     *      },
     *  },
     *  output = {
     *      "class" = "QuestionBundle\Entity\Wrapper\QuestionWrapper",
     *  },
     *  views = {"version1", "default"},
     * )
     *
     * @Route("/api/v1/question/publish/", methods="POST")
     */
    public function publishAction() {
        $title = $this->getPost('Title');
        $description = $this->getPost('Description');
        $questionUrls = $this->getPost('QuestionUrls');
        $reward = floatval($this->getPost('Reward'));
        $isAnonymous = intval($this->getPost('IsAnonymous'));

        $userService = $this->get('user.userservice');
        $questionService = $this->get('question.questionservice');
        $wrapperService = $this->get('question.wrapperservice');

        try {
            $this->checkIfLogin(true);

            StringUtilService::checkIsValidString($title, '请填写标题');
            StringUtilService::checkIsValidString($description, '请填写描述');
            if ($reward <= 0) {
                $this->throwNewException(BaseConst::STATUS_ERROR_COMMON, "请填写报酬");
            }

            $cityId = $this->getCityId();
            $lng = floatval($this->getCookieData('LocLng'));
            $lat = floatval($this->getCookieData('LocLat'));

            $expireTime = TimeUtilService::getDateTimeAfterHours(QuestionConst::QUESTION_EXPIRE_HOURS);

            $question = $questionService->publishQuestion($this->userId, $cityId, $lng, $lat, $title, $description, $questionUrls, $reward, $isAnonymous, $expireTime);

            $this->status = BaseConst::STATUS_SUCCESS;
            $this->data = $wrapperService->getQuestionWrapper($question);
            $this->msg = "问题发布成功";
        } catch (\Exception $e) {
            $this->printExceptionToLog($e);
        }

        return $this->getJsonResponse();
    }

    /** 
     * @ApiDoc(
     *  resource = true,
     *  section = "Question",
     *  description = "根据QUID获取问题",
     *  tags = {
     *      "stable" = "#23fd09",
     *      "cyy" = "#607f8d"
     *  },
     *  requirements = {
     *      {
     *          "name" = "quid",
     *          "requirement" = "true",
     *          "dataType" = "string",
     *          "description" = "问题对外标识",
     *      },
     *  },
     *  output = {
     *      "class" = "QuestionBundle\Entity\Wrapper\QuestionWrapper",
     *  },
     *  views = {"version1", "default"},
     * )
     *
     * @Route("/api/v1/question/detail/{quid}", methods="GET")
     */
    public function detailAction($quid) {
        $questionService = $this->get('question.questionservice');
        $wrapperService = $this->get('question.wrapperservice');

        try {
            $this->checkIfLogin(false);

            $question = $questionService->getQuestionByQuid($quid);
            if (!UtilService::isValidObj($question)) {
                $this->throwNewException(BaseConst::STATUS_ERROR_COMMON, "不存在该问题");
            }

            $this->status = BaseConst::STATUS_SUCCESS;
            $this->data = $wrapperService->getQuestionWrapper($question);
            $this->msg = "获取问题详情成功";
        } catch (\Exception $e) {
            $this->printExceptionToLog($e);
        }

        return $this->getJsonResponse();
    }

    /**
     * @ApiDoc(
     *  resource = true,
     *  section = "Question",
     *  description = "最新问题列表",
     *  tags = {
     *      "stable" = "#23fd09",
     *      "cyy" = "#607d8b"
     *  },
     *  parameters = {
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
     * @Route("api/v1/question/list/new/", methods="POST")
     */
    public function listNewAction() {
        $orderStr = $this->getPost('OrderStr');

        $questionService = $this->get('question.questionservice');
        $wrapperService = $this->get('question.wrapperservice');

        try {
            $questionList = $questionService->getNewQuestionList($orderStr);

            $this->status = BaseConst::STATUS_SUCCESS;
            $this->data = $wrapperService->getQuestionListWrapper($questionList, $orderStr);
            $this->msg = "获取最新问题列表成功";
        } catch (\Exception $e) {
            $this->printExceptionToLog($e);
        }
        return $this->getJsonResponse();
    }

    /**
     * @ApiDoc(
     *  resource = true,
     *  section = "Question",
     *  description = "热门问题列表",
     *  tags = {
     *      "stable" = "#23fd09",
     *      "cyy" = "#607d8b"
     *  },
     *  parameters = {
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
     * @Route("api/v1/question/list/hot/", methods="POST")
     */
    public function listHotAction() {
        $orderStr = $this->getPost('OrderStr');

        $questionService = $this->get('question.questionservice');
        $wrapperService = $this->get('question.wrapperservice');

        try {
            $questionList = $questionService->getHotQuestionList($orderStr);

            $this->status = BaseConst::STATUS_SUCCESS;
            $this->data = $wrapperService->getQuestionListWrapper($questionList, $orderStr);
            $this->msg = "获取热门问题列表成功";
        } catch (\Exception $e) {
            $this->printExceptionToLog($e);
        }
        return $this->getJsonResponse();
    }

    /**
     * @ApiDoc(
     *  resource = true,
     *  section = "Question",
     *  description = "附近问题列表",
     *  tags = {
     *      "stable" = "#23fd09",
     *      "cyy" = "#607d8b"
     *  },
     *  parameters = {
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
     * @Route("api/v1/question/list/nearby/", methods="POST")
     */
    public function listNearbyAction() {
        $orderStr = $this->getPost('OrderStr');

        $questionService = $this->get('question.questionservice');
        $wrapperService = $this->get('question.wrapperservice');

        try {
            $questionList = $questionService->getNearbyQuestionList($orderStr);

            $this->status = BaseConst::STATUS_SUCCESS;
            $this->data = $wrapperService->getQuestionListWrapper($questionList, $orderStr);
            $this->msg = "获取附近问题列表成功";
        } catch (\Exception $e) {
            $this->printExceptionToLog($e);
        }
        return $this->getJsonResponse();
    }
}
