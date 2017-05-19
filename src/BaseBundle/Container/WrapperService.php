<?php

namespace BaseBundle\Container;

use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

use BaseBundle\Container\BaseService;

use BaseBundle\Entity\Wrapper\BoolWrapper;

class WrapperService extends BaseService {
    public function __construct(EntityManager $em, Logger $logger, ContainerInterface $container, RequestStack $requestStack) {
        parent::__construct($em, $logger, $container, $requestStack);
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-17
     *
     * 返回Bool的Wrapper
     */
    public function getBoolWrapper($isPass) {
        $wrapper = new BoolWrapper();

        $wrapper->setIsPass($isPass);

        return $wrapper;
    }

}
