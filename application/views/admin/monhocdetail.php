<?
checkLogin();
$mh = buildSearch(["id"=>$idmonhoc])->asJson("chuongtrinh")->exec("monhoc");

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
        <div class="section-title">Thông tin môn học</div>
        <div class="section-body">
            <p>Mã học phần</p>
            <input class="form-control" type="text" id="idmonhoc" value="<?=$mh->mamonhoc?>" disabled>
            <br>
            <p>Tên học phần</p>
            
            <input class="form-control" type="text" id="name" value="<?=$mh->name?>"><br>
            <p>Ngành</p>
            <input class="form-control" type="text" id="nganh" value="<?=$mh->nganh?>"><br>
            <p>Chương trình học</p>
            <div>
                <form>
                    <? if(count($mh->chuongtrinh) > 0) { 
                        for($i=0; $i<count($mh->chuongtrinh); $i++) {?>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-info" onclick="AddRow(this)"><i class="fa fa-plus"></i></button>
                                <button type="button" class="btn btn-info" onclick="RemRow(this)"><i class="fa fa-minus"></i></button>
                            </div>
                            <input type="text" placeholder="Tên chương" value="<?=$mh->chuongtrinh[$i]?>" class="form-control">
                        </div>
                    <? }} else { ?>
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
            <center><button class="btn btn-primary">Lưu trữ</button></center>
        </div>
    </div>
    <script>
        function AddRow(btn){
            var row = btn.parentElement.parentElement;
            var clone = document.createElement("div");
            clone.innerHTML = row.outerHTML;
            clone.querySelector("input").value="";
            row.parentElement.appendChild(clone.children[0]);
        }
        function RemRow(btn){
            var row = btn.parentElement.parentElement;
            var f= findForm(btn);
            if(f.children.length < 2){
                return false;
            }
            row.remove();
        }
        function findForm(btn){
            while(btn.tagName!="FORM"){
                btn=btn.parentElement;
            }
            return btn;
        }
    </script>
<? include "footer.htm" ?>