<?php
if ((int)$cfg->get('allow_registration') == 0)
{
      output_message('error','Registration: Locked');
}
else
{
	if(isset($_POST['step']) && $_POST['step'] == 3 && $allow_reg === true)
	{
		$finalize = finalize();
		if($finalize == TRUE)
		{
			if((int)$cfg->get('require_act_activation'))
			{
				output_message('success', $lang['email_sent_act']);
			}
			else
			{
				output_message('success', $lang['reg_succ'].'<meta http-equiv=refresh content="5;url=index.php?p=account&sub=login">');
			}
		}
	}
	elseif(isset($_POST['step']) && $_POST['step'] == 2 && $allow_reg === true)
	{
		build_CommBox_Header();
?>
	<script type="text/javascript">
	<!--
	var MIN_LOGIN_L = <?php echo $regparams['MIN_LOGIN_L']; ?>;
	var MAX_LOGIN_L = <?php echo $regparams['MAX_LOGIN_L']; ?>;
	var MIN_PASS_L  = <?php echo $regparams['MIN_PASS_L']; ?>;
	var MAX_PASS_L  = <?php echo $regparams['MAX_PASS_L']; ?>;
	var SUCCESS = false;
	function check_login(){
		if (!document.regform.r_login.value || document.regform.r_login.value.length > MAX_LOGIN_L || document.regform.r_login.value.length < MIN_LOGIN_L || !document.regform.r_login.value.match(/^[A-Za-z0-9_]+$/)) {
			$('t_login').innerHTML ='<?php echo sprintf($lang['reg_checklogin'],$regparams['MIN_LOGIN_L'],$regparams['MAX_LOGIN_L']) ?>!';
			$('t_login').show();
			SUCCESS = false;
		} else {
			$('t_login').hide();
			try
			{
				var request = new Ajax.Request(
					SITE_HREF+'index.php?p=ajax&sub=checklogin&nobody=1&ajaxon=1',
					{
						method: 'get',
						parameters: 'q=' + encodeURIComponent($F('r_login')),
						onSuccess: function(reply){
							if (reply.responseText == 'false') {
								$('t_login').innerHTML ='<?php $lang['reg_checkloginex'];?>!';
								$('t_login').show();
								SUCCESS = false;
							} else {
								SUCCESS = true;
							}
						}
					}
				);
			}
			catch (e)
			{
				alert('Error: ' + e.toString());
			}
		}
	}
	function check_pass(){
		if (!document.regform.r_pass.value || document.regform.r_pass.value.length > MAX_PASS_L || document.regform.r_pass.value.length < MIN_PASS_L) {
			$('t_pass').innerHTML = '<?php echo sprintf($lang['reg_checkpass'],$regparams['MIN_PASS_L'],$regparams['MAX_PASS_L']) ?>!';
			$('t_pass').show();
			SUCCESS = false;
		} else {
			$('t_pass').hide();
			SUCCESS = true;
		}
	}
	function check_cpass(){
		if (!document.regform.r_cpass.value || document.regform.r_pass.value!=document.regform.r_cpass.value) {
			$('t_cpass').innerHTML ='<?php echo $lang['reg_checkcpass'];?>!';
			$('t_cpass').show();
			SUCCESS = false;
		} else {
			$('t_cpass').hide();
			SUCCESS = true;
		}
	}
	function check_email(){
		if (document.regform.r_email.value.length < 1 || !document.regform.r_email.value.match(/^[A-Za-z0-9_\-\.]+\@[A-Za-z0-9_\-\.]+\.\w+$/)) {
			$('t_email').innerHTML ='<?php echo $lang['reg_checkemail'];?>!';
			$('t_email').show();
			SUCCESS = false;
		} else {
			$('t_email').hide();
			try
			{
				var request = new Ajax.Request(
					SITE_HREF+'index.php?p=ajax&sub=checkemail&nobody=1&ajaxon=1',
					{
						method: 'get',
						parameters: 'q=' + encodeURIComponent($F('r_email')),
						onComplete: function(reply){
							if (reply.responseText == 'false') {
								$('t_email').innerHTML ='<?php echo $lang['reg_checkemailex'];?>!';
								$('t_email').show();
								SUCCESS = false;
							} else {
								SUCCESS = true;
							}
						}
					}
				);
			}
			catch (e)
			{
				alert('Error: ' + e.toString());
			}
		}
	}
	function check_all(){
		check_login();
		check_pass();
		check_cpass();
		check_email();
		return SUCCESS;
	}
	// -->
	</script>
	<style media="screen" title="currentStyle" type="text/css">
	p.nm, p.wm { 
			margin: 0.5em 0 0.5em 0; 
			padding: 3px; }
			
		p.nm { 
			background-color: #FEF5DA; 
			border-right: 1px solid #D0CBAF;
			border-bottom: 1px solid #D0CBAF; 
			color: #605033; }
		
		p.wm { 
			background-color: #FBD8D7; 
			border-right: 1px solid #DCBFB4;
			border-bottom: 1px solid #DCBFB4; 
			color: #6A0D0B; }
	#regform label {
		display: block;
		margin-top: 1em;
		font-weight: bold;
	}
	p.nm, p.wm { 
		margin: 0px;
		margin-top: 3px;
	}
	</style>
				<div style="padding-left:8px; padding-right: 14px">
					<form method="post" action="index.php?p=account&amp;sub=register" name="regform" id="regform" onsubmit="return check_all();">
						<input type="hidden" name="r_key" value="<?php echo $_POST['r_key'];?>"/>
						<input type="hidden" name="step" value="3"/>
						
						<label for="r_login"><?php echo $lang['username'];?>:</label>
						<input type="text" id="r_login" name="r_login" size="40" maxlength="16" onblur="check_login();"/>
						<p id="t_login" style="display:none;" class="wm"></p>

						<label for="r_pass"><?php echo $lang['pass'];?>:</label>
						<input type="password" id="r_pass" name="r_pass" size="40" maxlength="16" onblur="check_pass();"/>
						<p id="t_pass" style="display:none;" class="wm"></p>

						<label for="r_cpass"><?php echo $lang['cpass'];?>:</label>
						<input type="password" id="r_cpass" name="r_cpass" size="40" maxlength="16" onblur="check_cpass();"/>
						<p id="t_cpass" style="display:none;" class="wm"></p>

						<label for="r_email"><?php echo $lang['email'];?>:</label>
						<input type="text" id="r_email" name="r_email" size="40" maxlength="50" onblur="check_email();"/>
						<p id="t_email" style="display:none;" class="wm"></p>

					<?php 
						if ((int)$cfg->get('reg_secret_questions') == 0)
						{ ?>

							<label for="secretq1"><?php echo $lang['secretq']; ?> 1:</label>
							Q: <select id="secretq1" name="secretq1">
							<option value="Disabled">Disabled</option>
					<?php 
							foreach ($sc_q as $question)
							{?>
								<option value="<?php echo htmlspecialchars($question['question']); ?>"><?php echo $question['question']; ?></option>
					<?php 	} ?>
							</select><br />
							A: <input type="hidden" id="secreta1" name="secreta1" size="40" maxlength="50"/>

							<label for="secretq2"><?php echo $lang['secretq']; ?> 2:</label>
							Q: <select id="secretq2" name="secretq2">
								<option value="Disabled">Disable</option>
					<?php 
							foreach ($sc_q as $question)
							{ ?>
								<option value="<?php echo htmlspecialchars($question['question']); ?>"><?php echo $question['question']; ?></option>
					<?php 	} ?>
							</select><br />
							A: <input type="hidden" id="secreta2" name="secreta2" size="40" maxlength="50"/>
			
					<?php 	
						} ?>

					<?php 
						if ($cfg->get('reg_secret_questions') == 1)
						{ ?>
							<label for="secretq1"><?php echo $lang['secretq']; ?> 1:</label>
							Q: <select id="secretq1" name="secretq1">
								<option value="0">None</option>
					<?php 
							foreach ($sc_q as $question)
							{ ?>
								<option value="<?php echo htmlspecialchars($question['question']); ?>"><?php echo $question['question']; ?></option>
					<?php   } ?>
							</select><br />
							A: <input type="text" id="secreta1" name="secreta1" size="40" maxlength="50"/>

							<label for="secretq2"><?php echo $lang['secretq']; ?> 2:</label>
							Q: <select id="secretq2" name="secretq2">
								<option value="0">None</option>
					<?php 
							foreach ($sc_q as $question)
							{ ?>
								<option value="<?php echo htmlspecialchars($question['question']); ?>"><?php echo $question['question']; ?></option>
					<?php 	} ?>
							</select><br />
							A: <input type="text" id="secreta2" name="secreta2" size="40" maxlength="50"/>
					<?php 
						} ?>

						<label for="r_account_type"><?php echo $lang['exp_select']; ?>:</label>
						<select id="r_account_type" name="r_account_type">
							<option selected="selected" value="2"><?php echo $lang['wotlk'];?></option>
							<option value="1"><?php echo $lang['tbc'];?></option>
							<option value="0"><?php echo $lang['classic'];?></option>
						</select><br /><br />

					<?php
						if ((int)$cfg->get('reg_act_imgvar') == 1)
						{        
							// Initialize random image:
							$captcha = new Captcha;
							$captcha->load_ttf();
							$captcha->make_captcha();
							$captcha->delold();
							$filename = $captcha->filename;
							$privkey = $captcha->privkey;
							$DB->query("INSERT INTO `mw_acc_creation_captcha`(filename, acc_creation_captcha.key) VALUES('$filename','$privkey')");
					?>
							<img src="<?php echo $filename; ?>" alt=""/><br />
							<input type="hidden" name="filename_image" value="<?php echo $filename; ?>"/>
							<b>Type letters above (6 characters)</b>
							<br />
							<input type="text" name="image_key"/><br />
					<?php 	
						} ?>
			
						<br />
						<center>
							<input type='image' class="button" src='<?php echo $currtmp; ?>/images/buttons/createaccount-button2.gif' />
						</center>
					</form>
				</div>
	<?php	build_CommBox_Footer();
		}
		elseif(empty($_POST['step']) && $cfg->get('reg_invite') == 0 && $allow_reg === true)
		{
			build_CommBox_Header();
	?>
			<form method="post" action="index.php?p=account&amp;sub=register">
				<input type="hidden" name="step" value="2"/>
				<input type="hidden" name="r_key" value="0"/>
				<div style="margin:4px;padding:6px 9px 6px 9px;text-align:left;">
					<h2 style="margin:2px;"> <?php echo $lang['rules_agreement'] ?> </h2>
					<div style="color: red"><?php echo $lang['warn_email'] ?></div>
					<br/>
					<?php include("lang/server_rules/".$GLOBALS['user_cur_lang'].".html"); ?>
				</div>
				<div style="margin:4px;padding:6px 9px 0px 9px;text-align:center;">
					<input type='image' class="button" src="<?php echo $currtmp; ?>/images/buttons/disagree-button.gif" name="disagree" value="1" />
					<input type='image' class="button" src='<?php echo $currtmp; ?>/images/buttons/agree-button.gif' />
				</div>
			</form>
	<?php
			build_CommBox_Footer();
		}
		elseif(isset($_POST['step']) && $_POST['step'] == 1 && $cfg->get('reg_invite') == 1)
		{
			if($Account->isValidRegkey($_POST['r_key']) !== TRUE)
			{
				output_message('validation',$lang['bad_reg_key']);
				$allow_reg = false;
				$err_array[] = "Your registration key was invalid. Please check it for typos.";
			}
			else
			{
				build_CommBox_Header();
	?>
				<form method="post" action="index.php?p=account&amp;sub=register">
					<input type="hidden" name="step" value="2"/>
					<input type="hidden" name="r_key" value="<?php echo $_POST['r_key'];?>"/>
					<div style="margin:4px;padding:6px 9px 6px 9px;text-align:left;">
						<h2 style="margin:2px;"> <?php echo $lang['rules_agreement'] ?> </h2>
						<div style="color: red"><?php echo $lang['warn_email'] ?></div>
						<br/>
						<?php include("lang/server_rules/".$GLOBALS['user_cur_lang'].".html"); ?>
					</div>
					<div style="margin:4px;padding:6px 9px 0px 9px;text-align:center;">
						<input type='image' class="button" src="<?php echo $currtmp; ?>/images/buttons/disagree-button.gif" name="disagree" value="1" />
						<input type='image' class="button" src='<?php echo $currtmp; ?>/images/buttons/agree-button.gif' />
					</div>
				</form>
				
	<?php		build_CommBox_Footer();
			}
		}
		elseif(empty($_POST['step']) && $cfg->get('reg_invite') == 1 && $allow_reg === true)
		{
			build_CommBox_Header();
	?>
			<form method="post" action="index.php?p=account&amp;sub=register">
				<input type="hidden" name="step" value="1"/>
				<div style="margin:4px;padding:6px 9px 6px 9px;text-align:left;">
					<b><?php echo $lang['reg_key'];?>:</b> 
					<input type="text" name="r_key" size="45" maxlength="50"/>
				</div>
				<div style="background:none;margin:4px;padding:6px 9px 0px 9px;text-align:left;">
					<input type="submit" class="button" value="<?php echo $lang['next'];?>"/>
				</div>
			</form>
	<?php
			build_CommBox_Footer();
		}

}
?>
