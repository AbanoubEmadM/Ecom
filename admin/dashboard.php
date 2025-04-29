<?php
session_start();


if (isset($_SESSION['username'])) {
    $pageTitle = 'Dashboard';
    include 'init.php';
    echo '<pre>';
    print_r($_SESSION);
    include $tbl . 'footer.php';
} else {
    header('location:index.php');
}
