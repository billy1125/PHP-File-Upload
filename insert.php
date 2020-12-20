<?php
// 設置資料類型 json，編碼格式 utf-8
header('Content-Type: application/json; charset=UTF-8');

require_once "config.php";

$fp = fopen('data.csv', 'r');

// while (($data = fgetcsv($fp, 1000, ','))) {
//     $student_name =  $data[1];
//     $student_id =  $data[0];
//     // echo $student_name . " " . $student_id;
//     insert_file_upload_record($link, $student_name, $student_id, $student_id);
// }

//insert_file_upload_record($link, $fp);

fclose($fp);

function insert_file_upload_record($link, $fp)
{
    // Define variables and initialize with empty values
    $student_id_err = "";

    // Check input errors before inserting in database
    if (empty($student_id_err)) {

        // Prepare an insert statement
        $sql = "INSERT INTO students (student_name, student_id, password) VALUES (?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {


            while (($data = fgetcsv($fp, 1000, ','))) {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "sis", $param_username, $param_student_id, $param_password);
                // Set parameters
                $param_username = $data[1];
                $param_student_id = $data[0];
                $param_password = $data[0];

                // Attempt to execute the prepared statement
                if (mysqli_stmt_execute($stmt)) {
                    // Redirect to login page
                    //header("location: login.php");
                } else {
                    echo "Something went wrong. Please try again later.";
                }
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($link);
}
