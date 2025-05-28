<?php

ob_start();
session_start();
$pageTitle = 'Categories';
if (isset($_SESSION['username'])) {
    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
    if ($do == 'Manage') {

        GetPageTitle("Manage Categories");
    } elseif ($do == 'Add') {
    } elseif ($do == 'Edit') {
    } elseif ($do == 'Insert') {
    } elseif ($do == 'Update') {
    } elseif ($do == 'Delete') {
    } elseif ($do == 'Add') {
    }
} else {
    header("location: index.php");
    exit();
}
