<link rel="stylesheet" media="all" href="<?=ROOT?>/views/css/jquery.cleditor.css"/>
<script type="text/javascript" src="<?=ROOT?>/views/js/jquery.cleditor.min.js"></script>

<script type="text/javascript">

$(document).ready(function(){
    // common ajax settings
    $.ajaxSetup({
        type: 'POST',
        async: false,
        timeout: 500
    });
    
    /**
     * Start "like" button functionality
     */
    likeAction = function(like){
        $.ajax({
            url: '<?=ROOT?>/users/likeAction',
            data: { 'crush_id': <?=$result->login_id?>, 'action': like },
            success: function(data){
                if (data == 'LIKE'){
                    $('#likeButton').html('I already like <?=$him?>');
                    refreshLike(true);
                }else{
                    $('#likeButton').html('I like <?=$him?>');
                    refreshLike(false);
                }
            }
        });
    };
    
    refreshLike = function(bool){
        tempLiked = bool;
        tempLikeText = tempLiked ? 'I already like <?=$him?>' : 'I like <?=$him?>';
        $('#likeButton').html(tempLikeText);
    };
    
    $('#likeButton').hover(function(){
        window.tempLikeText = $('#likeButton').html();
        if (tempLiked) $('#likeButton').html('[Dislike now]');
    }, function(){
        $('#likeButton').html(window.tempLikeText);
    });
    
    
    /**
     * Start chat functionality
     */
    openChat = function(login_id){
        console.log('Not implemented yet. Id: ' + login_id);
    };

    /**
     * Assign default values for input fields
     */ 
    applyDefaultValue = function(e, val) {
        if (typeof(e) != 'undefined' && e){
      		e.style.color = '#666';
      		e.value = val;
            e.onfocus = function() {if(this.value == val) {this.style.color = '';this.value = '';}};
      		e.onblur = function() {if(this.value == '') {this.style.color = '#666';this.value = val;}};
        }
    };
    defaultGuestbookText = 'Write your comment here...';
    applyDefaultValue(document.getElementById('comment-box'), defaultGuestbookText);
    
    // Prevent submitting defaultText
    $('#comment-button').click(function(event){
        if ($('#comment-box').val()==defaultGuestbookText){
            event.preventDefault();
            alert('Write something meaningful please');
        }
    });

    // Modal window for pictures
	$("a[rel='album']").colorbox({transition:"fade"});

    <? if (\models\session::get('username') == $username): ?>
        // CLEditor jquery editor
        editor = $(".form_edit").cleditor({
            width:"100%",
            height:"100%",
            controls: "bold italic underline strikethrough subscript superscript | font size " +
                      "style | color highlight removeformat | bullets numbering | outdent " +
                      "indent | alignleft center alignright | undo redo | rule image link unlink"
        })[0].focus();
    <? endif; ?>

    /**
     * Do stuff if logged in
     */
    <? if (\models\session::get('login_id')): ?>
    $('.editable').inlineEdit({
        buttons: '',
        save: function(event, data) {
            $.ajax({
                url: '<?=ROOT?>/users/updateStatus',
                data: { 'value': data.value }
            });
        }
    });
    refreshLike(<?=$iLikeOrNot?"true":"false"?>);
    <? endif; ?>
});
</script>
<!--start Main-->
<div id="content" class="clearfix">
    <div class="userBlock userBlockNoHover <?=$result->gender=='m'?'male':'female'?>">
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
            <span class="pt5" style="text-align:left">
                <?/*
                <span class="name fc<?=$result->gender=='f'?'fe':''?>male"><?=$result->username?></span><br>
                <span class="normalfont"><b class="greenfont">Online</b> | <a href="javascript:openChat(<?=$result->login_id?>);" style="display:inline;">Chat Online</a></span><br>
                <span class="fs12"><?=$result->location?> - <?=$result->country?><br></span><br>
                <b><span class="fc<?=($result->gender=='f'?'fe':'')?>male"><?=($result->gender=='f'?'Female':'Male')?></span> - <?=\models\Time::getAge($result->birthday)?> years old.</b>
                <div class="comment_wrapper">
                    <img src="/views/images/shape_square.png">
                        <span class="editable"> <?=($result->mood ? $result->mood : 'Click to edit mood...')?></span>
                    <img src="/views/images/pencil.png">
                </div>*/?>
                <a id="messages" class="win-command" href="/messages/" style="float:left">
                    <span class="win-commandicon win-large">&#57474;</span>
                    <span class="win-label-right">Favorite</span>
                </a>
                <br clear="all">
                <a id="messages" class="win-command" href="/messages/" style="float:left">
                    <span class="win-commandicon win-large">&#57653;</span>
                    <span class="win-label-right">Send message</span>
                </a>
            </span>
    </div>
    <div class="  buttonGrid">
        <? if (\models\session::get('username') == $username): ?>
            <a class="" href="/photos">Add pictures</a><br>
            <a class="" href="/settings">Messages</a><br>
            <a class="" href="/settings">Edit settings</a><br>
        <? elseif (\models\session::get('username')): ?>
            <button class="gbutton" id="likeButton" onclick="likeAction(1)" style="width:130px;">&nbsp;</button>
        <? endif; ?>
    </div>
    {clear}
