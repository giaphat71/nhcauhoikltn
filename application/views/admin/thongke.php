<?
checkLogin();
$catid="tk";
$page = $_GET['page'] ?? 0;
$page = intval($page);
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
    <? if($page < 1) { ?>
    <div class="section bg-light">
        <div class="section-title">Câu hỏi</div>
        <div class="section-body">
            <!-- bootstrap card -->
            <div class="row m-0">
                <div class="card col-3">
                    <div class="card-body" style="font-size: 24px;">
                        <?=buildSearch()->project("COUNT(1) as total")->exec("monhoc")->total ?>
                    </div>
                    <h5 class="card-title">Số môn học</h5>
                    
                </div>
                <div class="card col-3">
                    <div class="card-body" style="font-size: 24px;">
                        <?=buildSearch()->project("COUNT(1) as total")->exec("cauhoi")->total ?>
                    </div>
                    <h5 class="card-title">Số câu hỏi</h5>
                    
                </div>
                <div class="card col-3">
                    <div class="card-body" style="font-size: 24px;">
                        <?=buildSearch(["addtime"=>['exp',"> NOW() - INTERVAL 1 DAY"]])->project("COUNT(1) as total")->exec("cauhoi")->total ?>
                    </div>
                    <h5 class="card-title">Số câu hỏi hôm nay</h5>
                    
                </div>
            </div>
            
        </div>
    </div>
    <div class="section bg-light">
        <div class="section-title">Cán bộ</div>
        <div class="section-body">
            <!-- bootstrap card -->
            <div class="row m-0">
                <div class="card col-3">
                    <div class="card-body" style="font-size: 24px;">
                        <?=buildSearch()->project("COUNT(1) as total")->exec("users")->total ?>
                    </div>
                    <h5 class="card-title">Số cán bộ</h5>
                    
                </div>
            </div>
            
        </div>
    </div>
    <div class="section bg-light">
        <div class="section-title">Ma trận đề</div>
        <div class="section-body">
            <!-- bootstrap card -->
            <div class="row m-0">
                <div class="card col-3">
                    <div class="card-body" style="font-size: 24px;">
                        <?=buildSearch()->project("COUNT(1) as total")->exec("matrix")->total ?>
                    </div>
                    <h5 class="card-title">Số ma trận đề</h5>
                    
                </div>
                <div class="card col-3">
                    <div class="card-body" style="font-size: 24px;">
                        <?=buildSearch()->project("COUNT(1) as total")->exec("matrixresult")->total ?>
                    </div>
                    <h5 class="card-title">Số đề được tạo</h5>
                    
                </div>
                <div class="card col-3">
                    <div class="card-body" style="font-size: 24px;">
                        <?=buildSearch(["addtime"=>['exp',"> NOW() - INTERVAL 1 DAY"]])->project("COUNT(1) as total")->exec("matrixresult")->total ?>
                    </div>
                    <h5 class="card-title">Số đề tạo hôm nay</h5>
                    
                </div>
            </div>
        </div>
    </div>
    <? } ?>
    <div class="section bg-light">
        <div class="section-title">Các đề được tạo</div>
        <div class="section-body">
            <table class="table table-hover bg-white">
                <thead>
                    <tr>
                        <td>Cán bộ</td>
                        <td>Môn học</td>
                        <td>Ma trận</td>
                        <td>Số câu hỏi</td>
                        <td>Kết quả</td>
                    </tr>
                </thead>
                <tbody>
                <? 
                $list = buildSearch()
                                ->leftJoin("users","users.id=runner")
                                ->leftJoin("monhoc","monhoc.id=idmonhoc")
                                ->leftJoin("matrix","matrix.id=idmatrix")
                                ->project("matrixresult.*,users.name as uname,monhoc.name as mname,matrix.name as mxname,matrix.questioncount as questcount")
                                ->limit(10)->paginate(10,$page)->sort("matrixresult.id DESC")->exec("matrixresult");
                if($list){
                    $total = getTotalRow($list);
                    for($i=0; $i<count($list); $i++){  ?>
                        <tr>
                            <td><?=$list[$i]->uname?></td>
                            <td><?=$list[$i]->mname?></td>
                            <td><?=$list[$i]->mxname?></td>
                            <td><?=$list[$i]->questcount?></td>
                            <td><a href="/canbo/runmatrix/<?=$list[$i]->idmonhoc?>/<?=$list[$i]->idmatrix?>/?idresult=<?=$list[$i]->id?>">Xem kết quả</a></td>
                        </tr>
                    <? } 
                }else{
                    $total = 0;
                }
                ?>
                </tbody>
            </table>
        </div>
        <div class="pagination">
            <?=renderPagination($total,$page,10,"/admin/thongke?page={i}")?>
        </div>
    </div>
    <script>
    </script>
<? include "footer.htm" ?>