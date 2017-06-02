<?php

namespace UtilBundle\Container;

use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

use BaseBundle\Container\BaseService;

use UtilBundle\Entity\Wrapper\TokenWrapper;

class WrapperService extends BaseService {
    public function __construct(EntityManager $em, Logger $logger, ContainerInterface $container, RequestStack $requestStack) {
        parent::__construct($em, $logger, $container, $requestStack);
    }

    /**
     * zzs, since 6.0
     *
     * 2016-09-01
     *
     * 包装七牛Token返回数据
     */
    public function getQiniuUpToken($upToken, $photoKey) {
        $wrapper = new TokenWrapper();
        $wrapper->setUpToken($upToken);
        $wrapper->setPhotoKey($photoKey);
        return $wrapper;
    }
}
