<?php
session_start();
// session_destroy();
$noNavbar = '';

if (isset($_SESSION['username'])) {
    header("Location: dashboard.php");
}

include 'init.php';
?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['user'];
    $pass = $_POST['password'];
    $hashedPass = sha1($pass);

    $stmt = $conn->prepare('Select UserID from users WHERE username = ? AND password = ? AND GroupID = 1');
    $stmt->execute([$username, $hashedPass]);
    $count = $stmt->rowCount();

    if ($count > 0) {
        $_SESSION['username'] = $username;
        header("Location: dashboard.php");
    }
}
?>

<form action="<?php echo $_SERVER['PHP_SELF'] ?>" class="login" method="POST">
    <h4 class="text-center">Admin Login</h4>
    <input class="form-control" type="text" name="user" placeholder="Username" required>
    <input class="form-control" type="password" name="password" placeholder="Password" required>
    <input type="submit" class="btn btn-primary btn-block" name="submit" value="Login">
</form>

<?php include $tbl . 'footer.php' ?>