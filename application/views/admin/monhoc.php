<?
$listmh = [];
checkLogin();
    $ft = buildSearch();
    $g = $_GET;
    $name =$g['name'] ?? "";
    $id =$g['id'] ?? "";
    $nganh =$g['nganh'] ?? "";
    if($name){
        $ft->addLike("name", $name);
    }
    if($id){
        $ft->addLike("mamonhoc", $id);
    }
    if($nganh){
        $ft->addLike("nganh", $nganh);
    }
    $pagem = $g['pagem'] ?? 0;
    $listmh = $ft->project("monhoc.*,COUNT(cauhoi.id) as num")->
        leftJoin("cauhoi","cauhoi.idmonhoc=monhoc.id")->
        groupBy("monhoc.id")->limit(20)->
        paginate(20,(int)$pagem)->exec("monhoc");
    if(!$listmh){
        $listmh=[];
        $total = 0;
    }else $total = $listmh[0]->total;
$catid = "qlmh";
include "header.htm" ?>
    <style>
        .monhoc{
            background-color: rgb(238, 237, 237);
            padding: 8px;
            border-radius: 8px;
            transition: all .5s;
            margin-bottom: 8px;
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
        <div class="flex">
            <input value="<?=$name?>"type="text" class="form-control" placeholder="Tên học phần"><button onclick="search(this,'name')" type="button" class="btn btn-primary">
                <i class="fa fa-search"></i>
            </button>
            <input value="<?=$id?>" type="text" class="form-control" placeholder="Mã học phần"><button onclick="search(this,'id')" type="button" class="btn btn-primary">
                <i class="fa fa-search"></i>
            </button>
            <input value="<?=$nganh?>" type="text" class="form-control" placeholder="Ngành học"><button onclick="search(this,'nganh')" type="button" class="btn btn-primary">
                <i class="fa fa-search"></i>
            </button>
            <script>
                function search(btn,t){
                    var qr = location.search;
                    var val = btn.previousElementSibling.value;
                    if(qr.indexOf(t+"=")>0){
                        qr = qr.replace(new RegExp("([\\?&])"+t+"=[^&]*"),"$1"+t+"="+val);
                    }else if(qr.length>0){
                        qr+="&"+t+"="+val;
                    }else{
                        qr = "?"+t+"="+val;
                    }
                    location = location.pathname+qr;
                }
            </script>
        </div>
    </div>
    <div class="section bg-light">
        <div class="section-title">Danh sách môn học</div>
        <button onclick="location='/admin/monhoc/add'" class="btn btn-primary" style="float:right;">Thêm môn học</button>
        <div class="section-body">
            <table class="table table-hover bg-white">
                <thead>
                    <tr>
                        <td>Mã học phần</td>
                        <td>Tên</td>
                        <td>Ngành</td>
                        <td>Số câu hỏi</td>
                        <td>Số cán bộ</td>
                        <td>Câu hỏi</td>
                        <td>Cán bộ</td>
                        <td>Sửa thông tin</td>
                    </tr>
                </thead>
                <tbody>
                <? for ($i = 0;$i<count($listmh);$i++){ ?>
                    <tr>
                        <td><?=$listmh[$i]->mamonhoc?></td>
                        <td><?=$listmh[$i]->name?></td>
                        <td><?=$listmh[$i]->nganh?></td>
                        <td><?=$listmh[$i]->num?></td>
                        <td>Số cán bộ</td>
                        <td><a href="/admin/monhoc/cauhoi/<?=$listmh[$i]->id?>">Xem</a></td>
                        <td><a href="/admin/monhoc/canbo/<?=$listmh[$i]->id?>">Xem</a></td>
                        <td><a href="/admin/monhoc/<?=$listmh[$i]->id?>">Sửa</a></td>
                    </tr>
                <? } ?>
                </tbody>
            </table>
        </div>
    </div>
<? include "footer.htm" ?>