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
use Mpdf\Mpdf;

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

        // BUILD PDF WITH MPDF SET FILENAME
        $payment->setBill($this->buildPDF($payment, $company->getUser()->getUsername()));

        $this->getDoctrine()->getManager()->persist($payment);
        $this->getDoctrine()->getManager()->flush();

        return $company->getUser();

     }

     public function buildPDF(Payment $payment, $username){
        ob_start();
        $paymentPDF = [
            'date' => $payment->getDate(),
            'amount' => $payment->getAmount(),
            'order_id' => $payment->getOrderId(),
            'payer_id' => $payment->getPayerId(),
            'payment_id' => $payment->getPaymentId(),
            'payment_token' => $payment->getPaymentToken(),
            'return_url' => $payment->getReturnUrl(),
            'username' => $username
        ];
        include( $this->getParameter('bill_directory') . '/devis-pdf.php');
        $var = ob_get_contents();
        ob_end_clean();

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'P',
            'margin_top' => 80,
            'margin_bottom' => 25
        ]);

        $mpdf->SetHTMLHeader('
            <div class="header" style="background-color: #343a40; padding: 20px; font-family: helvetica; font-weight: 600;">
                <table width="100%">
                    <tr>
                        <td style="color: #ffb300; font-size: 22px;">
                            Spo(r)ts
                        </td>
                        <td style="text-align: right; color: white; font-size: 16px;">
                            Order: '. $payment->getOrderId() .'
                        </td>
                    </tr>
                </table>
            </div>
        ');

        $mpdf->SetHTMLFooter('
        <div>
            <table style="border-collapse: collapse; width: 100%; text-align: center;">
                <tr>
                    <td style="background-color: #ffb300; color: white;
                     padding: 20px 0; text-align: center; font-family: Helvetica;">
                        Phone number: <br>
                        +33 1 43 87 98 32 
                    </td>
                    <td style="background-color: #343a40; color: white;
                     padding: 20px 0; text-align: center; font-family: Helvetica;">
                        Email address: <br>
                        <a style="color: white; font-weight: bold;" href="mailto:sportsesgi@gmail.com">sportsesgi@gmail.com</a>
                    </td>
                    <td style="background-color: #ffb300; color: white;
                     padding: 20px 0; text-align: center; font-family: Helvetica;">
                        At: <br>
                        Paris, France
                    </td>
                </tr>  
            </table>
        </div>
        ');

        $mpdf->writeHTML($var);

        $file_path = $this->getParameter('bill_directory') . './bills/' . $payment->getOrderId() . '.pdf';
        $mpdf->OutPut($file_path, 'F');

        return $payment->getOrderId() . '.pdf';
     }
}