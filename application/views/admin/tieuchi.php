<?
$listmh = [];
checkLogin();
$ft = buildSearch();

$listtc = $ft->limit(1000)->asJson("valuerange")->exec("tieuchi") ?? [];

$catid = "qltc";
include "header.htm" ?>
    <style>
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
        
    </div>
    <div class="section bg-light">
        <div class="section-title">Danh sách tiêu chí</div>
        <button onclick="addTieuchi()" class="btn btn-primary" style="float:right;">Thêm tiêu chí mới</button>
        <div class="section-body">
            <table class="table table-hover bg-white">
                <thead>
                    <tr>
                        <td>Tên tiêu chí</td>
                        <td>Loại</td>
                        <td>Phạm vi giá trị</td>
                        <td>Chỉnh sửa</td>
                        <td>Xóa</td>
                    </tr>
                </thead>
                <tbody>
                <? 
                $typelist = ["string"=>"Chuỗi","number"=>"Số","have"=>"Nhãn"];
                function getValueRange($tag) {
                    if($tag->type == "number"){
                        return $tag->valuerange->min." - ".$tag->valuerange->max;
                    }
                    if($tag->type == "array"){
                        return implode(", ",$tag->valuerange);
                    }
                    return "";
                }
                for ($i = 0;$i<count($listtc);$i++){ ?>
                    <tr id="tc-<?=$listtc[$i]->id?>">
                        <td><?=$listtc[$i]->name?></td>
                        <td><?=$typelist[$listtc[$i]->type]?></td>
                        <td><?=getValueRange($listtc[$i])?></td>
                        <td><? if($listtc[$i]->type=="number" ||$listtc[$i]->type=="array" ){ ?>
                            <a href="javascript:void(0);" onclick="editTieuchi(<?=$listtc[$i]->id?>)">Sửa</a></td>
                        <? } ?>
                        <td><a href="javascript:void(0);" onclick="removeTieuchi(<?=$listtc[$i]->id?>)">Xóa</a></td>
                    </tr>
                <? } ?>
                </tbody>
            </table>
        </div>
    </div>
    <script>
        var tccontext = 0;
        function editTieuchi(id){
            tccontext = id;
            var tag = g("tc-"+id);
            var wd = ui.win.create("Tiêu chí");
            var body = wd.body();
            var valuetype = {
                "Chuỗi": "string",
                "Số":"number",
                "Nhãn":"have",
                "Lựa chọn":"array"
            };
            var type = valuetype[tag.cells[1].innerText];
            body.innerHTML = "<div class='section'><div class='section-title'>Sửa tiêu chí</div><div class='section-body'><form id='form-tieuchi'><input type='hidden' name='id' value='"+
                 id+"'><div class='form-group'><label>Tên tiêu chí</label><input disabled type='text' name='name' class='form-control' value='"+
                 tag.cells[0].innerHTML+"'></div><div class='form-group'><label>Loại</label><select disabled name='type' class='form-control' value='"+type+
                 "'><option value='string'>Chuỗi</option><option value='number'>Số</option><option value='array'>Lựa chọn</option><option value='have'>Nhãn</option></select></div>"+
                 "<div class='form-group'><label>Phạm vi giá trị</label><input type='text' name='valuerange' class='form-control' value='"+tag.cells[2].innerHTML+"'></div></form></div></div>";
            body.innerHTML += "<center class='mb-4'><button type='button' onclick='saveTieuchi()'>Lưu trữ</button></center>";
            body.querySelector("select").value = valuetype[tag.cells[1].innerHTML];
            wd.show();
        }
        function addTieuchi() {
            var wd = ui.win.create("Tiêu chí");
            var body = wd.body();
            body.innerHTML = `<div class='section'><div class='section-title'>Thêm tiêu chí mới</div><div class='section-body'><form id='form-tieuchi'>
            <input type='hidden' name='id' value='0'><div class='form-group'><label>Tên tiêu chí</label><input type='text' name='name' class='form-control' value=''>
            </div><div class='form-group'><label>Loại</label><select name='type' class='form-control' value=""><option value='string'>Chuỗi ( Do cán bộ nhập chữ )</option>
            <option value='number'>Số ( min - max )</option><option value='array'>Lựa chọn ( lựa chọn 1, lựa chọn 2,...)</option><option value='have'>Nhãn (Không cần giá trị)</option></select></div>
                 <div class='form-group'><label>Phạm vi giá trị</label><input type='text' name='valuerange' class='form-control' value=''></div></form></div></div>`;
            body.innerHTML += "<center class='mb-4'><button type='button' onclick='saveNewTieuchi()'>Thêm</button></center>"
            wd.show();
        }
        function removeTieuchi(id){
            if(confirm("Bạn có chắc chắn muốn xóa tiêu chí này?, hành động có thể gây ra lỗi trong hệ thống khi có các câu hỏi gắn tiêu chí này.")){
                fetch("/admin/ajax",{
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded"},
                    body: "ajax=removetieuchi&id="+id
                }).then(async function(res){
                    var data = await res.text();
                    if(data == "success"){
                        g("tc-"+id).remove();
                    }
                });
            }
        }
        function valuerangeToJson(valuerange){
            var t = valuerange.split("-");
            return {
                min: parseInt(t[0]),
                max: parseInt(t[1])
            };
        }
        function valuerangeToJsonString(valuerange){
            var t = valuerange;
            return {
                max: parseInt(t)
            };
        }
        function valuerangeToJsonArray(valuerange){
            var t = valuerange.split(",");
            return t.map(v=>v.trim());
        }
        function saveTieuchi(){
            var form = g("form-tieuchi");
            var type = form.type.value;
            var valuerange = form.valuerange.value;
            var id = form.id.value;
            if(type == "array"){
                valuerange = JSON.stringify(valuerangeToJsonArray(valuerange));
            }else if(type == "number"){
                valuerange = JSON.stringify(valuerangeToJson(valuerange));
            }else if(type == "string"){
                valuerange = JSON.stringify(valuerangeToJsonString(valuerange));
            }
            var xhr = new XMLHttpRequest();
            xhr.open("POST","/admin/ajax");
            xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
            xhr.onload = function(){
                if(xhr.status == 200){
                    var res = xhr.responseText;
                    if(res == "success"){
                        ui.win.close();
                        location.reload();
                    }else{
                        alert(res);
                        location.reload();
                    }
                }
            }
            xhr.send(`id=${id}&ajax=savetieuchi&valuerange=${valuerange}&type=${type}`);
        }
        function saveNewTieuchi(){
            var form = g("form-tieuchi");
            var type = form.type.value;
            var valuerange = form.valuerange.value;
            var name = form.name.value;
            if(type == "array"){
                valuerange = JSON.stringify(valuerangeToJsonArray(valuerange));
            }else if(type == "number"){
                valuerange = JSON.stringify(valuerangeToJson(valuerange));
            }else if(type == "string"){
                valuerange = JSON.stringify(valuerangeToJsonString(valuerange));
            }
            var xhr = new XMLHttpRequest();
            xhr.open("POST","/admin/ajax");
            xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
            xhr.onload = function(){
                if(xhr.status == 200){
                    var res = xhr.responseText;
                    if(res == "success"){
                        //g("tc-"+tccontext).cells[0].innerHTML = form.name.value;
                        //g("tc-"+tccontext).cells[1].innerHTML = form.type.value;
                        //g("tc-"+tccontext).cells[2].innerHTML = form.valuerange.value;
                        location.reload();
                        ui.win.close();
                    }else{
                        alert(res);
                        location.reload();
                    }
                }
            }
            xhr.send(`ajax=addtieuchi&valuerange=${valuerange}&type=${type}&name=${name}`);
        }
    </script>
<? include "footer.htm" ?>