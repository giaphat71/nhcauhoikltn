<div class="quest" type="choosethebest">  
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
        <span class="showans" style="display:none;"><?=$ansNum?></span>
    <? } ?>
    <? if ($type=="2x2") { ?>
        <div quest="choosable" class="row">
            <span class="col-6"><b class="ctb-letter">A.</b> <?=$answer[0] ?></span>
            <span class="col-6"><b class="ctb-letter">B.</b> <?=$answer[1] ?></span>
            <span class="col-6"><b class="ctb-letter">C.</b> <?=$answer[2] ?></span>
            <span class="col-6"><b class="ctb-letter">D.</b> <?=$answer[3] ?></span>
        </div>
    <? }else if ($type=="1x4") { ?>
        <div quest="choosable" class="d-block">
            <div class="d-block"><b class="ctb-letter">A.</b> <?=$answer[0] ?></div>
            <div class="d-block"><b class="ctb-letter">B.</b> <?=$answer[1] ?></div>
            <div class="d-block"><b class="ctb-letter">C.</b> <?=$answer[2] ?></div>
            <div class="d-block"><b class="ctb-letter">D.</b> <?=$answer[3] ?></div>
        </div>
    <? }else if ($type=="1x3") { ?>
        <div quest="choosable" class="row">
            <div class="col-6"><b class="ctb-letter">A.</b> <?=$answer[0] ?></div>
            <div class="col-6"><b class="ctb-letter">B.</b> <?=$answer[1] ?></div>
            <div class="col-6"><b class="ctb-letter">C.</b> <?=$answer[2] ?></div>
        </div>
    <? } 
    else if ($type=="1x2") { ?>
        <div quest="choosable" class="row">
            <span class="col-6"><b class="ctb-letter">A.</b> <?=$answer[0] ?></span>
            <span class="col-6"><b class="ctb-letter">B.</b> <?=$answer[1] ?></span>
        </div>
    <? } 
    else { ?>
        <div quest="choosable" class="d-block">
            <? for ($i=0; $i<count($answer); $i++) {
                ?>
                    <span class="d-block"><b class="ctb-letter"><?=chr($i+65)?></b><?=$answer[$i]?></span>
                <?
            } ?>
        </div>
    <? } ?>
</div>