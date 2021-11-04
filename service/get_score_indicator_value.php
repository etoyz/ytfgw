<?php
session_start();
$response = array(
    "status" => 'notLogin',
    "data" => null,
    "code" => 0
);

if (isset($_SESSION['usertype'])) { // 已经登录
    $user = null;
    if ($_SESSION['usertype'] === "enterprise") {
        $user = $_SESSION['loginid'];
    } else {
        $user = $_GET['user'];
    }

    require_once 'db.php';
    $db = new DB();

    $sql = "DESCRIBE enterprise_score;";
    $sql2 = "SELECT * FROM enterprise_score WHERE `loginid` = '" . $db->escape($user) . "' AND `type` = 'machine';";
    $re = $db->query($sql);
    $re2 = $db->query($sql2);
    $response["status"] = "isLogin";
    $response["data"] = [];
    $response["code"] = 0;
    $re = $re->fetch_all(MYSQLI_ASSOC);

    $re2 = mysqli_fetch_array($re2);
    $i = 0;
    $score_cnt = 0;
    foreach ($re as $f) {
        if ($f['Field'] != "loginid" && $f['Field'] != "type") {
            array_push($response["data"], array(
                "indicator" => $f['Field'],
                "score" => number_format($re2[$i], 2)
            ));
            $score_cnt += $re2[$i];
        }
        $i++;
    }
    array_push($response["data"], array(
        "indicator" => "得分汇总",
        "score" => number_format($score_cnt, 2)
    ));
}

die(json_encode($response));
