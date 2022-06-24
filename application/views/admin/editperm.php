<?
checkLogin();
requirePerm("grant");
$u = getUser($idcanbo);
if(!$u){
    show_404();
}
$perm = $u->perm;
function isPerm($perm,$name)
{
    if(property_exists($perm, $name)){
        return $perm->$name;
    }
    return false;
}
$catid="qlcb";
$permlistuser = [
    "addquestion" => "Thêm câu hỏi",
    "updatequestion" => "Sửa câu hỏi",
    "deletequest" => "Xóa câu hỏi",
    "addmatrix" => "Thêm ma trận",
    "updatematrix" => "Sửa ma trận",
    "saveasroot" => "Lưu làm đề gốc",
    "runmatrix" => "Chạy ma trận",
];
$permlistadmin = [
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
    "rollback" => "Khôi phục dữ liệu"
];
$permgroup = buildSearch()->exec("permgroup");

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
        <div class="section-title">Thông tin quyền hạn của <?=$u->name?></div>
        <div class="section-body">
            <div class="flex">
                <div style="flex:1;padding: 12px;font-size:24px;">Nhóm quyền hạn: </div>
                <div style="padding: 12px;display:flex;">
                    <select class="form-control" id="permgroup" value="<?=($perm->group > 0)?$perm->group:""?>">
                        <option value="">Chọn nhóm quyền hạn</option>
                        <? foreach($permgroup as $g){ ?>
                            <option <?=$perm->group == $g->id ? "selected" : ""?> value="<?=$g->id?>"><?=$g->name?></option>
                        <? } ?>
                    </select>
                    <button onclick="editPermGroup()" class="btn btn-secondary" style="white-space:nowrap" id="addpermgroup">Thêm/sửa nhóm</button>
                </div>
            </div>
        </div>
        <div class="section-body">
            <div class="row p-4">
                <div class="col-6" style="border: 1px solid; padding:0">
                    <div style="text-align:center;padding: 4px; background:white;">Quyền quản trị</div>
                    <div style="padding: 6px;">
                        <? foreach($permlistadmin as $k => $v){
                            if(isPerm($perm,$k)){ ?>
                            <div>
                                <label class="checkbox-inline w-100">
                                    <input style="-webkit-appearance:checkbox" type="checkbox" name="<?=$k?>" checked> <?=$v?>
                                </label>
                            </div>
                        <? }else{ ?>
                            <div>
                                <label class="checkbox-inline w-100">
                                    <input style="-webkit-appearance:checkbox" type="checkbox" name="<?=$k?>"> <?=$v?>
                                </label>
                            </div>
                        <? } } ?>
                    </div>
                </div>
                <div class="col-6" style="border: 1px solid; padding:0">
                    <div style="text-align:center;padding: 4px; background:white;">Quyền người dùng</div>
                    <div style="padding: 6px;">
                        <? foreach($permlistuser as $k => $v){
                            if(isPerm($perm,$k) === true){ ?>
                            <div>
                                <label class="checkbox-inline w-100">
                                    <input style="-webkit-appearance:checkbox" type="checkbox" name="<?=$k?>" checked> <?=$v?>
                                </label>
                            </div>
                        <? }else{ ?>
                            <div>
                                <label class="checkbox-inline w-100">
                                    <input style="-webkit-appearance:checkbox" type="checkbox" name="<?=$k?>"> <?=$v?>
                                </label>
                            </div>
                        <? } } ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="section-body">
            <div class="">
                <div style="flex:1;padding: 12px;font-size:24px;">
                    Quyền cấp quyền cho tài khoản: 
                    <input style="-webkit-appearance:checkbox;transform:scale(2)" type="checkbox" id="grantperm" <?=($perm->grant ===true)?"checked":""?>>
                </div>
            </div>
            <div class="">
                <div style="flex:1;padding: 12px;font-size:24px;">
                    Quyền truy cập trang quản trị: 
                    <input style="-webkit-appearance:checkbox;transform:scale(2)" type="checkbox" id="adminperm" <?=($u->isadmin == "1")?"checked":""?>>
                </div>
            </div>
        </div>
        <div class="section-body" style="text-align: center;">
            <button onclick="savePerm()" class="btn btn-primary">Lưu trữ</button>
        </div>
    </div>
    <script>
        var context = <?=$idcanbo?>;
        function savePerm(){
            var perm = {
                id: <?=$idcanbo?>,
                group: $("#permgroup").val(),
                grant: $("#grantperm").is(":checked"),
                admin: $("#adminperm").is(":checked"),
                ajax: "saveperm",
                perms: {}
            };
            $("input[name]").each(function(){
                if($(this).is(":checked")){
                    perm.perms[$(this).attr("name")] = true;
                }
            });
            perm.perms = JSON.stringify(perm.perms);
            $.ajax({
                url: "/admin/ajax",
                type: "POST",
                data: perm,
                success: function(data){
                    if(data == "success"){
                        alert("Cập nhật thành công");
                    }else{
                        alert(data);
                    }
                }
            });
        }
    </script>
<? include "footer.htm" ?>