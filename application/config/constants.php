<?php
if ( ! defined('BASEPATH'))
{
            exit('No direct script access allowed');
}

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
$xml = simplexml_load_file("application/config/settings.xml");
$xml = json_decode(json_encode($xml), true);

$xml['constants'] = array_map(function($value)
{
            if(is_array($value) && empty($value))
            {
                $value = '';
            }
            return $value;
}, $xml['constants']);

foreach($xml['constants'] as $key => $value)
{
    define($key, $value);
}
/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('CURRENT_DATETIME',		date('Y-m-d H:i:s'));
define('DOCUMENT_ROOT',		$_SERVER['DOCUMENT_ROOT'].'/');
/* End of file constants.php */
/* Location: ./application/config/constants.php */