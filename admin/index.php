<?php
define('D_ROOT', $_SERVER['DOCUMENT_ROOT']);
require_once(D_ROOT . "/admin/functions.php");
require_once(D_ROOT . "/include/config.php");

$request = explode('/', $_SERVER['REQUEST_URI']);

check_option($request[2]);
