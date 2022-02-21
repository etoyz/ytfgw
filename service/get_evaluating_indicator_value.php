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
    if ($_SESSION['usertype'] === "enterprise") {
        $user = $_SESSION['loginid'];
    } else {
        $user = $_GET['user'];
    }
    require_once '../include/db.php';
    $db = new DB();
    $user = $db->escape($user);
    $type = $db->escape($_GET['type']);

    // patch
    if ($type == 1 && $_SESSION['privilege'] !== '0') { // 超级管理员用户才能看核定数据
        die(json_encode($response));
    }

    $sql = "DESCRIBE enterprise_data;";
    $sql2 = "SELECT * FROM enterprise_data WHERE `loginid` = '$user' AND `type` = '$type';";
    $re_indicators = $db->query($sql);
    $re_indicator_values = $db->query($sql2);
    $re_indicators = $re_indicators->fetch_all(MYSQLI_ASSOC);
//    var_dump($re2);
//    var_dump($sql2);
    if ($re_indicator_values->num_rows == 0 && $type == 1) // 若查询专家核定数据，而专家还未操作过，则返回企业原数据
    {
        $sql2 = "SELECT * FROM enterprise_data WHERE `loginid` = '$user' AND `type` = '0';";
        $re_indicator_values = $db->query($sql2);
    }
//    var_dump($re_indicator_values);
    $re_indicator_values = mysqli_fetch_array($re_indicator_values);
//    var_dump($re_indicator_values);
    $units = ["loginid", "type", "万元", "万元", "人", "人", "人", "人月", "个", "个", "个", "项", "项", "个", "个", "万元", "项", "项", "项", "项", "项", "项", "项", "万元", "万元", "万元", "项", "项", "万元", "项", "项", "项", "项", "---", "---", "---", "---", "---"];
    $i = 0;

    // file code
    require_once "../include/define_error_handler_to_catch_warnings.php";
    try {
        $allFile = scandir("../uploads/$user");
    } catch (Exception $e) {
//        var_dump($e);
        $allFile = array();
    }
    require_once "../include/common.php";
//    var_dump($re);
//    var_dump($re2);
    $disable_indicators = ["loginid", "type"];
    if (get_user_status($user) <= 4) {// 申报体系
        array_push($disable_indicators, "运行评价报告");
        array_push($disable_indicators, "市级企业技术中心认定通知");
    } else {// 评价体系
        array_push($disable_indicators, "申请报告");
    }
    $no_input_indicators = ["运行评价报告", "市级企业技术中心认定通知", "申请报告", "信用报告证明材料", "高新技术企业（仅上传附件）"];
    foreach ($re_indicators as $f) {
        if (!in_array($f['Field'], $disable_indicators)) {
            $attachment_name = fetch_indicator_attachment_name($f['Field'], $GLOBALS['allFile']);
//            $attachment_tag = "<a style='color: #0000FF;text-decoration: underline' target='_blank' href='../service/attachment_view.php?path=" . urlencode("../uploads/$user/$attachment_name") . "'>" . substr($attachment_name, strlen("附件_$f[Field]_")) . "</a>";
            if ($attachment_name == "未上传")
                $attachment_tag = "";
            else
                $attachment_tag = "<a style='color: #0000FF;text-decoration: underline' target='_blank' href='../service/attachment_view.php?path=" . urlencode("../uploads/$user/$attachment_name") . "'>" . "点击查看>>>" . "</a>";
            if (in_array($f['Field'], $no_input_indicators))
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
