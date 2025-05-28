<?php
// ? Manage Members
session_start();

if (!isset($_SESSION['username'])) {
    header("location: index.php");
    exit;
}

include 'init.php';

$do = $_GET['do'] ?? 'Manage';

switch ($do) {
    case 'Manage':
        GetPageTitle("Manage Members");

        $condition = 'GroupID != ? AND RegStatus = ?';
        $params = [1, ($_GET['page'] ?? '') === 'pending' ? 0 : 1];
        $orderBy = ($_GET['page'] ?? '') === 'pending' ? '' : 'RegDate';

        $rows = GetItems('*', 'users', $condition, $orderBy, $params);
        if (!empty($rows)) {
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
                        <?php foreach ($rows as $row): ?>
                            <tr>
                                <td><?= $row['UserID'] ?></td>
                                <td><?= $row['Username'] ?></td>
                                <td><?= $row['Email'] ?></td>
                                <td><?= $row['FullName'] ?></td>
                                <td><?= $row['RegDate'] ?></td>
                                <td>
                                    <a href="?do=Edit&userid=<?= $row['UserID'] ?>" class="btn btn-success">Edit</a>
                                    <a href="?do=Delete&userid=<?= $row['UserID'] ?>" class="btn btn-danger confirm">Delete</a>
                                    <?php if ($row['RegStatus'] == 0): ?>
                                        <a href="?do=Activate&userid=<?= $row['UserID'] ?>" class="btn btn-primary confirm">Activate</a>
                                    <?php else: ?>
                                        <a href="?do=Deactivate&userid=<?= $row['UserID'] ?>" class="btn btn-primary confirm">Deactivate</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <a class="btn btn-primary" href="?do=Add">+ New Member</a>

        <?php
        } else {
            echo '<div class="alert alert-warning">There Is No Members To Show</div>';
            echo '<a class="btn btn-primary" href="?do=Add">+ New Member</a>';
        }
        break;

    case 'Add':
        GetPageTitle('Add New Member');
        ?>
        <form action="?do=Insert" method="POST" class="form-control">
            <?php include 'member_form.php'; ?>
        </form>
        <?php
        break;

    case 'Insert':
        GetPageTitle('Insert Member');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $fullname = $_POST['fullname'];
            $pass = $_POST['newpassword'];

            $formErrors = [];
            if (empty($username)) $formErrors[] = 'Username is required';
            if (empty($email)) $formErrors[] = 'Email is required';
            if (empty($pass)) $formErrors[] = 'Password is required';
            if (empty($fullname)) $formErrors[] = 'Full Name is required';

            foreach ($formErrors as $error) {
                echo "<div class='alert alert-danger'>$error</div>";
            }

            if (empty($formErrors)) {
                if (CheckItem("Username", "users", "Username = ?", [$username]) === 0) {
                    AddMember(sha1($pass), $username, $email, $fullname);
                    redirectHome("<div class='alert alert-success text-center'>1 Record Inserted</div>", 'back');
                } else {
                    redirectHome("<div class='alert alert-danger text-center'>This User Already Exists</div>", 'back');
                }
            }
        } else {
            redirectHome("<div class='alert alert-danger'>You can't access this page directly</div>", 'back');
        }
        break;

    case 'Edit':
        $userID = is_numeric($_GET['userid'] ?? 0) ? $_GET['userid'] : 0;
        $row = GetItem('*', 'users', "UserID = ?", [$userID]);

        if ($row) {
            GetPageTitle("Edit Member");
        ?>
            <form action="?do=Update" method="POST" class="form-control">
                <input type="hidden" name="userid" value="<?= $userID ?>">
                <?php include 'member_form.php'; ?>
            </form>
<?php
        } else {
            redirectHome("<div class='alert alert-danger'>User Not Found</div>", 'back');
        }
        break;

    case 'Update':
        GetPageTitle('Update Member');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['userid'];
            $username = $_POST['username'];
            $email = $_POST['email'];
            $fullname = $_POST['fullname'];
            $pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);

            $formErrors = [];
            if (empty($username)) $formErrors[] = 'Username is required';
            if (empty($email)) $formErrors[] = 'Email is required';
            if (empty($fullname)) $formErrors[] = 'Full Name is required';

            foreach ($formErrors as $error) {
                echo "<div class='alert alert-danger'>$error</div>";
            }

            if (empty($formErrors)) {
                if (UpdateUser($username, $pass, $email, $fullname, $id)) {
                    redirectHome("<div class='alert alert-success text-center'>1 Record Updated</div>", 'back');
                } else {
                    redirectHome("<div class='alert alert-danger'>This User Already Exists</div>", 'back');
                }
            }
        } else {
            redirectHome("<div class='alert alert-danger'>You are not allowed to see this page</div>", 'back');
        }
        break;

    case 'Delete':
        GetPageTitle('Delete Member');

        $userid = is_numeric($_GET['userid'] ?? 0) ? $_GET['userid'] : 0;

        if (CheckItem('UserID', 'users', $userid)) {
            DeleteUser($userid);
            redirectHome("<div class='alert alert-success text-center'>1 Record Deleted</div>");
        } else {
            redirectHome("<div class='alert alert-danger text-center'>There is no such user</div>");
        }
        break;

    case 'Activate':
        GetPageTitle('Activate Member');
        $userid = is_numeric($_GET['userid'] ?? 0) ? $_GET['userid'] : 0;

        if (CheckItem('UserID', 'users', $userid)) {
            if (ActivateMember($userid)) {
                redirectHome("<div class='alert alert-success text-center'>User Activated</div>", 'back');
            } else {
                redirectHome("<div class='alert alert-danger text-center'>This User is Already Active</div>");
            }
        } else {
            redirectHome("<div class='alert alert-danger text-center'>There is no such user</div>");
        }
        break;

    case 'Deactivate':
        GetPageTitle('Deactivate Member');
        $userid = is_numeric($_GET['userid'] ?? 0) ? $_GET['userid'] : 0;

        if (CheckItem('UserID', 'users', $userid)) {
            if (DeactivateMember($userid)) {
                redirectHome("<div class='alert alert-success text-center'>User Deactivated</div>", 'back');
            } else {
                redirectHome("<div class='alert alert-danger text-center'>This User is Already Deactivated</div>");
            }
        } else {
            redirectHome("<div class='alert alert-danger text-center'>There is no such user</div>");
        }
        break;

    default:
        redirectHome("<div class='alert alert-danger'>Invalid Action</div>");
        break;
}

include $tbl . 'footer.php';
?>