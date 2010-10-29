<!-- Start #main -->
<div id="main">			
	<div class="content">	
	<?php
		if(isset($_GET['action'])) {
			if($_GET['action'] == 'add'){ 
				if(isset($_POST['subject'])) {
					addNews($_POST['subject'],$_POST['message'],$user['username']);
				}
	?>
		<div class="content-header">
			<h4><a href="index.php?p=admin">Main Menu</a> / <a href="index.php?p=admin&sub=news">News</a> / Add News</h4>
		</div> <!-- .content-header -->				
		<div class="main-content">
			<form method="POST" action="index.php?p=admin&sub=news&action=add" class="form label-inline">
			<input type="hidden" name="task" value="addnews">
			
				<table>
					<thead>
						<tr>
							<th><center>Add News</center></th>
						</tr>
					</thead>
				</table>
				<br />
				
				<div class="field">
					<label for="Subject">Subject: </label>
					<input id="Subject" name="subject" size="20" type="text" class="medium" />
					<p class="field_help">Enter your news item subject here.</p>
				</div>
				
				<div class="field">
					<label for="Message">Message: </label><br />
					<textarea id="Message" name="message" rows="15" cols="78" class="inputbox"></textarea>
				</div>
				<br />		
				<div class="buttonrow-border">								
					<center><button><span>Submit News</span></button></center>			
				</div>
			</form>
		</div>

<?php 
	// Otherwise, editing
	}
	elseif($_GET['action'] == 'edit')
	{ 
		if(isset($_GET['id'])) 
		{
			$content = $DB->selectRow("SELECT * FROM `mw_news` WHERE `id`='".$_GET['id']."'");
			if(isset($_POST['delete'])) 
			{
				delNews($_POST['id']);
			}
			elseif(isset($_POST['editmessage'])) 
			{
				editNews($_POST['id'],$_POST['editmessage']);
			}
?>
		<div class="content-header">
			<h4><a href="index.php?p=admin">Main Menu</a> / <a href="index.php?p=admin&sub=news">News</a> / Edit News</h4>
		</div> <!-- .content-header -->				
		<div class="main-content">
			<form method="POST" action="index.php?p=admin&sub=news&action=edit&id=<?php echo $_GET['id']; ?>" class="form label-inline">
			<input type="hidden" name="task" value="editnews">
			<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">			
				<table>
					<thead>
						<tr>
							<th><center>Edit News</center></th>
						</tr>
					</thead>
				</table>
				<br />
				
				<div class="field">
					<label for="Subject">Subject: </label>
					<input id="Subject" name="subject" size="20" type="text" class="medium" disabled="disabled" value="<?php echo $content['title']; ?>" />
					<p class="field_help">Enter your news item subject here.</p>
				</div>
				
				<div class="field">
					<label for="Message">Message: </label><br />
					<textarea id="Message" name="editmessage" rows="15" cols="78" class="inputbox"><?php echo $content['message']; ?></textarea>
				</div>
				<br />		
				<div class="buttonrow-border">								
					<center>
						<button><span>Submit News</span></button>
						<button name="delete" class="btn-sec"><span>DELETE This News Topic</span></button>
					</center>
				</div>
			</form>
		</div>
<?php 
		}
		else
		{ ?>		
			<b><u><center>No Id Specified!</center></u></b><br /><br />

	<?php	}
	}
	else
	{ ?>
You arent suppossed to be here :p
<?php 
	} 
}
else
{
?>
		<div class="content-header">
			<h4><a href="index.php?p=admin">Main Menu</a> / News</h4>
		</div> <!-- .content-header -->				
		<div class="main-content">
			<h4><a href="index.php?p=admin&sub=news&action=add" /><center><b><u>.:Add News:.</u></b></center></a></h4>
			<h2><center>News List</center></h2>
			<table>
				<tbody>
					<thead>
						<tr>
							<th width="40%"><center>News Title</center></th>
							<th width="30%"><center>Posted By</center></th>
							<th width="30%"><center>Post Time</center></th>
						</tr>
					</thead>
					<?php
					if($gettopics != FALSE)
					{
						foreach($gettopics as $row) 
						{
							$date_n = date("Y-m-d, g:i a", $row['post_time']);
					?>
					<tr>
						<td align="center"><a href="index.php?p=admin&sub=news&action=edit&id=<?php echo $row['id']; ?>"><?php echo $row['title']; ?></a></td>
						<td align="center"><?php echo $row['posted_by']; ?></td>
						<td align="center"><?php echo $date_n; ?></td>
					</tr>
					<?php } // END FOR EACH NEWS
					} // END IF ?>
				</tbody>
			</table>
		</div>
<?php }
?>
	</div>
</div>