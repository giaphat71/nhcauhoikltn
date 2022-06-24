<?
$listhistory = [];
checkLogin();
$ft = buildSearch();
$g = $_GET;
$type = $g['type'] ?? "";
if($type){
    $ft->add("action",$g['type']);
}
if(isset($g['user']) && $g['user']){
    $ft->addLike("users.name",$g['user']);
}
$pagem = $g['page'] ?? 0;
$listhistory = $ft->project("
    history.id,
    history.author,
    history.content,
    history.addtime,
    history.action,
    LENGTH(history.datarollback) as rollbacklen,
    users.name as uname")->
    leftJoin("users","users.id=author")->
    paginate(30,(int)$pagem)->sort("history.id DESC")->limit(30)->exec("history");
if(!$listhistory){
    $listhistory = [
    ];
}
$typelist= [
    "addquestion" => "Thêm câu hỏi",
    "updatequestion" => "Sửa câu hỏi",
    "deletequest" => "Xóa câu hỏi",
    "addmatrix" => "Thêm ma trận",
    "updatematrix" => "Sửa ma trận",
    "saveasroot" => "Lưu làm đề gốc",
    "runmatrix" => "Chạy ma trận",
    "addpermtouser" => "Cấp quyền môn học",
    "lockuserperm" => "Khóa/mở quyền môn học",
    "deluserperm" => "Xóa quyền môn học",
    "addmonhoc" => "Thêm môn học",
    "updatemonhoc" => "Sửa môn học",
    "deletemonhoc" => "Xóa môn học",
    "addcanbo" => "Thêm cán bộ",
    "updatecanbo" => "Sửa cán bộ",
    "deletecanbo" => "Xóa cán bộ",
    "acceptuser"=> "Chấp nhận đăng ký",
    "addtieuchi" => "Thêm tiêu chí",
    "savetieuchi" => "Sửa tiêu chí",
    "login" => "Đăng nhập",
    "register" => "Đăng ký",
    "saveperm"=>"Sửa quyền",
    "changePass"=>"Đổi mật khẩu",
    "saveasroot"=>"Lưu đề gốc",
    "saveInfo"=>"Sửa thông tin",
];
$catid = "ht";
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
            <select value="<?=$type?>" class="form-control">
                <option value="">Tất cả</option>
                <? foreach ($typelist as $k => $v): ?>
                    <option value="<?=$k?>" <?=($type == $k)?"selected":"" ?>><?=$v?></option>
                <? endforeach; ?>
            </select>
            <button onclick="search(this,'type')" type="button" class="btn btn-primary">
                <i class="fa fa-search"></i>
            </button>
            <input value="<?=$_GET['user']??""?>" type="text" class="form-control" placeholder="Tên người thao tác">
            <button onclick="search(this,'user')" type="button" class="btn btn-primary">
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
                    qr = qr.replace(/page=(\d+)/g,"page=0");
                    location = location.pathname+qr;
                }
            </script>
        </div>
    </div>
    <div class="section bg-light">
        <div class="section-title">Lịch sử hoạt động</div>
        <div class="section-body">
            <table class="table table-hover bg-white" style="table-layout:auto">
                <thead>
                    <tr>
                        <td>Thời gian</td>
                        <td>Người thực hiện</td>
                        <td>Loại</td>
                        <td>Nội dung</td>
                        <td>Phục hồi</td>
                    </tr>
                </thead>
                <tbody>
                <? 
                
                for ($i = 0;$i<count($listhistory);$i++){ ?>
                    <tr>
                        <td><?=date("H:m:s m/d/y",strtotime($listhistory[$i]->addtime));?></td>
                        <td><a href="/admin/canbo/<?=$listhistory[$i]->author?>"><?=$listhistory[$i]->uname?></a></td>
                        <td><?=$typelist[$listhistory[$i]->action]?></td>
                        <td><?=$listhistory[$i]->content?></td>
                        <td>
                            <?php
                                if((int)$listhistory[$i]->rollbacklen > 0){
                                    echo '<a href="javascript:void(0)" onclick="rollback(this,'.$listhistory[$i]->id.')">Phục hồi</a>';
                                } 
                            ?>
                        </td>
                        
                    </tr>
                <? } ?>
                </tbody>
            </table>
        </div>
        <div class="pagination">
            <?=renderPagination(getTotalRow($listhistory),(int)$pagem,30,"/admin/history?page={i}","page")?>
        </div>
    </div>
    <script>
        function rollback(btn,id){
            var r = confirm("Bạn có chắc chắn muốn phục hồi?");
            if(r){
                var xhr = new XMLHttpRequest();
                xhr.open("POST","/admin/ajax");
                xhr.send("ajax=rollback&id="+id);
                xhr.onreadystatechange = function(){
                    if(xhr.readyState == 4 && xhr.status == 200){
                        var res = JSON.parse(xhr.responseText);
                        if(res.status == 1){
                            btn.innerHTML = "Đã phục hồi";
                            btn.onclick = null;
                        }
                    }
                }
            }
        }
    </script>
<? include "footer.htm" ?>