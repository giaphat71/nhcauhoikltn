<?
checkLogin();
$quest = getModule("quest");
$idcauhoi = $idcauhoi ?? 0;
if($idcauhoi){
    $cauhoi = buildSearch(["id"=>$idcauhoi])->exec("cauhoi");
    if(!$cauhoi){
        die("Không tìm thấy câu hỏi này.");
    }
}
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
    <div class="section-title">
        <? if($idcauhoi) echo "Sửa câu hỏi"; else echo "Thêm câu hỏi cho học phần"; ?>
    </div>
    <div class="section-body">
        <p>Loại câu hỏi 
            <select id="questtype" class="form-control" onchange="changeqtype(this.value)" value="<?=$idcauhoi?$cauhoi->type:""?>">
                <option value="choosethebest">Chọn đáp án đúng</option>
                <option value="checkalltrue">Chọn các đáp án đúng</option>
                <option value="askandanswer">Câu hỏi tự luận</option>
                <option value="connectpair">Nối câu</option>
                <option value="putinplace">Điền vào chỗ trống</option>
            </select>
        </p>
        <div>
            <p>Nội dung câu hỏi</p>
            <textarea id="questtext" class="form-control" placeholder="Nội dung câu hỏi"><? if($idcauhoi) echo $cauhoi->text;?></textarea>
            <br>
        </div>

        <div id="tabdiv">
            <div id="choosethebest">
                <p>Danh sách đáp án</a>
                <div id="ctb-ans">
                    <form>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-info" onclick="choosethebest.addAnswer(this)"><i class="fa fa-plus"></i></button>
                                <button type="button" class="btn btn-info" onclick="choosethebest.removeAnswer(this)"><i class="fa fa-minus"></i></button>
                            </div>
                            <input onkeydown="choosethebest.keyup(this);" onchange="mathCheck(this)" type="text" placeholder="Đáp án" class="form-control" value="Đáp án 1">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-success" onclick="choosethebest.markRight(this)"><i class="fa fa-check"></i></button>
                            </div>
                        </div>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-info" onclick="choosethebest.addAnswer(this)"><i class="fa fa-plus"></i></button>
                                <button type="button" class="btn btn-info" onclick="choosethebest.removeAnswer(this)"><i class="fa fa-minus"></i></button>
                            </div>
                            <input onkeydown="choosethebest.keyup(this);" onchange="mathCheck(this)" type="text" placeholder="Đáp án" class="form-control" value="Đáp án 2">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-primary" onclick="choosethebest.markRight(this)"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-info" onclick="choosethebest.addAnswer(this)"><i class="fa fa-plus"></i></button>
                                <button type="button" class="btn btn-info" onclick="choosethebest.removeAnswer(this)"><i class="fa fa-minus"></i></button>
                            </div>
                            <input onkeydown="choosethebest.keyup(this);" onchange="mathCheck(this)" type="text" placeholder="Đáp án" class="form-control" value="Đáp án 3">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-primary" onclick="choosethebest.markRight(this)"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-info" onclick="choosethebest.addAnswer(this)"><i class="fa fa-plus"></i></button>
                                <button type="button" class="btn btn-info" onclick="choosethebest.removeAnswer(this)"><i class="fa fa-minus"></i></button>
                            </div>
                            <input onkeydown="choosethebest.keyup(this);" onchange="mathCheck(this)" type="text" placeholder="Đáp án" class="form-control" value="Đáp án 4">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-primary" onclick="choosethebest.markRight(this)"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <!-- toggle -->
                        <div class="col-md-6">
                            <div class="toggle-switch">
                                <label class="switch">
                                    <input id="ctb-lockans" type="checkbox" <?= $cauhoi->data->lockposition ?? false ? "checked" : "" ?>>
                                    <span class="slider round"></span>
                                </label>
                                <label>
                                    <span>Khóa vị trí đáp án</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="checkalltrue" hidden>
                <p>Danh sách đáp án</a>
                <div id="cat-ans">
                    <form>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-info" onclick="checkalltrue.addAnswer(this)"><i class="fa fa-plus"></i></button>
                                <button type="button" class="btn btn-info" onclick="choosethebest.removeAnswer(this)"><i class="fa fa-minus"></i></button>
                            </div>
                            <input onkeydown="choosethebest.keyup(this);;" onchange="mathCheck(this)" type="text" placeholder="Đáp án" class="form-control">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-success" onclick="checkalltrue.markRight(this)"><i class="fa fa-check"></i></button>
                            </div>
                        </div>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-info" onclick="checkalltrue.addAnswer(this)"><i class="fa fa-plus"></i></button>
                                <button type="button" class="btn btn-info" onclick="choosethebest.removeAnswer(this)"><i class="fa fa-minus"></i></button>
                            </div>
                            <input onkeydown="choosethebest.keyup(this);" onchange="mathCheck(this)" type="text" placeholder="Đáp án" class="form-control">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-success" onclick="checkalltrue.markRight(this)"><i class="fa fa-check"></i></button>
                            </div>
                        </div>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-info" onclick="checkalltrue.addAnswer(this)"><i class="fa fa-plus"></i></button>
                                <button type="button" class="btn btn-info" onclick="choosethebest.removeAnswer(this)"><i class="fa fa-minus"></i></button>
                            </div>
                            <input onkeydown="choosethebest.keyup(this);" onchange="mathCheck(this)" type="text" placeholder="Đáp án" class="form-control">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-primary" onclick="checkalltrue.markRight(this)"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-info" onclick="checkalltrue.addAnswer(this)"><i class="fa fa-plus"></i></button>
                                <button type="button" class="btn btn-info" onclick="choosethebest.removeAnswer(this)"><i class="fa fa-minus"></i></button>
                            </div>
                            <input onkeydown="choosethebest.keyup(this);" onchange="mathCheck(this)" type="text" placeholder="Đáp án" class="form-control">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-primary" onclick="checkalltrue.markRight(this)"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                    </form>
                    
                </div>
            </div>
            <div id="askandanswer" hidden>
                <p>Đáp án</a>
                <textarea id="aaa-ans" class="form-control"></textarea>
            </div>
            <div id="putinplace" hidden>
                <div class="alert-info p-3 rounded">
                    Dùng <b>tô đen</b> để biểu thị vị trí cần điền vào và đáp án của nó, ví dụ "Đây là một <b>đáp án</b> đúng" thì cụm từ "đáp án" sẽ thay bằng chỗ trống.
                </div>
            </div>
            <div id="connectpair" hidden>
                <div id="cp-ans" style="min-height: 240px;width:100%;border-radius: 6px; padding: 6px; background: white;display:flex;">
                    <div id="cp-col-l" class="col-6" style="flex:1;background: #eee;border-radius: 6px;padding: 8px;">
                        <div class="cp-ans">
                            <div class="input-group cp-pair">
                                <div class="input-group-prepend">
                                    <button type="button" class="btn btn-info" onclick="connectpair.removeAnswer(this,false)"><i class="fa fa-minus"></i></button>
                                </div>
                                <input onkeydown="connectpair.keyup(this);" onchange="mathCheck(this)" type="text" placeholder="Đáp án" class="form-control">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-success cp-connector" onclick="connectpair.connector(this,false)"><i class="fa fa-arrow-right"></i></button>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-info w-100" onclick="connectpair.addAnswer(this,false)"><i class="fa fa-plus"></i> Thêm đáp án</button>
                    </div>
                    <div id="cp-connect" style="width: 160px;">
                        <canvas style="margin-top:8px" id="cp-canvas" width="160" height="100"></canvas>
                    </div>
                    <div id="cp-col-r" class="col-6" style="flex:1;background: #eee;border-radius: 6px;padding: 8px;">
                        <div class="cp-ans">
                            <div class="input-group cp-pair">
                                <div class="input-group-prepend">
                                    <button type="button" class="btn btn-success cp-connector" onclick="connectpair.connector(this,true)"><i class="fa fa-arrow-left"></i></button>
                                    
                                </div>
                                <input onkeydown="connectpair.keyup(this);" onchange="mathCheck(this)" type="text" placeholder="Đáp án" class="form-control">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-info" onclick="connectpair.removeAnswer(this,true)"><i class="fa fa-minus"></i></button>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-info w-100" onclick="connectpair.addAnswer(this,true)"><i class="fa fa-plus"></i> Thêm đáp án</button>
                    </div>
                </div>
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
<script type="text/javascript" src="/javascripts/questedit.js"></script>
<script type="text/javascript">
    var context=<?=$idmonhoc?>;
    var qtype = "choosethebest";
    function savequest(isreload){
        var text= encodeURIComponent(tinyMCE.activeEditor.getContent());
        var data = encodeURIComponent(getDataByType());
        var endpoint = "addquestion";
        if(window.quest){
            endpoint = "updatequestion&idquest="+quest.id;
        }
        fetch("/user/ajax",{
            method:"POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded"},
            body: `ajax=${endpoint}&idmonhoc=${context}&type=${qtype}&text=${text}&tags=${getTags()}&data=${data}`
        }).then(t=>t.text()).then(t=>{
            alert(t);
            if(isreload){
                location.reload();
            }
        });
    }
    function getDataByType(){
        if(qtype=="choosethebest"){
            return choosethebest.toData();
        }
        if(qtype=="checkalltrue"){
            return checkalltrue.toData();
        }
        if(qtype=="askandanswer"){
            return askandanswer.toData();
        }
        if(qtype=="putinplace"){
            return putinplace.toData();
        }
        if(qtype=="connectpair"){
            return connectpair.toData();
        }
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
    
    
    tinymce.init({
        selector:'#questtext',
        plugins:"image codesample table",
        setup: function(editor) {
            editor.on('keydown', function(e) {
                if(e.keyCode==9){
                    e.preventDefault();
                    switch(qtype){
                        case "choosethebest":
                            q("#ctb-ans")[0].querySelector("input").focus();
                            break;
                        case "checkalltrue":
                            q("#cat-ans")[0].querySelector("input").focus();
                            break;
                        case "askandanswer":
                            q("#aaa-ans")[0].focus();
                            break;
                        case "putinplace":
                            break;
                        case "connectpair":
                            break;
                    }
                }
            });
        },
        external_plugins: {
            'tiny_mce_wiris': `http://localhost:8000/mathtype.js`,
        },
        
        htmlAllowedTags: ['.*'],
        htmlAllowedAttrs: ['.*'],
        draggable_modal: true,
        toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | outdent indent | image table codesample tiny_mce_wiris_formulaEditor tiny_mce_wiris_formulaEditorChemistry'

    });
    <? if($idcauhoi) { ?>
        var quest = <?=json_encode($cauhoi)?>;
        changeqtype(quest.type);
        if(window[quest.type]){
            window[quest.type].fromData(quest.data);
        }
        parseTag(quest.tieuchi);
        g("questtype").value = quest.type;
    <? } ?>
</script>
<?
include "footer.htm";
?>