<?php
session_start();

if (isset($_POST)) {
    //print_r($_POST);
    foreach ($_POST as $key => $value) {
        $_POST[$key] = str_replace("'", "\'", $value); 
        //$_POST[$key] = str_replace(array('SELECT', 'INSERT'), "", $value); // Tratamento de segurança (SQL-Injection);
    }
}
error_reporting(E_ERROR | E_PARSE);
header('Content-Type: text/html; charset=utf-8');

require_once 'helper/Bootstrap.php';
define('RAIZ_PATH', '');
define('APP_ROOT', 'http'.(isset($_SERVER['HTTPS']) ? (($_SERVER['HTTPS']=="on") ? "s" : "") : "").'://' . $_SERVER['HTTP_HOST'] . '/'. RAIZ_PATH . '');

use core\System;

$System = new System();
$System->run();
?>