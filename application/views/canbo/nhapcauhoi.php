<?
checkLogin();
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
    #excelview:empty + div{
        display: block;
    }
    #excelview table{
        width: 100%;
        border-collapse: collapse;
    }
    #excelview table th{
        background-color: #eee;
        padding: 4px 8px;
        border-bottom: 1px solid #ddd;
    }
    #excelview table td{
        padding: 4px 8px;
    }
    #excelview table tr:nth-child(even){
        background-color: #f9f9f9;
    }
    table input[type=checkbox]{
        -webkit-appearance: checkbox;
    }
    #excelview:not(:empty) + div{
        display: block !important;
    }
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<div class="section bg-light">
    <div class="section-title">Nhập câu hỏi</div>
    <div class="section-body">
        <div id="excelview"></div>
        <div style="display:none;margin:20px;">
            <button class="btn btn-primary" id="import" onclick="saveQuestion()">Nhập các câu hỏi đã chọn</button>
        </div>
        <div onclick="g('excel').click()">
            <div style="margin: 20px auto;background:#eee;border-radius:12px;padding:24px;text-align:center;cursor:pointer">
                Mở file Excel
                <input type="file" id="excel" style="display:none" onchange="readExcel(this.files[0])">
            </div>
        </div>
        <div onclick="g('word').click()">
            <div style="margin: 20px auto;background:#eee;border-radius:12px;padding:24px;text-align:center;cursor:pointer">
                Mở file Word
                <input type="file" id="word" style="display:none" onchange="readWord(this.files[0])">
            </div>
        </div>
    </div>
    <script>
        var context=<?=$idmonhoc?>;
        // phát hiện tên các cột tương ứng
        function detectColumnName(name){
            if(name.match(/nội dung|noidung|noi dung|cau hoi|cauhoi/i)){
                return "cauhoi";
            }
            if(name.match(/^[abcd]$|đáp án [1234]/i)){
                return "dapan";
            }
            if(name.match(/dapandung|dapan$|^đáp án$/i)){
                return "dapandung";
            }
            if(name.match(/dokho|độ khó/i)){
                return "dokho";
            }
            return "";
        }
        // đọc file excel bằng xlsx.js
        function readExcel(file){
            var reader = new FileReader();
            reader.onload = function(e){
                var data = e.target.result;
                var workbook = XLSX.read(data, {type: 'binary'});
                var first_sheet_name = workbook.SheetNames[0];
                var worksheet = workbook.Sheets[first_sheet_name];
                var html = XLSX.utils.sheet_to_html(worksheet);
                $("#excelview").html(html);
                insertSelect();
            }
            reader.readAsBinaryString(file);
        }
        var detectedCols = [];
        function getTColName(a){
            switch(a){
                case "cauhoi":return "Nội dung câu hỏi";
                case "dapan":return "Đáp án";
                case "dapandung":return "Đáp án đúng";
                case "dokho":return "Độ khó";
            }
        }
        // thêm các nút lựa chọn vào đầu mỗi hàng
        function insertSelect(){
            var table = q("#excelview table")[0];
            var headlist = table.rows[0];
            var cols = [];
            detectedCols = [];
            for(var i = 0; i < headlist.cells.length; i++){
                var name = headlist.cells[i].innerText;
                var colname = detectColumnName(name);
                if(colname){
                    headlist.cells[i].innerText = getTColName(colname);
                    detectedCols.push({
                        name:colname,
                        index:i+1
                    });
                }
            }
            var selectHead = document.createElement("th");
            selectHead.innerHTML = "Chọn <input type='checkbox' onclick='selectAll(this)'>";
            headlist.insertBefore(selectHead, headlist.cells[0]);
            for(var i = 1; i < table.rows.length; i++){
                var row = table.rows[i];
                var selectCell = document.createElement("td");
                selectCell.innerHTML = "<input type='checkbox' onclick='selectRow(this)'>";
                row.insertBefore(selectCell, row.cells[0]);
            }
        }
        function selectAll(e){
            var table = q("#excelview table")[0];
            for(var i = 1; i < table.rows.length; i++){
                var row = table.rows[i];
                row.cells[0].firstChild.checked = e.checked;
            }
        }
        // tạo các object quest với các cột đã phát hiện
        function buildQuestWithColAndRow(row){
            if(detectedCols.filter(col=>col.name=="cauhoi").length>0){
                var index = detectedCols.filter(col=>col.name=="cauhoi")[0].index;
                var cauhoi = row.cells[index].innerText;
                var quest = {
                    text: cauhoi,
                    data:{
                        answer:[],
                        rightanswer:[]
                    },
                    tags: [],
                };
                var dapans = detectedCols.filter(col=>col.name=="dapan");
                if(dapans.length>0){
                    for(var i = 0; i < dapans.length; i++){
                        var index = dapans[i].index;
                        var dapan = row.cells[index].innerText;
                        if(dapan){
                            quest.data.answer.push(dapan);
                        }
                    }
                }
                if(detectedCols.filter(col=>col.name=="dapandung").length>0){
                    var index = detectedCols.filter(col=>col.name=="dapandung")[0].index;
                    var dapandung = row.cells[index].innerText;
                    if(dapandung){
                        if(dapandung.length > 1){
                            quest.data.rightanswer = dapandung;
                        }else{
                            if(dapandung.match(/[ABCD]/i)){
                                // get number 1 2 3 4 from A B C D ascii
                                var ansindex = dapandung.charCodeAt(0) - 65;
                                quest.data.rightanswer = dapan[ansindex];
                            }else{
                                quest.data.rightanswer = dapandung;
                            }
                        }
                    }
                }
                if(detectedCols.filter(col=>col.name=="dokho").length>0){
                    var index = detectedCols.filter(col=>col.name=="dokho")[0].index;
                    var dokho = row.cells[index].innerText;
                    if(dokho){
                        quest.tags.push({
                            slugname: "do-kho",
                            value: dokho
                        });
                    }
                }
                return quest;
            }
            return false;
        }
        // lưu vào hệ thống các câu hỏi đã chọn
        function saveQuestion(){
            var quests = [];
            var table = q("#excelview table")[0];
            for(var i = 1; i < table.rows.length; i++){
                var row = table.rows[i];
                if(row.cells[0].firstChild.checked){
                    var quest = buildQuestWithColAndRow(row);
                    if(quest){
                        quests.push(quest);
                    }
                }
            }
            console.log(quests);
            fetch("/user/ajax",{
                method:"POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded"},
                body: `ajax=addquestions&idmonhoc=${context}&quests=${encodeURIComponent(JSON.stringify(quests))}`
            }).then(t=>t.text()).then(t=>{
                alert(t);
            });
        }
        
    </script>
</div>