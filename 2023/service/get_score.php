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
    $sql_fetch_indicators = "DESCRIBE enterprise_score;";
    $sql_fetch_values0 = "SELECT * FROM enterprise_score WHERE `loginid` = '" . $db->escape($user) . "' AND `type` = '0';";
    $sql_fetch_values1 = "SELECT * FROM enterprise_score WHERE `loginid` = '" . $db->escape($user) . "' AND `type` = '1';";
    $re_indicators = $db->query($sql_fetch_indicators);
    $re_values0 = $db->query($sql_fetch_values0);
    $re_values1 = $db->query($sql_fetch_values1);

    $re_indicators = $re_indicators->fetch_all(MYSQLI_ASSOC);
    $re_values0 = mysqli_fetch_array($re_values0);
    $re_values1 = mysqli_fetch_array($re_values1);

    $indicators_disabled = ["loginid", "type"]; // 被禁用的列
    $indicators_highlight1 = ["定量得分", "定性得分"]; // 特殊显示的列 level1
    $indicators_highlight2 = ["总得分"]; // 特殊显示的列 level2

    $i = -1;
    foreach ($re_indicators as $f) { // 遍历各个指标
        $i++;
        $indicator = $f['Field']; // 指标名
        if (in_array($indicator, $indicators_disabled)) // 若是被禁用的列
            continue;

        // 得分
        if ($re_values0[$i] === null)
            $score_0 = "----";
        else
            $score_0 = number_format($re_values0[$i], 2);
        if ($re_values1[$i] === null)
            $score_1 = "----";
        else
            $score_1 = number_format($re_values1[$i], 2);

        if (in_array($indicator, $indicators_highlight1)) { // 若是特殊显示的列 1
            $indicator = "<span style='color: red;font-size: medium;'>$indicator</span>";
            $score_0 = "<span style='color: red;font-size: medium;'>$score_0</span>";
            $score_1 = "<span style='color: red;font-size: medium;'>$score_1</span>";
        }
        if (in_array($indicator, $indicators_highlight2)) { // 若是特殊显示的列 2
            $indicator = "<span style='color: red;font-size: medium;font-weight: bold'>$indicator</span>";
            $score_0 = "<span style='color: red;font-size: medium;font-weight: bold'>$score_0</span>";
            $score_1 = "<span style='color: red;font-size: medium;font-weight: bold'>$score_1</span>";
        }

        array_push($response["data"], array(
            "indicator" => $indicator,
            "score_0" => $score_0,
            "score_1" => $score_1
        ));
    }
    die(json_encode($response));
} else
    die(json_encode(array(
        "code" => 1,
        "msg" => get_string("PERMISSION_DENY"),
        "count" => 0,
        "data" => []
    )));
