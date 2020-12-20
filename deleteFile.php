<?php
// 設置資料類型 json，編碼格式 utf-8
header('Content-Type: application/json; charset=UTF-8');

// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

$result = 0;

$student_id = "";
if (isset($_GET["student_id"]) && $_GET["student_id"] != "") {
    $student_id = $_GET["student_id"];
}

if ($student_id != "") {
    $dir = "../students/" .  $student_id;

    if (checkFiles($dir)) {
        rrmdir($dir);
        if (checkFiles($dir) == false) {
            $result = 1;
        }
    } else {
        $result = 2;
    }
}
else{
    $result = -1;
}


function rrmdir($dir)
{
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (filetype($dir . "/" . $object) == "dir")
                    rrmdir($dir . "/" . $object);
                else
                    unlink($dir . "/" . $object);
            }
        }
        reset($objects);
        rmdir($dir);
    }
}

function checkFiles($dir)
{
    $results = false;
    if (is_dir($dir)) {
        $objects = scandir($dir);
        if (count($objects) > 0) {
            $results = true;
        }
    }
    return $results;
}


echo json_encode(array("answer" => $result));
