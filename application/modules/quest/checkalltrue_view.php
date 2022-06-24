<div class="quest" type="checkalltrue">  
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
        <span class="showans" style="display:none;"><?=implode(",",$ansNum)?></span>
    <? } ?>
    <div quest="checkalltrue" class="d-block">
        <? for ($i=0; $i<count($answer); $i++) {
            ?>
                <span class="d-block">
                    <span>
                        <input style="-webkit-appearance:checkbox;" type="checkbox" name="ans[]" value="<?=$i?>">
                    </span><?=chr($i+65).". $answer[$i]"?>
                </span>
            <?
        } ?>
    </div>
</div>