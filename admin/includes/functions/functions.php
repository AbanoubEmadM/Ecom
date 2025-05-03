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
function FindUser($select, $from, $value)
{
    global $conn;
    $stmt = $conn->prepare("SELECT $select FROM $from WHERE $select = ?");
    $stmt->execute([$value]);
    return $stmt->rowCount();
}

function GetUser(int $userID)
{
    // Get User Data
    global $conn;
    $stmt = $conn->prepare('Select * From Users Where UserID = ? Limit 1');
    $stmt->execute([$userID]);
    $row = $stmt->fetch();
    return $stmt->rowCount() == 1 ? $row : [];
}
function GetUsers($condition = null)
{
    // Get Admin Data
    global $conn;
    $stmt = $conn->prepare("Select * from users Where GroupID != 1 $condition");
    $stmt->execute();
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
function GetNumberOfItems($item, $tbl, $condition = null)
{
    global $conn;
    $stmt = $conn->prepare("Select Count($item) From $tbl $condition");
    $stmt->execute();
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
    $stmt = $conn->prepare("UPDATE users SET Username = ?, Password = ?, Email = ?, FullName = ? WHERE UserID = ?");
    $stmt->execute([$username, $pass, $email, $fullname, $id]);
    return $stmt->rowCount() ? true : false;
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
function GetLatestItems($tbl, $select, $limit = 6, $orderBy = 'desc', $orderByField)
{
    global $conn;
    $stmt = $conn->prepare("SELECT $select FROM $tbl ORDER By $orderByField $orderBy LIMIT $limit");
    $stmt->execute();
    $rows = $stmt->fetchAll();
    return $rows;
}
