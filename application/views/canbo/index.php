<?
checkLogin();
$listmh = buildSearch(['idcanbo'=>$_SESSION['id']])
    ->project("monhoc.*, count(cauhoi.id) as num,permission.id as pemid,permission.islocked")
    ->leftJoin("cauhoi","cauhoi.idmonhoc=permission.idmonhoc")
    ->groupBy("permission.idmonhoc")
    ->leftJoin("monhoc","monhoc.id=permission.idmonhoc")
    ->limit(1000)
    ->exec("permission") ?? [];

include "header.htm" ?>
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
            margin-bottom: 6px;
        }
        .section{
            padding: 8px;
        }
        
    </style>
    <div class="section bg-light">
        <div class="section-title">Chọn một môn học để bắt đầu</div>
        <div class="section-body">
            <div class="flex" style="flex-wrap: wrap;">
            <? for ($i = 0;$i<count($listmh);$i++){ ?>
                <div class="monhoc col-4">
                    <a href="/canbo/list-cauhoi/<?=$listmh[$i]->id?>">
                        <div class="monhoc-title"><?=$listmh[$i]->name?></div>
                        <div class="monhoc-num">Số câu hỏi: <?=$listmh[$i]->num?></div>
                    </a>
                </div>
            <? } ?>
            </div>
        </div>
    </div>
<? include "footer.htm" ?>