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
    $sql = "DESCRIBE enterprise_score;";
    $sql2 = "SELECT * FROM enterprise_score WHERE `loginid` = '" . $user . "' AND `type` = 'machine';";
    //$sql2 = "SELECT * FROM enterprise_score WHERE `loginid` = '" . mysqli_real_escape_string($user) . "' AND `type` = 'machine';";
    $db = new DB();
    $re = $db->query($sql);
    $re2 = $db->query($sql2);
    $response["status"] = "isLogin";
    $response["data"] = [];
    $response["code"] = 0;
    $re = $re->fetch_all(MYSQLI_ASSOC);

    $re2 = mysqli_fetch_array($re2);
    $i = 0;
    foreach ($re as $f) {
        if ($f['Field'] != "loginid" && $f['Field'] != "type")
            array_push($response["data"], array(
                "indicator" => $f['Field'],
                "score" => $re2[$i]
            ));
        $i++;
    }
}

die(json_encode($response));
