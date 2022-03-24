<?
checkLogin();
$idmonhoc = $idmonhoc ?? "";
$isupdate = $idmonhoc != "";
$mh = $isupdate ? getMonhoc($idmonhoc) : null;
$catid="qlmh";
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
        <div class="section-title">Thêm môn học mới</div>
        <div class="section-body">
            <p>Mã học phần</p>
            <input class="form-control" type="text" id="idmonhoc" <?=$isupdate?"disabled":""?> value="<?=$isupdate?$mh->mamonhoc:""?>">
            <br>
            <p>Tên học phần</p>
            <input class="form-control" type="text" id="name" value="<?=$isupdate?$mh->name:""?>"><br>
            <p>Ngành</p>
            <input class="form-control" type="text" id="nganh" value="<?=$isupdate?$mh->nganh:""?>"><br>
            <p>Chương trình học</p>
            <div>
                <form id="chuongtrinh">
                    <?php
                        if($isupdate){
                            foreach($mh->chuongtrinh as $ct){
                                ?>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <button type="button" class="btn btn-info" onclick="AddRow(this)"><i class="fa fa-plus"></i></button>
                                        <button type="button" class="btn btn-info" onclick="RemRow(this)"><i class="fa fa-minus"></i></button>
                                    </div>
                                    <input type="text" placeholder="Tên chương" value="<?=$ct?>" class="form-control">
                                </div>
                                <?
                            }
                        }else{ ?>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <button type="button" class="btn btn-info" onclick="AddRow(this)"><i class="fa fa-plus"></i></button>
                                        <button type="button" class="btn btn-info" onclick="RemRow(this)"><i class="fa fa-minus"></i></button>
                                    </div>
                                    <input type="text" placeholder="Tên chương" class="form-control">
                                </div>
                    <? } ?>
                </form>
            </div>
            <br>
            <center><button type="button" class="btn btn-primary" onclick="addMonhoc(<?=$idmonhoc?>)">Lưu trữ</button></center>
        </div>
    </div>
    <script>
        function AddRow(btn){
            var row = btn.parentElement.parentElement;
            var clone = document.createElement("div");
            clone.innerHTML = row.outerHTML;
            var ip = clone.querySelector("input");
            if(row.querySelector("input").value.contain("Chương")){
                ip.value="Chương ";
            }else
            ip.value="";
            row.parentElement.appendChild(clone.children[0]);
            ip.focus();
        }
        function RemRow(btn){
            var row = btn.parentElement.parentElement;
            var f= findForm(btn);
            if(f.children.length < 2){
                return false;
            }
            alert(f.children.length);
            row.remove();
        }
        function findForm(btn){
            while(btn.tagName!="FORM"){
                btn=btn.parentElement;
            }
            return btn;
        }
        function v(k){
            return encodeURIComponent(val(k));
        }
        function addMonhoc(id){
            // account password name donvi avatar
            var chuongtrinh = [];
            q("#chuongtrinh input").forEach(function(e){
                if(e.value!="")
                chuongtrinh.push(e.value);
            });
            chuongtrinh = encodeURIComponent(chuongtrinh.join("///"));
            var param = `ajax=${id?"updatemonhoc&id="+id:"addmonhoc"}&mamonhoc=${v("idmonhoc")}&name=${v("name")}&nganh=${v("nganh")}&chuongtrinh=${chuongtrinh}`;
            fetch("/admin/ajax",{
                method: "POST",
                headers: {"Content-Type": "application/x-www-form-urlencoded"},
                body: param
            }).then(a=>a.text()).then(t=>{
                ui.alert(t);
                if(t.toLowerCase().contain("thành công")){
                    //location.reload();
                }
            });
        }
    </script>
<? include "footer.htm" ?>