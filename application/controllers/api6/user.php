<?php
if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}
/**
* Name:  User
*
* Description:  This Class contain API function related to User
*               Modified Function base on requirement 
* @package User
* @version 1.0
*
*/
class User extends CI_Controller
{
    public $bulkdata;
    public $response_message;
    public $logarray;
    
	/**
	 * Constructor
	 * Check header token if it's not valid then send response with invalid session.
	 * If user status is "lock" then send notification with response 0 to lock app
	 * If token is valid then it will fetch "response and message" based on user's language.
	 * @access 	public
	 * @public $bulkdata = json request by app
	 */
    function __construct()
    {
        header("Content-type: application/json; charset=utf-8");
        parent::__construct();
        $bulkdata = $this->input->get();
        
        $this->load->model('api/users');
        $this->load->model('api/api');
        
        $this->load->model('api/notification');
        $this->load->model('api/checkins');
        
        $this->load->model('api/contacts');
        $this->load->model('api/mediafunc');
        
        $this->load->model('api/common');
        $this->load->model('api/history');
        
        $this->load->model('api/checkins');
        $this->load->model('api/checkincrons');
        
        $this->load->model('api/notification');
        $this->load->library('bcrypt');
        
        
        $this->bulkdata = $this->api->iwmf_jsondecode($bulkdata['bulkdata']);
        $method = $this->router->fetch_method();
        $header = getallheaders();
        
        $key = substr($header['language_code'],0,32);
        $str = substr($header['language_code'],32,strlen($header['language_code'])-45);
        $language = $this->api->AES_Decode($str,$key);
        $language = (isset($language) && $language != '') ? $language : 'EN';
        $this->lang->load('message', $language);
        $this->response_message = $this->lang->language;
        if(!isset($this->bulkdata) && count($this->bulkdata) == 0)
        {
            $postdata = $this->input->post();
            $this->logarray = json_decode($postdata['bulkdata'], TRUE);
            
            $this->bulkdata = $this->api->iwmf_jsondecode($postdata['bulkdata']);
        }
        
        if($method == 'resetpassword' || $method == 'getuserdetail' || $method == 'signout'  || ($method == 'createuser' && isset($this->bulkdata['iscreateuser']) && ($this->bulkdata['iscreateuser'] == 0)))
        {
			/*check header token*/
           $header = getallheaders();
           $this->lang->load('message', $language);
           $this->response_message = $this->lang->language;
           $checkheader = $this->api->verifyheader($header);
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
          elseif($checkheader['user_lock_status'] == -1)
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
      }
      

      if(isset($this->user_id) && $this->user_id > 0)
      {
        
        $this->language = $this->users->getuserlanguage($this->user_id)->language_code;
    }
    elseif($this->bulkdata['language_code'])
    {
        $this->language = $this->bulkdata['language_code'] ;
    }
    else
    {
       
       $key = substr($header['language_code'],0,32);
       $str = substr($header['language_code'],32,strlen($header['language_code'])-45);
       $language = $this->api->AES_Decode($str,$key);
       $this->language = $language;
   }
   
   $this->language = (isset($this->language) && $this->language != '') ? $this->language : 'EN';
   if($language != $this->language)
   {
       $this->lang->is_loaded = array();
       $this->lang->language = array();
       $this->lang->load('message', $this->language);
       $this->response_message = $this->lang->language;
   }
   
   $this->lang->load('mail', $this->language);
   $this->mail_template = $this->lang->language;
   
}

public function index()
{
}

