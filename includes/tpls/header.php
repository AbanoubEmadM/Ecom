<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php getTitle() ?></title>
    <link rel="stylesheet" href="<?php echo $css ?>bootstrap.min.css">

    <link rel="stylesheet" href="<?php echo $css ?>all.min.css">
    <link rel="stylesheet" href="<?php echo $css ?>front.css">

</head>

<body>
    <div class="upper-bar">
        <div class="container">
            <?php
            if (isset($_SESSION['user'])) {
                $status = CheckUserStatus($_SESSION['user']);
                // if $status == 1 then this user not approved yet
                if ($status) {
                    echo '<div class="user-info"> 
                        <p> Sorry, ' . $_SESSION['user'] . ' is not approved yet</p>
                        <a href="logout.php">Logout <i class="fas fa-sign-out-alt"></i></a>
                      </div>';
                } else {
                    echo '<div class="user-info"> 
                        <a href="profile.php">' . $_SESSION['user'] . '</a>
                        <a href="logout.php">Logout <i class="fas fa-sign-out-alt"></i></a>
                      </div>';
                }
            } else {
                echo '<div class="user-info"> 
                        <a href="login.php">Login <i class="fas fa-sign-in-alt"></i></a>
                      </div>';
            }
            ?>
        </div>
    </div>