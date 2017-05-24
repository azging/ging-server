<?php

namespace WalletBundle\Container;

class OrderConst {
    const ORDER_PAYMENT_TYPE_UNPAY = 0;
    const ORDER_PAYMENT_TYPE_BALANCE = 1;
    const ORDER_PAYMENT_TYPE_WECHAT = 2;

    const ORDER_TRADE_TYPE_UNKNOWN = 0;
    const ORDER_TRADE_TYPE_QUESTION = 1;
    const ORDER_TRADE_TYPE_ADOPT_ANSWER = 2;
    const ORDER_TRADE_TYPE_WITHDRAW = 3;
}
