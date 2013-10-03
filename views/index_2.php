<?
# Show login or register box
if (isset($login_id)){
    if (count($events) > 0):
        foreach($events as $event) echo $event->event_content;
    else:
        echo 'Nothing new has happened lately.';
    endif;
}
?>
<hr style="border-color: #eee;">

<? 
foreach($newUsers as $user):
    include 'user_block.php';
endforeach;
?>

<a class="win-command arrowLeft" style="padding-top:0;">
    <span class="win-commandicon win-large">&#57618;</span>
</a>
<a class="win-command arrowRight">
    <span class="win-commandicon win-large">&#57617;</span>
</a>


<br clear="all">
<ul class="pagination-clean">
<?
foreach ($pagination as $page){
    echo "<li>$page</li>";
}
?>
</ul>