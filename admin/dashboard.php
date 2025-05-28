<?php
session_start();
ob_start(); // Output buffering - stores output except headers

// Check if user is logged in
if (isset($_SESSION['username'])) {
    $pageTitle = 'Dashboard';
    include 'init.php';
?>

    <!-- Dashboard Statistics Section -->
    <div class="container text-center home-stats">
        <div class="row">
            <!-- Members Stats -->
            <div class="col-md-3">
                <div class="stat st-members">
                    <div>Total Members</div>
                    <span>
                        <a href="members.php">
                            <?php echo GetNumberOfItems('UserID', 'users'); ?>
                        </a>
                    </span>
                </div>
            </div>

            <!-- Pending Members Stats -->
            <div class="col-md-3">
                <div class="stat st-pending">
                    <div>Pending Members</div>
                    <span>
                        <a href="members.php?do=Manage&page=pending">
                            <?php echo GetNumberOfItems('UserID', 'users', ' RegStatus = ?', [0]); ?>
                        </a>
                    </span>
                </div>
            </div>

            <!-- Items Stats -->
            <div class="col-md-3">
                <div class="stat st-items">
                    <div>Total Items</div>
                    <span>
                        <a href="items.php">
                            <?php echo GetNumberOfItems('ProductID', 'products'); ?>
                        </a>
                    </span>
                </div>
            </div>

            <!-- Comments Stats -->
            <div class="col-md-3">
                <div class="stat st-comments">
                    <div>Total Items</div>
                    <span>
                        <a href="items.php">
                            <?php echo GetNumberOfItems('CID', 'comments'); ?>
                        </a>
                    </span>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- Latest Activity Section -->
    <div class="container latest">
        <div class="row">
            <!-- Latest Users Panel -->
            <div class="col-sm-6">
                <div class="jumbotron jumbotron-fluid">
                    <div class="container">
                        <h2>Latest Registered Users</h2>
                        <ul class="list-unstyled latest-users">
                            <?php
                            $rows = GetLatestItems('users', '*', 5, 'desc', 'RegDate');
                            foreach ($rows as $row) {
                                echo '<li>';
                                echo $row['Username'];
                                echo '<a href="members.php?do=Edit&userid=' . $row['UserID'] . '">';
                                echo '<span class="btn btn-success float-right"><i class="fa fa-edit"></i> Edit</span></a>';

                                if ($row['RegStatus'] == 0) {
                                    echo "<a href='members.php?do=Activate&userid=" . $row['UserID'] . "' class='btn btn-primary float-right activate'>";
                                    echo '  <span><i class="fa fa-check"></i> Activate</span></a>';
                                }

                                echo '</li>';
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Latest Items Panel -->
            <div class="col-sm-6">
                <div class="jumbotron jumbotron-fluid">
                    <div class="container">
                        <h2>Latest Added Products</h2>
                        <ul class="list-unstyled latest-users">
                            <?php
                            $rows = GetLatestItems('products', '*', 5, 'desc', 'AddDate');
                            foreach ($rows as $row) {
                                echo '<li>';
                                echo $row['ProductName'];
                                echo '<a href="products.php?do=Edit&productID=' . $row['ProductID'] . '">';
                                echo '<span class="btn btn-success float-right"><i class="fa fa-edit"></i> Edit</span></a>';

                                if ($row['Approve'] == 0) {
                                    echo "<a href='products.php?do=Approve&productID=" . $row['ProductID'] . "' class='btn btn-primary float-right activate'>";
                                    echo '<span><i class="fa fa-check"></i> Approve</span></a>';
                                }

                                echo '</li>';
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Latest Comments Panel -->
            <div class="col-sm-6">
                <div class="jumbotron jumbotron-fluid">
                    <div class="container">
                        <h2>Latest Added Comments</h2>
                        <ul class="list-unstyled latest-users">
                            <?php
                            $rows = GetLatestComments();
                            foreach ($rows as $row) {
                                echo '<li>';
                                echo $row['Username'] . ': ' . $row['Comment'];
                                echo '<a href="comments.php?do=Edit&commentid=' . $row['CID'] . '">';
                                echo '<span class="btn btn-success float-right"><i class="fa fa-edit"></i> Edit</span></a>';

                                if ($row['Status'] == 0) {
                                    echo "<a href='comments.php?do=Activate&commentid=" . $row['CID'] . "' class='btn btn-primary float-right activate'>";
                                    echo '<span><i class="fa fa-check"></i> Approve</span></a>';
                                }

                                echo '</li>';
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>

<?php
    include $tbl . 'footer.php';
} else {
    // Redirect if not logged in
    header('location:index.php');
}
ob_end_flush();
?>