<?php
/**
 * 保存评判数据
 */
session_start();
error_reporting(0);
ini_set('display_errors', 0);
require "../include/common.php";
require "../include/verify_login.php";

$arr = array(
    "code" => 1,
    "msg" => "",
);

$db = new DB();

if (is_enterprise()) { // 若是企业用户，则只能提交自己的数据，且会进行状态校验
    $loginid = $_SESSION['loginid'];
    $type = 0; // 企业提报的数据
    // 检查用户状态
    $status = get_user_status($_SESSION['loginid']) % 5;
    if ($status != "1" && $status != "3") {
        if ($status == "0")
            $arr["msg"] = "请先完善企业信息！";
        else if ($status == "2")
            $arr["msg"] = "您已经提交，请勿重复提交！";
        else if ($status == "4")
            $arr["msg"] = "已经通过！";
        die(json_encode($arr));
    }
} else if (has_permission_admin_super() | has_permission_admin_expert()) {
    // 若是管理员用户,// 超管可以提交任意用户数据，用于重新判分; 专家也可以提交
    $loginid = $_POST['loginid'];
    $type = 1; // 专家核定的数据
} else {
    die(json_encode(array(
        "code" => 1,
        "msg" => get_string("PERMISSION_DENY"),
        "count" => 0,
        "data" => []
    )));
}

// 要插入到数据库的数据，暂存到$_POST里
$_POST['loginid'] = $loginid;
$_POST['type'] = $type;


// 数据插入数据库
$sql = "INSERT INTO `enterprise_data`(";
foreach (array_keys($_POST) as $key) {
    $sql .= "`" . $db->escape($key) . "`, ";
}
$sql = substr($sql, 0, -2);
$sql .= ") VALUES(";
foreach (array_keys($_POST) as $key) {
//    echo $db->escape($_POST[$key]) . " ";
    if($db->escape($_POST[$key]))
        $sql .= "'" . $db->escape($_POST[$key]) . "', ";
    else
        $sql .= "'" . 0 . "', ";
}
$sql = substr($sql, 0, -2);
$sql .= ");";
$re = $db->query($sql); // 成功返回true，失败返回错误码
//var_dump($re);
if ($re === true)
    $arr['code'] = 0;
else if ($re == 1062) { // 如果已经存在，则尝试进行更新
    $sql2 = "UPDATE `enterprise_data` SET ";
    foreach (array_keys($_POST) as $key) {
        $sql2 .= "`" . $db->escape($key) . "` = '" . $db->escape($_POST[$key]) . "', ";
    }
    $sql2 = substr($sql2, 0, -2);
    $sql2 .= " WHERE `loginid` = '" . $db->escape($loginid) . "' AND `type` = '" . $db->escape($type) . "'";
    $re2 = $db->query($sql2); // 成功返回true，失败返回错误码
//    var_dump($re2);
    if ($re2 === true){
        $arr['code'] = 0;
    }

    else
        $arr['msg'] = "1062 " . $re2;
} else
    $arr['msg'] = $re;


if ($arr["code"] === 0) { // 若成功保存提交的数据
    // 计算成绩并插入数据库
    cal_score($loginid, $type, $db);
    // update info
    if (is_enterprise()) { //企业修改才改提交时间
        date_default_timezone_set("PRC");
        $sql = "UPDATE `enterprise` set  `submit_time` = '" . date('Y-m-d H:i:s', time()) . "' WHERE `loginid` = '" . $db->escape($loginid) . "'";
        $db->query($sql);
    }
    $arr['msg'] = "成功提交数据";
    die(json_encode($arr));
}

