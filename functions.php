<?php
class MODULES{

}
$user=[];
/**
 * The goal of this file is to allow developers a location
 * where they can overwrite core procedural functions and
 * replace them with their own. This file is loaded during
 * the bootstrap process and is called during the frameworks
 * execution.
 *
 * This can be looked at as a `master helper` file that is
 * loaded early on, and may also contain additional functions
 * that you'd like to use throughout your entire application
 *
 * @see: https://codeigniter4.github.io/CodeIgniter4/
 */
function moveUser($p){
    header("Location: ".$p);
    die();
}
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
function checkLogin(){
    if(!isset($_SESSION))
    session_start();
    if(isset($_SESSION['isLoggedIn'])){
        return true;
    }else{
        moveUser("/user/login");
    }
}
function getUser($id = 0)
{
    if(!$id){
        $id = $_SESSION['id'];
    }
    return buildSearch()->add("id",$id,true)->exec("users");
}
function getMonhoc($id)
{
    return buildSearch()->add("id",$id,true)->asJson("chuongtrinh")->exec("monhoc");
}
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
function isAdmin($id = 0){
    if(!$id){
        $id = $_SESSION['id'];
    }
    $u = getUser($id);
    return $u->isadmin == "1";
}
function checkLock(){
    if(getUser()->islocked){
        die("Tài khoản của bạn đã quản trị viên bị khóa.");
    }
}
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
function hashPassword($user,$password){
    $salt = "asdiuhighw8g289n982@%@#$^@#$^";
    $hash = md5($user.$salt.$password);
    return $hash;
}
function enableDebug()
{
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
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
function renderPagination($total,$current,$limit,$uri){
    $tmpr = function($i,$t,$d,$uri){
        return "<li class=\"page-item $d\">
            <a class=\"page-link\" href=\"".str_replace("{i}",$i,$uri)."\">$t</a>
        </li>";
    };
    $totalpage = floor($total / $limit);
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
function parseRouteMap($file,$route){
    $fdata = file_get_contents("application/views/$file");
    $lines = preg_split("/\r?\n/",$fdata);
    $root = "";
    foreach($lines as $line){
        if(trim($line) == "")continue;
        if($line[0] == "#"){
            $root=explode("=",$line)[1];
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
    if(file_exists("application/views/".$root.$f.".php")){
        return [$root.$f.".php",[]];
    }
    return false;
}
function getModule($module)
{
    if(!isset($GLOBALS['modules'])){
       $GLOBALS['modules'] = [];
    }
    if(array_key_exists($module, $GLOBALS['modules'])){
        return requestModule($module);
    }
    include "application/modules/$module.php";
    return requestModule($module);
}
function requestModule($module){
    $m = $GLOBALS['modules'][$module];
    if($m[0]=="className"){
        return $m[1];
    }else{
        $c = $m[1];
        return $c;
    }
}
function objectAssign($o1,$o2,$o3=null)
{
    foreach ($o2 as $k=>$v){
        $o1->{$k} = $v;
    }
    if($o3){
        objectAssign($o1,$o3);
    }
}
function explodeFilter($e,$s){
    return array_filter(explode($e,$s), "strlen");
}
function trimLower($s){
    return trim(mb_strtolower($s));
}
function htmlwithoutxss($cc){
	$rs = $cc;
	do{
		$cc = $rs;
		$rs = preg_replace("~<script|on\w+ *?=|javascript|<\?|\?>|<%~", "", $cc);
	}while($rs != $cc);
	return $rs;
}
require_once "sqlbuilder.php";