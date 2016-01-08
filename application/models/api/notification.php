<?php
/**
* Name:  Notification
* @package Notification
* @version 1.0
*/
class Notification extends CI_Model
{
	public $pemPath;
	
	function __construct()
	{
		
		parent::__construct();
		$facebook_config = array(
			'appId'  => FACEBOOK_APP_ID,
			'secret' => FACEBOOK_APP_SECRET,
			);
		$this->load->library('Facebook', $facebook_config);
		
		$this->load->library('twitteroauth');
		
		$this->pemPath = 'assets/include/ck.pem';
	}
	
	/**
	 * Function for sending push notification to iphone
	 *
	 * @access 	public
	 * @param string deviceToken
	 * @param string notification message
	 * @param string status of notification
	 * @return void
	 */
	function sendToIphone($salutation = "", $deviceToken = "", $message = "",$status = 'MissedCheckIn')
	{
		/* Put your private key's passphrase here:*/
		$passphrase = PASSPHRASE;
		
		$ctx = stream_context_create();
		stream_context_set_option($ctx, 'ssl', 'local_cert', $this->pemPath);
		stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
		
		/* Open a connection to the APNS server*/
		$fp = stream_socket_client('ssl://gateway.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
		
		if (!$fp)
		{
			exit("Failed to connect: $err $errstr" . PHP_EOL);
		}
		
		/* Create the payload body*/
		$body['aps'] = array( 'alert' => $message, 'badge' => 1, 'sound' => 'default' );
		
		$body['status'] = $status;
		
		/* Encode the payload as JSON*/
		$payload = json_encode($body);
		
		/* Build the binary notification*/
		$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
		
		/* Send it to the server*/
		$result = fwrite($fp, $msg, strlen($msg));
		
		if (!$result)
		{
			$jsondata['message'] = 'Message not delivered';
			$jsondata['status'] = 0;
		}
		else
		{
			$jsondata['message'] = 'Message successfully delivered';
			$jsondata['status'] = 1;
		}
		
		/* Close the connection to the server*/
		fclose($fp);
	}
	
	/**
	 * Function for sending push message to client
	 *
	 * @access 	public
	 * @param string deviceToken
	 * @param string notification message
	 * @param int status of notification
	 * @return void
	 */
	function sendToAndroid($salutation = "", $deviceToken = "", $message = "",$type = 1)
	{
		/* prep the bundle*/
		$msg = array
		(
			'message' => $message,
			'type' => $type
			);
		
		$fields = array
		(
			'registration_ids' =>  array($deviceToken),
			'data'	=> $msg
			);
		
		$headers = array
		(
			'Authorization: key=' . ANDROID_API_ACCESS_KEY,
			'Content-Type: application/json'
			);
		
		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
		$result = curl_exec($ch );
		curl_close($ch);
		
		if (!$result)
		{
			$jsondata['message'] = 'Message not delivered';
			$jsondata['status'] = 0;
		}
		else
		{
			$jsondata['message'] = 'Message successfully delivered';
			$jsondata['status'] = 1;
		}
		
	}
	
	/**
	 * Post on facebook
	 *
	 * @access 	public
	 * @param string accessToken
	 * @param string Post message 
	 * @return void
	 */
	function postOnFB($accessToken, $message)
	{
		$data = array(
			'message' => $message,
			'access_token' => $accessToken
			);
		$this->facebook->api('/me/feed', 'post', $data);
	}
	
	/**
	 * post on twitter
	 *
	 * @access 	public
	 * @param string authentication token
	 * @param string Twitter secret token
	 * @param string Post message 
	 * @return array
	 */
	function postOnTwitter($oauth_token, $oauth_token_secret, $message)
	{
		$this->connection = $this->twitteroauth->create(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET, $oauth_token,  $oauth_token_secret);

		$data = array(
			'status' => $message,
			);
		$result = $this->connection->post('statuses/update', $data);
		
		return $result;
	}
}
