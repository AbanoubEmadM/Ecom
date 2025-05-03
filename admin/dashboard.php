<?php
session_start();
ob_start(); // output buffering تخزين للمخرجات معادا الهيدر
if (isset($_SESSION['username'])) {
    $pageTitle = 'Dashboard';
    include 'init.php';
?>
    <div class="container text-center home-stats">
        <div class="row">
            <div class="col-md-3">
                <div class="stat st-members">
                    Total Members
                    <span> <a href="members.php"> <?php echo GetNumberOfItems('UserID', 'users') ?> </a></span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat st-pending">
                    Pending Members
                    <span><a href="members.php?do=Manage&page=pending"><?php echo GetNumberOfItems('UserID', 'users', 'WHERE RegStatus = 0') ?></a></span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat st-items">
                    Total Items
                    <span>1500</span>

                </div>
            </div>
            <div class="col-md-3">
                <div class="stat st-comments">
                    Total Comments
                    <span>25</span>

                </div>
            </div>
        </div>
    </div>
    <div class="container latest">
        <div class="row">
            <div class="col-sm-6">
                <div class="jumbotron jumbotron-fluid">
                    <div class="container">
                        <h2 class="">Latest Registered Users</h1>
                            <ul class="list-unstyled latest-users">
                                <?php
                                $rows = GetLatestItems('users', '*', 5, 'desc', 'RegDate');
                                foreach ($rows as $row) {
                                    echo '<li>';
                                    echo $row['username'];
                                    echo '<a href="members.php?do=Edit&userid=' . $row['UserID'] . '">';
                                    echo '<span class="btn btn-success float-right"><i class="fa fa-edit"></i> Edit</span></a>';
                                    if ($row['RegStatus'] == 0) {
                                        echo "<a href='members.php?do=Activate&userid=" . $row['UserID'] . "' class='btn btn-primary float-right activate'>";
                                        echo '  <span><i class="fa fa-check"></i> Activate</span></a>';
                                    }
                                };

                                echo '</li>';
                                ?>
                            </ul>

                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="jumbotron jumbotron-fluid">
                    <div class="container">
                        <h2 class="">Latest Items</h1>
                            <p class="lead">Test</p>
                    </div>
                </div>
            </div>

        </div>
    <?php
    include $tbl . 'footer.php';
} else {
    header('location:index.php');
}
ob_end_flush();
    ?>