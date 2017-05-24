<?php

namespace BaseBundle\Container;

use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class BaseService {
    protected $em;
    protected $logger;
    protected $container;
    protected $requestStack;

    public function __construct(EntityManager $em, Logger $logger, ContainerInterface $container, RequestStack $requestStack) {
        $this->em = $em;
        $this->logger = $logger;
        $this->container = $container;
        $this->requestStack = $requestStack;
    }

    protected function throwNewException($status, $msg) {
        throw new \Exception($msg, $status);
    }

    protected function printInfoLog($msg) {
        $this->logger->info($msg);
    }

    protected function printErrorLog($msg) {
        $this->logger->error($msg);
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 获取Cookie Data数据
     */
    private function getCookieData($name) {
        $data = $this->requestStack->getCurrentRequest()->cookies->get($name);
        if (empty($data)) {
            $data = $this->requestStack->getCurrentRequest()->get($name);
        }
        return trim($data);
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 获取Cookie的UUID
     */
    private function getUuid() {
        return $this->getCookieData('UUID');
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 获取Cookie的LocLng
     */
    protected function getLocLng() {
        return $this->getCookieData('LocLng');
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 获取Cookie的LocLng
     */
    protected function getLocLat() {
        return $this->getCookieData('LocLat');
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 获取Cookie的CityId
     */
    protected function getCityId() {
        return $this->getCookieData('CityId');
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 获取登录的User
     */
    protected function getLoginUser($isValid = true) {
        //TODO: 是否需要CID
        $userService = $this->getUserService();
        return $userService->getUserByUuid($this->getUuid(), $isValid);
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 获取AppWrapperService
     */
    protected function getAppWrapperService() {
        return $this->container->get('app.wrapperservice');
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 获取UserService
     */
    protected function getUserService() {
        return $this->container->get('user.userservice');
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 获取UserWrapperService
     */
    protected function getUserWrapperService() {
        return $this->container->get('user.wrapperservice');
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 获取QuestionService
     */
    protected function getQuestionService() {
        return $this->container->get('question.questionservice');
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 获取AnswerService
     */
    protected function getAnswerService() {
        return $this->container->get('question.answerservice');
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 获取QuestionCommentService
     */
    protected function getQuestionCommentService() {
        return $this->container->get('question.commentservice');
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 获取QuestionWrapperService
     */
    protected function getQuestionWrapperService() {
        return $this->container->get('question.wrapperservice');
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 获取PlaceService
     */
    protected function getPlaceService() {
        return $this->container->get('place.placeservice');
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 获取OrderService
     */
    protected function getOrderService() {
        return $this->container->get('wallet.orderservice');
    }

}
