<?php

namespace BaseBundle\Container;

class BaseConst {
    // VALID值
    const IS_VALID_TRUE = 1;
    const IS_VALID_FALSE = 0;

    // 错误信息
    const STATUS_ERROR_COMMON = -1;
    const STATUS_ERROR_SERVER = -1;
    const MSG_ERROR_SERVER = "服务器故障";
    const STATUS_ERROR_NO_CID_LOGIN = -2;

    // 成功信息
    const STATUS_SUCCESS = 0;

    // 客户端类型
    const CLIENT_OS_TYPE_IOS = 1;
    const CLIENT_OS_TYPE_ANDROID = 2;
    const CLIENT_OS_TYPE_WEB = 3;

    // 列表默认数量
    const LIST_DEFAULT_NUM = 10;
}
