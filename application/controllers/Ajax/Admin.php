<?php

$output = "";
$p = $_POST;
$ajax = $p['ajax'] ?? "";
try {
    if($ajax=="login"){
        $password = hashPassword($p['account'],$p['password']);
        $u = buildSearch([
            "account" => $p['account'],
            "password" => $password
        ])->exec("users");
        if($u==null){
            die("Đăng nhập thất bại, kiểm tra tài khoản hoặc mật khẩu");
        }
        //$auth = getModule("auth");
        //$token = $auth->genToken($u->account,$u->password);
        //setcookie("access",token,strtotime("+ 30 days"));
        sessionSet([
            'isLoggedIn'=>true,
            'id'=>$u->id,
            'name'=>$u->name,
            'isAdmin'=>$u->isadmin == "1",
        ]);
        if($_SESSION['isAdmin']){
            die("asuccess");
        }
        die("success");
    }
    if($ajax=="register"){
        $account = $p['account'];
        $password = $p['password'];
        $name = strip_tags($p['name']) ?? "";
        $email = $p['email'] ?? "";
        $donvi = $p['donvi'] ?? "";
        if(!preg_match("/^[\w\d@\.\*\%@\!\-_\(\)\,\$]+$/",$account) || strlen($account) < 4){
            die("Tên tài khoản không phù hợp.");
        }
        if(strlen($password) < 8){
            die("Mật khẩu tối thiểu 8 ký tự.");
        }
        if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
            die("Email không hợp lệ.");
        }
        
        include "./donvi.php";
        if(!array_key_exists($donvi,$listdonvi)){
            die("Đơn vị không hợp lệ.");
        }
        if(strlen($name) < 6){
            die("Họ và tên không hợp lệ.");
        }
        $password = hashPassword($account,$password);
        $exist = buildSearch(['account'=>$account])->exec("users");
        if($exist){
            die("Tài khoản đã tồn tại.");
        }
        $insert = buildSearch(['account'=>$account, 'password'=>$password, "name"=>$name,"email"=>$email,"donvi"=>$donvi])->exec("users");
        if($insert){
            die("success");
        }else{
            die("Lỗi không xác định");
        }
    }
    if(checkLogin()) {
        requireAdmin();
        if($ajax=="finduser"){
            $ft = buildSearch([]);
            if($p['name'] ?? ""){
                $ft->addLike("name",$p['name']);
            }
            if($p['nick'] ?? ""){
                $ft->addLike("account",$p['nick']);
            }
            if($p['donvi'] ?? ""){
                $ft->addLike("donvi",$p['donvi']);
            }
            $rs = $ft->project("id as _id, name")->limit(15)->exec("users");
            die(json_encode($rs));
        }
        if($ajax=="findmh"){
            $ft = buildSearch([]);
            if($p['name'] ?? ""){
                $ft->addLike("name",$p['name']);
            }
            if($p['id'] ?? ""){
                $ft->addLike("mamonhoc",$p['id']);
            }
            if($p['nganh'] ?? ""){
                $ft->addLike("nganh",$p['nganh']);
            }
            $rs = $ft->project("id as _id, name")->limit(5)->exec("monhoc");
            die(json_encode($rs));
        }
        if($ajax=="addpermtouser"){
            $perm = buildSearch([
                "idmonhoc" => $p['mh'],
                "idcanbo"=>$p['canbo'],
            ])->exec("permission");
            if($perm){
                die ("Cán bộ này đã có quyền");
            }else{
                if(buildInsert([
                    "idmonhoc"=>$p['mh'],
                    "idcanbo"=>$p['canbo'],
                ])->exec("permission")){
                    die ("success");
                }else{
                    die ("Có lỗi không xác định xãy ra.");
                }
            }
        }
        if($ajax=="lockuserperm"){
            $perm = buildSearch(["id"=>$p['id']])->exec("permission");
            if(!$perm){
                die("notexist");
            }
            $v = $perm->islocked;
            if($v=="1"){
                buildUpdate(["id"=>$p['id']],["islocked"=>0])->exec("permission");
                die("unlocked");
            }else{
                buildUpdate(["id"=>$p['id']],["islocked"=>1])->exec("permission");
                die("locked");
            }
        }
        if($ajax=="deluserperm"){
            $perm =  buildSearch(["id"=>$p['id']])->exec("permission");
            if(!$perm){
                die("notexist");
            }
            buildDelete(["id"=>$p['id']])->exec("permission");
            die("success");
        }
        if($ajax=="addmonhoc"){
            $name = $p['name'] ?? "";
            $nganh = $p['nganh'] ?? "";
            $mamonhoc = $p['mamonhoc'] ?? "";
            $chuongtrinh = explodeFilter("///",$p['chuongtrinh']);
            if(!$name || !$nganh || !$mamonhoc){
                die("Vui lòng kiểm tra nội dung.");
            }
            $insert =buildInsert([
                "name"=>$name,
                "nganh"=>$nganh,
                "mamonhoc"=>$mamonhoc,
                "chuongtrinh"=>$chuongtrinh,
            ])->asJson("chuongtrinh")->exec("monhoc");
            if($insert){
                die("Thêm thành công");
            }else{
                die("Có lỗi xãy ra");
            }
        }
        if($ajax=="updatemonhoc"){
            $name = $p['name'] ?? "";
            $nganh = $p['nganh'] ?? "";
            $chuongtrinh = explodeFilter("///",$p['chuongtrinh'] ?? "");
            if(!$name && !$nganh){
                die("Vui lòng kiểm tra nội dung.");
            }
            $update =buildUpdate(["id"=>(int)$p['id']],[
                "name"=>$name,
                "nganh"=>$nganh,
                "chuongtrinh"=>$chuongtrinh,
            ])->asJson("chuongtrinh")->exec("monhoc");
            if($update){
                die("Sửa thành công");
            }else{
                die("Có lỗi xãy ra");
            }
        }
        if($ajax=="deletemonhoc"){
            $id = $p['id'];
            $rs = buildDelete(["id"=>(int)$id])->exec("monhoc");
            if($rs){
                die("success");
            }else{
                die("Có lỗi xảy ra.");
            }
        }
        if($ajax=="addcanbo"){
            $account = $p['account'] ?? "";
            $name = $p['name'] ?? "";
            $password = $p['password'] ?? "";
            $donvi = $p['donvi'] ?? "";
            $email = $p['email'] ?? "";
            $avatar = $p['avatar'] ?? "";
            if(!$name || !$donvi || !$password){
                die("Vui lòng kiểm tra tên, đơn vị, hoặc mật khẩu.");
            }
            if(strlen($account) < 4){
                die("Độ dài tài khoản tối thiểu là 4 ký tự.");
            }
            if(preg_match("#[^a-zA-Z0-9_@\.\-]#",$account)){
                die("Tên tài khoản đăng nhập không thể chứa ký tự đặc biệt.");
            }
            $password = hashPassword($account,$password);
            $insert =buildInsert([
                "account"=>$account,
                "password"=>$password,
                "email"=>$email,
                "avatar"=>$avatar,
                "name"=>$name,
                "donvi"=>$donvi,
                "isaccept"=>1
            ])->clean()->exec("users");
            if($insert){
                die("Thêm thành công");
            }else{
                die("Có lỗi xãy ra");
            }
        }
        if($ajax=="updatecanbo"){
            $id = $p['id'];
            if(isAdmin($id)){
                die("Đối phương có quyền hạn ngang cấp với bạn.");
            }
            $name = $p['name'] ?? "";
            $donvi = $p['donvi'] ?? "";
            $email = $p['email'] ?? "";
            $avatar = $p['avatar'] ?? "";
            if(!$name || !$donvi){
                die("Vui lòng kiểm tra tên hoặc đơn vị.");
            }
            $update =buildUpdate(["id"=>(int)$id],[
                "name"=>$name,
                "donvi"=>$donvi,
                "email"=>$email,
                "avatar"=>$avatar,
            ])->clean()->exec("users");
            if($update){
                die("Sửa thành công");
            }else{
                die("Có lỗi xãy ra");
            }
        }
        if($ajax=="deletecanbo"){
            $id = $p['id'];
            if(isAdmin($id)){
                die("Đối phương có quyền hạn ngang cấp với bạn.");
            }
            $rs = buildDelete(["id"=>(int)$id])->exec("users");
            if($rs){
                die("success");
            }else{
                die("Có lỗi xảy ra.");
            }
        }
    }else{
        die("Bạn chưa đăng nhập.");
    }
} catch (\Exception $e) {
    $output += $e;
}
echo $output;
?>