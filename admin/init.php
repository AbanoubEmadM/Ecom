<?php

$tbl = 'includes/tpls/';
$css = 'layout/css/';
$js = 'layout/js/';
$func = 'includes/functions/';
$lang = 'includes/languages/';

include $lang . 'en.php';
include $tbl . 'header.php';
include 'connect.php';
include $tbl . 'footer.php';

if (!isset($noNavbar)) {
    include $tbl . 'navbar.php';
}
