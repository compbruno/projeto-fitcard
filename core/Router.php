<?php

namespace core;

class Router {
    protected $routers = array (
        'sessao' => 'sessao',
        'portal' => 'portal'
    );
    protected $routerOnDefault = 'portal';
    protected $onDefault = true;
}