<?php

$tbl = 'includes/tpls/';
$css = 'layout/css/';
$js = 'layout/js/';
$func = 'includes/functions/';
$lang = 'includes/languages/';

include 'connect.php';
include $lang . 'en.php';
include $func . 'functions.php';
include $tbl . 'header.php';
include $tbl . 'PageTitle.php';

if (!isset($noNavbar)) {
    include $tbl . 'navbar.php';
}
