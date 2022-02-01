<?php
/**
 * 获取指标成绩
 */
session_start();
$response = array(
    "status" => 'notLogin',
    "data" => null,
    "code" => 1,
    "msg" => "无权限查看！"
);

if (isset($_SESSION['usertype']) && $_SESSION['usertype'] === "manager" && $_SESSION['privilege'] == "0") { // 已经登录且是管理员
    $response["status"] = "isLogin";
    $response["data"] = [];
    $response["code"] = 0;

    $user = $_GET['user'];

    require_once '../include/db.php';
    $db = new DB();

    $sql = "DESCRIBE enterprise_score;";
    $sql2 = "SELECT * FROM enterprise_score WHERE `loginid` = '" . $db->escape($user) . "' AND `type` = '0';";
    $sql3 = "SELECT * FROM enterprise_score WHERE `loginid` = '" . $db->escape($user) . "' AND `type` = '1';";
    $re = $db->query($sql);
    $re2 = $db->query($sql2);
    $re3 = $db->query($sql3);

    $re = $re->fetch_all(MYSQLI_ASSOC);

    $re2 = mysqli_fetch_array($re2);
    $re3 = mysqli_fetch_array($re3);
    $i = 0;
    foreach ($re as $f) {
        if ($f['Field'] != "loginid" && $f['Field'] != "type") {
            array_push($response["data"], array(
                "indicator" => $f['Field'],
                "score_0" => number_format($re2[$i], 2),
                "score_1" => number_format($re3[$i], 2)
            ));
        }
        $i++;
    }
}

die(json_encode($response));
