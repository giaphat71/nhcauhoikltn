<?php
enableDebug();
class QuestionMatrix{
    public $questcount = 0;
    public $questions = [];
    public $idmonhoc = 0;
    public function loadAll($idmonhoc) {
        //tải toàn bộ câu hỏi vào bộ nhớ
        $this->questions = buildSearch(["idmonhoc"=>$idmonhoc])->exec("cauhoi");
        $this->idmonhoc = $idmonhoc;
    }
    public function load($idmonhoc, $author = 0, $allowshare = false) {
        //tải toàn bộ câu hỏi phù hợp điều kiện vào bộ nhớ
        $this->idmonhoc = $idmonhoc;
        if($author==0){
            $author = $_SESSION['id'];
        }
        if($allowshare){
            $this->questions = buildSearch([
                "idmonhoc"=>$idmonhoc,
                "||"=>
                    [
                        ["author",(int)$author],
                        ["isshare",1]
                    ]
                ])->exec("cauhoi");
        }else{
            $this->questions = buildSearch(["idmonhoc"=>$idmonhoc,"author"=>(int)$author])->exec("cauhoi");
        }
        
    }
    public function loadList($listids)
    {
        // tải các câu hỏi theo id
        $this->questions = buildSearch(["id"=>["exp","IN (".implode(",",$listids).")"]])->exec("cauhoi");
    }
    public function matchTag($tagl,$tagr){
        // kiểm tra tiêu chí
        return $tagl->slugname == $tagr->slugname && $tagl->value == $tagr->value;
    }
    public function matchTagAll($tags,$tagsmatch){
        // kiểm tra các tiêu chí của câu hỏi so với điều kiện
        foreach($tagsmatch as $tag){
            $found = false;
            foreach($tags as $tagmatch){
                if($this->matchTag($tag,$tagmatch)){
                    $found = true;
                    break;
                }
            }
            if(!$found){
                return false;
            }
        }
        return true;
    }
    public function filter($matchs){
        // lọc các câu hỏi phù hợp tiêu chí
        $questions = [];
        foreach($this->questions as $q){
            if($this->matchTagAll($q->tieuchi,$matchs)){
                $questions[] = $q;
            }
        }
        return $questions;
    }
    public function getQuestFromQuery($query,$matched){
        //lọc các câu hỏi phù hợp tiêu chí
        $count =$query->count;
        $tagset = $query->tags;
        $questmatched = $this->filter($tagset);
        $questions = [];
        if(count($questmatched) < $count){
            return false;
        }
        $retry = 0;
        while (count($questions) < $count){
            // chọn câu hỏi ngẫu nhiên từ các kết quả phù hợp
            $quest = $questmatched[mt_rand(0,count($questmatched)-1)];
            if(!in_array($quest,$questions) && !in_array($quest,$matched)){
                $questions[] = $quest;
                $retry = 0;
            }else{
                $retry ++;
                // quá 50 lần không tìm ra câu hỏi phù hợp thì ngừng lại và trả về false
                if($retry > 50){
                    return false;
                }
            }
        }
        return $questions;
    }
    public function generateChallenge($idmatrix,$matrix,$count =1){
        // tạo ra các kết quả ma trận theo ma trận đề
        $challenges = [];
        for($i=0;$i<$count;$i++){
            $questions = [];
            foreach($matrix as $query){
                $found = $this->getQuestFromQuery($query,$questions);
                if(!$found){
                    return false;
                }
                $questions = array_merge($questions,$found);
            }
            
            $challenges[] = $questions;
        }
        // lưu trữ kết quả tạm vào csdl
        $challengesid = $this->saveResult($idmatrix,$challenges);
        return [$challengesid,$challenges];
    }
    public function saveResult($idmatrix,$result){
        $rs = [];
        // chỉ giữ id khi lưu
        for($i=0;$i<count($result);$i++){
            $rs[] = [];
            foreach($result[$i] as $q){
                $rs[$i][] = $q->id;
            }
        }
        $insertid = buildInsert([
            "idmonhoc"=>$this->idmonhoc,
            "runner"=>$_SESSION['id'],
            "idmatrix"=>$idmatrix,
            "data"=>$rs])->asJson("data")->exec("matrixresult");
        return $insertid;
    }
    public function replayResult($idmonhoc,$result)
    {
        // chạy lại kết quả đã lưu
        $this->idmonhoc = $idmonhoc;
        $listids = [];
        for($i=0;$i<count($result);$i++){
            $listids = array_merge($listids,$result[$i]);
        }
        $this->loadList($listids);
        if(!$this->questions){
            return false;
        }
        for($i=0;$i<count($result);$i++){
            for($j=0;$j<count($result[$i]);$j++){
                $questid = $result[$i][$j];
                $filter = array_filter($this->questions,function($q) use($questid){
                    return $q->id == $questid;
                });
                if(count($filter) == 1){
                    
                    $result[$i][$j] = $filter[array_key_first($filter)];
                }else{
                    die("Kết quả ma trận bị lỗi do câu hỏi đã bị xóa!");
                }
            }
        }
        return $result;
    }
}
$GLOBALS['modules']['matrix'] = ["className","QuestionMatrix"];