<?php

namespace WalletBundle\Container;

use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

use BaseBundle\Container\BaseService;

use WalletBundle\Entity\Wrapper\WalletWrapper;

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
}
