<?php
if(isset($_GET['id']))
{
?>
	<!-- EDITING LINK -->
	<div class="content">	
		<div class="content-header">
			<h4><a href="?p=admin">Main Menu</a> / <a href="?p=admin&sub=vote">Vote Links</a> / Edit</h4>
		</div> <!-- .content-header -->				
		<div class="main-content">
			<?php
				if(isset($_POST['action']))
				{
					if($_POST['action'] == 'edit')
					{
						if(isset($_POST['delete']))
						{
							deleteSite();
						}
						else
						{
							editSite();
						}
					}
				}
			?>
			<form method="POST" action="?p=admin&sub=vote&id=<?php echo $_GET['id']; ?>" class="form label-inline">
			<input type="hidden" name="action" value="edit">
			<?php
				$edit_info = $DB->selectRow("SELECT * FROM `mw_vote_sites` WHERE `id`='".$_GET['id']."'");
			?>
			<div class="field">
				<label for="Link Title">Hostname: </label>
				<input id="Link Title" name="hostname" size="20" type="text" class="medium" value="<?php echo $edit_info['hostname']; ?>" />
				<p class="field_help">Host of the votesite (ei: www.keyswow.com)</p>
			</div>
			
			<div class="field">
				<label for="Link H">Vote Link: </label>
				<input id="Link H" name="votelink" size="20" type="text" class="medium" value="<?php echo $edit_info['votelink']; ?>"  />
				<p class="field_help">The Http:// address of where users need to go to vote for you</p>
			</div>
			
			<div class="field">
				<label for="Link H">Image URL: </label>
				<input id="Link H" name="image_url" size="20" type="text" class="medium" value="<?php echo $edit_info['image_url']; ?>"  />
				<p class="field_help">The Http:// address of the votesite image.</p>
			</div>
			
			<div class="field">
				<label for="Link H">Points: </label>
				<input id="Link H" name="points" size="20" type="text" class="tiny" value="<?php echo $edit_info['points']; ?>" />
				<p class="field_help">How many points is this site worth?</p>
			</div>
			
			<div class="buttonrow-border">								
				<center>
					<button><span>Update Site</span></button>
					<button class="btn-sec" name="delete"><span>DELETE Site</span></button>
				</center>					
			</div>
			
			</form>
		</div>
	</div>
<?php
}
elseif(isset($_GET['addlink']))
{
?>

<!-- ADDING LINK -->
	<div class="content">	
		<div class="content-header">
			<h4><a href="?p=admin">Main Menu</a> / <a href="?p=admin&sub=vote">Vote Links</a> / ADD</h4>
		</div> <!-- .content-header -->				
		<div class="main-content">
			<form method="POST" action="?p=admin&sub=vote" class="form label-inline">
			<input type="hidden" name="action" value="addSite">
			<div class="field">
				<label for="Link Title">Hostname: </label>
				<input id="Link Title" name="link_host" size="20" type="text" class="medium" />
				<p class="field_help">Host of the votesite (ei: www.keyswow.com)</p>
			</div>
			
			<div class="field">
				<label for="Link H">Vote Link: </label>
				<input id="Link H" name="link" size="20" type="text" class="medium" />
				<p class="field_help">The Http:// address of where users need to go to vote for you</p>
			</div>
			
			<div class="field">
				<label for="Link H">Image URL: </label>
				<input id="Link H" name="link_image" size="20" type="text" class="medium" />
				<p class="field_help">The Http:// address of the votesite image.</p>
			</div>
			
			<div class="field">
				<label for="Link H">Points: </label>
				<input id="Link H" name="link_points" size="20" type="text" class="tiny" />
				<p class="field_help">How many points is this site worth?</p>
			</div>
			
			<div class="buttonrow-border">								
				<center><button><span>Add Votesite</span></button></center>			
			</div>		
			</form>
		</div>
	</div>

<?php
}
else
{
?>
	<div class="content">	
		<div class="content-header">
			<h4><a href="?p=admin">Main Menu</a> / Vote Links</h4>
		</div> <!-- .content-header -->				
		<div class="main-content">
			<?php
				if(isset($_POST['action']))
				{
					if($_POST['action'] == 'addSite')
					{
						addSite();
					}
				}
			?>
			<form method="POST" action="?p=admin&sub=vote&addlink=true" class="form label-inline">
			<input type="hidden" name="action" value="update">
				<h5><center>List of Vote Sites</center></h5><br />
				<table>
					<thead>
						<th><center><b>Hostname</center></b></th>
						<th><center><b>Image</center></b></th>
						<th><center><b>Votelink</center></b></th>
						<th><center><b>Points</center></b></th>
					</thead>
				<?php
					if($get_sites != FALSE)
					{
						foreach($get_sites as $site)
						{
							echo "
								<tr>
									<td width='25%' align='center'><a href='?p=admin&sub=vote&id=".$site['id']."'>".$site['hostname']."</a></td>
									<td width='35%' align='center'>".$site['image_url']."</td>
									<td width='25%' align='center'><a href='".$site['votelink']."'>".$site['votelink']."</a></td>
									<td width='15%' align='center'>".$site['points']."</td>
								</tr>
							";
						}
					}
				?>
				</table>
			<br />
			<div class="buttonrow-border">								
				<center><button><span>Add New Link</span></button></center>			
			</div>
			</form>
		</div>
	</div>
<?php
} ?>