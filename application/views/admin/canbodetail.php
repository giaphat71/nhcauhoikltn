<?
checkLogin();
$u = getUser($idcanbo);
$catid="qlcb";
include "header.htm" ?>
    <style>
        .monhoc{
            background-color: rgb(238, 237, 237);
            padding: 8px;
            border-radius: 8px;
            transition: all .5s;
        }
        .monhoc:hover{
            background-color: rgb(219, 219, 219);
        }
        .monhoc-title{
            font-size: 24px;
        }
        .monhoc-num{
            font-size:12px;
        }
        .section-title{
            font-size: 24px;
            font-weight: bold;
            display: inline-block;
            border-bottom: 1px solid gray;
            margin-bottom: 6px;
        }
        .section{
            padding: 8px;
        }
    </style>
    <div class="section bg-light">
        <div class="section-title">Thông tin cán bộ</div>
        <div class="section-body">
            <p>Tài khoản</p>
            <input class="form-control" type="text" id="account" value="<?=$u->account?>" disabled>
            <br>
            <p>Họ và tên</p>
            <input class="form-control" type="text" id="name" value="<?=$u->name?>"><br>
            <p>Đơn vị</p>
            <input class="form-control" type="text" id="nganh" value="<?=$u->donvi?>"><br>
            <p>Avatar</p>
            <input class="form-control" type="text" id="avatar" value="<?=$u->avatar?>"><br>
            <center><button class="btn btn-primary">Lưu trữ</button></center>
        </div>
    </div>
    <script>
    </script>
<? include "footer.htm" ?>