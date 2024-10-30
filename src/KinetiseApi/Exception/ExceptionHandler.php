<?php

namespace KinetiseApi\Exception;

use Exception;
use KinetiseApi\HTTP\KinetiseErrorResponse;

class ExceptionHandler
{
    public static function handle(Exception $exception)
    {
        $response = new KinetiseErrorResponse($exception->getMessage());

        $response->send();


        return;
    }
}
