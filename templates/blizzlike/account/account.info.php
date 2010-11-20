<br>
<?php 
builddiv_start(0, $lang['account_info']);
?>
<table width = "550" align='center'>
	<tr>
		<td>
			<?php
			write_subheader($lang['account_info']);
			?>
			<table width = "550" style = "border-width: 1px; border-style: dotted; border-color: #928058;">
				<tr>
					<td>
						<table width='545' style="border-width: 1px; border-style: solid; border-color: black; background-image: url('<?php echo $currtmp; ?>/images/light3.jpg');">
						<tr>
							<td>
								<table border='0' cellspacing='0' cellpadding='4' width='540'>
								<tr>
									<td align='right' valign = "top" width='25%'><b>Account Status:</b></td>
									<td align='left' valign = "top" width='25%'>
										<?php 
											if($user['locked'] == 1)
											{
												echo "<font color='darkred'><b>Locked</b></font>";
											}
											else
											{
												echo "<font color='green'><b>Active</b></font>";
											}
										?>
									</td>
									<td align='right' valign = "top" width='25%'><b>Vote Count:<b></td>
									<td align='left' valign = "top" width='25%'><?php echo $user['total_votes']; ?></td>
								</tr>
								<tr>
									<td align='right' valign = "top" width='25%'><b>Registration Date:</b></td>
									<td align='left' valign = "top" width='25%'><?php echo $joindate; ?></td>
									<td align='right' valign = "top" width='25%'><b>Webpoint Balance:<b></td>
									<td align='left' valign = "top" width='25%'><?php echo $user['web_points']; ?></td>
								</tr>
								<tr>
									<td align='right' valign = "top" width='25%'><b>Registration IP:</b></td>
									<td align='left' valign = "top" width='25%'><?php echo $regiseter_ip; ?></td>
									<td align='right' valign = "top" width='25%'><b>Earned/Spent:<b></td>
									<td align='left' valign = "top" width='25%'><?php echo $user['points_earned']." / ".$user['points_spent']; ?></td>
								</tr>
								<tr>
									<td align='right' valign = "top" width='25%'><b>Account Level:</b></td>
									<td align='left' valign = "top" width='25%'><?php echo $account_level; ?></td>
									<td align='right' valign = "top" width='25%'><b>Total Donations:<b></td>
									<td align='left' valign = "top" width='25%'>$<?php echo $user['total_donations']; ?></td>
								</tr>
								</table>
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