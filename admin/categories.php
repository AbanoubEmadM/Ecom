<?php
ob_start();
session_start();
$pageTitle = 'Categories';
if (isset($_SESSION['username'])) {
    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
    if ($do == 'Manage') {

        GetPageTitle("Manage Categories");
?>
        <?php
        $sortingArr = ['Asc', 'Desc'];
        $sort = isset($_GET['sort']) && in_array($_GET['sort'], $sortingArr) ? $_GET['sort'] : 'Asc';
        $rows = GetItems('*', 'categories');
        if (!empty($rows)) {
        ?>
            <div class="categories-ordering">
                Ordering:
                <a class='<?php echo $sort == 'Asc' ? 'active' : '' ?>' href="?sort=Asc">Asc</a> |
                <a class='<?php echo $sort == 'Desc' ? 'active' : '' ?>' href="?sort=Desc">Desc</a>
            </div>
        <?php
            foreach ($rows as $row) {
                echo "<div class='container cat'>";
                echo "<h3>" . htmlspecialchars($row['CategoryName']) . "</h3>";
                echo '<div class="full-view">';
                echo "<p class='description'>" .
                    (!empty($row['CategoryDesc']) ? htmlspecialchars($row['CategoryDesc']) : "This category has no description") .
                    "</p>";
                echo "<ul class='category-meta'>";
                echo "<li><strong>Visibility:</strong> " . ($row['Visibility'] == 0 ? htmlspecialchars("Visibile") : htmlspecialchars("Hidden")) . "</li>";
                echo "<li><strong>Ads:</strong> " . ($row['AllowAds'] == 0 ? htmlspecialchars(" Allowed") : htmlspecialchars(" not Allowed")) . "</li>";
                echo "<li><strong>Comments:</strong> " . ($row['AllowComment'] == 0 ? htmlspecialchars(" Allowed") : htmlspecialchars(" not Allowed")) . "</li>";
                echo "</ul>";
                echo "<a href='?do=Edit&catID=" . $row['CategoryID'] . "' class='btn btn-success'>Edit</a>";
                echo "<a href='?do=Delete&catID=" . $row['CategoryID'] . "' class='btn btn-danger confirm'>Delete</a>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>No Categories Found</div>";
            echo '<a class="btn btn-primary" href="?do=Add">+ New Category</a>';
        }
        ?>
    <?php
    } elseif ($do == 'Add') {
        GetPageTitle('Add New Category');
    ?>
        <form class="add-category" action="?do=Insert" method="POST">
            <!-- Start Category Name  -->
            <div class="form-group">
                <label for="exampleInputEmail1">Category Name</label>
                <input type="text" required name='categoryname' class="form-control" id="categorynameInput" aria-describedby="emailHelp" placeholder="Enter Category Name">
            </div>

            <!-- End Category Name  -->
            <div class="form-group">
                <label for="exampleInputEmail1">Category Description</label>
                <input type="text" required name='categorydesc' class="form-control" id="categorydescInput" placeholder="Enter Category Description">
            </div>

            <!-- Start Ordering  -->
            <div class="form-group">
                <label for="exampleInputPassword1">Ordering</label>
                <input type="number" required name='ordering' class="form-control" id="orderingInput" placeholder="Enter Category Order">
            </div>
            <!-- End Ordering  -->

            <!-- Start Visibility  -->
            <div class="form-group">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="visibility" id="yes-vis" checked value="1">
                    <label class="form-check-label" for="yes-vis">
                        Visible
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="visibility" id="no-vis" value="0">
                    <label class="form-check-label" for="no-vis">
                        Hidden
                    </label>
                </div>
            </div>
            <!-- End Visibility  -->

            <!-- Start Comments  -->
            <div class="form-group">
                <label>Allow Comments</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="comments" id="yes-com" value="1">
                    <label class="form-check-label" for="yes-com">
                        Yes
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="comments" id="no-com" value="0" checked>
                    <label class="form-check-label" for="no-com">
                        No
                    </label>
                </div>
            </div>
            <!-- End Comments  -->


            <!-- Start Ads  -->
            <div class="form-group">
                <label>Allow Ads</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="ads" id="yes-ads" value="1">
                    <label class="form-check-label" for="yes-ads">
                        Yes
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="ads" id="no-ads" checked value="0">
                    <label class="form-check-label" for="no-ads">
                        No
                    </label>
                </div>
            </div>
            <!-- End Ads  -->
            <button type="submit" class="btn btn-primary">Add Category</button>
        </form>
        </div>
        <?php

    } elseif ($do == 'Insert') {
        echo "<h1 class='text-center'>Insert Category</h1>";
        echo '<div class="container">';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get Variables From the Form
            $CategoryName = $_POST['categoryname'];
            $CategoryDesc = ($_POST['categorydesc']);
            $Ordering = $_POST['ordering'];
            $Visibility = $_POST['visibility'];
            $AllowComment = $_POST['comments'];
            $AllowAds = $_POST['ads'];

            // Validate The Form
            $formErrors = [];
            if (empty($CategoryName)) {
                $formErrors[] = 'Category Name is required';
            }
            // Show The Errors if there are any
            foreach ($formErrors as $error) {

                echo '<div class="alert alert-danger">' . $error . '</div>';
            }

            // If there is no errors then update the database
            if (empty($formErrors)) {
                $check = CheckItem("CategoryName", "Categories", $CategoryName);
                if ($check === 0) {
                    AddCategory($CategoryName, $CategoryDesc, $Ordering, $Visibility, $AllowComment, $AllowAds);
                    $msg = '<div class="alert alert-success text-center" role="alert"> 1 Record Inserted</div>';
                    //  redirectHome($msg, 'back');
                    echo '<div class="alert alert-success text-center" role="alert"> 1 Record Inserted</div>';
                } else {
                    $msg = '<div class="alert alert-danger text-center" role="alert"> This User Already Exists</div>';
                    redirectHome($msg, 'back');
                }
            }

            echo '</div>';
        } else {
            $theMsg = '<div class="alert alert-danger" role="alert"> You Cant Access This Page Directly</div>';
            redirectHome($theMsg, 'back');
        }
    } elseif ($do == 'Delete') {
        GetPageTitle('Delete Member');

        $catID = isset($_GET['catID']) && is_numeric($_GET['catID']) ? $_GET['catID'] : 0;
        // Get User Data
        $count = count(GetItem('*', 'categories', ' CategoryID = ?', [$catID]));

        if ($count > 0) {
            $DeleteRowCount = DeleteCategory($catID);
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
    } elseif ($do == 'Edit') {
        $catID = isset($_GET['catID']) && is_numeric($_GET['catID']) ? $_GET['catID'] : 0;
        $row = GetItem('*', 'categories', "CategoryID = ?", [$catID]);
        $count = count($row);
        // If there is such ID Show the Form
        if ($count > 0) {
            GetPageTitle("Edit Category");
            // echo '<pre>';
            // print_r($row);
        ?>
            <form class="add-category" action="?do=Update" method="POST">
                <!-- Start Category Name  -->
                <input type="hidden" name='catID' value="<?php echo $row['CategoryID'] ?>">

                <div class="form-group">
                    <label for="exampleInputEmail1">Category Name</label>
                    <input type="text" required name='categoryname' value="<?php echo $row['CategoryName'] ?>" class="form-control" id="categorynameInput" aria-describedby="emailHelp" placeholder="Enter Category Name">
                </div>

                <!-- End Category Name  -->
                <div class="form-group">
                    <label for="exampleInputEmail1">Category Description</label>
                    <input type="text" required name='categorydesc' value="<?php echo $row['CategoryDesc'] ?>" class="form-control" id="categorydescInput" placeholder="Enter Category Description">
                </div>

                <!-- Start Ordering  -->
                <div class="form-group">
                    <label for="exampleInputPassword1">Ordering</label>
                    <input value="<?php echo $row['Ordering'] ?>" type="number" required name='ordering' class="form-control" id="orderingInput" placeholder="Enter Category Order">
                </div>
                <!-- End Ordering  -->

                <!-- Start Visibility  -->
                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="visibility" id="yes-vis" checked <?php if ($row['Visibility'] == 1) {
                                                                                                                echo 'checked';
                                                                                                            } ?> value="1">
                        <label class="form-check-label" for="yes-vis">
                            Visible
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="visibility" id="no-vis" <?php if ($row['Visibility'] == 0) {
                                                                                                        echo 'checked';
                                                                                                    } ?> value="0">
                        <label class="form-check-label" for="no-vis">
                            Hidden
                        </label>
                    </div>
                </div>
                <!-- End Visibility  -->

                <!-- Start Comments  -->
                <div class="form-group">
                    <label>Allow Comments</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="comments" id="yes-com" <?php if ($row['AllowComment'] == 1) {
                                                                                                        echo 'checked';
                                                                                                    } ?> value="1">
                        <label class="form-check-label" for="yes-com">
                            Yes
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="comments" id="no-com" <?php if ($row['AllowComment'] == 0) {
                                                                                                        echo 'checked';
                                                                                                    } ?> value="0">
                        <label class="form-check-label" for="no-com">
                            No
                        </label>
                    </div>
                </div>
                <!-- End Comments  -->


                <!-- Start Ads  -->
                <div class="form-group">
                    <label>Allow Ads</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="ads" id="yes-ads" <?php if ($row['AllowAds'] == 1) {
                                                                                                    echo 'checked';
                                                                                                } ?> value="1">
                        <label class="form-check-label" for="yes-ads">
                            Yes
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="ads" id="no-ads" <?php if ($row['AllowAds'] == 0) {
                                                                                                echo 'checked';
                                                                                            } ?> value="0">
                        <label class="form-check-label" for="no-ads">
                            No
                        </label>
                    </div>
                </div>
                <!-- End Ads  -->
                <button type="submit" class="btn btn-primary">Add Category</button>
            </form>

<?php
        }
    } elseif ($do == 'Update') {
        GetPageTitle('Update Member');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get Variables From the Form
            $catID = $_POST['catID'];
            $CategoryName = $_POST['categoryname'];
            $CategoryDesc = ($_POST['categorydesc']);
            $Ordering = $_POST['ordering'];
            $Visibility = $_POST['visibility'];
            $AllowComment = $_POST['comments'];
            $AllowAds = $_POST['ads'];

            // Validate The Form
            $formErrors = [];
            if (empty($CategoryName)) {
                $formErrors[] = 'Category Name is required';
            }
            // Show The Errors if there are any
            foreach ($formErrors as $error) {

                echo '<div class="alert alert-danger">' . $error . '</div>';
            }
            // If there is no errors then update the database
            if (empty($formErrors)) {
                // Update the Database with this Info
                if (UpdateCategory($CategoryName, $CategoryDesc, $Ordering, $Visibility, $AllowComment, $AllowAds, $catID)) {
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
    }
    include $tbl . 'footer.php';
} else {
    header('Location: index.php');
    exit();
}


ob_end_flush();
