<?php
if (!defined('BASEPATH'))
{
	exit('No direct script access allowed');
}

/**
* Name:  Checkin
*
* Description:  This Class contain API function related to chekin, alert, sos, and app unlock
*               Modified Function base on requirement 
*
* @package CheckIn
* @version 1.0
*/

class CheckIn extends CI_Controller
{
	public $bulkdata;
	public $response_message;
	public $logarray;
	/**
	 * Constructor
	 * Check header token if it's not valid then send response with invalid session.
	 * If user status is "lock" then send notification with response 0 to lock app
	 * If token is valid then it will fetch "response and message" based on user's language.
	 *
	 * @access 	public
	 * @public $this->bulkdata = json request by app
	 */
	function __construct()
	{
		header("Content-type: application/json; charset=utf-8");
		parent::__construct();
		$bulkdata = $this->input->get();
		
		$this->load->model('api/checkins');
		$this->load->model('api/users');
		$this->load->model('api/contacts');
		$this->load->model('api/history');
		$this->load->model('api/api');
		$this->load->model('api/common');
		$this->load->model('api/notification');
		$this->load->model('api/mediafunc');
		$this->load->library('bcrypt');
		$this->email->initialize($config);
		$this->bulkdata = $this->api->iwmf_jsondecode($bulkdata['bulkdata']);
		
		if(!isset($this->bulkdata) && count($this->bulkdata) == 0) {
			$postdata = $this->input->post();
			$this->logarray = json_decode($postdata['bulkdata'], TRUE);
			
			$this->bulkdata = $this->api->iwmf_jsondecode($postdata['bulkdata']);
		}

		$method = $this->router->fetch_method();
		$header = getallheaders();
		$key = substr($header['language_code'],0,32);
		$str = substr($header['language_code'],32,strlen($header['language_code'])-45);
		$language = $this->api->AES_Decode($str,$key);
		$language = (isset($language) && $language != '') ? $language : 'EN';
		$checkheader = $this->api->verifyheader($header);
		$language = (isset($language) && $language != '') ? $language : 'EN';
		$this->lang->load('message', $language);
		$this->response_message = $this->lang->language;
		
		if($checkheader['status'] == 0)
		{
			$responsedata['message'] = $this->response_message['FORCE_LOGOUT'];
			$responsedata['status'] = '3';
			/*JSON Data Encode*/
			$this->api->iwmf_jsonencode($responsedata);
			/*JSON Render the Data*/
			$this->api->iwmf_jsonrender();
			exit;
		}
		elseif($checkheader['user_lock_status'] == -1  && $method != 'unlockapp')
		{
			$getuser_data = $this->users->getuserdetailbyid($checkheader['user_id']);
			$message = "App lock By Admin";
			if($getuser_data[0]['device_type'] == '1')
			{
				$this->notification->sendToIphone('', $getuser_data[0]['devicetoken'], $message,'lockapp');
			}
			elseif($getuser_data[0]['device_type'] == '2')
			{
				$this->notification->sendToAndroid('', $getuser_data[0]['devicetoken'], $message,3);
			}
			$responsedata['message'] = $this->response_message['FORCE_LOGOUT'];
			$responsedata['status'] = '3';
			
			/*JSON Data Encode*/
			$this->api->iwmf_jsonencode($responsedata);
			/*JSON Render the Data*/
			$this->api->iwmf_jsonrender();
			exit;
		}
		else
		{
			$this->user_id = $checkheader['user_id'];
			$this->headertoken = $checkheader['headertoken'];
		}
		
		if(isset($this->user_id) && $this->user_id > 0)
		{
			$this->language = $this->users->getuserlanguage($this->user_id)->language_code;
		}
		$this->language = (isset($this->language) && $this->language != '') ? $this->language : 'EN';
		if($language != $this->language)
		{
			$this->lang->is_loaded = array();
			$this->lang->language = array();
			$this->lang->load('message', $this->language);
			$this->response_message = $this->lang->language;
		}
		$this->lang->is_loaded = array();
		$this->lang->language = array();
		$this->lang->load('mail', $this->language);
		$this->mail_template = $this->lang->language;
	}    
	public function index()
	{
	}
	
