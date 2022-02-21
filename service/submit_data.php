<?php
/**
 * 保存评判数据
 */
session_start();
$arr = array(
    "status" => 'notLogin',
    "data" => null
);

$user = null;
$type = 0;
if (isset($_SESSION['usertype'])) {
    $arr["status"] = "isLogin";
    require_once '../include/db.php';
    $db = new DB();

    // 若是企业用户，则只能提交自己的数据，且会进行状态校验
    if ($_SESSION['usertype'] === "enterprise") {
        $user = $_SESSION['loginid'];
        // 检查用户状态
        $status = $_SESSION['status'] % 5;
        if ($status != "1" && $status != "3") {
            if ($status == "0")
                $arr["data"] = "请先完善企业信息！";
            else if ($status == "2")
                $arr["data"] = "您已经提交，请勿重复提交！";
            else if ($status == "4")
                $arr["data"] = "已经通过！";
            die(json_encode($arr));
        }
    } else if ($_SESSION['usertype'] === "manager" && $_SESSION['privilege'] == '0'){ // 如果是超级管理员：可以提交任意用户数据，用于重新判分; 可以提交专家的评分
        $user = $_POST['loginid'];
        $type = $_POST['type'];
    } else {
        $arr['data'] = "无权限进行此操作！";
        die(json_encode($arr));
    }

    // 数据插入数据库
    $sql = "INSERT INTO `enterprise_data`(";
    foreach (array_keys($_POST) as $key) {
        $sql .= "`" . $db->escape($key) . "`, ";
    }
    $sql = substr($sql, 0, -2);
    $sql .= ") VALUES(";
    foreach (array_keys($_POST) as $key) {
        $sql .= "'" . $db->escape($_POST[$key]) . "', ";
    }
    $sql = substr($sql, 0, -2);
    $sql .= ");";
//    var_dump($sql);
    $arr["data"] = $db->query($sql); // 成功返回true，失败返回错误码
    if ($arr["data"] == 1062) { // 如果已经存在，则尝试进行更新
        $sql = "UPDATE `enterprise_data` SET ";
        foreach (array_keys($_POST) as $key) {
            $sql .= "`" . $db->escape($key) . "` = '" . $db->escape($_POST[$key]) . "', ";
        }
        $sql = substr($sql, 0, -2);
        $sql .= " WHERE `loginid` = '" . $db->escape($user) . "' AND `type` = '" . $db->escape($type) . "'";
        $arr["data"] = $db->query($sql); // 成功返回true，失败返回错误码
    }

    if ($arr["data"] === true) {
        // 计算成绩并插入数据库
        cal_score();
        // update info 企业修改才改提交时间
        if ($_SESSION['usertype'] === "enterprise") {
            date_default_timezone_set("PRC");
            $sql = "UPDATE `enterprise` set  `submit_time` = '" . date('Y-m-d H:i:s', time()) . "' WHERE `loginid` = '" . $db->escape($user) . "'";
            $db->query($sql);
        }
    }
}

