<?php
ob_start();
session_start();
$pageTitle = 'Products';

if (isset($_SESSION['username'])) {
    include 'init.php';

    $do = $_GET['do'] ?? 'Manage';
    $condition = 'Approve = ?';
    $params = [1, ($_GET['page'] ?? '') === 'pending' ? 0 : 1];
    $orderBy = ($_GET['page'] ?? '') === 'pending' ? '' : 'RegDate';

    switch ($do) {
        case 'Manage':
            GetPageTitle("Manage Products");
            $rows = GetUsersWithProducts();
            if (!empty($rows)) {
                echo '<div class="product-grid">';
?>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>#ID</th>
                                <th>Product Name</th>
                                <th>Product Description</th>
                                <th>Price</th>
                                <th>Adding Date</th>
                                <th>Category</th>
                                <th>Username</th>
                                <th>Control</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rows as $row): ?>
                                <tr>
                                    <td><?= $row['ProductID'] ?></td>
                                    <td><?= $row['ProductName'] ?></td>
                                    <td><?= $row['ProductDesc'] ?></td>
                                    <td><?= $row['ProductPrice'] ?></td>
                                    <td><?= $row['AddDate'] ?></td>
                                    <td><?= $row['CategoryName'] ?></td>
                                    <td><?= $row['Username'] ?></td>
                                    <td>
                                        <?php if ($row['Approve'] == 0): ?>
                                            <a href='?do=Approve&productID=<?= $row['ProductID'] ?>' class='btn btn-primary confirm'>Approve</a>
                                        <?php else: ?>
                                            <a href='?do=Deactivate&productID=<?= $row['ProductID'] ?>' class='btn btn-primary confirm'>Deactivate</a>
                                        <?php endif; ?>
                                        <a href='?do=Edit&productID=<?= $row['ProductID'] ?>' class='btn btn-success'>Edit</a>
                                        <a href='?do=Delete&productID=<?= $row['ProductID'] ?>' class='btn btn-danger confirm'>Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php
            } else {
                echo '<div class="alert alert-warning">There Is No Products To Show</div>';
                echo '<a class="btn btn-primary" href="?do=Add">+ New Product</a>';
            }
            echo '</div>';
            echo '<a class="btn btn-primary" href="?do=Add">+ New Product</a>';
            break;

        case 'Add':
            GetPageTitle('Add New Product');
            ?>
            <form action="?do=Insert" method="POST">
                <?php include 'product_form.php'; ?>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
            <?php
            break;

        case 'Insert':
            GetPageTitle('Insert Product');

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $PostParams = $_POST;
                $formErrors = [];

                if (empty($PostParams['name'])) $formErrors[] = 'Name is required';
                if (empty($PostParams['desc'])) $formErrors[] = 'Description is required';
                if (empty($PostParams['price'])) $formErrors[] = 'Price is required';
                if (empty($PostParams['country'])) $formErrors[] = 'Country is required';

                foreach ($formErrors as $error) {
                    echo '<div class="alert alert-danger">' . $error . '</div>';
                }

                if (empty($formErrors)) {
                    AddProduct($PostParams);
                    $msg = '<div class="alert alert-success text-center">1 Record Inserted</div>';
                    redirectHome($msg, 'back');
                }
            } else {
                $msg = '<div class="alert alert-danger">You can’t access this page directly</div>';
                redirectHome($msg, 'back');
            }

            break;

        case 'Edit':
            $productID = isset($_GET['productID']) && is_numeric($_GET['productID']) ? $_GET['productID'] : 0;
            $row = GetItem('*', 'products', "ProductID = ?", [$productID]);
            GetPageTitle("Edit Comments");

            if ($row) {
            ?>
                <form action="?do=Update" method="POST">
                    <input type="hidden" name="productID" value="<?= $productID ?>">
                    <?php include 'product_form.php'; ?>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
                <?php
                GetPageTitle("Product Comments");
                $rows = GetProductComments($productID);
                ?>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Comment</th>
                                <th>User name</th>
                                <th>Added Date</th>
                                <th>Control</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rows as $row): ?>
                                <tr>
                                    <td><?= $row['Comment'] ?></td>
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
            } else {
                $msg = "<div class='alert alert-danger'>Product Not Found</div>";
                redirectHome($msg, 'back');
            }

            break;

        case 'Update':
            GetPageTitle('Update Product');

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $productID = $_POST['productID'];
                $PostParams = $_POST;
                $formErrors = [];
                if (empty($PostParams['name'])) $formErrors[] = 'Name is required';
                if (empty($PostParams['desc'])) $formErrors[] = 'Description is required';
                if (empty($PostParams['price'])) $formErrors[] = 'Price is required';
                if (empty($PostParams['country'])) $formErrors[] = 'Country is required';

                foreach ($formErrors as $error) {
                    echo '<div class="alert alert-danger">' . $error . '</div>';
                }

                if (empty($formErrors)) {
                    if (UpdateProduct($PostParams)) {
                        $msg = '<div class="alert alert-success text-center">1 Record Updated</div>';
                        redirectHome($msg, 'back');
                    }
                }
            } else {
                $msg = '<div class="alert alert-danger text-center">You are not allowed to see this page</div>';
                redirectHome($msg, 'back');
            }

            break;

        case 'Delete':
            GetPageTitle('Delete Product');

            $productID = isset($_GET['productID']) && is_numeric($_GET['productID']) ? $_GET['productID'] : 0;
            $row = GetItem('*', 'products', 'ProductID = ?', [$productID]);

            if ($row) {
                $DeleteRowCount = DeleteProduct($productID);
                $msg = "<div class='alert alert-success text-center'>{$DeleteRowCount} Record Deleted</div>";
                redirectHome($msg);
            } else {
                $msg = '<div class="alert alert-danger text-center">There is no such product</div>';
                redirectHome($msg);
            }

            break;
        case 'Approve':
            GetPageTitle('Approve Product');
            $productID = isset($_GET['productID']) && is_numeric($_GET['productID']) ? $_GET['productID'] : 0;
            $row = GetItem('*', 'products', 'ProductID = ?', [$productID]);

            if ($row) {
                $ApproveRowCount = ApproveProduct($productID);
                $msg = "<div class='alert alert-success text-center'>{$ApproveRowCount} Record Approved</div>";
                redirectHome($msg);
            } else {
                $msg = '<div class="alert alert-danger text-center">There is no such product</div>';
                redirectHome($msg);
            }
            break;
        case 'Deactivate':
            GetPageTitle('Deactivate Product');
            $productID = isset($_GET['productID']) && is_numeric($_GET['productID']) ? $_GET['productID'] : 0;
            $row = GetItem('*', 'products', 'ProductID = ?', [$productID]);

            if ($row) {
                $DeactivateRowCount = DeactivateProduct($productID);
                $msg = "<div class='alert alert-success text-center'>{$DeactivateRowCount} Record Deactivated</div>";
                redirectHome($msg);
            } else {
                $msg = '<div class="alert alert-danger text-center">There is no such product</div>';
                redirectHome($msg);
            }
            break;
    }

    include $tbl . 'footer.php';
} else {
    header("Location: index.php");
    exit();
}
?>