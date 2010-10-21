<?php
	// Block out all users who arent admins
	if($user['account_level'] == 5) {
		echo "You Are Banned";
		exit;
	}
	if($user['account_level'] <= 2) {
		redirect('index.php',1);
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
	<title>IceWeb Admin Panel</title>
	<link rel="stylesheet" href="inc/admin/css/main.css" type="text/css"/>
	
	<!--[if IE 8]>	
		<link rel="stylesheet" href="inc/admin/css/ie8.css" type="text/css" media="screen" title="ie8" charset="utf-8" />
	<![endif]-->
	
	<!--[if IE 7]>	
		<link rel="stylesheet" href="inc/admin/css/ie7.css" type="text/css" media="screen" title="ie8" charset="utf-8" />
	<![endif]-->
</head>

<body>
<div id="page">
	<!-- Start #header -->
	<div id="header">		
		<div class="pad">			
			<h1 id="title"><center>IceWeb Admin Panel</center></h1>
			<div id="subheader">
				Core Version: <?php echo $Core->version; ?>
				&nbsp;&nbsp;&nbsp; <font color='black'>|</font> &nbsp;&nbsp;&nbsp;
				Database Version: <?php 
                    $db_act_ver = $DB->selectCell("SELECT dbver FROM iceweb_version");
					if($db_act_ver < $Core->exp_dbversion) 
					{ 
						echo "<font color='red'>".$db_act_ver." (<a href=\"index.php?p=admin&sub=updates\" /><small>Needs Updated</small></a>)</font>";
					}
					elseif($db_act_ver > $Core->exp_dbversion) 
					{ 
						echo "<font color='red'>".$db_act_ver." (<a href=\"index.php?p=admin&sub=updates\" /><small>Database outdates the core!</small></a>)</font>";
					}
					else
					{ 
						echo $db_act_ver; 
					} ?> 
			</div>
		</div> <!-- .pad -->		
	</div> <!-- #header -->
	
	<!-- Start #nav -->
	<div id="nav" class="clearfix">		
		<ul>
			<li>
				<center><a href="index.php?p=admin">Admin Home</a> | <a href="index.php">Site Index</a></center>		
			</li>					
		</ul>		
	</div> <!-- #nav -->
	
	<!-- Start #body -->
	<div id="body">	
	<!-- Start #sidebar -->
		<div id="sidebar">			
			<div class="content">				
				<div class="content-header">
					<h4>Server Information</h4> 					
				</div> <!-- .content-header -->						
				<div class="main-content">		
					<p>
						PHP Version: <?php echo phpversion(); ?><br />
						MySQL Version: <?php echo mysql_get_server_info(); ?><br /><br />
						Allow Url Open (Fopen): <?php echo $allowfopen; ?><br />
						Allow Fsockopen: <?php echo $fsock; ?><br />
					</p>						
					<div class="clear"></div>
				</div> <!-- .main-content -->	
			</div> <!-- .content -->		
		</div> <!-- #sidebar -->
		
		<!-- Start #main -->
		<div id="main">		