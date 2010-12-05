<?php
if(isset($_GET['id']))
{
?>
	<!-- EDITING ITEM -->
	<div class="content">	
		<div class="content-header">
			<h4><a href="?p=admin">Main Menu</a> / <a href="?p=admin&sub=shop">Shop Items</a> / Edit</h4>
		</div> <!-- .content-header -->				
		<div class="main-content">
			<?php
				if(isset($_POST['action']))
				{
					if($_POST['action'] == 'edit')
					{
						if(isset($_POST['delete']))
						{
							deleteItem();
						}
						else
						{
							editItem();
						}
					}
				}
			?>
			<form method="POST" action="?p=admin&sub=shop&id=<?php echo $_GET['id']; ?>" class="form label-inline">
			<input type="hidden" name="action" value="edit">
			<?php
				$edit_info = $DB->selectRow("SELECT * FROM `mw_shop_items` WHERE `id`='".$_GET['id']."'");
			?>
			
			<div class="field">
				<label for="Item Number">Item Number: </label>
				<input id="Item Number" name="item_number" size="20" type="text" class="medium" value="<?php echo $edit_info['item_number']; ?>" />
				<p class="field_help">Item number for reward. Seperate items with a "," comma. 0 For no item.</p>
			</div>
			
			<div class="field">
				<label for="Itemset">Itemset: </label>
				<input id="Itemset" name="itemset" size="20" type="text" class="medium" value="<?php echo $edit_info['itemset']; ?>" />
				<p class="field_help">Itemset Number for reward. Limit 1. (0 = No Itemset)</p>
			</div>
			
			<div class="field">
				<label for="Gold">Gold: </label>
				<input id="Gold" name="gold" size="20" type="text" class="medium" value="<?php echo $edit_info['gold']; ?>" />
				<p class="field_help">Enter amount of gold to be sent in copper.(ie: 10000 = 1g)</p>
			</div>
			
			<div class="field">
				<label for="Item desc">Description: </label>
				<input id="Item desc" name="desc" size="20" type="text" class="large" value="<?php echo $edit_info['desc']; ?>" />
				<p class="field_help">Description shown to users while viewing the shop reward.</p>
			</div>
			
			<div class="field">
				<label for="q">Quantity: </label>
				<input id="q" name="quanity" size="20" type="text" class="tiny" value="<?php echo $edit_info['quanity']; ?>" />
				<p class="field_help">Quantity (amount) of items receiving. Does not affect gold or itemsets!</p>
			</div>
			
			<div class="field">
				<label for="Cost">Web Points Cost: </label>
				<input id="Cost" name="wp_cost" size="20" type="text" class="tiny" value="<?php echo $edit_info['wp_cost']; ?>" />
				<p class="field_help">Cost to buy the item via Web Points</p>
			</div>
			
			<div class="field">
				<label for="Site Emu">Realms: </label>
				<select id="type" class="small" name="realms">
					<option value=\"0\">All Realms</option><?php echo $realmzlist; ?>
				</select>
				<p class="field_help">Which realms are able to purchase this item?</p>
			</div>
			
			<div class="buttonrow-border">								
				<center>
					<button><span>Update Shop Item</span></button>
					<button class="btn-sec" name="delete"><span>DELETE Shop Item</span></button>
				</center>					
			</div>
			
			</form>
		</div>
	</div>
<?php
}
elseif(isset($_GET['additem']))
{
?>

<!-- ADDING LINK -->
	<div class="content">	
		<div class="content-header">
			<h4><a href="?p=admin">Main Menu</a> / <a href="?p=admin&sub=shop">Shop Items</a> / ADD</h4>
		</div> <!-- .content-header -->				
		<div class="main-content">	
		<?php
				if(isset($_POST['action']))
				{
					if($_POST['action'] == 'add')
					{
						addItem();
					}
				}
			?>
			<form method="POST" action="?p=admin&sub=shop&additem=true" class="form label-inline">
			<input type="hidden" name="action" value="add">
			
			<div class="field">
				<label for="Item Number">Item Number: </label>
				<input id="Item Number" name="item_number" size="20" type="text" class="medium" />
				<p class="field_help">Item number for reward. Seperate items with a "," comma. 0 For no item.</p>
			</div>
			
			<div class="field">
				<label for="Itemset">Itemset: </label>
				<input id="Itemset" name="itemset" size="20" type="text" class="medium" />
				<p class="field_help">Itemset Number for reward. Limit 1. (0 = No Itemset)</p>
			</div>
			
			<div class="field">
				<label for="Gold">Gold: </label>
				<input id="Gold" name="gold" size="20" type="text" class="medium" />
				<p class="field_help">Enter amount of gold to be sent in copper.(ie: 10000 = 1g)</p>
			</div>
			
			<div class="field">
				<label for="Item desc">Description: </label>
				<input id="Item desc" name="desc" size="20" type="text" class="large" />
				<p class="field_help">Description shown to users while viewing the shop reward.</p>
			</div>
			
			<div class="field">
				<label for="q">Quantity: </label>
				<input id="q" name="quanity" size="20" type="text" class="tiny" />
				<p class="field_help">Quantity (amount) of items receiving. Does not affect gold or itemsets!</p>
			</div>
			
			<div class="field">
				<label for="Cost">Web Points Cost: </label>
				<input id="Cost" name="wp_cost" size="20" type="text" class="tiny" />
				<p class="field_help">Cost to buy the item via Web Points</p>
			</div>
			
			<div class="field">
				<label for="realms">Realms: </label>
				<select id="type" class="small" name="realms">
					<option value=\"0\">All Realms</option><?php echo $realmzlist; ?>
				</select>
				<p class="field_help">Which realms are able to purchase this item?</p>
			</div>
			
			<div class="buttonrow-border">								
				<center><button><span>Add Shop Item</span></button></center>			
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
			<h4><a href="?p=admin">Main Menu</a> / Shop Items</h4>
		</div> <!-- .content-header -->				
		<div class="main-content">
			<form method="POST" action="?p=admin&sub=shop&additem=true" class="form label-inline">
				<h5><center>List of Shop Items</center></h5><br />
				<table>
					<thead>
						<th><center><b>ID</center></b></th>
						<th><center><b>Reward</center></b></th>
						<th><center><b>Quantity</center></b></th>
						<th><center><b>Cost</center></b></th>
						<th><center><b>Realms</center></b></th>
						<th><center><b>Action</center></b></th>
					</thead>
				<?php
					if($getitems != FALSE)
					{
						foreach($getitems as $row)
						{
							echo "
								<tr>
									<td width='10%' align='center'>".$row['id']."</td>
									<td width='40% align='center'><center>";
									if($row['item_number'] != 0)
									{
										$item_name = $WDB->selectCell("SELECT `name` FROM `item_template` WHERE entry='".$row['item_number']."'");
										if($item_name == FALSE) 
										{ 
											echo "<font color='red'> INVALID ITEM ID!</font>"; 
										}
										else
										{ 
											echo "<a href='http://www.wowhead.com/?item=".$row['item_number']."' target='_blank'>".$item_name."</a>"; 
										}
									}
									else
									{
										echo "No Item";
									}
									if($row['itemset'] != 0) 
									{ 
										echo "<br /><a href='http://www.wowhead.com/?itemset=".$row['itemset']."' target='_blank'>ItemSet # ".$row['itemset']."</a>"; 
									}
									if($row['gold'] != 0) 
									{ 
										echo "<br />Gold: "; print_gold($row['gold']); 
									}
							echo"	</center></td>								
									<td width='10%' align='center'>".$row['quanity']."</td>
									<td width='10%' align='center'>".$row['wp_cost']."</a></td>
									<td width='15%' align='center'>
									";
									if ($row['realms'] == 0) 
									{ 
										echo "All"; 
									}
									else
									{ 
										echo $row['realms']; 
									}
							echo"
									<td width='15%' align='center'><a href='?p=admin&sub=shop&id=".$row['id']."'>Edit / Del</a></td>
									</td>
								</tr>
							";
						}
					}
				?>
				</table>
				<div id="pg">
				<?php
					// If there is going to be more then 1 page, then show page nav at the bottom
					if($totalrows > $limit)
					{
						admin_paginate($totalrows, $limit, $page, '?p=admin&sub=shop');
					}
				?>
				</div>
			<br />
			<div class="buttonrow-border">								
				<center><button><span>Add New Shop Item</span></button></center>			
			</div>
			</form>
		</div>
	</div>
<?php
} ?>