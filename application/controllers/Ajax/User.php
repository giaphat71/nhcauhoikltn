<?php

$ajax = $_POST['ajax'] ?? "";
$p = $_POST;
$history = getModule("history");
// kiểm tra đăng nhập
checkLogin();
// kiểm tra tình trạng khóa tài khoản
checkLock();
$uname = $_SESSION['name'];
$uid = (int)$_SESSION['id'];
// kiểm tra dữ liệu đầu vào của câu hỏi
function validateCtb($p){
    $rightanswer = htmlwithoutxss($p['rightanswer']);
    if(strlen($rightanswer) < 1){
        die("Dữ liệu về đáp án đúng không hợp lý.");
    }
    $answer = $p['answer'];
    if(!is_array($answer)){
        die("Dữ liệu về đáp án không hợp lý.");
    }
    $rs = [];
    foreach($answer as $ans){
        if(!is_string($ans)){
            die("Dữ liệu về đáp án không hợp lý.");
        }
        $ans = htmlwithoutxss($ans);
        if(strlen($ans) < 1){
            die("Dữ liệu về đáp án không hợp lý.");
        }
        $rs[] = $ans;
    }
    if(count($rs) < 2){
        die("Dữ liệu về đáp án không hợp lý.");
    }
    $object = (object)["answer"=>$rs,"rightanswer"=>$rightanswer];
    if(isset($p['lockposition']) && $p['lockposition'] == "true"){
        $object->lockposition = true;
    }
    return $object;
}
function validateCat($p){
    $rightanswer = $p['rightanswer'];
    if(!is_array($rightanswer)){
        die("Dữ liệu về đáp án đúng không hợp lý.");
    }
    $rightans = [];
    foreach($rightanswer as $ans){
        if(!is_string($ans)){
            die("Dữ liệu về đáp án đúng không hợp lý.");
        }
        $ans = htmlwithoutxss($ans);
        if(strlen($ans) < 1){
            die("Dữ liệu về đáp án không hợp lý.");
        }
        $rightans[] = $ans;
    }
    if(count($rightans) < 1){
        die("Dữ liệu về đáp án đúng không hợp lý.");
    }
    $answer = $p['answer'];
    if(!is_array($answer)){
        die("Dữ liệu về đáp án không hợp lý.");
    }
    $rs = [];
    foreach($answer as $ans){
        if(!is_string($ans)){
            die("Dữ liệu về đáp án không hợp lý.");
        }
        $ans = htmlwithoutxss($ans);
        if(strlen($ans) < 1){
            die("Dữ liệu về đáp án không hợp lý.");
        }
        $rs[] = $ans;
    }
    if(count($rs) < 2){
        die("Dữ liệu về đáp án không hợp lý.");
    }
    $object = (object)["answer"=>$rs,"rightanswer"=>$rightans];
    return $object;
}
function validatePip($text){
    $text =html_entity_decode($text);
    $ans = preg_match_all("#<strong>(.*?)</strong>#",$text,$matches);
    if($ans == 0){
        die("Dữ liệu về đáp án không hợp lý.");
    }
    $answerlist = [];
    foreach($matches[1] as $ans){
        $ans = htmlwithoutxss($ans);
        if(strlen($ans) < 1){
            die("Dữ liệu về đáp án không hợp lý.");
        }
        $answerlist[] = $ans;
    }
    if(count($answerlist) < 1){
        die("Dữ liệu về đáp án không hợp lý.");
    }
    $object = (object)["answer"=>$answerlist];
    return $object;
}
function validateAas($p){
    $answer = $p['answer'];
    if(strlen($answer) < 1){
        die("Dữ liệu về đáp án trống.");
    }
    $answer = htmlwithoutxss($answer);
    $object = (object)["answer"=>$answer];
    return $object;
}
function validateCp($p)
{
    $ansleft = $p['ansleft'];
    if(!is_array($ansleft)){
        die("Dữ liệu về đáp án không hợp lý.");
    }
    $ansleft = array_map("htmlwithoutxss",$ansleft);
    $ansright = $p['ansright'];
    if(!is_array($ansright)){
        die("Dữ liệu về đáp án không hợp lý.");
    }
    $ansright = array_map("htmlwithoutxss",$ansright);
    $pair = $p['pair'];
    if(!is_array($pair)){
        die("Dữ liệu về đáp án không hợp lý.");
    }
    foreach($pair as $pa){
        $l = $pa[0];
        $r = $pa[1];
        if(!is_string($l)){
            die("Dữ liệu về đáp án không hợp lý.");
        }
        if(!is_string($r)){
            die("Dữ liệu về đáp án không hợp lý.");
        }
        if(!in_array($l,$ansleft)){
            die("Dữ liệu về đáp án không hợp lý.");
        }
        if(!in_array($r,$ansright)){
            die("Dữ liệu về đáp án không hợp lý.");
        }
    }
    $object = (object)["ansleft"=>$ansleft,"ansright"=>$ansright,"pair"=>$pair];
    return $object;
}
// kiểm tra dữ liệu đầu vào của ma trận
function validateMatrix($matrix){
    $count = 0;
    $tagmanager = getModule("tags");
    foreach($matrix as $group){
        foreach($group->tags as $tag){
            if(!$tagmanager->isValid($tag)){
                die("Dữ liệu xảy ra lỗi.");
            }
        }
        $count += $group->count;
    }
    return $count;
}
if($ajax == "addquestion"){
    $type = $p['type'];
    $text = $p['text'];
    $tags = json_decode($p['tags']);
    $tags = getModule("tags")->validate($tags);
    if(is_array($tags) && count($tags)>0){
        if(!$tags){
            die("Cần gắn nhãn cho câu hỏi.");
        }
    }else{
        $tags = [];
    }
    $data = json_decode($p['data'],true);
    $idmonhoc = $p['idmonhoc'];
    checkAccessToMonhoc($idmonhoc);
    if($type=="choosethebest"){
        $data = validateCtb($data);
    }elseif($type=="askandanswer"){
        $data = validateAas($data);
    }elseif($type=="checkalltrue"){
        $data = validateCat($data);
    }elseif($type=="connectpair"){
        $data = validateCp($data);
    }elseif($type=="putinplace"){
        $data = validatePip($text);
    }else{
        die("Kiểu câu hỏi này chưa hỗ trợ.");
    }
    $text = htmlwithoutxss($text);
    $insert = buildInsert([
        "type"=>$type,
        "idmonhoc"=>$idmonhoc,
        "author"=>$_SESSION['id'],
        "text"=>$text,
        "tieuchi"=>$tags,
        "data"=>$data,])->asJson("tieuchi")->asJson("data")->exec("cauhoi");
    if($insert){
        $monhoc = getMonhoc($idmonhoc);
        $history->Ajax("$uname thêm 1 [url=/canbo/cauhoi/$idmonhoc/$insert]câu hỏi[/url] cho môn học {$monhoc->name}.");
        die("Thành công");
    }else{
        die("Lỗi không xác định");
    }
}

