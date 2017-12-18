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

    public function getFilterRepository()
    {
        return $this->getDoctrine()->getRepository('AppBundle:Filter');
    }

    public function getUserFilterRepository()
    {
        return $this->getDoctrine()->getRepository('AppBundle:User_Filter');
    }

    public function getMapRepository()
    {
        return $this->getDoctrine()->getRepository('AppBundle:Map');
    }

    public function getSpotRepository()
		{
			return $this->getDoctrine()->getRepository('AppBundle:Spot');
		}



}