</div>


<?
# Flash a message!
isset($flash) and print $flash or print '<br clear="all">';

$pictureCounter = 0;
?>

<!--start Pictures-->
<div class="clearfix content">
    <h3>Photos. <a href="/photos/" class="orange"><img style="vertical-align:baseline;" src="/views/images/add.png"> Add</a></h3><hr>

    <? foreach ($pictures as $id => $v): ?>
        <? if ($v->avatar) continue; ++$pictureCounter; ?>
        <a rel="album" href="/uploads/<?=$v->thumb?>">
            <div class="left picture" style="margin-right:5px;"><img height="120" width="120" src="/uploads/t/<?=$v->thumb?>" alt="<?=$v->id?>"></div>
        </a>
    <? endforeach; ?>
    <? if ($pictureCounter == 0): ?>
        <div class="message">Looks like <?=$result->gender=='m'?'he':'she'?> didn't upload any pictures :(</div>
    <? endif; ?>
	{clear}
</div>

<br clear="all">

<? /*
<!--start Blacktab-->
<ul class="clearfix">
    <li class="blacktab">xoofoezrfezfzer</li>
    <li class="blacktab selected">1424524524</li>
</ul>
<br clear="all">*/
?>

<!--start Info-->
<div class="clearfix content">
	<h3>About me.</h3><hr>
    <? if (\models\session::get('username') == $result->username): ?>
        <form method="post" action="/users/updateField">
            <textarea name="form_edit" class="form_edit"><?=$result->like?></textarea>
            <button class="gbutton red" type="submit">Save</button>
        </form>
    <? else: ?>
        <?=html_entity_decode($result->like)?>
    <? endif; ?>
	{clear}
</div>

<br clear="all">


<div class="grid_5" style="margin:0;">
<div class="content">
	<h3>Useless info.</h3><hr>
    <?
    if (\models\session::get('username') == $username):
        echo '<form method="post" action="/users/updateInfo">
        <ul class="user_stats">';
        foreach ($mainInfoBox as $key => $options){
            echo "<li><span><b>$key</b></span><select name='{$key}'>";
            foreach ($mainInfoBoxEdit[$key] as $option){
                echo "<option ".($mainInfoBox[$key]==$option ? 'selected' : '').">$option</option>";
            }
            echo "</select></li>";
        }
        echo '</ul><button class="gbutton red" type="submit">Save</button>
        </form>';
    else:
    	if (isset($mainInfoBox)){
            echo '<ul class="user_stats">';
            foreach ($mainInfoBox as $key => $what){
                echo "<li><span><b>".ucfirst($key)."</b> </span><span>$what</span></li>";
            }
            echo '</ul>';
        }else{
            echo "<div class=\"warning\"><b>{$result->username}</b> hasn't filled in anything here yet.<br><a onclick=\"\">Click here to ask {$him} about it</a>.</div>";
        }
    endif; ?>
</div>
</div>
<div class="grid_7" style="margin:0 0 0 26px;">
<div class="content">
	<h3>First thing people notice about me.</h3><hr>
	<?
	if (\models\session::get('username') == $result->username):
        echo '<form method="post" action="/users/updateField">
              <textarea name="notice_edit" class="form_edit">'.$result->notice_about.'</textarea>
              <button class="gbutton red" type="submit">Save</button>
              </form>';
    elseif (empty($result->notice_about)):
        echo "<div class=\"warning\"><b>{$result->username}</b> hasn't filled in anything here yet.<br><a onclick=\"\">Click here to ask {$him} about it</a>.</div>";
    else:
		echo html_entity_decode($result->notice_about);
	endif;
	?>
</div>
</div>

<br clear="all"/>
<br clear="all"/>
<!--start Guestbook-->
<? include("user_guestbook.php"); ?>
<!-- end -->