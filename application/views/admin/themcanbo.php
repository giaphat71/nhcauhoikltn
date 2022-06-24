<?
checkLogin();
$catid="qlcb";
include "header.htm"; ?>
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
        <div class="section-title">Thêm cán bộ mới</div>
        <div class="section-body">
            <form>
            <p>Tài khoản</p>
            <input class="form-control" type="text" id="account">
            <br><p>Mật khẩu</p>
            <div class="input-group">
                <input class="form-control" type="text" id="password">
                <div class="input-group-append">
                    <button type="button" class="btn btn-success" onclick="genPass()"><i class="fa fa-wand"></i></button>
                </div>
            </div>
            
            <br>
            <p>Họ và tên</p>
            <input class="form-control" type="text" id="name"><br>
            <p>Đơn vị</p>
            <input class="form-control" type="text" id="donvi"><br>
            <p>Avatar</p>
            <input class="form-control" type="text" id="iavatar" value="/images/defaultavatar.png"><br><br>
            <center><button type="button" class="btn btn-primary" onclick="addCanbo()">Lưu trữ</button></center>
            </form>
        </div>
    </div>
    <script>
        function genPass(){
            var result           = '';
            var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789??<>!@$%^&';
            var charactersLength = characters.length;
            for ( var i = 0; i < 16; i++ ) {
                result += characters.charAt(Math.floor(Math.random() * 
            charactersLength));
            }
            g("password").value = result;
        }
        function v(k){
            return encodeURIComponent(val(k));
        }
        function addCanbo(){
            // account password name donvi avatar
            var param = `ajax=addcanbo&account=${v("account")}&password=${v("password")}&name=${v("name")}&donvi=${v("donvi")}&avatar=${v("iavatar")}`;
            fetch("/admin/ajax",{
                method: "POST",
                headers: {"Content-Type": "application/x-www-form-urlencoded"},
                body: param
            }).then(a=>a.text()).then(t=>{
                ui.alert(t);
            });
        }
    </script>
<? include "footer.htm" ?>