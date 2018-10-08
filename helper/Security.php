<?php
namespace helper;

class Security {
    public function __construct() {
        if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
            header('location:' . APP_ROOT . 'portal/sessao');
        }
    }
}