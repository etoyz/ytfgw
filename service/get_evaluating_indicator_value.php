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
    $sql2 = "SELECT * FROM enterprise_data WHERE `loginid` = $user;";
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
    $units = ["万元", "万元", "人", "人", "人", "人月", "个", "个", "个", "项", "项", "个", "个", "万元", "项", "项", "项", "项", "项", "项", "项", "万元", "万元", "万元", "项", "项", "万元", "项", "项", "项", "项"];
    $i = 0;

    // file code
    require_once "define_error_handler_to_catch_warnings.php";
    try {
        $allFile = scandir("../uploads/$user");
    } catch (Exception $e) {
//        var_dump($e);
        $allFile = array();
    }
    function fetch_indicator_attachment_name($indicator)
    {
        foreach (preg_grep("/^附件_" . $indicator . ".*$/", $GLOBALS['allFile']) as $r)
            return $r;
        return "未上传";
    }

    foreach ($re as $f) {
        if ($f['Field'] != "loginid") {
            $attachment_name = fetch_indicator_attachment_name($f['Field']);
            array_push($response["data"], array(
                "indicator" => $f['Field'],
                "value" => $re2[$i],
                "unit" => $units[$i],
                "file" => "<a class='layui-btn layui-btn-xs layui-btn-primary' target='_blank' href='../uploads/$user/$attachment_name'>$attachment_name</a>"
            ));
            $i++;
        }
    }
}

die(json_encode($response));
