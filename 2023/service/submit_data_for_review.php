<?php
/**
 * 提交审核（之后不可更改评判数据）
 */
session_start();

require "../include/common.php";

if (is_login()) { // 验证登录状态
    if (is_enterprise()) {  // 验证是否是企业用户登录
        $status = get_user_status($_SESSION['loginid']);

        if ($status <= 4)
            $dst_status = 2;
        else
            $dst_status = 7;

        $status %= 5;
        if ($status != "1" && $status != "3") {
            if ($status == "0")
                die("请先完善企业信息！");
            else if ($status == "2")
                die("您已经提交，请勿重复提交！");
            else if ($status == "4")
                die("已经通过！");
        }

        $db = new DB();
        $sql = "UPDATE `enterprise` set  `status` = $dst_status WHERE `loginid` = '" . $db->escape($_SESSION["loginid"]) . "'";
        $re = $db->query($sql);
        if ($re === true) // 成功
            die("1");
        else
            die("数据库错误：" . $re);
    } else
        die(get_string("ENTERPRISE_ONLY"));
} else
    die(get_string("NOT_LOGIN"));