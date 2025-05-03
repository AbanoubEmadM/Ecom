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
        if (isset($_GET['page']) && $_GET['page'] == 'pending') $rows = GetUsers(' AND RegStatus = 0');
        else $rows = GetUsers();
        GetPageTitle("Manage Members");
?>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User Name</th>
                        <th>Email</th>
                        <th>Full name</th>
                        <th>Reg Date</th>
                        <th>Control</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($rows as $row) {
                        echo "<tr>";
                        echo "<td>" . $row['UserID'] . "</td>";
                        echo "<td>" . $row['username'] . "</td>";
                        echo "<td>" . $row['Email'] . "</td>";
                        echo "<td>" . $row['FullName'] . "</td>";
                        echo "<td>" . $row['RegDate'] . " </td>";
                        echo "<td>";
                        echo "<a href='?do=Edit&userid=" . $row['UserID'] . "' class='btn btn-success'>Edit</a>";
                        echo "<a href='?do=Delete&userid=" . $row['UserID'] . "' class='btn btn-danger confirm'>Delete</a>";
                        if ($row['RegStatus'] == 0) {
                            echo "<a href='?do=Activate&userid=" . $row['UserID'] . "' class='btn btn-primary confirm'>Activate</a>";
                        } else {
                            echo "<a href='?do=Deactivate&userid=" . $row['UserID'] . "' class='btn btn-primary confirm'>Deactivate</a>";
                        }
                        echo "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <a class='btn-primary btn' href="?do=Add">+ New Member</a>
        </div>
        <?php
    } elseif ($do == 'Edit') { // Edit Page

        $userID = isset($_GET['userid']) && is_numeric($_GET['userid']) ? $_GET['userid'] : 0;
        $row = GetUser($userID);
        $count = count($row);
        // If there is such ID Show the Form
        if ($count > 0) {
            GetPageTitle("Edit Member");
        ?>
            <form class="form-control" action="?do=Update" method="POST">

                <input type="hidden" name='userid' value="<?php echo $userID ?>">

                <div class="form-group">
                    <label for="exampleInputEmail1">User Name</label>
                    <input type="text" required name='username' value="<?php echo $row['username'] ?>" class="form-control" id="usernameInput" aria-describedby="emailHelp" placeholder="Enter User Name">
                </div>

                <div class="form-group">
                    <label for="exampleInputPassword1">Password</label>
                    <input type="hidden" name='oldpassword' value="<?php echo $row['Password'] ?>">
                    <input type="password" name='newpassword' class="form-control" id="passwordInput" placeholder="Password">
                </div>

                <div class="form-group">
                    <label for="exampleInputEmail1">Email address</label>
                    <input type="email" required name='email' value="<?php echo $row['Email'] ?>" class="form-control" id="emailInput" aria-describedby="emailHelp" placeholder="Enter email">
                    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                </div>

                <div class="form-group">
                    <label for="exampleInputPassword1">Full Name</label>
                    <input type="text" required name='fullname' value="<?php echo $row['FullName'] ?>" class="form-control" id="fullnameInput" placeholder="Full Name">
                </div>

                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
            </div>
        <?php
        } else {
            echo '<div class="container">';
            $msg = "<div class='alert alert-danger'>User Not Found</div>";
            echo '</div>';
            redirectHome($msg, 'back');
        ?>
        <?php }

        include $tbl . 'footer.php';
    } elseif ($do == 'Update') {
        GetPageTitle('Update Member');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get Variables From the Form
            $id = $_POST['userid'];
            $username = $_POST['username'];
            $email = $_POST['email'];
            $fullname = $_POST['fullname'];

            // Update Password
            $pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);

            // Validate The Form
            $formErrors = [];
            if (empty($username)) {
                $formErrors[] = 'Username is required';
            }
            if (empty($email)) {
                $formErrors[] = 'Email is required';
            }
            if (empty($fullname)) {
                $formErrors[] = 'Full Name is required';
            }
            // Show The Errors if there are any
            foreach ($formErrors as $error) {

                echo '<div class="alert alert-danger">' . $error . '</div>';
            }
            // If there is no errors then update the database
            if (empty($formErrors)) {
                // Update the Database with this Info
                if (UpdateUser($username, $pass, $email, $fullname, $id)) {
                    // Show the Success message 
                    $msg = '<div class="alert alert-success text-center" role="alert">
                    1 Record Updated </div>';

                    // redirectHome($msg, 'back');
                    redirectHome($msg, 'back');
                }
            }
            echo '</div>';
            include $tbl . 'footer.php';
        } else {
            $errorMsg = '<div class="alert alert-danger text-center" role="alert">You are not allowed to see this page</div>';

            redirectHome($errorMsg, 'back');
        }
        // Add Member Title
    } elseif ($do == 'Add') {
        GetPageTitle('Add New Member');
        ?>
        <form class="" action="?do=Insert" method="POST">

            <div class="form-group">
                <label for="exampleInputEmail1">User Name</label>
                <input type="text" required name='username' class="form-control" id="usernameInput" aria-describedby="emailHelp" placeholder="Enter User Name">
            </div>

            <div class="form-group">
                <label for="exampleInputPassword1">Password</label>
                <input type="password" name='newpassword' class="form-control password" id="passwordInput" placeholder="Password">
                <i class="show-pass fa-eye fa-solid fa fa-2x"></i>
            </div>

            <div class="form-group">
                <label for="exampleInputEmail1">Email address</label>
                <input type="email" required name='email' class="form-control" id="emailInput" aria-describedby="emailHelp" placeholder="Enter email">
                <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
            </div>

            <div class="form-group">
                <label for="exampleInputPassword1">Full Name</label>
                <input type="text" required name='fullname' class="form-control" id="fullnameInput" placeholder="Full Name">
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
        </div>
<?php
    } elseif ($do == 'Delete') {
        // Delete Member Title
        GetPageTitle('Delete Member');

        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? $_GET['userid'] : 0;
        // Get User Data
        $count = count(GetUser($userid));

        if ($count > 0) {
            $DeleteRowCount = DeleteUser($userid);
            // Delete Data From DB
            $msg = '<div class="container">
            <div class="alert alert-success text-center">
                <h4>' . $DeleteRowCount . ' Record Deleted'  . '</h1>
            </div>
        </div>';
            redirectHome($msg);
        } else {
            $msg = '<div class="container">
                <div class="alert alert-danger text-center">
                    <h4>There is no such user</h1>
                </div>
            </div>';
            redirectHome($msg);
        }
    } elseif ($do == 'Insert') {
        echo "<h1 class='text-center'>Insert Member</h1>";
        echo '<div class="container">';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get Variables From the Form
            $id = $_SESSION['ID'];
            $username = $_POST['username'];
            $email = $_POST['email'];
            $fullname = $_POST['fullname'];
            $pass = $_POST['newpassword'];

            $hashedPass = sha1($pass);

            // Validate The Form
            $formErrors = [];
            if (empty($username)) {
                $formErrors[] = 'Username is required';
            }
            if (empty($email)) {
                $formErrors[] = 'Email is required';
            }
            if (empty($pass)) {
                $formErrors[] = 'Password is required';
            }
            if (empty($fullname)) {
                $formErrors[] = 'Full Name is required';
            }
            // Show The Errors if there are any
            foreach ($formErrors as $error) {

                echo '<div class="alert alert-danger">' . $error . '</div>';
            }

            // If there is no errors then update the database
            if (empty($formErrors)) {
                $check = FindUser("Username", "users", $username);
                if ($check === 0) {
                    AddMember($hashedPass, $username, $email, $fullname);
                    $msg = '<div class="alert alert-success text-center" role="alert"> 1 Record Inserted</div>';
                    redirectHome($msg, 'back');
                    echo '<div class="alert alert-success text-center" role="alert"> 1 Record Inserted</div>';
                } else {
                    $msg = '<div class="alert alert-danger text-center" role="alert"> This User Already Exists</div>';
                    redirectHome($msg, 'back');
                }
            }

            echo '</div>';
        } else {
            $theMsg = '<div class="alert alert-danger" role="alert"> This User Already Exists</div>';
            redirectHome($theMsg, 'back');
        }
    } elseif ($do == 'Activate') {

        GetPageTitle('Activate Member');
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? $_GET['userid'] : 0;
        if (FindUser('UserID', 'users', $userid)) {
            if (ActivateMember($userid)) {
                $msg = '<div class="container">
                <div class="alert alert-success text-center">
                    <h4> User Activated</h4>
                </div>
            </div>';
                redirectHome($msg, 'back');
            } else {
                $msg = '<div class="container">
                <div class="alert alert-danger text-center">
                    <h4>This User Already Activated</h1>
                </div>
            </div>';
                redirectHome($msg);
            }
        } else {
            $msg = '<div class="container">
            <div class="alert alert-danger text-center">
                <h4>There is no such user</h1>
            </div>
        </div>';
            redirectHome($msg);
        }
    } elseif ($do == 'Deactivate') {

        GetPageTitle('Deactivate Member');
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? $_GET['userid'] : 0;
        if (FindUser('UserID', 'users', $userid)) {
            if (DeactivateMember($userid)) {
                $msg = '<div class="container">
                <div class="alert alert-success text-center">
                    <h4> User Activated</h4>
                </div>
            </div>';
                redirectHome($msg, 'back');
            } else {
                $msg = '<div class="container">
                <div class="alert alert-danger text-center">
                    <h4>This User Already Activated</h1>
                </div>
            </div>';
                redirectHome($msg);
            }
        } else {
            $msg = '<div class="container">
            <div class="alert alert-danger text-center">
                <h4>There is no such user</h1>
            </div>
        </div>';
            redirectHome($msg);
        }
    }
    include $tbl . 'footer.php';
}
