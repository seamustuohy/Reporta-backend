<?php

/**
* Name:  Common
* @package Common
* @version 1.0
*/
class Common extends CI_Model
{
	public $responsedata = array();
	public $bulkdata     = array();
	/**
	 * Send email
	 *
	 * @access 	public
	 * @param string	 from email id
	 * @param string from name id
	 * @param string to email id
	 * @param string email subject
	 * @param string email message 
	 * @param array attachment file
	 * @param string language of mail
	 * @return void
	 */
	public function send_email($from, $from_name, $to,$subject,$message,$media = NULL,$language_code = '')
        {
        
		$this->load->library('email');
		
		$this->lang->is_loaded = array();
		$this->lang->language = array();
		$this->lang->load('mail', $this->language);
		$this->footer = $this->lang->language;

		$message = $message.$this->footer['MAIL_FOOTER'];
		
		$config['protocol']    = $this->config->item('protocol');
		$config['smtp_host']    = $this->config->item('smtp_host');
		$config['smtp_port']    = $this->config->item('smtp_port');
		$config['smtp_user']    = $this->config->item('smtp_user');
		$config['smtp_pass']    = $this->config->item('smtp_pass');
		$config['charset']    = 'utf-8';
		$config['newline']    = "\r\n";
		$config['mailtype'] = $this->config->item('mailtype');
		$config['validation'] = TRUE;
		/* bool whether to validate email or not*/
		
		$this->email->initialize($config);
		$this->email->from($from, $from_name);
		$this->email->to($to);
		
		$this->email->subject($subject);
		$folder = '';
		
		for($i=0; $i < count($media);$i++)
		{
		    if($media[$i]['mediatype'] == '1')
		    {
			    $folder='assets/uploads/audio/';
		    }
		    elseif($media[$i]['mediatype'] == '2')
		    {
			    $folder='assets/uploads/video/';
		    }
		    elseif($media[$i]['mediatype'] == '3')
		    {
			    $folder='assets/uploads/picture/';
		    }
		    $file = $folder . $media[$i]['medianame'];
		    if(file_exists($file) )
		    {
			$this->email->attach($file);
		    }
			
		}
		$this->email->message($message);
		$this->email->send();
		$this->email->clear('TRUE');
		
		
	}
	
	
	/**
	 * Send sms
	 *
	 * @access 	public
	 * @param string from phone number
	 * @param string phone number
	 * @param string SMS message 
	 * @return array
	 */
	public function send_sms($from,$to,$message)
	{
		$this->load->library('twilio');
		$to_array = explode(',', $to);
		$smsresult='';
		for($i=0;$i<count($to_array);$i++)
		{
			$response = $this->twilio->sms($from, $to_array[$i], $message);
			
			if($response->IsError)
			{
				$smsresult .= ' Error: phone_no: '.$to_array[$i].'  ' . $response->ErrorMessage.'  ---  ';
			}
			else
			{
			    $smsresult .= 'phone_no: '.$to_array[$i].'   Sent message  --  ';
			}
		}
		
		return $smsresult;
	}
	
	/**
	 * encode string
	 *
	 * @access 	public
	 * @param string string to encode
	 * @return string
	 */
	function encode($string)
	{
		$key = KEY;
		$key = sha1($key);
		$strLen = strlen($string);
		$keyLen = strlen($key);
		$j=0;$hash='';
		for ($i = 0; $i < $strLen; $i++)
		{
			$ordStr = ord(substr($string,$i,1));
			if ($j == $keyLen)
			{
				$j = 0;
			}
			$ordKey = ord(substr($key,$j,1));
			$j++;
			$hash .= strrev(base_convert(dechex($ordStr + $ordKey),16,36));
		}
		return $hash;
	}
	/**
	 * decode string
	 *
	 * @access 	public
	 * @param string string to decode
	 * @return string
	 */
	function decode($string)
	{
		$key = KEY;
		$key = sha1($key);
		$strLen = strlen($string);
		$keyLen = strlen($key);
		$j=0;$hash='';
		for ($i = 0; $i < $strLen; $i+=2)
		{
			$ordStr = hexdec(base_convert(strrev(substr($string,$i,2)),36,16));
			if ($j == $keyLen)
			{
				$j = 0;
			}
			
			$ordKey = ord(substr($key,$j,1));
			$j++;
			$hash .= chr($ordStr - $ordKey);
		}
		return $hash;
	}
}