// 计算成绩并插入数据库
function cal_score($loginid, $type, $db)
{
    foreach (array_keys($_POST) as $key) {
        if (!$db->escape($_POST[$key])) {
            $_POST[$key] = '';
        }
    }

    // 公式
    $metaData = array();
    $metaData["研究与试验发展人员人均研发经费支出"] = array(
        $_POST["研究与试验发展经费支出"] / $_POST['研究与试验发展人员数'], 5, 20, 40
    );
    $metaData["研究与试验发展经费支出占主营业务收入的比重"] = array(
        $_POST["研究与试验发展经费支出"] / $_POST['主营业务收入'] * 100, 8, 10, 20
    );
    $metaData["享受加计扣除政策额度"] = array(
        $_POST["享受加计扣除政策额度"], 1, 0, 0
    );
    $metaData["研究与试验发展人员数"] = array(
        $_POST["享受加计扣除政策额度"], 3, 30, 50
    );
    $metaData["研发人员占企业职工总数的比重"] = array(
        $_POST["研究与试验发展人员数"] / $_POST["企业职工总数"] * 100, 3, 3, 20
    );
    $metaData["中高级职称人员占研发人员比重"] = array(
        $_POST["技术中心中高级职称人数"] / $_POST["研究与试验发展人员数"] * 100, 1, 10, 20
    );
    $metaData["技术中心高级专家、博士人数"] = array(
        $_POST["技术中心高级专家、博士人数"], 3, 1, 5
    );
    $metaData["来技术中心从事研发工作的外部专家"] = array(
        $_POST["来技术中心从事研发工作的外部专家"], 3, 5, 10
    );
    $metaData["企业拥有的全部有效发明专利数"] = array(
        $_POST["企业拥有的全部有效发明专利数"], 4, 3, 10
    );
    $metaData["企业全部研发项目数"] = array(
        $_POST["企业全部研发项目数"], 3, 6, 30
    );
    $metaData["基础研究和应用研究项目数"] = array(
        $_POST["其中：基础研究和应用研究项目数"], 2, 1, 3
    );
    $metaData["产学研合作项目数量"] = array(
        $_POST["产学研合作项目数量"], 1, 2, 6
    );
    $metaData["国家、省级研发平台数"] = array(
        $_POST["国家级研发平台数"] + $_POST["省级研发平台数"], 3, 1, 2
    );
    $metaData["市级研发平台数"] = array(
        $_POST["市级研发平台数"], 2, 1, 2
    );
    $metaData["技术开发仪器设备原值"] = array(
        $_POST["技术开发仪器设备原值"], 4, 500, 1000
    );
    $metaData["通过国家、省级认证（认定）的实验室和检测机构数"] = array(
        $_POST["通过国家认证（认定）的实验室和检测机构数"] + $_POST["通过省级认证（认定）的实验室和检测机构数"], 2, 1, 2
    );
    $metaData["通过市级认证（认定）的实验室和检测机构数"] = array(
        $_POST["通过市级认证（认定）的实验室和检测机构数"], 1, 1, 2
    );
    $metaData["近三年被受理的专利申请数"] = array(
        $_POST["近三年被受理的专利申请数"], 3, 8, 24
    );
    $metaData["近三年被受理的发明专利申请数"] = array(
        $_POST["其中：近三年被受理的发明专利申请数"], 4, 3, 9
    );
    $metaData["近三年主持或参加制定的国际、国家、行业标准数"] = array(
        $_POST["最近三年制定的国际标准数"] + $_POST["最近三年制定的国家标准数"] + $_POST["最近三年制定的行业标准数"], 2, 1, 3
    );
    $metaData["最近三年制定的企业标准数"] = array(
        $_POST["最近三年制定的企业标准数"], 1, 2, 6
    );
    $metaData["主营业务收入"] = array(
        $_POST["主营业务收入"], 4, 10000, 20000
    );
    $metaData["新产品销售收入占主营业务收入的比重"] = array(
        $_POST["新产品销售收入"] / $_POST["主营业务收入"] * 100, 6, 15, 20
    );
    $metaData["新产品销售利润占利润总额的比重"] = array(
        $_POST["新产品销售利润"] / $_POST["利润总额"] * 100, 6, 10, 15
    );
    $metaData["利润率"] = array(
        $_POST["利润总额"] / $_POST["主营业务收入"] * 100, 4, 5, 12
    );
    $metaData["获市级以上自然科学、技术发明、科技进步奖项目数"] = array(
        $_POST["获市级以上自然科学、技术发明、科技进步奖项目数"], 1, 1, 2
    );
    $metaData["获市级以上政府质量奖、商标和名牌数"] = array(
        $_POST["获市级以上政府质量奖、商标和名牌数"], 1, 1, 2
    );
    $metaData["是否列入《山东省新型基础设施网建设行动计划》"] = array(
        $_POST["是否列入《山东省新型基础设施网建设行动计划》（是填1，否填0）"], 2, 0, 1
    );
    $metaData["是否列入《烟台市新型基础设施网建设行动计划》"] = array(
        $_POST["是否列入《烟台市新型基础设施网建设行动计划》（是填1，否填0）"], 1, 0, 1
    );

    //  指标数值0    权重1  基本要求2    满分要求3  分数4
    $score_cnt = 0;
    foreach ($metaData as &$data) { // 成绩存储在data[4]
        $weight = $data[1]; //权重
        $n_basic = $data[2]; //基本要求
        $n_full = $data[3]; //满分要求
        $n = $data[0]; //指标数值
        if ($n >= $n_full) // 满分
            array_push($data, $weight);
        else if ($n <= 0) // 0分
            array_push($data, 0);
        else if ($n > 0 && $n <= $n_basic)
            array_push($data, $n / $n_basic * $weight * 0.6);
        else if ($n > $n_basic && $n < $n_full)
            array_push($data, ($n - $n_basic) / ($n_full - $n_basic) * $weight * 0.4 + $weight * 0.6);
        $score_cnt += $data[4];
    }
    $sql = "INSERT INTO `enterprise_score`(";
    foreach (array_keys($metaData) as $key) {
        $sql .= "`$key`, ";
    }
    $sql .= "`loginid`, `type`, `定量得分`) VALUES(";
    foreach (array_keys($metaData) as $key) {
        if(!$metaData[$key][4])
            $sql .= "'" . 0 . "', ";
        else
            $sql .= "'" . $metaData[$key][4] . "', ";
    }
    $sql .= "'$loginid', $type, '$score_cnt');";
    $re = $db->query($sql);
//    var_dump($db->get_error());
//    var_dump($sql);
    if ($re === 1062) { // 如果已经存在尝试进行更新
        $sql2 = "UPDATE `enterprise_score` SET ";
        foreach (array_keys($metaData) as $key) {
            if(!$metaData[$key][4])
                $sql2 .= "`$key` = '" . 0 . "', ";
            else
                $sql2 .= "`$key` = '" . $metaData[$key][4] . "', ";
        }
        $sql2 .= "`type` = $type, `定量得分` = '$score_cnt' WHERE `loginid` = '$loginid' AND `type` = '$type'";
        $db->query($sql2);
    }
    if ($type === 1) { // 专家核定的数据
        $sql_update = "UPDATE `enterprise` set `expert_score` = '" . number_format($score_cnt, 2) . "' WHERE `loginid` = '$loginid'";
        $db->query($sql_update);
    }
}
