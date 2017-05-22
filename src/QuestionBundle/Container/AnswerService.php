<?php

namespace QuestionBundle\Container;

use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

use BaseBundle\Container\BaseService;
use UtilBundle\Container\TimeUtilService;
use UtilBundle\Container\UtilService;

use BaseBundle\Container\BaseConst;

class AnswerService extends BaseService {
    private $questionAnswerRepo;
    private $userService;

    public function __construct(EntityManager $em, Logger $logger, ContainerInterface $container, RequestStack $requestStack) {
        parent::__construct($em, $logger, $container, $requestStack);
        $this->questionAnswerRepo = $this->em->getRepository('QuestionBundle:QuestionAnswer');
        $this->userService = $this->container->get('user.userservice');
    }   

    /** 
     * cyy, since 1.0
     *
     * 2017-05-22
     *
     * 根据id查找Answer
     */
    public function getAnswerById($id, $isValid = true) {
        return $this->questionAnswerRepo->selectOneAnswerByProp('id', $id, $isValid);
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-22
     *
     * 根据auid查找Answer
     */
    public function getAnswerByAuid($auid, $isValid = true) {
        return $this->questionAnswerRepo->selectOneAnswerByProp('auid', $auid, $isValid);
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-22
     *
     * 添加回答
     */
    public function addAnswer($questionId, $userId, $content, $type) {
        $infoArr = array(
            'QuestionId' => $questionId,
            'UserId' => $userId,
            'Content' => $content,
            'Type' => $type,
        );
        return $this->questionAnswerRepo->insertAnswer($infoArr);
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-22
     *
     * 最新回答列表
     */
    public function getNewAnswerList(&$orderStr) {
        if (empty($orderStr)) {
            $orderStr = TimeUtilService::timeToStr(TimeUtilService::getDateTimeAfterMinutes("+5"));
        }

        $qb = $this->em->createQueryBuilder();
        $q = $qb->select('qa')
            ->from('QuestionBundle:QuestionAnswer', 'qa')
            ->where('qa.createTime < :CreateTime')
            ->andWhere('qa.isValid = 1')
            ->setParameter('CreateTime', $orderStr)
            ->addOrderBy('qa.createTime', 'DESC')
            ->setMaxResults(BaseConst::LIST_DEFAULT_NUM)
            ->getQuery();
        $answerArr = $q->getResult();

        if (UtilService::isValidArr($answerArr)) {
            $answer = end($answerArr);
            $orderStr = TimeUtilService::timeToStr($answer->getCreateTime());
        }
        return $answerArr;
    }
}
