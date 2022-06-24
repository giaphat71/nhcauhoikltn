<?php

define('outputSql',false);
define('jsonMapper',[
    "cauhoi"=>["tieuchi","data"],
    "monhoc"=>["chuongtrinh"],
    "matrix"=>['data'],
    "users"=>['perm']
]);
function autoMapJsonToQuery($query,$table){
    if(array_key_exists($table,jsonMapper)){
        foreach(jsonMapper[$table] as $jsonCol){
            if(!in_array($jsonCol,$query->jsonCol))
            $query->asJson($jsonCol);
        }
    }
}
class SearchQuery{
    public $limit = 0;
    public $offset = 0;
    public $where = [];
    public $join = [];
    public $projection = "*";
    public $order = "";
    public $group = "";
    public $jsonCol = [];
    public $pagi = "";
    // tự động chuyển cột thành object khi lấy dữ liệu
    public function asJson($col){
        $this->jsonCol[] = $col;
        return $this;
    }
    // chuyển tên cột thành dạng cột của mysql trước khi thực hiện sql ( từ abc.xyz thành `abc`.`xyz`)
    public function getColName($c){
        if(strpos($c,'.')!==false){
            $tc = explode('.',$c);
            return "`".$tc[0]."`.`".$tc[1]."`";
        }
        return "`".$c."`";
    }
    // chuyển các điều kiện đã sử dụng thành câu lệnh sql
    public function parseWhere($w)
    {
        if(is_string($w)){
            return $w;
        }
        $s = "";
        if($w[0] == "&&"){
            $q = [];
            foreach($w[1] as $query){
                $q[]= $this->parseWhere($query);
            }
            $s.= "( " . implode(" AND ", $q) . " )";
        }elseif($w[0] == "||"){
            $q = [];
            foreach($w[1] as $query){
                $q[]= $this->parseWhere($query);
            }
            $s.= "( " . implode(" OR ", $q) . " )";
        }elseif($w[0] == "in"){
            $q = [];
           
            $vals = $w[1][1];
            $isnumber = is_int($vals[0]);
            for($i=0;$i<count($vals);$i++){
                $vals[$i] = sqlescape($vals[$i]);
            }
            if($isnumber){
                $s= $this->getColName($w[1][0])." IN (" . implode(", ", $vals) . " )";
            }else{
                $s= $this->getColName($w[1][0])." IN ('" . implode("', '", $vals) . "')";
            }
        }else{
            $colname = $this->getColName($w[0]);
            if(is_array($w[1])){
                $mode = $w[1][0];
                if($mode == "LIKE"){
                    $s = "$colname LIKE '%". sqlescape($w[1][1]) ."%' ";
                }elseif($mode == "NOT LIKE"){
                    $s = "$colname NOT LIKE '%". sqlescape($w[1][1]) ."%' ";
                }elseif($mode == "exp" || $mode == "?"){
                    $s = "$colname ". $w[1][1];
                }else{
                    if(is_string($w[1][1])){
                        $s = "$colname $mode '". sqlescape($w[1][1]) ."'";
                    }else{
                        $s = "$colname $mode ". $w[1][1];
                    }
                }
            }else{
                if(is_string($w[1])){
                    $s = "$colname = '". sqlescape($w[1]) ."'";
                }else{
                    $s = "$colname = ". $w[1];
                }
            }
        }
        return $s;
    }
    // thêm điều kiện
    public function add($key, $value, $number=false){
        if($number){
            $this->where[] = [$key,(int)$value];
        }else
        $this->where[] = [$key,$value];
        return $this;
    }
    // thêm điều kiện like
    public function addLike($key, $value){
        $this->where[] = [$key,["LIKE",$value]];
        return $this;
    }
    // thêm các điều kiện đặc thù như IN, BETWEEN, ...
    public function addMode($key, $mode,$value, $number=false){
        if($number){
            $this->where[] = [$key,[$mode,(int)$value]];
        }else
        $this->where[] = [$key,[$mode,$value]];
        return $this;
    }
    // thêm điều kiện NOT
    public function addNot($key, $value, $number=false){
        if($number){
            $this->where[] = [$key,["<>",(int)$value]];
        }else
        $this->where[] = [$key,["<>",$value]];
        return $this;
    }
    // thêm điều kiện NOT LIKE
    public function addNotLike($key, $value){
        $this->where[] = [$key,["NOT LIKE",$value]];
        return $this;
    }
    // thêm nhiều điều kiện cùng lúc
    public function adds($arr){
        foreach($arr as $key=>$value){
            $this->where[] = [$key,$value];
        }
        return $this;
    }
    // thêm giới hạn LIMIT số kết quả trả về
    public function limit($l){
        $this->limit = $l;
        return $this;
    }
    // chỉ định kết quả trả về, vd: id, name, ..., mặc định là *
    public function project($project){
        $this->projection = $project;
        return $this;
    }
    // chia kết quả thành các trang LIMIT OFFSET, tự động lưu trữ tổng số kết quả
    public function paginate($perpage,$p){
        if((int)$p > 0){
            $this->offset = $perpage * $p;
        }else{
            $this->offset = 0;
        }
        $this->pagi = ", count(1) OVER() AS total";
        return $this;
    }
    // thực hiện sắp xếp kết quả
    public function sort($v){
        $this->order = $v;
        return $this;
    }
    // nhóm các kết quả
    public function groupBy($v){
        $this->group = $v;
        return $this;
    }
    // đúc kết các điều kiện thành câu lệnh sql
    public function getSql($table){
        $s = "SELECT ".$this->projection.$this->pagi." FROM $table ";
        if(count($this->join) > 0){
            $join = [];
            foreach($this->join as $jq){
                $j = "LEFT JOIN $jq[0] ON ";
                if(is_string($jq[1])){
                    $join[] = $j.$jq[1];
                }else{
                    $arr = [];
                    foreach($jq[1] as $m){
                        $colname = $this->getColName($m[0]);
                        if(is_string($m[1])){
                            $arr[]= "$colname = '" . sqlescape($m[1]) . "' ";
                        }else{
                            $arr[]= "$colname = " . $m[1] . " ";
                        }
                    }
                    $join[] = $j.implode(" AND ", $arr);
                }
            }
            $s .= implode(" ", $join)." ";
        }
        if(is_string($this->where)){
            $s .= " WHERE " . $this->where ." ";
        }else
        if(count($this->where) > 0){
            for($i=0;$i<count($this->where);$i++){
                $this->where[$i]=$this->parseWhere($this->where[$i]);
            }
            $s .= "WHERE " . implode(" AND ",$this->where)." ";
        }
        if($this->group != ""){
            $s .= "GROUP BY " . $this->group ." ";
        }
        if($this->order != ""){
            $s .= "ORDER BY " . $this->order ." ";
        }
        if($this->limit > 0){
            $s .= "LIMIT " . $this->limit." ";
        }
        if($this->offset > 0){
            $s .= "OFFSET " . $this->offset." ";
        }
        if(outputSql)echo $s;
        return $s;
        
    }
    // truy vấn nhiều bảng
    public function leftJoin($table, $conditions){
        $this->join[] = [$table,$conditions];
        return $this;
    }
    // thực hiện truy vấn và trả về kết quả với tên bảng
    public function exec($table,$assoc = false){
        autoMapJsonToQuery($this,$table);
        $db = newmysql();
        $rs = $db->query($this->getSql($table));
        if($rs===false || $db->error){
            echo $db->error;
            return null;
        }
        if($rs->num_rows == 0){
            return null;
        }
        if($rs->num_rows == 1 && !$this->order && !$this->offset && !$this->limit){
            $arr = $rs->fetch_assoc();
            if(count($this->jsonCol) > 0){
                foreach($this->jsonCol as $c){
                    if(array_key_exists($c,$arr))
                    $arr[$c] = json_decode($arr[$c],$assoc);
                }
            }
            if($this->limit > 0){
                if($assoc){
                    return [$arr];
                }
                return [(object)$arr];
            }else{
                if($assoc){
                    return $arr;
                }
                return (object)$arr;
            }
        }else{
            $arr = $rs->fetch_all(MYSQLI_ASSOC);
            $havejson = count($this->jsonCol) > 0;
            if(!$assoc){
                for($i = 0; $i < $rs->num_rows; $i++){
                    if($havejson){
                        foreach($this->jsonCol as $c){
                            if(array_key_exists($c,$arr[$i])){
                                $arr[$i][$c] = json_decode($arr[$i][$c],$assoc);
                            }
                        }
                    }
                    $arr[$i] = (object)$arr[$i];
                }
            }else if($havejson){
                for($i = 0; $i < $rs->num_rows; $i++){
                    foreach($this->jsonCol as $c){
                        if(array_key_exists($c,$arr[$i])){
                            $arr[$i][$c] = json_decode($arr[$i][$c],$assoc);
                        }
                    }
                }
            }
            return $arr;
        }
    }
    // thực hiện truy vấn và trả về kết quả có thể dùng vòng lặp
    public function execEnum($table){
        autoMapJsonToQuery($this,$table);
        $db = newmysql();
        $rs = $db->query($this->getSql($table));
        if($rs->num_rows == 0 || $db->error){
            if(outputSql)echo $db->error;
            return false;
        }
        return $rs;
    }
}
// tương tự với các câu lệnh khác
class DeleteQuery{
    public $limit = 0;
    public $where = [];
    