	/**
	 * This function will use to insert or update checkin data. 
	 * If request have "checkin id" then it will update checkin data else it will insert checkin data and send mail to user's contacts.
	 * @access	public
	 *
	 * @return void
	 */
	public function createcheckin()
	{
		try
		{
			$responsedata = array();
			if(!empty($this->bulkdata))
			{
				$result_avtivecheckin = $this->checkins->getsignoutcheckinlist($this->user_id);
				/* check Active checkin*/
				if(count($result_avtivecheckin) == 0 || (!empty($this->bulkdata['checkin_id'])))
				{
					$contactlist = $this->bulkdata['contactlist'];
					$check_sos_by_user = $this->contacts->getconfirmedsoscontactbyuser($this->user_id);
					/* Check sos contect */
					if($check_sos_by_user)
					{
						$check_sos_contactlist = $this->contacts->getconfirmedsoscontactbyuser($this->user_id, $contactlist);
						if($check_sos_contactlist)
						{
							$checkindata['user_id'] = $this->user_id;
							$checkindata['description'] = $this->bulkdata['description'];
							$checkindata['location'] = $this->bulkdata['location'];
							$checkindata['latitude'] = $this->bulkdata['latitude'];
							$checkindata['longitude'] = $this->bulkdata['longitude'];
							if($this->bulkdata['endtime'] && $this->bulkdata['endtime'] != '')
							{
								$checkindata['endtime'] = $this->bulkdata['endtime'];
								$checkindata['endtime'] =date('Y-m-d H:i:s', (strtotime($checkindata['endtime'])));
							}
							else
							{
								$checkindata['endtime'] = '0000-00-00 00:00:00';
							}
							$checkindata['message_sms'] = $this->bulkdata['message_sms'];
							$checkindata['message_email'] = $this->bulkdata['message_email'];
							$checkindata['message_social'] = $this->bulkdata['message_social'];
							$checkindata['receiveprompt'] = $this->bulkdata['receiveprompt'];
							$checkindata['frequency'] = $this->bulkdata['frequency'];
							$checkindata['status'] = $this->bulkdata['status'];
							$checkindata['fb_token'] = $this->bulkdata['fb_token'];
							$checkindata['twitter_token'] = $this->bulkdata['twitter_token'];
							$checkindata['twitter_token_secret'] = $this->bulkdata['twitter_token_secret'];
							$checkindata['devicetoken'] = (empty($this->bulkdata['devicetoken'])) ? "" : $this->bulkdata['devicetoken'];
							$checkindata['type'] = (empty($this->bulkdata['devicetype'])) ? "" : $this->bulkdata['devicetype'];
							
							$receiveprompt = explode(",", $this->bulkdata['receiveprompt']);
							if((!empty($this->bulkdata['checkin_id'])) && ($this->bulkdata['checkin_id'] != 0))
							{
								if($checkindata['status'] == '0')
								{
									$checkindata['starttime'] = $this->bulkdata['starttime'];
									$checkindata['starttime'] =date('Y-m-d H:i:s', (strtotime($checkindata['starttime'])));
									/*new for nextconfimationtime*/
									$checkindata['nextconfirmationtime'] = (date('Y-m-d H:i:s', (strtotime($checkindata['starttime'])+($checkindata['frequency'] * 60))));
								}
								else
								{
									$checkindata['nextconfirmationtime'] = (date('Y-m-d H:i:s', (strtotime(CURRENT_DATETIME)+($checkindata['frequency'] * 60))));
								}
								$checkindata['laststatustime'] = CURRENT_DATETIME;
								$checkindata['updated_on'] = CURRENT_DATETIME;
								/*update checkin*/
								$this->checkins->updatecheckin($checkindata, $this->bulkdata['checkin_id']);
								$checkin_id = $this->bulkdata['checkin_id'];
								$responsedata['message'] = $this->response_message['UPDATED'];
							}
							else
							{
								$user_detail = $this->users->getuserdetailbyid($this->user_id);
								$checkindata['starttime'] = $this->bulkdata['starttime'];
								$checkindata['starttime'] =date('Y-m-d H:i:s', (strtotime($checkindata['starttime'])));
								/*new for nextconfimationtime*/
								$checkindata['nextconfirmationtime'] = (date('Y-m-d H:i:s', (strtotime($checkindata['starttime'])+($checkindata['frequency'] * 60))));
								$checkindata['laststatustime'] = CURRENT_DATETIME;
								$checkindata['timezone_id'] = $this->bulkdata['timezone_id'];
								$checkindata['username'] = $user_detail[0]['firstname'].' '.$user_detail[0]['lastname'];
								$checkindata['created_on'] = CURRENT_DATETIME;
								$checkindata['updated_on'] = CURRENT_DATETIME;
								$result = $this->checkins->createcheckin($checkindata);
								$checkin_id = $result;
								
								/*brodcast entry*/
								$broadcastdata['user_id'] = $this->user_id;
								$broadcastdata['foreign_id'] = $checkin_id;
								$broadcastdata['table_id'] = '1';
								$broadcastdata['timezone_id'] = $this->bulkdata['timezone_id'];
								$broadcastdata['time'] = $this->bulkdata['time'];
								$broadcastdata['latitude'] = $this->bulkdata['latitude'];
								$broadcastdata['longitude'] = $this->bulkdata['longitude'];
								$broadcastdata['created_on'] = CURRENT_DATETIME;
								/*create broadcast*/
								$this->history->createbroadcast($broadcastdata);
								
								$responsedata['checkin_id'] = $result;
								$responsedata['message'] = $this->response_message['CREATED'];
								$contactlist_array = array();
								if($contactlist != '')
								{
									$contactlist_exp = explode(",", $contactlist);
									for($i=0; $i<count($contactlist_exp); $i++)
									{
										$contactlist_array[] = array(
											'contactlist_id' => $contactlist_exp[$i],
											'foreign_id' => $checkin_id,
											'table_id' => '1'
											);
									}
									$this->checkins->deletecheckincontactlist($checkin_id,'1');
									$this->checkins->insertcheckincontactlist($contactlist_array);
								}
								
								$contactsdata = $this->contacts->contactbycontactlist($contactlist);
								$checkindata['starttime'] = $this->bulkdata['starttime'];
								$checkindata['starttime'] =date('Y-m-d H:i:s', (strtotime($checkindata['starttime'])));
								for($i=0; $i<count($contactsdata); $i++)
								{
									$from = EMAIL_FROM;
									$from_name = EMAIL_NAME;
									$to = $contactsdata[$i]['emails'];

									$subject = $this->mail_template['CREATE_CHECKIN_SUBJECT'];
									$message = $this->mail_template['CREATE_CHECKIN_MESSAGE'];
									$message_string = array("%firstname%", "%lastname%", "%frequency%","%location%");
									$replace_string   = array($user_detail[0]['firstname'], $user_detail[0]['lastname'], $checkindata['frequency'],$checkindata['location']);
									$subject = str_replace($message_string, $replace_string, $subject);
									$message = str_replace($message_string, $replace_string, $message);
									
									$this->common->send_email($from, $from_name, $to, $subject, $message,NULL,$this->language);
								}
								
								/*SMS*/
								if(in_array("2", $receiveprompt))
								{
									$message = $this->mail_template['CREATE_CHECKIN_SMS'];
									$message_string = array("%firstname%", "%lastname%", "%frequency%","%location%");
									$replace_string   = array($user_detail[0]['firstname'], $user_detail[0]['lastname'], $checkindata['frequency'],$checkindata['location']);
									$message = str_replace($message_string, $replace_string, $message);
									$contactsdata = $this->contacts->contactbycontactlist($contactlist);
									
									for($i=0; $i < count($contactsdata) ; $i++)
									{
										/*send SMS*/
										$this->common->send_sms(SMS_FROM,$contactsdata[$i]['mobile'],$message);
									}
								}
								
								/*Social*/
								if(in_array("3", $receiveprompt))
								{
									$social_message = $user_detail[0]['firstname'].' '.$user_detail[0]['lastname'].' has checked in at '.$checkindata['location'].' for '.$checkindata['description'].'. '.$user_detail[0]['firstname'].'�s next confirmation time is '.gmdate('d/m/Y \a\t H:i T', (strtotime($checkindata['starttime'])+($checkindata['frequency'] * 60))).'.';
									$social_message .= '<br>'.$checkindata['message_social'];
									if($checkindata['fb_token'] != '')
									{
										$this->notification->postOnFB($checkindata['fb_token'], $social_message);
									}
									if($checkindata['twitter_token'] != '' && $checkindata['twitter_token_secret'] != '')
									{
										$this->notification->postOnTwitter($checkindata['twitter_token'], $checkindata['twitter_token_secret'], $social_message);
									}
								}
							}
							
							$checkinhistorydata['checkin_id'] = $checkin_id;
							$checkinhistorydata['status'] = $this->bulkdata['status'];
							$checkinhistorydata['timezone_id'] = $this->bulkdata['timezone_id'];
							$checkinhistorydata['time'] = $this->bulkdata['time'];
							$checkinhistorydata['latitude'] = $this->bulkdata['latitude'];
							$checkinhistorydata['longitude'] = $this->bulkdata['longitude'];
							$checkinhistorydata['created_on'] = CURRENT_DATETIME;
							
							/*create checkin history*/
							$this->history->createcheckinhistory($checkinhistorydata);
							/*update checkin count*/
							$this->users->updatecheckincount($this->user_id);
							$responsedata['status'] = '1';
						}
						else
						{
							$responsedata['message'] = $this->response_message['CONFIRMED_SOS_IN_ACTIVE_CIRCLE'];
							$responsedata['status'] = '0';
						}
					}
					else
					{
						$responsedata['message'] = $this->response_message['SOS_APP_LOCK'];
						$responsedata['status'] = '0';
					}
				}
				else
				{
					$responsedata['message'] = $this->response_message['CLOSEPRECHECKIN'];
					$responsedata['status'] = '0';
				}
			}
			else
			{
				$responsedata['message'] = $this->response_message['INVALID_PARAMS'];
				$responsedata['status'] = '0';
			}
		}
		catch(Exception $e)
		{
			$responsedata = array(
				'message' 	=> $e->getMessage(),
				'status' 	=> '0'
				);
		}
		
		if($responsedata['status'] == 1)
		{
			$responsedata['headertoken'] = $this->headertoken;
		}
		
        /*JSON Data Encode*/
		$this->api->iwmf_jsonencode($responsedata);
        /*JSON Render the Data*/
		$this->api->iwmf_jsonrender();
	}
	/**
	 * This function will use for update status('0'=>'Pending','1'=>'Started','2'=>'Confirmed','3'=>'Deleted','4'=>'Closed','5'=>'Missed') of checkin. 
	 * It will check password to close checkin.
     * If user Confirmed their checkin then update status as well as next Confirmation time
	 * @access	public
	 *
	 * @return void
	 */
	public function updatecheckinstatus()
	{
		try
		{
			
			$responsedata = array();
			if(!empty($this->bulkdata))
			{
				$checkindata['id'] = $this->bulkdata['checkin_id'];
				$checkindata['status'] = $this->bulkdata['status'];
				$checkindata['laststatustime'] = CURRENT_DATETIME;
				$contactsdata = array();
				$checkincontectlist = $this->contacts->getcheckincontectlist($checkindata['id']);
				if($checkincontectlist[0]['contactlist'])
				{
					$contactsdata = $this->contacts->contactbycontactlist($checkincontectlist[0]['contactlist']);
					
				}
				$result = $this->checkins->getcheckinbyid($checkindata['id']);
				/* 3-cancle checkin, 4-close checkin*/
				if($checkindata['status'] == '3' || $checkindata['status'] == '4')
				{
					$user_id = $this->user_id;
					$password = $this->bulkdata['password'];
					
					if($user_id != '' && $password != '')
					{
						$result_user_data = $this->users->getuserdetail($user_id);
						$salt_password = $password.$result_user_data[0]['salt'];
						$password = md5($password.$result_user_data[0]['salt']);
						$hash_password = $this->bcrypt->check_password($salt_password, $result_user_data[0]['password']);
						if($password != $result_user_data[0]['password'] && (!$hash_password))
						{
							$responsedata['message'] = $this->response_message['AUTHFAIL'];
							$responsedata['status'] = '0';
							
							/*JSON Data Encode*/
							$this->api->iwmf_jsonencode($responsedata);
				    /*JSON Render the Data*/
							$this->api->iwmf_jsonrender();
							exit;
						}
					}
					else
					{
						$responsedata['message'] = $this->response_message['AUTHFAIL'];
						$responsedata['status'] = '0';
						
						/*JSON Data Encode*/
						$this->api->iwmf_jsonencode($responsedata);
						/*JSON Render the Data*/
						$this->api->iwmf_jsonrender();
						
						exit;
						
					}
				}
				
				if($result[0]['status'] == 5)
				{
					$responsedata['message'] = $this->response_message['CHECKIN_ALREADY_MISSED'];
					$responsedata['status'] = '2';
				}
				else if ($result[0]['status'] == 4 || $result[0]['status'] == 3 )
				{
					$responsedata['message'] = $this->response_message['CHECKIN_ALREADY_CLOSED'];
					$responsedata['status'] = '2';
				}
				else
				{
					/* 3-cancle checkin, 4-close checkin*/
					if($checkindata['status'] == '3' || $checkindata['status'] == '4')
					{
						$user_id = $this->user_id;
						$password = $this->bulkdata['password'];
						
						if($user_id != '' && $password != '')
						{
							$userdetail = $this->users->getuserdetail($user_id);
							$salt_password = $password.$result_user_data[0]['salt'];
							$password = md5($password.$userdetail[0]['salt']);
							$hash_password = $this->bcrypt->check_password($salt_password, $result_user_data[0]['password']);
							if($password == $userdetail[0]['password'] || $hash_password)
							{
								/*update checkin*/
								$this->checkins->updatecheckin($checkindata, $checkindata['id']);
								
								/* notification mail  for close checkin to contacts*/
								$checkin_data = $this->checkins->getcheckinbyid($checkindata['id']);
								$receiveprompt = explode(",", $checkin_data[0]['receiveprompt']);
								$from = EMAIL_FROM;
								$from_name = EMAIL_NAME;
								
								$subject = $this->mail_template['CLOSE_CHECKIN_SUBJECT'];
								$message = $this->mail_template['CLOSE_CHECKIN_MESSAGE'];
								$message_closed_sms = $this->mail_template['CLOSE_CHECKIN_SMS'];
								
								$message_string = array("%firstname%", "%lastname%");
								$replace_string = array($userdetail[0]['firstname'], $userdetail[0]['lastname']);
								$message = str_replace($message_string, $replace_string, $message);
								$message_closed_sms = str_replace($message_string, $replace_string, $message_closed_sms);
								
								for($i=0; $i<count($contactsdata); $i++)
								{
									$this->common->send_email($from, $from_name, $contactsdata[$i]['emails'], $subject, $message,NULL,$userdetail[0]['language_code']);
									/*SMS*/
									if(in_array("2", $receiveprompt))
									{
										/*send SMS*/
										$this->common->send_sms(SMS_FROM,$contactsdata[$i]['mobile'],$message_closed_sms);
									}
									
									/*Social*/
									if(in_array("3", $receiveprompt))
									{
										$social_message = $message_closed_sms ;
										if($checkindata['fb_token'] != '')
										{
											$this->notification->postOnFB($checkindata['fb_token'], $social_message);
										}
										
										if($checkindata['twitter_token'] != '' && $checkindata['twitter_token_secret'] != '')
										{
											$this->notification->postOnTwitter($checkindata['twitter_token'], $checkindata['twitter_token_secret'], $social_message);
										}
									}
								}
								
								$checkinhistorydata['checkin_id'] = $this->bulkdata['checkin_id'];
								$checkinhistorydata['status'] = $this->bulkdata['status'];
								$checkinhistorydata['timezone_id'] = $this->bulkdata['timezone_id'];
								$checkinhistorydata['time'] = $this->bulkdata['time'];
								$checkinhistorydata['latitude'] = $this->bulkdata['latitude'];
								$checkinhistorydata['longitude'] = $this->bulkdata['longitude'];
								$checkinhistorydata['created_on'] = CURRENT_DATETIME;
								
                                /*create checkin history*/
								$this->history->createcheckinhistory($checkinhistorydata);
								
								$responsedata['message'] = $this->response_message['UPDATED'];
								$responsedata['status'] = '1';
							}
							else
							{
								$responsedata['message'] = $this->response_message['AUTHFAIL'];
								$responsedata['status'] = '0';
							}
						}
						else
						{
							$responsedata['message'] = $this->response_message['INVALID_PARAMS'];
							$responsedata['status'] = '0';
						}
					}
					/* 1-started checkin*/
					elseif($checkindata['status'] == '1')
					{
						$checkindata['starttime'] = $this->bulkdata['time'];
						$checkindata['starttime'] =date('Y-m-d H:i:s', (strtotime($checkindata['starttime'])));
						
						$checkindata['nextconfirmationtime'] = (date('Y-m-d H:i:s', (strtotime($checkindata['starttime'])+($result[0]['frequency'] * 60))));
						
                        /*update checkin*/
						$this->checkins->updatecheckin($checkindata, $checkindata['id']);
						
						$checkinhistorydata['checkin_id'] = $this->bulkdata['checkin_id'];
						$checkinhistorydata['status'] = $this->bulkdata['status'];
						$checkinhistorydata['timezone_id'] = $this->bulkdata['timezone_id'];
						$checkinhistorydata['time'] = $this->bulkdata['time'];
						$checkinhistorydata['latitude'] = $this->bulkdata['latitude'];
						$checkinhistorydata['longitude'] = $this->bulkdata['longitude'];
						$checkinhistorydata['created_on'] = CURRENT_DATETIME;
						
                        /*create checkin history*/
						$this->history->createcheckinhistory($checkinhistorydata);
						
						$responsedata['message'] = $this->response_message['UPDATED'];
						$responsedata['status'] = '1';
					}
					/* 2-Confirmed checkin*/
					elseif($checkindata['status'] == '2')
					{
						$frequency = $result[0]['frequency'] * 60;
						$checkindata['nextconfirmationtime'] = (date('Y-m-d H:i:s', ( strtotime(CURRENT_DATETIME) + $frequency)));
						if(isset($this->bulkdata['ismadeanychanges']) && $this->bulkdata['ismadeanychanges'] == 1)
						{
							$checkindata['description'] = $this->bulkdata['description'];
							$checkindata['location'] = $this->bulkdata['location'];
							$checkindata['latitude'] = $this->bulkdata['latitude'];
							$checkindata['longitude'] = $this->bulkdata['longitude'];
							if($checkindata['endtime'] && $checkindata['endtime'] != '')
							{
								$checkindata['endtime'] = $this->bulkdata['endtime'];
							}
							else
							{
								$checkindata['endtime'] = '0000-00-00 00:00:00';
							}
							$checkindata['message_sms'] = $this->bulkdata['message_sms'];
							$checkindata['message_email'] = $this->bulkdata['message_email'];
							$checkindata['message_social'] = $this->bulkdata['message_social'];
							$checkindata['receiveprompt'] = $this->bulkdata['receiveprompt'];
							$checkindata['frequency'] = $this->bulkdata['frequency'];
							$checkindata['status'] = $this->bulkdata['status'];
							$checkindata['fb_token'] = $this->bulkdata['fb_token'];
							$checkindata['twitter_token'] = $this->bulkdata['twitter_token'];
							$checkindata['twitter_token_secret'] = $this->bulkdata['twitter_token_secret'];
							
							$frequency = $checkindata['frequency'] * 60;
							$checkindata['nextconfirmationtime'] = (date('Y-m-d H:i:s', ( strtotime(CURRENT_DATETIME) + $frequency)));
						}
                        /*update checkin*/
						$this->checkins->updatecheckin($checkindata, $checkindata['id']);
						
						/* notification mail  for close checkin to contacts*/
						$user_id = $this->user_id;
						$userdetail = $this->users->getuserdetail($user_id);
						$from = EMAIL_FROM;
						$from_name = EMAIL_NAME;
						$frequency = $frequency / 60;
						
						$subject = $this->mail_template['SOS_REQUEST_SUBJECT'];
						$message = $this->mail_template['SOS_REQUEST_MESSAGE'];
						
						$message_string = array("%firstname%", "%lastname%", "%frequency%","%location%");
						$replace_string   = array($userdetail[0]['firstname'], $userdetail[0]['lastname'], $frequency,$checkindata['location']);
						$subject = str_replace($message_string, $replace_string, $subject);
						$message = str_replace($message_string, $replace_string, $message);
						
						for($i=0; $i<count($contactsdata); $i++)
						{  
							$this->common->send_email($from, $from_name, $contactsdata[$i]['emails'], $subject, $message,NULL,$this->language);
						}
						
						$checkinhistorydata['checkin_id'] = $this->bulkdata['checkin_id'];
						$checkinhistorydata['status'] = $this->bulkdata['status'];
						$checkinhistorydata['timezone_id'] = $this->bulkdata['timezone_id'];
						$checkinhistorydata['time'] = !empty($this->bulkdata['time'])?$this->bulkdata['time']:'0000-00-00 00:00:00';
						$checkinhistorydata['latitude'] = $this->bulkdata['latitude'];
						$checkinhistorydata['longitude'] = $this->bulkdata['longitude'];
						$checkinhistorydata['created_on'] = CURRENT_DATETIME;
                        /*create checkin history*/
						$this->history->createcheckinhistory($checkinhistorydata);
						
						$responsedata['message'] = $this->response_message['UPDATED'];
						$responsedata['status'] = '1';
					}
					else
					{
						$responsedata['message'] = $this->response_message['INVALID_PARAMS'];
						$responsedata['status'] = '0';
					}
				}
			}
			else
			{
				$responsedata['message'] = $this->response_message['INVALID_PARAMS'];
				$responsedata['status'] = '0';
			}
		}
		catch(Exception $e)
		{
			$responsedata = array(
				'message' 	=> $e->getMessage(),
				'status' 	=> '0'
				);
		}
		
		if($responsedata['status'] == 1)
		{
			$responsedata['headertoken'] = $this->headertoken;
		}
		
		/*JSON Data Encode*/
		$this->api->iwmf_jsonencode($responsedata);
        /*JSON Render the Data*/
		$this->api->iwmf_jsonrender();
	}
	/**
	 * This function will use for Checkin data
	 *
	 * @access	public
	 *
	 * @return void
	 */
	public function getcheckin()
	{
		try
		{
			$responsedata = array();
			if(!empty($this->bulkdata))
			{
				$checkin_id = $this->bulkdata['checkin_id'];
				$result = $this->checkins->getcheckin($checkin_id);
				if(isset($result) && count($result) > 0)
				{
					$responsedata['data'] = $result;
					$responsedata['message'] = $this->response_message['LISTED'];
					$responsedata['status'] = '1';
				}
				else
				{
					$responsedata['message'] = $this->response_message['NODATA'];
					$responsedata['status'] = '0';
				}
			}
			else
			{
				$responsedata['message'] = $this->response_message['INVALID_PARAMS'];
				$responsedata['status'] = '0';
			}
		}
		catch(Exception $e)
		{
			$responsedata = array(
				'message' 	=> $e->getMessage(),
				'status' 	=> '0'
				);
		}
		
		if($responsedata['status'] == 1)
		{
			$responsedata['headertoken'] = $this->headertoken;
		}
		
		/*JSON Data Encode*/
		$this->api->iwmf_jsonencode($responsedata);
        /*JSON Render the Data*/
		$this->api->iwmf_jsonrender();
	}
	
