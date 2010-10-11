<?php
class RA
{	
	function send_items($items, $amount, $whom)
	{
		global $cfg, $DB;
	}
	
	function send_money($amount, $whom)
	{
		global $cfg, $DB;
	}
	
	function soap_header()
	{
		global $cfg, $DB;
		$remote = array(0,0,0,0);
		if($cfg->get('emulator') == 'mangos')
		{
			$client = new SoapClient(NULL,
			array(
			"location" => "http://".$remote[0].":".$remote[1]."/",
			"uri" => "urn:MaNGOS",
			"style" => SOAP_RPC,
			"login" => $remote[2],
			"password" => $remote[3]
			));
		}
		elseif($cfg->get('emulator') == 'trinity')
		{
			$client = new SoapClient(NULL,
			array(
			"location" => "http://".$remote[0].":".$remote[1]."/",
			"uri" => "urn:TC",
			"style" => SOAP_RPC,
			"login" => $remote[2],
			"password" => $remote[3]
			));
		}
		return $client;
	}
}
?>