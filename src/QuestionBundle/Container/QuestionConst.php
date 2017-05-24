<?php

namespace QuestionBundle\Container;

class QuestionConst {
    const PHOTO_THUMB_SUFFIX = '?imageView2/2/w/375';

    const QUESTION_DEFAULT_WEIGHT = 600000;
    const QUESTION_MAX_WEIGHT = 10000000;

    const QUESTION_EXPIRE_HOURS = 48;

    const QUESTION_STATUS_DEFAULT = 0;
    const QUESTION_STATUS_UNPAID = 1;
    const QUESTION_STATUS_ANSWERING = 2;
    const QUESTION_STATUS_EXPIRED_NO_ANSWER = 3;
    const QUESTION_STATUS_EXPIRED_NO_ANSWER_REFUNDED = 4;
    const QUESTION_STATUS_EXPIRED_UNADOPTED = 5;
    const QUESTION_STATUS_EXPIRED_UNADOPTED_PAID_FIRST = 6;
    const QUESTION_STATUS_ADOPTED = 7;
    const QUESTION_STATUS_ADOPTED_PAID_BEST = 8;

    const QUESTION_STATUS_TYPE_ALL = 0;
    const QUESTION_STATUS_TYPE_UNSOLVED = 1;
    const QUESTION_STATUS_TYPE_SOLVED = 2;

    const QUESTION_PAY_STATUS_UNPAID = 0;
    const QUESTION_PAY_STATUS_PAID = 1;
    const QUESTION_PAY_STATUS_PAID_TO_ANSWER = 2;
    const QUESTION_PAY_STATUS_REFUND = 3;
}
