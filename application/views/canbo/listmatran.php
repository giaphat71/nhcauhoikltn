<?
checkLogin();
$idcanbo = (int)$_SESSION['id'];
$lishmt = buildSearch([
        "idmonhoc"=>$idmonhoc,
        "||"=>[["author",$idcanbo],["isshare",1]]
    ])->sort("addtime DESC")->asJson("data")->asJson("tieuchi")->limit(100)->exec("matrix") ?? [];
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
    <div class="section-title">Các câu hỏi của học phần
        
    </div>
    <button type="button" class="btn btn-primary" style="float:right;" onclick="location='/canbo/themcauhoi/<?=$idmonhoc?>'">Thêm câu hỏi</button>
    <button type="button" class="btn btn-primary" style="float:right;margin-right:4px;" onclick="location='/canbo/matrix/<?=$idmonhoc?>'">Tạo đề</button>
    <div class="section-body">
        <?  
            for($i=0; $i<count($lishmt); $i++){ 
                ?>
                    <div class="monhoc" onclick="location='/canbo/matrix/<?=$idmonhoc?>/<?=$lishmt[$i]->id?>'">
                    <div class="monhoc-title"><?=$lishmt[$i]->name?></div>
                    <div class="monhoc-num">Câu hỏi: <?=$lishmt[$i]->num?></div>
                <?
            }
        ?>
    </div>
</div>
<?
include "footer.htm";
?>