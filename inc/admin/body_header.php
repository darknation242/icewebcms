<?php
	// Block out all users who arent admins
	if($user['account_level'] == 5) 
	{
		echo "You Are Banned";
		exit;
	}
	if($user['account_level'] <= 2) 
	{
		redirect('index.php',1);
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
	<title>MangosWeb Enhanced Admin Panel</title>
	<link rel="stylesheet" href="inc/admin/css/main.css" type="text/css"/>
	
	<!--[if IE 8]>	
		<link rel="stylesheet" href="inc/admin/css/ie8.css" type="text/css" media="screen" title="ie8" charset="utf-8" />
	<![endif]-->
	
	<!--[if IE 7]>	
		<link rel="stylesheet" href="inc/admin/css/ie7.css" type="text/css" media="screen" title="ie8" charset="utf-8" />
	<![endif]-->
	
	<!-- TinyMCE Module -->
	<script type="text/javascript" src="modules/tiny_mce/tiny_mce.js"></script>
	
	<script type="text/javascript">
	tinyMCE.init({
		// General options
		mode : "textareas",
		theme : "advanced",
		plugins : "style,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist",

		// Theme options
		theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,image,|,insertdate,inserttime,preview,|,forecolor",
		theme_advanced_buttons3 : "hr,|,charmap,emotions,iespell,|,fullscreen",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,

		// Example content CSS (should be your site CSS)
		content_css : "css/content.css",

		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "lists/template_list.js",
		external_link_list_url : "lists/link_list.js",
		external_image_list_url : "lists/image_list.js",
		media_external_list_url : "lists/media_list.js",

		// Style formats
		style_formats : [
			{title : 'Bold text', inline : 'b'},
			{title : 'Red text', inline : 'span', styles : {color : '#ff0000'}},
			{title : 'Red header', block : 'h1', styles : {color : '#ff0000'}},
			{title : 'Example 1', inline : 'span', classes : 'example1'},
			{title : 'Example 2', inline : 'span', classes : 'example2'},
			{title : 'Table styles'},
			{title : 'Table row 1', selector : 'tr', classes : 'tablerow1'}
		],

		// Replace values for the template plugin
		template_replace_values : {
			username : "Some User",
			staffid : "991234"
		}
	});
</script>
	
</head>

<body>
<div id="page">
	<!-- Start #header -->
	<div id="header">		
		<div class="pad">			
			<h1 id="title"><center><img src="inc/admin/images/MangosWeb.png" /></center></h1>
			<div id="subheader">
				Core Version: <?php echo $Core->version; ?>
				&nbsp;&nbsp;&nbsp; <font color='black'>|</font> &nbsp;&nbsp;&nbsp;
				Database Version: <?php 
                    $db_act_ver = $DB->selectCell("SELECT `dbver` FROM `mw_db_version`");
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