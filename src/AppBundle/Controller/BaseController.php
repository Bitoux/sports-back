<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use AppBundle\Utils\Error;

class BaseController extends FOSRestController
{

    public $error;

    public function __construct()
    {
        $this->error = new Error();
    }

    public function getUserRepository()
    {
        return $this->getDoctrine()->getRepository('AppBundle:User');
    }


}