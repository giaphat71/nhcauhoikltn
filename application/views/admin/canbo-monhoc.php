<?
checkLogin();
$listmh = buildSearch(['idcanbo'=>$idcanbo])
    ->project("monhoc.*, count(cauhoi.id) as num,permission.id as _id,permission.islocked")
    ->leftJoin("cauhoi","cauhoi.idmonhoc=permission.idmonhoc")
    ->groupBy("permission.idmonhoc")
    ->leftJoin("monhoc","monhoc.id=permission.idmonhoc")
    ->limit(999)
    ->exec("permission") ?? [];
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
        <div class="section-title">Danh sách môn học</div>
        <button type="button" class="btn btn-primary" style="float:right;" onclick="addMonhoc()">Thêm môn học</button>
        <div class="section-body">
            <table class="table table-hover bg-white">
                <thead>
                    <tr>
                        <td>Mã môn học</td>
                        <td>Tên</td>
                        <td>Ngành</td>
                        <td>Số câu hỏi</td>
                        <td>Câu hỏi</td>
                        <td>Khóa/Mở khóa</td>
                        <td>Gỡ quyền hạn</td>
                    </tr>
                </thead>
                <tbody>
                <? foreach($listmh as $row){?>
                    <tr>
                        <td><?=$row->mamonhoc?></td>
                        <td><?=$row->name?></td>
                        <td><?=$row->nganh?></td>
                        <td><?=$row->num?></td>
                        <td><a href="/admin/canbo/<?=$idcanbo?>/monhoc/<?=$row->id?>">Xem</a></td>
                        <td><a href="javascript:void(0);" onclick="lockPerm('<?=$row->_id?>')">
                        <?=$row->islocked?"Mở khóa":"Khóa"?></a></td>
                        <td><a href="javascript:void(0);" onclick="removePerm('<?=$row->_id?>')">Xóa</a></td>
                    </tr>
                <? } ?>
                </tbody>
            </table>
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
        function addUserPerm(mh){
            var id = '<?=$idcanbo?>';
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
        function addMonhoc(){
            var md = createModal("Thêm học phần");
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
                body: `ajax=findmh&name=${t}`
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