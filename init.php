<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
$tbl = 'includes/tpls/';
$css = 'layout/css/';
$js = 'layout/js/';
$func = 'includes/functions/';
$lang = 'includes/languages/';

$userSession = '';
if (isset($_SESSION['user'])) {
    $userSession = $_SESSION['user'];
}

include 'connect.php';
include $lang . 'en.php';
include $func . 'functions.php';
include $tbl . 'header.php';
include $tbl . 'PageTitle.php';

if (!isset($noNavbar)) {
    include $tbl . 'navbar.php';
}
