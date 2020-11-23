<?php
// 設置資料類型 json，編碼格式 utf-8
header('Content-Type: application/json; charset=UTF-8');

// // Initialize the session
// session_start();

// // Check if the user is already logged in, if yes then redirect him to welcome page
// if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
//     header("location: upload.php");
//     exit;
// }

$results = $kind_id = "";
$array_results;

if (isset($_GET["kind_id"]) && $_GET["kind_id"] != "") {
    $kind_id = $_GET["kind_id"];
    // Include config file
    require_once "config.php";
}

switch ($kind_id) {
    case '1':
        $array_results = query_upload_records($link, $_GET["student_id"]);
        break;
    
    default:
        exit;
        break;
}


function query_upload_records($link, $student_id){
    $arr = null;
    // Validate credentials
    if (!empty($link) && !empty($student_id)) {
        // Prepare a select statement
        $sql = "SELECT upload_datetime FROM file_upload WHERE student_id = ? ORDER BY upload_datetime DESC";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_student_id);

            // Set parameters
            $param_student_id = $student_id;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Store result
                mysqli_stmt_store_result($stmt);
                // Check if username exists, if yes then verify password
                if (mysqli_stmt_num_rows($stmt) > 0) {
                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $upload_datetime);
                    $temp = array();
                    while (mysqli_stmt_fetch($stmt)) {
                        array_push($temp, $upload_datetime);                        
                    }
                    $arr = array ('results' => 1, 'datetime_list' => $temp);
                } else {                    
                    $arr = array ('results' => 1, 'datetime_list' => []);
                }
            } 
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }else {
        $arr = array ('results' => -1, 'datetime_list' => []);
    }

    // Close connection
    mysqli_close($link);
    return $arr;
}

echo json_encode($array_results);

?>