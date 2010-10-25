<?php
// Main Library Class!
class Lib
{
	function versionInfo()
	{
		$version = '3.3.5a';
		$revision = '1';
		$copyright = 'MangosWebSDL written by Steven Wilson, &copy 2010. All Rights Reserved';
		$return = array(
			'version' => $version,
			'revision' => $revision,
			'copyright' => $copyright
			);
		return $return;
	}
}
?>