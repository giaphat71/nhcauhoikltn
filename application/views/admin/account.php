<?

checkLogin();
$u = getUser();

$catid="acc";
include "header.htm" ?>
    <style>
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
        <div class="section-title">Thông tin cá nhân</div>
        <button onclick="changePass()" class="btn btn-primary" style="float:right;">Đổi mật khẩu</button>
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
            <center><button class="btn btn-primary">Cập nhật</button></center>
        </div>
    </div>
    <script>
    </script>
<? include "footer.htm" ?>