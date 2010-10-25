<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<!--
/*************************************************************************/
/* You may copy, spread the givenned project, 
/* in accordance with GNU GPL, however any change 
/* the code as a whole or a part of the code given project, 
/* advisable produce with co-ordinations of the author of the project
/*
/* (c) Sasha aka LordSc. lordsc@yandex.ru, updated by TGM and Peec
/* MangosWeb Enhanced By Wilson212. http://code.google.com/p/mwenhanced
/*************************************************************************/
-->
<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
<link rel="shortcut icon" href="<?php echo $currtmp; ?>/images/favicon.ico"/>
<script src="http://static.wowhead.com/widgets/power.js"></script>
<?php
$this_rss_url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$this_rss_url = explode('/index.php',$this_rss_url);
$this_rss_url = "http://".$this_rss_url[0];
if($this_rss_url[strlen($this_rss_url)-1] != '/') 
{
    $this_rss_url .= '/';
}
$this_rss_url .= "rss.php";
?>
<link rel="alternate" href="<?php echo $this_rss_url;?>" type="application/rss+xml" title="<?php echo (string)$cfg->get('site_title');?> RSS News Feed"/>
<title><?php echo (string)$cfg->get('site_title');?><?php echo $title_str;?></title>
<style media="screen" title="currentStyle" type="text/css">
    @import url("<?php echo $currtmp; ?>/css/newhp.css");
    @import url("<?php echo $currtmp; ?>/css/newhp_basic.css");
    @import url("<?php echo $currtmp; ?>/css/newhp_icons.css");
    @import url("<?php echo $currtmp; ?>/css/newhp_layout.css");
    @import url("<?php echo $currtmp; ?>/css/newhp_specific.css");
    @import url("<?php echo $currtmp; ?>/css/additional_optimisation.css");
	@import url("<?php echo $currtmp; ?>/css/topnav.css"); 
</style>
<script type="text/javascript"><!--
    var SITE_HREF = '<?php echo $cfg->get('site_href');?>';
    var DOMAIN_PATH = '<?php echo $_SERVER["HTTP_HOST"];?>';
    var SITE_PATH = '<?php echo $cfg->get('site_href')?>';
--></script>
<script src="<?php echo $currtmp; ?>/js/detection.js" type="text/javascript"></script>
<script src="<?php echo $currtmp; ?>/js/functions.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo $master_tmp; ?>/js/global.js"></script>
<script type="text/javascript" src="<?php echo $master_tmp; ?>/js/compressed/prototype.js"></script>
<script type="text/javascript" src="<?php echo $master_tmp; ?>/js/compressed/behaviour.js"></script>
<script type="text/javascript" src="<?php echo $master_tmp; ?>/js/core.js"></script>
<script type="text/javascript" src="<?php echo $currtmp; ?>/js/template_rules.js"></script>
<script type="text/javascript"><!--
    if (is_ie)
        document.write('<link rel="stylesheet" type="text/css" href="<?php echo $currtmp; ?>/css/additional_win_ie.css" media="screen, projection" />');
    if(is_opera)
        document.write('<link rel="stylesheet" type="text/css" href="<?php echo $currtmp; ?>/css/additional_opera.css" media="screen, projection" />');
    function loadPage(list) {
        location.href=list.options[list.selectedIndex].value
    }
--></script>
</head>
<body>
  <!-- Top Navbar Start -->
  <script>
	var global_nav_lang = '<?php echo ""; ?>';
	var site_name = '<?php echo (string)$cfg->get('site_title'); ?>';
	var site_link = '<?php echo (string)$cfg->get('site_base_href'); echo "index.php" ?>';
	var forum_link = '<?php echo $cfg->get('site_forums'); ?>';
	var armory_link = '<?php echo $cfg->get('site_armory'); ?>';
  </script>
  <div id="shared_topnav">
	<script src="<?php echo $currtmp; ?>/js/buildtopnav.js"></script>
  </div>

  <!-- TOOLTIP start --> 
  <div id="contents">
  <table cellspacing="0" cellpadding="0" border="0" width="100%">
	<tr>
		<td><img src="<?php echo $currtmp; ?>/images/pixel.gif" width="1" height="1" alt="" /></td>
		<td bgcolor="#000000"></td>
		<td><img src="<?php echo $currtmp; ?>/images/pixel.gif" width="1" height="1" alt="" /></td>
	</tr>
	<tr>
		<td bgcolor="#000000"></td>
		<td>
		<table cellspacing="0" cellpadding="0" border="0" width="100%">
			<tr>
				<td width="1" height="1" bgcolor="#000000"></td>
				<td bgcolor="#D5D5D7" height="1"><img src="<?php echo $currtmp; ?>/images/pixel.gif" width="1" height="1" alt="" /></td>
				<td width="1" height="1" bgcolor="#000000"></td>
			</tr>
			<tr>
				<td bgcolor="#A5A5A5" width="1"><img src="<?php echo $currtmp; ?>/images/pixel.gif" width="1" height="1" alt="" /></td>
				<td valign="top" class="trans_div"><div id="tooltiptext"></div></td> 
				<td bgcolor="#A5A5A5" width="1"><img src="<?php echo $currtmp; ?>/images/pixel.gif" width="1" height="1" alt="" /></td>
			</tr>
			<tr>
				<td width="1" height="1" bgcolor="#000000"></td>
				<td bgcolor="#4F4F4F"><img src="<?php echo $currtmp; ?>/images/pixel.gif" width="1" height="2" alt="" /></td>
				<td width="1" height="1" bgcolor="#000000"></td>
			</tr>
        </table>
		</td>
		<td bgcolor="#000000"></td>
	</tr>
	<tr>
		<td><img src="<?php echo $currtmp; ?>/images/pixel.gif" width="1" height="1" alt="" /></td>
		<td bgcolor="#000000"></td>
		<td><img src="<?php echo $currtmp; ?>/images/pixel.gif" width="1" height="1" alt="" /></td>
	</tr>
  </table>
  </div>
  <script src="<?php echo $currtmp; ?>/js/tooltip.js" type="text/javascript"></script>
  <!-- TOOLTIP end --> 
