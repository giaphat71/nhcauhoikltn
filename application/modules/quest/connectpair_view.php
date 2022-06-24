<div class="quest" type="connectpair">  
    <? if($isShowTag) { ?>
        <p><?=$tag?></p>
    <? } ?>
    <b style="margin-bottom: 8px;"><?=$questNum??""?></b> <span><?=$text?></span>
    <? if($isEdit) { ?>
        <div style="float: right;position: absolute;right: 5px;top:5px;">
            <button type="button" style="position: static;" class="btn btn-secondary" onclick="editQuest(this)"><i class="fa fa-edit"></i></button>
            <button type="button" style="position: static;" class="btn btn-danger" onclick="delQuest(this)"><i class="fa fa-trash"></i></button>  
        </div>
        
    <? } ?>
    <? if ($isShowAns) { ?>
        <span class="showans" style="display:none;"><?=json_encode($ans)?></span>
    <? } ?>
    <div quest="connectpair" class="d-flex">
        <div class="col-6 cp-l" style="flex:1;padding-right:0px;position:relative">
            <?php foreach ($ansleft as $key => $value) { ?>
                <div class="cp-item"><?=$value?></div>
            <?php } ?>
        </div>
        <div class="col-2 cp-connect" style="width: 160px;padding: 0px;">
            <canvas class="cp-render" width="160" height="100"></canvas>
        </div>
        <div class="col-6 cp-r" style="flex:1;padding-left:0px;position:relative">
            <?php foreach ($ansright as $key => $value) { ?>
                <div class="cp-item"><?=$value?></div>
            <?php } ?>
        </div>
    </div>
</div>