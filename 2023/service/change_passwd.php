<?php
/**
 * 企业&管理员 修改密码
 */
session_start();

require "../include/common.php";

if (is_login()) {
    if (is_enterprise()) {
        $db = new DB();
        $sql = "UPDATE `enterprise` SET `loginpw` = '" . password_hash($_POST['loginpw'], PASSWORD_DEFAULT) . "' WHERE `loginid` = '" . $db->escape($_SESSION["loginid"]) . "'";
        $re = $db->query($sql);
        if ($re === true)
            die("密码修改成功！");
        else
            die($re);
    } else {
        include_once "../../service_admin/services.php";
        die(change_password_for_admin($_SESSION["loginid"], $_POST['loginpw']));
    }
}
die(get_string("NOT_LOGIN"));