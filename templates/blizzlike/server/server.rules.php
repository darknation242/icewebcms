<br />
<?php builddiv_start(1, $lang['server_rules']) ?>
Welcome <?php echo $user['username'] ?>! <?php echo $lang['server_rules_desc'] ?>
<br /><br />
<div style="border: 2px dotted #1E4378;margin:4px;padding:6px 9px;text-align:left;font-weight:bold;">
<h2><center>
  <u><?php echo $lang['server_rules'] ?></u></center></h2>
<br /><?php echo $content; ?>
</div>
<br />
<?php builddiv_end() ?>