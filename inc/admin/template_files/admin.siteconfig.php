<!-- Start #main -->
<div id="main">			
	<div class="content">	
		<div class="content-header">
			<h4><a href="?p=admin">Main Menu</a> / Site Config</h4>
		</div> <!-- .content-header -->				
		<div class="main-content">					
		<?php 
		if(isset($_POST['task'])) 
		{
			if($_POST['task'] == 'saveconfig') 
			{
				saveConfig();
			}
		} ?>
			<div class="mini-nav" style="width: 98%;">
				<table>
					<thead>
						<th  style="background: #FFD;"><center>Sub - Navigation</center></th>
					</thead>
				</table>
				<p>
					<center>
						| <a href="#basic">Basic Settings</a> |
						<a href="#config">Site Configuration</a> |
						<a href="#lang">Language Settings</a> |
						<a href="#acct">Account & Register Settings</a> |
						<a href="#fp">Frontpage Settings</a> |
						<br />
						| <a href="#email">Email Settings</a> |
						| <a href="#paypal">Paypal Settings</a> |
					</center>
				</p>
			</div>
			<form method="POST" action="?p=admin&sub=siteconfig" name="adminform" class="form label-inline">
			<input type="hidden" name="task" value="saveconfig">
	
			<!-- BASIC SITE CONFIG -->
			<table>
				<thead>
					<tr>
						<th><center><a name="basic"></a>Basic Site Settings</center></th>
					</tr>
				</thead>
			</table>
			<br />
			
			<div class="field">
				<label for="Site Title">Site Title: </label>
				<input id="Site Title" name="cfg__site_title" size="20" type="text" class="medium" value="<?php echo $Config->get('site_title'); ?>" />
				<p class="field_help">Enter your site title here.</p>
			</div>
			
			<div class="field">
				<label for="Site Email">Site Email: </label>
				<input id="Site Email" name="cfg__site_email" size="20" type="text" class="medium" value="<?php echo $Config->get('site_email'); ?>" />
				<p class="field_help">Enter your site email here.</p>
			</div>
			
			<div class="field">
				<label for="Site Cookie">Site Cookie: </label>
				<input id="Site Cookie" name="cfg__site_cookie" size="20" type="text" class="medium" value="<?php echo $Config->get('site_cookie'); ?>" />
				<p class="field_help">Site cookie name. Changing this will log you out as well as close all sessions.</p>
			</div>
			
			<div class="field">
				<label for="Site Armory">Site Armory Link: </label>
				<input id="Site Armory" name="cfg__site_armory" size="20" type="text" class="medium" value="<?php echo $Config->get('site_armory'); ?>" />
				<p class="field_help">Full link including "http://" to your armory. Set to "0" to disable.</p>
			</div>
			
			<div class="field">
				<label for="Site Forums">Site Forums Link: </label>
				<input id="Site Forums" name="cfg__site_forums" size="20" type="text" class="medium" value="<?php echo $Config->get('site_forums'); ?>" />
				<p class="field_help">Full link including "http://" to your forums. Set to "0" to disable.</p>
			</div>
			
			<!-- SITE CONFIG -->
			<table>
				<thead>
					<tr>
						<th><center><a name="config"></a>Site Configuration</center></th>
					</tr>
				</thead>
			</table>
			<br />
			
			<div class="field">
				<label for="Site Emu">Emulator: </label>
				<select id="type" class="small" name="cfg__emulator">
					<?php 
						if($Config->get('emulator') == 'mangos')
						{ $e_s = 'selected="selected"'; $e_s2 = ''; }else{ $e_s2 = 'selected="selected"'; $e_s = ''; }
					?>
					<option value="mangos" <?php echo $e_s; ?>>Mangos</option>
					<option value="trinity" <?php echo $e_s2; ?>>Trinity</option>
				</select>
			</div>
			
			<div class="field">
				<label for="Site DR">Default Realm: </label>
				<select id="type" class="medium" name="cfg__default_realm_id">
					<?php 
						foreach($realms as $Config_realms)
						{
							if($Config->get('default_realm_id') == $Config_realms['id'])
							{ $e_rs = 'selected="selected"'; }else{ $e_rs = ''; }
							echo "<option value=".$Config_realms['id']." ".$e_rs.">".$Config_realms['name']."</option>";
						}
					?>
				</select>
				<p class="field_help">Default selected realm for new users.</p>
			</div>
			
			<div class="field">
				<label for="Site Templates">Site Templates: </label>
				<input id="Site Templates" name="cfg__templates" size="30" type="text" class="large" value="<?php echo $Config->get('templates'); ?>" />
				<p class="field_help">Seperate templates with a "," comma. Case sensative on some servers!</p>
			</div>
			<br />
			
			<!-- LANG CONFIG -->
			<table>
				<thead>
					<tr>
						<th><center><a name="lang"></a>Site Language Configuration</center></th>
					</tr>
				</thead>
			</table>
			<br />
			
			<div class="field">
				<label for="Site DL">Default Language: </label>
				<input id="Site DL" name="cfg__default_lang" size="20" type="text" class="medium" value="<?php echo $Config->get('default_lang'); ?>" />
				<p class="field_help">Website default language</p>
			</div>
			
			<div class="field">
				<label for="Site AL">Site Languages: </label>
				<input id="Site AL" name="cfg__available_lang" size="20" type="text" class="medium" value="<?php echo $Config->get('available_lang'); ?>" />
				<p class="field_help">Seperate Languages with a "," comma. Case sensative on some servers!</p>
			</div>
			<br />
			
			<!-- ACCOUNT CONFIG -->
			<table>
				<thead>
					<tr>
						<th><center><a name="acct"></a>Site Registration / Account Configuration</center></th>
					</tr>
				</thead>
			</table>
			<br />
			
			<div class="field">
				<label for="Site allow_registration">Account Registration: </label>
				<select id="type" class="small" name="cfg__allow_registration">
					<?php 
						if($Config->get('allow_registration') == 1)
						{ $e_ar = 'selected="selected"'; $e_ar2 = ''; }else{ $e_ar2 = 'selected="selected"'; $e_ar = ''; }
					?>
					<option value="1" <?php echo $e_ar; ?>>Enabled</option>
					<option value="0" <?php echo $e_ar2; ?>>Disabled</option>
				</select>																											
				<p class="field_help">Allow guests to register an account on your server.</p>
			</div>
			
			<div class="field">
				<label for="Site require_act_activation">Require Account Activation: </label>
				<select id="type" class="small" name="cfg__require_act_activation">
					<?php 
						if($Config->get('require_act_activation') == 1)
						{ $e_arr = 'selected="selected"'; $e_arr2 = ''; }else{ $e_arr2 = 'selected="selected"'; $e_arr = ''; }
					?>
					<option value="1" <?php echo $e_arr; ?>>Enabled</option>
					<option value="0" <?php echo $e_arr2; ?>>Disabled</option>
				</select>																											
				<p class="field_help">Requires users to activate there accounts via Email.</p>
			</div>
			
			<div class="field">
				<label for="Site reg_invite">Require Invite: </label>
				<select id="type" class="small" name="cfg__reg_invite">
					<?php 
						if($Config->get('reg_invite') == 1)
						{ $e_ari = 'selected="selected"'; $e_ari2 = ''; }else{ $e_ari2 = 'selected="selected"'; $e_ari = ''; }
					?>
					<option value="1" <?php echo $e_ari; ?>>Enabled</option>
					<option value="0" <?php echo $e_ari2; ?>>Disabled</option>
				</select>																											
				<p class="field_help">Requires guests to have an invite code before registering an account.</p>
			</div>
			
			<div class="field">
				<label for="Site reg_invite">Registration captcha: </label>
				<select id="type" class="small" name="cfg__reg_act_imgvar">
					<?php 
						if($Config->get('reg_act_imgvar') == 1)
						{ $e_ariv = 'selected="selected"'; $e_ariv2 = ''; }else{ $e_ariv2 = 'selected="selected"'; $e_ariv = ''; }
					?>
					<option value="1" <?php echo $e_ariv; ?>>Enabled</option>
					<option value="0" <?php echo $e_ariv2; ?>>Disabled</option>
				</select>																											
				<p class="field_help">Enables CAPTCHA. Users will hae to enter a generated image code before creating an account</p>
			</div>
			
			<div class="field">
				<label for="Site reg_invite">Require Secret Questions: </label>
				<select id="type" class="small" name="cfg__reg_secret_questions">
					<?php 
						if($Config->get('reg_secret_questions') == 1)
						{ $e_arsq = 'selected="selected"'; $e_arsq2 = ''; }else{ $e_arsq2 = 'selected="selected"'; $e_arsq = ''; }
					?>
					<option value="1" <?php echo $e_arsq; ?>>Enabled</option>
					<option value="0" <?php echo $e_arsq2; ?>>Disabled</option>
				</select>																											
				<p class="field_help">Requires users to input secret questions / answers when registering account.<br /> 
					Questions are set in <a href="?p=admin&sub=squestions">here</a>
				</p>
			</div>
			
			<div class="field">
				<label for="Site allow_user_passchange">Allow Pass Change: </label>
				<select id="type" class="small" name="cfg__allow_user_passchange">
					<?php 
						if($Config->get('allow_user_passchange') == 1)
						{ $e_aup = 'selected="selected"'; $e_aup2 = ''; }else{ $e_aup2 = 'selected="selected"'; $e_aup = ''; }
					?>
					<option value="1" <?php echo $e_aup; ?>>Enabled</option>
					<option value="0" <?php echo $e_aup2; ?>>Disabled</option>
				</select>																											
				<p class="field_help">Allow users to change their passwords</p>
			</div>
			
			<div class="field">
				<label for="Site allow_user_emailchange">Allow Email Change: </label>
				<select id="type" class="small" name="cfg__allow_user_emailchange">
					<?php 
						if($Config->get('allow_user_emailchange') == 1)
						{ $e_aec = 'selected="selected"'; $e_aec2 = ''; }else{ $e_aec2 = 'selected="selected"'; $e_aec = ''; }
					?>
					<option value="1" <?php echo $e_aec; ?>>Enabled</option>
					<option value="0" <?php echo $e_aec2; ?>>Disabled</option>
				</select>																											
				<p class="field_help">Allow users to change their Emails</p>
			</div>
			
			<div class="field">
				<label for="Site max_act_per_ip">Max Accounts Per IP: </label>
				<input id="Site max_act_per_ip" name="cfg__max_act_per_ip" size="10" type="text" class="tiny" value="<?php echo $Config->get('max_act_per_ip'); ?>" />
				<p class="field_help">Maximum accounts per IP address. "0" is unlimited.</p>
			</div>
			
			<div class="field">
				<label for="Site max_avatar_file_size">Avatar File Size: </label>
				<input id="Site max_avatar_file_size" name="cfg__max_avatar_file_size" size="10" type="text" class="small" value="<?php echo $Config->get('max_avatar_file_size'); ?>" />
				<p class="field_help">Size in bytes. "0" to disable</p>
			</div>
			
			<div class="field">
				<label for="Site max_avatar_sizee">Avatar Max Dimmensions: </label>
				<input id="Site max_avatar_size" name="cfg__max_avatar_size" size="10" type="text" class="small" value="<?php echo $Config->get('max_avatar_size'); ?>" />
				<p class="field_help">Example: "80x80". Dont forget the "x" between the hieght and width!</p>
			</div>
			<br />
			
			<!-- Frontpage Settings -->
			<table>
				<thead>
					<tr>
						<th><center><a name="fp"></a>Frontpage Settings</center></th>
					</tr>
				</thead>
			</table>
			<br />
			
			<div class="field">
				<label for="Site default_component">Default Component: </label>
				<input id="Site default_component" name="cfg__default_component" size="10" type="text" class="small" value="<?php echo $Config->get('default_component'); ?>" />
				<p class="field_help">Dont touch this unless you know what your doing!</p>
			</div>
			
			<div class="field">
				<label for="Site flash_display_type">Banner Type: </label>
				<select id="type" class="medium" name="cfg__flash_display_type">
					<?php 
						if($Config->get('flash_display_type') == 0)
							{ $e_fpf = ''; $e_fpf2 = ''; $e_fpf3 = 'selected="selected"'; }
						elseif($Config->get('flash_display_type') == 1)
							{ $e_fpf = 'selected="selected"'; $e_fpf2 = ''; $e_fpf3 = ''; }
						elseif($Config->get('flash_display_type') == 2)
							{ $e_fpf = ''; $e_fpf2 = 'selected="selected"'; $e_fpf3 = ''; }
					?>
					<option value="2" <?php echo $e_fpf2; ?>>External Flash</option>
					<option value="1" <?php echo $e_fpf; ?>>Internal Flash</option>
					<option value="0" <?php echo $e_fpf3; ?>>Banner</option>
				</select>																											
				<p class="field_help">External Flash is directly played from worldofwarcraft.com. <br />Banner is an image called "banner.jpg in the 
					"templates/< template name >/images/" folder</p>
			</div>
			
			<div class="field">
				<label for="Site fp_vote_banner">Vote Banner: </label>
				<select id="type" class="small" name="cfg__fp_vote_banner">
					<?php 
						if($Config->get('fp_vote_banner') == 1)
						{ $e_fpvb = 'selected="selected"'; $e_fpvb2 = ''; }else{ $e_fpvb2 = 'selected="selected"'; $e_fpvb = ''; }
					?>
					<option value="1" <?php echo $e_fpvb; ?>>Enabled</option>
					<option value="0" <?php echo $e_fpvb2; ?>>Disabled</option>
				</select>																											
				<p class="field_help">Displays the vote banner on the frontpage.</p>
			</div>
			
			<div class="field">
				<label for="Site fp_newbie_guide">Newbie Guide: </label>
				<select id="type" class="small" name="cfg__fp_newbie_guide">
					<?php 
						if($Config->get('fp_newbie_guide') == 1)
						{ $e_fpng = 'selected="selected"'; $e_fpng2 = ''; }else{ $e_fpng2 = 'selected="selected"'; $e_fpng = ''; }
					?>
					<option value="1" <?php echo $e_fpng; ?>>Enabled</option>
					<option value="0" <?php echo $e_fpng2; ?>>Disabled</option>
				</select>																											
				<p class="field_help">Displays the Newbie Guide on the frontpage.</p>
			</div>
			
			<div class="field">
				<label for="Site fp_hitcounter">Hit Counter: </label>
				<select id="type" class="small" name="cfg__fp_hitcounter">
					<?php 
						if($Config->get('fp_hitcounter') == 1)
						{ $e_fphc = 'selected="selected"'; $e_fphc2 = ''; }else{ $e_fphc2 = 'selected="selected"'; $e_fphc = ''; }
					?>
					<option value="1" <?php echo $e_fphc; ?>>Enabled</option>
					<option value="0" <?php echo $e_fphc2; ?>>Disabled</option>
				</select>																											
				<p class="field_help">Displays the Hit Counter on the frontpage</p>
			</div>
			
			<div class="field">
				<label for="Site fp_serverinfo">Server Info: </label>
				<select id="type" class="small" name="cfg__fp_serverinfo">
					<?php 
						if($Config->get('fp_serverinfo') == 1)
						{ $e_fpsi = 'selected="selected"'; $e_fpsi2 = ''; }else{ $e_fpsi2 = 'selected="selected"'; $e_fpsi = ''; }
					?>
					<option value="1" <?php echo $e_fpsi; ?>>Enabled</option>
					<option value="0" <?php echo $e_fpsi2; ?>>Disabled</option>
				</select>																											
				<p class="field_help">Displays the 'Server Info' on the frontpage. Enabling this can/will cause Frontpage to load slower.</p>
			</div>
			
			<div class="field">
				<label for="Site fp_realmstatus">Realm Status: </label>
				<select id="type" class="small" name="cfg__fp_realmstatus">
					<?php 
						if($Config->get('fp_realmstatus') == 1)
						{ $e_fpsirs = 'selected="selected"'; $e_fpsirs2 = ''; }else{ $e_fpsirs2 = 'selected="selected"'; $e_fpsirs = ''; }
					?>
					<option value="1" <?php echo $e_fpsirs; ?>>Enabled</option>
					<option value="0" <?php echo $e_fpsirs2; ?>>Disabled</option>
				</select>																											
				<p class="field_help">Displays the 'Realm Status' under Server Info. FP Server Info must be Enabled!</p>
			</div>
			
			<div class="field">
				<label for="Site fp_playersonline">Players Online: </label>
				<select id="type" class="small" name="cfg__fp_playersonline">
					<?php 
						if($Config->get('fp_playersonline') == 1)
						{ $e_fpsipo = 'selected="selected"'; $e_fpsipo2 = ''; }else{ $e_fpsipo2 = 'selected="selected"'; $e_fpsipo = ''; }
					?>
					<option value="1" <?php echo $e_fpsipo; ?>>Enabled</option>
					<option value="0" <?php echo $e_fpsipo2; ?>>Disabled</option>
				</select>																											
				<p class="field_help">Displays the '# of Players Online' under Server Info. FP Server Info must be Enabled!</p>
			</div>
			
			<div class="field">
				<label for="Site fp_serverip">Server IP: </label>
				<select id="type" class="small" name="cfg__fp_serverip">
					<?php 
						if($Config->get('fp_serverip') == 1)
						{ $e_fpsiip = 'selected="selected"'; $e_fpsiip2 = ''; }else{ $e_fpsiip2 = 'selected="selected"'; $e_fpsiip = ''; }
					?>
					<option value="1" <?php echo $e_fpsiip; ?>>Enabled</option>
					<option value="0" <?php echo $e_fpsiip2; ?>>Disabled</option>
				</select>																											
				<p class="field_help">Displays the '# of Players Online' under Server Info. FP Server Info must be Enabled!</p>
			</div>
			
			<div class="field">
				<label for="Site fp_servertype">Server Type: </label>
				<select id="type" class="small" name="cfg__fp_servertype">
					<?php 
						if($Config->get('fp_servertype') == 1)
						{ $e_fpsist = 'selected="selected"'; $e_fpsist2 = ''; }else{ $e_fpsist2 = 'selected="selected"'; $e_fpsist = ''; }
					?>
					<option value="1" <?php echo $e_fpsist; ?>>Enabled</option>
					<option value="0" <?php echo $e_fpsist2; ?>>Disabled</option>
				</select>																											
				<p class="field_help">Displays the 'Server Type' under Server Info. FP Server Info must be Enabled!</p>
			</div>
			
			<div class="field">
				<label for="Site fp_serverlang">Server Language: </label>
				<select id="type" class="small" name="cfg__fp_serverlang">
					<?php 
						if($Config->get('fp_serverlang') == 1)
						{ $e_fpsisl = 'selected="selected"'; $e_fpsisl2 = ''; }else{ $e_fpsisl2 = 'selected="selected"'; $e_fpsisl = ''; }
					?>
					<option value="1" <?php echo $e_fpsisl; ?>>Enabled</option>
					<option value="0" <?php echo $e_fpsisl2; ?>>Disabled</option>
				</select>																											
				<p class="field_help">Displays the 'Server Language' under Server Info. FP Server Info must be Enabled!</p>
			</div>
			
			<div class="field">
				<label for="Site fp_serverpop">Server Population: </label>
				<select id="type" class="small" name="cfg__fp_serverpop">
					<?php 
						if($Config->get('fp_serverpop') == 1)
						{ $e_fpsipop = 'selected="selected"'; $e_fpsipop2 = ''; }else{ $e_fpsipop2 = 'selected="selected"'; $e_fpsipop = ''; }
					?>
					<option value="1" <?php echo $e_fpsipop; ?>>Enabled</option>
					<option value="0" <?php echo $e_fpsipop2; ?>>Disabled</option>
				</select>																											
				<p class="field_help">Displays the 'Server Population' under Server Info. FP Server Info must be Enabled!</p>
			</div>
			
			<div class="field">
				<label for="Site fp_serveract">Server Accounts: </label>
				<select id="type" class="small" name="cfg__fp_serveract">
					<?php 
						if($Config->get('fp_serveract') == 1)
						{ $e_fpsiat = 'selected="selected"'; $e_fpsiat2 = ''; }else{ $e_fpsiat2 = 'selected="selected"'; $e_fpsiat = ''; }
					?>
					<option value="1" <?php echo $e_fpsiat; ?>>Enabled</option>
					<option value="0" <?php echo $e_fpsiat2; ?>>Disabled</option>
				</select>																											
				<p class="field_help">Displays the 'Server Accounts' under Server Info. FP Server Info must be Enabled!</p>
			</div>
			
			<div class="field">
				<label for="Site fp_serveractive_act">Active Accounts: </label>
				<select id="type" class="small" name="cfg__fp_serveractive_act">
					<?php 
						if($Config->get('fp_serveractive_act') == 1)
						{ $e_fpsiact = 'selected="selected"'; $e_fpsiact2 = ''; }else{ $e_fpsiact2 = 'selected="selected"'; $e_fpsiact = ''; }
					?>
					<option value="1" <?php echo $e_fpsiact; ?>>Enabled</option>
					<option value="0" <?php echo $e_fpsiact2; ?>>Disabled</option>
				</select>																											
				<p class="field_help">Displays the 'Server Active Accounts' under Server Info. FP Server Info must be Enabled!</p>
			</div>
			
			<div class="field">
				<label for="Site fp_serverchars">Server Characters: </label>
				<select id="type" class="small" name="cfg__fp_serverchars">
					<?php 
						if($Config->get('fp_serverchars') == 1)
						{ $e_fpsic = 'selected="selected"'; $e_fpsic2 = ''; }else{ $e_fpsic2 = 'selected="selected"'; $e_fpsic = ''; }
					?>
					<option value="1" <?php echo $e_fpsic; ?>>Enabled</option>
					<option value="0" <?php echo $e_fpsic2; ?>>Disabled</option>
				</select>																											
				<p class="field_help">Displays the 'Server Characters' under Server Info. FP Server Info must be Enabled!</p>
			</div>
			
			<div class="field">
				<label for="Site fp_server_moreinfo">Server More Info: </label>
				<select id="type" class="small" name="cfg__fp_server_moreinfo">
					<?php 
						if($Config->get('fp_server_moreinfo') == 1)
						{ $e_fpsimi = 'selected="selected"'; $e_fpsimi2 = ''; }else{ $e_fpsimi2 = 'selected="selected"'; $e_fpsimi = ''; }
					?>
					<option value="1" <?php echo $e_fpsimi; ?>>Enabled</option>
					<option value="0" <?php echo $e_fpsimi2; ?>>Disabled</option>
				</select>																											
				<p class="field_help">Displays the 'More Info' under Server Info. FP Server Info must be Enabled!</p>
			</div>
			<br />
			
			<!-- Email Settings -->
			<table>
				<thead>
					<tr>
						<th><center><a name="email"></a>Email Settings</center></th>
					</tr>
				</thead>
			</table>
			<br />
			
			<div class="field">
				<label for="Site email_type">Email Relay Type: </label>
				<select id="type" class="small" name="cfg__email_type">
					<?php 
						if($Config->get('email_type') == 0)
							{ $e_et = ''; $e_et2 = ''; $e_et3 = 'selected="selected"'; }
						elseif($Config->get('flash_display_type') == 1)
							{ $e_et = 'selected="selected"'; $e_et2 = ''; $e_et3 = ''; }
						elseif($Config->get('flash_display_type') == 2)
							{ $e_et = ''; $e_et2 = 'selected="selected"'; $e_et3 = ''; }
					?>
					<option value="2" <?php echo $e_et2; ?>>MTA</option>
					<option value="1" <?php echo $e_et; ?>>MIME</option>
					<option value="0" <?php echo $e_et3; ?>>SMTP</option>
				</select>																											
				<p class="field_help">Learn More <u><a href="http://keyswow.com/forum/">Here</a></u></p>
			</div>
			
			<div class="field">
				<label for="Site email_smtp_host">MTA - SMTP Host: </label>
				<input id="Site email_smtp_host" name="cfg__email_smtp_host" size="10" type="text" class="medium" value="<?php echo $Config->get('email_smtp_host'); ?>" />
				<p class="field_help">MTA Email type only - SMTP host such as "smtp.gmail.com"</p>
			</div>
			
			<div class="field">
				<label for="Site email_smtp_port">MTA - SMTP Port: </label>
				<input id="Site email_smtp_port" name="cfg__email_smtp_port" size="10" type="text" class="xsmall" value="<?php echo $Config->get('email_smtp_port'); ?>" />
				<p class="field_help">MTA Email type only - SMTP port .</p>
			</div>
			
			<div class="field">
				<label for="Site email_use_secure">MTA - Use Secure: </label>
				<select id="type" class="xsmall" name="cfg__email_use_secure">
					<?php 
						if($Config->get('email_use_secure') == 1)
						{ $e_eus = 'selected="selected"'; $e_eus2 = ''; }else{ $e_eus2 = 'selected="selected"'; $e_eus = ''; }
					?>
					<option value="1" <?php echo $e_eus; ?>>Yes</option>
					<option value="0" <?php echo $e_eus2; ?>>No</option>
				</select>																											
				<p class="field_help">MTA Email type only - Use secure port.</p>
			</div>
			
			<div class="field">
				<label for="Site email_smtp_secure">MTA - Secure Type: </label>
				<select id="type" class="xsmall" name="cfg__email_smtp_secure">
					<?php 
						if($Config->get('email_smtp_secure') == 'ssl')
						{ $e_est = 'selected="selected"'; $e_est2 = ''; }else{ $e_est2 = 'selected="selected"'; $e_est = ''; }
					?>
					<option value="ssl" <?php echo $e_est; ?>>SSL</option>
					<option value="tls" <?php echo $e_est2; ?>>TLS</option>
				</select>																											
				<p class="field_help">MTA Email type only - Secure Type</p>
			</div>
			
			<div class="field">
				<label for="Site email_smtp_user">MTA - SMTP User: </label>
				<input id="Site email_smtp_user" name="cfg__email_smtp_user" size="10" type="text" class="medium" value="<?php echo $Config->get('email_smtp_user'); ?>" />
				<p class="field_help">MTA Email type only - SMTP Username .</p>
			</div>
			
			<div class="field">
				<label for="Site email_smtp_pass">MTA - SMTP Pass: </label>
				<input id="Site email_smtp_pass" name="cfg__email_smtp_pass" size="10" type="password" class="medium" value="<?php echo $Config->get('email_smtp_pass'); ?>" />
				<p class="field_help">MTA Email type only - SMTP Password.</p>
			</div>
			<br />
			
			<!-- Paypal Settings -->
			<table>
				<thead>
					<tr>
						<th><center><a name="paypal"></a>Paypal Settings</center></th>
					</tr>
				</thead>
			</table>
			<br />
			
			<div class="field">
				<label for="Site Paypal Email">Site Paypal Email: </label>
				<input id="Site Paypal Email" name="cfg__paypal_email" size="20" type="text" class="medium" value="<?php echo $Config->get('paypal_email'); ?>" />
				<p class="field_help">Enter your Paypal email here.</p>
			</div>
			
			<div class="buttonrow-border">								
				<center><button><span>Update Config</span></button></center>			
			</div>
			</form>
		</div> <!-- .main-content -->	
	</div> <!-- .content -->		
</div> <!-- #main -->