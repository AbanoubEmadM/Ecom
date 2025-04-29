<?php
//  ? Manage Members 
//  ? Add Member
//  ? Edit Member
//  ? Delete Member

session_start();

if (isset($_SESSION['username'])) {
    include 'init.php';
    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
    if ($do == 'Manage') {
        echo "Manage User: " . $_GET['userid'];
    } elseif ($do = 'Edit') {
        echo '<pre>';
        print_r($_GET);
        echo "Edit User: " . $_GET['userid'];
    }
    include $tbl . 'footer.php';
} else {
    header('location:index.php');
}
