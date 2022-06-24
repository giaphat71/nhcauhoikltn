<?
checkLogin();
$idcanbo = (int)$_SESSION['id'];
$pageq = $_GET['pageq'] ?? 0;
$lishch = buildSearch([
        "idmonhoc"=>$idmonhoc,
        "||"=>[["author",$idcanbo],["isshare",1]]
    ])->sort("addtime DESC")->asJson("data")->asJson("tieuchi")->limit(20)->paginate(20,$pageq)->exec("cauhoi") ?? [];
$quest = getModule("quest");
include "header.htm";
?>
<style>
    .monhoc{
        background-color: rgb(238, 237, 237);
        padding: 8px;
        border-radius: 8px;
        transition: all .5s;
    }
    .monhoc:hover{
        background-color: rgb(219, 219, 219);
    }
    .monhoc-title{
        font-size: 24px;
    }
    .monhoc-num{
        font-size:12px;
    }
    .section-title{
        font-size: 24px;
        font-weight: bold;
        display: inline-block;
        border-bottom: 1px solid gray;
        margin-bottom: 6px;
    }
    .section{
        padding: 8px;
    }
    .selected{
        background-color: #fff;
        color:red;
    }
</style>
<div class="section bg-light">
    <div class="section-title">Các câu hỏi của học phần</div>
    <button type="button" class="btn btn-primary" style="float:right;" onclick="location='/canbo/themcauhoi/<?=$idmonhoc?>'">Thêm câu hỏi</button>
    <button type="button" class="btn btn-primary" style="float:right;margin-right:4px;" onclick="location='/canbo/matrix/<?=$idmonhoc?>'">Ma trận đề</button>
    <div class="section-body">
        <? for($i=0; $i<count($lishch); $i++){ 
            $q = Question::GetQuestByType($lishch[$i]->type)->load($lishch[$i]);
            ?>
            <div class="quest-wrapper" qid="<?=$lishch[$i]->id?>">
                <?=$q->render(true,true,null,true)?>
            </div>
        <? } ?>
    </div>
    <div class="pagination">
        <?=renderPagination(getTotalRow($lishch),$pageq,20,"/canbo/list-cauhoi/".$idmonhoc."?pageq={i}")?>
    </div>
    <script src="/javascripts/questedit.js"></script>
    <script>
        var context = <?=$idmonhoc?>;
        
        function getAnsId(d){
            while(d.className!="quest-wrapper"){
                d = d.parentElement;
            }
            return d.getAttribute("qid");
        }
        function editQuest(btn){
            var id = getAnsId(btn);
            location = "/canbo/cauhoi/"+context+"/"+id;   
        }
        function delQuest(btn){
            var id = getAnsId(btn);
            if(confirm("Bạn có chắc chắn muốn xóa câu hỏi này?")){
                fetch("/user/ajax",{
                    method:"POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded"},
                    body: `ajax=deletequest&idmonhoc=${context}&id=${id}`
                }).then(t=>t.text()).then(t=>{
                    alert(t);
                });
            }
        }
        // cấu hình hiển thị ký hiệu toán học
        MathJax = {
            tex: {
                inlineMath: [['[math]', '[/math]']]
            },
            svg: {
                fontCache: 'global'
            }
        };
        showAnswer();
    </script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
    <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
    
</div>
<?
include "footer.htm";
?>