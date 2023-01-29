<?php
/**
 * 获取指标成绩
 */
session_start();

require "../include/common.php";
require "../include/verify_login.php";

if (has_permission_admin_super() | has_permission_admin_expert()) { // 超管和专家才可操作
    $response = array(
        "data" => [],
        "code" => 0,
    );

    $user = $_GET['user'];
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
            // 修正，空值置为----
            if ($re2[$i] === null)
                $response["data"][sizeof($response["data"]) - 1]["score_0"] = "----";
            if ($re3[$i] === null)
                $response["data"][sizeof($response["data"]) - 1]["score_1"] = "----";
        }
        $i++;
    }
    die(json_encode($response));
} else
    die(json_encode(array(
        "code" => 1,
        "msg" => get_string("PERMISSION_DENY"),
        "count" => 0,
        "data" => []
    )));
