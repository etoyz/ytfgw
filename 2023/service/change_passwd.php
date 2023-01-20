<?php
/**
 * 企业&管理员 修改密码
 */
session_start();
if (isset($_SESSION['usertype'])) {
    if ($_SESSION['usertype'] === "enterprise") {
        require_once "../include/db.php";
        $db = new DB();
        $sql = "UPDATE `enterprise` SET `loginpw` = '" . password_hash($_POST['loginpw'], PASSWORD_DEFAULT) . "' WHERE `loginid` = '" . $db->escape($_SESSION["loginid"]) . "'";
        if ($db->query($sql)) {
            die("密码修改成功！");
        }
    } else {
        include_once "../../service_admin/services.php";
        die(change_password_for_admin($_SESSION["loginid"], $_POST['loginpw']));
    }
}
die("未登录");