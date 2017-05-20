<?php

namespace QuestionBundle\Container;

use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

use BaseBundle\Container\BaseService;

class QuestionService extends BaseService {
    private $questionRepo;
    private $userService;

    public function __construct(EntityManager $em, Logger $logger, ContainerInterface $container, RequestStack $requestStack) {
        parent::__construct($em, $logger, $container, $requestStack);
        $this->questionRepo = $this->em->getRepository('QuestionBundle:Question');
        $this->userService = $this->container->get('user.userservice');
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-19
     *
     * 根据id查找Question
     */
    public function getQuestionById($id, $isValid = true) {
        return $this->questionRepo->selectOneQuestionByProp('id', $id, $isValid);
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-19
     *
     * 根据quid查找Question
     */
    public function getQuestionByQuid($quid, $isValid = true) {
        return $this->questionRepo->selectOneQuestionByProp('quid', $quid, $isValid);
    }

    /**
     * cyy, since 6.0
     *
     * 2017-05-19
     *
     * 发布问题
     */
    public function publishQuestion($userId, $cityId, $lng, $lat, $title, $description, $photoUrls, $reward, $isAnonymous, $expireTime) {
        $infoArr = array(
            'UserId' => $userId,
            'Title' => $title,
            'Description' => $description,
            'PhotoUrls' => $photoUrls,
            'Reward' => $reward,
            'IsAnonymous' => $isAnonymous,
            'CityId' => $cityId,
            'Lng' => $lng,
            'Lat' => $lat,
            'ExpireTime' => $expireTime,
        );
        return $this->questionRepo->insertQuestion($infoArr);
    }
}
