<div class="warning" style="width:400px;margin:0;">
    The user you are searching for doesn't exist.<br>
    No worries, we still love you...
</div>
<br>
<div class="content">
    <h3>Perhaps you were looking for</h3><hr>
    <? foreach($maybeUsers as $user) :?>
    <div class="userBlockSmall <?=$user->gender=='m'?'male':'female'?> clearfix">
        <a href="/user/<?=$user->username?>">
            <span class="userBlockLeft left">
                <img class="left" height="150" width="150" alt="<?=$user->username?>'s profile picture" src="/uploads/t/<?=$user->avatar?>">
                <span class="avatarName"><?=$user->username?></span>
                <span class="avatarAge">18</span>
            </span>
        </a>
    </div>
    <? endforeach; ?>
</div>
