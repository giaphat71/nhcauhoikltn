<?php
class PutInPlace extends Question{
    public $text = "";
    public $answer = [
    ];
    public function prepare($text){
        $t = preg_replace_callback("#<strong>(.*?)</strong>#si",function($matches){
            $innertext = $matches[1];
            $width = mb_strlen($innertext)*10;
            return "<input style='width:{$width}px' type='text' class='pip-input'/>";
        },$text);
        return $t;
    }
    public function render($showTag = false,$edit=false,$questNum = null,$showAns=false){
        $text = $this->prepare($this->text);
        $data = [
            "text"=> $text,
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
        return $this->view("putinplace",$data);
    }
    public function checkAnswer($ans){
        if(count($ans) != count($this->answer)){
            return false;
        }
        for($i = 0; $i < count($ans); $i++){
            if(trim($ans[$i]) != trim($this->answer[$i])){
                return false;
            }
        }
        return false;
    }
}