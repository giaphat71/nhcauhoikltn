<?php
class History{
    public function PrepareContent($content){
        $content = str_replace("<","&lt;",$content);
        $content = str_replace(">","&gt;",$content);
        $content = str_replace("\n","<br>",$content);
        $content = preg_replace("#\[url=([^\]]*)\](.*?)\[/url\]#","<a href=\"$1\">$2</a>",$content);
        $content = preg_replace("#\[url=([^\]]*)\]#","<a href=\"$1\">$1</a>",$content);
        $content = preg_replace("#\[img=([^\]]*)\]#","<img src=\"$1\">",$content);
        
        return $content;
    }
    public function Insert($action,$content, $data=""){
        $author = $_SESSION['id'];
        $content = $this->PrepareContent($content);
        buildInsert(["action"=>$action,"content"=>$content,"author"=>(int)$_SESSION['id'], 'datarollback'=>$data])->exec("history");
    }
    public function Ajax($content,$data="")
    {
        $ajax = $_POST['ajax'];
        
        $content = $this->PrepareContent($content);
        var_dump($content);
        buildInsert(["action"=>$ajax,"content"=>$content,"author"=>(int)$_SESSION['id'], 'datarollback'=>$data])->exec("history");
    }
    public function Record($action = "", $user = 0, $page = 0)
    {
        if($user > 0){
            if($action){
                return buildSearch(["action"=>$action, "author"=>$user])->sort("id DESC")->limit(50)->paginate(50,(int)$page)->exec("history");
            }else{
                return buildSearch(["author"=>$user])->sort("id DESC")->limit(50)->paginate(50,(int)$page)->exec("history");
            }
        }else{
            if($action){
                return buildSearch(["action"=>$action])->sort("id DESC")->limit(50)->paginate(50,(int)$page)->exec("history");
            }else{
                return buildSearch()->sort("id DESC")->limit(50)->paginate(50,(int)$page)->exec("history");
            }
        }
    }
}
$GLOBALS['modules']['history'] = ["object",new History()];