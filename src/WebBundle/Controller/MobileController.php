<?php

namespace WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use BaseBundle\Controller\WebBaseController;
 

class MobileController extends WebBaseController {

    function __construct($filePath = __FILE__) {
        parent::__construct($filePath);
    }

    /**
     * @Route("/mobile/user/policy/")
     */
    public function policyAction()
    {
        return $this->render('WebBundle:Mobile:user-policy.html.twig', $this->getTwigData());
    }
 

}