<?php 
// print something lile that when use redirect('link',0,3); <meta http-equiv=refresh content="'.$wait_sec.';url='.$linkto.'">
echo $GLOBALS['redirect'];
?>
    <div style="background: url(<?php echo $currtmp; ?>/images/page-bg-top.jpg) repeat-x 0 0; height: 88px; position: relative; width: 100%; "></div>
    <center>
		<table border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td>
					<div id="hp">
						<div class="top-nav-container">
							<div id="loginbox">
								<div class="loginboxleft"></div>
								<div class="loginboxbg">
									<form action="<?php echo mw_url('account', 'login'); ?>" method="post">
									<?php $accountid = $user['id'];
									if($user['id']>0)
									{
										$userpm_num = 0;
									?>
										<input type="hidden" name="action" value="logout"/>
										<?php 
											echo empty($user['character_name']) ? $user['username'] : $user['character_name'];
										?>
											| <a href="<?php echo mw_url('account', 'pms'); ?>"<?php echo($userpm_num>0?' style="color:red;"':'');?>>
											<?php echo$userpm_num;?> <?php echo $lang['newpms'];?></a>
										<a href="<?php echo mw_url('account', 'manage'); ?>"><img src="<?php echo $currtmp; ?>/images/buttons/button-profile.gif" alt="Profile"/></a> 
										<input type="image" src="<?php echo $currtmp; ?>/images/buttons/button-logout.gif" value="Logout"/>
								<?php 
									}else{ ?>
										Login: <input name="login" size="14" type="text"/>
										Password: <input name="pass" size="14" type="password"/>
										<input type="hidden" name="action" value="login">
										<input type="image" src="<?php echo $currtmp; ?>/images/buttons/button-login.gif" value="Login"/>
								<?php } ?>
									</form>
								</div>
								<div class="loginboxright"></div>
							</div>
						<div onmouseover="myshow('countrydropdown');" id="droppf" onmouseout="myhide('countrydropdown');">
							<div style="overflow: hidden; visibility: inherit; display: block; cursor: default; background-color: transparent; background-image: url(<?php echo $currtmp; ?>/images/countrymenu-bg.gif); height: 19px; padding-left: 9px; padding-top: 2px;"><a class="menufillertop"><?php echo $GLOBALS['user_cur_lang']; ?></a><img src="<?php echo $currtmp; ?>/images/pixel.gif" alt=""/></div>
							<div id="countrydropdown" style="height: auto; visibility:hidden; display: none;">
								<?php foreach($languages as $lang_name) { ?>
									<div OnMouseOver="this.style.backgroundColor='rgb(100, 100, 100)';" OnMouseOut="this.style.backgroundColor='rgb(29, 28, 27)';" style="cursor: pointer; background-color: rgb(29, 28, 27); color: rgb(244, 196, 0); font-family: arial,comic sans ms,technical; font-size: 12px; font-style: normal; text-align: left; background-image: url(<?php echo $currtmp; ?>/images/bullet-trans-bg.gif); width: 136px; height: 15px; padding-left: 9px; padding-top: 0px; left: 1px; top: 1px;">
										<a class="menuLink" style="display:block;" href="javascript:setcookie('Language', '<?php echo $lang_name;?>'); window.location.reload();"><?php echo ($GLOBALS['user_cur_lang'] == $lang_name?'&gt; '.$lang_name:$lang_name);?></a>
									</div>
								<?php } ?>
							</div>
						</div>
						<div onmouseover="myshow('contextdropdown');" id="dropps" onmouseout="myhide('contextdropdown');">
							<div style="overflow: hidden; visibility: inherit; display: block; cursor: default; background-color: transparent; background-image: url(<?php echo $currtmp; ?>/images/countrymenu-bg.gif); height: 19px; padding-left: 9px; padding-top: 2px;"><a class="menufillertop">Theme:</a><img src="<?php echo $currtmp; ?>/images/pixel.gif" alt=""/></div>
							<div id="contextdropdown" style="height: auto; visibility:hidden; display: none;">
							<?php
								$tmpl_list = explode(",", $cfg->get('templates'));
								$tkey = 0;
								foreach($tmpl_list as $templ) 
								{ ?>
									<div OnMouseOver="this.style.backgroundColor='rgb(100, 100, 100)';" OnMouseOut="this.style.backgroundColor='rgb(29, 28, 27)';" style="cursor: pointer; background-color: rgb(29, 28, 27); color: rgb(244, 196, 0); font-family: arial,comic sans ms,technical; font-size: 12px; font-style: normal; text-align: left; background-image: url(<?php echo $currtmp; ?>/images/bullet-trans-bg.gif); width: 136px; height: 15px; padding-left: 9px; padding-top: 0px; left: 1px; top: 1px;">
										<a class="menuLink" style="display:block;" href="javascript:setcookie('cur_selected_theme', '<?php echo $tkey;?>'); window.location.reload();"><?php echo ($_COOKIE['cur_selected_theme'] == $tkey?'&gt; '.$templ:$templ);?></a> 
									</div>
							<?php 	$tkey++;
								} ?>
							</div>
						</div>
						<div onmouseover="myshow('realmdropdown');" id="droppt" onmouseout="myhide('realmdropdown');">
							<div style="overflow: hidden; visibility: inherit; display: block; cursor: default; background-color: transparent; background-image: url(<?php echo $currtmp; ?>/images/countrymenu-bg.gif); height: 19px; padding-left: 9px; padding-top: 2px;"><a class="menufillertop">Realm:</a><img src="<?php echo $currtmp; ?>/images/pixel.gif" alt=""/></div>
							<div id="realmdropdown" style="height: auto; visibility:hidden; display: none;">
							<?php
							foreach($realms as $realm) { ?>
								<div OnMouseOver="this.style.backgroundColor='rgb(100, 100, 100)';" OnMouseOut="this.style.backgroundColor='rgb(29, 28, 27)';" style="cursor: pointer; background-color: rgb(29, 28, 27); color: rgb(244, 196, 0); font-family: arial,comic sans ms,technical; font-size: 12px; font-style: normal; text-align: left; background-image: url(<?php echo $currtmp; ?>/images/bullet-trans-bg.gif); width: 136px; height: 15px; padding-left: 9px; padding-top: 0px; left: 1px; top: 1px;">
									<a class="menuLink" style="display:block;" href="javascript:setcookie('cur_selected_realm', '<?php echo $realm['id'];?>'); window.location.reload();"><?php echo ($user['cur_selected_realm'] == $realm['id']?'&gt; '.$realm['name']:$realm['name']);?></a> 
								</div>
							<?php } ?>
							</div>
						</div>
						<div style="position: absolute; top: 0; left: 0; z-index: 20002;">
							<div id="wow-logo">
								<a href="./"><img title="World of Warcraft" alt="wowlogo" height="103" width="252" src="<?php echo $currtmp; ?>/images/pixel000.gif"/></a>
							</div>
						</div>
					</div>
				<div>
                <div id="hpwrapper">
                  <table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td valign="top">
                        <div id="navwrapper">
                          <div id="nav">
                            <div id="left-bg"></div>
                            <div id="leftmenu">
                              <div id="leftmenucontainer">
                                <?php 
                                    build_main_menu(); // MAIN LINKS HERE!!!
                                ?>
                              </div>
                            </div>
                          </div>
                          <div style="clear: both;"></div>
                        </div>
                      </td><td valign="top">
                        <div id="mainwrapper">
						<div id="main">
                            <div id="main-content-wrapper">
                              <div id="main-content">
                                <table cellspacing="0" cellpadding="0" border="0">
                                  <tr>
                                    <td>
                                      <div id="main-top">
                                        <div>
                                          <div><div></div></div>
                                        </div>
                                      </div>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>
                                      <div id="contentpadding">
                                        <div id="cnt">
                                          <div id="cnt-wrapper">
                                            <div id="contentcontainer">
                                              <table cellspacing="0" cellpadding="0" border="0" width="99%">
                                                <tr>
                                                  <td valign="top">
                                                    <div id="cntmain">
                                                      <div id="cnt-top">
                                                        <div>
                                                          <div></div>
                                                        </div>
                                                      </div>
                                                      <div id="content">
                                                        <div id="content-left">
                                                          <div id="content-right">
                                                            <div style="padding-right:10px; margin-left:11px;" id="compcont"> 
                                                            <div style="clear:both;display:block;position:relative;width:100%;margin-top:-4px;">
                                                            <!-- Pathway -->
                                                            <?php if(isset($_GET['p'])){ ?><div class="redbannerbg"><div class="redbannerleft"></div><div class="redbannerlabel"><?php echo $pathway_str;?></div><div class="redbannerright"></div></div><?php } ?>
                                                            <?php echo $GLOBALS['messages']; ?>
                                                            <!-- Component body BEGIN -->
                                                            