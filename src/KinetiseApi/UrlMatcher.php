<?php

namespace KinetiseApi;

use KinetiseApi\Controller\Resolver;
use KinetiseApi\View\Presenter;
use Symfony\Component\HttpFoundation\Request;

class UrlMatcher
{
    const API_KEY = 'kinetiseapi';

    /**
     * @var Request
     */
    private $request;

    private $controller;
    private $action;

    public function __construct()
    {
        $this->request = Request::createFromGlobals();
        $this->controller = 'index';
        $this->action = 'index';

        if ($this->request->isMethod('POST') &&
            0 === strpos($this->request->headers->get('Content-Type'), 'application/json')) {
            $data = json_decode($this->request->getContent(), true);

            if ($data && isset($data['form'])) {
                $this->request->query->set('id', $data['form']['id']);
                $form = is_array($data['form']) ? $data['form'] : array();
                $params = is_array($data['params']) ? $data['params'] : array();
                $this->request->request->replace(array_merge($form, $params));
            }
        }
    }

    public function executeRoute()
    {
        if ($this->request->query->has(self::API_KEY)) {

            $path = trim($this->request->query->get(self::API_KEY));

            if (preg_match('/^([a-zA-z]+):?([a-zA-z]+)?$/', $path, $matches)) {
                $this->controller = $matches[1] ? $matches[1] : 'index';
                $this->action = $matches[2] ? $matches[2] : 'index';
            }

            Presenter::send(
                Resolver::execute($this->request, $this->controller, $this->action)
            );
        }
    }

    public function getRequest()
    {
        return $this->request;
    }
}
