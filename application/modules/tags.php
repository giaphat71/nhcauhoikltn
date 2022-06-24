<?php
class Tagmanager{
    public $list= null;
    // hàm render nhãn dán thành html
    public function render($tag,$value){
        if($this->list == null){
            $this->loadList();
        }
        if(!array_key_exists($tag, $this->list)){
            return "";
        }
        if($value == ""){
            return "<span class='tag'>".$this->list[$tag]->name."</span>";
        }
        return "<span class='tag'>".$this->list[$tag]->name.": $value</span>";
    }
    // hàm load danh sách nhãn dán vào bộ nhớ
    public function loadList(){
        $this->list = [];
        $query = buildSearch()->limit(100)->asJson("valuerange")->exec("tieuchi");
        for ($i = 0; $i < count($query); $i++){
            $this->list[$query[$i]->slugname] = $query[$i];
        }
    }
    // hàm kiểm tra phạm vi dữ liệu của nhãn dán
    public function checkValue($tagname,$value){
        // kiểm tra nhãn dán tồn tại hay không
        if(!array_key_exists($tagname,$this->list)){
            return false;
        }
        $tag = $this->list[$tagname];
        if($tag->type == "number"){
            // nhãn dán loại số
            if($value > $tag->valuerange->max || $value < $tag->valuerange->min){
               return false;
            }
        }
        if($tag->type == "string"){
            // nhãn dán loại chữ
            if(strlen($value) < 1){
                return false;
            }
        }
        if($tag->type == "array"){
            // nhãn dán loại giá trị định trước ( a,b,c,1,2,3)
            return in_array($value,$tag->valuerange);
        }
        // nhãn dán loại khác
        return true;
    }
    // lấy tất cả nhãn dán phù hợp với câu hỏi theo môn học khi tạo câu hỏi
    public function getAllTags($context){
        if(!$this->list){
            $this->loadList();
        }
        $rs = [];
        $ct = null;
        foreach($this->list as $tag){
            // nhãn dán đặc biệt "phân đoạn chương trình" dựa theo nội dung chương trình của môn học
            if($tag->slugname=="phan-doan-chuong-trinh"){
                if($ct==null){
                    $ct = buildSearch(["id"=>$context])->asJson("chuongtrinh")->exec("monhoc");
                }
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
    // kiểm tra giá trị của các nhãn dán
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
    // thống kê tất cả nhãn dán của các câu hỏi phù hợp để gợi ý tạo ma trận
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
            if($tag[0])
            $result2[] = ["sign"=>md5($key),"slugname"=>$tag[0],"value"=>$tag[1],"count"=>$value,"name"=>$this->list[$tag[0]]->name];
        }
        return $result2;
    }
    // kiểm tra dữ liệu đầu vào khi nhập giá trị nhãn dán
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
// export
$GLOBALS['modules']["tags"] = ["object",new Tagmanager()];