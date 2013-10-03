<div class="clearfix content">
    <h4><?=isset($posts) ? ucfirst(\models\String::convertNumber(count($posts))).' comments in total.' : 'Comments' ?></h4>
</div>

<br clear="all">

<style>
.comment_wrapper {
    border: 4px solid #eee;
    background: #fbfbfb;
    margin-bottom: 5px;line-height: 11px;
}
.comment_wrapper textarea {
    margin:0;
}
.comment_wrapper .comment {
    border: 1px solid #aaa;
    padding: 5px;
    width: 910px;
    line-height: normal;
}
.comment_wrapper.comment_post {
    border: 4px solid #cec;
}

</style>

<?
if (isset($posts)):
foreach ($posts as $id => $v):
?>
<div class="comment_wrapper">
    <div class="left comment">
        <img class="left" height="60" width="60" src="/uploads/t/<?=$v->thumb?>">

        <div style="padding:5px;">
            &nbsp;&nbsp; <img height="13" width="6" src="/views/images/<?=$v->gender?>.png">
            <a class="fc<?=$v->gender=='f'?'fe':''?>male" href="/user/<?=$v->poster_name?>"><?=$v->poster_name?></a>
            <em style="font-size: 11px;color:#666; float:right;">- <?=\models\Time::timeAgo($v->timestamp)?> ago</em>
        </div>

        <span class="double_quote" style="margin-left: 70px; position: absolute;">&#147;</span>
        <span style="width:840px; position: relative; left: 28px;">
            <?=$v->content?><span class="double_quote">&#148;</span>
        </span>

    </div>
    <br clear="all">
</div>
<?
endforeach;
else: echo '<p>No one left any comments yet, how about you write something nice?</p>';
endif;
?>

<? if (\models\session::get('login_id')): ?>
    <form action="/users/postComment" method="post">
        <div class="comment_wrapper comment_post">
        <textarea id="comment-box" name="comment-box" style="height:50px;width:100%;margin:0;"></textarea>
        </div>
        <input type="hidden" name="userid" value="<?=$result->login_id?>">
        <input type="hidden" name="username" value="<?=$result->username?>">
        &nbsp; <button class="gbutton blue" id="comment-button">Post Comment</button>
    </form>
<? endif; ?>

<ul class="pagination-clean">
<?
foreach ($pagination as $page){
    echo "<li>$page</li>";
}
?>
</ul>