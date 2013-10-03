<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8"/>
	<title><?=(isset($title) ? $title : 'Social Networking & Dating website');?></title>
    <?=(isset($metaDescription) ? '<meta name="description" content="'.$metaDescription.'" />' : '');?>
	<meta name="viewport" content="initial-scale=1.0, width=device-width, maximum-scale=1.0" />
    <meta name="google-site-verification" content="8vrQhs0O0aAYIMeXRTr6lW2ZKa3veUyQ7GSaU-0RrwY" />
    <meta name="msvalidate.01" content="6EA9CFD7E8C1E095197217F27242F4D5" />
	<link rel="stylesheet" media="all" href="/views/css/base.css"/>
	<link rel="stylesheet" media="all" href="/views/css/style.css"/>
    <link rel="stylesheet" media="all" href="/views/css/colorbox.css"/>
    <link rel="stylesheet" media="all" href="/views/css/ui-light.css"/>

	<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
    <script type="text/javascript" src="/views/js/jquery-1.7.1.js"></script>
    <script type="text/javascript" src="/views/js/jquery-ui-1.8.16.custom.min.js"></script>
    <script type="text/javascript" src="/views/js/jquery.animate-enhanced.min.js"></script>
    <script type="text/javascript" src="/views/js/jquery.inlineedit.js"></script>
    <script type="text/javascript" src="/views/js/jquery.colorbox-min.js"></script>
    <script type="text/javascript" src="/views/js/jquery.wipetouch.js"></script>
</head>
<body>
    
<header>
<?
if (\models\session::get('login_id')):
    echo '<span id="logged_in">Logged in as <a href="/user/'.\models\session::get('username').'">'.\models\session::get('username').'</a></span>';
    echo '<a style="float:right" href="/index/logout">Logout</a>';
else:
    echo '<img src="/views/images/message.png"> Join our community. <a href="/register" style="text-decoration:underline">Sign up</a> for free. (It only takes a minute!)';
endif;
?>
</header> 

<?
if (isset($userNews)){
    echo '<div>';
    foreach($userNews as $user) include 'user_block_small.php'; echo '<br clear="all">';
    echo '</div>';
}
?>
<nav>
    <ul>
        <?
        if (\models\session::get('login_id')):
            echo '<li><a class="orange" href="/user/'.\models\session::get('username').'">Me</a></li>';
            //echo '<li><a href="/blog/'.\models\session::get('username').'">Blog</a></li>';
        else:
            echo '<li><a class="orange" href="/index/login">Login</a></li>';
        endif;
    ?>
    <li><a href="/">Home</a></li>
    <li><a href="/feed/">What's new</a></li>
    <li><a href="/search/">Search</a></li>
    <?if (\models\session::get('login_id')):?>
        <li><a href="/messages/">Messages</a></li>
        <li><a href="/photos/">Photos</a></li>
        <li><a href="/favorites/">Favorites</a></li>
    <?endif;?>
    <li><a href="/forum/">Forum</a></li>
    </ul>
</nav>
    
<div id="container">
	<div id="main">
        <section style="margin:0px;">
            <br clear="all">
            <?/*
            //<g:plusone annotation="inline"></g:plusone>
            if(strpos($_SERVER['REQUEST_URI'], '/user/')!==false): ?>
                <h3>Similar profiles</h3>
                <?
                foreach($similarProfiles as $sp){
                    echo '<div style="padding:3px 10px 3px 0px; margin-top:5px;"><img height="13" width="6" src="/views/images/'.$sp->gender.'.png"> <a href="/user/'.$sp->username.'">'.$sp->username.'</a></div>';
                }

            endif; */?>
            <?
            // Print the contents of the view
            print $content->returnString();
            // Print page numbers if pagination is set
            if(isset($pagination)) print $pagination;
            ?>
        </section>
        <br clear="all">
	</div>

	<!--<footer>
		<div class="container">
			<div class="grid_8 stats">Page rendered in <? print round((microtime(true) - START_TIME), 4); ?> seconds
			taking <? print round((memory_get_usage() - START_MEMORY_USAGE) / 1024, 2); ?> KB
			(<? print (memory_get_usage() - START_MEMORY_USAGE); ?> Bytes)
			</div>
		</div>
	</footer>-->

</div>

<div class="right-sidebar">
    <? include('sidebar.php'); ?>
</div>

<script>
    $(document).ready(function(){
        $('nav a[href="<?=$_SERVER['REQUEST_URI']?>"]').addClass('on');
    });
</script>
    
    
<? if (!LOCAL): ?>
<script type="text/javascript">
setTimeout(function(){
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-24411901-2']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
  (function() {
    //var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    //po.src = 'https://apis.google.com/js/plusone.js';
    //var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
}, 500);
</script>
<? endif; ?>
<? if( ! empty($footer)) print $footer; //JS snippets and such ?>
</body>
</html>