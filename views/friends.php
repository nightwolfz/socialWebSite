<article class="content clearfix">

<? if (count($myFriends)<1) :?>
    <h3>Oops, seems like you haven't added anyone to your friendlist yet :(</h3>
<? endif; ?> 
    
<?
foreach($myFriends as $user){
    include 'user_block.php';
}
?>

</article>