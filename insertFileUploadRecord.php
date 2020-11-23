<?php

if ($student_id != ""){
    // Include config file
    require_once "config.php";
    insert_file_upload_record($link, $student_id);
}


function insert_file_upload_record($link, $student_id){
    // Define variables and initialize with empty values
    $student_id_err = "";

    // Check input errors before inserting in database
    if (empty($student_id_err)) {

        // Prepare an insert statement
        $sql = "INSERT INTO file_upload (student_id) VALUES (?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_student_id);

            // Set parameters
            $param_student_id = $student_id;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Redirect to login page
                //header("location: login.php");
            } else {
                echo "Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($link);
}

?>
