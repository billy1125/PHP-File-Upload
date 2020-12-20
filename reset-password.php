<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

$student_id = htmlspecialchars($_SESSION["student_id"]);
$username = htmlspecialchars($_SESSION["username"]);
$admin = htmlspecialchars($_SESSION["admin"]);

// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate new password
    if (empty(trim($_POST["new_password"]))) {
        $new_password_err = "請輸入你的新密碼";
    } elseif (strlen(trim($_POST["new_password"])) < 6) {
        $new_password_err = "請至少要輸入六個英數字或符號.";
    } else {
        $new_password = trim($_POST["new_password"]);
    }

    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "請重複輸入你的密碼";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($new_password_err) && ($new_password != $confirm_password)) {
            $confirm_password_err = "密碼不匹配！";
        }
    }

    // Check input errors before updating the database
    if (empty($new_password_err) && empty($confirm_password_err)) {
        // Prepare an update statement
        $sql = "UPDATE students SET password = ? WHERE id = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "si", $param_password, $param_id);

            // Set parameters
            //$param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $param_password = $new_password;
            $param_id = $_SESSION["id"];

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Password updated successfully. Destroy the session, and redirect to login page
                session_destroy();
                header("location: login.php");
                exit();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
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
    <title>密碼重設</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body>
    <div class="jumbotron">
        <div class="container text-center">
            <h1>計算機概論網頁作業</h1>
        </div>
    </div>
    <div class="container">
        <h2><b><?php echo $username; ?></b> 同學你好！<a href="logout.php" class="btn btn-danger">登出</a></h2>
        <ul class="nav nav-tabs">
            <li><a href="upload.php">Home</a></li>
            <li><a href="all_work.php">作品展覽</a></li>
            <li class="active"><a href="reset-password.php">重設密碼</a></li>
            <li><a href="upload_info.php">作業上傳須知</a></li>
        </ul>
        <div class="row">

            <div class="col-md-6">
                <h3>密碼重設</h3>
                <hr>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group <?php echo (!empty($new_password_err)) ? 'has-error' : ''; ?>">
                        <label>新密碼（請勿少於6個英數字或符號）</label>
                        <input type="password" name="new_password" class="form-control" value="<?php echo $new_password; ?>">
                        <span class="help-block"><?php echo $new_password_err; ?></span>
                    </div>
                    <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                        <label>確認密碼</label>
                        <input type="password" name="confirm_password" class="form-control">
                        <span class="help-block"><?php echo $confirm_password_err; ?></span>
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" value="送出">
                        <a class="btn btn-link" href="upload.php">取消</a>
                    </div>
                </form>
            </div>

        </div>
    </div>
    <footer class="container-fluid text-center bg-info">
        <p>網站管理員：<a href="mailto:chlu@mail.fgu.edu.tw?subject=老師網站有問題！&body=">呂卓勳老師</a></p>
    </footer>
</body>

</html>