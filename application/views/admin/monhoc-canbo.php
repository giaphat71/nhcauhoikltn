<?php
checkLogin();
$pageu = $_GET['pageu'] ?? 0;
$listu = buildSearch(['permission.idmonhoc'=>$idmonhoc])
        ->project("users.*, count(cauhoi.id) as num,permission.id as _id,permission.islocked")
        ->leftJoin("cauhoi","cauhoi.idmonhoc=permission.idmonhoc")
        ->groupBy("users.id")
        ->leftJoin("users","users.id=permission.idcanbo")
        ->limit(20)
        ->exec("permission") ?? [];
$total = count($listu);
$catid="qlcb";
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
        <div class="section-title">Danh sách cán bộ của môn học <?="abc"?></div>
        <button type="button" class="btn btn-primary" style="float:right;" onclick="addCanbo()">Cấp quyền</button>
        <div class="section-body">
        <table class="table table-hover bg-white">
                <thead>
                    <tr>
                        <td>Tài khoản</td>
                        <td>Tên</td>
                        <td>Đơn vị</td>
                        <td>Số câu hỏi</td>
                        <td>Khóa/Mở khóa</td>
                        <td>Gỡ quyền</td>
                    </tr>
                </thead>
                <tbody>
                <? for($i=0; $i<$total; $i++){  ?>
                    <tr>
                        <td><?=$listu[$i]->account?></td>
                        <td><?=$listu[$i]->name?></td>
                        <td><?=$listu[$i]->donvi?></td>
                        <td><?=$listu[$i]->num?></td>
                        <td><a href="javascript:void(0);" onclick="lockPerm('<?=$listu[$i]->_id?>')">
                        <?=$listu[$i]->islocked?"Mở khóa":"Khóa"?></a></td>
                        <td><a href="javascript:void(0);" onclick="removePerm('<?=$listu[$i]->_id?>')">Xóa</a></td>
                    </tr>
                <? } ?>
                </tbody>
            </table>
        </div>
        <div class="pagination">
            <?=renderPagination($total,$pageu,20,"/admin/monhoc/canbo/".$idmonhoc."?pageu={i}")?>
        </div>
    </div>
    <script>
        function lockPerm(id){
            fetch("/admin/ajax",{
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: `ajax=lockuserperm&id=${id}`
            }).then(function(response){
                return response.text();
            }).then(function(response){
                if(response=="locked"){
                    ui.notif("Đã khóa");
                }else if(response=="unlocked"){
                    ui.notif("Đã mở khóa");
                }else alert(response);
            })
        } 
        function removePerm(id){
            if(confirm("Xác nhận gỡ quyền cán bộ này?"))
            fetch("/admin/ajax",{
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: `ajax=deluserperm&id=${id}`
            }).then(function(response){
                return response.text();
            }).then(function(response){
                if(response=="success"){
                    ui.notif("Đã xóa");
                }else alert(response);
            })
        } 
        function addUserPerm(id){
            var mh = '<?=$idmonhoc?>';
            var btn = event.target;
            fetch("/admin/ajax",{
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: `ajax=addpermtouser&mh=${mh}&canbo=${id}`
            }).then(function(response){
                return response.text();
            }).then(function(response){
                if(response=="success"){
                    ui.notif("Đã thêm");
                    btn.parentElement.remove();
                }else alert(response);
            });
            
        } 
        function addCanbo(){
            var md = createModal("Thêm cán bộ");
            md.body().innerHTML = `<p>Nhập tên để tìm kiếm</p>
                <input class="form-control" type=""text" onchange="dosearchcb(this.value)"><br>
                <div id="tmp-search"></div><br><center>
                <button type="button" class="btn btn-primary">Thêm</button>
            </center>`;
            md.show();
        }
        function dosearchcb(t){
            fetch("/admin/ajax",{
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: `ajax=finduser&name=${t}`
            }).then(function(response){
                return response.json();
            }).then(function(response){
                g("tmp-search").innerHTML="";
                for(var i = 0; i < response.length; i++) {
                    var d = document.createElement("p");
                    d.innerHTML = `<span style="font-size:18px;">${response[i].name}</span>
                    <button style="float:right;" onclick="addUserPerm('${response[i]._id}')" class="btn btn-success">Thêm</button>
                    <div style="clear:both"></div>`;
                    g("tmp-search").appendChild(d);
                }
            })
        }
    </script>
<? include "footer.htm" ?>