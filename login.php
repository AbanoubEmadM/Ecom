<?php

include 'init.php';
?>
<?php
session_start();

if (isset($_SESSION['user'])) {
    header("Location: index.php");
}
?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $pass = $_POST['password'];
    $hashedPass = sha1($pass);

    $stmt = $conn->prepare('Select 
                                UserID,Username, Password 
                            from 
                                users 
                            WHERE 
                                username = ? 
                            AND 
                                password = ? 
                            LIMIT 1');
    $stmt->execute([$username, $hashedPass]);
    $row = $stmt->fetch();
    $count = $stmt->rowCount();

    if ($count > 0) {
        $_SESSION['user'] = $username;
        header("Location: index.php");
        exit();
    } else {
        echo "<script>alert('Invalid Username or Password')</script>";
    }
}
?>

<?php
include './' . $tbl . 'footer.php';
?>
<div class="container login-page">
    <h1 class="text-center"><span class="login">Login</span> | <span class="sign-up">Sign Up</span></h1>
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" class="login" method="POST">
        <input type="text" class="form-control" name="username" placeholder="Enter Your Username">
        <input type="password" class="form-control" name="password" placeholder="Enter Your Password">
        <input type="submit" class="btn btn-primary btn-block" value="Login">
    </form>
    <form class="sign-up">
        <input type="text" class="form-control" name="username" placeholder="Enter Your Username">
        <input type="password" class="form-control" name="password" placeholder="Enter Your Password">
        <input type="password" class="form-control" name="password" placeholder="Enter Your Password Again">
        <input type="Email" class="form-control" name="password" placeholder="Enter Your Email">

        <input type="submit" class="btn btn-success btn-block" value="Sign Up">
    </form>

</div>
<?php
include $tbl . 'footer.php';
?>