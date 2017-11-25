<?php

namespace AppBundle\Utils;

use JMS\Serializer\SerializationContext;
use FOS\RestBundle\View\View;


class Error
{

    /**
     * @return View
     */
    public function elementNotFound($statusCode = 404) {
        $view = View::create();
        $view->setStatusCode($statusCode);
        $view->setFormat('json');
        $view->setData(array(
            "status" => $statusCode,
            'result' => 'element not found.'
        ));

        return $view;
    }

}