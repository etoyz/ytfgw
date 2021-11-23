<?php
session_start();
$response = array(
    "status" => 'notLogin',
    "data" => null,
    "code" => 1,
    "msg" => "无权限查看！"
);

if (isset($_SESSION['usertype']) && $_SESSION['usertype'] === "manager" && $_SESSION['privilege'] == "0") { // 已经登录且是管理员
    $user = $_GET['user'];

    require_once 'db.php';
    $db = new DB();

    $sql = "DESCRIBE enterprise_score;";
    $sql2 = "SELECT * FROM enterprise_score WHERE `loginid` = '" . $db->escape($user) . "' AND `type` = '$_GET[type]';";
    $re = $db->query($sql);
    $re2 = $db->query($sql2);
    if ($re2->num_rows == 0 && $_GET['type'] == "expert") { // 如果专家未打分，则使用机器数据
        $sql2 = "SELECT * FROM enterprise_score WHERE `loginid` = '" . $db->escape($user) . "' AND `type` = 'machine';";
        $re2 = $db->query($sql2);
    }
    $response["status"] = "isLogin";
    $response["data"] = [];
    $response["code"] = 0;
    $re = $re->fetch_all(MYSQLI_ASSOC);

    $re2 = mysqli_fetch_array($re2);
    $i = 0;
    $score_cnt = 0;
    foreach ($re as $f) {
        if ($f['Field'] != "loginid" && $f['Field'] != "type") {
            if ($f['Field'] == '得分汇总' && $re2[$i] == 0) { // 异常情况 TODO 大坑
                $sql = "UPDATE `enterprise_score` SET `得分汇总` = '$score_cnt'  WHERE `loginid` = '" . $db->escape($user) . "' AND `type` = 'machine'";
                $db->query($sql);
                array_push($response["data"], array(
                    "indicator" => $f['Field'],
                    "score" => number_format($score_cnt, 2)
                ));
                continue;
                //                var_dump($sql);
                //                var_dump($db->query($sql));
                //                var_dump($score_cnt);
            }

            array_push($response["data"], array(
                "indicator" => $f['Field'],
                "score" => number_format($re2[$i], 2)
            ));
            $score_cnt += $re2[$i];
        }
        $i++;
    }
//    array_push($response["data"], array(
//        "indicator" => "得分汇总",
//        "score" => number_format($score_cnt, 2)
//    ));
}

die(json_encode($response));
