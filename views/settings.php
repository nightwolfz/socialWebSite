<link rel="stylesheet" media="all" href="/views/css/jquery.cleditor.css"/>
<script type="text/javascript" src="/views/js/jquery.cleditor.min.js"></script>

<script type="text/javascript">

$(document).ready(function(){
    // common ajax settings
    $.ajaxSetup({
        type: 'POST',
        async: false,
        timeout: 500
    });
});
</script>

<!--start Main-->
<div class="userBlock userBlockNoHover" style="width: 150px;">
        <span class="userBlockLeft left">
            <? if (count($pictures)<1): ?>
            <img class="left" height="150" width="150" alt="<?=$result->username?>'s profile picture" src="/uploads/t/defaultm.jpg">
            <? endif; ?>
            <? foreach ($pictures as $id => $v): if (!$v->avatar) continue; ?>
            <img class="left" height="150" width="150" alt="<?=$result->username?>'s profile picture" src="/uploads/t/<?=isset($v->thumb)?$v->thumb:'defaultm.jpg'?>">
            <? endforeach; ?>
            <span class="avatarName"><?=$result->username?></span>
            <span class="avatarAge">18</span>
        </span>
</div>

<a class="gbutton" href="/settings/visitors">Show people who visited my profile</a>


<?
# Flash a message!
isset($flash) and print $flash;
?>

<div class="grid_7" style="margin:0 0 0 5px;">
<div class="content">
	<h3>What I'm searching for</h3><hr>
    <form method="post" action="/settings/saveSearchInfo">
        I want to 
        <input type="text" maxlength="50" name="wants_to" value="<?=$result->wants_to?>">
        with a 
        <select name="with_who">
            <option value="0" <?=($result->with_who==0?'selected':'')?>>Man</option>
            <option value="1" <?=($result->with_who==1?'selected':'')?>>Woman</option>
            <option value="2" <?=($result->with_who==2?'selected':'')?>>Man or Woman</option>
            <option value="3" <?=($result->with_who==3?'selected':'')?>>No one</option>
        </select>
        who's between
        <select name="age_range">
            <? foreach(\models\Users::$ageRanges as $age): ?>
                <option value="<?=$age?>" <?=($result->range_from.'-'.$result->range_to == $age ?'selected':'')?>><?=str_replace('-', ' and ', $age)?></option>
            <? endforeach; ?>
        </select>
        years old.

        <br clear="all">
        <button name="saveSearchInfo" class="gbutton red">Save</button>
    </form>
</div>
</div>