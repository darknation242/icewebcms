<?php 
if(isset($_GET['id']))
{
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
		else
		{

?>


		<div class="content">	
			<div class="content-header">
				<h4><a href="?p=admin">Main Menu</a> / <a href="?p=admin&sub=chartools">Character tools</a> / <?php echo $Character->getName($_GET['id']); ?></h4>
			</div> <!-- .content-header -->				
			<div class="main-content">
				<br /><font color="red"><center><b>Under Construction</b><br />Remember, this is just a preview, and not everything is finished yet!</center></font>
			</div>
		</div>
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
					<a href="?p=admin&sub=chartools&char=1">#</a> 
					<a href="?p=admin&sub=chartools&char=a">A</a> 
					<a href="?p=admin&sub=chartools&char=b">B</a> 
					<a href="?p=admin&sub=chartools&char=c">C</a> 
					<a href="?p=admin&sub=chartools&char=d">D</a> 
					<a href="?p=admin&sub=chartools&char=e">E</a> 
					<a href="?p=admin&sub=chartools&char=f">F</a> 
					<a href="?p=admin&sub=chartools&char=g">G</a> 
					<a href="?p=admin&sub=chartools&char=h">H</a> 
					<a href="?p=admin&sub=chartools&char=i">I</a> 
					<a href="?p=admin&sub=chartools&char=j">J</a> 
					<a href="?p=admin&sub=chartools&char=k">K</a> 
					<a href="?p=admin&sub=chartools&char=l">L</a> 
					<a href="?p=admin&sub=chartools&char=m">M</a> 
					<a href="?p=admin&sub=chartools&char=n">N</a> 
					<a href="?p=admin&sub=chartools&char=o">O</a> 
					<a href="?p=admin&sub=chartools&char=p">P</a> 
					<a href="?p=admin&sub=chartools&char=q">Q</a> 
					<a href="?p=admin&sub=chartools&char=r">R</a> 
					<a href="?p=admin&sub=chartools&char=s">S</a> 
					<a href="?p=admin&sub=chartools&char=t">T</a> 
					<a href="?p=admin&sub=chartools&char=u">U</a> 
					<a href="?p=admin&sub=chartools&char=v">V</a> 
					<a href="?p=admin&sub=chartools&char=w">W</a> 
					<a href="?p=admin&sub=chartools&char=x">X</a> 
					<a href="?p=admin&sub=chartools&char=y">Y</a> 
					<a href="?p=admin&sub=chartools&char=z">Z</a>              
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
				foreach($characters as $row) { 
				?>
				<tr class="content">
					<td align="center"><a href="?p=admin&sub=chartools&id=<?php echo $row['guid']; ?>"><?php echo $row['name']; ?></a></td>
					<td align="center"><?php echo $row['level']; ?></td>
					<td align="center"><?php echo $Character->charInfo['race'][$row['race']]; ?></td>
					<td align="center"><?php echo $Character->charInfo['class'][$row['class']]; ?></td>
					<td align="center"><?php echo $Zone->getZoneName($row['zone']); ?></td>
				</tr><?php } ?>
			</table>
			<div id="pg">
			<?php
				// If there is going to be more then 1 page, then show page nav at the bottom
				if($totalrows > $limit)
				{
					admin_paginate($totalrows, $limit, $page, '?p=admin&sub=chartools');
				}
			?>
			</div>
		</form>
		</div>
	</div>
<?php } ?>