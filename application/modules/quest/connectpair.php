<?php
class ConnectPair extends Question{
    public $text = "";
    public $pair = [
    ];
    public $ansleft = [];
    public $ansright = [];
    public function render($showTag = false,$edit=false,$questNum = null,$showAns=false){
        $text = "Nối 2 cột có ý nghĩa với nhau.";
        $this->shuffle($this->ansleft);
        $this->shuffle($this->ansright);
        $data = [
            "text"=> $text,
            "tag"=> $showTag ? $this->renderTag($this->tieuchi) : "",
            "isShowTag"=> $showTag,
            "isEdit"=> $edit,
            "questNum"=> $questNum,
            "isShowAns"=> $showAns,
            "ansleft"=>$this->ansleft,
            "ansright"=>$this->ansright,
        ];
        
        if($showAns){
            $data["ans"] = $this->pair;
        }
        // trả về html
        return $this->view("connectpair",$data);
    }
    public function checkAnswer($ans){
        if(count($ans) != count($this->pair)){
            return false;
        }
        $right = 0;
        for($i = 0; $i < count($this->pair); $i++){
            for($j = 0; $j < count($ans); $j++){
                if(trim($ans[$j][0]) == trim($this->pair[$i][0]) && trim($ans[$j][1]) == trim($this->pair[$i][1])){
                    $right++;
                    break;
                }
            }
        }
        return $right == count($this->pair);
    }
}