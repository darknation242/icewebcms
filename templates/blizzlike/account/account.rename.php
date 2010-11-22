<br />
<?php 
builddiv_start(0, $lang['char_rename']);
?>

<table width = "550" align='center'>
	<tr>
		<td>
			<?php
			write_subheader($lang['char_rename']);
			?>
			<table width = "550" style = "border-width: 1px; border-style: dotted; border-color: #928058;">
				<tr>
					<td>
						<table width='545' style="border-width: 1px; border-style: solid; border-color: black; background-image: url('<?php echo $Template['path']; ?>/images/light3.jpg');">
						<tr>
							<td>
								<form method='POST' action='<?php echo mw_url('account', 'rename'); ?>'>
								<table border='0' cellspacing='0' cellpadding='4' width='540'>
								<?php
									if(isset($_POST['newname']))
									{
										if($_POST['id'] != 0)
										{
											changeName();
										}
										else
										{
											output_message('error', $lang['account_has_no_characters']);
										}
									}
								?>
								<tr>
									<td colspan='2'><center><?php echo $Page_Desc; ?></center><br /></td>
								</tr>
								<tr>
									<td align='right' valign = "top" width='30%'><b>Character: </b></td>
									<td align='left' valign = "top" width='70%'>
										<select name='id'>
											<?php
												if($character_list == FALSE)
												{
													echo "<option value='0'>No Characters Found!</option>";
												}
												else
												{
													foreach($character_list as $character)
													{
														echo "<option value='".$character['guid']."'>".$character['name']."</option>";
													}
												}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<td align='right' valign = "top" width='30%'><b>New Name: </b></td>
									<td align='left' valign = "top" width='70%'>
										<input type='text' name='newname' size='36'>
									</td>
								</tr>
								<tr>
									<td colspan='2' align='center'>
										<br />
										<input type="image" src="<?php echo $Template['path']; ?>/images/buttons/continue-button.gif" class="button" style="font-size:12px;" value="<?php echo $lang['change'];?>">
									</td>
								</tr>
								</table>
								</form>
							</td>
						</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<?php
builddiv_end(); 
?>