$(document).ready(function () {
    $("#accssee_userweb").click(function () {
        $.ajax({
            type: "POST",
            url: "../students/" + student_id,
            dataType: "html",
            success: function (data) {
                document.location.href = "../students/" + student_id;
            },
            error: function (jqXHR) {
                swal("Oh no!", "你目前沒有上傳網頁", "warning");
                //alert("發生錯誤: " + jqXHR.status);
            }
        })
    })

    $("#delete_btn").click(function () {
        if (student_id != "") {
            $.ajax({
                type: "GET",
                url: "deleteFile.php?student_id=" + student_id,
                dataType: "json",
                timeout: 60000,
                success: function (data) {
                    switch (data.answer) {
                        case -1:
                            swal("檔案未刪除", "上傳錯誤", "warning");
                            break;
                        case 0:
                            swal("檔案未刪除", "你目前沒有上傳網頁", "warning");
                            break;
                        case 1:
                            swal("刪除完成", "請記得要上傳檔案", "success");
                            $("#file_upload_status").html("<p>你目前沒有上傳檔案</p>");
                            break;
                        case 2:
                            swal("取消刪除", "你目前沒有上傳檔案", "warning");
                            $("#file_upload_status").html("<p>你目前沒有上傳檔案</p>");
                            break;
                        default:
                            break;
                    }

                },
                error: function (jqXHR) {
                    alert("發生錯誤: " + jqXHR.status);
                }
            })
        }
    })

    $("#file_upload_status").ready(function () {
        $.ajax({
            type: "GET",
            url: "checkFiles.php?student_id=" + student_id,
            dataType: "json",
            success: function (data) {
                if (data.answer == 1) {
                    $("#file_upload_status").html("<p>你已經有上傳檔案，以下是最近一次的上傳時間</p>");
                    queryUploadDatatimeList();
                } else {
                    $("#file_upload_status").html("<p>你目前沒有上傳檔案</p>");
                }
            },
            error: function (jqXHR) {
                alert("發生錯誤: " + jqXHR.status);
            }
        })
    })

    $("#students_data").ready(function () {
        if (is_admin == "Y") {
            $("#students_data").css("display", "");
        }
    })

    $("#student_data_list").ready(function () {
        $.ajax({
            type: "GET",
            url: "query.php?kind_id=2",
            dataType: "json",
            success: function (data) {
                if (data.results == 1) {
                    for (let i = 0; i < data.student_data.length; i++) {
                        $("#student_data_list").append("<tr><td>" + data.student_data[i][0] +
                            //"</td><td><a href='../students/" + data.student_data[i][2] + "'>" + data.student_data[i][2] + "</a>" +
                            "</td><td id='" + data.student_data[i][2] + "'>" + data.student_data[i][2] +
                            "</td><td>" + data.student_data[i][1] +
                            "</td><td>" + data.student_data[i][3] +
                            "</td><td>" + data.student_data[i][4] +
                            "</td></tr>");
                        if (data.student_data[i][4] != null) {
                            $("#" + data.student_data[i][2]).html("<a href='../students/" + data.student_data[i][2] + "'>" + data.student_data[i][2] + "</a>")
                        }
                    }
                } else {
                    $("#student_data_list").append("<p>沒有資料</p>");
                }
            },
            error: function (jqXHR) {
                console.log("發生錯誤: " + jqXHR.status);
            }
        })
    })

    $("#student_works").ready(function () {
        $.ajax({
            type: "GET",
            url: "query.php?kind_id=2",
            dataType: "json",
            success: function (data) {
                if (data.results == 1) {
                    for (let i = 0; i < data.student_data.length; i++) {
                        $("#student_works").append("<tr><td>" + data.student_data[i][0] +
                            "</td><td id='" + data.student_data[i][2] + "'>" + data.student_data[i][2] +
                            "</td></tr>");
                        if (data.student_data[i][4] != null) {
                            $("#" + data.student_data[i][2]).html("<a href='../students/" + data.student_data[i][2] + "'>" + data.student_data[i][2] + "</a>")
                        }
                    }
                } else {
                    $("#student_works").append("<p>沒有資料</p>");
                }
            },
            error: function (jqXHR) {
                console.log("發生錯誤: " + jqXHR.status);
            }
        })
    })

    function queryUploadDatatimeList() {
        $.ajax({
            type: "GET",
            url: "query.php?kind_id=1&student_id=" + student_id,
            dataType: "json",
            success: function (data) {
                if (data.results == 1) {
                    DatatimeList = "";
                    for (let i = 0; i < data.datetime_list.length; i++) {
                        $("#file_upload_status").append("<p>" + data.datetime_list[i] + "</p>");
                        DatatimeList += data.datetime_list[i] + "<br>";
                    }

                    // $("#file_upload_status").text(DatatimeList);
                } else {
                    $("#file_upload_status").html("<p>你目前沒有上傳檔案</p>");
                }
            },
            error: function (jqXHR) {
                alert("發生錯誤: " + jqXHR.status);
            }
        })
    }

    $("#btn-file-uploader").click(function () {

        var fileUploader = document.querySelector('#file-uploader');
        var formData = new FormData();
        var files = fileUploader.files;

        // Check file selected or not
        if (files.length > 0 && student_id != "") {
            formData.append('file', files[0]);
            formData.append('student_id', student_id);

            $.ajax({
                url: 'doAction_test.php',
                type: 'POST',
                data: formData,
                dataType: "json",
                contentType: false,
                processData: false,
                success: function (data) {
                    if (data.answer == 1) {
                        $("#file_upload_status").html("<p>你已經有上傳檔案，以下是上傳時間</p>");
                        queryUploadDatatimeList();
                        swal("上傳成功", "請記得前往你的網頁看看是否有顯示？", "success");
                    } else {
                        $("#file_upload_status").html("<p>你目前沒有上傳檔案</p>");
                        swal("沒有上傳", "請記得要上傳檔案", "warning");
                    }
                },
                error: function (jqXHR) {
                    console.log("發生錯誤: " + jqXHR.status);
                    swal("失敗上傳", "錯誤碼: " + jqXHR.status, "error");
                }
            });
        } else {
            swal("上傳錯誤", "請重新選擇", "warning");
        }
    });
})