	/**
	 * This function will use for Creating alert
	 * It will check that user have minimum one SOS enable contact to create alert
	 * After creating alert it will send alert mail to user's contacts.
	 *
	 * @access	public
	 *
	 * @return void
	 */
	public function sendalert()
	{
		try
		{
			$responsedata = array();
			
			if(!empty($this->bulkdata))
			{
				$contactlist = $this->bulkdata['contactlist'];
				$check_sos_by_user = $this->contacts->getconfirmedsoscontactbyuser($this->user_id);
				
				if($check_sos_by_user)
				{
					$check_sos_contactlist = $this->contacts->getconfirmedsoscontactbyuser($this->user_id, $contactlist);
					if($check_sos_contactlist)
					{
						$user_detail = $this->users->getuserdetailbyid($this->user_id);
						
						$alertdata['user_id'] = $this->user_id;
						$alertdata['situation'] = $this->bulkdata['situation'];
						$alertdata['description'] = $this->bulkdata['description'];
						$alertdata['location'] = $this->bulkdata['location'];
						$alertdata['latitude'] = $this->bulkdata['latitude'];
						$alertdata['longitude'] = $this->bulkdata['longitude'];
						$alertdata['timezone_id'] = $this->bulkdata['timezone_id'];
						$alertdata['fb_token'] = $this->bulkdata['fb_token'];
						$alertdata['twitter_token'] = $this->bulkdata['twitter_token'];
						$alertdata['twitter_token_secret'] = $this->bulkdata['twitter_token_secret'];
						$alertdata['created_on'] = CURRENT_DATETIME;
						$alertdata['username'] = $user_detail[0]['firstname'].' '.$user_detail[0]['lastname'];
						$result = $this->checkins->createalert($alertdata);
						$alert_id = $result;
						
						$broadcastdata['user_id'] = $this->user_id;
						$broadcastdata['foreign_id'] = $alert_id;
						$broadcastdata['table_id'] = '2';
						$broadcastdata['timezone_id'] = $this->bulkdata['timezone_id'];
						$broadcastdata['time'] = $this->bulkdata['time'];
						$broadcastdata['latitude'] = $this->bulkdata['latitude'];
						$broadcastdata['longitude'] = $this->bulkdata['longitude'];
						$broadcastdata['created_on'] = CURRENT_DATETIME;
                        /*create broadcast*/
						$this->history->createbroadcast($broadcastdata);
						
						/* update count in user table*/
						$this->users->updatealertcount($this->user_id);
						
						if($contactlist != '')
						{
							$contactlist_arr = explode(",", $contactlist);
							for($i=0; $i<count($contactlist_arr); $i++)
							{
								/*table_id 2 for type Alert*/
								$contactlist_array[] = array(
									'contactlist_id' => $contactlist_arr[$i],
									'foreign_id' => $alert_id,
									'table_id' => '2'
									);
							}
							
							$this->checkins->insertcheckincontactlist($contactlist_array);
						}
						
						$date = gmdate('d/m/Y \a\t H:i T', strtotime(CURRENT_DATETIME));
						$subject = $this->mail_template['ALERT_ISSUE_SUBJECT'];
						$message = $this->mail_template['ALERT_ISSUE_MESSAGE'];
						$sms_message = $this->mail_template['ALERT_ISSUE_SMS'];
						
						$message_string = array("%firstname%", "%lastname%", "%datetime%","%location%","%situation%");
						$replace_string   = array($user_detail[0]['firstname'], $user_detail[0]['lastname'], $date,$alertdata['location'],$alertdata['situation']);
						$message = str_replace($message_string, $replace_string, $message);
						$sms_message = str_replace($message_string, $replace_string, $sms_message);
						$social_message = $sms_message;
						if($contactlist != '')
						{
							$contactsdata = $this->contacts->contactbycontactlist($contactlist);
							for($i=0; $i<count($contactsdata); $i++)
							{
								$message = '';
								$from = EMAIL_FROM;
								$from_name = EMAIL_NAME;
								$to = $contactsdata[$i]['emails'];
								if(isset($alertdata['description']) && $alertdata['description'] != '')
								{
									$message .="<br><br>".$alertdata['description'];
								}
								$this->common->send_email($from, $from_name, $to, $subject, $message,NULL,$this->language);
								/*send SMS*/
								$this->common->send_sms(SMS_FROM,$contactsdata[$i]['mobile'],$sms_message);
							}
						}
						/* POST on Social*/
						if($alertdata['fb_token'] != '')
						{
							$this->notification->postOnFB($alertdata['fb_token'], $social_message);
						}
						if($alertdata['twitter_token'] != '' && $alertdata['twitter_token_secret'] != '')
						{
							$this->notification->postOnTwitter($alertdata['twitter_token'], $alertdata['twitter_token_secret'], $social_message);
						}
						
						$responsedata['alert_id'] = $result;
						$responsedata['message'] = $this->response_message['CREATED'];
						$responsedata['status'] = '1';
						
						/*update mail flag if no media in alert*/
						if($this->bulkdata['mediacount'] == 0)
						{
							$alertupdate['is_mediasend'] = 1;
							/*update alert*/
							$this->checkins->updatealert($alertupdate,$responsedata['alert_id']);
						}
					}
					else
					{
						$responsedata['message'] = $this->response_message['CONFIRMED_SOS_IN_ACTIVE_CIRCLE'];
						$responsedata['status'] = '0';
					}
				}
				else
				{
					$responsedata['message'] = $this->response_message['CONFIRMED_SOS_IN_CONTACTS'];
					$responsedata['status'] = '0';
				}
			}
			else
			{
				$responsedata['message'] = $this->response_message['INVALID_PARAMS'];
				$responsedata['status'] = '0';
			}
		}
		catch(Exception $e)
		{
			$responsedata = array(
				'message' 	=> $e->getMessage(),
				'status' 	=> '0'
				);

		}
		
		if($responsedata['status'] == 1)
		{
			$responsedata['headertoken'] = $this->headertoken;
		}
		
        /*JSON Data Encode*/
		$this->api->iwmf_jsonencode($responsedata);
        /*JSON Render the Data*/
		$this->api->iwmf_jsonrender();
	}
	
