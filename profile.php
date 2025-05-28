<?php
session_start();
include 'init.php';
if ($_SESSION['user']) {
    $user = GetItem('*', 'users', 'Username = ?', [$userSession]);
    $ads = GetItem('*', 'categories', 'CategoryID = ?', [$userSession]);
    $comments = GetItem('*', 'comments', 'UserID = ?', [$user['UserID']]);
?>
    <div class="info">
        <div class="container">
            <div class="panel">
                <h1>Profile</h1>
                <p>Name: <?= $user['Username'] ?></p>
                <p>Email: <?= $user['Email'] ?></p>
                <p>Full Name: <?= $user['FullName'] ?></p>
                <p>Regestration Date: <?= $user['RegDate'] ?></p>
                <p>Regestration Status: <?= $user['RegStatus'] == 1 ? 'Active' : 'Inactive' ?></p>
            </div>
        </div>
    </div>
    <div class="ads">
        <div class="container">
            <div class="panel">
                <h1>Ads</h1>
                <div class="panel">
                    <?= $ads ? $ads['Comment'] : 'No Comments'; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="comments">
        <div class="container">
            <h1>Comments</h1>
            <div class="panel">
                <?= $comments ? $comments['Comment'] : 'No Comments'; ?>
            </div>
        </div>
    </div>
<?php
    include $tbl . 'footer.php';
} else {
    header('Location: login.php');
}
?>