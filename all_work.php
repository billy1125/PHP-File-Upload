<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

$student_id = htmlspecialchars($_SESSION["student_id"]);
$username = htmlspecialchars($_SESSION["username"]);
$admin = htmlspecialchars($_SESSION["admin"]);
?>

<!DOCTYPE html>
<html lang="zh-tw">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>計算機概論檔案上傳</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <script type="text/JavaScript">
        const student_id = <?php echo $student_id; ?>;
    </script>
    <script src="js/upload.js"></script>

</head>

<body>
    <div class="jumbotron">
        <div class="container text-center">
            <h1>計算機概論網頁作業</h1>
        </div>
    </div>

    <div class="container">
        <h2><b><?php echo $username; ?></b> 同學你好！<a href="logout.php" class="btn btn-danger">登出</a></h2>

        <ul class="nav nav-tabs" id="navbar">
            <li><a href="upload.php">Home</a></li>
            <li class="active"><a href="all_work.php">作品展覽</a></li>
            <li><a href="reset-password.php">重設密碼</a></li>
            <li><a href="upload_info.php">作業上傳須知</a></li>
        </ul>
        <div class="row">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">學號</th>
                    </tr>
                </thead>
                <tbody id="student_works">
                </tbody>
            </table>
        </div>
    </div>
    <footer class="container-fluid text-center bg-info">
        <p>網站管理員：<a href="mailto:chlu@mail.fgu.edu.tw?subject=老師網站有問題！&body=">呂卓勳老師</a></p>
    </footer>
</body>

</html>