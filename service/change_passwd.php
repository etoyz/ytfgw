<?php
/**
 * 企业&管理员 修改密码
 */
session_start();
if (isset($_SESSION['usertype'])) {
    $sql = null;
    require_once "../include/db.php";
    $db = new DB();
    if ($_SESSION['usertype'] === "enterprise") {
        $sql = "UPDATE `enterprise` SET `loginpw` = '" . password_hash($_POST['loginpw'], PASSWORD_DEFAULT) . "' WHERE `loginid` = '" . $db->escape($_SESSION["loginid"]) . "'";
    } else {
//        $sql = "UPDATE `manager` SET `loginpw` = " . password_hash($_POST['loginpw'], PASSWORD_DEFAULT . "WHERE `loginid` = $_SESSION[loginid]");
        $sql = "UPDATE `manager` SET `loginpw` = '" . $db->escape($_POST['loginpw']) . "'WHERE `loginid` = '" . $db->escape($_SESSION["loginid"]) . "'";
    }
    if ($db->query($sql)){
        session_destroy();
        die("密码修改成功！");
    }
}
die("未登录");