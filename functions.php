<?php
// tạo biến Modules ở global
class MODULES{

}
$user=[];
// hàm để redirect
function moveUser($p){
    header("Location: ".$p);
    die();
}
// hàm để mở kết nối csdl ở bất kỳ đâu
function newmysql(){
    if(isset($GLOBALS['db'])){
        return $GLOBALS['db'];
    }
    $conn = new mysqli("localhost","root","","nganhangcauhoi");
    if($conn->error){
        die("Mysql connect error");
    }
    $GLOBALS['db'] = $conn;
    return $conn;
}
// hàm để kiểm tra tình trạng đăng nhập
function checkLogin(){
    if(!isset($_SESSION))
    session_start();
    if(isset($_SESSION['isLoggedIn'])){
        return true;
    }else{
        moveUser("/user/login");
    }
}
// hàm để lấy thông tin người dùng
function getUser($id = 0)
{
    if(!$id){
        $id = $_SESSION['id'];
    }
    return buildSearch()->add("id",$id,true)->exec("users");
}
// hàm để lấy thông tin môn học
function getMonhoc($id)
{
    return buildSearch()->add("id",$id,true)->asJson("chuongtrinh")->exec("monhoc");
}
// hàm để yêu cầu quyền quản trị
function requireAdmin(){
    if(checkLogin()){
        if($_SESSION['isAdmin']){
            return true;
        }else{
            moveUser("/canbo");
        }
    }else{
        moveUser("/user/login");
    }
}
// hàm để kiểm tra quyền quản trị
function isAdmin($id = 0){
    if(!$id){
        $id = $_SESSION['id'];
    }
    $u = getUser($id);
    return $u->isadmin == "1";
}
// hàm để kiểm tra tình trạng khóa tài khoản
function checkLock(){
    if(getUser()->islocked){
        die("Tài khoản của bạn đã quản trị viên bị khóa.");
    }
}
// hàm để kiểm tra quyền của cán bộ đối với môn học
function checkAccessToMonhoc($mhid){
    if(isAdmin()){
        return true;
    }
    $perm = buildSearch(["idcanbo"=>$_SESSION['id'],"idmonhoc"=>(int)$mhid])->exec("permission");
    if(!$perm){
        die("Bạn không có quyền truy cập vào môn học này.");
    }
    if($perm->islocked){
        die("Quyền truy cập vào môn học này của bạn đã bị khóa.");
    }
}
// hàm để băm mật khẩu trước khi lưu vào cơ sở dữ liệu
function hashPassword($user,$password){
    $salt = "asdiuhighw8g289n982@%@#$^@#$^";
    $hash = md5($user.$salt.$password);
    return $hash;
}
// hàm để bật chế độ debug
function enableDebug()
{
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
function disableDebug()
{
	ini_set('display_errors', 0);
	ini_set('display_startup_errors', 0);
	error_reporting(0);
}
// hàm để lấy ip của người dùng
function getClientIP(){
    if (array_key_exists('HTTP_X_REAL_IP', $_SERVER)) {
           return $_SERVER["HTTP_X_REAL_IP"]; 
    }elseif (array_key_exists('HTTP_CF_CONNECTING_IP', $_SERVER)) {
           return $_SERVER["HTTP_CF_CONNECTING_IP"]; 
    }else
    if (array_key_exists('HTTP_X_F_FORWARDED_FOR', $_SERVER)) {
           return $_SERVER["HTTP_X_F_FORWARDED_FOR"]; 
    }else if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)){
           return  $_SERVER["HTTP_X_FORWARDED_FOR"];  
    }else if (array_key_exists('REMOTE_ADDR', $_SERVER)) { 
           return $_SERVER["REMOTE_ADDR"]; 
    }else if (array_key_exists('HTTP_CLIENT_IP', $_SERVER)) {
           return $_SERVER["HTTP_CLIENT_IP"]; 
    }
    return '';
}
// hàm để lấy ip của người dùng bao gôm ipv6
function getRealClientIP(){
    $ip = getClientIP();
    $ip = preg_replace("#[^0-9abcdef\.:]#", "", $ip);
    $ipv = explode(":", $ip);
    if(count($ipv) == 2){
        return $ipv[0];
    }
    if(count($ipv) == 1){
        return $ip;
    }
    return $ip;
}
// hàm để loại bỏ kiểu tấn công xss
function xssremove($str){
	return preg_replace("~\<|script|onload|onerror|onclick|src=~i",'', strip_tags($str));
}
function exportTable($sql,$conn)
{
	$sqlresult = $conn->query($sql);
	$cols = $sqlresult->fetch_fields();
	$head = "<tr><th><input type='checkbox' style='width: 20px;' onclick='selectall(this.checked)'></th>";
	foreach ($cols as $col) {
		$head.="<th>".$col->name."</th>";
	}
	$head.="</tr>";
	$body = "";
	while ($row=$sqlresult->fetch_assoc()) {
		$body .= "<tr><td><input type='checkbox'></td>";
		foreach ($row as $value) {
			$body.="<td>".htmlentities($value)."</td>";
		}
		$body.="</tr>";
	}
	return $head.$body;
}
// hàm util để dễ dàng tạo mẫu chuyển trang
function renderPagination($total,$current,$limit,$uri,$paramname = ""){
    if(strlen($paramname) > 0){
        $serveruri = $_SERVER['REQUEST_URI'];
        if(strpos($serveruri, $paramname) !== false){
            $uri = preg_replace("#$paramname=(\d+)#", "$paramname={i}", $serveruri);
        }
    }
    $tmpr = function($i,$t,$d,$uri){
        return "<li class=\"page-item $d\">
            <a class=\"page-link\" href=\"".str_replace("{i}",$i,$uri)."\">$t</a>
        </li>";
    };
    $totalpage = floor($total / $limit);
    if($total % $limit == 0){
        $totalpage--;
    }
    if($current > $totalpage){
        moveUser(str_replace("{i}",0,$uri));
        die();
    }
    $row = [];
    if($current == 0){
    	array_push($row,$tmpr(0,'|<',"disabled",$uri));
    }else{
        array_push($row,$tmpr(0,'|<',"",$uri));
    }
    if($current > 0){
        array_push($row,$tmpr($current-1,'<',"",$uri));
    }
    for($i = $current - 5; $i <= $totalpage && $i<$current+6; $i++){
        if($i<0)continue;
        if($current == $i){
            array_push($row,$tmpr($i,$i+1,"disabled",$uri));
        }else{
            array_push($row,$tmpr($i,$i+1,"",$uri));
        }
    }
    if($current < $totalpage){
        array_push($row,$tmpr($current+1,'>',"",$uri));
    }
    if($current == $totalpage){
        array_push($row,$tmpr($totalpage,'>|',"disabled",$uri));
    }else{
        array_push($row,$tmpr($totalpage,'>|',"",$uri));
    }
    $tmp = "<div style=\"width:100%;text-align:center;\"><nav style=\"display:inline-block;\">
        <ul class=\"pagination\">
            ".implode("",$row)."
        </ul>
    </nav></div>";
    return $tmp;
}
function sqlescape($v){
    // escape sql string
    return newmysql()->real_escape_string($v);
}
function sessionSet($arr){
    if(!isset($_SESSION)){
        session_start();
    }
    foreach($arr as $k=>$v){
        $_SESSION[$k]=$v;
    }
}

