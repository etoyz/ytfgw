<?php
session_start();
$arr = array(
    "status" => 'notLogin',
    "data" => null
);
if (isset($_SESSION['usertype']) && $_SESSION['usertype'] === "enterprise") {
    $arr["status"] = "isLogin";

    if ($_SESSION['status'] != "1") {
        if ($_SESSION['status'] == "0")
            $arr["data"] = "请先完善企业信息！";
        else if ($_SESSION['status'] == "2")
            $arr["data"] = "您已经提交，请勿重复提交！";
        die(json_encode($arr));
    }

    $sql = "INSERT INTO `enterprise_data`(";
    foreach (array_keys($_POST) as $key) {
        $sql .= "`$key`, ";
    }
    $sql .= "`loginid`) VALUES(";
    foreach (array_keys($_POST) as $key) {
        $sql .= "'$_POST[$key]', ";
    }
    //$sql = substr($sql, 0, strlen($sql) - 2);
    $sql .= "'$_SESSION[loginid]');";

    require_once 'db.php';
    $db = new DB();
    $arr["data"] = $db->query($sql); // 成功返回true，失败返回错误码

    // update status
    if ($arr["data"] == true) {
        $sql = "UPDATE `enterprise` set `status` = 2, `submit_time` = '" . date('Y-m-d H:i:s',time()) . "' WHERE `loginid` = '$_SESSION[loginid]'";
        $db->query($sql);
        $_SESSION['status'] = 2;
    }
}
die(json_encode($arr));
