<?php

namespace QuestionBundle\Container;

use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

use BaseBundle\Container\BaseService;
use UtilBundle\Container\UtilService;
use UtilBundle\Container\TimeUtilService;
use UtilBundle\Container\StringUtilService;

use QuestionBundle\Entity\Wrapper\QuestionWrapper;
use QuestionBundle\Entity\Wrapper\QuestionAnswerWrapper;
use QuestionBundle\Entity\Wrapper\QuestionListWrapper;

class WrapperService extends BaseService {
    public function __construct(EntityManager $em, Logger $logger, ContainerInterface $container, RequestStack $requestStack) {
        parent::__construct($em, $logger, $container, $requestStack);
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-19
     *
     * 返回Question的Wrapper
     */
    public function getQuestionWrapper($question, $showUser = true) {
        if (!UtilService::isValidObj($question)) {
            return array();
        }
        $userService = $this->getUserService();
        $userWrapperService = $this->getUserWrapperService();

        $wrapper = new QuestionWrapper();

        $wrapper->setQuestion($question);

        $userWrapper = null;
        if ($showUser) {
            $user = $userService->getUserById($question->getUserId());
            $userWrapper = $userWrapperService->getUserWrapper($user);
        }
        $wrapper->setUserWrapper($userWrapper);

        return $wrapper;
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-19
     *
     * 返回QuestionWrapper的List
     */
    public function getQuestionListWrapper($questionList, $orderStr, $showUser = true) {
        $wrapper = new QuestionListWrapper();

        $questionWrapperList = array();
        foreach ($questionList as $question) {
            $questionWrapper = $this->getQuestionWrapper($question, $showUser);
            $questionWrapperList[] = $questionWrapper;
        }
        $wrapper->setQuestionWrapperList($questionWrapperList);
        $wrapper->setOrderStr($orderStr);

        return $wrapper;
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-22
     *
     * 返回QuestionAnswer的Wrapper
     */
    public function getQuestionAnswerWrapper($questionAnswer, $showUser = true) {
        if (!UtilService::isValidObj($questionAnswer)) {
            return array();
        }
        $userService = $this->getUserService();
        $userWrapperService = $this->getUserWrapperService();

        $wrapper = new QuestionAnswerWrapper();

        $wrapper->setQuestionAnswer($questionAnswer);

        $userWrapper = null;
        if ($showUser) {
            $user = $userService->getUserById($questionAnswer->getUserId());
            $userWrapper = $userWrapperService->getUserWrapper($user);
        }
        $wrapper->setUserWrapper($userWrapper);

        return $wrapper;
    }
}
