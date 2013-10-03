<article id="content" class="clearfix">

<script type="text/javascript">

$(function(){
    applyDefaultValue = function(e, val) {
      e.css('color', '#666').val(val);
      e.focus(function() {if(e.val() == val) {e.css('color', '').val('');}});
      e.blur(function() {if(e.val() == '') {e.css('color', '#666').val(val);}});
    };

    applyDefaultValue($('#username'), 'Enter username...');
    applyDefaultValue($('#password'), 'Enter password...');
});

</script>
<? if (isset($required)): ?>
	<div class="warning">You need to be logged in to use this functionality.</div>
<? endif; ?>


    <? if ($action == 'default'): ?>
        <form id="loginForm" action="/index/login" method="post">
            <label for="username">Username</label><input type="text" id="username" name="username"><br clear="all">
            <label for="password">Password</label><input type="text" id="password" name="password"><br clear="all">
            <input type="hidden" name="action-login" value="1">
            <br>
            New ? <a href="/signup" style="width: 68px;text-decoration: underline;">Sign Up!</a>
            <button type="submit" class="uibutton blue" style="width: 90px;float:right">Login</button>
        </form>
    <? elseif ($action == 'action-login'): ?>
        <div class="success">Logging in as <b><?=$_SESSION['username']?></b>...</div>
		<?
		$redirectUrl = (strpos($_SERVER['SERVER_NAME'], 'rule20.com') !== false) ? \models\session::get('Referer', '/') : '/';
		?>
        <meta http-equiv="refresh" content="2;url=<?=$redirectUrl?>">
    <? elseif ($action == 'action-logout'): ?>
        <div class="warning">Logging out...</div>
        <meta http-equiv="refresh" content="2;url=/">
    <? endif; ?>


</article>
<style>
    #loginForm {
        width: 360px;
    }
    #loginForm input {
        float:right;
    }
    #loginForm label {
        line-height: 36px;
        font-size: 16px;
        margin-right: 15px;
    }
</style>