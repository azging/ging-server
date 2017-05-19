<?php

namespace UserBundle\Container;

class PhoneConst {
    const AUTH_CODE_EXPIRE_MINUTES = "+5";
    const AUTH_CODE_EXPIRE_SECONDS = "300";

    const AUTH_CODE_LENGTH = 6;
    const AUTH_CODE_MAX_TIME = 5;

    const YIMEI_GATEWAY_URL = 'http://sdk999ws.eucp.b2m.cn:8080/sdk/SDKService';
    const YIMEI_MARKET_URL = 'http://sdk4rptws.eucp.b2m.cn:8080/sdk/SDKService';
    const YIMEI_SERIAL_NUMBER_AUTH_CODE = '9SDK-EMY-0999-REQMR';
    const YIMEI_SERIAL_NUMBER_MARKET = '6SDK-EMY-6688-KJXSN';
    const YIMEI_PASSWORD = 'duckr1031';
    const YIMEI_SESSION_KEY = '103115';
    const YIMEI_MARKET_SESSION_KEY = '892251';
    const YIMEI_CONNECT_TIME_OUT = 2;
    const YIMEI_READ_TIME_OUT = 10; 
}