// hàm để trỏ các dường dẫn dựa trên file routemap
function parseRouteMap($file,$route){
    // load user defined route map
    $fdata = file_get_contents("application/views/$file");
    $lines = preg_split("/\r?\n/",$fdata);
    $root = "";
    $fileignore=[];
    foreach($lines as $line){
        if(trim($line) == "")continue;
        if($line[0] == "#"){
            $directive=explode("=",$line);
            if($directive[0] == "#root"){
                $root = $directive[1];
            }
            if($directive[0]=="#file"){
                $fileignore=explode(",",$directive[1]);
            }
            continue;
        }
        $l = explode(" ",trim($line));
        if(preg_match("#^".$root.$l[0]."$#i",$route,$m)){
            $params = [];
            for($i = 2;$i<count($l);$i++){
                $param = explode("=",$l[$i]);
                if(count($param)>1){
                    $value = $m[(int)substr($param[1],1)];
                    $params[$param[0]] = $value;
                }
            }
            return [$root.$l[1].".php",$params];
        }
    }
    $f = substr(preg_replace("#[^a-z0-9A-Z/\-\_]+#","",$route),strlen($root));
    if(!in_array($f,$fileignore) && file_exists("application/views/".$root.$f.".php")){
        return [$root.$f.".php",[]];
    }
    return false;
}
// hàm để load một module
function getModule($module)
{
    // tạo biến lưu trữ cache nếu chưa có
    if(!isset($GLOBALS['modules'])){
       $GLOBALS['modules'] = [];
    }
    // kiểm tra module đã có cache chưa, nếu có thì dùng code đã cache
    if(array_key_exists($module, $GLOBALS['modules'])){
        return requestModule($module);
    }
    // load module từ thư mục modules vào cache
    include "application/modules/$module.php";
    return requestModule($module);
}
function requestModule($module){
    // trả về module từ bộ nhớ cache
    $m = $GLOBALS['modules'][$module];
    // xác định loại module là class hay object
    if($m[0]=="className"){
        return $m[1];
    }else{
        $c = $m[1];
        return $c;
    }
}
function objectAssign($o1,$o2,$o3=null)
{
    // php like Object.assign
    foreach ($o2 as $k=>$v){
        $o1->{$k} = $v;
    }
    if($o3){
        objectAssign($o1,$o3);
    }
}
function explodeFilter($e,$s){
    // split string but remove empty string
    return array_filter(explode($e,$s), "strlen");
}
function trimLower($s){
    // shorthand to compare strings
    return trim(mb_strtolower($s));
}
function htmlwithoutxss($cc){
    // remove all xss but allow html
	$rs = $cc;
	do{
		$cc = $rs;
		$rs = preg_replace("~<script|on\w+ *?=|javascript|<\?|\?>|<%~", "", $cc);
	}while($rs != $cc);
	return $rs;
}
function to_slug($str) {
    $str = trim(mb_strtolower($str));
    $str = preg_replace('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $str);
    $str = preg_replace('/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $str);
    $str = preg_replace('/(ì|í|ị|ỉ|ĩ)/', 'i', $str);
    $str = preg_replace('/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $str);
    $str = preg_replace('/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $str);
    $str = preg_replace('/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $str);
    $str = preg_replace('/(đ)/', 'd', $str);
    $str = preg_replace('/[^a-z0-9-\s]/', '', $str);
    $str = preg_replace('/([\s]+)/', '-', $str);
    return $str;
}
function getPermGroup($groupid){
    $group = buildSearch(["id"=>$groupid])->exec("permgroup");
    if(!$group){
        return new stdClass();
    }
    return $group;
}
function requirePerm($perm ="ajax"){
    if($perm == "ajax"){
        $perm = $_POST['ajax'];
    }
    
    $permEnable = buildSearch(["id"=>$_SESSION["id"]])->project("perm")->exec("users");
    
    if(!$permEnable){
        die("Bạn chưa được cấp quyền thực hiện thao tác này");
    }
    $permEnable = $permEnable->perm;
   
    if(property_exists($permEnable,$perm)){
        if($permEnable->$perm === true){

        }else{
            die("Bạn chưa được cấp quyền thực hiện thao tác này");
        }
    }else{
        if(property_exists("group",$perm)){
            $permgroup = getPermGroup($permEnable->group);
            if(property_exists($permgroup,$perm)){
                if($permgroup->$perm === true){

                }else{
                    die("Bạn chưa được cấp quyền thực hiện thao tác này");
                }
            }
        }else
        die("Bạn chưa được cấp quyền thực hiện thao tác này");
    }
}
require_once "sqlbuilder.php";