    public function getColName($c){
        if(strpos($c,'.')!==false){
            $tc = explode('.',$c);
            return "`".$tc[0]."`.`".$tc[1]."`";
        }
        return "`".$c."`";
    }
    public function parseWhere($w)
    {
        if(is_string($w)){
            return $w;
        }
        $s = "";
        if($w[0] == "&&"){
            $q = [];
            foreach($w[1] as $query){
                $s[]= $this->parseWhere($query);
            }
            $s.= "( " . implode(" AND ", $q) . " )";
        }elseif($w[0] == "||"){
            $q = [];
            foreach($w[1] as $query){
                $s[]= $this->parseWhere($query);
            }
            $s.= "( " . implode(" AND ", $q) . " )";
        }elseif($w[0] == "in"){
            $q = [];
            foreach($w[1] as $query){
                $s[]= $this->parseWhere($query);
            }
            $s.= "( " . implode(" AND ", $q) . " )";
        }else{
            $colname = $this->getColName($w[0]);
            if(is_array($w[1])){
                $mode = $w[1][0];
                if($mode == "LIKE"){
                    $s = "$colname LIKE '%". sqlescape($w[1][1]) ."%' ";
                }elseif($mode == "NOT LIKE"){
                    $s = "$colname NOT LIKE '%". sqlescape($w[1][1]) ."%' ";
                }else{
                    if(is_string($w[1][1])){
                        $s = "$colname $mode '". sqlescape($w[1][1]) ."'";
                    }else{
                        $s = "$colname $mode ". $w[1][1];
                    }
                }
            }else{
                if(is_string($w[1])){
                    $s = "$colname = '". sqlescape($w[1]) ."'";
                }else{
                    $s = "$colname = ". $w[1];
                }
            }
        }
        return $s;
    }
    public function add($key, $value, $number=false){
        if($number){
            $this->where[] = [$key,(int)$value];
        }else
        $this->where[] = [$key,$value];
        return $this;
    }
    public function addLike($key, $value){
        $this->where[] = [$key,["LIKE",$value]];
        return $this;
    }
    public function addMode($key, $mode,$value, $number=false){
        if($number){
            $this->where[] = [$key,[$mode,(int)$value]];
        }else
        $this->where[] = [$key,[$mode,$value]];
        return $this;
    }
    public function addNot($key, $value, $number=false){
        if($number){
            $this->where[] = [$key,["<>",(int)$value]];
        }else
        $this->where[] = [$key,["<>",$value]];
        return $this;
    }
    public function addNotLike($key, $value){
        $this->where[] = [$key,["NOT LIKE",$value]];
        return $this;
    }
    public function adds($arr){
        foreach($arr as $key=>$value){
            $this->where[] = [$key,$value];
        }
        return $this;
    }
    public function limit($l){
        $this->limit = $l;
        return $this;
    }
    public function getSql($table){
        $s = "DELETE FROM $table ";
        if(is_string($this->where)){
            $s .= " WHERE " . $this->where ." ";
        }else
        if(count($this->where) > 0){
            for($i=0;$i<count($this->where);$i++){
                $this->where[$i]=$this->parseWhere($this->where[$i]);
            }
            $s .= "WHERE " . implode(" AND ",$this->where)." ";
        }else{
            die("Unexpected delete");
        }
        if($this->limit > 0){
            $s .= "LIMIT " . $this->limit." ";
        }
        if(outputSql)var_dump($s);
        return $s;
    }
    public function exec($table){
        $db = newmysql();
        $db->query($this->getSql($table));
        if($db->affected_rows == 0 || $db->error){
            if(outputSql)echo $db->error; //debug only
            return false;
        }
        return true;
    }
}
class UpdateQuery{
    public $limit = 0;
    public $where = [];
    public $updateparam = [];
    public $jsonCol = [];
    public function asJson($col)
    {
        $this->jsonCol[]= $col;
        return $this;
    }
    public function clean(){
        foreach($this->updateparam as $key=>$value){
            if(in_array($key,$this->jsonCol)){
            }else
            if($value."" == ""){
                array_splice($this->updateparam,$key,1);
            }
        }
        return $this;
    }
    public function getColName($c){
        if(strpos($c,'.')!==false){
            $tc = explode('.',$c);
            return "`".$tc[0]."`.`".$tc[1]."`";
        }
        return "`".$c."`";
    }
    public function parseWhere($w)
    {
        if(is_string($w)){
            return $w;
        }
        $s = "";
        if($w[0] == "&&"){
            $q = [];
            foreach($w[1] as $query){
                $s[]= $this->parseWhere($query);
            }
            $s.= "( " . implode(" AND ", $q) . " )";
        }elseif($w[0] == "||"){
            $q = [];
            foreach($w[1] as $query){
                $s[]= $this->parseWhere($query);
            }
            $s.= "( " . implode(" AND ", $q) . " )";
        }elseif($w[0] == "in"){
            $q = [];
            foreach($w[1] as $query){
                $s[]= $this->parseWhere($query);
            }
            $s.= "( " . implode(" AND ", $q) . " )";
        }else{
            $colname = $this->getColName($w[0]);
            if(is_array($w[1])){
                $mode = $w[1][0];
                if($mode == "LIKE"){
                    $s = "$colname LIKE '%". sqlescape($w[1][1]) ."%' ";
                }elseif($mode == "NOT LIKE"){
                    $s = "$colname NOT LIKE '%". sqlescape($w[1][1]) ."%' ";
                }else{
                    if(is_string($w[1][1])){
                        $s = "$colname $mode '". sqlescape($w[1][1]) ."'";
                    }else{
                        $s = "$colname $mode ". $w[1][1];
                    }
                }
            }else{
                if(is_string($w[1])){
                    $s = "$colname = '". sqlescape($w[1]) ."'";
                }else{
                    $s = "$colname = ". $w[1];
                }
            }
        }
        return $s;
    }
    public function add($key, $value, $number=false){
        if($number){
            $this->where[] = [$key,(int)$value];
        }else
        $this->where[] = [$key,$value];
        return $this;
    }
    public function addLike($key, $value){
        $this->where[] = [$key,["LIKE",$value]];
        return $this;
    }
    public function addMode($key, $mode,$value, $number=false){
        if($number){
            $this->where[] = [$key,[$mode,(int)$value]];
        }else
        $this->where[] = [$key,[$mode,$value]];
        return $this;
    }
    public function addNot($key, $value, $number=false){
        if($number){
            $this->where[] = [$key,["<>",(int)$value]];
        }else
        $this->where[] = [$key,["<>",$value]];
        return $this;
    }
    public function addNotLike($key, $value){
        $this->where[] = [$key,["NOT LIKE",$value]];
        return $this;
    }
    public function adds($arr){
        foreach($arr as $key=>$value){
            $this->where[] = [$key,$value];
        }
        return $this;
    }
    public function update($k,$v){
        $this->updateparam[$k] = $v;
        return $this;
    }
    public function updates($arr){
        foreach($arr as $key=>$value){
            $this->updateparam[$key] = $value;
        }
        return $this;
    }
    public function limit($l){
        $this->limit = $l;
        return $this;
    }
    public function getSql($table){
        $s = "UPDATE $table SET ";
        $update = [];
        foreach($this->updateparam as $col=>$val){
            if(is_array($val)){
                if(in_array($col,$this->jsonCol)){
                    $update[]= "$col = '".sqlescape(json_encode($val))."'";
                }else
                $update[]= "$col = ".$val[0]."";
            }else
            if(is_string($val)){
                $update[]= "$col = '".sqlescape($val)."'";
            }else{
                if(in_array($col,$this->jsonCol)){
                    $update[]= "$col = '".sqlescape(json_encode($val))."'";
                }else
                $update[]= "$col = ".sqlescape($val);
            }
        }
        $s .= implode(', ',$update)." ";
        if(is_string($this->where)){
            $s .= " WHERE " . $this->where ." ";
        }else
        if(count($this->where) > 0){
            for($i=0;$i<count($this->where);$i++){
                $this->where[$i]=$this->parseWhere($this->where[$i]);
            }
            $s .= "WHERE " . implode(" AND ",$this->where)." ";
        }
        if($this->limit > 0){
            $s .= "LIMIT " . $this->limit." ";
        }
        if(outputSql)var_dump($s);
        return $s;
    }
    public function exec($table){
        autoMapJsonToQuery($this,$table);
        $db = newmysql();
        $db->query($this->getSql($table));
        if($db->affected_rows == 0 || $db->error){
            if(outputSql)echo $db->error; //debug only
            return false;
        }
        return true;
    }
}
class InsertQuery{
    public $values = [];
    public $duplicateUpdate = false;
    public $jsonCol = [];
    public function asJson($col)
    {
        $this->jsonCol[]= $col;
        return $this;
    }
    public function clean(){
        foreach($this->values as $k=>$v){
            if($v."" == ""){
                unset($this->values[$k]);
            }
        }
        
        return $this;
    }
    public function add($k,$v)
    {
        $this->values[$k] = $v;
        return $this;
    }
    public function adds($arr)
    {
        foreach($arr as $key => $v){
            $this->values[$key] = $v;
        }
        return $this;
    }
    public function getSql($table){
        $cols = [];
        $values = [];
        foreach($this->values as $col=>$val){
            $cols[] = $col;
            if(in_array($col, $this->jsonCol)){
                $values[]="'".sqlescape(json_encode($val))."'";
            }else
            if(is_string($val)){
                $values[]="'".sqlescape($val)."'";
            }else{
                $values[]=$val;
            }
        }
        $cols = implode(', ',$cols);
        $values = implode(', ',$values);
        $s = "INSERT INTO $table($cols) VALUES($values) ";
        if($this->duplicateUpdate){
            $update = [];
            foreach($this->values as $col=>$val){
                if(in_array($col, $this->jsonCol)){
                    $values[]="'".sqlescape(json_encode($val))."'";
                }else
                if(is_string($val)){
                    $update[]= "$col = '".sqlescape($val)."'";
                }else{
                    $update[]= "$col = ".sqlescape($val);
                }
            }
            $s .= "ON DUPLICATE KEY UPDATE ".implode(', ',$update);
        }
        if(outputSql)var_dump($s);
        return $s;
    }
    public function updateOnDuplicate($enable = false){
        $this->duplicateUpdate = $enable;
        return $this;
    }
    public function exec($table){
        autoMapJsonToQuery($this,$table);
        $db = newmysql();
        $db->query($this->getSql($table));
        if($db->affected_rows == 0 || $db->error){
            if(outputSql)echo $db->error; //debug only
            return false;
        }
        return $db->insert_id;
    }
}
// xây dựng câu lệnh select với điều kiện
function buildSearch($where=[]){
    $q = new SearchQuery();
    $q->adds($where);
    return $q;
}
// xây dựng câu lệnh insert
function buildInsert($insert){
    $q = new InsertQuery();
    $q->adds($insert);
    return $q;
}
// xây dựng câu lệnh update
function buildUpdate($where=[],$update=[]){
    $q = new UpdateQuery();
    $q->adds($where);
    $q->updates($update);
    return $q;
}
// xây dựng câu lệnh delete
function buildDelete($where){
    $q = new DeleteQuery();
    $q->adds($where);
    return $q;
}
// lấy tổng số dòng khi dùng select
function getTotalRow($rs){
    $total = 0;
    if(!$rs)return 0;
    if(count($rs)>0){
        $total = $rs[0]->total;
    }
    return $total;
}