	/**
	 * This function will use to send alert or checkin mail with attachment uploaded by user.
	 *
	 * @access	public
	 *
	 * @return void
	 */
	public function sendmailwithmedia()
	{
		try
		{
			$responsedata = array();
			if(!empty($this->bulkdata))
			{
				$user_id = $this->user_id;
				$id = $this->bulkdata['foreign_id'];
				$type = $this->bulkdata['table_id'];
				$all_to = '';
				$result = $this->checkins->getalertbyidwithuser($id,$user_id,$type);
				$user_detail = $this->users->getuserdetailbyid($user_id);
				if($result)
				{
					$result_contactlist =  $this->checkins->getcontactlistbyalert($id,$type);
					$contactsdata = $this->contacts->contactbycontactlist($result_contactlist[0]['contactlist']);
					$result_media = $this->mediafunc->getmediabycheckinwithtable($id,$type);
					$from = EMAIL_FROM;
					$from_name = EMAIL_NAME;
					/* For alert mail with media*/
					if($type == '2')
					{
						$alertupdate['is_mediasend'] = 1;
						/* update alert*/
						$this->checkins->updatealert($alertupdate,$id);
						
						$date = gmdate('d/m/Y \a\t H:i T', strtotime(CURRENT_DATETIME));
						$subject = $this->mail_template['ALERT_ISSUE_SUBJECT'];
						$message = $this->mail_template['ALERT_ISSUE_MESSAGE'];
						$message_string = array("%firstname%", "%lastname%", "%datetime%","%location%","%situation%");
						$replace_string   = array($user_detail[0]['firstname'], $user_detail[0]['lastname'], $date,$result[0]['location'],$result[0]['situation']);
						$message = str_replace($message_string, $replace_string, $message);
						
						if(isset($result[0]['description']) && $result[0]['description'] != '')
						{
							$message .="<br><br>".$result[0]['description'];
						}
					}
					/* for create checkin*/
					elseif($type == '1')
					{
						
						$subject = $this->mail_template['SOS_REQUEST_SUBJECT'];
						$message = $this->mail_template['SOS_REQUEST_MESSAGE'];
						$message_string = array("%firstname%", "%lastname%", "%frequency%","%location%");
						$replace_string   = array($user_detail[0]['firstname'], $user_detail[0]['lastname'], $result[0]['frequency'],$result[0]['location']);
						$subject = str_replace($message_string, $replace_string, $subject);
						$message = str_replace($message_string, $replace_string, $message);
					}
					
					if(count($contactsdata) > 0)
					{
						$all_to = $contactsdata[0]['emails'];
					}
					for($i=1; $i<count($contactsdata); $i++)
					{
						$all_to .= ",".$contactsdata[$i]['emails'];
					}
					$this->common->send_email($from, $from_name, $all_to, $subject, $message, $result_media,$this->language);
					$responsedata['message'] = $this->response_message['EMAIL_SEND'];
					$responsedata['status'] = '1';
				}
				else
				{
					$responsedata['message'] = $this->response_message['NODATA'];
					$responsedata['status'] = '0';
				}
			}
			else
			{
				$responsedata['message'] = $this->response_message['INVALID_PARAMS'];
				$responsedata['status'] = '0';
			}
		}
		catch(Exception $e)
		{
			$responsedata = array(
				'message' 	=> $e->getMessage(),
				'status' 	=> '0'
				);
		}
		
		if($responsedata['status'] == 1)
		{
			$responsedata['headertoken'] = $this->headertoken;
		}
		
        /*JSON Data Encode*/
		$this->api->iwmf_jsonencode($responsedata);
        /*JSON Render the Data*/
		$this->api->iwmf_jsonrender();
	}

