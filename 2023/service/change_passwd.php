<?php
/**
 * 企业&管理员 修改密码
 */
// 开始会话
session_start();

// 引入通用函数
require "../include/common.php";

// 如果已经登录
if (is_login()) {
    // 如果是企业用户
    if (is_enterprise()) {
        // 新建数据库连接
        $db = new DB();
        // 更新密码
        $sql = "UPDATE `enterprise` SET `loginpw` = '" . password_hash($_POST['loginpw'], PASSWORD_DEFAULT) . "' WHERE `loginid` = '" . $db->escape($_SESSION["loginid"]) . "'";
        $re = $db->query($sql);
        // 判断密码是否修改成功
        if ($re === true)
            // 如果成功则输出密码修改成功
            die("密码修改成功！");
        else
            // 如果未能成功则输出错误信息
            die($re);
    } else {
        // 如果是管理员用户
        // 引入管理员服务文件
        include_once "../../service_admin/services.php";
        // 执行修改管理员密码操作
        die(change_password_for_admin($_SESSION["loginid"], $_POST['loginpw']));
    }
}
// 如果未登录则输出未登录信息
die(get_string("NOT_LOGIN"));