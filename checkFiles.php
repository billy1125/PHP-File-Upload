<?php
// 設置資料類型 json，編碼格式 utf-8
header('Content-Type: application/json; charset=UTF-8');

$result = 0;

$student_id = "";
if (isset($_GET["student_id"]) && $_GET["student_id"] != "") {
    $student_id = $_GET["student_id"];
}

$dir = "../students/" .  $student_id;

if (checkFiles($dir)) {
    $result = 1;
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

?>