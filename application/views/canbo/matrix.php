<?
checkLogin();
$idcanbo = (int)$_SESSION['id'];
$idmatrix = $idmatrix ?? "";
if($idmatrix){
    $ft = ["id"=>$idmatrix];
    if(!isAdmin()){
        $ft["idcanbo"] = $idcanbo;
    }
    $matrix = buildSearch($ft)->exec("matrix");
    if(!$matrix){
        die("Không tìm thấy ma trận này.");
    }
}
$mh = getMonhoc($idmonhoc);
if(!$mh){
    die("Không tìm thấy môn học này");
}
$tags = getModule("tags");
include "header.htm";
?>
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
    .selected{
        background-color: #fff;
        color:red;
    }
    #ground-block{
        background-color: #fff;
        padding: 8px;
        border-radius: 8px;
    }
    .matrix-group{
        background-color: #eee;
        padding: 8px;
        border-radius: 4px;
        margin-bottom: 4px;
    }
    .matrix-group .remove{
        margin-left: 4px;
        float: right;
        cursor: pointer;
        font-size: 16px;
        border:none;
        outline: none;
        color: gray;
        width: 30px;
        transition: all 0.2s;
    }
    .matrix-group .remove:hover{
        color: black;
    }
    .matrix-group .matrix-group-name{
        outline: none;
        display: inline-block;
    }
    .matrix-group .matrix-group-name:hover,.matrix-group .matrix-group-name:active{
        transform: translateY(-2px);
    }
    #md-top,#md-bottom{
        margin:8px;
        padding: 8px;
        background-color: #ebebeb;
        border-radius: 4px;
        min-height: 160px;
    }
    .tag {
        font-size: 14px;
        color: black;
        padding: 4px 4px;
        font-family: nunito;
        margin-right: 6px;
        border-radius: 4px;
        background: white;
        border: none;
    }
    .tag.disabled {
        background: #eee;
        color: gray;
    }
    .matrix-group-count{
        padding: 4px;
        border-radius: 4px;
        background: white;
        
    }
    .matrix-group-count span{
        padding: 0 10px;
        outline: none;
    }
