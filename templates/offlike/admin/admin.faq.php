<br>
<?php builddiv_start(1, "FAQ") ?>
<?php
foreach($alltopics as $topic) {
$topic_choose .= "<option value='".$topic['id']."'>".$topic['question']."</option>";
}
// We we start with our form posts
if(isset($_POST['add'])){
	if(isset($_POST['add_question'])) {
		if(isset($_POST['add_answer'])) {
			$ques = $_POST['add_question'];
			$answ = $_POST['add_answer'];
			$DB->query("INSERT INTO `site_faq` (`question`,`answer`) VALUES ('". $ques ."','". $answ ."')");
			echo "<font color=\"blue\"><center>Success. Added FAQ</center></font>";
		}else{ echo "<font color=\"darkred\"><center>You Did Not Provide An Answer</center></font>"; }
	}else{ echo "<font color=\"darkred\"><center>You Did Not Provide A Question</center></font>"; }
}elseif(isset($_POST['delete'])){
	$gid = $_POST['delete'];
	$DB->query("DELETE FROM `site_faq` WHERE `id`=?d",$gid);
	echo "<font color=\"blue\"><center>Deleted FAQ #" . $gid . ".</center></font>";
}elseif(isset($_POST['finishedit'])){
	if(isset($_POST['edit_question'])) {
		if(isset($_POST['edit_answer'])) {
			$ques = $_POST['edit_question'];
			$answ = $_POST['edit_answer'];
			$gid = $_POST['edit_id'];
			$DB->query("UPDATE `site_faq` SET `question`='". $ques ."', `answer`='". $answ ."' WHERE `id`=?d",$gid);
			echo "<font color=\"blue\"><center>Successfully Edited FAQ #" . $gid . ".</center></font>";
		}
	}
}
?>
<center>

<!-- If user posted to edit -->
<?php
if(isset($_POST['edit'])){
$numb = $_POST['edit'];
$q = $DB->select("SELECT `question`,`answer` FROM `site_faq` WHERE `id`=?d",$numb);
foreach($q as $qs) {
}
?>
<form action="index.php?n=admin&sub=faq" method="POST">
<table width="600px">
	<tr><center>Editting FAQ # <?php echo $numb ?></center></tr>
	<tr><center><b>Question: </b><input type="text" name="edit_question" size="50" value="<?php echo $qs['question'] ?>"></center></tr>
	<tr><center><b>Answer: </b><input type="text" name="edit_answer" size="50" value="<?php echo $qs['answer'] ?>"></center></tr>
	<tr><center><input type="hidden" name="edit_id" value="<?php echo $numb ?>"><input type="submit" value="Finish" name="finishedit"></center></tr>
</table>
</form>
</center>

<?php }else{ ?>

<!-- Add -->
<form action="index.php?n=admin&sub=faq" method="POST">
<table width="600px">
	<tr><center>Add A FAQ</center></tr>
	<tr><center><b>Question: </b><input type="text" name="add_question" size="50"></center></tr>
	<tr><center><b>Answer: </b><input type="text" name="add_answer" size="50"></center></tr>
	<tr><center><input type="submit" value="Add Faq" name="add"></center></tr>
</table>
</form>
</center>
<br /><br />

<!-- Edit -->
<center>
<form action="index.php?n=admin&sub=faq" method="POST">
<table width="600px" >
	<tr><center>Select A FAQ To Edit</center></tr>
	<tr><center><select name='edit'><?php echo $topic_choose ?></select></center></tr>
	<tr><center><input type="submit" value="EDIT"></center></tr>
</table>
</form>
</center>
<br /><br />

<!-- Delete -->
<center>
<form action="index.php?n=admin&sub=faq" method="POST">
<table width="600px">
	<tr><center>Delete A FAQ</center></tr>
	<tr><center><select name='delete'><?php echo $topic_choose ?></select></center></tr>
	<tr><center><input type="submit" value="Delete"></center></tr>
</table>
</form>
</center>
<?php 
}
builddiv_end() ?>