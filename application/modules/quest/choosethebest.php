<?php
class ChooseTheBest extends Question{
    public $text = "";
    // đáp án, load từ cơ sở dữ liệu
    public $answer = [
        "1",
        "2",
        "3",
        "4"
    ];
    // hàm render, tham số: showTag: hiện nhãn dán, edit: hiện nút sửa, questNum: hiện số thứ tự, showAns: hiện đáp án
    public function render($showTag = false,$edit=false,$questNum = null,$showAns=false){
        // xác định hình thức hiển thị
        $mode = "2x2";
        if(count($this->answer) > 4){
            $mode = "1xn";
        }elseif(count($this->answer) == 2){
            $mode = "1x2";
        }elseif(count($this->answer) == 3){
            $mode = "1x3";
        }else
        for($i = 0; $i <count($this->answer);$i++){
            if(strlen($this->answer[$i]) > 30){
                $mode = "1x4";
                break;
            }
        }
        // xáo trộn đáp án
        if(isset($this->lockposition) && $this->lockposition == true){
            // khi khóa vị trí đáp án sẽ không xáo trộn.
        }else
        $this->shuffle($this->answer);
        $data = [
            "text"=> $this->text,
            "answer"=> $this->answer,
            "type"=> $mode,
            "tag"=> $showTag ? $this->renderTag($this->tieuchi) : "",
            "isShowTag"=> $showTag,
            "isEdit"=> $edit,
            "questNum"=> $questNum,
            "isShowAns"=> $showAns,
            "ansNum"=> $showAns ? $this->getRightAnswerIndex() : 0
        ];
        // trả về html
        return $this->view("choosethebest",$data);
    }
    public function checkAnswer($ans){
        // kiểm tra đáp án đúng
        if(trimLower($this->rightanswer) == trimLower($ans)){
            return true;
        }
        return false;
    }
}