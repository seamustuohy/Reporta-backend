<?php defined('BASEPATH') || exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Iteration count
|--------------------------------------------------------------------------
|
| How many iterations of hashing should occur?
|
| Default: 8
|
*/

/*
|--------------------------------------------------------------------------
| Portable hashes
|--------------------------------------------------------------------------
|
| Should the hash be portable?
|
| Default: false
|
*/

/**
* This will get configuration value from settings.xml file
* Get values under "twilio" tag.
*
*/
$xml = simplexml_load_file("application/config/settings.xml");
$xml = json_decode(json_encode($xml), true);
	
$xml['twilio'] = array_map(function($value)
{
	    if(is_array($value) && empty($value))
	    {
		$value = '';
	    }
	    return $value;
}, $xml['bcrypt']);
	
$config = $xml['bcrypt'];



/* End of file bcrypt.php */
/* Location: ./system/application/config/bcrypt.php */