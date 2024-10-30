<?php

namespace KinetiseApi\HTTP;

use Symfony\Component\HttpFoundation\Response;

class KinetiseResponse extends Response
{
    public function __construct($message = null, $status = self::HTTP_OK, $headers = array())
    {
        if ($message === null) {
            $res = array("results"=>array());
        } else {
            $res = array(
                "message" => array( "description" => $message)
            );
        }

        parent::__construct(json_encode($res), $status, array(
            'Content-type' => 'application/json',
        ));
    }
}
