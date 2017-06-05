<?php

namespace BaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use BaseBundle\Container\ServerConst;

class WebBaseController extends BaseController {
    protected $title;
    protected $baseUrl;

    function __construct($filePath = __FILE__) {
        parent::__construct($filePath);
        $this->title = ''; 
        $this->baseUrl = ServerConst::WEB_ROOT;
    }   

    public function getTwigData() {
        $dataArr = array();
        $dataStr = json_encode($this->data);
        $dataArr["title"] = $this->title;
        $dataArr["baseUrl"] = $this->baseUrl;
        $dataArr["data"] = $dataStr;

        return $dataArr;
    }
}
