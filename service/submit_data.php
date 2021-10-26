<?php
session_start();
$arr = array(
    "status" => 'notLogin',
    "data" => null
);
if (isset($_SESSION['usertype']) && $_SESSION['usertype'] === "enterprise") {
    $arr["status"] = "isLogin";

    // 检查用户状态
    if ($_SESSION['status'] != "1") {
        if ($_SESSION['status'] == "0")
            $arr["data"] = "请先完善企业信息！";
        else if ($_SESSION['status'] == "2")
            $arr["data"] = "您已经提交，请勿重复提交！";
        die(json_encode($arr));
    }

    // 公式
    $metaData = array();
    $metaData["研究人员人均研发经费支出"] = array(
        $_POST["研究与试验发展经费支出"] / $_POST['研究与试验发展人员数'], 8, 20, 40
    );
    $metaData["研发经费支出占主营业务收入的比重"] = array(
        $_POST["研究与试验发展经费支出"] / $_POST['主营业务收入'], 12, 10, 20
    );
    $metaData["研发人员占职工总数的比重"] = array(
        $_POST["研究与试验发展人员数"] / $_POST['企业职工总数'], 7, 30, 50
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
        $_POST["其中：基础研究和应用研究项目数"] / $_POST['企业全部研发项目数'], 4, 1, 4
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
        $_POST["通过国家（国际组织）、省认证（认定）的实验室和检测机构数"], 3, 1, 1
    );
    $metaData["当年被受理的专利申请数"] = array(
        $_POST["当年被受理的专利申请数"], 5, 4, 10
    );
    $metaData["当年被受理的发明专利申请数"] = array(
        $_POST["其中：当年被受理的发明专利申请数"], 6, 1, 5
    );
    $metaData["最近三年主持和参加制定的国际、国家、行业和地方标准数"] = array(
        $_POST["最近三年主持和参加指定的国际、国家和行业、企业标准数"], 4, 1, 3
    );
    $metaData["新产品销售收入占主营业务收入的比重"] = array(
        $_POST["新产品销售收入"] / $_POST['主营业务收入'], 10, 15, 20
    );
    $metaData["新产品销售利润占利润总额的比重"] = array(
        $_POST["新产品销售利润"] / $_POST['利润总额'], 10, 10, 15
    );
    $metaData["利润率"] = array(
        $_POST["利润总额"] / $_POST['主营业务收入'], 5, 5, 12
    );
    $metaData["获省级自然科学、技术发明、科技进步奖项目数"] = array(
        $_POST["获省级以上自然科学、技术发明、科技进步奖项目数"], 4, 1, 2
    );
    $metaData["企业享受加计扣除政策额度"] = array(
        $_POST["近两年企业享受加计扣除政策额度"], 3, 0, "0"
    );
    $metaData["承担的省级以上项目数量"] = array(
        $_POST["承担的省级以上项目数量"], 4, 1, 2
    );
    $metaData["获省级协会以上成果奖励项目数量"] = array( //？？
        $_POST["企业获得省级以上质量标杆，品牌培育示范、试点企业项目数量"], 4, 1, 2
    );


    // 数据插入数据库
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
        date_default_timezone_set("PRC");
        $sql = "UPDATE `enterprise` set `status` = 2, `submit_time` = '" . date('Y-m-d H:i:s', time()) . "' WHERE `loginid` = '$_SESSION[loginid]'";
        $db->query($sql);
        $_SESSION['status'] = 2;
    }
}
die(json_encode($arr));
