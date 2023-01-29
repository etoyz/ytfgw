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
        // 获取定量打分
        $sql_get = "SELECT `得分汇总（定量）` FROM enterprise_score
	            WHERE loginid='$user' AND `type`=1";
        $re_get = $db->query($sql_get);
        if ($re_get->num_rows === 0)
            die("请先核定企业数据，完成定量打分！");

        $score_cnt1 = $re_get->fetch_array()[0]; //定量打分
        $score_cnt2 = $_POST['a1'] + $_POST['a2'] + $_POST['a3'] + $_POST['a4'] + $_POST['a5']; //定性打分
        $score_cnt = $score_cnt1 + $score_cnt2; //总得分
        $sql = "UPDATE enterprise_score
	            SET `企业在行业中的地位和作用`='$_POST[a1]',
	                `企业技术中心创新资源整合情况`='$_POST[a2]',
	                `企业技术中心研究开发工作开展情况`='$_POST[a3]',
	                `企业技术中心取得的主要创新成果`='$_POST[a4]',
	                `企业技术创新战略和规划`='$_POST[a5]',
	                `得分汇总（定性）`='$score_cnt2',
	                `得分汇总（全部）`='$score_cnt'
	            WHERE loginid='$user' AND `type`=1";
        $re = $db->query($sql);
        if ($re === true)
            die("1");
        else
            die("数据库错误：" . $re);
    } else
        die(get_string("PERMISSION_DENY"));
} else
    die(get_string("NOT_LOGIN"));