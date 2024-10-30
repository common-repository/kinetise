<?php

namespace KinetiseApi\View;

use Symfony\Component\HttpFoundation\Response;

class Presenter
{
    public static function send(Response $response)
    {
        $response->send();
        exit;
    }
}
