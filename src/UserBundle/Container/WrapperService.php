<?php

namespace UserBundle\Container;

use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

use BaseBundle\Container\BaseService;
use UtilBundle\Container\UtilService;

use UserBundle\Entity\Wrapper\UserWrapper;
use UserBundle\Entity\Wrapper\UserListWrapper;

class WrapperService extends BaseService {
    public function __construct(EntityManager $em, Logger $logger, ContainerInterface $container, RequestStack $requestStack) {
        parent::__construct($em, $logger, $container, $requestStack);
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-18
     *
     * 返回用户的Wrapper
     */
    public function getUserWrapper($user) {
        if (!UtilService::isValidObj($user)) {
            return array();
        }
        $wrapper = new UserWrapper();

        $wrapper->setUser($user);

        return $wrapper;
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-18
     *
     * 返回用户的Wrapper List
     */
    public function getUserListWrapper($userList, $orderStr) {
        $wrapper = new UserListWrapper();

        $userWrapperList = array();
        foreach ($userList as $user) {
            $userWrapper = $this->getUserWrapper($user);
            $userWrapperList[] = $userWrapper;
        }
        $wrapper->setUserWrapperList($userWrapperList);
        $wrapper->setOrderStr($orderStr);

        return $wrapper;
    }
}