function cal_score()
{
    // 公式
    $user = $GLOBALS['user'];
    $type = $GLOBALS['type'];
    $metaData = array();
    $metaData["研究人员人均研发经费支出"] = array(
        $_POST["研究与试验发展经费支出"] / $_POST['研究与试验发展人员数'], 8, 20, 40
    );
    $metaData["研发经费支出占主营业务收入的比重"] = array(
        $_POST["研究与试验发展经费支出"] / $_POST['主营业务收入'] * 100, 12, 10, 20
    );
    $metaData["研发人员占职工总数的比重"] = array(
        $_POST["研究与试验发展人员数"] / $_POST['企业职工总数'] * 100, 7, 30, 50
    );
    $metaData["技术中心拥有的高级专家和博士人数"] = array(
        $_POST["技术中心高级专家、博士人数"], 4, 1, 5
    );
    $metaData["来技术中心从事研发工作的外部专家人数"] = array(
        $_POST["来技术中心从事研发工作的外部专家人数"], 4, 5, 10
    );
    $metaData["企业拥有的全部有效发明专利数"] = array(
        $_POST["企业拥有的全部有效发明专利数"], 5, 1, 10
    );
    $metaData["企业全部研发项目数"] = array(
        $_POST["企业全部研发项目数"], 4, 6, 10
    );
    $metaData["基础研究和应用研究项目数占全部研发项目数的比重"] = array(
        $_POST["其中：基础研究和应用研究项目数"] / $_POST['企业全部研发项目数'] * 100, 4, 1, 4
    );
    $metaData["国家、省级研发平台数"] = array(
        $_POST["国家级研发平台数"] + $_POST['省级研发平台数'], 3, 1, 2
    );
    $metaData["市级研发平台数"] = array(
        $_POST["市级研发平台数"], 2, 1, 2
    );
    $metaData["企业技术开发仪器设备原值"] = array(
        $_POST["企业技术开发仪器设备原值"], 4, 500, 1000
    );
    $metaData["通过国家（国际组织）、省认证的实验室和检测机构数"] = array(
        $_POST["通过国家（国际组织）、省认证（认定）的实验室和检测机构数【国家级】"] + $_POST["通过国家（国际组织）、省认证（认定）的实验室和检测机构数【省级】"], 3, 1, 1
    );
    $metaData["当年被受理的专利申请数"] = array(
        $_POST["当年被受理的专利申请数"], 5, 4, 10
    );
    $metaData["当年被受理的发明专利申请数"] = array(
        $_POST["其中：当年被受理的发明专利申请数"], 6, 1, 5
    );
    $metaData["最近三年主持和参加制定的国际、国家、行业和地方标准数"] = array(
        $_POST["最近三年主持和参加指定的国际、国家和行业、企业标准数【国际】"] + $_POST["最近三年主持和参加指定的国际、国家和行业、企业标准数【国家】"] + $_POST["最近三年主持和参加指定的国际、国家和行业、企业标准数【行业】"] + $_POST["最近三年主持和参加指定的国际、国家和行业、企业标准数【企业】"], 4, 1, 3
    );
    $metaData["新产品销售收入占主营业务收入的比重"] = array(
        $_POST["新产品销售收入"] / $_POST['主营业务收入'] * 100, 10, 15, 20
    );
    $metaData["新产品销售利润占利润总额的比重"] = array(
        $_POST["新产品销售利润"] / $_POST['利润总额'] * 100, 10, 10, 15
    );
    $metaData["利润率"] = array(
        $_POST["利润总额"] / $_POST['主营业务收入'] * 100, 5, 5, 12
    );
    $metaData["获省级自然科学、技术发明、科技进步奖项目数"] = array(
        $_POST["获省级以上自然科学、技术发明、科技进步奖项目数【国家级】"] + $_POST["获省级以上自然科学、技术发明、科技进步奖项目数【省级】"], 4, 1, 2
    );
    $metaData["企业享受加计扣除政策额度"] = array(
        $_POST["近两年企业享受加计扣除政策额度"], 3, 0, "0"
    );
    $metaData["承担的省级以上项目数量"] = array(
        $_POST["承担的省级以上项目数量【国家级】"] + $_POST["承担的省级以上项目数量【省级】"], 4, 1, 2
    );
    $metaData["获省级协会以上成果奖励项目数量"] = array( //？？
        $_POST["企业获得省级以上质量标杆，品牌培育示范、试点企业项目数量【国家级】"] + $_POST["企业获得省级以上质量标杆，品牌培育示范、试点企业项目数量【省级】"], 4, 1, 2
    );
    $metaData["营业收入"] = array( //？？
        $_POST["主营业务收入"], 5, 5000, 20000
    );
    //  指标数值0    权重1  基本要求2    满分要求3
    $score_cnt = 0;
    foreach ($metaData as &$data) { // 成绩存储在data[4]
        if ($data[0] >= $data[3])
            array_push($data, $data[1]);
        else if ($data[0] == $data[2])
            array_push($data, $data[1] * 0.6);
        else if ($data[0] <= 0)
            array_push($data, 0);
        else if ($data[0] > 0 && $data[0] < $data[2])
            array_push($data, $data[0] / $data[2] * $data[1] * 0.6);
        else if ($data[0] > $data[2] && $data[0] < $data[3])
            array_push($data, ($data[0] - $data[2]) / ($data[3] - $data[2]) * $data[1] * 0.4 + $data[1] * 0.6);
//        var_dump("满分", $data[3], "基本", $data[2], "数值", $data[0], "权重", $data[1], "得分", $data[4]);
        $score_cnt += $data[4];
    }

    $sql = "INSERT INTO `enterprise_score`(";
    foreach (array_keys($metaData) as $key) {
        $sql .= "`$key`, ";
    }
    $sql .= "`loginid`, `type`, `得分汇总`) VALUES(";
    foreach (array_keys($metaData) as $key) {
        $sql .= "'" . $metaData[$key][4] . "', ";
    }
    $sql .= "'$user', $type, '$score_cnt');";
    if ($GLOBALS['db']->query($sql) == 1062) { // 如果已经存在尝试进行更新
        $sql = "UPDATE `enterprise_score` SET ";
        foreach (array_keys($metaData) as $key) {
            $sql .= "`$key` = '" . $metaData[$key][4] . "', ";
        }
        $sql .= "`type` = $type, `得分汇总` = '$score_cnt' WHERE `loginid` = '$user' AND `type` = '$type'";
        $GLOBALS['db']->query($sql);
//        var_dump($sql);
        $sql1 = "UPDATE `enterprise` set `expert_score` = '" . number_format($score_cnt, 2) . "' WHERE `loginid` = '$user'";
        $GLOBALS['db']->query($sql1);
    }
}

die(json_encode($arr));
