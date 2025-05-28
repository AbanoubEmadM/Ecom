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
        GetPageTitle("Manage Comments");

        $rows = GetComments();
?>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Comment</th>
                        <th>Item Name</th>
                        <th>User name</th>
                        <th>Added Date</th>
                        <th>Control</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <td><?= $row['CID'] ?></td>
                            <td><?= $row['Comment'] ?></td>
                            <td><?= $row['ProductName'] ?></td>
                            <td><?= $row['Username'] ?></td>
                            <td><?= $row['CommentDate'] ?></td>
                            <td>
                                <a href="?do=Edit&commentid=<?= $row['CID'] ?>" class="btn btn-success">Edit</a>
                                <a href="?do=Delete&commentid=<?= $row['CID'] ?>" class="btn btn-danger confirm">Delete</a>
                                <?php if ($row['Status'] == 0): ?>
                                    <a href="?do=Activate&commentid=<?= $row['CID'] ?>" class="btn btn-primary confirm">Activate</a>
                                <?php else: ?>
                                    <a href="?do=Deactivate&commentid=<?= $row['CID'] ?>" class="btn btn-primary confirm">Deactivate</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
        break;

    case 'Edit':
        $commID = $_GET['commentid'];
        $row = GetItem('*', 'comments', "CID = ?", [$commID]);

        if ($row) {
            GetPageTitle("Edit Product");
        ?>
            <form action="?do=Update" method="POST">
                <input type="hidden" name="commID" value="<?= $commID ?>">
                <div class="form-group">
                    <label for="exampleFormControlTextarea1">Edit Comment</label>
                    <input type="text" value="<?= $row['Comment'] ?>" class="form-control" name='comment' id="comment" />
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
<?php
        } else {
            $msg = "<div class='alert alert-danger'>Product Not Found</div>";
            redirectHome($msg, 'back');
        }

        break;

    case 'Update':
        GetPageTitle('Update Member');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $commID = $_POST['commID'];
            $comment = $_POST['comment'];

            $formErrors = [];
            if (empty($commID)) $formErrors[] = 'Comment ID is required';

            foreach ($formErrors as $error) {
                echo "<div class='alert alert-danger'>$error</div>";
            }

            if (empty($formErrors)) {
                if (UpdateComment($commID, $comment)) {
                    redirectHome("<div class='alert alert-success text-center'>1 Record Updated</div>", 'back');
                }
            }
        } else {
            redirectHome("<div class='alert alert-danger'>You are not allowed to see this page</div>", 'back');
        }
        break;

    case 'Delete':
        GetPageTitle('Delete Member');

        $commID = is_numeric($_GET['commentid'] ?? 0) ? $_GET['commentid'] : 0;

        if (CheckItem('CID', 'comments', $commID)) {
            DeleteComment($commID);
            redirectHome("<div class='alert alert-success text-center'>1 Record Deleted</div>");
        } else {
            redirectHome("<div class='alert alert-danger text-center'>There is no such Comment</div>");
        }
        break;

    case 'Activate':
        GetPageTitle('Activate Member');
        $commID = is_numeric($_GET['commentid'] ?? 0) ? $_GET['commentid'] : 0;

        if (CheckItem('CID', 'comments', $commID)) {
            if (ActivateComment($commID)) {
                redirectHome("<div class='alert alert-success text-center'>Comment Activated</div>", 'back');
            } else {
                redirectHome("<div class='alert alert-danger text-center'>This Comment is Already Active</div>");
            }
        } else {
            redirectHome("<div class='alert alert-danger text-center'>There is no such Comment</div>");
        }
        break;

    case 'Deactivate':
        GetPageTitle('Deactivate Member');
        $commID = is_numeric($_GET['commentid'] ?? 0) ? $_GET['commentid'] : 0;

        if (CheckItem('CID', 'comments', $commID)) {
            if (DeactivateComment($commID)) {
                redirectHome("<div class='alert alert-success text-center'>Comment Deactivated</div>", 'back');
            } else {
                redirectHome("<div class='alert alert-danger text-center'>This Comment is Already Deactivated</div>");
            }
        } else {
            redirectHome("<div class='alert alert-danger text-center'>There is no such Comment</div>");
        }
        break;

    default:
        redirectHome("<div class='alert alert-danger'>Invalid Action</div>");
        break;
}

include $tbl . 'footer.php';
?>