<?
checkLogin();
$quest = getModule("quest");
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
    .input-group-prepend{
        background-color: #fff;
        background-clip: padding-box;
        -border: 1px solid #ced4da;
    }
    #tagspreview > span{
        background-color: #eee;
        font-size: 12px;
        padding: 4px 8px;
        border-radius: 5px;
        margin: 4px;
        cursor: pointer;
        transition: background-color .3s;
    }
    #tagspreview > span:hover{
        background-color: #ddd;
    }
    #tags > span{
        background-color: #eee;
        font-size: 12px;
        padding: 4px 8px;
        border-radius: 5px;
        margin: 4px;
        cursor: pointer;
        transition: background-color .3s;
    }
    #tags > span:hover{
        background-color: #ddd;
    }
    #tags > span::after{
        content: attr(value);
        margin-left: 4px;
    }
    .input-group button{
        width:38px;
    }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.10.3/tinymce.min.js" referrerpolicy="origin"></script>

<div class="section bg-light">
    <div class="section-title">Thêm câu hỏi cho học phần
        
    </div>
    <div class="section-body">
        <p>Loại câu hỏi 
            <select class="form-control" onchange="changeqtype(this.value)">
                <option value="choosethebest">Chọn đáp án đúng</option>
                <option value="checkalltrue">Chọn các đáp án đúng</option>
                <option value="askandanswer">Câu hỏi tự luận</option>
                <option value="connectpair">Nối câu/ảnh</option>
                <option value="putinplace">Điền vào chỗ trống</option>
            </select>
        </p>
        <div>
            <p>Nội dung câu hỏi</p>
            <textarea id="questtext" class="form-control" placeholder="Nội dung câu hỏi">Một cộng một bằng mấy?</textarea>
            <br>
        </div>

        <div id="tabdiv">
            <div id="choosethebest">
                <p>Danh sách đáp án</a>
                <div id="ctb-ans">
                    <form>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-info" onclick="ctb_AddAns(this)"><i class="fa fa-plus"></i></button>
                                <button type="button" class="btn btn-info" onclick="ctb_RemAns(this)"><i class="fa fa-minus"></i></button>
                            </div>
                            <input type="text" placeholder="Đáp án" class="form-control">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-success" onclick="ctb_MarkRight(this)"><i class="fa fa-check"></i></button>
                            </div>
                        </div>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-info" onclick="ctb_AddAns(this)"><i class="fa fa-plus"></i></button>
                                <button type="button" class="btn btn-info" onclick="ctb_RemAns(this)"><i class="fa fa-minus"></i></button>
                            </div>
                            <input type="text" placeholder="Đáp án" class="form-control">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-primary" onclick="ctb_MarkRight(this)"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-info" onclick="ctb_AddAns(this)"><i class="fa fa-plus"></i></button>
                                <button type="button" class="btn btn-info" onclick="ctb_RemAns(this)"><i class="fa fa-minus"></i></button>
                            </div>
                            <input type="text" placeholder="Đáp án" class="form-control">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-primary" onclick="ctb_MarkRight(this)"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-info" onclick="ctb_AddAns(this)"><i class="fa fa-plus"></i></button>
                                <button type="button" class="btn btn-info" onclick="ctb_RemAns(this)"><i class="fa fa-minus"></i></button>
                            </div>
                            <input type="text" placeholder="Đáp án" class="form-control">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-primary" onclick="ctb_MarkRight(this)"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                    </form>
                    
                </div>
            </div>
            <div id="checkalltrue" hidden>
                <p>Danh sách đáp án</a>
                <div id="cat-ans">
                    <form>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-info" onclick="cat_AddAns(this)"><i class="fa fa-plus"></i></button>
                                <button type="button" class="btn btn-info" onclick="ctb_RemAns(this)"><i class="fa fa-minus"></i></button>
                            </div>
                            <input type="text" placeholder="Đáp án" class="form-control">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-success" onclick="cat_MarkRight(this)"><i class="fa fa-check"></i></button>
                            </div>
                        </div>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-info" onclick="cat_AddAns(this)"><i class="fa fa-plus"></i></button>
                                <button type="button" class="btn btn-info" onclick="ctb_RemAns(this)"><i class="fa fa-minus"></i></button>
                            </div>
                            <input type="text" placeholder="Đáp án" class="form-control">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-success" onclick="cat_MarkRight(this)"><i class="fa fa-check"></i></button>
                            </div>
                        </div>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-info" onclick="cat_AddAns(this)"><i class="fa fa-plus"></i></button>
                                <button type="button" class="btn btn-info" onclick="ctb_RemAns(this)"><i class="fa fa-minus"></i></button>
                            </div>
                            <input type="text" placeholder="Đáp án" class="form-control">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-primary" onclick="cat_MarkRight(this)"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-info" onclick="cat_AddAns(this)"><i class="fa fa-plus"></i></button>
                                <button type="button" class="btn btn-info" onclick="ctb_RemAns(this)"><i class="fa fa-minus"></i></button>
                            </div>
                            <input type="text" placeholder="Đáp án" class="form-control">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-primary" onclick="cat_MarkRight(this)"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                    </form>
                    
                </div>
            </div>
            <div id="askandanswer" hidden>
                <p>Đáp án</a>
                <textarea id="aaa-ans" class="form-control"></textarea>
            </div>
        </div>
        <div>
            <br>
            <p>Xếp loại</p>
            <div class="flex">
                <div style="border: 1px solid #e3dcdc;" class="w-50 bg-white rounded p-2" id="tags"></div>
                <div style="border: 1px solid #e3dcdc;" class="w-50 bg-white rounded p-2" id="tagspreview">
                    <?$tags = getModule("tags"); ?>
                    <?=$tags->getAllTags($idmonhoc)?>
                </div>
            </div>
        </div>
        <br>
        <center><button class="btn btn-primary" type="button" onclick="savequest()">Lưu trữ</button>
    
        <button class="btn btn-primary" type="button" onclick="savequest(true)">Lưu và thêm tiếp</button></center>
    </div>
