<?php
session_start();
if (isset($_SESSION['usertype'])) {
    $sql = null;
    require_once "db.php";
    $db = new DB();
    if ($_SESSION['usertype'] === "enterprise") {
        $sql = "UPDATE `enterprise` SET `loginpw` = '" . password_hash($_POST['loginpw'], PASSWORD_DEFAULT) . "' WHERE `loginid` = '$_SESSION[loginid]'";
    } else {
//        $sql = "UPDATE `manager` SET `loginpw` = " . password_hash($_POST['loginpw'], PASSWORD_DEFAULT . "WHERE `loginid` = $_SESSION[loginid]");
        $sql = "UPDATE `manager` SET `loginpw` = '" . $_POST['loginpw'] . "'WHERE `loginid` = '$_SESSION[loginid]'";
    }
    if ($db->query($sql))
        die("密码修改成功！");
}
die("未登录");