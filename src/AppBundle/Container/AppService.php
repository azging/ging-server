<?php

namespace AppBundle\Container;

use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

use BaseBundle\Container\BaseService;

class AppService extends BaseService {
    private $appFeedbackRepo;

    public function __construct(EntityManager $em, Logger $logger, ContainerInterface $container, RequestStack $requestStack) {
        parent::__construct($em, $logger, $container, $requestStack);
        $this->appFeedbackRepo = $this->em->getRepository('AppBundle:AppFeedback');
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-22
     *
     * 添加意见反馈
     */
    public function addFeedback($userId, $content) {
        $infoArr = array(
            'UserId' => $userId,
            'Content' => $content,
        );
        return $this->appFeedbackRepo->insertAppFeedback($infoArr);
    }
}
