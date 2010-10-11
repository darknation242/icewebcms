<br>
<?php builddiv_start(1, $lang['admin_panel']) ?>
Welcome to the MangosWeb Enhanced v2 Admin Panel. Current MangosWeb Revision: <?php echo $rev ?>
<ul style="font-weight:bold;"><h2>Site Managment</h2>
      <li><a href="index.php?n=admin&amp;sub=members"><?php echo $lang['users_manage'];?></a></li>
      <li><a href="index.php?n=admin&amp;sub=config"><?php echo $lang['site_config'];?></a></li>
	  <li><a href="index.php?n=admin&sub=realms"><?php echo $lang['realms_manage'];?></a></li>
      <li><a href="index.php?n=admin&sub=keys"><?php echo $lang['regkeys_manage'];?></a></li>
      <li><a href="index.php?n=admin&sub=donate"><?php echo $lang['donate'];?> Admin</a></li>
	  <li><a href="index.php?n=admin&sub=vote"><?php echo $lang['vote'];?> Admin</a></li>
	  <li><a href="index.php?n=admin&sub=faq">FAQ Admin</a></li>
	  <li><a href="index.php?n=admin&sub=langs"><?php echo $lang['langs_manage'];?></a></li>
	  <li><a href="index.php?n=admin&sub=updatefields">Update Data Fields</a></li>
	  <!--<br />-->
	  <li><a href="components/admin/extplorer/" target="_blank">MangosWeb File Manager</a></li>
</ul>

<ul style="font-weight:bold;"><h2>Character Tools</h2>
	  <li><a href="index.php?n=admin&sub=chartools">Character Rename</a></li>
	  <li><a href="index.php?n=admin&sub=chartransfer">Character Transfer</a></li>
</ul>

<ul style="font-weight:bold;"><h2>Server Managment</h2>
	  <li><a href="index.php?n=admin&sub=tickets">GM Ticket Manager</a></li>    
  <?php
  if ((int)$MW->getConfig->core_work->enable){
  ?>
  <li><a href="index.php?n=admin&amp;sub=viewlogs">View GM Logs</a></li>
  <?php } ?>
  <li><a href="index.php?n=admin&sub=backup">Backup management</a></li>
</ul>

<ul style="font-weight:bold;"><h2>News Manager</h2>
	<li><a href="index.php?n=admin&sub=forum">News Admin</a></li>
	<br />
	<li><a href="index.php?n=admin&sub=news&action=add"><?php echo $lang['news_add'];?></a></li>
	<li><a href="index.php?n=admin&sub=news&action=edit"><?php echo $lang['news_manage'];?></a></li>
</ul>
<?php builddiv_end() ?>
