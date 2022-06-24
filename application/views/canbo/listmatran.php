<?
checkLogin();
$idcanbo = (int)$_SESSION['id'];
$isadmin = (int)$_SESSION['isAdmin'];
$lishmt = buildSearch(["idmonhoc"=>$idmonhoc]);
if(!$isadmin){
    $lishmt->add("exp","author = $idcanbo OR isshare = 1");
}
$lishmt = $lishmt->sort("addtime DESC")->asJson("data")->asJson("tieuchi")->limit(100)->exec("matrix") ?? [];
$lishmtrs = buildSearch(["rootmatrix.idmonhoc"=>$idmonhoc]);
if(!$isadmin){
    $lishmtrs->add("exp","rootmatrix.author = $idcanbo");
}
$lishmtrs = $lishmtrs->sort("addtime DESC")->leftJoin("matrix","matrix.id = idmatrix")->project("rootmatrix.*, matrix.name")->limit(100)->exec("rootmatrix") ?? [];
include "header.htm";
?>
<style>
    .monhoc{
        background-color: rgb(238, 237, 237);
        padding: 8px;
        border-radius: 8px;
        transition: all .5s;
        margin-bottom: 4px;;
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
    <div class="section-title">Các ma trận của học phần
        
    </div>
    <button type="button" class="btn btn-primary" style="float:right;margin-right:4px;" onclick="location='/canbo/matrix/<?=$idmonhoc?>/0/'">Tạo đề</button>
    <div class="section-body">
        <?  
            for($i=0; $i<count($lishmt); $i++){ 
                ?>
                    <div class="monhoc" onclick="location='/canbo/matrix/<?=$idmonhoc?>/<?=$lishmt[$i]->id?>'">
                    <div class="monhoc-title"><?=$lishmt[$i]->name?></div></div>
                <?
            }
        ?>
    </div>
    <div class="section-title">Các đề gốc của học phần
        </div>
        <div class="section-body">
            <?  
                for($i=0; $i<count($lishmtrs); $i++){ 
                    ?>
                        <div class="monhoc" onclick="location='/canbo/runmatrix/<?=$idmonhoc?>/<?=$lishmtrs[$i]->idmatrix?>/?idresult=<?=$lishmtrs[$i]->idresult?>'">
                        <div class="monhoc-title"><?=$lishmtrs[$i]->name?> - Thời gian: <?=$lishmtrs[$i]->addtime?></div></div>
                    <?
                }
            ?>
        </div>
</div>
<?
include "footer.htm";
?>