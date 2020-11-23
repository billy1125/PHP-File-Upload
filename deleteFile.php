<?php
// 設置資料類型 json，編碼格式 utf-8
header('Content-Type: application/json; charset=UTF-8');

// $result = array(array("answer" => ""));
$result = 0;

$student_id = "";
if (isset($_GET["student_id"]) && $_GET["student_id"] != "") {
    $student_id = $_GET["student_id"];
}

$dir = "../students/" .  $student_id;

if (checkFiles($dir)){
    rrmdir($dir);
    if (checkFiles($dir) == false){
        $result = 1;
    }
}
else
{
    $result = 2;
}



function rrmdir($dir) {
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (filetype($dir."/".$object) == "dir") 
                    rrmdir($dir."/".$object); 
                else
                    unlink($dir."/".$object);
            }
        }
        reset($objects);
        rmdir($dir);
    }
}

function checkFiles($dir){
    $results = false;
    if (is_dir($dir)){
        $objects = scandir($dir);
        if (count($objects) > 0){
            $results = true;
        }
    }
    return $results;
}


echo json_encode(array("answer" => $result));
?>
