<?php
class AskAndAnswer extends Question{
    public $text = "";
    public $answer = "";
    public function render($showTag = false,$edit=false,$questNum = null,$showAns=false){
        $data = [
            "text"=> $this->text,
            "tag"=> $showTag ? $this->renderTag($this->tieuchi) : "",
            "isShowTag"=> $showTag,
            "isEdit"=> $edit,
            "questNum"=> $questNum,
            "isShowAns"=> $showAns,
        ];
        if($showAns){
            $data["ans"] = $this->answer;
        }
        // trả về html
        return $this->view("askandanswer",$data);
    }
    public function checkAnswer($ans){
        if(trim($ans) !== trim($this->answer)){
            return false;
        }
    }
}