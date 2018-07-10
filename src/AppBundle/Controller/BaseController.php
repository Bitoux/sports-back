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

	public function  getEventRepository(){
    	return $this->getDoctrine()->getRepository('AppBundle:Event');
	}

    public function getFriendRepository(){
        return $this->getDoctrine()->getRepository('AppBundle:Friend');
    }

    public function getInvitationRepository(){
        return $this->getDoctrine()->getRepository('AppBundle:Invitation');
    }

    public function getCompanyRepository(){
        return $this->getDoctrine()->getRepository('AppBundle:Company');
    }

    public function getPaymentRepository(){
        return $this->getDoctrine()->getRepository('AppBundle:Payment');
    }

    public function getProEventRepository(){
        return $this->getDoctrine()->getRepository('AppBundle:ProEvent');
    }



}