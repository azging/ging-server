<?php

namespace UtilBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Qiniu\Auth;

use BaseBundle\Controller\ApiBaseController;

use UtilBundle\Container\StringUtilService;
use UtilBundle\Container\WrapperService;

use UtilBundle\Entity\Wrapper\TokenWrapper;

use BaseBundle\Container\BaseConst;
use UtilBundle\Container\QiniuConst;

class QiniuController extends ApiBaseController {
    public function __construct() {
        parent::__construct(__FILE__);
    }

    /**
     * @ApiDoc(
     *  resource = true,
     *  section = "Util",
     *  description = "获取七牛上传Token",
     *  tags = {
     *      "stable" = "#23fd09",
     *      "cyy" = "#607d8b"
     *  },
     *  parameters = {
     *      {
     *          "name" = "Type",
     *          "dataType" = "string",
     *          "required" = true,
     *          "format" = "default,avatar,question,answer,video,file",
     *          "description" = "上传七牛的资源类型"
     *      },
     *  },
     *  output = {
     *      "class" = "PhotoBundle\Entity\Wrapper\TokenWrapper",
     *  },
     *  views = {"version1", "default"},
     * )
     *
     * @Route("api/v1/util/qiniu/uptoken/", methods="POST")
     */
    public function upTokenAction() {
        $type = $this->getPost('Type');

        $wrapperService = $this->get('util.wrapperservice');
        try {
            if (empty($type)) {
                $type = "default";
            }
            $bucket = QiniuConst::QINIU_BUCKET;
            if ('video' == $type) {
                $bucket = QiniuConst::QINIU_VIDEO_BUCKET;
            }

            // 用于签名的公钥和私钥
            $accessKey = QiniuConst::QINIU_ACCESS_KEY;
            $secretKey = QiniuConst::QINIU_SECRET_KEY;

            // 初始化签权对象
            $auth = new Auth($accessKey, $secretKey);
            $upToken = $auth->uploadToken($bucket);

            $photoKey = $type . '_' . StringUtilService::getGuid();

            $this->status = BaseConst::STATUS_SUCCESS;
            $this->data = $wrapperService->getQiniuUpToken($upToken, $photoKey);
            $this->msg = "获取Token成功";
        } catch (\Exception $e) {
            $this->printExceptionToLog($e);
        }
        return $this->getJsonResponse();
    }
}
