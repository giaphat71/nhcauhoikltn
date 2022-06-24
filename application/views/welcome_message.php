<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(!checkLogin()){
	moveUser('/user/login');
}else{
	if(isAdmin()){
		moveUser('/admin');
	}
	else{
		moveUser('/canbo');
	}
}
?>
