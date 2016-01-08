<?php
if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}
/**
* Name:  Checkin
*
* Modified Function base on requirement 
* @package CheckInCron
* @version 1.0
*
*/
class CheckInCron extends CI_Controller
{
    public $bulkdata;
    public $response_message;
    
	/**
	 * Constructor
	 *
	 * @access 	public
	 */
    function __construct()
    {
        header("content-type: text/html; charset=utf-8");
        parent::__construct();
        
        $this->load->model('api/checkincrons');
        $this->load->model('api/notification');
        $this->load->model('api/checkins');
        $this->load->model('api/mediafunc');
        $this->load->model('api/contacts');
        $this->load->model('api/common'); 
        $this->load->model('api/users');
        $this->load->model('api/history');
    }
    
	/**
	 * This cron function will run on every one minute and it will Send mail for missed checkin or reminder
     * IF users checkinconfirm time is larger then 30 minute then it will send two reminder mail 1) before 10 minute 2) before 2 minute of missed checkin time
	 * IF users checkinconfirm time is less then 30 minute then it will send one reminder mail before 2 minute of missed checkin time
	 * If user missed there checkin then it will update their checkin status to "missed" and send notification to lock app also send missed checkin mail to sos enabled contacts 
	 *
	 * @access	public
	 * @return void
	 * @get_checkin_list = list of checkin 
	 */
    public function checkinreminder()
    {
       $this->lang->load('mail', 'EN');
       $this->response_message['EN'] = $this->lang->language;
       
       $this->lang->is_loaded = array();
       $this->lang->language = array();
       $this->lang->load('mail', 'AR');
       $this->response_message['AR'] = $this->lang->language;
       
       $this->lang->is_loaded = array();
       $this->lang->language = array();
       $this->lang->load('mail', 'ES');
       $this->response_message['ES'] = $this->lang->language;
       
       $this->lang->is_loaded = array();
       $this->lang->language = array();
       $this->lang->load('mail', 'FR');
       $this->response_message['FR'] = $this->lang->language;
       
       $this->lang->is_loaded = array();
       $this->lang->language = array();
       $this->lang->load('mail', 'IW');
       $this->response_message['IW'] = $this->lang->language;
       
       $this->lang->is_loaded = array();
       $this->lang->language = array();
       $this->lang->load('mail', 'TR');
       $this->response_message['TR'] = $this->lang->language;
       $get_checkin_list = $this->checkincrons->getremindercheckinlist();
       
       for($i=0; $i<count($get_checkin_list); $i++)
       {
           $nextconfirmationtime = strtotime($get_checkin_list[$i]['nextconfirmationtime']);
           $checkin_id = $get_checkin_list[$i]['id'];
           $currenttime = CURRENT_DATETIME;
           $user_id = $get_checkin_list[$i]['user_id'];
           if($nextconfirmationtime < (strtotime($currenttime) - 60) )
           {
				$updateData['laststatustime'] = CURRENT_DATETIME;
				$updateData['status'] = 5;
				$updateData['checkin_enabled'] = '1';
              
				/*update checkins*/
				$this->checkins->updatecheckin($updateData, $checkin_id);
              
				$user_update['status'] = -1;
				/*update user*/
				$this->users->updateuser($user_update, $user_id);
			}
      }
      
      for($i=0; $i<count($get_checkin_list); $i++)
      {
        $checkin_id = $get_checkin_list[$i]['id'];
        $user_id = $get_checkin_list[$i]['user_id'];
        $nextconfirmationtime = strtotime($get_checkin_list[$i]['nextconfirmationtime']);
        $frequency = $get_checkin_list[$i]['frequency']*60;
        $currenttime = CURRENT_DATETIME;
        $devicetoken = $get_checkin_list[$i]['devicetoken'];
        $receiveprompt = explode(",", $get_checkin_list[$i]['receiveprompt']);
        $message_sms = $get_checkin_list[$i]['message_sms'];
        $message_email = $get_checkin_list[$i]['message_email'];
        $devicetokenstype = $get_checkin_list[$i]['type'];
        $laststatustime = $get_checkin_list[$i]['laststatustime'];
        $location = $get_checkin_list[$i]['location'];
        $timezoneid = $get_checkin_list[$i]['timezone_id'];
        $latitude = $get_checkin_list[$i]['latitude'];
        $longitude = $get_checkin_list[$i]['longitude'];
        
        $fb_token = $get_checkin_list[$i]['fb_token'];
        $twitter_token = $get_checkin_list[$i]['twitter_token'];
        $twitter_token_secret = $get_checkin_list[$i]['twitter_token_secret'];
        $language = $this->users->getuserlanguage($user_id)->language_code;
        $result = $this->users->getuserdetail($user_id);
        
        $firstname = $result[0]['firstname'];
        $lastname = $result[0]['lastname'];
        $user_phone_no = $result[0]['phone'];
        /*Checkin missed*/
        if($nextconfirmationtime < (strtotime($currenttime) - 60) )
        {
            $message = $this->response_message[$language]['MISSCHECKIN_PUSH'];
            
			/*ios notification*/
            if($devicetokenstype == '1')
            {
                /*send notification for ios*/
                $this->notification->sendToIphone('', $devicetoken, $message);
            }
			/*android notification*/
            elseif($devicetokenstype == '2')
            {
                /*send notification for android*/
                $this->notification->sendToAndroid('', $devicetoken, $message);
            }
            
            if($laststatustime == '0000-00-00 00:00:00')
            {
                $date = $get_checkin_list[$i]['starttime'] ;
            }
            else
            {
                $date = $laststatustime ;
            }
            
            $date = gmdate('d/m/Y \a\t H:i T',strtotime($currenttime));
            $email_subject =  $this->response_message[$language]['MISSCHECKIN_MAIL_SUBJECT'];
            $message =  $this->response_message[$language]['MISSCHECKIN_MAIL_MESSAGE'];
            $message_miss_sms = $this->response_message[$language]['MISSCHECKIN_SMS_MESSAGE'];
            $message_string = array("%firstname%", "%lastname%", "%datetime%","%location%");
            $replace_string   = array($firstname, $lastname, $date,$location);
            $message = str_replace($message_string, $replace_string, $message);
            $message_miss_sms = str_replace($message_string, $replace_string, $message_miss_sms);
            
            if(isset($message_email) && $message_email != '')
            {
                $message .= '<br><br>'.$message_email;
            }		
            
            $result_contactlist = $this->checkins->getcheckin($checkin_id);
            $result_contact = $this->contacts->contactbycontactlist($result_contactlist[0]['contactlist']) ;
            $result_media = $this->mediafunc->getmediabycheckinwithtable($checkin_id,1);
            $email_from_name = EMAIL_NAME;
            $email_media = $result_media;
            
            for($j=0; $i < count($result_contact) ; $j++)
            {
              $this->common->send_email(EMAIL_FROM, $email_from_name, $result_contact[$j]['emails'], $email_subject, $message, $email_media,$language);
              if($result_contact[$j]['sos_enabled'] == '1')
              {
                 $link = "<a href = ".site_url() ."otpgenerator?uid=".$this->common->encode($user_id)."&cid=".$this->common->encode($result_contact[$j]['contact_id'])." mc:disable-tracking >click here</a>";
                 
                 $app_lock_email_subject = $this->response_message[$language]['APP_UNLOCK_SUBJECT'];
                 $message_otp = $this->response_message[$language]['APP_UNLOCK_MESSAGE'];
                 
                 $message_string = array("%firstname%", "%lastname%", "%link%");
                 $replace_string   = array($firstname, $lastname, $link);
                 $message_otp = str_replace($message_string, $replace_string, $message_otp);
                 $email_message  = $message_otp;
                 $email_message .= '<br>'.$message_email;
				 /*send mail*/
                 $this->common->send_email(EMAIL_FROM, $email_from_name, $result_contact[$j]['emails'], $app_lock_email_subject, $email_message, $email_media,$language);
             }
         }
         
			/*SMS*/
			if(in_array('2', $receiveprompt))
			{
				$message_miss_sms .= ' '.$message_sms;
				$result_contactlist = $this->checkins->getcheckin($checkin_id);
				$result_contact = $this->contacts->contactbycontactlist($result_contactlist[0]['contactlist']) ;
				for($z=0; $i < count($result_contact) ; $z++)
				{
					$phone_no = explode(",",$result_contact[$z]['mobile']);
					for($j = 0 ; $j < count($phone_no);$j++ )
					{
						/*send SMS*/
						$this->common->send_sms(SMS_FROM,$phone_no[$j],$message_miss_sms);
					}
				}
			}
		 
			if(in_array('3', $receiveprompt))
			{
				$social_message = $this->response_message[$language]['MISSCHECKIN_SMS_MESSAGE'];
				$message_string = array("%firstname%", "%lastname%", "%datetime%","%location%");
				$replace_string   = array($firstname, $lastname, $date,$location);
				$social_message = str_replace($message_string, $replace_string, $social_message);

				if($fb_token != '')
				{
					$this->notification->postOnFB($fb_token, $social_message);
				}
				if($twitter_token != '' && $twitter_token_secret != '')
				{
					$this->notification->postOnTwitter($twitter_token, $twitter_token_secret, $social_message);
				}
			}
            
			/* update mail send flag after mail send*/
            $updatecheckin['is_mailsend'] = 1;
            /*update checkin*/
            $this->checkins->updatecheckin($updatecheckin, $checkin_id);
            
            $checkinhistorydata['checkin_id'] = $checkin_id;
            $checkinhistorydata['status'] = 5;
            $checkinhistorydata['timezone_id'] = $timezoneid;
            $checkinhistorydata['time'] = $currenttime;
            $checkinhistorydata['latitude'] = $latitude;
            $checkinhistorydata['longitude'] = $longitude;
            $checkinhistorydata['created_on'] = CURRENT_DATETIME;
            
            /*add checkin history*/
            $this->history->createcheckinhistory($checkinhistorydata);
            
        }
        elseif(($nextconfirmationtime <= ((strtotime($currenttime))+ 600 ))  &&  ($nextconfirmationtime > ((strtotime($currenttime))+ 540 )) && (!empty($devicetoken)) && (!empty($devicetokenstype))  && $frequency >= (30*60)   )
        {
            /*sent notification for next chakin*/
            $email_from = EMAIL_FROM;
            $email_from_name = EMAIL_NAME;
            $email_to = $user_result[0]['email'];
            $email_media = $this->mediafunc->getmediabycheckinwithtable($checkin_id,1);
            $email_subject_reminder = $this->response_message[$language]['CHECKIN_REMINDER_TEN_SUBJECT'];
            $message_reminder = $this->response_message[$language]['CHECKIN_REMINDER_TEN_MESSAGE'];
            $sms_message_reminder = $this->response_message[$language]['CHECKIN_REMINDER_TEN_SMS'];
            $this->common->send_email($email_from, $email_from_name,$email_to,$email_subject_reminder,$message_reminder,$email_media,$language);
            
			/*Email*/
            if(in_array('2', $receiveprompt))
            {
				/*send SMS*/
				$this->common->send_sms($user_phone_no,$user_phone_no,$sms_message_reminder);
			}
			/*Social*/
          if(in_array('3', $receiveprompt))
          {
            $social_message = $sms_message_reminder;
            if($fb_token != '')
            {
				$this->notification->postOnFB($fb_token, $social_message);
			}
			if($twitter_token != '' && $twitter_token_secret != '')
			{
				$this->notification->postOnTwitter($twitter_token, $twitter_token_secret, $social_message);
			}
		  }
}

elseif(($nextconfirmationtime <= ((strtotime($currenttime))+ 120 ))  &&  ($nextconfirmationtime > ((strtotime($currenttime))+ 60 )) && (!empty($devicetoken)) && (!empty($devicetokenstype))  && $frequency >= (15*60)   )
{
    /*sent notification for next chakin*/
    $email_from = EMAIL_FROM;
    $email_from_name = EMAIL_NAME;
    $email_to = $user_result[0]['email'];
    $email_media = $this->mediafunc->getmediabycheckinwithtable($checkin_id,1);
    $email_subject_reminder = $this->response_message[$language]['CHECKIN_REMINDER_TWO_SUBJECT'];
    $message_reminder = $this->response_message[$language]['CHECKIN_REMINDER_TWO_MESSAGE'];
    $sms_message_reminder = $this->response_message[$language]['CHECKIN_REMINDER_TWO_SMS'];
    $this->common->send_email($email_from, $email_from_name,$email_to,$email_subject_reminder,$message_reminder,$email_media,$language);
    
	/*Email*/
    if(in_array('2', $receiveprompt))
    {
	  /*send SMS*/
      $this->common->send_sms($user_phone_no,$user_phone_no,$sms_message_reminder);
  }
  /*Social*/
  if(in_array('3', $receiveprompt))
  {
    $social_message = $sms_message_reminder;
    if($fb_token != '')
    {
     $this->notification->postOnFB($fb_token, $social_message);
 }
 if($twitter_token != '' && $twitter_token_secret != '')
 {
    $this->notification->postOnTwitter($twitter_token, $twitter_token_secret, $social_message);
}
}
}
}
}

/**
 * This cron function will run on every one minute, it will update status from pending to start as well as start to close.
 * 
 * @access	public
 * @return void
 * @get_checkin_list = list of checkin 
 */
public function checkinststus()
{
    $get_checkin_list = $this->checkincrons->getpendingcheckin();
    /*update status for pending checkin*/
    for($i=0; $i<count($get_checkin_list); $i++)
    {
        $updateData['status'] = 1;
	    /*update checkin*/
        $this->checkins->updatecheckin($updateData,$get_checkin_list[$i]['id'] );
        $checkinhistorydata['checkin_id'] = $get_checkin_list[$i]['id'];
        $checkinhistorydata['status'] = 1;
        $checkinhistorydata['timezone_id'] = $get_checkin_list[$i]['timezone_id'];
        $checkinhistorydata['time'] = CURRENT_DATETIME;
        $checkinhistorydata['latitude'] = $get_checkin_list[$i]['latitude'];
        $checkinhistorydata['longitude'] = $get_checkin_list[$i]['longitude'];
        $checkinhistorydata['created_on'] = CURRENT_DATETIME;
        /*create checkin history*/
        $this->history->createcheckinhistory($checkinhistorydata);
        
    }
    
    $get_checkin_list = $this->checkincrons->getclosecheckin();
    for($i=0; $i<count($get_checkin_list); $i++)
    {
        if(strtotime($get_checkin_list[$i]['endtime']) && $get_checkin_list[$i]['endtime'] != '0000-00-00 00:00:00')
        {
            $updateData['status'] = 4;
            /*update checkin*/
            $this->checkins->updatecheckin($updateData, $get_checkin_list[$i]['id']);
            
            $checkinhistorydata['checkin_id'] = $get_checkin_list[$i]['id'];
            $checkinhistorydata['status'] = 4;
            $checkinhistorydata['timezone_id'] = $get_checkin_list[$i]['timezone_id'];
            $checkinhistorydata['time'] = CURRENT_DATETIME;
            $checkinhistorydata['latitude'] = $get_checkin_list[$i]['latitude'];
            $checkinhistorydata['longitude'] = $get_checkin_list[$i]['longitude'];
            $checkinhistorydata['created_on'] = CURRENT_DATETIME;
            /*create checkin history*/
            $this->history->createcheckinhistory($checkinhistorydata);
        }  
    }   
}
}