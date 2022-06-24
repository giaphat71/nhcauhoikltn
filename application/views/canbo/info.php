<?

checkLogin();
$u = getUser();

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
            <input class="form-control" type="text" id="iavatar" value="<?=$u->avatar?>"><br>
            <center><button class="btn btn-primary" onclick="saveInfo()">Cập nhật</button></center>
        </div>
    </div>
    <script>
        function changePass(){
            $("#changePass").modal("show");
        }
        function saveInfo(){
            var name = $("#name").val();
            var nganh = $("#nganh").val();
            var avatar = $("#iavatar").val();
            $.ajax({
                url: "/canbo/ajax",
                type: "POST",
                data: {
                    name: name,
                    nganh: nganh,
                    avatar: avatar,
                    ajax: "saveInfo"
                },
                success: function(data){
                    if(data == "success"){
                        alert("Cập nhật thành công");
                    }
                }
            });
        }
    </script>
    <!-- Change pass modal -->
    <div class="modal fade" id="changePass">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Đổi mật khẩu</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Mật khẩu cũ</label>
                        <input class="form-control" type="password" id="oldPass">
                    </div>
                    <div class="form-group">
                        <label>Mật khẩu mới</label>
                        <input class="form-control" type="password" id="newPass">
                    </div>
                    <div class="form-group">
                        <label>Nhập lại mật khẩu mới</label>
                        <input class="form-control" type="password" id="newPass2">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="savePass()">Cập nhật</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        function savePass(){
            var oldPass = $("#oldPass").val();
            var newPass = $("#newPass").val();
            var newPass2 = $("#newPass2").val();
            $.ajax({
                url: "/canbo/ajax",
                type: "POST",
                data: {
                    oldPass: oldPass,
                    newPass: newPass,
                    newPass2: newPass2,
                    ajax: "changePass"
                },
                success: function(data){
                    if(data == "success"){
                        alert("Cập nhật thành công");
                        $("#changePass").modal("hide");
                    }else{
                        alert(data);
                    }
                }
            });
        }
        // give copilot 10 star 
    </script>
<? include "footer.htm" ?>