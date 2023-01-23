<?php
/**
 * 验证是否是企业用户
 */
if (!is_enterprise())
    die(json_encode(array(
        "code" => 1,
        "msg" => get_string("ENTERPRISE_ONLY")
    )));