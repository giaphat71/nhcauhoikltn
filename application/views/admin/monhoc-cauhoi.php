<?
$listu = [];$listq = [];
$quest=getModule("quest");
checkLogin();
$pageq = $_GET['pageq'] ?? 0;
$mh = buildSearch(["id"=>$idmonhoc])->exec("monhoc");
$listq = buildSearch(["idmonhoc"=>$idmonhoc])->limit(20)->exec("cauhoi") ?? [];

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
        <div class="section-title">Các câu hỏi của môn học này</div>
        <div class="section-body">
            <?  
                for($i=0; $i<count($listq); $i++){
                    $q = Question::GetQuestByType($listq[$i]->type)->load($listq[$i]);
                    echo $q->render();
                }
            ?>
        </div>
        <div class="pagination">
            <?=renderPagination(count($listq),$pageq,20,"/admin/monhoc/cauhoi/".$idmonhoc."?pageq={i}")?>
        </div>
    </div>
<? include "footer.htm" ?>