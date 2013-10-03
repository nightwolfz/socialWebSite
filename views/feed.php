<article id="content" class="clearfix">
    <ul class="feed">
        <? foreach($newUsers as $user) :?>
        <li class="clearfix">
            <a href="/user/<?=$user['username']?>">
                <img class="avatar" src="/uploads/t/<?=$user['avatar']?>">
            </a>
            <h3>
                <a href="/user/<?=$user['username']?>"><?=$user['username']?></a>
            </h3>
            <p>
                <?=html_entity_decode($user['like'])?>
            </p>
        </li>
        <? endforeach; ?>
    </ul>
</article>
<br/>