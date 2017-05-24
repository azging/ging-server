<?php

namespace WalletBundle\Container;

use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

use BaseBundle\Container\BaseService;
use UtilBundle\Container\UtilService;

use WalletBundle\Entity\Wrapper\WalletWrapper;
use WalletBundle\Entity\Wrapper\OrderWrapper;

class WrapperService extends BaseService {
    public function __construct(EntityManager $em, Logger $logger, ContainerInterface $container, RequestStack $requestStack) {
        parent::__construct($em, $logger, $container, $requestStack);
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-23
     *
     * 返回Wallet的Wrapper
     */
    public function getWalletWrapper($user) {
        $wrapper = new WalletWrapper();

        $wrapper->setBalance($user->getWalletBalance());

        return $wrapper;
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-23
     *
     * 返回Order的Wrapper
     */
    public function getOrderWrapper($order, $balancePrepay = null, $wxPrepay = null, $showUser = false) {
        if (!UtilService::isValidObj($order)) {
            return array();
        }
        $userService = $this->getUserService();
        $orderService = $this->getOrderService();

        $wrapper = new OrderWrapper();

        $wrapper->setOrder($order);
        $wrapper->setWxPrepay($wxPrepay);
        $wrapper->setBalancePrepay($balancePrepay);

        if (true == $showUser) {
            $user = $userService->getUserById($order->getUserId(), false);
            $wrapper->setUser($user);
        }

        return $wrapper;
    }
}
