<div class="quest" type="askandanswer">  
    <? if($isShowTag) { ?>
        <p><?=$tag?></p>
    <? } ?>
    <b><?=$questNum??""?></b> <span><?=$text?></span>
    <? if($isEdit) { ?>
        <div style="float: right;position: absolute;right: 5px;top:5px;">
            <button type="button" style="position: static;" class="btn btn-secondary" onclick="editQuest(this)"><i class="fa fa-edit"></i></button>
            <button type="button" style="position: static;" class="btn btn-danger" onclick="delQuest(this)"><i class="fa fa-trash"></i></button>  
        </div>
        
    <? } ?>
    <? if ($isShowAns) { ?>
        <span class="showans" style="display:none;"><?=$ans?></span>
    <? } ?>
    <div quest="askandanswer" class="d-block">
        <textarea style="min-height: 60px" onkeyup="keyup(this,['resize'])" class="form-control" placeholder="Nhập câu trả lời"></textarea>
    </div>
</div>