 /**
 * This function will use to Create or update User
 * If request have flag "iscreateuser" with value 0 then update user else create user and send welcome mail.
 * 
 * @access	public
 *
 * @return void
 */
public function createuser()
{
    try
    {
        $responsedata = array();
        
        if(!empty($this->bulkdata))
        {
            $userdata['username'] = $this->bulkdata['username'];
            
            $userdata['email'] = $this->bulkdata['email'];
            $userdata['firstname'] = $this->bulkdata['firstname'];
            $userdata['lastname'] = $this->bulkdata['lastname'];
            $userdata['language'] = $this->bulkdata['language'];
            $userdata['phone'] = $this->bulkdata['phone'];
            $userdata['jobtitle'] = $this->bulkdata['jobtitle'];
            $userdata['affiliation_id'] = $this->bulkdata['affiliation_id'];
            $userdata['freelancer'] = $this->bulkdata['freelancer'];
            $userdata['origin_country'] = $this->bulkdata['origin_country'];
            $userdata['working_country'] = $this->bulkdata['working_country'];
            $userdata['sendmail'] = ($this->bulkdata['sendmail'] == 0 ? '0' : '1');
            $userdata['gender'] = $this->bulkdata['gender'];
            $userdata['gender_type'] = $this->bulkdata['gender_type'];
            $userdata['email'] = strtolower($userdata['email']);
            $userdata['username'] = strtolower($userdata['username']);
			/* update user*/
            if(isset($this->bulkdata['iscreateuser']) && ($this->bulkdata['iscreateuser'] == 0) )
            {
                $result_email =  $this->users->getuserdetailbyemail($userdata['email']);
                if($this->user_id <=0)
                {
                  $responsedata['message'] = $this->response_message['FORCE_LOGOUT'];
                  $responsedata['status'] = '3';
              }
              elseif ( empty($userdata['email']) )
              {
                $responsedata['message'] = $this->response_message['FILL_ALL_INFO'];
                $responsedata['status'] = '0';
            }
            else if ( ($result_email[0]['id'] != ($this->user_id)) && count($result_email) > 0)
            {
                /*Sorry, it looks like mailid belongs to an existing account.*/
                $responsedata['message'] = $this->response_message['EMAILEXITS'];
                $responsedata['status'] = '0';
            }
            else
            {
                $userdata['updated_on'] = CURRENT_DATETIME;
				/*updateuser result*/
                $this->users->updateuser($userdata, $this->user_id);
                $result = $this->users->getuserdetailbyid($this->user_id);
                $responsedata['data'] = $result[0];
                $responsedata['data']['headertoken'] = $this->headertoken;
                $responsedata['data']['lockstatus'] = 0;
                $responsedata['data']['checkin_id'] = '0';
                $responsedata['message'] = $this->response_message['UPDATED'];
                $responsedata['status'] = '1';
            }
        }
        else
        {
          $userdata['send_update_repota_email'] = $this->bulkdata['send_update_repota_email'];
          $userdata['language_code'] = $this->bulkdata['language_code'];
          $userdata['password'] = $this->bulkdata['password'];
          $userdata['devicetoken'] = $this->bulkdata['devicetoken'];
          $userdata['device_type'] = $this->bulkdata['devicetype'];
          
          /*new  chake username or email already exists or not*/
          $result = $this->users->getuserdetailbyusername($userdata['username']);
          $result_email =  $this->users->getuserdetailbyemail($userdata['email']);
          
          if ( empty($userdata['username']) || empty($userdata['email']) || empty($userdata['password']) )
          {
            $responsedata['message'] = $this->response_message['FILL_ALL_INFO'];
            $responsedata['status'] = '0';
        }
        else if(isset($result) && count($result) > 0)
        {
            $responsedata['message'] = $this->response_message['USEREXITS'];
            $responsedata['status'] = '0';
        }
        else if (isset($result_email) && count($result_email) > 0)
        {
            /*Sorry, it looks like mailid belongs to an existing account.*/
            $responsedata['message'] = $this->response_message['EMAILEXITS'];
            $responsedata['status'] = '0';
        }
        else
        {
         $userdata['salt'] = $this->api->randomPassword(4);
         $password = $this->bulkdata['password'].$userdata['salt'];
         $userdata['password'] = $this->bcrypt->hash_password($password);
         $userdata['created_on'] = CURRENT_DATETIME;
         $userdata['password_change'] = CURRENT_DATETIME;
         $userdata['last_login_time'] = CURRENT_DATETIME;
         $userdata['app_encryption_key'] = $this->api->randomPassword(16);
         $userdata['islogin'] = 1 ;
         $userdata['status'] = 1 ;
         $userdata['id'] =  microtime(true) * 10000;
         $result = $this->users->createuser($userdata);
         
			/* put password entry in oldpassword*/
         $user_id = $result;
         $oldpass['user_id'] = $user_id;
         $oldpass['password'] = $userdata['password'];
			/*store oldpassword*/
         $this->users->insertoldpassword($oldpass);
         $listdata['user_id'] = $user_id;
         $listdata['circle'] = $this->bulkdata['circle'];
         $listdata['listname'] = $this->bulkdata['listname'];
         $contacts = $this->bulkdata['contacts'];
         $listdata['defaultstatus'] = ($this->bulkdata['defaultstatus'] != '') ? $this->bulkdata['defaultstatus'] : '';
         if(!empty($this->bulkdata['list_id']))
         {
            $listdata['updated_on'] = CURRENT_DATETIME;
            $result = $this->contacts->updatecontactlist($listdata, $this->bulkdata['list_id']);
            $contactlist_id = $this->bulkdata['list_id'];
            $responsedata['message'] = $this->response_message['UPDATED'];
        }
        else
        {
            $listdata['created_on'] = CURRENT_DATETIME;
            $contactlist_id = $this->contacts->createcontactlist($listdata);
        }
        for($z=0; $z<count($contacts); $z++)
        {
            $contactdata['firstname'] = $contacts[$z]['firstname'];
            $contactdata['lastname'] = $contacts[$z]['lastname'];
            $contactdata['mobile'] = $contacts[$z]['mobile'];
            $contactdata['emails'] = strtolower($contacts[$z]['emails']);
            $contactdata['updated_on'] = CURRENT_DATETIME;
            $contactdata['sos_enabled'] = $contacts[$z]['sos_enabled'];
            $contactdata['status'] = (($contacts[$z]['sos_enabled']) == '2' ? '0' : '-1' );
            $associated_id[0] = $contactlist_id;
            if(!empty($contacts[$z]['contact_id']))
            {
                $contact_id = $contacts[$z]['contact_id'];
                $user = $this->users->getuserdetailbycontactlists($associated_id);
                $user_id = $user[0]['user_id'];
                $user_detail = $this->users->getuserdetail($user_id);
                if($contacts[$z]['sos_enabled'] == '2')
                {
                    $contact_detail = $this->contacts->getcontactdetailbyid($contacts[$z]['contact_id']);
                    if($contact_detail[0]['sos_enabled'] == '0')
                    {
                        $email_from = EMAIL_FROM;
                        $email_from_name = EMAIL_NAME;
                        $email_to = $contactdata['emails'];
                        $link = "<a href = ".site_url() ."sosrequest?uid=".$this->common->encode($user_id)."&cid=".$this->common->encode($contact_id)." mc:disable-tracking >click here</a>";
                        $email_subject = $this->mail_template['SOS_REQUEST_SUBJECT'];
                        $email_message = $this->mail_template['SOS_REQUEST_MESSAGE'];
                        $message_string = array("%firstname%", "%lastname%", "%link%");
                        $replace_string   = array($user_detail[0]['firstname'], $user_detail[0]['lastname'], $link);
                        $email_message = str_replace($message_string, $replace_string, $email_message);
                        $email_subject = str_replace($message_string, $replace_string, $email_subject);
                        $this->common->send_email($email_from, $email_from_name, $email_to, $email_subject, $email_message,NULL,$userdata['language_code']);
                        $contactdata['status'] = '0';
                    }
                }
                $contactdata['updated_on'] = CURRENT_DATETIME;
                $result = $this->contacts->updatecontact($contactdata, $this->bulkdata['contact_id']);
                $result_associated_id = $this->contacts->getassociatedbycontactid($contact_id);
                for($i=0; $i<count($result_associated_id); $i++)
                {
                    $old_associated_id[] = $result_associated_id[$i]['contactlist_id'];
                }
                $associated_id_for_insert = array_values(array_diff($associated_id, $old_associated_id));
                for($i=0; $i<count($associated_id_for_insert); $i++)
                {
                    $associated_data['contactlist_id'] = $associated_id_for_insert[$i];
                    $associated_data['contact_id'] = $contact_id;
                    $result = $this->contacts->createassociatedcontact($associated_data);
                }
                $associated_id_for_del = array_values(array_diff($old_associated_id, $associated_id));
                if($associated_id_for_del)
                {
				    /*Remove associated concts*/
                    $this->contacts->deleteassociatedcontact(implode(",",$associated_id_for_del), $contact_id);
                }
                $responsedata['message'] = $this->response_message['UPDATED'];
            }
            else
            {
                $contactdata['created_on'] = CURRENT_DATETIME;
                /*Edit Check duplicate contect. donot create duplicate contect just add in accociat contect*/
                $result = $this->contacts->createcontact($contactdata);
                $contact_id = $result;
                $user = $this->users->getuserdetailbycontactlists($associated_id);
                $user_id = $user[0]['user_id'];
                $user_detail = $this->users->getuserdetail($user_id);
                if($contactdata['sos_enabled'] == '2')
                {
                    $email_from = EMAIL_FROM;
                    $email_from_name = EMAIL_NAME;
                    $email_to = $contactdata['emails'];
                    $email_subject = "Request from ".$user_detail[0]['firstname']." ".$user_detail[0]['lastname'];
                    $link = "<a href = ".site_url() ."sosrequest?uid=".$this->common->encode($user_id)."&cid=".$this->common->encode($contact_id)." mc:disable-tracking >click here</a>";
                    
                    $email_subject = $this->mail_template['SOS_REQUEST_SUBJECT'];
                    $email_message = $this->mail_template['SOS_REQUEST_MESSAGE'];
                    $message_string = array("%firstname%", "%lastname%", "%link%");
                    $replace_string   = array($user_detail[0]['firstname'], $user_detail[0]['lastname'], $link);
                    $email_message = str_replace($message_string, $replace_string, $email_message);
                    $email_subject = str_replace($message_string, $replace_string, $email_subject);
                    $this->common->send_email($email_from, $email_from_name, $email_to, $email_subject, $email_message,NULL,$userdata['language_code']);
                    $contactdata['status'] = '0';
                }
                
                for($i=0; $i<count($associated_id); $i++)
                {
                    $inassociated = $this->contacts->inassociated($associated_id[$i],$contact_id);
                    if(empty($inassociated))
                    {
                       $associated_data['contactlist_id'] = $associated_id[$i];
                       $associated_data['contact_id'] = $contact_id;
                       $result = $this->contacts->createassociatedcontact($associated_data);
                   }
               }
           }
       }
       
       if($this->bulkdata['sendmail'] == 1 )
       {
         $subject = $this->mail_template['WELCOME_SUBJECT'];
         $message = $this->mail_template['WELCOME_MESSAGE'];
         $this->common->send_email(EMAIL_FROM, EMAIL_NAME,$userdata['email'],$subject,$message,NULL,$userdata['language_code']);
     }
     $result = $this->users->getuserdetailbyid($user_id);
     $responsedata['data'] = $result[0];
     $responsedata['data']['lockstatus'] = 0;
     $responsedata['data']['checkin_id'] = '0';
     $headertoken = $this->api->generateheader($user_id);
     $responsedata['data']['headertoken'] = $headertoken;
     $responsedata['message'] = $this->response_message['CREATED'];
     $responsedata['status'] = '1';
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
 * This function will use to Get user data
 *
 * @access	public
 *
 * @return void
 */
public function getuserdetail()
{
    try
    {
        $responsedata = array();
        if(!empty($this->bulkdata))
        {
            $user_id = $this->user_id;
            $result = $this->users->getuserdetailbyid($user_id);
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
 * This function will use to check username and email for create user.
 * If username or email already in database then send response with status 0 
 *
 * @access	public
 *
 * @return void
 */
public function checkusernameemail()
{
    try
    {
        $responsedata = array();
        if(!empty($this->bulkdata))
        {
            $userdata['username'] = (empty($this->bulkdata['username'])) ?"" : $this->bulkdata['username'];
            $userdata['email'] = (empty($this->bulkdata['email'])) ?"" : $this->bulkdata['email'];
            $userdata['email'] = strtolower($userdata['email']);
            $userdata['username'] = strtolower($userdata['username']);
            $result = $this->users->getuserdetailbyusername($userdata['username']);
            $result_email =  $this->users->getuserdetailbyemail($userdata['email']);
            
            if ( empty($userdata['username']) || empty($userdata['email']) )
            {
                $responsedata['message'] = $this->response_message['FILL_ALL_INFO'];
                $responsedata['status'] = '0';
            }
            else if (isset($result) && count($result) > 0)
            {
                $responsedata['message'] =  $this->response_message['USEREXITS'];
                $responsedata['status'] = '0';
            }
            else if (isset($result_email) && count($result_email) > 0)
            {
                /*Sorry, it looks like mailid belongs to an existing account.*/
                $responsedata['message'] = $this->response_message['EMAILEXITS'];
                $responsedata['status'] = '0';
            }
            else
            {
                $responsedata['message'] = $this->response_message['AVAILABLE'];
                $responsedata['status'] = '1';    
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
 * This function will use for forgotpassword
 * Check mail id and send reset password link in mail.
 *
 * @access	public
 *
 * @return void
 */
public function forgotpassword()
{ 
    try
    {
        $responsedata = array();
        if(!empty($this->bulkdata))
        {
            $email = $this->bulkdata['email'];
            $email = strtolower($email);
            $result = $this->users->getuserdetailbyemail($email);
            
			/* send mail if email id is in database*/
            if(isset($result) && count($result) > 0)
            {
                $userdata['forgotpassword_code'] = $this->api->randomPassword();
                $userdata['forgotpassword_code'] = $userdata['forgotpassword_code']."_".strtotime(CURRENT_DATETIME);
                $count_username = $this->users->updateuser($userdata, $result[0]['id']);
                if($count_username > 0)
                {
                 $link = "<a href = ".site_url() ."newpassword?uid=".$this->common->encode($result[0]['id'])."&fc=".$this->common->encode($userdata['forgotpassword_code'])." mc:disable-tracking >click here</a>";
				 /*sent Email with new password.*/
                 $subject = $this->mail_template['FORGOTPASSWORD_SUBJECT'];
                 $email_message = $this->mail_template['FORGOTPASSWORD_MESSAGE'];
                 $message_string = array("%email%", "%username%", "%link%");
                 $replace_string   = array($result[0]['email'], $result[0]['username'], $link);
                 $email_message = str_replace($message_string, $replace_string, $email_message);
                 $this->common->send_email(EMAIL_FROM, EMAIL_NAME,$result[0]['email'],$subject,$email_message,NULL,$result[0]['language_code']);
                 
                 $responsedata['message'] = $this->response_message['PASSWORD_REQUEST_SEND'];
                 $responsedata['status'] = '1';
             }
             else
             {
                $responsedata['message'] = $this->response_message['PASSWORD_NOT_CHANGE'];
                $responsedata['status'] = '0';
            }
        }
        else
        {
            $responsedata['message'] = $email ." ". $this->response_message['EMAIL_NOT_EXIST_ENTER_CORRECT_EMAIL'];
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

	/*JSON Data Encode*/
$this->api->iwmf_jsonencode($responsedata);
    /*JSON Render the Data*/
$this->api->iwmf_jsonrender();
}


/**
 * This function will use to Sign out
 * Update devicetoken with NULL value.
 * @access	public
 *
 * @return void
 */
public function signout()
{
    try
    {
        $responsedata = array();
        if($this->user_id  && $this->user_id != '' )
        {
          $userdata['devicetoken'] = NULL;
          $result = $this->users->updateuser($userdata, $this->user_id);
          $result_avtivecheckin = $this->checkins->getsignoutcheckinlist($this->user_id);
			/*if checkin is active then update devicetoken to null*/
          if($result_avtivecheckin)
          {
              $checkindata['devicetoken'] = NULL;
              $result = $this->checkins->updatecheckin($checkindata, $result_avtivecheckin[0]['id']);
          }
          
		/*deleteheadertoken*/
          $this->users->deleteheaderdata($this->user_id);
          $responsedata['message'] = $this->response_message['SIGN_OUT_SUCCESSFULY'];
          $responsedata['status'] = '1';
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

    /*JSON Data Encode*/
$this->api->iwmf_jsonencode($responsedata);
    /*JSON Render the Data*/
$this->api->iwmf_jsonrender();
}

/**
 * This function will use for Login
 * It will first check username and password.
 * After 6 wrong attempt user account will lock for 24 hours. 
 * On successful login, this function will return user information, user status(lock or unlock), as well as any active checkin.
 * @access	public
 *
 * @return void
 */
public function login()
{ 
	try
    {
        $responsedata = array();
        if(!empty($this->bulkdata))
        {
            $username = $this->bulkdata['username'];
            $devicetoken = $this->bulkdata['devicetoken'];
            $devicetype = $this->bulkdata['devicetype'];
            $username = strtolower($username);
            $result_username = $this->users->getuserdetailbyusername($username);
            if($result_username && $result_username[0]['delete'] == 0)
            {
                $salt_password = $this->bulkdata['password'].$result_username[0]['salt'];
                $password = md5($salt_password);
                $hash_password = $this->bcrypt->check_password($salt_password, $result_username[0]['password']);
                $getlogin_attempt = $this->users->getloginattempt($result_username[0]['id'],$devicetoken);
                if(isset($getlogin_attempt) && $getlogin_attempt[0]['login_attempt_number'] >= 6 && strtotime($getlogin_attempt[0]['last_attempt_time']) > (strtotime(CURRENT_DATETIME) - 86400))
                {
                 $responsedata['message'] = $this->response_message['LOCK_USER'];
                 $responsedata['status'] = '0';
             }
             else
             {
                 if(isset($result_username) && ($result_username[0]['password'] == $password || $hash_password) && $password != '')
                 {
                     if(!empty($getlogin_attempt))
                     {
                        $loginattempt['login_attempt_number'] = 0;
                        $loginattempt['last_attempt_time'] = CURRENT_DATETIME;
                        
						/*update login attempt count*/
                        $this->users->updateloginattempt($loginattempt,$getlogin_attempt[0]['login_attempt_id']);
                    }
                    $user_id = $result_username[0]['id'];
                    $headertoken = $this->api->generateheader($user_id);
					/*delelte header data using deviceid*/
                    $this->users->deleteheaderdatabydeviceid($devicetoken,$user_id);
                    $userdata['islogin'] = 1;
                    $userdata['last_login_time'] = CURRENT_DATETIME;
                    $userdata['devicetoken'] = $devicetoken;
                    $userdata['device_type'] = $devicetype;
					/*update user data*/
                    $this->users->updateuser($userdata, $user_id);
                    $user_data = $this->users->getuserdetailbyid($user_id);
                    $responsedata['data'] = $user_data[0];
                    $responsedata['data']['lockstatus'] = 0;
                    $responsedata['data']['checkin_id'] = '0';
                    $responsedata['data']['activecheckin'] = array();
                    if($result_username[0]['lock_admin'] == 1)
                    {
                        $responsedata['data']['lockstatus'] = 1;
                    }
                    if($result_username[0]['status'] == -1)
                    {
                        $responsedata['data']['lockstatus'] = 1;   
                    }
                    else
                    {
                        $result_avtivecheckin = $this->checkins->getsignoutcheckinlist($user_id);
                        if($result_avtivecheckin)
                        {
                            $checkindata['devicetoken'] = $devicetoken;
                            $checkindata['type'] = $devicetype;
							/*Update checkin data */
                            $this->checkins->updatecheckin($checkindata, $result_avtivecheckin[0]['id']);
                        }
                        
                        $responsedata['data']['activecheckin'] = $result_avtivecheckin;
                        if($result_avtivecheckin)
                        {
                            $checkin_id = $result_avtivecheckin[0]['id'];
                            $count= $this->checkins->checkinconfirmedcount($checkin_id);
                            $responsedata['data']['activecheckin'][0]['checkinconfirmedcount'] = $count[0]['count'];
                        }
                    }
                    $password_expired =  strtotime($result_username[0]['password_change']) + ( 30 *24 * 3600);
                    $currentdate = strtotime(CURRENT_DATETIME);
                    $responsedata['data']['passwordexpired'] = '0';
                    if($password_expired < $currentdate)
                    {
                        $responsedata['data']['passwordexpired'] = '1';
                    }
                    
                    $responsedata['data']['headertoken'] = $headertoken;
                    $responsedata['message'] = $this->response_message['AUTHENTICATED'];
                    $responsedata['status'] = '1'; 
                }
                else
                {
                 if(!empty($getlogin_attempt))
                 {
                    if(strtotime($getlogin_attempt[0]['last_attempt_time']) <= (strtotime(CURRENT_DATETIME) - 86400))
                    {
                        $loginattempt['login_attempt_number'] = 1;    
                    }
                    else
                    {
                        $loginattempt['login_attempt_number'] = $getlogin_attempt[0]['login_attempt_number'] + 1;
                    }
                    $loginattempt['last_attempt_time'] = CURRENT_DATETIME;
					/*update login attempt count*/
                    $this->users->updateloginattempt($loginattempt,$getlogin_attempt[0]['login_attempt_id']);
                }
                else
                {
                    $loginattempt['user_id'] = $result_username[0]['id'];
                    $loginattempt['devicetoken'] = $devicetoken ;
                    $loginattempt['login_attempt_number'] = 1;
                    $loginattempt['last_attempt_time'] = CURRENT_DATETIME;
                    
					/*insert login attemt*/
                    $this->users->insertloginattempt($loginattempt);
                }
                
                if($loginattempt['login_attempt_number'] == '5')
                {
                    $responsedata['message'] = $this->response_message['LAST_ATTEMPT_REMAIN'];
                }
                elseif($loginattempt['login_attempt_number'] == '6')
                {
                    $responsedata['message'] = $this->response_message['LOCK_USER'];
                }
                else
                {
                    $responsedata['message'] = $this->response_message['INVALID_PASSWORD'];
                }
                
                $responsedata['status'] = '0';
            }
        }
    }
    else
    {
        $responsedata['message'] = $this->response_message['INVALID_USERNAME'];
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

    /*JSON Data Encode*/
$this->api->iwmf_jsonencode($responsedata);
    /*JSON Render the Data*/
$this->api->iwmf_jsonrender();
}

/**
 * This function will use for Change password
 * It will check below criteria
 *     - Password string should not contain username.
 *     - Password string should not contain past three password.
 * @access	public
 *
 * @return void
 */
public function resetpassword()
{
	try
    {
        $responsedata = array();
        if(!empty($this->bulkdata))
        {
          $result_user = $this->users->getuserdetail($this->user_id);
          $oldpassword = $this->bulkdata['oldpassword'];
          $newpassword = $this->bulkdata['newpassword'];
          $salt_password = $oldpassword.$result_user[0]['salt'];
          $oldpassword = md5($salt_password);
          $hash_password = $this->bcrypt->check_password($salt_password, $result_user[0]['password']);
			/* check old and new password are same or not*/
          if($oldpassword == $result_user[0]['password'] || $hash_password)
          {
				/* check  password have user name in it*/
              if(stripos($newpassword,$result_user[0]['username']) === FALSE && stripos($newpassword,$result_user[0]['firstname']) === FALSE && stripos($newpassword,$result_user[0]['lastname']) === FALSE)
              {
                 $newpassword = $newpassword.$result_user[0]['salt'];
				/* check with old password*/
                 $result_password = $this->users->getoldpassword($result_user[0]['id']);
                 $password_match = 0;
                 for($pass_i = 0 ;$pass_i < count($result_password); $pass_i++)
                 {    
                     if($this->bcrypt->check_password($newpassword,$result_password[$pass_i]['password']))
                     {
                        $password_match = 1;
                        break;
                    }
                }
				/*count larger than 0 shows that it match with old 3 password*/
                if($password_match == 0)
                {
                 $newpassword = $this->bcrypt->hash_password($newpassword);
                 $userdata['password'] = $newpassword ;
                 $userdata['password_change'] = CURRENT_DATETIME ;
			     /*update user data*/
                 $this->users->updateuser($userdata, $this->user_id);
                 
                 $oldpass['user_id'] = $this->user_id;
                 $oldpass['password'] = $userdata['password'];
                 
			    /*insert old password*/
                 $this->users->insertoldpassword($oldpass);
                 
                 $responsedata['message'] = $this->response_message['PASS_UPDATED'];
                 $responsedata['status'] = '1';
             }
             else
             {
                 $responsedata['message'] = $this->response_message['MATCH_OLD_PASS'];
                 $responsedata['status'] = '0';
             }
         }
         else
         {
             $responsedata['message'] = $this->response_message['PASSWORD_NOT_NAME'];
             $responsedata['status'] = '0';
         }
     }
     else
     {
      $responsedata['message'] = $this->response_message['INVALID_PASSWORD'];
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