	/**
	 * This function will use to Create SOS
	 * It will check that user have minimum one SOS enable contact to create sos
	 * After creating sos it will send otp mail
	 *
	 * @access	public
	 *
	 * @return void
	 */
	public function sos()
	{
		try
		{
			$responsedata = array();
			if(!empty($this->bulkdata))
			{
				$sosdata['user_id'] = $this->user_id;
				$sosdata['timezone_id'] = $this->bulkdata['timezone_id'];
				$sosdata['time'] = $this->bulkdata['time'];
				$sosdata['latitude'] = $this->bulkdata['latitude'];
				$sosdata['longitude'] = $this->bulkdata['longitude'];
				$sosdata['created_on'] = CURRENT_DATETIME;
				$user_id = $sosdata['user_id'];
				$contactsdata = $this->contacts->getsoscontactbyuser($sosdata['user_id']);
				
				if($contactsdata)
				{
					$result = $this->history->createsos($sosdata);
					$sos_id = $result;
					$broadcastdata['user_id'] = $this->user_id;
					$broadcastdata['foreign_id'] = $sos_id;
					$broadcastdata['table_id'] = '3';
					$broadcastdata['timezone_id'] = $this->bulkdata['timezone_id'];
					$broadcastdata['time'] = $this->bulkdata['time'];
					$broadcastdata['latitude'] = $this->bulkdata['latitude'];
					$broadcastdata['longitude'] = $this->bulkdata['longitude'];
					$broadcastdata['created_on'] = CURRENT_DATETIME;
					/*create broadcast*/
					$this->history->createbroadcast($broadcastdata);
					
					$user_update['status'] = -1;
					/*update user*/
					$this->users->updateuser($user_update, $sosdata['user_id']);
					
					$user_detail = $this->users->getuserdetailbyid($sosdata['user_id']);
					$contactsdata = $this->contacts->getsoscontactbyuser($sosdata['user_id']);
					
					for($i=0; $i<count($contactsdata); $i++)
					{
						$from = EMAIL_FROM;
						$from_name = EMAIL_NAME;
						$to = $contactsdata[$i]['emails'];
						$link = "<a href = ".site_url() ."otpgenerator?uid=".$this->common->encode($broadcastdata['user_id'])."&cid=".$this->common->encode($contactsdata[$i]['contact_id'])."  mc:disable-tracking >click here</a>";
						
						$subject_appunlock = $this->mail_template['APP_UNLOCK_SUBJECT'];
						$message_appunlock = $this->mail_template['APP_UNLOCK_MESSAGE'];
						$message_string = array("%firstname%", "%lastname%", "%link%");
						$replace_string   = array($user_detail[0]['firstname'], $user_detail[0]['lastname'], $link);
						$message_appunlock = str_replace($message_string, $replace_string, $message_appunlock);
						$subject_sos = $this->mail_template['SOS_ISSUED_SUBJECT'];
						$message_sos = $this->mail_template['SOS_ISSUED_MESSAGE'];
						$message_string = array("%firstname%", "%lastname%");
						$replace_string   = array($user_detail[0]['firstname'], $user_detail[0]['lastname']);
						$message_sos = str_replace($message_string, $replace_string, $message_sos);
						$sms_data = $this->mail_template['SOS_ISSUED_SMS'];
						$sms_data = str_replace($message_string, $replace_string, $sms_data);
						
						$this->common->send_email($from, $from_name, $to, $subject_sos, $message_sos,NULL,$this->language);	    
						$this->common->send_email($from, $from_name, $to, $subject_appunlock, $message_appunlock,NULL,$this->language);
						
						/*send SMS*/
						$this->common->send_sms(SMS_FROM,$contactsdata[$i]['mobile'],$sms_data);
					}
					/*and closc any active checkin for this users*/
					$result_avtivecheckin = $this->checkins->getsignoutcheckinlist($user_id);
					if(isset($result_avtivecheckin) && count($result_avtivecheckin)>0)
					{
						$checkindata['status'] = 3;
						/* update checkin*/
						$this->checkins->updatecheckin($checkindata, $result_avtivecheckin[0]['id']);
						
						$checkinhistorydata['checkin_id'] = $result_avtivecheckin[0]['id'];
						$checkinhistorydata['status'] = $result_avtivecheckin[0]['status'];
						$checkinhistorydata['timezone_id'] = $result_avtivecheckin[0]['timezone_id'];
						$checkinhistorydata['time'] = !empty($result_avtivecheckin[0]['time'])?$result_avtivecheckin[0]['time']:'0000-00-00 00:00:00';
						$checkinhistorydata['latitude'] = $result_avtivecheckin[0]['latitude'];
						$checkinhistorydata['longitude'] = $result_avtivecheckin[0]['longitude'];
						$checkinhistorydata['created_on'] = CURRENT_DATETIME;
						
                        /* create checkin history*/
						$this->history->createcheckinhistory($checkinhistorydata);
					}
					$responsedata['sos_id'] = $sos_id;
					$responsedata['message'] = $this->response_message['CREATED'];
					$responsedata['status'] = '1';
				}
				else
				{
					$responsedata['message'] = $this->response_message['SOS_APP_LOCK'];
					$responsedata['status'] = '0';
				}
			}
			else
			{
				$responsedata['message'] = $this->response_message['INVALID_PARAMS'];
				$responsedata['status'] = '0';
			}
		}
		catch(Exception $e)
		{
			$responsedata = array(
				'message' 	=> $e->getMessage(),
				'status' 	=> '0'
				);
		}
		
		if($responsedata['status'] == 1)
		{
			$responsedata['headertoken'] = $this->headertoken;
		}

        /*JSON Data Encode*/
		$this->api->iwmf_jsonencode($responsedata);
        /*JSON Render the Data*/
		$this->api->iwmf_jsonrender();
	}
	
