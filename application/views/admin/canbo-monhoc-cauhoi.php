<?
$listu = [];$listq = [];
$quest=getModule("quest");
checkLogin();
$pageq = $_GET['pageq'] ?? 0;
$mh = buildSearch(["id"=>$idmonhoc])->exec("monhoc");
$listq = buildSearch(["idmonhoc"=>$idmonhoc,"author"=>$idcanbo])->limit(20)->paginate(20,$pageq)->exec("cauhoi") ?? [];

$catid="qlmh";
include "header.htm" ?>
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
    </style>
    <div class="section bg-light">
        <div class="section-title">Các câu hỏi của môn học và cán bộ này</div>
        <div class="section-body">
            <? for($i=0; $i<count($listq); $i++){ 
                $q = Question::GetQuestByType($listq[$i]->type)->load($listq[$i]);
                ?>
                <div class="quest-wrapper" qid="<?=$listq[$i]->id?>">
                    <?=$q->render(true,true,null,true)?>
                </div>
            <? } ?>
        </div>
        <script src="/javascripts/questedit.js"></script>
        <script>
            var context = <?=$idmonhoc?>;
            showAnswer();
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
            MathJax = {
                tex: {
                    inlineMath: [['[math]', '[/math]']]
                },
                svg: {
                    fontCache: 'global'
                }
            };
        </script>
        <div class="pagination">
            <?=renderPagination(getTotalRow($listq),$pageq,20,"/admin/monhoc/cauhoi/".$idmonhoc."?pageq={i}")?>
        </div>
    </div>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
    <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
<? include "footer.htm" ?>