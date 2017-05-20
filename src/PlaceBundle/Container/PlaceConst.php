<?php

namespace PlaceBundle\Container;

class PlaceConst {
    const BAIDU_API_URL = 'http://api.map.baidu.com/geocoder/v2/?';
    const BAIDU_API_AK = 'C50a5fe294d31a7da81991a42083e1a7';
    const BAIDU_API_CALLBACK = 'renderReverse';
    const BAIDU_API_OUTPUT = 'json';
    const BAIDU_API_POIS = '0';

    const DEFAULT_CITY_ID = 110000;
    const CHINA_CITY_ID = 1;

    const MUNICIPALITY_BEIJING_ID = 110000;
    const MUNICIPALITY_TIANJING_ID = 120000;
    const MUNICIPALITY_SHANGHAI_ID = 310000;
    const MUNICIPALITY_CHONGQING_ID = 500000;
    const MUNICIPALITY_TAIWAN_ID = 710000;
    const MUNICIPALITY_XIANGGANG_ID = 810000;
    const MUNICIPALITY_AOMEN_ID = 820000;

    const BUSINESS_AREA_THUMB_SUFFIX = '?imageView2/2/w/200';
}
