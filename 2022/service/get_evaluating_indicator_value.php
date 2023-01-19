<?php
/**
 * 获取评价指标与对应的值
 */
session_start();
$response = array(
    "status" => 'notLogin',
    "data" => null
);

if (isset($_SESSION['usertype'])) { // 已经登录
    $response["status"] = "isLogin";
    $response["data"] = [];
    $response["code"] = 0;

    $user = null;
    if ($_SESSION['usertype'] === "enterprise") { // 若是企业用户，则只能查询自己的
        $user = $_SESSION['loginid'];
    } else { // 若是管理员用户，则可以查询任何人的
        $user = $_GET['user'];
    }
    require_once '../include/db.php';
    $db = new DB();
    $user = $db->escape($user);
    $type = $db->escape($_GET['type']); // type为0表示企业提报的数据，为1表示专家核定的数据

    // patch
    if ($type == 1 && $_SESSION['privilege'] !== '0') { // 非超级管理员不可查看专家核定数据
        die(json_encode($response));
    }

    $sql = "DESCRIBE enterprise_data;"; // 表“enterprise_data”的各列名为评价指标名
    $re_indicators = $db->query($sql)->fetch_all(MYSQLI_ASSOC);
    $sql2 = "SELECT * FROM enterprise_data WHERE `loginid` = '$user' AND `type` = '$type';";
    $re_indicator_values = $db->query($sql2);
    if ($type == 1 && $re_indicator_values->num_rows == 0) // 若查询专家核定数据，而专家还未操作过，则返回企业原数据
    {
        $sql2 = "SELECT * FROM enterprise_data WHERE `loginid` = '$user' AND `type` = '0';";
        $re_indicator_values = $db->query($sql2);
    }
    $re_indicator_values = mysqli_fetch_array($re_indicator_values);
//    var_dump($re_indicator_values);
    // 各评价指标的单位
    $units = ["loginid", "type", "万元", "万元", "人", "人", "人", "人月", "个", "个", "个", "项", "项", "个", "个", "万元", "项", "项", "项", "项", "项", "项", "项", "万元", "万元", "万元", "项", "项", "万元", "项", "项", "项", "项", "---", "---", "---", "---", "---"];
    $i = 0;

    require_once "../include/common.php";
    $indicators_disabled = ["loginid", "type"]; // 被禁用的列
    if (get_user_status($user) <= 4) {// 申报体系
        array_push($indicators_disabled, "运行评价报告");
        array_push($indicators_disabled, "市级企业技术中心认定通知");
    } else {// 评价体系
        array_push($indicators_disabled, "申请报告");
    }
    // 无需输入数据的列
    $indicators_no_input = ["运行评价报告", "市级企业技术中心认定通知", "申请报告", "信用报告证明材料", "高新技术企业（仅上传附件）"];
    foreach ($re_indicators as $f) {
        if (!in_array($f['Field'], $indicators_disabled)) { // 若不是被禁用的列
            $attachment_name = fetch_attachment_name($user, $f['Field']);
//            $attachment_tag = "<a style='color: #0000FF;text-decoration: underline' target='_blank' href='../service/attachment_view.php?path=" . urlencode("../uploads/$user/$attachment_name") . "'>" . substr($attachment_name, strlen("附件_$f[Field]_")) . "</a>";
            if ($attachment_name == "未上传")
                $attachment_tag = "";
            else
                $attachment_tag = "<a style='color: #0000FF;text-decoration: underline' target='_blank' href='../service/attachment_view.php?path=" . urlencode("../uploads/$user/$attachment_name") . "'>" . "点击查看>>>" . "</a>";
            if (in_array($f['Field'], $indicators_no_input)) // 若是无需输入的列
                $value = "---";
            else {
                if ($re_indicator_values == null)
                    $value = null;
                else
                    $value = $re_indicator_values[$i];
            }
            array_push($response["data"], array(
                "indicator" => $f['Field'],
                "value" => $value,
                "unit" => $units[$i],
                "attachment" => $attachment_tag
            ));
        }
        $i++;
    }
}

die(json_encode($response));
