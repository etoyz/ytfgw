<?php
/**
 * 获取评价指标与对应的值
 */
session_start();

require "../include/common.php";
require "../include/verify_login.php";

$response = array(
    "code" => 1,
    "msg" => "",
    "data" => []
);

if (is_enterprise()) { // 若是企业用户，则只能查询自己的
    $user = $_SESSION['loginid'];
    $type = 0; // 只能查询自己提交的数据
} else { // 若是管理员用户，则可以查询任何人的
    $user = $_GET['user'];
    $type = $_GET['type']; // 能查询专家核定的数据和自己提交的数据
}

$db = new DB();
$user = $db->escape($user);
$type = $db->escape($type); // type为0表示企业提报的数据，为1表示专家核定的数据

// patch
// 不是超管，也不是专家，则不可查看专家核定数据
if ($type == 1 && !has_permission_admin_super() && !has_permission_admin_expert()) {
    die(json_encode(array(
        "code" => 1,
        "msg" => get_string("PERMISSION_DENY"),
    )));
}

$sql_fetch_indicators = "DESCRIBE enterprise_data;"; // 表“enterprise_data”的各列名为评价指标名
$re_indicators = $db->query($sql_fetch_indicators)->fetch_all(MYSQLI_ASSOC);
$sql_fetch_values = "SELECT * FROM enterprise_data WHERE `loginid` = '$user' AND `type` = '$type';";
$re_values = $db->query($sql_fetch_values);
if ($type == 1 && $re_values->num_rows == 0) // 若查询专家核定数据，而专家还未操作过，则返回企业原数据
{
    $sql_fetch_values = "SELECT * FROM enterprise_data WHERE `loginid` = '$user' AND `type` = '0';";
    $re_values = $db->query($sql_fetch_values);
}
$re_values = mysqli_fetch_array($re_values);
//    var_dump($re_indicator_values);
// 各评价指标的单位
$units = ["loginid", "type", "万元", "万元", "人", "人", "人", "人月", "个", "个", "个", "项", "项", "个", "个", "万元", "项", "项", "项", "项", "项", "项", "项", "万元", "万元", "万元", "项", "项", "万元", "项", "项", "项", "项", "---", "---", "---", "---", "---"];
$i = 0;

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
            if ($re_values == null)
                $value = null;
            else
                $value = $re_values[$i];
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

$response['code'] = 0;

die(json_encode($response));
