<?php

namespace KinetiseApi\HTTP;

use Symfony\Component\HttpFoundation\Response;

class KinetiseItemResponse extends Response
{
    public function __construct($data)
    {
        parent::__construct(json_encode(array("results" => $data)), self::HTTP_OK, array(
            'Content-type' => 'application/json',
        ));
    }
}
