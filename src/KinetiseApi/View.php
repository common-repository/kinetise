<?php

namespace KinetiseApi;

use KinetiseApi\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class View
{
    private $templateDir;
    private $template;
    private $view;

    public function __construct(AbstractController $controller)
    {
        $this->view = new \stdClass();
        $this->templateDir = KINETISE_ROOT . DS . 'src' . DS . 'Kinetise' . DS . 'Resources' . DS . 'views';

        $reflection = new \ReflectionClass($controller);

        $this->templateDir .= DS . trim($reflection->getName(), $reflection->getNamespaceName());
    }

    public function setView($template)
    {
        $this->template = $template;
    }

    public function setViewParam($key, $value)
    {
        $this->view->$key = $value;

        return $this;
    }

    public function renderView()
    {
        return $this->process();
    }

    public function render()
    {
        return new Response($this->process());
    }

    private function process()
    {
        $template = $this->templateDir . DS . $this->template;

        if (!file_exists($template)) {
            throw new \Exception('Template view not exists');
        }

//        extract($this);
        ob_start();
        include $template;
        $rendered = ob_get_clean();

        return $rendered;
    }
}
