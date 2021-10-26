<?php
session_start();
$response = array(
    "status" => 'notLogin',
    "data" => null
);

if (isset($_SESSION['usertype'])) { // 已经登录
    $response["status"] = "isLogin";
    require_once 'db.php';
    $sql = null;
    if ($_SESSION['usertype'] == "manager") {
        $query = $_GET['query'];
        $from = ($_GET['page'] - 1) * $_GET['limit'];
        $to = $_GET['page'] * $_GET['limit'];
        if ($_GET['status'] === '0')
            $sql = "SELECT * FROM `enterprise` WHERE `status` IS NULL LIMIT " . $from . "," . $to . ";";
        else if ($_GET['status'] === '1')
            $sql = "SELECT * FROM `enterprise` WHERE `status` = '已申报' LIMIT " . $from . "," . $to . ";";
        else if ($_GET['status'] === '-1')
            $sql = "SELECT * FROM `enterprise` LIMIT " . $from . "," . $to . ";";
        $db = new DB();
        $re = $db->query($sql);
        //echo $re;
        $response["data"] = $re->fetch_all(MYSQLI_ASSOC);
        $response["code"] = 0;
    } else if ($_SESSION['usertype'] == "enterprise") {
        if ($_GET['status'] === '0')
            $sql = "SELECT * FROM `enterprise` WHERE `loginid` = " . $_SESSION['loginid'] . "
                    WHERE `status` = NULL;";
        else if ($_GET['status'] === '1')
            $sql = "SELECT * FROM `enterprise` WHERE `loginid` = " . $_SESSION['loginid'] . "
                    WHERE `status` = '已申报';";
        else if ($_GET['status'] === '-1')
            $sql = "SELECT * FROM `enterprise` WHERE `loginid` = " . $_SESSION['loginid'] . ";";
        $db = new DB();
        $re = $db->query($sql);
        $response["data"] = $re->fetch_all(MYSQLI_ASSOC);
        $response["code"] = 0;
    }
}
//var_dump($sql);
die(json_encode($response));