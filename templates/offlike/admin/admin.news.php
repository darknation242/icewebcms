<br>
<style>
div.errorMsg { width: 60%; height: 30px; line-height: 30px; font-size: 10pt; border: 2px solid #e03131; background: #ff9090;}
</style>
<?php builddiv_start(0, "News");
// If action is adding a new News topic
if($_GET['action'] == 'add'){
 ?>
<hr class="hidden" />
<div id="write_form" class="subsections">
<form method="post" action="index.php?n=admin&sub=news&action=add" name="mutliact" class="clearfix" OnSubmit="if(!this.owner.value || !this.title.value)return false;">
        <div id="ulist_cont"></div>
        <label for="title"><?php echo $lang['post_subj'];?> (max 80):</label><br/>
        <input type="text" name="title" id="title"" size="50" maxlength="80" class="input_text input_large" /><br /><br />
        </p>
    <?php write_form_tool(); ?>
        <div id="input_block">
            <textarea id="input_comment" name="message"></textarea><br/>
            <!-- <input value="<?php echo $lang['editor_preview'];?>" type="button" id="preview_do">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			-->
            <input type="reset" value="<?php echo $lang['editor_clear'];?>">
        </div>
        <div id="preview_block" style="display: none;background:none;">
            <div class="editor" id="input_preview"></div>
            <input class="button" id="preview_back" value="<?php echo $lang['editor_backtoedit']?>" type="button">
        </div>
        <input type="submit" value="<?php echo $lang['editor_send'];?>" class="input_btn_big" />
</form>
</div>
<?php 
// Otherwise, editing
}elseif($_GET['action'] == 'edit'){ 
	if($_GET['id'] > 0) {
	$loading = $DB->select("SELECT * FROM `f_posts` WHERE `topic_id`=?d",$_GET['id']);
	foreach($loading as $content) {
	}
	$load2 = $DB->select("SELECT * FROM `f_topics` WHERE `topic_id`=?d",$_GET['id']);
	foreach($load2 as $cont) {
	}
?>
<hr class="hidden" />
<div id="write_form" class="subsections">
<form method="post" action="index.php?n=admin&sub=news&action=edit&id=<?php echo $_GET['id'] ?>" name="mutliact" class="clearfix" OnSubmit="if(!this.owner.value || !this.title.value)return false;">
        <div id="ulist_cont"></div>
        <label for="title"><?php echo $lang['post_subj'];?> (max 80):</label><br/>
        <input type="text" name="title" id="title"" size="50" maxlength="80" class="input_text input_large" value="<?php echo $cont['topic_name'] ?>" disabled="disabled"/><br /><br />
        </p>
    <?php write_form_tool(); ?>
        <div id="input_block">
            <textarea id="input_comment" name="edit_message"><?php echo $content['message'];?></textarea><br/>
            <!-- <input value="<?php echo $lang['editor_preview'];?>" type="button" id="preview_do">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			-->
            <input type="reset" value="<?php echo $lang['editor_clear'];?>">
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="submit" value="DELETE THIS NEWS TOPIC" name="delete">
			<input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">
			<br />
        </div>
        <div id="preview_block" style="display: none;background:none;">
            <div class="editor" id="input_preview"></div>
            <input class="button" id="preview_back" value="<?php echo $lang['editor_backtoedit']?>" type="button">
        </div>
        <input type="submit" value="<?php echo $lang['editor_send'];?>" class="input_btn_big" />
</form>
</div>
<?php }else{ ?>		
		<b><u>Click a news link to edit</u></b><br /><br />
		<?php
		$gettopics = $DB->select("SELECT `topic_name`,`topic_id` FROM `f_topics`");
		foreach($gettopics as $zzz) {
		echo "<a href='index.php?n=admin&sub=news&action=edit&id=" . $zzz['topic_id'] . "'>" . $zzz['topic_name'] . "</a><br />";
		}
	}
}else{ ?>
You arent suppossed to be here :p
<?php } ?>
<?php builddiv_end() ?>