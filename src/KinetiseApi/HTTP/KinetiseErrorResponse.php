<?php

namespace KinetiseApi\HTTP;

use Symfony\Component\HttpFoundation\JsonResponse;

class KinetiseErrorResponse extends JsonResponse
{
    public function __construct($message)
    {
        parent::__construct(
                array(
                    "message" => array("description" => $message)
                ),
                self::HTTP_BAD_REQUEST
            );
    }
}
