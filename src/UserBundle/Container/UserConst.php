<?php

namespace UserBundle\Container;

class UserConst {
    const USER_NICK_MAX_LENGTH = 20; 

    const USER_AVATAR_THUMB_SUFFIX = '?imageView2/2/w/200';
    // 聊天账户密码长度
    const CHAT_PASSWORD_LENGTH = 8;

    const USER_LOGIN_TYPE_DEFAULT = 0;
    const USER_LOGIN_TYPE_TELEPHONE = 1;
    const USER_LOGIN_TYPE_WECHAT = 2;

    const USER_TYPE_COMMON = 1;
    const USER_TYPE_SUPER_ADMIN = 2;
    const USER_TYPE_SECOND_ADMIN = 3;

    const USER_GENDER_DEFAULT = 0;
    const USER_GENDER_MALE = 1;
    const USER_GENDER_FEMALE = 2;

    const USER_MAX_WEIGHT = 10000000;
    const USER_DEFAULT_WEIGHT = 600000;
}
