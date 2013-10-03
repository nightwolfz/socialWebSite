<?
use \models\Input as Input;
?>
<article class="content clearfix">
	<h3>Search profiles</h3>

	<form method="post" action="" class="gform">
    	Find <select name="sex">
        	<option value="0" <?=(Input::post('sex') == '0' ? 'selected' : '')?>>Men</option>
            <option value="1" <?=(Input::post('sex') == '1' ? 'selected' : '')?>>Women</option>
        </select>
        that are between
        <select name="range">
            <?
            foreach($ranges as $range){
                echo "<option value=\"{$range}\" ".(Input::post('range') == $range ? 'selected' : '').">{$range} years</option>";
            }
            ?>
        </select> old.
        <hr>
        <input type="checkbox" id="with_picture" name="with_picture" <?=(Input::post('with_picture')=='on' ? 'checked':'')?>> 
        <label for="with_picture">Only show profiles with pictures.</label>
        <input type="checkbox" id="with_webcam" name="with_webcam"  <?=(Input::post('with_webcam')=='on' ? 'checked':'')?>> 
        <label for="with_webcam">Only show profiles with webcams.</label>
        <input type="checkbox" id="is_online" name="is_online"    <?=(Input::post('is_online')=='on' ? 'checked':'')?>> 
        <label for="is_online">Only show profiles that were recently online.</label>
        <hr>
		<button name="search_now" class="uibutton" value="1">Find now</button>
    </form>
</article>

<br clear="all">

<article class="content clearfix">

<?
if (isset($newUsers)){
    foreach($newUsers as $user){
        include 'user_block.php';
    }
} else { ?>
	<div class="message">Use the search function above.</div>
<? } ?>

</article>