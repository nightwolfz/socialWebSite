<link rel="stylesheet" media="all" href="<?=ROOT?>/views/css/jquery.cleditor.css"/>
<script type="text/javascript" src="<?=ROOT?>/views/js/jquery.cleditor.min.js"></script>
<script type="text/javascript">

$(document).ready(function(){
    // Assign default values for input fields
    applyDefaultValue = function(e, val) {
      e.style.color = '#666';
      e.value = val;
      e.onfocus = function() {if(this.value == val) {this.style.color = '';this.value = '';}};
      e.onblur = function() {if(this.value == '') {this.style.color = '#666';this.value = val;}};
    };

    <? if (\models\session::get('username') && $action == 'topic'): ?>

        applyDefaultValue(document.getElementById('comment-box'), 'Write your post here...');

        // CLEditor jquery editor
        if ($("#comment-box")){
            editor = $("#comment-box").cleditor({
                width:"100%",
                height:"100%",
                controls: "bold italic underline strikethrough subscript superscript | font size " +
                        "style | color highlight removeformat | bullets numbering | outdent " +
                        "indent | alignleft center alignright | undo redo | rule image link unlink"
            })[0].focus();
        }
    <? endif; ?>
});
</script>
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
    width: 870px;
    line-height: normal;
}
.comment_wrapper.comment_post {
    border: 4px solid #cec;
}

</style>

<article id="content" class="clearfix">
    <ul class="uibutton-group">
        <li><a class="uibutton icon prev" title="<?=$_SERVER['HTTP_REFERER']?>" onclick="history.go(-1)"> Back </a></li>
    </ul>
    <br>

<? if ($action == 'categories'): ?>
    <table>
        <tr><th>Category</th><th>Description</th></tr>
        <?
        foreach($categories as $category){
            echo "<tr><td><a href='".ROOT."/forum/category/$category->cat_id'>$category->cat_name</a></td><td>$category->cat_description</td></tr>";
        }
        ?>
    </table>
<? elseif ($action == 'topics'): ?>
    <h3>Topics in ′<?=$categoryTitle?>′ category</h3>
    <table>
        <tr><th>Subject</th><th>Created by</th><th>Created on</th></tr>
        <?
        foreach($topics as $topic){
            echo "<tr><td><a href='".ROOT."/forum/topic/$topic->topic_id'>$topic->topic_subject</a></td><td>$topic->username</td><td>$topic->topic_date</td></tr>";
        }
        ?>
    </table>
<? elseif ($action == 'topic'):
    echo "<h3>$topicTitle</h3>";

    foreach ($posts as $id => $v): ?>
    <div class="comment_wrapper">
        <div class="left comment">
            <div class="userBlockMini left">
                <img height="60" width="60" src="<?=ROOT?>/uploads/t/<?=$v->avatar?>">
                <span class="avatarAge"><?=\models\Time::getAge($v->birthday);?></span>
            </div>
            &nbsp;&nbsp; <img height="13" width="6" src="<?=ROOT?>/views/images/<?=$v->gender?>.png"> <b><?=$v->username?></b><br>

            <span class="double_quote">&#147;</span><?=$v->post_content?><span class="double_quote">&#148;</span>
            <em style="font-size: 11px;color:#666;">- <?=\models\Time::show(strtotime($v->post_date))?></em>
        </div>
        <br clear="all">
    </div> <?
    endforeach;
    if (\models\session::get('username')): ?>
        <form action="" method="post">
            <div class="comment_wrapper comment_post">
            <textarea id="comment-box" name="post-content" style="height:50px;width:100%;margin:0;"></textarea>
            </div>
            <input type="hidden" name="userid" value="<?=$result->login_id?>">
            <input type="hidden" name="username" value="<?=$result->username?>">
            &nbsp; <button class="gbutton blue" id="comment-button">Reply to post</button>
        </form>
    <? endif;
endif; ?>


</article>
<br/>