</div>
<script type="text/javascript">
    var context=<?=$idmonhoc?>;
    var qtype = "choosethebest";
    function savequest(isreload){
        var text= encodeURIComponent(tinyMCE.activeEditor.getContent());
        var data = encodeURIComponent(getDataByType());
        fetch("/user/ajax",{
            method:"POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded"},
            body: `ajax=addquestion&idmonhoc=${context}&type=${qtype}&text=${text}&tags=${getTags()}&data=${data}`
        }).then(t=>t.text()).then(t=>{
            alert(t);
            if(isreload){
                location.reload();
            }
        });
    }
    function getDataByType(){
        if(qtype=="choosethebest"){
            return ctb_Data();
        }
    }
    function getTags(){
        var tags = q("#tags > span");
        var list = [];
        for(var i=0;i<tags.length;i++){
            list.push({
                slugname:tags[i].data.slugname,
                value: tags[i].getAttribute("value")
            });
        }
        return encodeURIComponent(JSON.stringify(list));
    }
    function ctb_Data(){
        var obj = {
            answer:Array.from(g("ctb-ans").querySelectorAll("input")).map(i=>i.value.trim()),
            rightanswer: g("ctb-ans").querySelector(".fa-check").parentElement.parentElement.previousElementSibling.value.trim(),
        };
        return JSON.stringify(obj);
    }
    q(".tag-pr").forEach(function(e){
        e.data = JSON.parse(decodeURIComponent(e.getAttribute('data')));
        e.removeAttribute("data");
        e.onclick = function(){
            if(this.hasAttribute("hasvalue")){
               if(confirm("Hủy bỏ: "+this.data.name)){
                   this.removeAttribute('hasvalue');
                   g("tagspreview").appendChild(this);
               }
            }else{
                showAssignValue(this);
            }
        }
    });
    function showAssignValue(tag){
        if(tag.data.type=="array"){
            var sel = ui.select("Chọn lựa");
            for(var i=0;i<tag.data.valuerange.length;i++){
                sel.option(tag.data.valuerange[i],tag.data.valuerange[i]);
            }
            sel.proc = function(v){
                
                tag.setAttribute("value",v);
                tag.setAttribute("hasvalue",true);
                g("tags").appendChild(tag);
                sel.hide();
            }
            sel.show();
        }
        if(tag.data.type=="number"){
            window.md = createModal("Nhập số liệu");
            md.context = tag;
            var min = tag.data.valuerange.min;
            var max = tag.data.valuerange.max;
            md.body().innerHTML = `Nhập số liệu cho ${tag.data.name}:<br>
                <input class="form-control" type="number" id="ip-tvl" min="${min}" max="${max}" placeholder="Nhập số, tối thiểu ${min}, tối đa ${max}">
                <br><center><button class="btn btn-primary" onclick="closeMdAddVal()">Xác nhận</button></center>`;
            md.show();
        }
        if(tag.data.type == 'string'){
            window.md = createModal("Nhập giá trị");
            md.context = tag;
            md.body().innerHTML = `Nhập số liệu cho ${tag.data.name}:<br>
                <input class="form-control" type="text" id="ip-tvl" placeholder="Nhập giá trị">
                <br><center><button class="btn btn-primary" onclick="closeMdAddVal()">Xác nhận</button></center>`;
            md.show();
        }
        if(tag.data.type == 'have'){
            tag.setAttribute("hasvalue",true);
            g("tags").appendChild(tag);
        }
    }
    function closeMdAddVal(){
        var tag = md.context;
        var value = val("tvl");
        if(tag.data.type == "number"){
            var n = parseInt(value);
            if(n > tag.data.valuerange.max || n < tag.data.valuerange.min){
                alert("Giá trị ngoài phạm vi cho phép.");
                return false;
            }
        }
        if(tag.data.type == "string"){
            if(value.length < 1){
                alert("Vui lòng nhập giá trị.");
                return false;
            }
        }
        tag.setAttribute("value",value);
        tag.setAttribute("hasvalue",true);
        g("tags").appendChild(tag);
        md.hide();
    }
    function ctb_AddAns(btn){
        var row = btn.parentElement.parentElement;
        var clone = document.createElement("div");
        clone.innerHTML = row.outerHTML;
        clone.querySelector("input").value="";
        var btn = clone.querySelector(".input-group-append button");
        btn.innerHTML = `<i class="fa fa-times"></i>`;
        btn.className="btn btn-primary";
        row.parentElement.appendChild(clone.children[0]);
    }
    function ctb_RemAns(btn){
        var row = btn.parentElement.parentElement;
        var f= findForm(btn);
        if(f.children.length < 3){
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
    function ctb_MarkRight(btn){
        var f = findForm(btn);
        var fa = `<i class="fa fa-times"></i>`;
        var tr = `<i class="fa fa-check"></i>`;
        f.querySelectorAll(".input-group-append button").forEach(b=>{
            b.className="btn btn-primary";
            b.innerHTML=fa;
        });
        btn.className="btn btn-success";
        btn.innerHTML=tr;
    }
    function cat_AddAns(btn){
        var row = btn.parentElement.parentElement;
        var clone = document.createElement("div");
        clone.innerHTML = row.outerHTML;
        clone.querySelector("input").value="";
        row.parentElement.appendChild(clone.children[0]);
    }
    function cat_MarkRight(btn){
        var fa = `<i class="fa fa-times"></i>`;
        var tr = `<i class="fa fa-check"></i>`;
        if(btn.className.indexOf("success")>0){
            btn.className="btn btn-primary";
            btn.innerHTML=fa;
        }else{
            btn.className="btn btn-success";
            btn.innerHTML=tr;
        }
    }
    function changeqtype(t){
        if(!g(t).hasAttribute("hidden")){
            return;
        }
        g("tabdiv").children.forEach(e=>{if(e.id!=t){e.setAttribute("hidden",true)}else{e.removeAttribute("hidden")}});
        this.qtype = t;
    }
    tinymce.init({
        selector:'#questtext',
        plugins:"image codesample table",
        external_plugins: {
            'tiny_mce_wiris': `//localhost/mathtype.js`,
        },
        
        htmlAllowedTags: ['.*'],
        htmlAllowedAttrs: ['.*'],
        draggable_modal: true,
        toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | outdent indent | image table codesample tiny_mce_wiris_formulaEditor tiny_mce_wiris_formulaEditorChemistry'

    });
    
</script>
<?
include "footer.htm";
?>