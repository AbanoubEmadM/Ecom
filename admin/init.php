<?php

$tbl = 'includes/tpls/';
$css = 'layout/css/';
$js = 'layout/js/';
$func = 'includes/functions/';
$lang = 'includes/languages/';

include $lang . 'en.php';
include $func . 'functions.php';
include $tbl . 'header.php';
include 'connect.php';

if (!isset($noNavbar)) {
    include $tbl . 'navbar.php';
}
