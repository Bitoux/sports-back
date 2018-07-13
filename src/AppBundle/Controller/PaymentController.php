<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Payment;
use AppBundle\Entity\Company;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationList;
use AppBundle\Exception\ResourceValidationException;

class PaymentController extends BaseController
{
    /**
     * @Rest\Post("/payments/add", name="add_payment")
     * @Rest\View(StatusCode = 200)
     */
     public function addPayment(Request $request){
        $date = $request->get('date');
        $amount = $request->get('amount');
        $companyID = $request->get('company');
        $order_id = $request->get('order_id');
        $payer_id = $request->get('payer_id');
        $payment_id = $request->get('payment_id');
        $payment_token = $request->get('payment_token');
        $return_url = $request->get('return_url');

        $payment = new Payment();

        $payment->setDate($date);
        $payment->setAmount($amount);
        $payment->setOrderId($order_id);
        $payment->setPayerId($payer_id);
        $payment->setPaymentId($payment_id);
        $payment->setPaymentToken($payment_token);
        $payment->setReturnUrl($return_url);

        $company = $this->getCompanyRepository()->find($companyID);

        $payment->setCompany($company);

        $this->getDoctrine()->getManager()->persist($payment);
        $this->getDoctrine()->getManager()->flush();

        return $company->getUser();

     }
}