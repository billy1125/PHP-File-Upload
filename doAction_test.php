<?php
// 設置資料類型 json，編碼格式 utf-8
header('Content-Type: application/json; charset=UTF-8');
$result = 0;

// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

$student_id = (empty($_REQUEST['student_id'])) ? "" : $_REQUEST['student_id'];

/**
 * 表單接收頁面
 */

// 網頁編碼宣告（防止產生亂碼）
header('content-type:text/html;charset=utf-8');
// 封裝好的單一及多檔案上傳 function
include_once 'upload.func.php';
// 重新建構上傳檔案 array 格式
$files = getFiles();

// 依上傳檔案數執行
foreach ($files as $fileInfo) {
    // 呼叫封裝好的 function
    $res = uploadFile($fileInfo, $student_id);

    // 顯示檔案上傳訊息
    //echo $res['mes'] . '<br>';

    // 上傳成功，將實際儲存檔名存入 array（以便存入資料庫）
    if (!empty($res['dest'])) {
        $uploadFiles[] = $res['dest'];
    }
}

$upzip_loaction = '../students/' . $student_id;

if (checkFiles($upzip_loaction)) {
    rrmdir($upzip_loaction);
}

require_once "insertFileUploadRecord.php";
$result = unzip($uploadFiles[0], $upzip_loaction);

//zip解壓縮
function unzip($zipfile, $upzip_loaction)
{
    $result = 0;
    $zip = new ZipArchive;
    $res = $zip->open($zipfile);
    if ($res === TRUE) {
        $zip->extractTo($upzip_loaction);
        $zip->close();
        $result = 1;
    } else {
        $result = 2;
    }

    //deleteOneFile($zipfile);

    return $result;
}

// 特定檔案夾下所有檔案與檔案夾刪除
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

// 確認是不是有檔案
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

function deleteOneFile($file_path)
{
    if (file_exists($file_path)) {
        unlink($file_path);
    }
}

echo json_encode(array("answer" => $result));
