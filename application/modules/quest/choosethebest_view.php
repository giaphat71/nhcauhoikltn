<div class="quest" type="choosethebest">
    
    <? if($isShowTag) { ?>
        <p><?=$tag?></p>
    <? } ?>
    <p><?=$questNum??""?><?=$text?></p>
    <? if($isEdit) { ?>
        <button type="button" style="float: right" class="btn btn-secondary"><i class="fa fa-edit"></i></button>
    <? } ?>
    <? if ($type=="2x2") { ?>
        <div quest="choosable" class="row">
            <span class="col-6">A. <?=$answer[0] ?></span>
            <span class="col-6">B. <?=$answer[1] ?></span>
            <span class="col-6">C. <?=$answer[2] ?></span>
            <span class="col-6">D. <?=$answer[3] ?></span>
        </div>
    <? }else if ($type=="1x4") { ?>
        <div quest="choosable" class="d-block">
            <span class="d-block">A. <?=$answer[0] ?></span>
            <span class="d-block">B. <?=$answer[1] ?></span>
            <span class="d-block">C. <?=$answer[2] ?></span>
            <span class="d-block">D. <?=$answer[3] ?></span>
        </div>
    <? }else if ($type=="1x3") { ?>
        <div quest="choosable" class="row">
            <span class="col-6">A. <?=$answer[0] ?></span>
            <span class="col-6">B. <?=$answer[1] ?></span>
            <span class="col-6">C. <?=$answer[2] ?></span>
        </div>
    <? } 
    else if ($type=="1x2") { ?>
        <div quest="choosable" class="row">
            <span class="col-6">A. <?=$answer[0] ?></span>
            <span class="col-6">B. <?=$answer[1] ?></span>
        </div>
    <? } 
    else { ?>
        <div quest="choosable" class="d-block">
            <? for ($i=0; $i<count($answer); $i++) {
                ?>
                    <span class="d-block"><?=chr($i+65)." $answer[$i]"?></span>
                <?
            } ?>
        </div>
    <? } ?>
</div>