<?php
checkLogin();
if(isAdmin()){
    $matrix = buildSearch(["id"=>$idmatrix,"idmonhoc"=>$idmonhoc])->exec("matrix");
}else{
    $matrix = buildSearch(["id"=>$idmatrix,"idmonhoc"=>$idmonhoc,"author"=>$_SESSION['id']])->exec("matrix");
}
if(isset($_GET['idresult'])){
    $idresult = (int)$_GET['idresult'];
    $matrixresult = buildSearch(["id"=>(int)$_GET['idresult']])->asJson("data")->exec("matrixresult");
    if(!$matrixresult){
        die("Không tìm thấy kết quả ma trận này");
    }
    $isreplayresult = true;
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
        if(isset($idresult)){
            $challenge = $module->replayResult($idmonhoc,$matrixresult->data);
            $challengeid = $matrixresult->id;
        }else{
            $module->load($idmonhoc,$_SESSION['id'],$allowshare);
            $challenge = $module->generateChallenge($matrix->id,$matrix->data, $runcount);
            if(!$challenge){
                die("Không thể tạo được đề dựa trên ma trận này, hãy kiểm tra số câu hỏi có phù hợp không.");
            }
            $challengeid = $challenge[0];
            $challenge = $challenge[1];
        }
        
        if(!$challenge){
            die("Không thể tạo được đề dựa trên ma trận này, hãy kiểm tra số câu hỏi có phù hợp không.");
        }
        for($i=0; $i<count($challenge); $i++){
            ?>
            <div id="de-<?=$i?>" idresult="<?=$challengeid?>">
                <h1>Đề <?=($i+1)?>
                    <div style="float:right;">
                        <? if(!isset($idresult)) { ?> <button class="btn btn-primary" onclick="saveAsRoot(this)">Lưu đề gốc</button> <? } ?>
                        <button class="btn btn-primary" onclick="printThis(this)">In đề này</button>
                        <button class="btn btn-primary" onclick="showAnswer(this)">Xem đáp án</button>
                    </div>
                </h1>
            <?
            
            for($j=0; $j<count($challenge[$i]);$j++){
                $q = Question::GetQuestByType($challenge[$i][$j]->type)->load($challenge[$i][$j]);
                echo $q->render(false,false,"Câu ".($j+1).".",true);
            }
            ?>
            </div>
            <?
        } 
    ?>
</div>
<script>
    function showAnswer(btn){
        if(btn.textContent.contain("Xem")){
            q(".showans + [quest=choosable]").forEach(e=>{
                var ansindex = e.previousElementSibling.textContent;
                e.children[parseInt(ansindex)].classList.add("showans");
            });
            btn.textContent = "Ẩn đáp án";
        }else{
            q(".showans + [quest=choosable]").forEach(e=>{
                var ansindex = e.previousElementSibling.textContent;
                e.children[parseInt(ansindex)].classList.remove("showans");
            });
            btn.textContent = "Xem đáp án";
        }
        
    }
    function getQuestHeader(btn){
        
        while(!btn.hasAttribute("idresult")){
            btn = btn.parentElement;
        }
        return btn;
    }
    function printThis(btn){
        var header = getQuestHeader(btn);
        var id = header.id.split("-")[1];
        var idresult =header.getAttribute("idresult");
        var url = "/canbo/print?idchallenge="+id+"&idresult="+idresult+"&idmonhoc=<?=$idmonhoc?>";
        var win = window.open(url, '_blank');
        win.focus();
    }
    function saveAsRoot(btn){
        var header = getQuestHeader(btn);
        var id = header.id.split("-")[1];
        var idresult = header.getAttribute("idresult");
        fetch("/canbo/ajax",{
            method:"POST",
            headers:{
                "Content-Type":"application/x-www-form-urlencoded"
            },
            body:"ajax=saveasroot&idmatrix=<?=$idmatrix?>&idmonhoc=<?=$idmonhoc?>&idchallenge="+id+"&idresult="+idresult
            
        }).then(res=>res.text()).then(data=>{
            if(data== "success"){
                alert("Đã lưu đề gốc thành công");
            }else{
                alert("Lưu đề gốc thất bại");
            }
        });

    }
    MathJax = {
        tex: {
            inlineMath: [['[math]', '[/math]']]
        },
        svg: {
            fontCache: 'global'
        }
    };
</script>
<script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
<script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
<style>
    .showans::first-letter{
        text-decoration: underline;
        color: red;
    }
</style>
<?php include "footer.htm" ?>