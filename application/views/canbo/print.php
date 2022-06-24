<?php
checkLogin();
$idresult = $_GET['idmatrix'] ?? 0;
$idmonhoc = $_GET['idmonhoc'] ?? 0;
$idchallenge = $_GET['idchallenge'] ?? 0;

if(isset($_GET['idresult'])){
    $idresult = (int)$_GET['idresult'];
    $matrixresult = buildSearch(["id"=>(int)$_GET['idresult']]);
    if(!isAdmin()){
        $matrixresult = $matrixresult->add("runner",(int)$_SESSION['id']);
    }
    $matrixresult = $matrixresult->asJson("data")->exec("matrixresult");
    if(!$matrixresult){
        die("Không tìm thấy kết quả ma trận này");
    }
    $matrix = buildSearch(["id"=>$matrixresult->idmatrix])->exec("matrix");
    if(!$matrix){
        die("Không tìm thấy ma trận này.");
    }
    $monhoc = getMonhoc($matrix->idmonhoc);
    $isreplayresult = true;
}else{
    die("Lỗi không xác định");
}
$runcount = $_GET['runcount'] ?? 1;
$runcount = (int)$runcount;
if($runcount < 1)$runcount = 1;
checkAccessToMonhoc($idmonhoc);
include "header.htm";
?>
<div class="container p-2" id="setting">
    <button class="btn btn-primary" onclick="printThis()">In</button>
    <button class="btn btn-primary" onclick="showAnswer2(this)">Xem đáp án</button>
    <button class="btn btn-primary" onclick="exportToWord()">Xuất ra Word</button>
    <button class="btn btn-primary" onclick="toPdf()">Xuất ra Pdf</button>
    <button class="btn btn-primary" onclick="changeRuncount()">Đổi số lượng đề</button>
    <button class="btn btn-primary" onclick="changetesttime()">Sửa thời gian làm bài</button>
</div>
<div class="container" id="page" style="font-size: 28px">
    <?php
        $className = getModule("matrix");
        getModule("quest");
        $module =new $className;
        $challenge = $module->replayResult($idmonhoc,$matrixresult->data);
        
        if(!$challenge){
            die("Không thể tạo được đề dựa trên ma trận này, hãy kiểm tra số câu hỏi có phù hợp không.");
        }
        $challenge = $challenge[$idchallenge];
        for($i=0;$i<$runcount;$i++){
            shuffle($challenge);
            $made = $i+1;
            ?>
                <div class="de" id="de-<?=$i?>" idresult="<?=$idresult?>">
                <div>
                    <? include "print_header.htm"; ?>
                </div>
            <?
            for($j=0; $j<count($challenge);$j++){
                $q = Question::GetQuestByType($challenge[$j]->type)->load($challenge[$j]);
                echo $q->render(false,false,"Câu ".($j+1).".",true);
            }
            ?>
                </div>
            <?
            if($i < $runcount - 1){
                echo "<div class='pagebreak'></div>";
            }
        }
        ?>
        </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="/javascripts/questedit.js"></script>
