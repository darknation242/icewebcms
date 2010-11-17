<?php
if(isset($_GET['linkid']))
{
?>
	<!-- EDITING LINK -->
	<div class="content">	
		<div class="content-header">
			<h4><a href="?p=admin">Main Menu</a> / <a href="?p=admin&sub=fplinks">Frontpage Links</a> / Edit</h4>
		</div> <!-- .content-header -->				
		<div class="main-content">
			<?php
				if(isset($_POST['action']))
				{
					if($_POST['action'] == 'edit')
					{
						if(isset($_POST['delete']))
						{
							deleteLink();
						}
						else
						{
							editLink();
						}
					}
				}
			?>
			<form method="POST" action="?p=admin&sub=fplinks&linkid=<?php echo $_GET['linkid']; ?>" class="form label-inline">
			<input type="hidden" name="action" value="edit">
			<?php
				$edit_info = $DB->selectRow("SELECT * FROM `mw_menu_items` WHERE `id`='".$_GET['linkid']."'");
			?>
			<div class="field">
				<label for="Link Title">Link Title: </label>
				<input id="Link Title" name="link_title" size="20" type="text" class="medium" value="<?php echo $edit_info['link_title']; ?>" />
				<p class="field_help">The name of the link displayed on the website.</p>
			</div>
			
			<div class="field">
				<label for="Link H">Link To: </label>
				<input id="Link H" name="link" size="20" type="text" class="medium" value="<?php echo $edit_info['link']; ?>" />
				<p class="field_help">The Http:// address of where the link points too. If withing MangosWeb, <br />link can be put like this 
										"?p= { PAGE } & sub= { SUB-PAGE }" etc etc</p>
			</div>
			
			<div class="field">
				<label for="Link M">Menu: </label>
				<select id="type" class="medium" name="menu_id">
					<?php 
						foreach($mainnav_links as $pre_nav)
						{
							$sub_links = explode("-", $pre_nav);
							if($edit_info['menu_id'] == $sub_links['0'])
							{ $e_rs = 'selected="selected"'; }else{ $e_rs = ''; }
							echo "<option value=".$sub_links['0']." ".$e_rs.">".$sub_links['1']."</option>";
						}
					?>
				</select>
				<p class="field_help">Displays the link under this selected menu.</p>
			</div>
			
			<div class="field">
				<label for="Link GO">Guest Only: </label>
				<select id="type" class="xsmall" name="guest_only">
					<?php 
						if($edit_info['guest_only'] == 1)
						{ $e_s = 'selected="selected"'; $e_s2 = ''; }else{ $e_s2 = 'selected="selected"'; $e_s = ''; }
					?>
					<option value="1" <?php echo $e_s; ?>>Yes</option>
					<option value="0" <?php echo $e_s2; ?>>No</option>
				</select>
				<p class="field_help">Is the link seen only by Guests?</p>
			</div>
			
			<div class="field">
				<label for="Link GO">Account Level: </label>
				<select id="type" class="medium" name="account_level">
					<option value="1" selected="selected">Guests</option>
					<option value="2">Members</option>
					<option value="3">Admins</option>
					<option value="4">Super Admins</option>
				</select>
				<p class="field_help">Minimum Account level to see the link.</p>
			</div>
			
			<div class="buttonrow-border">								
				<center>
					<button><span>Update Link</span></button>
					<button class="btn-sec" name="delete"><span>DELETE Link</span></button>
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
			<h4><a href="?p=admin">Main Menu</a> / <a href="?p=admin&sub=fplinks">Frontpage Links</a> / ADD</h4>
		</div> <!-- .content-header -->				
		<div class="main-content">
			<form method="POST" action="?p=admin&sub=fplinks" class="form label-inline">
			<input type="hidden" name="action" value="addlink">
			<div class="field">
				<label for="Link Title">Link Title: </label>
				<input id="Link Title" name="link_title" size="20" type="text" class="medium" />
				<p class="field_help">The name of the link displayed on the website.</p>
			</div>
			
			<div class="field">
				<label for="Link H">Link To: </label>
				<input id="Link H" name="link" size="20" type="text" class="medium" />
				<p class="field_help">The Http:// address of where the link points too. If withing MangosWeb, <br />link can be put like this 
										"?p= { PAGE } & sub= { SUB-PAGE }" etc etc</p>
			</div>
			
			<div class="field">
				<label for="Link M">Menu: </label>
				<select id="type" class="medium" name="menu_id">
					<?php 
						foreach($mainnav_links as $pre_nav2)
						{
							$sub_links2 = explode("-", $pre_nav2);
							echo "<option value=".$sub_links2['0'].">".$sub_links2['1']."</option>";
						}
					?>
				</select>
				<p class="field_help">Displays the link under this selected menu.</p>
			</div>
			
			<div class="field">
				<label for="Link GO">Guest Only: </label>
				<select id="type" class="xsmall" name="guest_only">
					<option value="1">Yes</option>
					<option value="0" selected="selected">No</option>
				</select>
				<p class="field_help">Is the link seen only by Guests?</p>
			</div>
			
			<div class="field">
				<label for="Link GO">Account Level: </label>
				<select id="type" class="medium" name="account_level">
					<option value="1" selected="selected">Guests</option>
					<option value="2">Members</option>
					<option value="3">Admins</option>
					<option value="4">Super Admins</option>
				</select>
				<p class="field_help">Minimum Account level to see the link.</p>
			</div>
			
			<div class="buttonrow-border">								
				<center><button><span>Add Link</span></button></center>			
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
			<h4><a href="?p=admin">Main Menu</a> / Frontpage Links</h4>
		</div> <!-- .content-header -->				
		<div class="main-content">
			<?php
				if(isset($_POST['action']))
				{
					if($_POST['action'] == 'update')
					{
						updateOrder();
					}
					elseif($_POST['action'] == 'addlink')
					{
						addLink();
					}
				}
			?>
			<h4><center><b><u><a href="?p=admin&sub=fplinks&addlink=true"> Add Link </a></u></b></center></h4>
			<form method="POST" action="?p=admin&sub=fplinks" class="form label-inline">
			<input type="hidden" name="action" value="update">
			<?php
				foreach($mainnav_links as $menuname)
				{
					$menunamev = explode('-',$menuname);
					$load_links = $DB->select("SELECT * FROM `mw_menu_items` WHERE `menu_id`='$menunamev[0]' ORDER BY `order`");
					if($load_links != FALSE)
					{
						echo "<h5><center>Menu ".$menunamev['0'].", ".$menunamev['1']."</center></h5><br />";
						echo "<table style='border-bottom: 1px solid #E5E2E2;'>
								<thead>
									<th>Link Title</th>
									<th>Min. Account Level</th>
									<th>Guest Only</th>
									<th>Order</th>
								</thead>";
						foreach($load_links as $link)
						{
							echo "<tr>
									<div class='field'>
									<td>
										<a href='?p=admin&sub=fplinks&linkid=".$link['id']."'>".$link['link_title']."</a>
									</td>
									<td>";
										if($link['account_level'] == 1)
										{
											echo "Guests";
										}
										elseif($link['account_level'] == 2)
										{
											echo "Members";
										}
										elseif($link['account_level'] == 3)
										{
											echo "Admins";
										}
										elseif($link['account_level'] == 4)
										{
											echo "Super Admins";
										}
							echo"   </td>
									<td>";
										if($link['guest_only'] == 1)
										{
											echo "Yes";
										}
										else
										{
											echo "No";
										}
							echo"	</td>
									<td>
										Order: <input name=".$link['id']." type='text' size='2' type='text' class='xsmall' value=".$link['order']."><br />
									</td>
									</div>
								</tr>";
								
							
						}
						echo "</table><br /><br />";
					}
				}
			?>
			<br />
			<div class="buttonrow-border">								
				<center><button><span>Update Menu Order</span></button></center>			
			</div>
			</form>
		</div>
	</div>
<?php
} ?>