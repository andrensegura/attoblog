<?php

define('D_ROOT', $_SERVER['DOCUMENT_ROOT']);
require_once(D_ROOT . "/include/functions.php");
require_once(D_ROOT . "/include/config.php");

$request = explode('/', $_SERVER['REQUEST_URI']);

switch($request[1]) {
    default:
        if(check_post($request[1])){
            display_post($request[1]);
        }else{
            #Remove extra shit from the URL that doesn't mean anything.
            $url = isset($_SERVER[HTTPS]) ? 'https' : 'http';
            $url .= '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            if( $url != HOME) {
                header("Location: " . HOME);
            }
            display_home();
        }
}
