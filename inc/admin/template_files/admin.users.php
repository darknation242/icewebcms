<?php 
if(isset($_GET['id']))
{
	if($_GET['id'] > 0) 
	{
		$gid = $_GET['id'];
		if(isset($_GET['action'])) 	
		{
			if($_GET['action'] == 'ban') 
			{
				showBanForm($gid);
			}
			elseif($_GET['action'] == 'unban') 
			{
				unBan($gid);
			}
			elseif($_GET['action'] == 'delete') 
			{
				deleteUser($gid);
			}
			else
			{
				echo "Invalid Action";
			}
		}
		else
		{
			$profile = $Account->getProfile($_GET['id']);
			$lastvisit = date("Y-m-d, g:i a", $profile['last_visit']);
			$seebanned = $DB->count("SELECT COUNT(*) FROM account_banned WHERE id='".$_GET['id']."' AND `active`=1");
			if($seebanned > 0) 
			{
				$bann = 1;
			}
			else
			{
				$bann = 0;
			}
?>
		<div class="content">	
			<div class="content-header">
				<h4><a href="?p=admin">Main Menu</a> / <a href="?p=admin&sub=users">Manage Users</a> / <?php echo $profile['username']; ?></h4>
			</div> <!-- .content-header -->				
			<div class="main-content">
			
				<?php
					if(isset($_POST['action']))
					{
						if($_POST['action'] == 'editProfile')
						{
							// EDIT
						}
						elseif($_POST['action'] == 'changePass')
						{
							changePass();
						}
						elseif($_POST['action'] == 'editWeb')
						{
							// EDIT
						}
					}
				?>
			
				<table style="border-bottom: 1px solid #E5E2E2;">
					<thead>
						<th colspan="4"><center><b>General Stats</center></b></th>
					</thead>
					<tbody>
						<tr>
							<td width="25%" align="right">Registration Date: </td>
							<td width="25%" align="left"><?php echo $profile['joindate']; ?></td>
							<td width="25%" align="right">Vote Count: </td>
							<td width="25%" align="left"><?php echo $profile['total_votes']; ?></td>
						</tr>
						<tr>
							<td width="25%" align="right">Registration IP: </td>
							<td width="25%" align="left"><?php echo $profile['registration_ip']; ?></td>
							<td width="25%" align="right"> Webpoint Balance: </td>
							<td width="25%" align="left"><?php echo $profile['web_points']; ?></td>
						</tr>
						<tr>
							<td width="25%" align="right">Last Activity (Game): </td>
							<td width="25%" align="left"><?php echo $profile['last_login']; ?></td>
							<td width="25%" align="right">Points Earned/Spent: </td>
							<td width="25%" align="left"><?php echo $profile['points_earned']." / ".$profile['points_spent']; ?></td>
						</tr>
						<tr>
							<td width="25%" align="right">Last Activity (Site): </td>
							<td width="25%" align="left"><?php echo $lastvisit; ?></td>
							<td width="25%" align="right">Total Donations: </td>
							<td width="25%" align="left">$<?php echo $profile['total_donations']; ?></td>
						</tr>
						
					</tbody>
				</table>
				<table>
					<tr>
						<td align="center" style="padding: 5px 5px 5px 5px;">
						<a href="?p=admin&sub=users&id=<?php echo $_GET['id']; ?>&action=delete" onclick="return confirm('Are you sure? This is Un-reversable!');">
							<b><font color="red">Delete Account</font></b></a> ||
						<?php
							if($bann == 1) 
							{
								echo "<a href=\"?p=admin&sub=users&id=".$_GET['id']."&action=unban\"><b><font color=\"red\">Unban</font></b></a>";
							}
							elseif($bann == 0) 
							{ 
								echo "<a href=\"?p=admin&sub=users&id=".$_GET['id']."&action=ban\"><b><font color=\"red\">Ban Account</font></b></a>";
							}
						?>
						</td>
					</tr>
				</table>
				
				<!-- EDIT PROFILE -->
				<br />
				<table>
					<thead>
						<th><center><b>Edit Profile</center></b></th>
					</thead>
				</table>
				<form method="POST" action="?p=admin&sub=users&id=<?php echo $_GET['id']; ?>" class="form label-inline">
					<input type="hidden" name="action" value="editProfile">
					
					<div class="field">
						<label for="Username">Username: </label>
						<input id="Username" name="username" size="20" type="text" class="medium" disabled="disbled" value="<?php echo $profile['username']; ?>"/>
					</div>
					
					<div class="field">
						<label for="Email">Email: </label>
						<input id="Email" name="email" size="20" type="text" class="medium" value="<?php echo $profile['email']; ?>"/>
					</div>
					
					<div class="field">
						<label for="Locked">Locked: </label>
						<select name="locked" class='xsmall'>
							<?php
								if($profile['locked'] == 1)
								{
									echo "<option value='1' selected='selected'>Yes</option><option value='0'>No</option>";
								}
								else
								{
									echo "<option value='1'>Yes</option><option value='0' selected='selected'>No</option>";
								}
							?>
						</select>
					</div>
					
					<div class="field">
						<label for="Exp">Expansion: </label>
						<select name="expansion" class='small'>
							<?php
								if($profile['expansion'] == 2)
								{
									echo "<option value='2' selected='selected'>WotLK</option><option value='1'>TBC</option><option value='0'>Classic</option>";
								}
								elseif($profile['expansion'] == 1)
								{
									echo "<option value='2'>WotLK</option><option value='1' selected='selected'>TBC</option><option value='0'>Classic</option>";
								}
								else
								{
									echo "<option value='2'>WotLK</option><option value='1'>TBC</option><option value='0' selected='selected'>Classic</option>";
								}
							?>
						</select>
					</div>
					
					<div class="buttonrow-border">								
						<center><button><span>Update Profile</span></button></center>			
					</div>
				</form>
				
				<!-- CHANGE PASSWORD -->
				<br />
				<br />
				<table>
					<thead>
						<th><center><b>Change Password</center></b></th>
					</thead>
				</table>
				<form method="POST" action="?p=admin&sub=users&id=<?php echo $_GET['id']; ?>" class="form label-inline">
					<input type="hidden" name="action" value="changePass">
				
					<div class="field">
						<label for="Password">New Password: </label>
						<input id="Password" name="password" size="20" type="text" class="medium" />
					</div>
					
					<div class="buttonrow-border">								
						<center><button><span>Set Password</span></button></center>			
					</div>
				</form>
				
				<!-- EDIT WEBSITE DETAILS -->
				<br />
				<br />
				<table>
					<thead>
						<th><center><b>Edit Website Account Details</center></b></th>
					</thead>
				</table>
				<form method="POST" action="?p=admin&sub=users&id=<?php echo $_GET['id']; ?>" class="form label-inline">
					<input type="hidden" name="action" value="changeWeb">
				
					<div class="field">
						<label for="Account_level">Account Level: </label>
						<select name="account_level" class='small'>
							<?php
								if($profile['account_level'] == 5)
								{
									echo "<option value='5' selected='selected'>Banned</option>";
								}
								elseif($profile['account_level'] == 4)
								{
									echo "<option value='4' selected='selected'>Super Admin</option>
										  <option value='3'>Admin</option>
										  <option value='2'>Member</option>
									"; 
								}
								elseif($profile['account_level'] == 3)
								{
									echo "<option value='4'>Super Admin</option>
										  <option value='3' selected='selected'>Admin</option>
										  <option value='2'>Member</option>
									"; 
								}
								else
								{
									echo "<option value='4'>Super Admin</option>
										  <option value='3' selected='selected'>Admin</option>
										  <option value='2' selected='selected'>Member</option>
									"; 
								}
							?>
						</select>
					</div>
					
					<div class="field">
						<label for="Exp">Theme: </label>
						<select name="theme" class='medium'>
							<?php
								$alltmpl = explode(",", $cfg->get('templates'));
								$key = 0;
								foreach($alltmpl as $tmpls) 
								{
									echo '<option value="'.$key.'"';
									if ($profile['theme'] == $key) 
									{
										echo ' selected="selected"';
									}
									echo '>'.$tmpls.'</option>';
									$key++;
								}
							?>
						</select>
					</div>
					
					<div class="buttonrow-border">								
						<center><button><span>Update</span></button></center>			
					</div>
				</form>
			
			</div> <!-- Main Content -->
		</div> <!-- Content -->

<?php
		}
	}
	else
	{
		echo "Invalid Request";
	}
}
else
{ ?>
<!-- Start #main -->
<div id="main">			
	<div class="content">	
		<div class="content-header">
			<h4><a href="?p=admin">Main Menu</a> / Manage Users</h4>
		</div> <!-- .content-header -->				
		<div class="main-content">	
		<center><h2>User List</h2></center>
		<table>
			<tr>
				<td colspan="4" align="center">
					<b>Sort by letter:</b>&nbsp;&nbsp;
					<small>
					<a href="?p=admin&sub=users">All</a> | 
					<a href="?p=admin&sub=users&char=1">#</a> 
					<a href="?p=admin&sub=users&char=a">A</a> 
					<a href="?p=admin&sub=users&char=b">B</a> 
					<a href="?p=admin&sub=users&char=c">C</a> 
					<a href="?p=admin&sub=users&char=d">D</a> 
					<a href="?p=admin&sub=users&char=e">E</a> 
					<a href="?p=admin&sub=users&char=f">F</a> 
					<a href="?p=admin&sub=users&char=g">G</a> 
					<a href="?p=admin&sub=users&char=h">H</a> 
					<a href="?p=admin&sub=users&char=i">I</a> 
					<a href="?p=admin&sub=users&char=j">J</a> 
					<a href="?p=admin&sub=users&char=k">K</a> 
					<a href="?p=admin&sub=users&char=l">L</a> 
					<a href="?p=admin&sub=users&char=m">M</a> 
					<a href="?p=admin&sub=users&char=n">N</a> 
					<a href="?p=admin&sub=users&char=o">O</a> 
					<a href="?p=admin&sub=users&char=p">P</a> 
					<a href="?p=admin&sub=users&char=q">Q</a> 
					<a href="?p=admin&sub=users&char=r">R</a> 
					<a href="?p=admin&sub=users&char=s">S</a> 
					<a href="?p=admin&sub=users&char=t">T</a> 
					<a href="?p=admin&sub=users&char=u">U</a> 
					<a href="?p=admin&sub=users&char=v">V</a> 
					<a href="?p=admin&sub=users&char=w">W</a> 
					<a href="?p=admin&sub=users&char=x">X</a> 
					<a href="?p=admin&sub=users&char=y">Y</a> 
					<a href="?p=admin&sub=users&char=z">Z</a>              
					</small>           
				</td>
			</tr>
		</table>
		<form method="POST" action="?p=admin&sub=users" name="adminform" class="form label-inline">
			<table width="95%">
				<thead>
					<tr>
						<th width="120"><b><center>UserName</center></b></th>
						<th width="140"><b><center>Email</center></b></th>
						<th width="120"><b><center>Registration Date</center></b></th>
						<th width="40"><b><center>Active/Ban</center></b></th>
					</tr>
				</thead>
				<?php
				foreach($getusers as $row) { 
				?>
				<tr class="content">
					<td align="center"><a href="?p=admin&sub=users&id=<?php echo $row['id']; ?>"><?php echo $row['username']; ?></a></td>
					<td align="center"><?php echo $row['email']; ?></td>
					<td align="center"><?php echo $row['joindate']; ?></td>
					<td align="center"><?php echo $row['locked']; ?></td>
				</tr><?php } ?>
			</table>
			<div id="pg">
			<?php
				// If there is going to be more then 1 page, then show page nav at the bottom
				if($totalrows > $limit)
				{
					admin_paginate($totalrows, $limit, $page, '?p=admin&sub=users');
				}
			?>
			</div>
		</form>
		</div>
	</div>
<?php } ?>