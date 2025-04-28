<?php

$do = isset($_GET['do']) ? $_GET['do'] : 'manage';

switch ($do) {
    case 'manage':
        echo 'Welcome in Manage Page';
        echo '<a href=?do=add> Add </a>';
        break;
    case 'add':
        echo 'Welcome in Add Page';
        break;
    case 'edit':
        echo 'Welcome in Edit Page';
        break;
    case 'delete':
        echo 'Welcome in Delete Page';
        break;
    default:
        echo 'Error 404 Page Not Found';
        break;
}
