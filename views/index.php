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
    include 'views/user_block.php';
endforeach; 
?>

<br clear="all">
<ul class="pagination-clean">
<?
foreach ($pagination as $page){
    echo "<li>$page</li>";
}
?>
</ul>