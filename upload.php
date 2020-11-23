<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}


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
        const student_id = <?php echo htmlspecialchars($_SESSION["student_id"]); ?>;
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
        <h2><b><?php echo htmlspecialchars($_SESSION["username"]); ?></b> 同學你好！<a href="logout.php" class="btn btn-danger">登出</a></h2>

        <ul class="nav nav-tabs">
            <li class="active"><a href="#">Home</a></li>
            <li><a href="reset-password.php">重設密碼</a></li>
        </ul>
        <div class="row">
            <div class="col-md-6">
                <h3>你的作業</h3>
                <hr>
                <div class="panel panel-primary">
                    <div class="panel-heading">檔案上傳狀況</div>
                    <div class="panel-body" id="file_upload_status"></div>
                </div>
                <a href="#" class="btn btn-success" id="accssee_userweb">前往網頁</a>
                <a href="#" class="btn btn-danger" id="delete_btn">刪除你所有的檔案</a>
            </div>
            <div class="col-md-6">
                <h3>Zip檔案上傳</h3>
                <hr>
                <!-- <form action="doAction.php" method="post" enctype="multipart/form-data" class="well"> -->
                <form class="well">
                    <!-- 限制上傳檔案的最大值 -->
                    <div class="form-group">
                        <input type="hidden" name="MAX_FILE_SIZE" value="20971520">
                        <!-- accept 限制上傳檔案類型。多檔案上傳 name 的屬性值須定義為 array -->
                        <input type="file" name="myFile[]" accept=".zip" placeholder="Text input" id="file-uploader">
                    </div>

                    <!-- <input type="file" name="myFile[]" accept=".zip" style="display: block;margin-bottom: 5px;">
    <input type="file" name="myFile[]" accept=".zip" style="display: block;margin-bottom: 5px;">
    <input type="file" name="myFile[]" accept=".zip" style="display: block;margin-bottom: 5px;"> -->

                    <!-- 使用 html 5 實現單一上傳框可多選檔案方式，須新增 multiple 元素 -->
                    <!-- <input type="file" name="myFile[]" id="" accept="image/jpeg,image/jpg,image/gif,image/png" multiple> -->
                    <div class="form-group">
                        <!-- <button type="submit" class="btn btn-primary">上傳檔案</button> -->
                        <a href="#" class="btn btn-primary" id="btn-file-uploader">上傳檔案</a>
                        <span class="help-block">檔案上傳僅限zip檔，大小僅限20mb以內</span>
                    </div>
                    <!-- <div class="alert alert-success" style="display:none" id="upload_results">
                        <h1><strong>Success!</strong> 你已經上傳成功！</h1>
                        <p>你可以看看你的網站內容：<a href=<?php echo '../students/' . htmlspecialchars($_SESSION["student_id"]); ?> class="btn btn-success">你的網頁</a></p>
                    </div> -->
                </form>
                <h3>說明</h3>
                <p class="well">請同學注意，上傳後會自動刪除你之前上傳的檔案，再進行解壓縮，所以請每次更新務必重新上傳zip壓縮檔。</p>
            </div>

        </div>
    </div>
    <footer class="container-fluid text-center bg-info">
        <p>網站管理員：<a href="mailto:chlu@mail.fgu.edu.tw?subject=老師網站有問題！&body=">呂卓勳老師</a></p>
    </footer>
</body>

</html>