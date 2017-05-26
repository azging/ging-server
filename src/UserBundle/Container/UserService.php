<?php

namespace UserBundle\Container;

use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

use BaseBundle\Container\BaseService;
use UtilBundle\Container\UtilService;
use UtilBundle\Container\StringUtilService;

use BaseBundle\Container\BaseConst;

use UserBundle\Entity\User;


class UserService extends BaseService {
    private $userRepo;

    public function __construct(EntityManager $em, Logger $logger, ContainerInterface $container, RequestStack $requestStack) {
        parent::__construct($em, $logger, $container, $requestStack);
        $this->userRepo = $this->em->getRepository('UserBundle:User');
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-18
     *
     * 更新用户更新时间
     */
    public function updateUserUpdateTime($user, $updateTime) {
        $infoArr = array(
            'UpdateTime' => $updateTime,
        );

        return $this->userRepo->updateUser($user, $infoArr);
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-18
     *
     * update user info
     */
    public function updateUserInfo($user, $infoArr) {
        return $this->userRepo->updateUser($user, $infoArr);
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-18
     *
     * 根据id获取用户
     */
    public function getUserById($id, $isValid = true) {
        return $this->userRepo->selectOneUserByProp('id', $id, $isValid);
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-18
     *
     * 根据手机号查找用户
     */
    public function getUserByTelephone($tel, $isValid = true) {
        return $this->userRepo->selectOneUserByProp('telephone', $tel, $isValid);
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-18
     *
     * get user by uuid
     */
    public function getUserByUuid($uuid, $isValid = true) {
        return $this->userRepo->selectOneUserByProp('uuid', $uuid, $isValid);
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-18
     *
     * 通过第三方账号查找用户
     */
    public function getUserBySocialId($type, $socialId, $isValid = true) {
        $user = new User();
        switch ($type) {
            case UserConst::USER_LOGIN_TYPE_WECHAT:
                $user = $this->getUserByWechatId($socialId, $isValid);
                break;
        }
        return $user;
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-18
     *
     * 通过微信查找用户
     */
    public function getUserByWechatId($wechatId, $isValid = true) {
        return $this->userRepo->selectOneUserByProp('wechatId', $wechatId, $isValid);
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-18
     *
     * 根据用户权重排序
     */
    public function getUserListOrderByWeight(&$orderStr) {
        if (empty($orderStr)) {
            $orderStr = UserConst::USER_MAX_WEIGHT;
        }

        $qb = $this->em->createQueryBuilder();
        $q = $qb->select('u')
            ->from('UserBundle:User', 'u')
            ->where('u.weight < :OrderStr')
            ->andWhere('u.isValid = 1')
            ->setParameter('OrderStr', $orderStr)
            ->addOrderBy('u.weight', 'DESC')
            ->setMaxResults(UserConst::USER_MSG_RCMD_DUCKR_PAGE_NUM)
            ->getQuery();
        $users = $q->getResult();

        if (UtilService::isValidArr($users)) {
            $user = end($users);
            $orderStr = $user->getWeight();
        }
        return $users;
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-18
     *
     * 通过手机号注册用户
     */
    public function registerByTelephone($telephone) {
        $nick = substr($telephone, 0, 3);
        $nick .= '****';
        $nick .= substr($telephone, 9, 2);
        $infoArr = array(
            'Telephone' => $telephone,
            'Nick' => $nick,
            'LoginType' => UserConst::USER_LOGIN_TYPE_TELEPHONE,
        );
        return $this->userRepo->insertUser($infoArr);
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-18
     *
     * 通过第三方账号注册用户
     */
    public function registerBySocial($type, $socialId, $nick, $avatarUrl, $gender) {
        $user = new User();
        switch ($type) {
            case UserConst::USER_LOGIN_TYPE_WECHAT:
                $user = $this->registerByWechat($socialId, $nick, $avatarUrl, $gender);
                break;
        }
        return $user;
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-18
     *
     * 通过微信注册用户
     */
    public function registerByWechat($wechatId, $nick, $avatarUrl, $gender) {
        $infoArr = array(
            'WechatId' => $wechatId,
            'Nick' => $nick,
            'AvatarUrl' => $avatarUrl,
            'Gender' => $gender,
            'LoginType' => UserConst::USER_LOGIN_TYPE_WECHAT,
        );
        return $this->userRepo->insertUser($infoArr);
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-18
     *
     * 更新用户的权重
     */
    public function updateTempWeight($user, $weight) {
        if (!UtilService::isPositiveInteger($weight)) {
            $coins = 0;
        }
        $tempWeight = $weight;
        $weight = $user->getWeight() - $user->getTempWeight() + $tempWeight;
        if ($weight > UserConst::USER_MAX_WEIGHT) {
            $weight = UserConst::USER_MAX_WEIGHT;
        }
        $infoArr = array(
            'weight' => $weight,
            'tempWeight' => $tempWeight,
        );

        return $this->userRepo->updateUser($user, $infoArr);
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-18
     *
     * 更新用户的位置
     */
    public function updateUserLocation($user, $lng, $lat) {
        $infoArr = array(
            'Lng' => $lng,
            'Lat' => $lat,
        );

        return $this->userRepo->updateUser($user, $infoArr);
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-24
     *
     * 扣减用户的余额
     */
    public function deductBalance($user, $diffBalance) {
        $infoArr = array(
            'WalletBalance' => $user->getWalletBalance() - $diffBalance,
        );

        return $this->userRepo->updateUser($user, $infoArr);
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-24
     *
     * 增加用户的余额
     */
    public function increaseBalance($user, $diffBalance) {
        $infoArr = array(
            'WalletBalance' => $user->getWalletBalance() + $diffBalance,
        );

        return $this->userRepo->updateUser($user, $infoArr);
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-24
     *
     * 更新用户的余额
     */
    public function updateBalance($user, $balance) {
        $infoArr = array(
            'WalletBalance' => $balance,
        );

        return $this->userRepo->updateUser($user, $infoArr);
    }

    /**
     * cyy, since 1.0
     *
     * 2017-05-18
     *
     * 删除用户
     */
    public function deleteUser($user) {
        $chatService = $this->container->get('chat.chatservice');
        $infoArr = array(
            'Uuid' => '#' . $user->getUuid(),
            'isValid' => 0,
        );
        $chatService->disableOpenfireAccount($user->getChatAccount());

        return $this->userRepo->updateUser($user, $infoArr);
    }
}
