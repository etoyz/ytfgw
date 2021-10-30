<?php
function fetch_indicator_attachment_name($indicator, $allFile)
{
    foreach (preg_grep("/^附件_" . $indicator . "_.*$/", $allFile) as $r)
        return $r;
    return "未上传";
}