if($ajax == "updatequestion"){
    $idquest = $p['idquest'];
    if(isAdmin()){
        $cauhoi = buildSearch(["id"=>$idquest])->exec("cauhoi");
    }else{
        $cauhoi = buildSearch(["id"=>$idquest,"author"=>$_SESSION['id']])->exec("cauhoi");
    }
    if(!$cauhoi){
        die("Câu hỏi không tồn tại.");
    }
    $type = $p['type'];
    $text = $p['text'];
    $tags = json_decode($p['tags']);
    $tags = getModule("tags")->validate($tags);
    if(is_array($tags) && count($tags)>0){
        if(!$tags){
            die("Cần gắn nhãn cho câu hỏi.");
        }
    }else{
        $tags = [];
    }
    $data = json_decode($p['data'],true);
    $idmonhoc = $p['idmonhoc'];
    checkAccessToMonhoc($idmonhoc);
    if($type=="choosethebest"){
        $data = validateCtb($data);
    }elseif($type=="askandanswer"){
        $data = validateAas($data);
    }elseif($type=="checkalltrue"){
        $data = validateCat($data);
    }elseif($type=="connectpair"){
        $data = validateCp($data);
    }elseif($type=="putinplace"){
        $data = validatePip($text);
    }else{
        die("Kiểu câu hỏi này chưa hỗ trợ.");
    }
    $text = htmlwithoutxss($text);
    $insert = buildUpdate(["id"=>$idquest],[
        "type"=>$type,
        "text"=>$text,
        "tieuchi"=>$tags,
        "data"=>$data,])->asJson("tieuchi")->asJson("data")->exec("cauhoi");
    if($insert){
        $history->Ajax("$uname sửa 1 [url=/canbo/cauhoi/$idmonhoc/$idquest]câu hỏi[/url].");
        die("Thành công");
    }else{
        die("Dữ liệu câu hỏi chưa có thay đổi.");
    }
}
if($ajax == "addquestions"){
    $type = "choosethebest";
    $quests = json_decode($p['quests']);
    $idmonhoc = $p['idmonhoc'];
    checkAccessToMonhoc($idmonhoc);
    $monhoc = getMonhoc($idmonhoc);
    $successcount = 0;
    $insertids = [];
    for($i=0;$i<count($quests);$i++){
        $quest = $quests[$i];
        $text = $quest->text;
        $tags = $quest->tags;
        $tags = getModule("tags")->validate($tags);
        if(is_array($tags) && count($tags)>0){
            if(!$tags){
                die("Cần gắn nhãn cho câu hỏi.");
            }
        }else{
            $tags = [];
        }
        
        $data = validateCtb((array)$quest->data);
        $insert = buildInsert([
            "type"=>$type,
            "idmonhoc"=>$idmonhoc,
            "author"=>$_SESSION['id'],
            "text"=>$text,
            "tieuchi"=>$tags,
            "data"=>$data,])->asJson("tieuchi")->asJson("data")->exec("cauhoi");
        if($insert){
            $insertids[] = ["[url=/canbo/cauhoi/$idmonhoc/$insert]".$insert."[/url]"];
            $successcount++;
        }else{
            if($successcount>0)
            $history->Ajax("$uname thêm $successcount câu hỏi cho môn học {$monhoc->name}: ".implode(", ",$insertids));
            die("Lỗi không xác định");
        }
    }
    $history->Ajax("$uname thêm $successcount câu hỏi cho môn học {$monhoc->name}: ".implode(", ",$insertids));
    echo "Thành công nhập $successcount câu hỏi";
}
if($ajax == "deletequest"){
    $id = $p['id'];
    $idmonhoc = $p['idmonhoc'];
    checkAccessToMonhoc($idmonhoc);
    requirePerm();
    $rollbackdata = buildSearch(["id"=>$id])->exec("cauhoi");
    if(!$rollbackdata){
        die("Câu hỏi không tồn tại.");
    }
    $rollbackdata->table = "cauhoi";
    $rollbackdata = json_encode($rollbackdata);
    $delete = buildDelete(["id"=>$id,"author"=>$_SESSION['id']])->exec("cauhoi");
    if($delete){
        $history->Ajax("$uname xóa 1 [url=/canbo/cauhoi/$idmonhoc/$id]câu hỏi[/url].",$rollbackdata);
        die("Thành công");
    }else{
        die("Lỗi không xác định");
    }
}
if($ajax == "saveMatrix"){
    $matrixid = (int)($p['idmatrix'] ?? 0);
    $matrixdata = json_decode($p['matrix']);
    $idmonhoc = (int)$p['id'];
    $name = $p['name'] ?? "";
    $description = $p['description'] ?? "";
    $description = strip_tags($description);
    $name = strip_tags($name);
    $qcount = validateMatrix($matrixdata);
    checkAccessToMonhoc($idmonhoc);
    $monhoc = getMonhoc($idmonhoc);
    if(strlen($name) < 4){
        die("Tên quá ngắn.");
    }
    if($qcount < 1){
        die("Không có câu hỏi nào.");
    }
    $totalpoint = $p['totalpoint'] ?? 0;
    if($matrixid > 0){
        $matrix = buildSearch(["id"=>$matrixid])->exec("matrix");
        if(!$matrix){
            die("Không tìm thấy ma trận câu hỏi.");
        }
        if($matrix->author != $_SESSION['id'] && !isAdmin()){
            die("Bạn không có quyền sửa ma trận câu hỏi này.");
        }
        requirePerm("updatematrix");
        $update = buildUpdate(["id"=>$matrixid],
            ["name"=>$name,
            "description"=>$description,
            "totalpoint"=>$totalpoint,
            "data"=>$matrixdata,
            "questioncount"=>$qcount,
            "lastupdate"=>["CURRENT_TIMESTAMP()"]])->exec("matrix");
        $rollbackdata->table = "matrix";
        $rollbackdata = json_encode($matrix);
        if($update){
            $history->Insert("updatematrix","$uname sửa 1 [url=/canbo/matrix/$idmonhoc/$matrixid]ma trận câu hỏi[/url].",$rollbackdata);
        }
        die("updated");
    }else{
        requirePerm("addmatrix");
        $insert = buildInsert(
            ["idmonhoc"=>$idmonhoc,
            "author"=>$_SESSION['id'],
            "name"=>$name,
            "description"=>$description,
            "totalpoint"=>(int)$totalpoint,
            "data"=>$matrixdata,
            "questioncount"=>$qcount])->exec("matrix");
        if(!$insert){
            die("Lỗi không xác định.");
        }
        $history->Insert("addmatrix","$uname thêm 1 [url=/canbo/matrix/$idmonhoc/$insert]ma trận câu hỏi[/url] cho môn học {$monhoc->name}.");
        die("success-".$insert);
    }
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
        $_SESSION['name'] = $newname;
        $_SESSION['avatar'] = $newavatar;
        $rollbackdata = json_encode(["id"=>$uid,"name"=>$_SESSION['name'],"avatar"=>$_SESSION['avatar'],"nganh"=>$_SESSION['nganh'],"table"=>"users"]);
        $history->Ajax("$uname đã thay đổi thông tin cá nhân.",$rollbackdata);
        die("success");
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
    $update = buildUpdate(["id"=>(int)$_SESSION['id']],[
        "password"=>$newpass
    ])->clean()->exec("users");
    if($update){
        $history->Ajax("$uname đã thay đổi mật khẩu.");
        die("success");
    }
    die("Lỗi không xác định");
}
if($ajax=="saveasroot"){
    $idchallenge = $p['idchallenge'] ?? 0;
    $idresult = $p['idresult'] ?? "";
    $idmatrix = $p['idmatrix'] ?? 0;
    $idmonhoc = $p['idmonhoc'] ?? 0;
    checkAccessToMonhoc($idmonhoc);
    requirePerm();
    if( !$idresult || !$idmatrix){
        die("Không tìm thấy thông tin.");
    }
    $mh = buildSearch(["id"=>$idmonhoc])->exec("monhoc");
    if(!$mh){
        die("Không tìm thấy thông tin môn học.");
    }
    $challenge = buildSearch(["id"=>$idresult])->exec("matrixresult");
    if(!$challenge){
        die("Không tìm thấy thông tin kết quả ma trận.");
    }
    $exist = buildSearch(["idmonhoc"=>$idmonhoc,
                        "idresult"=>$idresult,
                        "idmatrix"=>$idmatrix,
                        "idchallenge"=>$idchallenge,
                        ])->exec("matrixchallenge");
    if($exist){
        die("Đã tồn tại đề gốc tương tự.");
    }
    $insert = buildInsert(
        [
            "idmonhoc"=>$idmonhoc,
            "idresult"=>$idresult,
            "idmatrix"=>$idmatrix,
            "idchallenge"=>$idchallenge,
            "author"=>$_SESSION['id']
        ])->exec("rootmatrix");
    if(!$insert){
        $history->Ajax("$uname đã chọn đề [url=/canbo/runmatrix/$idmonhoc/$idmatrix/?idresult=$idresult/]ma trận[/url] làm đề gốc cho môn học {$mh->name}.");
        die("Lỗi không xác định.");
    }
    die("success");
}