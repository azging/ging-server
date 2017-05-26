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
use QuestionBundle\Container\QuestionConst;
use UtilBundle\Container\UtilConst;

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
    public function publishQuestion($userId, $cityId, $lng, $lat, $title, $description, $questionUrls, $reward, $isAnonymous, $expireTime) {
        $infoArr = array(
            'UserId' => $userId,
            'Title' => $title,
            'Description' => $description,
            'QuestionUrls' => $questionUrls,
            'Reward' => $reward,
            'IsAnonymous' => $isAnonymous,
            'CityId' => $cityId,
            'Lng' => $lng,
            'Lat' => $lat,
            'ExpireTime' => $expireTime,
        );
        return $this->questionRepo->insertQuestion($infoArr);
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-22
     *
     * 更新问题状态
     */
    public function updateStatus($question, $status) {
        $infoArr = array(
            'Status' => $status,
        );
        return $this->questionRepo->updateQuestion($question, $infoArr);
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-20
     *
     * 最新问题列表
     */
    public function getNewQuestionList(&$orderStr) {
        if (empty($orderStr)) {
            $orderStr = TimeUtilService::timeToStr(TimeUtilService::getDateTimeAfterMinutes("+5"));
        }
        $nowTime = TimeUtilService::dateToStr(TimeUtilService::getCurrentDateTime());

        $qb = $this->em->createQueryBuilder();
        $q = $qb->select('q')
            ->from('QuestionBundle:Question', 'q')
            ->where('q.createTime < :CreateTime')
            ->andWhere('q.isValid = 1')
            ->andWhere('q.expireTime < :ExpireTime')
            ->andWhere('q.status = :Status')
            ->setParameter('CreateTime', $orderStr)
            ->setParameter('ExpireTime', $nowTime)
            ->setParameter('Status', QuestionConst::QUESTION_STATUS_ANSWERING)
            ->addOrderBy('q.createTime', 'DESC')
            ->setMaxResults(BaseConst::LIST_DEFAULT_NUM)
            ->getQuery();
        $questionArr = $q->getResult();

        if (UtilService::isValidArr($questionArr)) {
            $question = end($questionArr);
            $orderStr = TimeUtilService::timeToStr($question->getCreateTime());
        }
        return $questionArr;
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-20
     *
     * 热门问题列表
     */
    public function getHotQuestionList(&$orderStr) {
        if (empty($orderStr)) {
            $orderStr = QuestionConst::QUESTION_MAX_WEIGHT;
        }
        $nowTime = TimeUtilService::dateToStr(TimeUtilService::getCurrentDateTime());

        $qb = $this->em->createQueryBuilder();
        $q = $qb->select('q')
            ->from('QuestionBundle:Question', 'q')
            ->where('q.weight < :Weight')
            ->andWhere('q.isValid = 1')
            ->andWhere('q.expireTime < :ExpireTime')
            ->andWhere('q.status = :Status')
            ->setParameter('Weight', $orderStr)
            ->setParameter('ExpireTime', $nowTime)
            ->setParameter('Status', QuestionConst::QUESTION_STATUS_ANSWERING)
            ->addOrderBy('q.weight', 'DESC')
            ->setMaxResults(BaseConst::LIST_DEFAULT_NUM)
            ->getQuery();
        $questionArr = $q->getResult();

        if (UtilService::isValidArr($questionArr)) {
            $question = end($questionArr);
            $orderStr = $question->getWeight();
        }
        return $questionArr;
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-20
     *
     * 附近问题列表
     */
    public function getNearbyQuestionList(&$orderStr) {
        if (empty($orderStr)) {
            $orderStr = 0;
        }
        $nowTime = TimeUtilService::dateToStr(TimeUtilService::getCurrentDateTime());

        $lng = $this->getLocLng();
        $lat = $this->getLocLat();
        if (!UtilService::checkIsLoc($lng, $lat)) {
            $lng = UtilConst::LOC_LNG_DEFAULT;
            $lat = UtilConst::LOC_LAT_DEFAULT;
        }

        $getDistanceSql = '(ROUND('
            . ' 6378.138 * 2 * ASIN(SQRT('
            . ' POWER(SIN((q.lat * PI() / 180 - :Lat * PI() / 180) / 2), 2) +'
            . ' COS(q.lat * PI() / 180) *'
            . ' COS(:Lat * PI() / 180) *'
            . ' POWER(SIN((q.lng * PI() / 180 - :Lng * PI() / 180) / 2), 2)'
            . ')) * 1000)) as distance';

        $qb = $this->em->createQueryBuilder();
        $q = $qb->select('q as Question')
            ->addSelect($getDistanceSql)
            ->from('QuestionBundle:Question', 'q')
            ->where('q.isValid = 1')
            ->andWhere('q.expireTime < :ExpireTime')
            ->andWhere('q.status = :Status')
            ->setParameter('ExpireTime', $nowTime)
            ->setParameter('Status', QuestionConst::QUESTION_STATUS_ANSWERING)
            ->setParameter('Lat', $lat)
            ->setParameter('Lng', $lng)
            ->having('distance > :Distance')
            ->setParameter('Distance', $orderStr)
            ->orderBy('distance', 'ASC')
            ->setMaxResults(BaseConst::LIST_DEFAULT_NUM)
            ->getQuery();
        $resultArr = $q->getResult();

        $questionArr = array();
        if (UtilService::isValidArr($resultArr)) {
            foreach ($resultArr as $result) {
                $questionArr[] = $result['Question'];
            }
            $orderStr = end($resultArr)['distance'];
        }
        return $questionArr;
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-20
     *
     * 根据状态类型查找用户的提问列表
     */
    public function getUserQuestionListByStatusType($userId, $statusType, &$orderStr) {
        if (empty($orderStr)) {
            $orderStr = TimeUtilService::timeToStr(TimeUtilService::getDateTimeAfterMinutes("+5"));
        }

        $qb = $this->em->createQueryBuilder();
        $q = $qb->select('q')
            ->from('QuestionBundle:Question', 'q')
            ->where('q.createTime < :CreateTime')
            ->andWhere('q.isValid = 1')
            ->andWhere('q.userId = :UserId')
            ->setParameter('UserId', $userId)
            ->setParameter('CreateTime', $orderStr);

        switch ($statusType) {
            case QuestionConst::QUESTION_STATUS_TYPE_ALL:
                break;
            case QuestionConst::QUESTION_STATUS_TYPE_UNSOLVED:
                $q = $q->andWhere('q.status = :StatusAnswer or q.status = :StatusNoAnswer or q.status = :StatusRefunded or q.status = :StatusUnAdopted or q.status = :StatusPaidFirst')
                    ->setParameter('StatusAnswer', QuestionConst::QUESTION_STATUS_ANSWERING)
                    ->setParameter('StatusNoAnswer', QuestionConst::QUESTION_STATUS_EXPIRED_NO_ANSWER)
                    ->setParameter('StatusRefunded', QuestionConst::QUESTION_STATUS_EXPIRED_NO_ANSWER_REFUNDED)
                    ->setParameter('StatusUnAdopted', QuestionConst::QUESTION_STATUS_EXPIRED_UNADOPTED)
                    ->setParameter('StatusPaidFirst', QuestionConst::QUESTION_STATUS_EXPIRED_UNADOPTED_PAID_FIRST);
                break;
            case QuestionConst::QUESTION_STATUS_TYPE_SOLVED:
                $q = $q->andWhere('q.status = :StatusAdopted or q.status = :StatusPaid')
                    ->setParameter('StatusAdopted', QuestionConst::QUESTION_STATUS_ADOPTED)
                    ->setParameter('StatusPaid', QuestionConst::QUESTION_STATUS_ADOPTED_PAID_BEST);
                break;
        }

        $q = $q->addOrderBy('q.createTime', 'DESC')
            ->setMaxResults(BaseConst::LIST_DEFAULT_NUM)
            ->getQuery();
        $questionArr = $q->getResult();

        if (UtilService::isValidArr($questionArr)) {
            $question = end($questionArr);
            $orderStr = TimeUtilService::timeToStr($question->getCreateTime());
        }
        return $questionArr;
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-22
     *
     * 更新回答数量
     */
    public function updateQuestionAnswerNum($question, $answerNum) {
        $infoArr = array(
            'AnswerNum' => $answerNum,
        );

        return $this->questionRepo->updateQuestion($question, $infoArr);
    }
}
