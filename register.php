<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: upload.php");
    exit;
}

// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$username = $password = $confirm_password = $student_id = "";
$username_err = $password_err = $confirm_password_err = $student_id_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "請輸入姓名";
    }else {
        $username = trim($_POST["username"]);
    }

    // else {
    //     // Prepare a select statement
    //     $sql = "SELECT id FROM students WHERE student_id = ?";

    //     if ($stmt = mysqli_prepare($link, $sql)) {
    //         // Bind variables to the prepared statement as parameters
    //         mysqli_stmt_bind_param($stmt, "s", $param_username);

    //         // Set parameters
    //         $param_username = trim($_POST["username"]);

    //         // Attempt to execute the prepared statement
    //         if (mysqli_stmt_execute($stmt)) {
    //             /* store result */
    //             mysqli_stmt_store_result($stmt);

    //             if (mysqli_stmt_num_rows($stmt) == 1) {
    //                 $username_err = "This name has been used.";
    //             } else {
    //                 $username = trim($_POST["username"]);
    //             }
    //         } else {
    //             echo "Oops! Something went wrong. Please try again later.";
    //         }
    //     }

    //     // Close statement
    //     mysqli_stmt_close($stmt);
    // }

    // Validate student_id
    if (empty(trim($_POST["student_id"]))) {
        $student_id_err = "請輸入學號";
    } else {
        // Prepare a select statement
        $sql = "SELECT id FROM students WHERE student_id = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_student_id);

            // Set parameters
            $param_student_id = trim($_POST["student_id"]);

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                /* store result */
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $student_id_err = "這個學號已經存在，請設定另一個學號";
                } else {
                    $student_id = trim($_POST["student_id"]);
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "請輸入密碼";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "請至少輸入六個英數字或符號";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "請再輸入一次密碼";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "密碼不匹配";
        }
    }

    // Check input errors before inserting in database
    if (empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($student_id_err)) {

        // Prepare an insert statement
        $sql = "INSERT INTO students (student_name, student_id, password) VALUES (?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sis", $param_username, $param_student_id, $param_password);

            // Set parameters
            $param_username = $username;
            $param_student_id = $student_id;
            $param_password = $password;
            //$param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Redirect to login page
                echo "ok";
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

<!DOCTYPE html>
<html lang="zh-tw">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>帳號申請</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <style type="text/css">
        body {
            font: 14px sans-serif;
        }

        .wrapper {
            width: 350px;
            padding: 20px;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <h2>新增帳號</h2>
        <p>請填寫以下項目</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>姓名</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($student_id)) ? 'has-error' : ''; ?>">
                <label>學號</label>
                <input type="text" name="student_id" class="form-control" value="<?php echo $student_id; ?>">
                <span class="help-block"><?php echo $student_id_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>密碼</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>請再輸入一次密碼</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="送出">
                <input type="reset" class="btn btn-default" value="重新設定">
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>
</body>

</html>