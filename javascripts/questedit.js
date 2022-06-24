
var choosethebest={
    // lưu thành object để lưu vào csdl
    toData:function(){
        var obj = {
            answer:Array.from(g("ctb-ans").children[0].querySelectorAll("input[type=\"text\"]")).map(i=>i.value.trim()),
            rightanswer: g("ctb-ans").querySelector(".fa-check").parentElement.parentElement.previousElementSibling.value.trim(),
        };
        if(g("ctb-lockans").checked){
            obj.lockposition = true;
        }
        return JSON.stringify(obj);
    },
    // đánh dấu đáp án đúng
    markRight:function(btn){
        var f = findForm(btn);
        var fa = `<i class="fa fa-times"></i>`;
        var tr = `<i class="fa fa-check"></i>`;
        f.querySelectorAll(".input-group-append button").forEach(b=>{
            b.className="btn btn-primary";
            b.innerHTML=fa;
        });
        btn.className="btn btn-success";
        btn.innerHTML=tr;
    },
    // bỏ đáp án
    removeAnswer:function(btn){
        var row = btn.parentElement.parentElement;
        var f= findForm(btn);
        if(f.children.length < 3){
            return false;
        }
        row.remove();
    },
    // thêm đáp án
    addAnswer:function(btn){
        var row = btn;
        if(btn.tagName=="BUTTON"){
            row = btn.parentElement.parentElement;
        }
        var clone = document.createElement("div");
        clone.innerHTML = row.outerHTML;
        clone.querySelector("input").value="";
        var btn = clone.querySelector(".input-group-append button");
        btn.innerHTML = `<i class="fa fa-times"></i>`;
        btn.className="btn btn-primary";
        row.parentElement.appendChild(clone.children[0]);
    },
    // load danh sách đáp án để chỉnh sửa
    fromData:function(d){
        var container = g("ctb-ans");
        var form = container.children[0];
        for(var j=1;j<form.children.length;j++){
            form.children[j].remove();
        }
        var rightanswer = d.rightanswer.toLowerCase().trim();
        for(var i=0;i<d.answer.length;i++){
            var ans = d.answer[i].toLowerCase().trim();
            var input = form.lastElementChild.querySelector("input");
            input.value = d.answer[i];
            if(rightanswer==ans){
                this.markRight(form.lastElementChild.querySelector(".input-group-append > button"));
            }
            if(i<d.answer.length-1){
                this.addAnswer(form.lastElementChild);
            }
        }
        form.children[0].remove();
    },
    keyup:function(input){
        if(event.keyCode==13 || event.keyCode==9){
            var row = input.parentElement;
            var nextrow = row.nextElementSibling;
            event.preventDefault();
            if(nextrow){
                var input = nextrow.querySelector("input");
                input.focus();
            }
        }
    }
}
var checkalltrue = {
    toData:function(){
        var obj = {
            answer:Array.from(g("cat-ans").children[0].querySelectorAll("input[type=\"text\"]")).map(i=>i.value.trim()),
            rightanswer: Array.from(g("cat-ans").querySelectorAll(".fa-check")).map(i=>i.parentElement.parentElement.previousElementSibling.value.trim()),
        };
        return JSON.stringify(obj);
    },
    addAnswer:function(btn){
        var row = btn.tagName=="BUTTON" ? btn.parentElement.parentElement : btn;
        var clone = document.createElement("div");
        clone.innerHTML = row.outerHTML;
        clone.querySelector("input").value="";
        row.parentElement.appendChild(clone.children[0]);
    },
    markRight:function(btn){
        var fa = `<i class="fa fa-times"></i>`;
        var tr = `<i class="fa fa-check"></i>`;
        if(btn.className.indexOf("success")>0){
            btn.className="btn btn-primary";
            btn.innerHTML=fa;
        }else{
            btn.className="btn btn-success";
            btn.innerHTML=tr;
        }
    },
    markFalse:function(btn){
        var fa = `<i class="fa fa-times"></i>`;
            btn.className="btn btn-primary";
            btn.innerHTML=fa;
    },
    fromData:function(d){
        var container = g("cat-ans");
        var form = container.children[0];
        for(var j=1;j<form.children.length;j++){
            form.children[j].remove();
        }
        var rightanswer = d.rightanswer;
        for(var i=0;i<d.answer.length;i++){
            var ans = d.answer[i];
            var input = form.lastElementChild.querySelector("input");
            input.value = ans;
            this.markFalse(form.lastElementChild.querySelector(".input-group-append > button"));
            if(rightanswer.indexOf(ans) >= 0){
                this.markRight(form.lastElementChild.querySelector(".input-group-append > button"));
            }
            if(i<d.answer.length-1){
                this.addAnswer(form.firstElementChild);
            }
        }
        form.children[0].remove();
    }
}
var connectpair = {
    toData:function(){
        var obj = {};
        var leftcontainer = g("cp-col-l").querySelector(".cp-ans");
        var rightcontainer = g("cp-col-r").querySelector(".cp-ans");
        var ansleft = Array.from(leftcontainer.querySelectorAll("input[type=\"text\"]")).map(i=>i.value.trim());
        var ansright = Array.from(rightcontainer.querySelectorAll("input[type=\"text\"]")).map(i=>i.value.trim());
        var pair = [];
        for(var i1 in this.connectdata){
            var i2 = this.connectdata[i1];
            if(i1[0] == 'l'){
                pair.push([ansleft[parseInt(i1.substring(2))],ansright[parseInt(i2.substring(2))]]);
            }
        }
        obj.ansleft = ansleft;
        obj.ansright = ansright;
        obj.pair = pair;
        return JSON.stringify(obj);
    },
    fromData:function(d){
        var leftcontainer = g("cp-col-l").querySelector(".cp-ans");
        var rightcontainer = g("cp-col-r").querySelector(".cp-ans");
        var ansleft = d.ansleft;
        var ansright = d.ansright;
        var pair = d.pair;
        for(var i=0;i<ansleft.length;i++){
            var input = leftcontainer.lastElementChild.querySelector("input");
            input.value = ansleft[i];
            if(i<ansleft.length-1)
            this.addAnswer(leftcontainer.lastElementChild);
        }
        for(var i=0;i<ansright.length;i++){
            var input = rightcontainer.lastElementChild.querySelector("input");
            input.value = ansright[i];
            if(i<ansright.length-1)
            this.addAnswer(rightcontainer.lastElementChild,true);
        }
        for(var i=0;i<pair.length;i++){
            var left = pair[i][0];
            var right = pair[i][1];
            this.connectdata["l-"+ansleft.indexOf(left)] = "r-"+ansright.indexOf(right);
            this.connectdata["r-"+ansright.indexOf(right)] = "l-"+ansleft.indexOf(left);
        }
        this.render();
    },
    addAnswer:function(btn, leftright){
        var container = g("cp-col-l").querySelector(".cp-ans");
        if(leftright){
            container = g("cp-col-r").querySelector(".cp-ans");
        }
        var row = container.querySelector(".input-group");
        var clone = document.createElement("div");
        clone.innerHTML = row.outerHTML;
        clone.querySelector("input").value="";
        clone.querySelector(".cp-connector").style.backgroundColor="";
        container.appendChild(clone.children[0]);
    },
    keyup:function(input){
        if(event.keyCode==13 || event.keyCode==9){
            var row = input.parentElement;
            var nextrow = row.nextElementSibling;
            event.preventDefault();
            if(nextrow){
                var input = nextrow.querySelector("input");
                input.focus();
            }
        }
    },
    findRow:function(e){
        while(e.className.indexOf("cp-pair")<0){
            e = e.parentElement;
        }
        return e;
    },
    childIndex:function(e){
        return Array.from(e.parentElement.children).indexOf(e);
    },
    currentRow:null,
    selIsRight:false,
    selIndex:0,
    connector:function(e,leftright){
        var row = this.findRow(e);
        var index = this.childIndex(row);
        if(e.tagName=='I'){
            e = e.parentElement;
        }
        if(leftright){
            if(!this.checkValidConnect(-1,index)){
                return this.removeConnector(index,leftright);
            }
        }else{
            if(!this.checkValidConnect(index,-1)){
                return this.removeConnector(index,leftright);
            }
        }
        if(this.currentRow==row){
            this.clearAllMark();
            return;
        }
        if(this.currentRow){
            if(this.selIsRight == leftright){
                this.clearAllMark();
            }else{
                if(leftright){
                    if(this.checkValidConnect(this.selIndex,index)){
                        this.connect(this.selIndex,index);
                        this.clearAllMark();
                    }
                }else{
                    if(this.checkValidConnect(index,this.selIndex)){
                        this.connect(index,this.selIndex);
                        this.clearAllMark();
                    }
                }
            }
        }else{
            this.currentRow = row;
            
            this.selIsRight = leftright;
            this.selIndex = index;
            e.style.backgroundColor = "red";
        }
    },
    checkValidConnect:function(i1,i2){
        if("l-"+i1 in this.connectdata){
            return false;
        }
        if("r-"+i2 in this.connectdata){
            return false;
        }
        return true;
    },
    render:function(){
        if(!this.context){
            this.context = g("cp-canvas").getContext("2d");
            this.canvas  = g("cp-canvas");
        }
        this.canvas.height = Math.max($("#cp-col-l .cp-ans").height(),$("#cp-col-r .cp-ans").height());
        this.context.clearRect(0,0,this.canvas.width,this.canvas.height);
        for(var i1 in this.connectdata){
            var i2 = this.connectdata[i1];
            if(i1[0] == 'l'){
                var x1 = 0;
                var y1 = 38*parseInt(i1.substr(2)) + 19;
                var x2 = this.canvas.width;
                var y2 = 38*parseInt(i2.substr(2)) + 19;
                this.context.beginPath();
                this.context.moveTo(x1,y1);
                this.context.lineTo(x2,y2);
                this.context.stroke();

            }
        }
    },
    connect:function(i1,i2){
        this.connectdata["l-"+i1] = "r-"+i2;
        this.connectdata["r-"+i2] = "l-"+i1;
        this.render();
    },
    removeConnector:function(i,lr){
        if(lr){
            var li = this.connectdata["r-"+i];
            delete this.connectdata["r-"+i];
            delete this.connectdata["l-"+li.substr(2)];
        }else{
            var li = this.connectdata["l-"+i];
            delete this.connectdata["l-"+i];
            delete this.connectdata["r-"+li.substr(2)];
        }
        this.render();
    },
    clearAllMark:function(){
        this.currentRow = null;
        this.selIsRight = false;
        this.selIndex = 0;
        q("#cp-ans .cp-connector").forEach(e=>e.style.backgroundColor = "");
    },
    connectdata:{},
    context:null,
    canvas:null,
    removeAnswer:function(btn, leftright){
        var container = g("cp-col-l").querySelector(".cp-ans");
        if(leftright){
            container = g("cp-col-r").querySelector(".cp-ans");
        }
        var row = this.findRow(btn);
        var index = this.childIndex(row);
        container.removeChild(row);
        if(leftright){
            if("r-"+index in this.connectdata){
                this.removeConnector(index,true);
            }
        }else{
            if("l-"+index in this.connectdata){
                this.removeConnector(index,false);
            }
        }
        this.clearAllMark();
    }
}
var putinplace = {
    toData:function(){
        return "{}";
    },
    fromData:function(d){
        return;
    }
    
}
var askandanswer = {
    toData:function(){
        return JSON.stringify({
            answer: g("aaa-ans").value.trim(),
        });
    },
    fromData:function(d){
        g("aaa-ans").value = d.answer;
    }
}
// các hàm util
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
function findForm(btn){
    while(btn.tagName!="FORM"){
        btn=btn.parentElement;
    }
    return btn;
}
// đổi loại câu hỏi
function changeqtype(t){
    if(!g(t).hasAttribute("hidden")){
        return;
    }
    g("tabdiv").children.forEach(e=>{if(e.id!=t){e.setAttribute("hidden",true)}else{e.removeAttribute("hidden")}});
    this.qtype = t;
    if(t=="connectpair"){
        try {
            tinymce.activeEditor.setContent("Nối 2 cột để thành các câu có ý nghĩa đúng.");
        } catch (error) {
            
        }
        
    }
}
// kiểm dữ liệu nhãn dán
function parseTag(list){
    for(var i=0;i<list.length;i++){
        var tag = list[i];
        var tagspan = q("#tagspreview span[name='"+tag.slugname+"']")[0];
        tagspan.setAttribute("value",tag.value);
        tagspan.setAttribute("hasvalue",true);
        g("tags").appendChild(tagspan);
    }
}
// tự phát hiện toán học
function mathCheck(input){
    if(input.value.indexOf("<math")>0){
        var mathml = input.value.match(/<math .*?<\/math>/)[0];
        var latex = WirisPlugin.Latex.getLatexFromMathML(mathml);
        input.value = input.value.replace(mathml,`[math]${latex}[/math]`);
    }
}
function showAnswer(){
    q(".showans + [quest=choosable]").forEach(e=>{
        var ansindex = e.previousElementSibling.textContent;
        if(ansindex.length > 0)
        e.children[parseInt(ansindex)].classList.add("showans");
    });
    q(".showans + [quest=checkalltrue]").forEach(e=>{
        var ansindex = e.previousElementSibling.textContent;
        ansindex = ansindex.split(",").filter(e=>e!="");
        for(var i=0;i<ansindex.length;i++){
            e.children[parseInt(ansindex[i])].querySelector("[type=checkbox]").checked = true;
        }
    });
    try {
        q(".showans + [quest=putinplace]").forEach(e=>{
            var ansjson = e.previousElementSibling.textContent;
            try {
                var json = JSON.parse(ansjson);
                if(json.push){
                    for(var i=0;i<json.length;i++){
                        var inputs = e.querySelectorAll("input");
                        inputs[i].value = json[i];
                    }
                }
            } catch (error) {}
        });
        q(".showans + [quest=askandanswer]").forEach(e=>{
            var ans = e.previousElementSibling.textContent;
            e.querySelector("textarea").value = ans;
        });
    } catch (error) {
        
    }
    
    q(".showans + [quest=connectpair]").forEach(e=>{
        var pair = e.previousElementSibling.textContent;
        pair = JSON.parse(pair);
        var canvas = e.querySelector("canvas");
        canvas.width = canvas.parentElement.offsetWidth;
        canvas.height = canvas.parentElement.offsetHeight - 7;
        var context = canvas.getContext("2d");
        context.clearRect(0,0,canvas.width,canvas.height);
        var coll = e.querySelector(".cp-l");
        var colr = e.querySelector(".cp-r");
        for(var p of pair){
            var ans1 = p[0];
            var ans2 = p[1];
            for(var i=0;i<coll.children.length;i++){
                if(coll.children[i].textContent.trim() == ans1){
                    for(var j=0;j<colr.children.length;j++){
                        if(colr.children[j].textContent.trim() == ans2){
                            var x1 = 0;
                            var x2 = canvas.width;
                            var y1 = 18 + coll.children[i].offsetTop;
                            var y2 = 18 + colr.children[j].offsetTop;
                            context.beginPath();
                            context.moveTo(x1,y1);
                            context.lineTo(x2,y2);
                            context.stroke();
                            break;
                        }
                    }
                    break;
                }
            }
        }
    });
}
function hideAnswer(){
    q(".showans + [quest=choosable]").forEach(e=>{
        var ansindex = e.previousElementSibling.textContent;
        e.children[parseInt(ansindex)].classList.remove("showans");
    });
    q(".showans + [quest=checkalltrue]").forEach(e=>{
        e.querySelectorAll("[type=checkbox]").forEach(e=>{e.checked = false;});
    });
    try {
        q(".showans + [quest=putinplace]").forEach(e=>{
            var inputs = e.querySelectorAll("input");
            for(var i=0;i<inputs.length;i++){
                inputs[i].value = "";
            }
        });
        q(".showans + [quest=askandanswer]").forEach(e=>{
            e.querySelector("textarea").value = "";
        });
    } catch (e) {
        
    }
    
    q(".showans + [quest=connectpair]").forEach(e=>{
        e.querySelector("canvas").getContext("2d").clearRect(0,0,e.querySelector("canvas").width,e.querySelector("canvas").height);
    });
}