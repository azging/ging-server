<?php

namespace WalletBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use BaseBundle\Controller\ApiBaseController;

use UtilBundle\Container\UtilService;

use BaseBundle\Container\BaseConst;

class WalletController extends ApiBaseController
{
    /** 
     * @ApiDoc(
     *  resource = true,
     *  section = "Wallet",
     *  description = "获取用户钱包余额",
     *  tags = {
     *      "stable" = "#23fd09",
     *      "cyy" = "#607f8d"
     *  },
     *  requirements = {
     *  },
     *  output = {
     *      "class" = "WalletBundle\Entity\Wrapper\WalletWrapper",
     *  },
     *  views = {"version1", "default"},
     * )
     *
     * @Route("/api/v1/wallet/balance/", methods="GET")
     */
    public function userInfoAction() {
        $uuid = $this->getCookieData('UUID');

        $userService = $this->get('user.userservice');
        $wrapperService = $this->get('wallet.wrapperservice');

        try {
            $this->checkIfLogin(true);

            $user = $userService->getUserByUuid($uuid);
            if (!UtilService::isValidObj($user)) {
                $this->throwNewException(BaseConst::STATUS_ERROR_COMMON, "不存在该用户");
            }

            $this->status = BaseConst::STATUS_SUCCESS;
            $this->data = $wrapperService->getWalletWrapper($user);
            $this->msg = "获取钱包余额成功";
        } catch (\Exception $e) {
            $this->printExceptionToLog($e);
        }

        return $this->getJsonResponse();
    }
}
