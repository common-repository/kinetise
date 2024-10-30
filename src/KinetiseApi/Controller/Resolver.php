<?php

namespace KinetiseApi\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Resolver
{
    const CONTROLLER_PREFIX = '\\Kinetise\\Controller\\';
    const CONTROLLER_POSTFIX = 'Controller';

    /**
     * @return Response
     */
    public static function execute(Request $request, $controller, $action)
    {
        $controller = ucfirst(strtolower($controller));
        $method = strtolower($action) . 'Action';

        $class = self::CONTROLLER_PREFIX . $controller . self::CONTROLLER_POSTFIX;

        $reflectionMethod = new \ReflectionMethod($class, $method);

        if (!$reflectionMethod->isPublic()) {
            throw new \Exception(sprintf('%s::%s method is not callable!', $class, $method));
        }

        $methodArgs = $reflectionMethod->getParameters();
        $values = array();

        /* @var \ReflectionParameter $mArg */
        foreach ($methodArgs as $mArg) {
            if ($request->query->has($mArg->getName())) {
                $values[$mArg->getName()] = $request->query->get($mArg->getName());
            }
        }

        $reflectionClass = $reflectionMethod->getDeclaringClass();

        $response = $reflectionMethod->invokeArgs(
            $reflectionClass->newInstance($request),
            $values
        );

        if (!$response instanceof Response) {
            throw new \Exception(sprintf('%s::%s must return "Response" instance', $class, $method));
        }

        return $response;
    }
}
