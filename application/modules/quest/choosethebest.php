<?php
class ChooseTheBest extends Question{
    public $text = "";
    public $answer = [
        "1",
        "2",
        "3",
        "4"
    ];
    public function render($showTag = false,$edit=false,$questNum = null){
        $mode = "2x2";
        if(count($this->answer) > 4){
            $mode = "1xn";
        }elseif(count($this->answer) == 2){
            $mode = "1x2";
        }elseif(count($this->answer) == 3){
            $mode = "1x3";
        }else
        for($i = 0; $i <count($this->answer);$i++){
            if(strlen($this->answer[$i]) > 20){
                $mode = "1x4";
                break;
            }
        }
        $this->shuffle($this->answer);
        $data = [
            "text"=> $this->text,
            "answer"=> $this->answer,
            "type"=> $mode,
            "tag"=> $showTag ? $this->renderTag($this->tieuchi) : "",
            "isShowTag"=> $showTag,
            "isEdit"=> $edit,
            "questNum"=> $questNum
        ];
        return $this->view("choosethebest",$data);
    }
    public function checkAnswer($ans){
        if(trimLower($this->rightanswer) == trimLower($ans)){
            return true;
        }
        return false;
    }
}