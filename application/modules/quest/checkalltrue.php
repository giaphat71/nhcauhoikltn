<?php
class CheckAllTrue extends Question{
    public $text = "";
    public $answer = [
        "1",
        "2",
        "3",
        "4"
    ];
    public function render($showTag = false,$edit=false,$questNum = null,$showAns=false){
        $this->shuffle($this->answer);
        $data = [
            "text"=> $this->text,
            "answer"=> $this->answer,
            "tag"=> $showTag ? $this->renderTag($this->tieuchi) : "",
            "isShowTag"=> $showTag,
            "isEdit"=> $edit,
            "questNum"=> $questNum,
            "isShowAns"=> $showAns,
            "ansNum"=> $showAns ? $this->getRightAnswerIndex() : 0
        ];
        // trả về html
        return $this->view("checkalltrue",$data);
    }
    public function checkAnswer($ans){
        if(count($ans) != count($this->rightanswer)){
            return false;
        }
        $right = 0;
        foreach($this->rightanswer as $rans){
            for($i = 0; $i < count($ans); $i++){
                if(trim($ans[$i]) == trim($rans)){
                    $right ++;
                    break;
                }
            }
        }
        return $right == count($this->rightanswer);
    }
    public function getRightAnswerIndex()
    {
        $indexs = [];
        foreach($this->rightanswer as $rans){
            for($i = 0; $i < count($this->answer); $i++){
                if(trim($this->answer[$i]) == trim($rans)){
                    $indexs[] = $i;
                    break;
                }
            }
        }
        return $indexs;
    }
}