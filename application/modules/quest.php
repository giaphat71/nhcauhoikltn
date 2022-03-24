<?php
class Question{
    public $name="Question";
    public $type="default";
    public $template="./quest/template/question.html";
    public $tieuchi=[];
    public function render(){

    }
    public static function GetQuestByType($type){
        require_once "quest/$type.php";
        return new $type();
    }
    public static function GenQuest($data){
        return Question::GetQuestByType($data->type)->load($data);
    }
    public function load($data){
        objectAssign($this,$data->data);
        $this->text = $data->text;
        $this->tags = $data->tieuchi;
        $this->tieuchi = $data->tieuchi;
        return $this;
    }
    public function renderTag($tags){
        $tags = $tags ?? $this->tags;
        $t = "";
        $tagmanager = getModule("tags");
        for($i=0;$i<count($tags);$i++){
            $t.=$tagmanager->render($tags[$i]->slugname, $tags[$i]->value);
        }
        
        return $t;
    }
    public function shuffle(&$array) {
        shuffle($array);
        return $array;
    }
    public function view($tmpl,$data){
        extract($data);
        include "quest/$tmpl"."_view.php";
    }
}
$GLOBALS['modules']['quest'] = ["className","Question"];