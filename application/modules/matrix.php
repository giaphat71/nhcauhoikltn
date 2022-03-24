<?php
class QuestionMatrix{
    public $questcount = 0;
    public $questions = [];
    public function loadAll($idmonhoc) {
        //load all questions from the database
        $this->questions = buildSearch(["idmonhoc"=>$idmonhoc])->exec("cauhoi");
    }
    public function load($idmonhoc, $author = 0, $allowshare = false) {
        //load all questions from the database
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
    public function matchTag($tagl,$tagr){
        return $tagl->slugname == $tagr->slugname && $tagl->value == $tagr->value;
    }
    public function matchTagAll($tags,$tagsmatch){
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
        $questions = [];
        foreach($this->questions as $q){
            if($this->matchTagAll($q->tieuchi,$matchs)){
                $questions[] = $q;
            }
        }
        return $questions;
    }
    public function getQuestFromQuery($query,$matched){
        $count =$query->count;
        $tagset = $query->tags;
        $questmatched = $this->filter($tagset);
        $questions = [];
        if(count($questmatched) < $count){
            return false;
        }
        $retry = 0;
        while (count($questions) < $count){
            $quest = $questmatched[mt_rand(0,count($questmatched)-1)];
            if(!in_array($quest,$questions) && !in_array($quest,$matched)){
                $questions[] = $quest;
                $retry = 0;
            }else{
                $retry ++;
                if($retry > 50){
                    return false;
                }
            }
        }
        return $questions;
    }
    public function generateChallenge($matrix,$count =1){
        $challenges = [];
        for($i=0;$i<$count;$i++){
            $questions = [];
            foreach($matrix as $query){
                $questions = array_merge($questions,$this->getQuestFromQuery($query,$questions));
            }
            $challenges[] = $questions;
        }
        return $challenges;
    }
}
$GLOBALS['modules']['matrix'] = ["className","QuestionMatrix"];