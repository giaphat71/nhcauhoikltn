<?php

class User extends CI_Controller
{
    public function index()
    {
        checkLogin();
        // if($_SESSION['isAdmin']){
        //     header('Location: /admin');
        //     die();
        // }
        $this->load->view('canbo/index.php');
    }
    public function login(){
        $this->load->view('login.php');
    }
    public function register(){
        $this->load->view('register.php');
    }
    public function ajax()
    {
        include 'Ajax/User.php';
    }
    public function _remap($method, $params = array())
    {
        $uri = explode("?",$_SERVER['REQUEST_URI'])[0];
        $route = parseRouteMap("canbo/routemap",$uri);
        if($route){
            $this->load->view($route[0],$route[1]);
        }else{
            $method = $method;
            if (method_exists($this, $method))
            {
                return call_user_func_array(array($this, $method), $params);
            }else{
                show_404();
            }
        }
    }
}
