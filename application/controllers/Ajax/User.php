<?php

$ajax = $_POST['ajax'] ?? "";
$p = $_POST;

checkLogin();
checkLock();
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
    return $object;
}
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
    if(!$tags){
        die("Dữ liệu xảy ra lỗi.");
    }
    $data = json_decode($p['data'],true);
    $idmonhoc = $p['idmonhoc'];
    checkAccessToMonhoc($idmonhoc);
    if($type=="choosethebest"){
        $data = validateCtb($data);
    }elseif($type=="askandanswer"){

    }elseif($type=="checkalltrue"){

    }elseif($type=="connectpair"){

    }elseif($type=="putinplace"){

    }else{
        die("Kiểu câu hỏi này chưa hỗ trợ.");
    }
    $insert = buildInsert([
        "type"=>$type,
        "idmonhoc"=>$idmonhoc,
        "author"=>$_SESSION['id'],
        "text"=>$text,
        "tieuchi"=>$tags,
        "data"=>$data,])->asJson("tieuchi")->asJson("data")->exec("cauhoi");
    if($insert){
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
        $update = buildUpdate(["id"=>$matrixid],
            ["name"=>$name,
            "description"=>$description,
            "totalpoint"=>$totalpoint,
            "data"=>$matrixdata,
            "questioncount"=>$qcount,
            "lastupdate"=>["CURRENT_TIMESTAMP()"]])->exec("matrix");
        die("updated");
    }else{
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
        die("success-".$insert);
    }
}