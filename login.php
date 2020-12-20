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
$username = $password = "";
$username_err = $password_err = "";

try {
    // Processing form data when form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Check if username is empty
        if (empty(trim($_POST["username"]))) {
            $username_err = "請輸入帳號";
        } else {
            $username = trim($_POST["username"]);
        }

        // Check if password is empty
        if (empty(trim($_POST["password"]))) {
            $password_err = "請輸入密碼";
        } else {
            $password = trim($_POST["password"]);
        }

        // Validate credentials
        if (empty($username_err) && empty($password_err)) {
            // Prepare a select statement
            $sql = "SELECT id, student_name, student_id, password, admin FROM students WHERE student_id = ?";

            if ($stmt = mysqli_prepare($link, $sql)) {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "s", $param_username);

                // Set parameters
                $param_username = $username;

                // Attempt to execute the prepared statement
                if (mysqli_stmt_execute($stmt)) {
                    // Store result
                    mysqli_stmt_store_result($stmt);

                    // Check if username exists, if yes then verify password
                    if (mysqli_stmt_num_rows($stmt) == 1) {
                        // Bind result variables
                        mysqli_stmt_bind_result($stmt, $id, $student_name, $student_id, $get_password, $admin);
                        if (mysqli_stmt_fetch($stmt)) {
                            //if (password_verify($password, $get_password)) {
                            if ($password == $get_password) {
                                // Password is correct, so start a new session
                                session_start();

                                // Store data in session variables
                                $_SESSION["loggedin"] = true;
                                $_SESSION["id"] = $id;
                                $_SESSION["username"] = $student_name;
                                $_SESSION["student_id"] = $student_id;
                                $_SESSION["admin"] = $admin;
                                // Redirect user to welcome page
                                header("location: upload.php");
                            } else {
                                // Display an error message if password is not valid
                                $password_err = "密碼有誤";
                            }
                        }
                    } else {
                        // Display an error message if username doesn't exist
                        $username_err = "沒有這個帳號";
                    }
                }
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
} catch (Exception $e) {
    echo "Exception" . $e->getCode() . ": " . $e->getMessage() . "<br />" .
        " in " . $e->getFile() . " on line " . $e->getLine() . "<br />";
} finally {
    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="zh-tw">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script type="text/javascript" src="js/captcha.jquery.js"></script>

    <link rel="stylesheet" type="text/css" href="css/captcha_style.css">
    <script type="text/javascript">
        $(document).ready(function() {
            $('#register_form').captcha();
            $('#btn_login').click(function() {
                if (!verifyCaptcha('#register_form')) {
                    swal("注意", "請點選我不是機器人", "warning");
                }
            })
        });
    </script>
</head>

<body>
    <div class="jumbotron">
        <div class="container text-center">
            <h1>學生登入</h1>
        </div>
    </div>
    <div class="container">
        <div class="col-md-6">
            <h2>帳號密碼驗證</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="register_form">
                <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                    <label>帳號</label>
                    <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                    <span class="help-block"><?php echo $username_err; ?></span>
                </div>
                <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                    <label>密碼</label>
                    <input type="password" name="password" class="form-control">
                    <span class="help-block"><?php echo $password_err; ?></span>
                </div>
                <div class="form-group">

                    <div class="captcha"></div>
                    <input type="submit" class="btn btn-primary" value="登入" id="btn_login">
                </div>
                <!-- <p>Don't have an account? <a href="register.php">Sign up now</a>.</p> -->
            </form>
        </div>
        <div class="col-md-6">

            <h2>說明</h2>
            <p class="well">請同學輸入帳號密碼，預設帳號密碼都是你的學號，密碼可於登入後修改（帳號無法修改），建議同學要修改密碼，避免資料被他人竄改。</p>
        </div>
    </div>

    <footer class="container-fluid text-center bg-info">
        <p>網站管理員：<a href="mailto:chlu@mail.fgu.edu.tw?subject=老師網站有問題！&body=">呂卓勳老師</a></p>
    </footer>
</body>

</html>