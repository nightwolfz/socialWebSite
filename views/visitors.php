<script type="text/javascript">
$(document).ready(function(){

});
</script>
<?
# Flash a message!
isset($flash) and print $flash;
?>

<div class="content">
	<h1>People who visited my profile recently</h1>
    <? foreach($visitors as $user) :?>
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