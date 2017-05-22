<?php

namespace QuestionBundle\Entity\Wrapper;

use UtilBundle\Container\UtilService;

use QuestionBundle\Entity\QuestionAnswer;
use UserBundle\Entity\Wrapper\UserWrapper;

class QuestionAnswerWrapper implements \JsonSerializable {

    /**
     * @var User
     */
    private $userWrapper;

    /**
     * @var QuestionAnswer
     */
    private $questionAnswer;

    public function __construct() {
        $this->userWrapper = new UserWrapper();
        $this->questionAnswer = new QuestionAnswer();
    }

    public function setUserWrapper($userWrapper) {
        $this->userWrapper = $userWrapper;

        return $this;
    }

    public function setQuestionAnswer($questionAnswer) {
        $this->questionAnswer = $questionAnswer;

        return $this;
    }

    public function jsonSerialize() {
        $arr = array(
            'QuestionAnswer' => $this->questionAnswer, 
            'CreateUserWrapper' => $this->userWrapper,
        );
        return UtilService::getNotNullValueArray($arr);
    }
}
