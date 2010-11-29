<br />
<?php
builddiv_start(1, 'Shop Checkout');
?>
<table width = "550" align='center'>
	<tr>
		<td>
			<?php
			write_subheader($lang['shop_review']);
			?>
			<form action='<?php echo mw_url('shop', 'checkout'); ?>' method='POST'>
			<input type='hidden' name='action' value='finalize'>
			<input type='hidden' name='id' value='<?php echo $package['id']; ?>'>
				<table width = "550" style = "border-width: 1px; border-style: dotted; border-color: #928058;">
					<tr>
						<td>
							<table width='545' style="border-width: 1px; border-style: solid; border-color: black; background-image: url('<?php echo $Template['path']; ?>/images/light3.jpg');">
							<tr>
								<td>
									<table border='0' cellspacing='0' cellpadding='4' width='540'>
									<?php
										if($_POST['action'] == 'finalize')
										{
											completeOrder();
											echo "<br />";
										}
										else
										{
									?>
										<tr>
											<td align='center' valign = "top" width='100%'><b><u>Items Recieving</u></b></td>
										</tr>
										<tr>
											<td align='center' valign = "top" width='100%'>
											<br />
											<?php
												if($package['item_number'] != 0) 
												{ 
													$item_name = $WDB->selectCell("SELECT `name` FROM `item_template` WHERE entry='".$package['item_number']."'");
													echo "<a href='http://www.wowhead.com/?item=".$package['item_number']."' target='_blank'>".$item_name."</a>";
												}
												if($package['itemset'] != 0) 
												{ 
													echo "<br /><a href='http://www.wowhead.com/?itemset=".$package['itemset']."' target='_blank'>ItemSet # ".$package['itemset']."</a>"; 
												}
												if($package['gold'] != 0) 
												{ 
													echo "<br /><font color='darkgreen'><b>Gold</b>: </font>"; print_gold($package['gold']);
													echo "</font>";
												}
											?>
											</td>
										</tr>
										
										<tr>
											<td align='center' valign = "top" width='100%'>
												<br /><b>Select a Character: </b>
													<select name='char'>
														<?php 
															foreach($character_list as $char)
															{
																echo "<option value='".$char['name']."'>".$char['name']."</option>";
															}
														?>
													</select>
											</td>
										</tr>
										
										<tr>
											<td align='center' valign = "top" width='100%'>
												<br /><b>Web Point Cost: </b><font color='darkred'><u><?php echo $package['wp_cost']; ?></u></font>
											</td>
										</tr>
										<tr>
											<td align='center' valign = "top" width='100%'>
												<br /><input type='image' class="button" src='<?php echo $Template['path']; ?>/images/buttons/complete-button.gif' />
											</td>
										</tr>
									<?php
										}
									?>
									</table>
								</td>
							</tr>
							</table>
						</td>
					</tr>
				</table>
			</form>
		</td>
	</tr>
</table>
<?php
builddiv_end();
?>