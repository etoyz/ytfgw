<?php
session_start();

$response = array(
    "code" => 0,
    "msg" => "",
    "data" => null
);

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    // error was suppressed with the @-operator
    if (0 === error_reporting()) {
        return false;
    }

    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

try {
    mkdir("../uploads/" . $_SESSION['loginid']);
} catch (Exception $e) {
}
ini_set("upload_max_filesize", "10M"); //TODO It does not work on some system
try {
    $response['data'] = move_uploaded_file($_FILES['file']['tmp_name'],
        "../uploads/" . $_SESSION['loginid'] . "/附件_" . $_POST['indicator'] . "_" . $_FILES['file']['name']);
} catch (Exception $e) {
    $response['error'] = json_encode($e);
}

die(json_encode($response));
