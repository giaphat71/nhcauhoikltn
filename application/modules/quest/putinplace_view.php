<div class="quest" type="putinplace">  
    <? if($isShowTag) { ?>
        <p><?=$tag?></p>
    <? } ?>
    <b><?=$questNum??""?></b> <p>Điền vào chỗ trống</p>
    <? if($isEdit) { ?>
        <div style="float: right;position: absolute;right: 5px;top:5px;">
            <button type="button" style="position: static;" class="btn btn-secondary" onclick="editQuest(this)"><i class="fa fa-edit"></i></button>
            <button type="button" style="position: static;" class="btn btn-danger" onclick="delQuest(this)"><i class="fa fa-trash"></i></button>  
        </div>
        
    <? } ?>
    <? if ($isShowAns) { ?>
        <span class="showans" style="display:none;"><?=json_encode($ans)?></span>
    <? } ?>
    <div quest="putinplace" class="d-block">
        <?=$text?>
    </div>
</div>