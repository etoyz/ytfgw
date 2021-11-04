<?php
session_start();
$response = array(
    "status" => 'notLogin',
    "data" => null
);

if (isset($_SESSION['usertype'])) { // 已经登录
    $user = null;
    if ($_SESSION['usertype'] === "enterprise") {
        $user = $_SESSION['loginid'];
    } else {
        $user = $_GET['user'];
    }

    require_once 'db.php';
    $sql = "DESCRIBE enterprise_data;";
    $sql2 = "SELECT * FROM enterprise_data WHERE `loginid` = '$user';";
    $db = new DB();
    $re = $db->query($sql);
    $re2 = $db->query($sql2);
    $response["status"] = "isLogin";
    $response["data"] = [];
    $response["code"] = 0;
    $re = $re->fetch_all(MYSQLI_ASSOC);
//    var_dump($re2);
//    var_dump($sql2);
    $re2 = mysqli_fetch_array($re2);
    $units = ["loginid", "万元", "万元", "人", "人", "人", "人月", "个", "个", "个", "项", "项", "个", "个", "万元", "项", "项", "项", "项", "项", "项", "项", "万元", "万元", "万元", "项", "项", "万元", "项", "项", "项", "项", "---"];
    $i = 0;

    // file code
    require_once "define_error_handler_to_catch_warnings.php";
    try {
        $allFile = scandir("../uploads/$user");
    } catch (Exception $e) {
//        var_dump($e);
        $allFile = array();
    }
    require_once "common.php";
//    var_dump($re);
//    var_dump($re2);
    foreach ($re as $f) {
        if ($f['Field'] != "loginid") {
            $attachment_name = fetch_indicator_attachment_name($f['Field'], $GLOBALS['allFile']);
            $attachment_tag = "<a style='color: #0000FF;text-decoration: underline' target='_blank' href='../uploads/$user/$attachment_name'>" . substr($attachment_name, strlen("附件_$f[Field]_")) . "</a>";
            if ($f['Field'] == "信用报告证明材料") {
                array_push($response["data"], array(
                    "indicator" => $f['Field'],
                    "value" => "---",
                    "unit" => "---",
                    "attachment" => $attachment_tag
                ));
                continue;
            }
            if ($re2 == null)
                $value = "";
            else
                $value = $re2[$i];
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
