<?php
class Tagmanager{
    public $list= null;
    public function render($tag,$value){
        if($this->list == null){
            $this->loadList();
        }
        if($value == ""){
            return "<span class='tag'>".$this->list[$tag]->name."</span>";
        }
        return "<span class='tag'>".$this->list[$tag]->name.": $value</span>";
    }
    public function loadList(){
        $this->list = [];
        $query = buildSearch()->limit(100)->asJson("valuerange")->exec("tieuchi");
        for ($i = 0; $i < count($query); $i++){
            $this->list[$query[$i]->slugname] = $query[$i];
        }
    }
    public function checkValue($tagname,$value){
        $tag = $this->list[$tagname];
        if($tag->type == "number"){
            if($value > $tag->valuerange->max || $value < $tag->valuerange->min){
               return false;
            }
        }
        if($tag->type == "string"){
            if(strlen($value) < 1){
                return false;
            }
        }
        if($tag->type == "array"){
            return in_array($value,$tag->valuerange);
        }
        return true;
    }
    public function getAllTags($context){
        if(!$this->list){
            $this->loadList();
        }
        $rs = [];
        foreach($this->list as $tag){
            if($tag->slugname=="phan-doan-chuong-trinh"){
                $ct = buildSearch(["id"=>$context])->asJson("chuongtrinh")->exec("monhoc");
                if($ct==null){
                    continue;
                }
                $obj = json_decode(json_encode($tag),true);
                $obj->valuerange=$ct->chuongtrinh;
                $rs[]="<span name='".$tag->slugname."' class='tag-pr' data='".urlencode(json_encode($obj))."'>".$obj->name."</span>";
            }else{
                $rs[]="<span name='".$tag->slugname."' class='tag-pr' data='".urlencode(json_encode($tag))."'>".$tag->name."</span>";
            }
        }
        return implode("",$rs);
    }
    public function validate($tags){
        if($this->list == null){
            $this->loadList();
        }
        $result = [];
        foreach($tags as $tag){
            if($this->checkValue($tag->slugname,$tag->value)){
                $result[]=["slugname"=>$tag->slugname,"value"=>htmlwithoutxss($tag->value)];
            }else{
                return false;
            }
        }
        return $result;
    }
    public function statTagsFor($idmh){
        $this->loadList();
        $query = buildSearch(["idmonhoc"=>$idmh])->project("tieuchi")->exec("cauhoi");
        $result = [];
        foreach($query as $q){
            foreach($q->tieuchi as $tag){
                $key = $tag->slugname.":".$tag->value;
                if(!isset($result[$key])){
                    $result[$key] = 0;
                }
                $result[$key]++;
            }
        }
        $result2 = [];
        foreach($result as $key=>$value){
            $tag = explode(":",$key);
            $result2[] = ["sign"=>md5($key),"slugname"=>$tag[0],"value"=>$tag[1],"count"=>$value,"name"=>$this->list[$tag[0]]->name];
        }
        return $result2;
    }
    public function isValid($tag){
        if($this->list == null){
            $this->loadList();
        }
        if(!property_exists($tag,"slugname") || !property_exists($tag,"value")){
            return false;
        }
        if(!isset($this->list[$tag->slugname])){
            return false;
        }
        
        return $this->checkValue($tag->slugname,$tag->value);
    }
}
$GLOBALS['modules']["tags"] = ["object",new Tagmanager()];