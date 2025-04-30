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
        echo "Manage User: ";
    } 
    elseif ($do == 'Edit') { // Edit Page

        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? $_GET['userid'] : 0;
        // Get User Data
        $stmt = $conn->prepare('Select * From Users Where UserID = ? Limit 1');
        $stmt->execute([$userid]);
        $row = $stmt->fetch();
        $count = $stmt->rowCount();
        // If there is such ID Show the Form
        if ($count > 0) { ?>
            <h1 class="text-center">Edit Member</h1>
            <div class="container">
            <form action="?do=Update" method="POST">
                <input type="hidden" name='userid' value="<?php echo $userid ?>">
                <div class="form-group">
                    <label for="exampleInputEmail1">User Name</label>
                    <input type="text" name='username' value="<?php echo $row['Username'] ?>" class="form-control" id="usernameInput" aria-describedby="emailHelp" placeholder="Enter User Name">
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Password</label>
                    <input type="hidden" name='oldpassword' value="<?php echo $row['Password'] ?>">
                    <input type="password" name='newpassword' class="form-control" id="passwordInput" placeholder="Password">
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Email address</label>
                    <input type="email" name='email' value="<?php echo $row['Email'] ?>" class="form-control" id="emailInput" aria-describedby="emailHelp" placeholder="Enter email">
                    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Full Name</label>
                    <input type="text" name='fullname' value="<?php echo $row['FullName'] ?>" class="form-control" id="fullnameInput" placeholder="Full Name">
                </div>

                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
            </div>  
        <?php 
        }
        else { ?>
            <div class="container">
                <div class="alert alert-danger">There is no such ID</div>
            </div>
        <?php }
    
    include $tbl . 'footer.php';
    } 
    elseif ($do == 'Update') {
        echo "<h1 class='text-center'>Edit Member</h1>";
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get Variables From the Form
            $id = $_POST['userid'];
            $username = $_POST['username'];
            $password = $_POST['password'];
            $email = $_POST['email'];
            $fullname = $_POST['fullname'];

            // Update the Database with this Info
            $stmt = $conn->prepare("UPDATE users SET Username = ?, Password = ?, Email = ?, FullName = ? WHERE UserID = ?");
            $stmt->execute([$username, sha1($password), $email, $fullname, $id]);
            echo '<div class="alert alert-success text-center" role="alert">
                ' . $stmt->rowCount() . ' Record Updated </div>';
            include $tbl . 'footer.php';
        }
        else {
            header('location:index.php');
        }
    }
}