<?php
checkLogin();
if(isAdmin()){
    $matrix = buildSearch(["id"=>$idmatrix,"idmonhoc"=>$idmonhoc])->exec("matrix");
}else{
    $matrix = buildSearch(["id"=>$idmatrix,"idmonhoc"=>$idmonhoc,"author"=>$_SESSION['id']])->exec("matrix");
}
if(!$matrix){
    die("Không tìm thấy ma trận này.");
}
$runcount = (int)($runcount ?? 1);
if($runcount < 1){
    die("Tối thiểu một bộ đề.");
}
$allowshare = $_GET['allowshare'] ?? true;
$showanswer = $_GET['showanswer'] ?? true;
$totalpoint = $matrix->totalpoint;
checkAccessToMonhoc($idmonhoc);
include "header.htm";
?>
<div class="container">
    <?php
        $className = getModule("matrix");
        getModule("quest");
        $module =new $className;
        $module->load($idmonhoc,$_SESSION['id'],$allowshare);
        $challenge = $module->generateChallenge($matrix->data, $runcount);
        for($i=0; $i<count($challenge); $i++){
            echo "<h1>Đề ".($i+1)."</h1>";
            for($j=0; $j<count($challenge[$i]);$j++){
                $q = Question::GetQuestByType($challenge[$i][$j]->type)->load($challenge[$i][$j]);
                echo $q->render(false,false,"Câu ".($j+1).".");
            }
        } 
    ?>
</div>
<?php include "footer.htm" ?>