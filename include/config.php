<?php

$jcfg = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/include/config.json");
$options = json_decode($jcfg, true);

define('HOME', $options["home"]);
define('THEME', $options["theme"]);

define('SITE_TITLE', $options["site_title"]);
define('PAGE_TITLE', $options["page_title"]);
define('SUB_PAGE_TITLE', $options["sub_page_title"]);


// these are for convenience
// this one can be used when linking a resource
define('THEME_LOC', HOME . '/src/themes/' . THEME);
