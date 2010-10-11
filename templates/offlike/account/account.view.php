<br>
<?php builddiv_start(1, $lang['si_acc']) ?>
<?php if($user['id']>0 && $profile){ ?>
<table align="center" width="100%" style="font-size:0.8em;"><tr><td align="center">
    <div style="border: 2px dotted #1E4378;background:none;margin:4px;padding:6px 9px 6px 9px;text-align:right;width:70%;">
        <center><font size="2"><b><?php echo $profile['username'];?></b>'s Profile</font></center>
		<?php if($profile['g_id'] > 4) {
		echo "<center><font color=\"red\" size=\"3\"> Banned </font></center>";
		}
		elseif($profile['g_id'] > 2){
		echo "<center><font color=\"darkred\">Administrator </font></center>";
		}
		if($profile['vip'] == 1 && $profile['donator'] == 1) {
		echo "<center><font color=\"blue\"> VIP </font>-<font color=\"purple\"> Donator </font></center>";
		}elseif($profile['vip'] == 1) {
		echo "<font color=\"blue\"><center> VIP </center></font>";
		}elseif($profile['donator'] == 1) {
		echo "<font color=\"purple\"><center> Donator </center></font>";
		} ?>
		<?php if($profile['avatar']) { ?>
        <b><center><br /><?php echo $lang['avatar'];?></b> &nbsp;<br/> <img src="images/avatars/<?php echo$profile['avatar'];?>" style="margin:1px;"></center>
        <?php } ?>
		<br /><br /><a href="index.php?n=account&sub=pms&action=add&to=<?php echo$profile['username'];?>"><?php echo $lang['personal_message'];?></a>
    </div>
	<div style="border: 2px dotted #1E4378;background:none;margin:4px;padding:6px 9px 6px 9px;text-align:left;width:70%;">
	<font size="2"><center><b>General Info</b></center></font><br />
	<b><?php echo $lang['gender'];?>: </b><?php if($profile['gender'] == 0) { echo "Male"; }else{ echo "Female"; } ?><br />
	<b>Forum Posts: </b><?php echo $profile['forum_posts'] ?><br /><br />
	<b>Join Date: </b><?php echo $joined ?><br />
	<b>Last Login (Game): </b><?php echo $lastlogggedin; ?><br />
	</div>
    
    <div style="border: 2px dotted #1E4378;background:none;margin:4px;padding:6px 9px 6px 9px;text-align:left;width:70%;">
	<font size="2"><center><b>Contact Details</b></center></font><br />
        <b>Email:</b> <?php echo $profile['email'];?> <br/>
    </div>
    <div style="border: 2px dotted #1E4378;background:none;margin:4px;padding:6px 9px 6px 9px;text-align:left;width:70%;">
	<font size="2"><center><b>Contact Details</b></center></font><br />
	<?php if($profile['hideemail']!=1){ ?>
		<b>Email:</b> <?php echo $profile['email'];?> <br/>
	<?php } ?>
        <b>WWW: </b> <?php echo $profile['homepage'];?> <br/>
        <b>ICQ: </b> <?php echo $profile['icq'];?> <br/>
        <b>MSN: </b> <?php echo $profile['msn'];?> <br/>
        <b><?php echo $lang['wherefrom'];?>: </b> <?php echo $profile['location'];?> <br/>
    </div>
    <div style="border: 2px dotted #1E4378;background:none;margin:4px;padding:6px 9px 6px 9px;text-align:center;width:70%;">
        <b><?php echo $lang['signature'];?></b> <br/>
        <div style="width:70%; text-align: left;"><?php echo my_preview($profile['signature']);?></div>
    </div>
</td></tr></table>
<?php } ?>
<?php builddiv_end() ?>