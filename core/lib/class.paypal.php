<?php
class Paypal 
{

	var $isTest = false;
	
	// Adds variables to the form
	function addVar($var,$value)
	{
		$this->vars[$var][0] = $var;
		$this->vars[$var][1] = $value;
	}
	
	// Sets the button image. 1 = Donate, 2 = Buy now, 3 = custom
	function setButtonType($type, $button_image = "")
	{
		switch($type)
		{
			// Donate	
			case 1:
				$this->button = '<input type="image" src="images/donate.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">';
				break;
			// Buy now
			case 2:
				$this->button = '<input type="image" src="images/paynow.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">';
				break;
			// Custom
			case 3:
				$this->button = '<input type="image" src="'.$button_image.'" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">';
		}
		$this->button .= "\n";
	}
	
	// Prints the form with all the hidden posts
	function showForm()
	{
		$url = $this->getAddress();
		$form  = '<form action="https://'.$url.'/cgi-bin/webscr" method="post" target="_blank" style="display:inline;">'."\n";
		foreach($this->vars as $key => $value)
		{
			$form .= '<input type="hidden" name="'.$value[0].'" value="'.$value[1].'">'."\n";
		}				
		$form .= $this->button;    
		$form .= '</form>';
		echo $form;
	}
	
	// Setup the log file
	function setLogFile($logFile)
	{
		$this->logFile = $logFile;
	}
	
	private function writeLog($msg)
	{
		$outmsg = date('Y-m-d H:i:s')." : ".$msg."<br />\n";
		
		$file = fopen($this->logFile,'a');
		fwrite($file,$outmsg);
		fclose($file);
	}
	
	// For the IPN. use to check if payment is valid, and the status
	function checkPayment($_POST)
	{
		$req = 'cmd=_notify-validate';
		foreach($_POST as $key => $value) 
		{
			$value = urlencode(stripslashes($value));
			$req .= "&$key=$value";
		}		
		$url = $this->getAddress();
		
		// Headers to post back to paypal
		$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
		$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
		$fp = fsockopen ('ssl://'.$url, 443, $errno, $errstr, 30);
		
		// $fp = fsockopen ($url, 80, $errno, $errstr, 30);
	
		if (!$fp) 
		{
			return FALSE;
		} 
		else 
		{
			fputs($fp, $header . $req);
			while(!feof($fp)) 
			{
				$res = fgets ($fp, 1024);
				if(strcmp($res, "VERIFIED") == 0) 
				{				
					return TRUE;
				} 
				else 
				{
					if($this->logFile != NULL)
					{
						$this->writeLog($_POST);
					}
					return FALSE;
				}
			}
			fclose ($fp);
		}
		return false;
	}
	
	// For use of sandbox
	function testMode($value)
	{
		$this->isTest = $value;
	}
	

	function getAddress()
	{
		if($this->isTest == TRUE)
		{
			return 'www.sandbox.paypal.com';
		} 
		else 
		{
			return 'www.paypal.com';
		}
	}
}
?>