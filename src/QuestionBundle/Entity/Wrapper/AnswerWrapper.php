<?php

namespace QuestionBundle\Entity\Wrapper;

use UtilBundle\Container\UtilService;

use QuestionBundle\Entity\QuestionAnswer;
use UserBundle\Entity\Wrapper\UserWrapper;

class AnswerWrapper implements \JsonSerializable {

    /**
     * @var User
     */
    private $userWrapper;

    /**
     * @var QuestionAnswer
     */
    private $answer;

    public function __construct() {
        $this->userWrapper = new UserWrapper();
        $this->answer = new QuestionAnswer();
    }

    public function setUserWrapper($userWrapper) {
        $this->userWrapper = $userWrapper;

        return $this;
    }

    public function setAnswer($answer) {
        $this->answer = $answer;

        return $this;
    }

    public function jsonSerialize() {
        $arr = array(
            'Answer' => $this->answer, 
            'CreateUserWrapper' => $this->userWrapper,
        );
        return UtilService::getNotNullValueArray($arr);
    }
}