<script>
    function showAnswer2(btn){
        if(btn.textContent.contain("Xem")){
            showAnswer();
            btn.textContent = "Ẩn đáp án";
        }else{
            hideAnswer();
            btn.textContent = "Xem đáp án";
        }
        
    }
    function printThis(btn){
        g("setting").style.display = "none";
        window.print();
        g("setting").style.display = "block";
    }
    function changeRuncount(){
        var runcount = prompt("Nhập số lượng đề");
        if(runcount){
            runcount = parseInt(runcount);
            if(runcount < 1)runcount = 1;
            window.location.href = "?idresult=<?=$idresult?>&idmonhoc=<?=$idmonhoc?>&idchallenge=<?=$idchallenge?>&runcount="+runcount;
        }
    }
    MathJax = {
        tex: {
            inlineMath: [['[math]', '[/math]']]
        },
        svg: {
            fontCache: 'global'
        }
    };
    $(document).ready(function(){
        g("pageheader").remove();
        g("pagefooter").remove();
    });
    function exportToWord(){
        q(".de").forEach(e=>{
            var name = e.querySelector(".ma-de").textContent;
            var elementid = e.id;
            Export2Word(e.id,name+" <?=htmlentities($matrix->name)." ".date("d/m/y")?>.docx");
        });
        
    }
    // from https://www.codexworld.com/export-html-to-word-doc-docx-using-javascript/#:~:text=JavaScript%20Code%3A,ID%20to%20export%20content%20from.
    
    function Export2Word(element, filename = ''){

        var preHtml = "<html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:w='urn:schemas-microsoft-com:office:word' xmlns='http://www.w3.org/TR/REC-html40'><head><meta charset='utf-8'><title>Đề dựa theo ma trận</title><link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css'></head><body>";
        var postHtml = "</body></html>";
        var html = preHtml+document.getElementById(element).innerHTML+postHtml;

        var blob = new Blob(['\ufeff', html], {
            type: 'application/msword'
        });
        
        // Specify link url
        var url = 'data:application/vnd.ms-word;charset=utf-8,' + encodeURIComponent(html);
        
        // Specify file name
        filename = filename?filename+'.doc':'document.doc';
        
        // Create download link element
        var downloadLink = document.createElement("a");

        document.body.appendChild(downloadLink);
        
        if(navigator.msSaveOrOpenBlob ){
            navigator.msSaveOrOpenBlob(blob, filename);
        }else{
            // Create a link to the file
            downloadLink.href = url;
            
            // Setting the file name
            downloadLink.download = filename;
            
            //triggering the function
            downloadLink.click();
        }
        
        document.body.removeChild(downloadLink);
    }
   
</script>
<script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
<script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
<style>
    .showans::first-letter{
        text-decoration: underline;
    }
    .h-time-edit{
        width: 80px;
        display: inline-block;
    }
    .quest {
        position: relative;
        padding: 8px;
        border-radius: 0;
        background-color: transparent;
        font-family: serif;
        font-size: inherit;
        margin-bottom: 0px;
        padding: 0;
        line-height: 1.1;
    }
    .quest:hover {
        background-color: transparent;
    }
    .quest > p:nth-child(1){
        font-weight: 800;
    }
    .quest > p:nth-child(1), .quest > p:nth-child(2) {
        display: inline;
        font-size: inherit;
    }.quest > p:nth-child(2) {
        margin-left: 4px;
    }
    .quest textarea, .quest textarea:hover, .quest textarea:focus, .quest textarea::placeholder {
        background-color: transparent;
        border: none;
        font-family: sans-serif;
        box-shadow: none;;
        color:transparent;
    }
    .quest p {
        margin: 0;
        font-size: inherit;
    }
    .quest > [quest]{
        padding: 0 24px;
    }
    @media print {
        .pagebreak { page-break-after: always; } 
    }
</style>
<script>
    q("textarea").forEach(function(e){
        e.remove();
    });
    q(".showans + [quest=putinplace]").forEach(e=>{
        var inputs = e.querySelectorAll("input");
        inputs.forEach(function(e){
            var dots = "";
            var width = e.clientWidth;
            var numdot = width / 5;
            for(var i = 0; i < numdot; i++){
                dots += ".";
            }
            e.previousSibling.textContent+= dots;
            e.remove();
        });
    });
    function updateTestTime(t){
        q(".h-time-edit").forEach(function(e){
            e.textContent = t;
        });
    }
    q(".h-time-edit").forEach(function(e){
        e.contentEditable = "true";
        e.onkeyup = function(){
            updateTestTime(this.textContent);
        }
    });
    function changetesttime(){
        var t = prompt("Nhập thời gian làm bài");
        if(t){
            t = parseInt(t);
            if(t < 1)t = 10;
            updateTestTime(t);
        }
    }
    function toPdf(){
        g("page").style.fontSize = "18px";
        q(".de").forEach(e=>{
            var name = e.querySelector(".ma-de").textContent;
            var opt = {
                margin:       0.5,
                filename:     name+' <?=htmlentities($matrix->name)." ".date("d/m/y")?>.pdf',
                image:        { type: 'jpeg', quality: 1 },
                html2canvas:  { scale: 1 },
                jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' },
                pagebreak:    { mode: 'avoid-all', after: '.pagebreak' }
            };
            html2pdf(e, opt);
        });
        
        setTimeout(function(){
            g("page").style.fontSize = "28px";
        },5000);
    }
</script>
<?php include "footer.htm" ?>