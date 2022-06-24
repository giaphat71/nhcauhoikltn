<?php
$output = "";
$p = $_POST;
$ajax = $p['ajax'] ?? "";

$history = getModule("history");
try {
    if($ajax=="login"){
        // băm mật khẩu
        $password = hashPassword($p['account'],$p['password']);
        $u = buildSearch([
            "account" => $p['account'],
            "password" => $password
        ])->exec("users");
        // xác thực tài khoản
        if($u==null){
            die("Đăng nhập thất bại, kiểm tra tài khoản hoặc mật khẩu");
        }
        sessionSet([
            'isLoggedIn'=>true,
            'id'=>$u->id,
            'name'=>$u->name,
            'isAdmin'=>$u->isadmin == "1",
            'avatar'=>$u->avatar
        ]);
        if($_SESSION['isAdmin']){
            // điều hướng về trang admin
            $history->Ajax("Quản trị {$u->name} đăng nhập vào hệ thống.");
            die("asuccess");
        }
        // điều hướng về trang cán bộ
        $history->Ajax("Giảng viên {$u->name} đăng nhập vào hệ thống.");
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
        $insert = buildInsert(['account'=>$account, 'password'=>$password, "name"=>$name,"email"=>$email,"donvi"=>$donvi])->exec("users");
        if($insert){
            $history->Ajax("{$name} đăng ký tài khoản mới, [url=/admin/canbo]Xem[/url].");
            die("success");
        }else{
            die("Lỗi không xác định");
        }
    }
    if(checkLogin()) {
        requireAdmin();
        $uname = $_SESSION['name'];
        $uid = $_SESSION['id'];
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
            requirePerm();
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
                    $user = getUser($p['canbo']);
                    $monhoc = getMonhoc($p['mh']);
                    $history->Ajax("{$uname} đã cho [{$user->id}]{$user->name} quyền truy cập môn học {$monhoc->name}.");
                    die ("success");
                }else{
                    die ("Có lỗi không xác định xãy ra.");
                }
            }
        }
        if($ajax=="lockuser"){
            requirePerm();
            $u = buildSearch(["id"=>(int)$p['id']])->exec("users");
            if($u){
                if($u->isadmin=="1"){
                    die("Không thể khóa quyền quản trị.");
                }
                $user = getUser($p['id']);
                if($u->islocked=="1"){
                    if(buildUpdate(["id"=>(int)$p['id']],["islocked"=>0])->exec("users")){
                        $history->Ajax("{$uname} đã mở khóa tài khoản [{$user->id}]{$user->name}.");
                        die("success");
                    }else{
                        die("Lỗi không xác định.");
                    }
                }else{
                    if(buildUpdate(["id"=>(int)$p['id']],["islocked"=>1])->exec("users")){
                        $history->Ajax("{$uname} đã khóa tài khoản [{$user->id}]{$user->name}.");
                        die("success");
                    }else{
                        die("Lỗi không xác định.");
                    }
                }
            }else{
                die("Không tìm thấy tài khoản.");
            }
        }
        if($ajax=="lockuserperm"){
            requirePerm();
            $perm = buildSearch(["id"=>$p['id']])->exec("permission");
            if(!$perm){
                die("notexist");
            }
            $v = $perm->islocked;
            $user = getUser($perm->idcanbo);
            $monhoc = getMonhoc($perm->idmonhoc);
            if($v=="1"){
                buildUpdate(["id"=>$p['id']],["islocked"=>0])->exec("permission");
                $history->Ajax("{$uname} đã mở khóa quyền truy cập môn học {$monhoc->name} của [{$user->id}]{$user->name}.");
                die("unlocked");
            }else{
                buildUpdate(["id"=>$p['id']],["islocked"=>1])->exec("permission");
                $history->Ajax("{$uname} đã khóa quyền truy cập môn học {$monhoc->name} của [{$user->id}]{$user->name}.");
                die("locked");
            }
        }
        if($ajax=="deluserperm"){
            requirePerm();
            $perm =  buildSearch(["id"=>$p['id']])->exec("permission");
            if(!$perm){
                die("notexist");
            }
            $user = getUser($perm->idcanbo);
            $monhoc = getMonhoc($perm->idmonhoc);
            $history->Ajax("{$uname} đã xóa quyền truy cập môn học {$monhoc->name} của [{$user->id}]{$user->name}.");
            buildDelete(["id"=>$p['id']])->exec("permission");
            die("success");
        }
        if($ajax=="addmonhoc"){
            requirePerm();
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
                $history->Ajax("{$uname} đã thêm môn học [url=/admin/monhoc/$insert]{$name}[/url].");
                die("Thêm thành công");
            }else{
                die("Có lỗi xãy ra");
            }
        }
        if($ajax=="updatemonhoc"){
            requirePerm();
            $name = $p['name'] ?? "";
            $nganh = $p['nganh'] ?? "";
            $chuongtrinh = explodeFilter("///",$p['chuongtrinh'] ?? "");
            if(!$name && !$nganh){
                die("Vui lòng kiểm tra nội dung.");
            }
            $monhoc = getMonhoc($p['id']);
            $monhoc->table="monhoc";
            $rollbackdata = json_encode($monhoc);
            $update =buildUpdate(["id"=>(int)$p['id']],[
                "name"=>$name,
                "nganh"=>$nganh,
                "chuongtrinh"=>$chuongtrinh,
            ])->asJson("chuongtrinh")->exec("monhoc");
            if($update){
                
                $history->Ajax("{$uname} đã sửa môn học [url=/admin/monhoc/{$p['id']}]{$monhoc->name}[/url].",$rollbackdata);
                die("Sửa thành công");
            }else{
                die("Có lỗi xãy ra");
            }
        }
        if($ajax=="deletemonhoc"){
            requirePerm();
            $id = $p['id'];
            $monhoc = getMonhoc($id);
            $monhoc->table = "monhoc";
            $rollbackdata = json_encode($monhoc);
            $rs = buildDelete(["id"=>(int)$id])->exec("monhoc");
            if($rs){
                $history->Ajax("{$uname} đã xóa môn học [url=/admin/monhoc/{$id}]{$monhoc->name}[/url].",$rollbackdata);
                die("success");
            }else{
                die("Có lỗi xảy ra.");
            }
        }
        if($ajax=="addcanbo"){
            requirePerm();
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
            
            //remove xss
            $name = htmlspecialchars($name);
            $donvi = htmlspecialchars($donvi);
            $email = htmlspecialchars($email);
            $avatar = htmlspecialchars($avatar);

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
                $history->Ajax("{$uname} đã thêm tài khoản [url=/admin/canbo/{$insert}]{$name}[/url].");
                die("Thêm thành công");
            }else{
                die("Có lỗi xãy ra");
            }
        }
        if($ajax=="updatecanbo"){
            requirePerm();
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
                $history->Ajax("{$uname} đã sửa tài khoản [url=/admin/canbo/{$id}]{$name}[/url].");
                die("Sửa thành công");
            }else{
                die("Có lỗi xãy ra");
            }
        }
        if($ajax=="deletecanbo"){
            requirePerm();
            $id = $p['id'];
            if(isAdmin($id)){
                die("Đối phương có quyền hạn ngang cấp với bạn.");
            }
            $canbo = getUser($id);
            $canbo->table = "users";
            $rollbackdata = json_encode($canbo);
            $rs = buildDelete(["id"=>(int)$id])->exec("users");
            if($rs){
                $history->Ajax("{$uname} đã xóa tài khoản {$canbo->name}.",$rollbackdata);
                die("success");
            }else{
                die("Có lỗi xảy ra.");
            }
        }
        if($ajax == "addtieuchi"){
            requirePerm();
            $name = $p['name'] ?? "";
            $type = $p['type'] ?? "";
            $valuerange = "";
            if(!$name || !$type){
                die("Vui lòng kiểm tra nội dung.");
            }
            if(strlen($name) < 4){
                die("Tên tiêu chí quá ngắn.");
            }
            if(!in_array($type,["have","string","number","array"])){
                die("Kiểu dữ liệu không hợp lệ.");
            }
            if($type=="number" || $type=="array"){
                $valuerange = json_decode($p['valuerange'] ?? "");
            }
            $slugname = to_slug($name);
            $insert =buildInsert([
                "slugname"=>$slugname,
                "name"=>$name,
                "type"=>$type,
                "valuerange"=>$valuerange
            ])->asJson("valuerange")->clean()->exec("tieuchi");
            if($insert){
                $history->Ajax("{$uname} đã thêm tiêu chí [url=/admin/tieuchi]{$name}[/url].");
                die("Thêm thành công");
            }else{
                die("Có lỗi xãy ra");
            }
        }
        if($ajax=="savetieuchi"){
            requirePerm();
            $id = $p['id'] ?? 0;
            if($id < 1){
                die("Id không hợp lệ.");
            }
            
            $tc = buildSearch(["id"=>(int)$id])->exec("tieuchi");
            
            if(!$tc){
                die("Tiêu chí không tồn tại.");
            }
            if($tc->type!="number"){
                die("Kiểu dữ liệu không hỗ trợ thay đổi.");
            }
            $newvaluerange = json_decode($p['valuerange'] ?? "");
            
            $update = buildUpdate(["id"=>(int)$id],[
                "valuerange"=>$newvaluerange
            ])->asJson("valuerange")->clean()->exec("tieuchi");
            
            if($update){
                $history->Ajax("{$uname} đã sửa tiêu chí [url=/admin/tieuchi]{$tc->name}[/url].");
                die("Sửa thành công");
            }
            die("Lỗi không xác định");
        }
        if($ajax=="saveInfo"){
            $newname = $p['name'] ?? "";
            $newnganh = $p['nganh'] ?? "";
            $newavatar = $p['avatar'] ?? "";

            if(!$newname || !$newnganh){
                die("Vui lòng kiểm tra tên hoặc ngành.");
            }
            if(strlen($newname) < 4){
                die("Tên quá ngắn.");
            }
            // remove xss
            $newname = htmlspecialchars($newname);
            $newnganh = htmlspecialchars($newnganh);
            $newavatar = htmlspecialchars($newavatar);
            $update = buildUpdate(["id"=>(int)$_SESSION['id']],[
                "name"=>$newname,
                "nganh"=>$newnganh,
                "avatar"=>$newavatar
            ])->clean()->exec("users");
            if($update){
                $history->Ajax("{$uname} đã sửa thông tin cá nhân.");
                die("Sửa thành công");
            }else{
                die("Dữ liệu chưa thay đổi");
            }
        }
        if($ajax == "changePass"){
            $oldpass = $p['oldPass'] ?? "";
            $newpass = $p['newPass'] ?? "";
            $newpass2 = $p['newPass2'] ?? "";
            if(!$oldpass || !$newpass || !$newpass2){
                die("Vui lòng kiểm tra lại thông tin.");
            }
            if($newpass != $newpass2){
                die("Mật khẩu không khớp.");
            }
            $user = buildSearch(["id"=>(int)$_SESSION['id']])->exec("users");
            if(!$user){
                die("Không tìm thấy thông tin.");
            }
            $password = hashPassword($user->account,$oldpass);
            $u = buildSearch([
                "account" => $p['account'],
                "password" => $password
            ])->exec("users");
            if($u==null){
                die("Mật khẩu không chính xác.");
            }
            $newpass = hashPassword($user->account,$newpass);
            $update = buildUpdate(["id"=>(int)$_SESSION['userid']],[
                "password"=>$newpass
            ])->clean()->exec("users");
            if($update){
                $history->Ajax("{$uname} đã thay đổi mật khẩu.");
                die("success");
            }
            die("Lỗi không xác định");
        }
        if($ajax == "acceptuser"){
            requirePerm();
            $id = $p['id'] ?? 0;
            if($id < 1){
                die("Id không hợp lệ.");
            }
            $user = buildSearch(["id"=>(int)$id])->exec("users");
            if(!$user){
                die("Không tìm thấy thông tin.");
            }
            $update = buildUpdate(["id"=>(int)$id],[
                "isaccept"=>1
            ])->clean()->exec("users");
            if($update){
                $history->Ajax("{$uname} đã chấp nhận tài khoản [url=/admin/canbo/$id]{$user->name}[/url].");
                die("success");
            }
        }
        if($ajax=="saveperm"){
            requirePerm("grant");
            $baseperm = new stdClass();
            $perms = $p['perms'];
            if($perms){
                $perms = json_decode($perms);
                objectAssign($baseperm,$perms);
            }
            $target = (int)$p['id'];
            $u = buildSearch(["id"=>(int)$target])->exec("users");
            if(!$u){
                die("Không tìm thấy thông tin.");
            }
            $group = $p['group'];
            $grant = $p['grant'];
            $admin = $p['admin'];
            if(!$group && $perms){
                die("Vui lòng chọn nhóm hoặc ít nhất một quyền.");
            }
            if($u->isadmin == 1){
                // kiểm tra tài khoản phải id 1 admin không
                if($uid != 1){
                    die("Không thể xóa quyền admin.");
                }
            }
            if($grant === "true"){
                $baseperm->grant = true;
            }
            if((int)$group > 0){
                $baseperm->group = (int)$group;
            }
            buildUpdate(["id"=>(int)$target],[
                "perm"=>$baseperm
            ])->exec("users");
            if($admin === "true"){
                buildUpdate(["id"=>(int)$target],[
                    "isadmin"=>1
                ])->exec("users");
            }
            $history->Ajax("{$uname} đã sửa quyền cho [url=/admin/canbo/{$target}/quyen]{$u->name}[/url].");
            die("success");
        }
        if($ajax == "rollback"){
            requirePerm("rollback");
            $id = $p['id'] ?? 0;
            if($id < 1){
                die("Id không hợp lệ.");
            }
            $rollbackrecord = buildSearch(["id"=>(int)$id])->exec("history");
            if(!$rollbackrecord){
                die("Không tìm thấy thông tin.");
            }
            $rollback = json_decode($rollbackrecord->datarollback,true);
            if(!$rollback){
                die("Không tìm thấy thông tin.");
            }
            $table = $rollback['table'];
            unset($rollback['table']);
            $rowid = $rollback['id'];
            $oldrow = buildSearch(["id"=>(int)$rowid])->exec($table);
            if(!$oldrow){
                $insert = buildInsert($rollback)->exec($table);
            }else{
                unset($rollback['id']);
                $update = buildUpdate(["id"=>(int)$rowid],$rollback)->exec($table);
            }
            if($insert || $update){
                die("success");
            }
            die("success");
        }
    }else{
        die("Bạn chưa đăng nhập.");
    }
} catch (\Exception $e) {
    $output += $e;
}
die($output);
?>