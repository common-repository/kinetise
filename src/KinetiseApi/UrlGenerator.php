<?php

namespace KinetiseApi;

class UrlGenerator
{
    public static function generate($controller, $action = null, array $params = array())
    {
        $action = $action || (!is_null($action) && $action != 'index') ? ':' . $action : '';

        $url = '/?' . UrlMatcher::API_KEY . '=' . $controller . $action;

        if (count($params) > 0) {
            foreach ($params as $param => $value) {
                $url .= '&' . $param . '=' .$value;
            }
        }

        return \site_url() . $url;
    }
}
