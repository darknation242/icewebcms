<?php 
if(isset($_GET['id']))
{
?>
	<div class="content">	
		<div class="content-header">
			<h4><a href="?p=admin">Main Menu</a> / <a href="?p=admin&sub=chartools">Character tools</a> / <?php echo $Character->getName($_GET['id']); ?></h4>
		</div> <!-- .content-header -->				
		<div class="main-content">
		<?php
			if($_GET['id'] > 0) 
			{
				if(isset($_GET['action'])) 	
				{
					if($_GET['action'] == 'delete') 
					{
						deleteCharacter($_GET['id']);
					}
					else
					{
						echo "Invalid Action";
					}
				}
				elseif(isset($_POST['action']))
				{
					if($_POST['action'] == 'change')
					{
						updateChar();
					}
					elseif($_POST['action'] == 'rename') 
					{
						flagRename();
					}
					elseif($_POST['action'] == 'customize') 
					{
						flagCustomize();
					}
					elseif($_POST['action'] == 'talents') 
					{
						flagTalentReset();
					}
					elseif($_POST['action'] == 'reset') 
					{
						resetFlags();
					}
				}
				else
				{
					$character = $CDB->selectRow("SELECT * FROM `characters` WHERE `guid`='".$_GET['id']."'");
		?>


		
					<table style="border-bottom: 1px solid #E5E2E2; width: 500px; margin-left: auto; margin-right: auto;">
						<thead>
							<th colspan="4"><center><b>General Info</center></b></th>
						</thead>
						<tbody>
							<tr>
								<td width="25%" align="right">Class: </td>
								<td width="25%" align="left"><?php echo $Character->charInfo['class'][$character['class']]; ?></td>
								<td width="25%" align="right">Account: </td>
								<td width="25%" align="left"><?php echo $Account->getLogin($character['account']); ?></td>
							</tr>
							<tr>
								<td width="25%" align="right">Race: </td>
								<td width="25%" align="left"><?php echo $Character->charInfo['race'][$character['race']]; ?></td>
								<td width="25%" align="right">Gold: </td>
								<td width="25%" align="left"><?php echo print_gold($character['money']); ?></td>
							</tr>
							<tr>
								<td width="25%" align="right">Level: </td>
								<td width="25%" align="left"><?php echo $character['level']; ?></td>
								<td width="25%" align="right">Status: </td>
								<td width="25%" align="left">
								<?php 
									if($character['online'] == 1)
									{
										echo "<font color='green'>Online</font>";
									}
									else
									{
										echo "<font color='red'>Offline</font>";
									}
								?>
								</td>
							</tr>						
						</tbody>
					</table>
					<table>
						<tr>
							<td align="center" style="padding: 5px 5px 5px 5px;">
							<a href="?p=admin&sub=chartools&id=<?php echo $_GET['id']; ?>&action=delete" onclick="return confirm('Are you sure? This is Un-reversable!');">
								<b><font color="red">Delete Character</font></b></a> ||
								<a href="?p=admin&sub=users&id=<?php echo $character['account']; ?>&action=ban"><b><font color="red">Ban Characters Account</font></b></a>
							</td>
						</tr>
					</table>
					<br />
					<br />
					<table>
						<thead>
							<th><center><b>Character Tools</center></b></th>
						</thead>
					</table>
					<form method="POST" action="?p=admin&sub=chartools&id=<?php echo $_GET['id']; ?>" class="form label-inline">
						<input type="hidden" name="action" value="change">
						
						<div class="field">
							<label for="name">Level: </label>
							<input id="name" name="level" size="2" type="text" class="xsmall" value="<?php echo $character['level']; ?>"/>
							<p class="field_help">Enter character level here</p>
						</div>
						
						<div class="field">
							<label for="name">Experiance: </label>
							<input id="name" name="xp" size="2" type="text" class="medium" value="<?php echo $character['xp']; ?>"/>
							<p class="field_help">Current character experiance points.</p>
						</div>
						
						<div class="field">
							<label for="name">Gold: </label>
							<input id="name" name="money" size="2" type="text" class="medium" value="<?php echo $character['money']; ?>"/>
							<p class="field_help">How much gold the character has in copper. Ex: 10000 = 1g</p>
						</div>
						
						<div class="buttonrow-border">								
							<center><button><span>Update</span></button></center>			
						</div>
					</form>
					<br />
					<table>
						<form method="POST" action="?p=admin&sub=chartools&id=<?php echo $_GET['id']; ?>" class="form label-inline">
							<input type="hidden" name="action" value="flag">
							<thead>
								<th colspan='2'><center><b>Character At Login Options</center></b></th>
							</thead>
							<tbody>
								<tr>
									<td width='30%' align='center'><button name='action' value='rename'><span>Change Name</span></button></td>
									<td>At Login, User is forced to change characters name.</td>
								</tr>
								<tr>
									<td width='30%' align='center'><button name='action' value='customize'><span>Re-Customize</span></button></td>
									<td>At Login, User is able to Re-Customize Character.</td>
								</tr>
								<tr>
									<td width='30%' align='center'><button name='action' value='talents'><span>Reset Talents</span></button></td>
									<td>Reset the characters talents</td>
								</tr>
								<tr>
									<td width='30%' align='center'><button  name='action' value='reset' class='btn-sec'><span>Reset All Flags</span></button></td>
									<td>Reset all flags (Character rename, Customize, Talent point reset)</td>
								</tr>
							</tbody>
						</form>
					</table>
			<?php
				}
			}
			else
			{
				echo "Invalid Character ID";
			}
			?>
		</div>
	</div>
<?php
}
else
{ ?>
	<div class="content">	
		<div class="content-header">
			<h4><a href="?p=admin">Main Menu</a> / Character tools</h4>
		</div> <!-- .content-header -->				
		<div class="main-content">
			<center><h2>Character List</h2></center>
		<table>
			<tr>
				<td colspan="4" align="center">
					<b>Sort by letter:</b>&nbsp;&nbsp;
					<small>
					<a href="?p=admin&sub=chartools">All</a> | 
					<a href="?p=admin&sub=chartools&sort=1">#</a> 
					<a href="?p=admin&sub=chartools&sort=a">A</a> 
					<a href="?p=admin&sub=chartools&sort=b">B</a> 
					<a href="?p=admin&sub=chartools&sort=c">C</a> 
					<a href="?p=admin&sub=chartools&sort=d">D</a> 
					<a href="?p=admin&sub=chartools&sort=e">E</a> 
					<a href="?p=admin&sub=chartools&sort=f">F</a> 
					<a href="?p=admin&sub=chartools&sort=g">G</a> 
					<a href="?p=admin&sub=chartools&sort=h">H</a> 
					<a href="?p=admin&sub=chartools&sort=i">I</a> 
					<a href="?p=admin&sub=chartools&sort=j">J</a> 
					<a href="?p=admin&sub=chartools&sort=k">K</a> 
					<a href="?p=admin&sub=chartools&sort=l">L</a> 
					<a href="?p=admin&sub=chartools&sort=m">M</a> 
					<a href="?p=admin&sub=chartools&sort=n">N</a> 
					<a href="?p=admin&sub=chartools&sort=o">O</a> 
					<a href="?p=admin&sub=chartools&sort=p">P</a> 
					<a href="?p=admin&sub=chartools&sort=q">Q</a> 
					<a href="?p=admin&sub=chartools&sort=r">R</a> 
					<a href="?p=admin&sub=chartools&sort=s">S</a> 
					<a href="?p=admin&sub=chartools&sort=t">T</a> 
					<a href="?p=admin&sub=chartools&sort=u">U</a> 
					<a href="?p=admin&sub=chartools&sort=v">V</a> 
					<a href="?p=admin&sub=chartools&sort=w">W</a> 
					<a href="?p=admin&sub=chartools&sort=x">X</a> 
					<a href="?p=admin&sub=chartools&sort=y">Y</a> 
					<a href="?p=admin&sub=chartools&sort=z">Z</a>              
					</small>           
				</td>
			</tr>
		</table>
		<form method="POST" action="?p=admin&sub=chartools" name="adminform" class="form label-inline">
			<table width="95%">
				<thead>
					<tr>
						<th width="30%"><b><center>Name</center></b></th>
						<th width="10%"><b><center>Level</center></b></th>
						<th width="20%"><b><center>Race</center></b></th>
						<th width="20%"><b><center>Class</center></b></th>
						<th width="20%"><b><center>Location</center></b></th>
					</tr>
				</thead>
			<?php
				foreach($characters as $row) 
				{ 
			?>
					<tr class="content">
						<td align="center"><a href="?p=admin&sub=chartools&id=<?php echo $row['guid']; ?>"><?php echo $row['name']; ?></a></td>
						<td align="center"><?php echo $row['level']; ?></td>
						<td align="center"><?php echo $Character->charInfo['race'][$row['race']]; ?></td>
						<td align="center"><?php echo $Character->charInfo['class'][$row['class']]; ?></td>
						<td align="center"><?php echo $Zone->getZoneName($row['zone']); ?></td>
					</tr>
			<?php 
				}
			?>
			</table>
			<div id="pg">
			<?php
				// If there is going to be more then 1 page, then show page nav at the bottom
				if($totalrows > $limit)
				{
					if(isset($_GET['sort']))
					{
						admin_paginate($totalrows, $limit, $page, '?p=admin&sub=chartools&sort='.$_GET['sort']);
					}
					else
					{
						admin_paginate($totalrows, $limit, $page, '?p=admin&sub=chartools');
					}
				}
			?>
			</div>
		</form>
		</div>
	</div>
<?php } ?>