	/**	 
	 * This function will use to unlock the app
	 * Need verification of user password as well as otp generated by one of it's contact  	
	 * @access	public
	 * @return void
	 */
	public function unlockapp()
	{
		try
		{
			$responsedata = array();
			if(!empty($this->bulkdata))
			{
				$user_id = $this->user_id;
				$password = $this->bulkdata['password'];
				$otp = $this->bulkdata['otp'];
				$checkin_id = $this->bulkdata['checkin_id'];
				$user_data = $this->users->getuserdetail($user_id);
				if($user_data[0]['lock_admin'] == 0)
				{
					$salt_password = $password.$user_data[0]['salt'];
					$password = md5($password.$user_data[0]['salt']);
					$hash_password = $this->bcrypt->check_password($salt_password, $user_data[0]['password']);
					$otpstatus = $this->users->unlockappauthbyuserid($user_id,"",$otp);
					
					if(($password == $user_data[0]['password'] || $hash_password == TRUE) && count($otpstatus)>0)
					{
						/*update otp status*/
						$this->users->updateotpstatus($user_id,$otp,$checkin_id);
						$responsedata['message'] = $this->response_message['AUTHENTICATED'];
						$responsedata['status'] = '1';
					}
					else
					{
						$responsedata['message'] = $this->response_message['AUTHFAIL'];
						$responsedata['status'] = '0';
					}
				}
				else
				{
					$responsedata['message'] = 'App is lock by admin.';
					$responsedata['status'] = '0';
				}
			}
			else
			{
				$responsedata['message'] = $this->response_message['INVALID_PARAMS'];
				$responsedata['status'] = '0';
			}
		}
		catch(Exception $e)
		{
			$responsedata = array(
				'message' 	=> $e->getMessage(),
				'status' 	=> '0'
				);
		}
		
		if($responsedata['status'] == 1)
		{
			$responsedata['headertoken'] = $this->headertoken;
		}
		
        /*JSON Data Encode*/
		$this->api->iwmf_jsonencode($responsedata);
        /*JSON Render the Data*/
		$this->api->iwmf_jsonrender();
	}
}