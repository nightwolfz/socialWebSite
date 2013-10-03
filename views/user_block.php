<div class="userPicture">
     <img class="left" height="150" alt="<?=$user['username']?>'s profile picture" src="<?=ROOT?>/uploads/<?=isset($user['avatar'])?$user['avatar']:'t/09/12/nightwolfz_1315840399_432.jpg'?>">
     <div class="userPopup">
         <b class="name fc<?=$user['gender']=='f'?'fe':''?>male"><a href="/user/<?=$user['username']?>"><?=$user['username']?></a></b><br>
         <b class="fs12"><?=isset($user['city'])?$user['city']:$user['location']?></b>
     </div>
</div>
