<?php

namespace QuestionBundle\Entity\Wrapper;

use UtilBundle\Container\UtilService;

use QuestionBundle\Entity\Question;
use UserBundle\Entity\Wrapper\UserWrapper;

class QuestionWrapper implements \JsonSerializable {

    /**
     * @var User
     */
    private $userWrapper;

    /**
     * @var Question
     */
    private $question;

    public function __construct() {
        $this->userWrapper = new UserWrapper();
        $this->question = new Question();
    }

    public function setUserWrapper($userWrapper) {
        $this->userWrapper = $userWrapper;

        return $this;
    }

    public function setQuestion($question) {
        $this->question = $question;

        return $this;
    }

    public function jsonSerialize() {
        $arr = array(
            'Question' => $this->question, 
            'CreateUserWrapper' => $this->userWrapper,
        );
        return UtilService::getNotNullValueArray($arr);
    }
}
