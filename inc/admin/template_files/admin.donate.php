<?php
if(isset($_GET['id']))
{
?>
	<!-- EDITING LINK -->
	<div class="content">	
		<div class="content-header">
			<h4><a href="index.php?p=admin">Main Menu</a> / <a href="index.php?p=admin&sub=donate">Donate Admin</a> / Edit</h4>
		</div> <!-- .content-header -->				
		<div class="main-content">
			<?php
				if(isset($_POST['action']))
				{
					if($_POST['action'] == 'edit')
					{
						if(isset($_POST['delete']))
						{
							deletePkg();
						}
						else
						{
							editPkg();
						}
					}
				}
			?>
			<form method="POST" action="index.php?p=admin&sub=donate&id=<?php echo $_GET['id']; ?>" class="form label-inline">
			<input type="hidden" name="action" value="edit">
			<?php
				$edit_info = $DB->selectRow("SELECT * FROM `mw_donate_packages` WHERE `id`='".$_GET['id']."'");
			?>
			
			<div class="field">
				<label for="Link Title">Desc: </label>
				<input id="Link Title" name="desc" size="20" type="text" class="large" value="<?php echo $edit_info['desc']; ?>" />
				<p class="field_help">Description of your donation package</p>
			</div>
			
			<div class="field">
				<label for="Link H">Cost: $</label>
				<input id="Link H" name="cost" size="20" type="text" class="xsmall" value="<?php echo $edit_info['cost']; ?>" />
				<p class="field_help">The cost of the donation package</p>
			</div>
			
			<div class="field">
				<label for="Link H">Point Reward: </label>
				<input id="Link H" name="points" size="20" type="text" class="xsmall" value="<?php echo $edit_info['points']; ?>" />
				<p class="field_help">The amount of webpoints the user get for donating with this package.</p>
			</div>
			
			<div class="buttonrow-border">								
				<center>
					<button><span>Update Package</span></button>
					<button class="btn-sec" name="delete"><span>DELETE Package</span></button>
				</center>					
			</div>
			
			</form>
		</div>
	</div>
<?php
}
elseif(isset($_GET['add']))
{
?>

<!-- ADDING LINK -->
	<div class="content">	
		<div class="content-header">
			<h4><a href="index.php?p=admin">Main Menu</a> / <a href="index.php?p=admin&sub=donate">Donate Admin</a> / ADD</h4>
		</div> <!-- .content-header -->				
		<div class="main-content">		
			<?php
				if(isset($_POST['action']))
				{
					if($_POST['action'] == 'add')
					{
						addPkg();
					}
				}
			?>		
			<form method="POST" action="index.php?p=admin&sub=donate&add=true" class="form label-inline">
			<input type="hidden" name="action" value="add">
			
			<div class="field">
				<label for="Link Title">Desc: </label>
				<input id="Link Title" name="desc" size="20" type="text" class="large" />
				<p class="field_help">Description of your donation package</p>
			</div>
			
			<div class="field">
				<label for="Link H">Cost: $</label>
				<input id="Link H" name="cost" size="20" type="text" class="xsmall" />
				<p class="field_help">The cost of the donation package</p>
			</div>
			
			<div class="field">
				<label for="Link H">Point Reward: </label>
				<input id="Link H" name="points" size="20" type="text" class="xsmall" />
				<p class="field_help">The amount of webpoints the user get for donating with this package.</p>
			</div>
			
			<div class="buttonrow-border">								
				<center><button><span>Add Package</span></button></center>			
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
			<h4><a href="index.php?p=admin">Main Menu</a> / Donate Admin</h4>
		</div> <!-- .content-header -->				
		<div class="main-content">
			<form method="POST" action="index.php?p=admin&sub=donate&add=true" class="form label-inline">
				<h5><center>List of Donation Packages</center></h5><br />
				<table>
					<thead>
						<th><center><b>ID</center></b></th>
						<th><center><b>Description</center></b></th>
						<th><center><b>Cost</center></b></th>
						<th><center><b>Reward</center></b></th>
						<th><center><b>Action</center></b></th>
					</thead>
				<?php
					if($get_pack != FALSE)
					{
						foreach($get_pack as $pack)
						{
							echo "
								<tr>
									<td width='10%' align='center'>".$pack['id']."</td>
									<td width='45%' align='center'>".$pack['desc']."</td>
									<td width='15%' align='center'>".$pack['cost']."</td>
									<td width='15%' align='center'>".$pack['points']."</td>
									<td width='15%' align='center'><a href='index.php?p=admin&sub=donate&id=".$pack['id']."'>Edit / Del</a></td>
								</tr>
							";
						}
					}
				?>
				</table>
			<br />
			<div class="buttonrow-border">								
				<center><button><span>Add New Donation</span></button></center>			
			</div>
			</form>
		</div>
	</div>
<?php
} ?>