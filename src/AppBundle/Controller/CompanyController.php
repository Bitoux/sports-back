<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Company;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationList;
use AppBundle\Exception\ResourceValidationException;

class CompanyController extends BaseController
{
    /**
	 * @Rest\Get("/company/{id}/proevents", name="company_proevents")
	 * @Rest\View(StatusCode = 200)
	 *
	 */
	public function getProEvents($id)
	{

        $company = $this->getCompanyRepository()->find($id);

		return $company->getProEvents();

    }

    /**
	 * @Rest\Get("/company/{id}/payments", name="company_payments")
	 * @Rest\View(StatusCode = 200)
	 *
	 */
	public function getPayments($id)
	{

        $company = $this->getCompanyRepository()->find($id);

		return $company->getPayments();

    }
}