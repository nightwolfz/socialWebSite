<article class="content clearfix">

<ul class="uibutton-group">
	<li><a class="uibutton icon prev" href="/messages/"> Inbox </a></li>
</ul>

<h3>Reading a message from <?=$from_username?></h3>
<hr>
<? foreach($messages as $messageId => $message) :?>

        <div class="left comment">
			<img src="/uploads/t/<?=$message->thumb?>" alt="<?=$message->from_id?>" width="50" height="50" style="float:left;">
			<b>&nbsp; &nbsp; &nbsp; <?=$message->username?></b><br>
            <div style="width:760px; margin-left: 60px;"><span class="double_quote">&#147;</span><?=$message->text?><span class="double_quote">&#148;</span></div>
            <em style="font-size: 11px;color:#666; float:right;"><?=\models\Time::show($message->timestamp)?></em>
        </div>
        <br clear="all"><hr>
<? endforeach; ?>

<form action="/messages/write" method="POST">
	<textarea name="message-box" style="width:808px; height:100px;"></textarea>
	<input type="hidden" name="message_id" value="<?=$message_id;?>">
	<input type="hidden" name="to_id" value="<?=$from_id;?>">
	<br/>
	<button class="uibutton icon add" type="submit"> Submit </button>
</form>
</article>