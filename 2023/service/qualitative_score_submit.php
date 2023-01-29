<?php
/**
 * 提交定性打分
 */
session_start();

require "../include/common.php";

if (is_login()) { // 验证登录状态
    if (has_permission_admin_super() | has_permission_admin_expert()) {  // 验证是否是超管或专家
        $user = $_GET['user'];
        $db = new DB();
        $sql_check = "SELECT `loginid` FROM enterprise_score
	            WHERE loginid='$user' AND `type`=1";
        if ($db->query($sql_check)->num_rows === 0)
            die("请先核定企业数据，完成定量打分！");
        $sql = "UPDATE enterprise_score
	            SET `企业在行业中的地位和作用`='$_POST[a1]',
	                `企业技术中心创新资源整合情况`='$_POST[a2]',
	                `企业技术中心研究开发工作开展情况`='$_POST[a3]',
	                `企业技术中心取得的主要创新成果`='$_POST[a4]',
	                `企业技术创新战略和规划`='$_POST[a5]'
	            WHERE loginid='$user' AND `type`=1";
        $re = $db->query($sql);
        if ($re === true)
            die("1");
        else
            die($re);
    } else
        die(get_string("PERMISSION_DENY"));
} else
    die(get_string("NOT_LOGIN"));