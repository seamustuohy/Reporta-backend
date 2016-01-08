<?php
if ( ! defined('BASEPATH'))
{
	exit('No direct script access allowed');
}
	/**
	* Name:  Twilio
	*
	* Author: Ben Edmunds
	*		  ben.edmunds@gmail.com
	*         @benedmunds
	*
	* Location:
	*
	* Created:  03.29.2011
	*
	* Description:  Twilio configuration settings.
	*
	*
	*/

	/**
	 * Mode ("sandbox" or "prod")
	 **/
	 
$xml = simplexml_load_file("application/config/settings.xml");
$xml = json_decode(json_encode($xml), true);

$xml['twilio'] = array_map(function($value)
{
	if(is_array($value) && empty($value))
	{
	    $value = '';
	}
	return $value;
}, $xml['twilio']);

$config = $xml['twilio'];
	
/* End of file twilio.php */