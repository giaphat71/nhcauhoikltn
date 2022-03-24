<?

checkLogin();
$ft = buildSearch(["isaccept"=>1]);
$g=$_GET;
$name =$g['name']??"";
$nganh =$g['nganh']??"";
if($name){
    $ft->addLike("name", $name);
}
if($nganh){
    $ft->addLike("donvi", $nganh);
}
$pageu = $g['pageu'] ?? 0;
$listcb= $ft->project("users.*, count(permission.id) as num")->groupBy("users.id")->leftJoin("permission","permission.idcanbo=users.id")->limit(20)->paginate(20,(int)$pageu)->exec("users") ?? [];
if(!$listcb){
    $listcb = [];
    $total = 0;
}else{
    $total = $listcb[0]->total;
}
$listcbdk= buildSearch(['isaccept'=>0])->limit(100)->exec("users") ?? [];
if(!$listcb){
    $listcb = [];
    $total = 0;
}else{
    $total = $listcb[0]->total;
}
$catid = "qlcb";
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
            <input value="<?=$name?>"type="text" class="form-control" placeholder="Tên cán bộ"><button onclick="search(this,'name')" type="button" class="btn btn-primary">
                <i class="fa fa-search"></i>
            </button>
            <input value="<?=$nganh?>" type="text" class="form-control" placeholder="Đơn vị"><button onclick="search(this,'nganh')" type="button" class="btn btn-primary">
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
        <div class="section-title">Danh sách cán bộ</div>
        <button onclick="location='/admin/canbo/add'" class="btn btn-primary" style="float:right;">Thêm cán bộ</button>
        <div class="section-body">
            <table class="table table-hover bg-white">
                <thead>
                    <tr>
                        <td>Tài khoản</td>
                        <td>Tên</td>
                        <td>Là quản trị</td>
                        <td>Đơn vị</td>
                        <td>Số môn học</td>
                        <td>Môn học</td>
                        <td>Sửa thông tin</td>
                        <td>Khóa</td>
                    </tr>
                </thead>
                <tbody>
                <? for($i=0; $i<count($listcb); $i++){ ?>
                    <tr>
                        <td><?=$listcb[$i]->account?></td>
                        <td><?=$listcb[$i]->name?></td>
                        <td><?=$listcb[$i]->isadmin?"Phải":"Không"?></td>
                        <td><?=$listcb[$i]->donvi?></td>
                        <td><?=$listcb[$i]->num?></td>
                        <td><a href="/admin/canbo/<?=$listcb[$i]->id?>/monhoc">Xem</a></td>
                        <td><a href="/admin/canbo/<?=$listcb[$i]->id?>">Sửa</a></td>
                        <td><a href="javascript:" onclick="lockUser(this,<?=$listcb[$i]->id?>)">Khóa tài khoản</a></td>
                    </tr>
                <? } ?>
                </tbody>
            </table>
        </div>
        <div class="pagination">
            <?=renderPagination($total, (int)$pageu,20,"/admin/canbo?pageu={i}")?>
        </div>
    </div>
    <br>
    <div class="section bg-light">
        <div class="section-title">Danh sách cán bộ đang chờ xác nhận</div>
        <div class="section-body">
            <table class="table table-hover bg-white">
                <thead>
                    <tr>
                        <td>Tài khoản</td>
                        <td>Họ và tên</td>
                        <td>Email</td>
                        <td>Đơn vị</td>
                        <td>Xác nhận</td>
                    </tr>
                </thead>
                <tbody>
                <?  include "./donvi.php"; 
                    for($i=0; $i<count($listcbdk); $i++){ ?>
                    <tr>
                        <td><?=$listcbdk[$i]->account?></td>
                        <td><?=$listcbdk[$i]->name?></td>
                        <td><?=$listcbdk[$i]->email?></td>
                        <td><?=$listdonvi[$listcbdk[$i]->donvi]?></td>
                        <td><a href="javascript:" onclick="acceptUser(this,<?=$listcbdk[$i]->id?>)">Xác nhận</a></td>
                    </tr>
                <? } ?>
                </tbody>
            </table>
        </div>
        <div class="pagination">
            <?=renderPagination($total, (int)$pageu,20,"/admin/canbo?pageu={i}")?>
        </div>
    </div>
    <script>
        function acceptUser(btn,id){
            fetch("/admin/ajax",{
                method: "POST",
                headers: {"content-type": "application/x-www-form-urlencoded"},
                body: "ajax=acceptuser&id="+id
            }).then(async a=>{
                var b = await a.text();
                if(b=="success"){
                    btn.parentElement.parentElement.style.background = "#c3f9c3;";
                }
            });
        }
    </script>
<? include "footer.htm" ?>