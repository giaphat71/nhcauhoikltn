<?php
class Question{
    public $name="Question";
    public $type="default";
    public $template="./quest/template/question.html";
    public $tieuchi=[];
    // hàm render ra html cho mỗi câu hỏi
    public function render(){

    }
    // tạo object câu hỏi theo loại câu hỏi
    public static function GetQuestByType($type){
        require_once "quest/$type.php";
        return new $type();
    }
    // tạo object câu hỏi từ dữ liệu
    public static function GenQuest($data){
        return Question::GetQuestByType($data->type)->load($data);
    }
    // sao chép dữ liệu từ csdl vào object
    public function load($data){
        objectAssign($this,$data->data);
        $this->text = preg_replace("#<p>(.*?)</p>#s","$1",$data->text);
        $this->tags = $data->tieuchi;
        $this->tieuchi = $data->tieuchi;
        return $this;
    }
    // hàm render ra html nhãn dán cho mỗi câu hỏi
    public function renderTag($tags){
        $tags = $tags ?? $this->tags;
        $t = "";
        // lấy module tags
        $tagmanager = getModule("tags");
        for($i=0;$i<count($tags);$i++){
            $t.=$tagmanager->render($tags[$i]->slugname, $tags[$i]->value);
        }
        
        return $t;
    }
    // hàm thực hiện xáo trộn đáp án
    public function shuffle(&$array) {
        shuffle($array);
        return $array;
    }
    // hàm render ra html câu hỏi
    public function view($tmpl,$data){
        extract($data);
        include "quest/$tmpl"."_view.php";
    }
    // hàm kiểm tra đáp án câu hỏi
    public function checkAnswer($ans)
    {
        return true;
    }
    // hàm xác định đáp án đúng
    public function getRightAnswerIndex()
    {
        $index = 0;
        for($i = 0; $i < count($this->answer); $i++)
        {
            if($this->checkAnswer($this->answer[$i]))
            {
                $index = $i;
                break;
            }
        }
        return $index;
    }
}
// export để sử dụng module
$GLOBALS['modules']['quest'] = ["className","Question"];