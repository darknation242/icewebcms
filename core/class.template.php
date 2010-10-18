<?php
class Template
{	
	public $xml;
	
	// This function sets up what template is going to be used, based on what the user has picked as his/her template
	function Init()
	{
		global $user, $cfg, $DB;
		$template_list = explode(",", $cfg->get('templates'));
		if ( $user['id'] == -1 ) // If user is a guest and not logged in 
		{
			if(isset($_COOKIE['cur_selected_theme'])) // If a cookie is set
			{
				$ct = (int)$_COOKIE['cur_selected_theme'];
				foreach($template_list as $template) 
				{
					$currtmp2[] = $template ;
				}
				$tmple = $currtmp2[$ct] ;
			}
			else
			{
				setcookie("cur_selected_theme", 0, time() + (3600 * 24 * 365));
				foreach($template_list as $template) 
				{
					$currtmp2[] = $template ;
				}
				$tmple = $currtmp2['0'] ;
			}
			$this->slave_tmpl = "templates/".$tmple;
			$this->path = "templates/".$tmple."/template.xml";
			return $this->return_template_info();
		}
		else // If user is logged in
		{
			if(isset($_COOKIE['cur_selected_theme'])) // If there is a cookie set with the theme
			{
				$tmpl_cookienum = (int)$_COOKIE['cur_selected_theme'];
				$tmpl_num = $user['theme'];
				if($tmpl_cookienum !== $tmpl_num) // If the cookie and set theme in DB are not the same, fix that :)
				{
					$DB->query( "UPDATE `account_extend` SET `theme`='$tmpl_cookienum' WHERE `account_id`='$user[id]'");
					$tmpl_num = $tmpl_cookienum;
				}
			}
			else // If a cookie is not set for a theme
			{
				$tmpl_num = $user['theme'];
				setcookie("cur_selected_theme", $tmpl_num, time() + (3600 * 24 * 365));
			}
			foreach($template_list as $template) 
			{
				$currtmp2[] = $template ;
			}
			$tmple = $currtmp2[$tmpl_num] ;
			// If persons current template is no longer available, this resets his template to default
			if($tmple == "")
			{ 
				$tmple = (string)$cfg->get('default_template');
				$DB->query( "UPDATE `account_extend` SET `theme`='0' WHERE `account_id`=?d",$user['id'] );
			}
			$this->slave_tmpl = "templates/".$tmple;
			$this->path = "templates/".$tmple."/template.xml";
			return $this->return_template_info();
		}
	}
	
	// Once the template is decided, we must load the xml that contains the template information, and return it back to the
	// main function
	function return_template_info()
	{
		$this->xml = simplexml_load_file($this->path);
		$ret = array(
			'path' => $this->slave_tmpl, 
			'script' => $this->xml->masterTemplate,
			'name' => $this->xml->name,
			'author' => $this->xml->author,
			'authorEmail' => $this->xml->authorEmail,
			'authorUrl' => $this->xml->authorUrl,
			'copyright' => $this->xml->copyright,
			'license' => $this->xml->license
			);
		return $ret;
	}
}
?>