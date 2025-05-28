<?php
// Title Function That Echos Page Title
function getTitle()
{
    global $pageTitle;
    if (isset($pageTitle)) {
        echo $pageTitle;
    } else {
        echo 'Default';
    }
}

function CheckUserStatus($username)
{
    global $conn;
    $stmt = $conn->prepare('Select 
                                UserID,Username, Password 
                            from 
                                users 
                            WHERE 
                                username = ? 
                            AND
                                RegStatus = 0
                            LIMIT 1');
    $stmt->execute([$username]);
    $row = $stmt->fetch();
    $count = $stmt->rowCount();
    return $count;
}

function getCategories()
{
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM categories ORDER BY CategoryID DESC");
    $stmt->execute();
    $rows = $stmt->fetchAll();
    return $rows;
}

// Redirect Function
/*
    ? Home Redirect Function
    ? This Function Accept Params [ $msg => Success | Error | Warning | Info | Danger ]
    ? This Function Accept Params [ $seconds => seconds before redirecting to home page ]
*/
function redirectHome($errorMsg, $url = null, $seconds = 3)
{
    if ($url == null) {
        $url = 'index.php';
    } else {
        $url = isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';
    }
    echo $errorMsg;
    echo '<div class="alert alert-info">Redirecting to Home Page in ' . $seconds . ' seconds</div>';
    header("refresh: $seconds; url=$url");
    exit();
}
function CheckItem($select, $tbl, $condition, $params = [])
{
    global $conn;
    $allowedTables = ['users', 'products', 'comments'];
    $allowedColumns = ['*', 'UserID', 'Username', 'email', 'RegDate', 'CID'];

    if (!in_array($tbl, $allowedTables) || !in_array($select, $allowedColumns)) {
        throw new Exception("Invalid column or table name");
    }

    $sql = "SELECT $select FROM $tbl";
    if ($condition) {
        $sql .= " WHERE $condition";
    }

    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    return $stmt->rowCount();
}

function GetItem($select, $tbl, $condition = '', $params = [])
{
    global $conn;
    $allowedTables = ['users', 'products', 'categories', 'comments'];
    $allowedColumns = ['*', 'UserID', 'username', 'email', 'RegDate', 'ProductID'];

    if (!in_array($tbl, $allowedTables) || !in_array($select, $allowedColumns)) {
        throw new Exception("Invalid column or table name");
    }

    $sql = "SELECT $select FROM $tbl";
    if ($condition) {
        $sql .= " WHERE $condition";
    }

    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    $row = $stmt->fetch();
    return $stmt->rowCount() == 1 ? $row : [];
}
function GetItems(string $select, string $tbl, string $condition = '', string $orderByField = '', array $params = []): array
{
    // Get Admin Data
    global $conn;
    $allowedTables = ['users', 'products', 'categories', 'comments'];
    $allowedColumns = ['*', 'UserID', 'username', 'email', 'RegDate'];

    if (!in_array($tbl, $allowedTables) || !in_array($select, $allowedColumns)) {
        throw new Exception("Invalid column or table name");
    }

    $sql = "SELECT $select FROM $tbl";
    if ($condition) {
        $sql .= " WHERE $condition";
    }

    if ($orderByField) {
        $sql .= " Order By $orderByField DESC ";
    }

    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    $rows = $stmt->fetchAll();
    return $rows;
}
function DeleteUser($userID)
{
    // Delete User Data
    global $conn;
    $stmt = $conn->prepare('Delete From Users Where UserID = :zID');
    $stmt->bindParam(':zID', $userID);
    $stmt->execute();
    return $stmt->rowCount();
}
function GetNumberOfItems($item, $tbl, $condition = '', $params = [])
{
    global $conn;
    $allowedTables = ['users', 'products', 'comments'];
    $allowedColumns = ['*', 'UserID', 'username', 'email', 'ProductID', 'CID'];
    if (!in_array($item, $allowedColumns) || !in_array($tbl, $allowedTables)) {
        throw new Exception("Invalid column or table name");
    }
    $sql = "Select Count($item) From $tbl";
    if ($condition) {
        $sql .= " Where $condition";
    }
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $count = $stmt->fetchColumn();
    return $count;
}
function AddMember($hashedPass, $username, $email, $fullname)
{
    global $conn;
    $stmt = $conn->prepare("INSERT INTO Users (UserName, Password, Email, FullName, RegDate, RegStatus) VALUES (:zuser,:zpass, :zemail, :zfullname, now(), :zStatus)");
    $stmt->execute(['zuser' => $username, 'zpass' => $hashedPass, 'zemail' => $email, 'zfullname' => $fullname, 'zStatus' => 1]);
}
function UpdateUser($username, $pass, $email, $fullname, $id)
{
    global $conn;
    $stmt1 = $conn->prepare("SELECT * FROM users WHERE UserID != ? AND UserName = ?");
    $stmt1->execute([$id, $username]);
    if ($stmt1->rowCount() > 0) {
        return false;
    }
    $stmt2 = $conn->prepare("UPDATE users SET UserName = ?, Password = ?, Email = ?, FullName = ? WHERE UserID = ?");
    $stmt2->execute([$username, $pass, $email, $fullname, $id]);
    return $stmt2->rowCount() ? true : false;
}
function ActivateMember($id)
{
    global $conn;
    $stmt = $conn->prepare("UPDATE users SET RegStatus = ? WHERE UserID = ?");
    $stmt->execute([1, $id]);
    return $stmt->rowCount() ? true : false;
}
function DeactivateMember($id)
{
    global $conn;
    $stmt = $conn->prepare("UPDATE users SET RegStatus = ? WHERE UserID = ?");
    $stmt->execute([0, $id]);
    return $stmt->rowCount() ? true : false;
}
function GetLatestItems($tbl, $select, $limit = 6, $orderBy = 'desc', $orderByField = null)
{
    global $conn;
    $allowedTables = ['users', 'products', 'comments'];
    $allowedColumns = ['*', 'UserID', 'username', 'email', 'RegDate', 'ProductID', 'AddDate'];

    if (!in_array($tbl, $allowedTables) || !in_array($select, $allowedColumns) || ($orderByField !== null && !in_array($orderByField, $allowedColumns))) {
        throw new Exception("Invalid column or table name");
    }
    if ($orderByField == null) {
        $sql = "SELECT $select FROM $tbl LIMIT $limit";
    } else {
        $sql = "SELECT $select FROM $tbl ORDER By $orderByField $orderBy LIMIT $limit";
    }
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll();
    return $rows;
}
function AddCategory($CategoryName, $CategoryDesc, $Ordering, $Visibility, $AllowComment, $AllowAds)
{
    global $conn;
    $stmt = $conn->prepare("INSERT INTO Categories (`CategoryName`, `CategoryDesc`, `Ordering`, `Visibility`, `AllowComment`, `AllowAds`) VALUES (:zCatName,:zCatDesc, :zOrd, :zVis, :zCom, :zAds)");
    $stmt->execute(['zCatName' => $CategoryName, 'zCatDesc' => $CategoryDesc, 'zOrd' => $Ordering, 'zVis' => $Visibility, 'zCom' => $AllowComment, 'zAds' => $AllowAds]);
}
function UpdateCategory($CategoryName, $CategoryDesc, $Ordering, $Visibility, $AllowComment, $AllowAds, $id)
{
    global $conn;
    $stmt = $conn->prepare("UPDATE categories SET CategoryName = ?, CategoryDesc = ?, Ordering = ?, Visibility = ?, AllowComment = ?, AllowAds = ? WHERE CategoryID = ?");
    $stmt->execute([$CategoryName, $CategoryDesc, $Ordering, $Visibility, $AllowComment, $AllowAds, $id]);
    return $stmt->rowCount() ? true : false;
}
function DeleteCategory($catID)
{
    // Delete User Data
    global $conn;
    $stmt = $conn->prepare('Delete From Categories Where CategoryID = :zID');
    $stmt->bindParam(':zID', $catID);
    $stmt->execute();
    return $stmt->rowCount();
}
function AddProduct(array $PostParams)
{
    global $conn;
    $stmt = $conn->prepare("INSERT INTO products (`ProductName`, `ProductDesc`, `ProductPrice`, `Status`, `CountryMade`, `CategoryID`, `UserID`) VALUES (:zName,:zDesc, :zPrice, :zStatus, :zCountry, :zCategoryID, :zUserID)");
    $stmt->execute(['zName' => $PostParams['name'], 'zDesc' => $PostParams['desc'], 'zPrice' => $PostParams['price'], 'zStatus' => $PostParams['status'], 'zCountry' => $PostParams['country'], 'zCategoryID' => $PostParams['category'], 'zUserID' => $PostParams['members']]);
}
function GetUsersWithProducts()
{
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM `products`
                            INNER JOIN users on 
                            users.UserID = products.UserID
                            INNER JOIN categories on 
                            categories.CategoryID = products.CategoryID
                            ");
    $stmt->execute();
    $rows = $stmt->fetchAll();
    return $rows;
}
function UpdateProduct($PostParams)
{
    global $conn;
    $stmt = $conn->prepare("UPDATE products SET ProductName = ?, ProductDesc = ?, ProductPrice = ?, CountryMade = ?, Status = ?, CategoryID = ? WHERE ProductID = ?");
    $stmt->execute([$PostParams['name'], $PostParams['desc'], $PostParams['price'], $PostParams['country'], $PostParams['status'], $PostParams['category'], $PostParams['productID']]);
    return $stmt->rowCount() ? true : false;
}
function DeleteProduct($ProductID)
{
    // Delete User Data
    global $conn;
    $stmt = $conn->prepare('Delete From Products Where ProductID = :zID');
    $stmt->bindParam(':zID', $ProductID);
    $stmt->execute();
    return $stmt->rowCount();
}
function ApproveProduct($ProductID)
{
    global $conn;
    $stmt = $conn->prepare("UPDATE Products SET Approve = ? WHERE ProductID = ?");
    $stmt->execute([1, $ProductID]);
    return $stmt->rowCount() ? true : false;
}
function DeactivateProduct($ProductID)
{
    global $conn;
    $stmt = $conn->prepare("UPDATE Products SET Approve = ? WHERE ProductID = ?");
    $stmt->execute([0, $ProductID]);
    return $stmt->rowCount() ? true : false;
}
function GetComments()
{
    global $conn;
    $stmt = $conn->prepare("SELECT CID, Comments.Status, Comment, Products.ProductName, Username, CommentDate FROM `comments` 
                            join products on 
                            comments.ProductID = products.ProductID
                            join users on 
                            comments.UserID = users.UserID
                            ORDER BY CommentDate DESC
                            ");
    $stmt->execute();
    $rows = $stmt->fetchAll();
    return $rows;
}
function GetProductComments($ProductID)
{
    global $conn;
    $stmt = $conn->prepare("SELECT CID, Comments.Status, Comment, Username, CommentDate FROM `comments` 
                            join users on 
                            comments.UserID = users.UserID
                            Where ProductID = ?");
    $stmt->execute([$ProductID]);
    $rows = $stmt->fetchAll();
    return $rows;
}

function UpdateComment($commid, $comment)
{
    global $conn;
    $stmt = $conn->prepare("UPDATE Comments SET Comment = ? WHERE CID = ?");
    $stmt->execute([$comment, $commid]);
    return $stmt->rowCount() ? true : false;
}
function DeleteComment($commid)
{
    // Delete User Data
    global $conn;
    $stmt = $conn->prepare('Delete From Comments Where CID = :zID');
    $stmt->bindParam(':zID', $commid);
    $stmt->execute();
    return $stmt->rowCount();
}
function ActivateComment($commid)
{
    global $conn;
    $stmt = $conn->prepare("UPDATE Comments SET status = ? WHERE CID = ?");
    $stmt->execute([1, $commid]);
    return $stmt->rowCount() ? true : false;
}
function DeactivateComment($commid)
{
    global $conn;
    $stmt = $conn->prepare("UPDATE Comments SET status = ? WHERE CID = ?");
    $stmt->execute([0, $commid]);
    return $stmt->rowCount() ? true : false;
}
function GetLatestComments()
{
    global $conn;
    $stmt = $conn->prepare("SELECT CID, Comments.Status, Comment, Products.ProductName, Username, CommentDate FROM `comments` 
                            join products on 
                            comments.ProductID = products.ProductID
                            join users on 
                            comments.UserID = users.UserID
                            ORDER BY CommentDate DESC
                            Limit 5
                            ");
    $stmt->execute();
    $rows = $stmt->fetchAll();
    return $rows;
}
