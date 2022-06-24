<?php

class Admin extends CI_Controller
{
    public $root = "/admin";
    public function index()
    {
        checkLogin();
        requireAdmin();
        $this->load->view('admin/index.php');
    }
    public function ajax()
    {
        include 'Ajax/Admin.php';
    }
    public function _remap($method, $params = array())
    {
        disableDebug();
        if($method!=="ajax")
        {
            requireAdmin();
        }else{
            return call_user_func_array(array($this, $method), $params);
        }
        
        $uri = explode("?",$_SERVER['REQUEST_URI'])[0];
        $route = parseRouteMap("admin/routemap",$uri);
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
