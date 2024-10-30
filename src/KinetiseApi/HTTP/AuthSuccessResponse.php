<?php

namespace KinetiseApi\HTTP;

use Symfony\Component\HttpFoundation\JsonResponse;

class AuthSuccessResponse extends JsonResponse
{
    public function __construct($sessionId)
    {
        $response = array(
            'sessionId' => $sessionId,
        );

        parent::__construct($response);
    }
}
