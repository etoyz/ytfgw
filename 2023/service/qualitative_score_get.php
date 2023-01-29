<?php
/**
 * 获取定性打分
 */
session_start();

require "../include/common.php";
require "../include/verify_login.php";

if (has_permission_admin_super() | has_permission_admin_expert()) {  // 验证是否是超管或专家
    $user = $_GET['user'];
    $db = new DB();
    $sql = "SELECT `企业在行业中的地位和作用` AS `a1`,
                `企业技术中心创新资源整合情况` AS `a2`,
                `企业技术中心研究开发工作开展情况` AS `a3`,
                `企业技术中心取得的主要创新成果` AS `a4`,
                `企业技术创新战略和规划` AS `a5`
                FROM enterprise_score 
                WHERE `loginid` = '" . $db->escape($user) . "' AND `type` = '1';";
    $re = $db->query($sql);
    die(json_encode(array(
        "code" => 0,
        "count" => $re->num_rows,
        "data" => $re->fetch_all(MYSQLI_ASSOC)
    )));
} else
    die(json_encode(array(
        "code" => 1,
        "msg" => get_string("PERMISSION_DENY")
    )));