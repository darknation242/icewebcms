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
			showUser($gid);
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
			<h4><a href="index.php?p=admin">Main Menu</a> / Manage Users</h4>
		</div> <!-- .content-header -->				
		<div class="main-content">	
		<center><h2>User List</h2></center>
		<table>
			<tr>
				<td colspan="4" align="center">
					<b>Sort by letter:</b>&nbsp;&nbsp;
					<small>
					<a href="index.php?p=admin&sub=users">All</a> | 
					<a href="index.php?p=admin&sub=users&char=1">#</a> 
					<a href="index.php?p=admin&sub=users&char=a">A</a> 
					<a href="index.php?p=admin&sub=users&char=b">B</a> 
					<a href="index.php?p=admin&sub=users&char=c">C</a> 
					<a href="index.php?p=admin&sub=users&char=d">D</a> 
					<a href="index.php?p=admin&sub=users&char=e">E</a> 
					<a href="index.php?p=admin&sub=users&char=f">F</a> 
					<a href="index.php?p=admin&sub=users&char=g">G</a> 
					<a href="index.php?p=admin&sub=users&char=h">H</a> 
					<a href="index.php?p=admin&sub=users&char=i">I</a> 
					<a href="index.php?p=admin&sub=users&char=j">J</a> 
					<a href="index.php?p=admin&sub=users&char=k">K</a> 
					<a href="index.php?p=admin&sub=users&char=l">L</a> 
					<a href="index.php?p=admin&sub=users&char=m">M</a> 
					<a href="index.php?p=admin&sub=users&char=n">N</a> 
					<a href="index.php?p=admin&sub=users&char=o">O</a> 
					<a href="index.php?p=admin&sub=users&char=p">P</a> 
					<a href="index.php?p=admin&sub=users&char=q">Q</a> 
					<a href="index.php?p=admin&sub=users&char=r">R</a> 
					<a href="index.php?p=admin&sub=users&char=s">S</a> 
					<a href="index.php?p=admin&sub=users&char=t">T</a> 
					<a href="index.php?p=admin&sub=users&char=u">U</a> 
					<a href="index.php?p=admin&sub=users&char=v">V</a> 
					<a href="index.php?p=admin&sub=users&char=w">W</a> 
					<a href="index.php?p=admin&sub=users&char=x">X</a> 
					<a href="index.php?p=admin&sub=users&char=y">Y</a> 
					<a href="index.php?p=admin&sub=users&char=z">Z</a>              
					</small>           
				</td>
			</tr>
		</table>
		<form method="POST" action="index.php?p=admin&sub=users" name="adminform" class="form label-inline">
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
					<td align="center"><a href="index.php?p=admin&sub=users&id=<?php echo $row['id']; ?>"><?php echo $row['username']; ?></a></td>
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
					// Display Page Links (Not written by me! :p )
					if($page != 1) 
					{ 
						$pageprev = $page-1;
						echo("<a href=\"index.php?p=admin&sub=users&page=".$pageprev."\">&laquo; Previous</a>&nbsp;&nbsp;");  
					}
					else
					{
						echo "<span class='disabled'>&laquo; Previous</span>&nbsp;&nbsp;";
					}
					$numofpages = $totalrows / $limit; 
					for($j = 1; $j <= $numofpages; $j++)
					{
						if($j == $page)
						{
							echo "<a  class='current'  href=\"index.php?p=admin&sub=users&page=".$j."\">".$j."</a>&nbsp;&nbsp;";
						}
						else
						{
							echo "<a href=\"index.php?p=admin&sub=users&page=".$j."\">$j</a>&nbsp;&nbsp;"; 
						}
					}
					if(($totalrows % $limit) != 0)
					{
						if($j == $page)
						{
							echo "<a  class='current'  href=\"index.php?p=admin&sub=users&page=".$j."\">".$j."</a>&nbsp;&nbsp;";
						}
						else
						{
							echo "<a href=\"index.php?p=admin&sub=users&page=".$j."\">$j</a>&nbsp;&nbsp;";
						}
					}	
					if(($totalrows - ($limit * $page)) > 0)
					{
						$pagenext   = $page + 1;
						echo "<a href=\"index.php?p=admin&sub=users&page=".$pagenext."\">Next &raquo;</a>&nbsp;&nbsp;";
					}
					else
					{
						echo "<span class='disabled'>Next &raquo;</span>"; 
					} 
				}
			?>
			</div>
		</form>
		</div>
	</div>
<?php } ?>