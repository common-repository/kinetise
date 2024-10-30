<?php

namespace KinetiseApi;

class Kernel
{
    /**
     * @var Bootstrap
     */
    private $bootsrap;

    public function __construct()
    {
        $this->bootsrap = new Bootstrap($this);
    }

    public function boot()
    {
        $this->bootsrap->init();
    }

    /**
     * @return Bootstrap
     */
    public function getBootstrap()
    {
        return $this->bootsrap;
    }
}
