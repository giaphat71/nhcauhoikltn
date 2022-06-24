<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Đăng ký</title>
    <script src="/jqr.js"></script>
    <link rel="stylesheet" type="text/css" href="/main.css?v=42">
    <link rel="stylesheet" href="/all.min.css">
    <link rel="stylesheet" href="/bootstrap.min.css?origin=">
    <link rel="stylesheet" href="/font.css?v=2">
    <script type="text/javascript" src="/stv.ui.js?v=1.181"></script>
    <script src="/bootstrap.min.js"></script>
    
    <style>
        
    </style>
</head>

<body style="font-family: nunito;">
    <div class="container shadow-lg rounded bg-light" style="margin: 4rem auto;">
        <form class="text-center border border-light p-5" style="max-width: 480px; margin-top: 72px;margin:auto;">

            <p class="h4 mb-4">Đăng ký</p>
        
            <!-- Email -->
            <input type="text" id="account" class="form-control mb-4" placeholder="Tài khoản">
        
            <!-- Password -->
            <input type="password" id="password" class="form-control mb-4" placeholder="Mật khẩu">
            <input type="email" id="email" class="form-control mb-4" placeholder="Email">
            <input type="text" id="name" class="form-control mb-4" placeholder="Họ và tên">
            <select class="form-control mb-4" name="donvi" id="donvi">
                <?php
                    include "./donvi.php";
                    foreach($listdonvi as $key=>$value){
                        echo "<option value='$key'>$value</option>";
                    }
                ?>
            </select>
            <button color="info" block="true" class="btn btn-primary" type="button" onclick="register()">Đăng nhập</button>
        
        </form>
    </div>
    <script>
        ui.scriptmanager.load("/javascripts/mainadmin.js?r="+Math.random());
        function login(){
            var pass = val("password");
            var nick = val("account");
            fetch("/admin/ajax",{
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: `ajax=login&account=${nick}&password=${pass}`
            }).then(function(response){
                return response.text();
            }).then(function(response){
                if(response=="success"){
                    location.href="/canbo";
                }else if(response=="asuccess"){
                    location.href="/admin";
                }else alert(response);
            })
        }
        function register(){
            var pass = val("password");
            var nick = val("account");
            var email = val("email");
            var name = val("name");
            var donvi = val("donvi");
            fetch("/admin/ajax",{
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: `ajax=register&account=${nick}&password=${pass}&email=${email}&name=${name}&donvi=${donvi}`
            }).then(function(response){
                return response.text();
            }).then(function(response){
                if(response=="success"){
                    location.href="/canbo";
                }else alert(response);
            });
        }
    </script>
</html>