<?
checkLogin();
$listmh = [];

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
<? include "footer.htm" ?>