</style>
<div class="section bg-light">
    <div class="section-title">Ma trận đề cho môn học <b><?=$mh->name?></b></div>
    <div class="section-body">
        <input id="name" class="form-control mb-4" placeholder="Tên ma trận đề" value="<?=$idmatrix?$matrix->name:""?>">
        <input id="description" class="form-control mb-4" placeholder="Giới thiệu (Optional)" value="<?=$idmatrix?$matrix->description:""?>">
        <input id="totalpoint" onchange="countTotalQuest()" class="form-control mb-4" placeholder="Tổng số điểm" value="<?=$idmatrix?$matrix->totalpoint:""?>">
        <br>
        <p style="font-size: 22px;">Chi tiết ma trận
            <span style="float: right;">Tổng số câu hỏi: <span id="questcount">0</span>, mỗi câu <span id="pointperquest">0</span>đ.</span>
        </p>
        <div class="" id="ground-block">
            <div id="ground"></div>
            <div class="add-matrix-group" style="text-align: center">
                <button class="btn w-100" style='background: #f1f1f1;' onclick="addGroup()"><i class="fa fa-plus"></i></button>
            </div>
        </div>
        <center class="m-3">
            <button class="btn btn-primary" type="button" onclick="saveMatrix(<?=$idmatrix?>)">Lưu trữ</button>&nbsp;
            <button class="btn btn-primary" type="button" onclick="runMatrix(<?=$idmatrix?>)">Khởi chạy</button>
        </center>
        <div>
            <template id="tmplmatrix">
                <div class="matrix-group" ondblclick="if(event.target==this)modifyGroup(this)">
                    <span class="matrix-group-count" onclick="this.children[0].focus();"><span contenteditable onkeydown="countquestchange(this)">1</span> câu hỏi</span>
                    <span contenteditable="true" class="matrix-group-name">{{groupname}}</span>
                    <span class="matrix-group-content"></span>
                    <button class="remove" onclick="removeGroup(this)"><i class="fa fa-times"></i></button>
                    <button class="remove" onclick="modifyGroup(this.parentElement)"><i class="fa fa-wrench"></i></button>
                </div>
            </template>
        </div>
        <script>
        <?php
            $list = $tags->statTagsFor($idmonhoc);
            echo "var context=$idmonhoc;\n";
            echo "var list = ".json_encode($list).";";
            if($idmatrix){
                echo "var idmatrix=$idmatrix;\n";
                echo "var matrix = ".json_encode($matrix->data).";";
            }
        ?>
            var currentmodify = null;
            function countquestchange(){
                if(!String.fromCharCode(event.keyCode).match(/[0-9]+/) && event.keyCode != 8 && event.keyCode != 46 && event.keyCode != 37 && event.keyCode != 39){
                    event.preventDefault();
                }
                setTimeout(function(){
                    countTotalQuest();
                },200);
                
            }
            function indexTag(){
                window.tagslist ={};
                for(var i=0; i<list.length; i++){
                    window.tagslist[list[i].sign] = list[i];
                }
            }
            function modifyGroup(g){
                currentmodify = g;
                var wd = ui.win.create("Nhóm câu hỏi");
                var body = wd.body();
                body.innerHTML = `<div id="md-top"></div>
                <div id="md-bottom"></div>
                <div class="mb-2" style="font-size: 12px;padding:8px;">Ghi chú: X ở trước các nhãn thể hiện số câu hỏi có nhãn này
                <button type="button" style="float:right;" class="btn btn-primary" onclick="saveModify();ui.win.close(this)">Lưu trữ</button></div>`;
                var bottom = body.querySelector("#md-bottom");
                var top = body.querySelector("#md-top");
                wd.onclose=function(){
                    saveModify();
                    wd.remove();
                }
                bottom.onclick = function(){
                    var target = event.target;
                    if(target.className.indexOf("disabled")>-1){
                        return;
                    };
                    if(target.tagName == "SPAN"){
                        top.appendChild(target);
                        filterTagAvailable();
                    }
                }
                top.onclick = function(){
                    var target = event.target;
                    if(target.tagName == "SPAN"){
                        bottom.appendChild(target);
                        filterTagAvailable();
                    }
                }
                
                var existtags = g.querySelectorAll(".matrix-group-content span");
                existtags.forEach(function(tag){
                    top.appendChild(tag);
                });
                for(var i = 0; i < list.length; i++){
                    var tag = list[i];
                    if(top.querySelector('[sign="'+tag.sign+'"]')){
                        continue;
                    }
                    var span = createTag(tag);
                    bottom.appendChild(span);
                }
                wd.show();
            }
            function filterTagAvailable(t){
                var usedtag = q("#md-top span");
                var unusedtag = q("#md-bottom span");
                var slugnames = [];
                for(var i = 0; i < usedtag.length; i++){
                    var tag = getTagFromSign(usedtag[i].getAttribute('sign'));
                    slugnames.push(tag.slugname);
                    
                }
                for(var j = 0; j < unusedtag.length; j++){
                    var tag2 = getTagFromSign(unusedtag[j].getAttribute('sign'));
                    if(slugnames.indexOf(tag2.slugname)>-1){
                        unusedtag[j].className = "tag disabled";
                    }else{
                        unusedtag[j].className = "tag";
                    }
                }
            }
            function createTag(tag){
                if(!tag.name){
                    tag = getTagFromNV(tag);
                }
                var span = document.createElement("span");
                    span.innerHTML =tag.count +"x "+ tag.name + (tag.value?": "+tag.value:"");
                    span.setAttribute("sign", tag.sign);
                    span.setAttribute("name", tag.slugname);
                    span.className="tag";
                return span;
            }
            function addGroup(modify = true){
                var tmpl = g("tmplmatrix").innerHTML;
                var groupname = "Nhóm câu hỏi ";
                var div = document.createElement("div");
                div.innerHTML = tmpl.replace("{{groupname}}",groupname);
                var group = div.children[0];
                g("ground").appendChild(div.children[0]);
                if(modify)modifyGroup(group);
            }
            function removeGroup(g){
                if(confirm("Xác nhận xóa nhóm này?")){
                    g.parentElement.removeChild(g);
                }
            }
            function loadGroup(){
                if(window.matrix){
                    for(var i = 0; i <matrix.length; i++){
                        var matrixgroup = matrix[i];
                        var tmpl = g("tmplmatrix").innerHTML;
                        var div = document.createElement("div");
                        div.innerHTML = tmpl.replace("{{groupname}}",matrixgroup.name);
                        var tags = matrixgroup.tags;
                        group = div.children[0];
                        g("ground").appendChild(div.children[0]);
                        for(var j = 0; j < tags.length; j++){
                            var tag = tags[j];
                            var span = createTag(tag);
                            group.querySelector(".matrix-group-content").appendChild(span);
                        }
                        group.querySelector(".matrix-group-count span").innerHTML = matrixgroup.count;
                    }
                    countTotalQuest();
                }else{
                    addGroup(false);
                }

            }
            function saveModify(){
                var group = currentmodify;
                if(!group){
                    return;
                }
                group.querySelector(".matrix-group-content").innerHTML = q("#md-top")[0].innerHTML;
                currentmodify = null;
                countTotalQuest();
            }
            function countTotalQuest(){
                var total = 0;
                var groups = q("#ground .matrix-group");
                for(var i = 0; i < groups.length; i++){
                    var group = groups[i];
                    var count = group.querySelector(".matrix-group-count").children[0].innerHTML;
                    total += parseInt(count || 0);
                }
                g("questcount").innerHTML = total;
                var maxpoints = val("totalpoint") || 0;
                if(maxpoints >= 0){
                    g("pointperquest").innerHTML = maxpoints/total;
                }
            }
            function saveMatrix(){
                var groups = g("ground").querySelectorAll(".matrix-group");
                var matrix = [];
                for(var i = 0; i < groups.length; i++){
                    var group = groups[i];
                    var name = group.querySelector(".matrix-group-name").innerHTML;
                    var tags = group.querySelectorAll(".matrix-group-content span");
                    var count = group.querySelector(".matrix-group-count").children[0].innerHTML;
                    if(count <=0){
                        return alert("Số câu hỏi mỗi nhóm phải lớn hơn bằng không");
                    }
                    var tagsign = [];
                    for(var j = 0; j < tags.length; j++){
                        var tag = tags[j];
                        var tagdata = getTagFromSign(tag.getAttribute("sign"));
                        tagsign.push({
                            slugname: tagdata.slugname,
                            value: tagdata.value
                        });
                    }
                    matrix.push({
                        name: name,
                        count: count,
                        tags: tagsign
                    });
                }
                var data = {
                    id: <?php echo $idmonhoc; ?>,
                    idmatrix: <?php echo $idmatrix?$idmatrix:"0"; ?>,
                    matrix: JSON.stringify(matrix),
                    name: val("name"),
                    description: val("description"),
                    totalpoint: val("totalpoint"),
                    ajax: "saveMatrix"
                }
                var url = "/user/ajax";
                var xhr = new XMLHttpRequest();
                xhr.open("POST", url, true);
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function(){
                    if(xhr.readyState == 4 && xhr.status == 200){
                        var res = xhr.responseText;
                        if(res.match(/^success-/)){
                            var newid = res.substr("success-".length);
                            window.location.href = "/canbo/matrix/"+context+"/"+newid;
                        }
                        else if(res=="updated"){
                            ui.notif("Đã lưu");
                        }else{
                            alert(res);
                        }
                    }
                }
                xhr.send(new URLSearchParams(data).toString());
            }
            function getTagFromSign(sign){
                return list.filter(function(tag){
                    return tag.sign == sign;
                })[0];
            }
            function getTagFromIndex(sign){
                return tagslist[sign]
            }
            function getTagFromNV(tag){
                return list.filter(function(t){
                    return t.slugname == tag.slugname && t.value == tag.value;
                })[0];
            }
            function runMatrix(){
                location.href = "/canbo/runmatrix/"+context+"/"+idmatrix;
            }
            loadGroup();
        </script>
    </div>
</div>
<?
include "footer.htm";
?>