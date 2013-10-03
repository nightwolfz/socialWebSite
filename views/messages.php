<article class="content clearfix">

<h3>Inbox</h3>
<hr>
<? foreach($messages as $messageId => $message) :?>

        <div class="left comment pointer" onclick="window.location.href='/messages/read/<?=$message->id?>'">
			<img src="/uploads/t/<?=$message->thumb?>" alt="<?=$message->from_id?>" width="50" height="50" style="float:left;">
			<b>&nbsp; &nbsp; &nbsp; <?=$message->username?></b><br>
            <div style="width:760px; margin-left: 60px;"><span class="double_quote">&#147;</span><?=$message->text?><span class="double_quote">&#148;</span></div>
            <em style="font-size: 11px;color:#666; float:right;"><?=\models\Time::show($message->timestamp)?></em>
        </div>
        <br clear="all"><hr>
<? endforeach; ?>

</article>