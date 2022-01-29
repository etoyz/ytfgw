<?php
session_start();
$response = array(
    "status" => 'notLogin',
    "data" => null
);

if (isset($_SESSION['usertype'])) { // 已经登录
    require_once '../include/db.php';
    $sql = "DESCRIBE enterprise_data;";
    $db = new DB();
    $re = $db->query($sql);
    $response["status"] = "isLogin";
    $response["data"] = [];
    $response["code"] = 0;
    $re = $re->fetch_all(MYSQLI_ASSOC);
    $units = ["loginid", "万元", "万元", "人", "人", "人", "人月", "个", "个", "个", "项", "项", "个", "个", "万元", "项", "项", "项", "项", "项", "项", "项", "万元", "万元", "万元", "项", "项", "万元", "项", "项", "项", "项", "---", "---", "---", "---", "---"];
    $i = 0;
    foreach ($re as $f) {
        if ($f['Field'] != "loginid") {
            if ($f['Field'] == "信用报告证明材料") {
                array_push($response["data"], array(
                    "indicator" => $f['Field'],
                    "unit" => "---",
                    "value" => "---"
                ));
                continue;
            }
            array_push($response["data"], array(
                "indicator" => $f['Field'],
                "unit" => $units[$i]
            ));
        }
        $i++;
    }
}

die(json_encode($response));
