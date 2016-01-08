<?php
if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}

/**
* Name:  Checkin
*
* Description:  This Class contain API function related to Contact
*               Modified Function base on requirement 
* @package Contact
* @version 1.0
*
*/

class Contact extends CI_Controller
{
    public $bulkdata;
    public $response_message;
    public $logarray;
    
	/**
	 * Constructor
	 * Get request  	 
	 * Check header token if it's not valid then send response with invalid session.
	 * If user status is "lock" then send notification with response 0 to lock app
	 * If token is valid then it will fetch "response and message" based on user's language.
	 * @access 	public
	 * @public $bulkdata = json request by app
	 * @public $header = header data
	 */
	
    function __construct()
    {
        header("Content-type: application/json; charset=utf-8");
        parent::__construct();
        $bulkdata = $this->input->get();
        
        $this->load->model('api/contacts');
        $this->load->model('api/api');
        $this->load->model('api/checkins');
        $this->load->model('api/users');
        $this->load->model('api/common');
        $this->load->model('api/notification');
        $this->bulkdata = $this->api->iwmf_jsondecode($bulkdata['bulkdata']);
        
        if(!isset($this->bulkdata) && count($this->bulkdata) == 0)
        {
            $postdata = $this->input->post();
            $this->logarray = json_decode($postdata['bulkdata'], TRUE);
            $this->bulkdata = $this->api->iwmf_jsondecode($postdata['bulkdata']);
        }
        
        $header = getallheaders();
        $key = substr($header['language_code'],0,32);
        $str = substr($header['language_code'],32,strlen($header['language_code'])-45);
        $language = $this->api->AES_Decode($str,$key);
        $language = (isset($language) && $language != '') ? $language : 'EN';
        $this->lang->load('message', $language);
        $this->response_message = $this->lang->language;
        $checkheader = $this->api->verifyheader($header);
        if($checkheader['status'] == 0)
        {
           $responsedata['message'] = $this->responsemessage['FORCE_LOGOUT'];
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
           
	       /*ios notification*/
           if($getuser_data[0]['device_type'] == '1')
           {
              $this->notification->sendToIphone('', $getuser_data[0]['devicetoken'], $message,'lockapp');
          }
	      /*android notification*/
          elseif($getuser_data[0]['device_type'] == '2')
          {
              $this->notification->sendToAndroid('', $getuser_data[0]['devicetoken'], $message,3);
          }
          
          $responsedata['message'] = $this->responsemessage['FORCE_LOGOUT'];
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


/**
 * This function will use to Create circle
 * There are two types of circle : public and private
 *
 * @access	public
 *
 * @return void
 */

public function createcontactcircle()
{
    try
    {
        $responsedata = array();
        if(!empty($this->bulkdata) && (isset($this->user_id) && $this->user_id != '') && (isset($this->bulkdata['circle']) && $this->bulkdata['circle'] != '') && (isset($this->bulkdata['listname']) && $this->bulkdata['listname'] != ''))
        {
            $listdata['user_id'] = $this->user_id;
            $listdata['circle'] = $this->bulkdata['circle'];
            $listdata['listname'] = $this->bulkdata['listname'];
            
            if(!empty($this->bulkdata['list_id']))
            {
                $listdata['updated_on'] = CURRENT_DATETIME;
		        /*update contactlist*/
                $this->contacts->updatecontactlist($listdata, $this->bulkdata['list_id']);
                $responsedata['data'] = array("circle_id" => $this->bulkdata['list_id'], "listname" => $listdata['listname']);
                $responsedata['message'] = $this->response_message['UPDATED'];
            }
            else
            {
                $listdata['created_on'] = CURRENT_DATETIME;
                $result = $this->contacts->createcontactlist($listdata);
                $responsedata['data'] = array("circle_id" => $result, "listname" => $listdata['listname']);
                $responsedata['message'] = $this->response_message['CREATED'];
            }
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
 * This function will use to insert or update contact
 * It will check duplication based on contact name, email, phone number.
 * 
 * @access	public
 *
 * @return void
 */
public function createsinglecontact()
{
    try
    {
        $responsedata = array();
        
        if(!empty($this->bulkdata) && (isset($this->bulkdata['firstname']) && $this->bulkdata['firstname'] != '') && (isset($this->bulkdata['lastname']) && $this->bulkdata['lastname'] != '') && (isset($this->bulkdata['mobile']) && $this->bulkdata['mobile'] != '') && (isset($this->bulkdata['emails']) && $this->bulkdata['emails'] != '') && (isset($this->bulkdata['associated_id']) && $this->bulkdata['associated_id'] != '') && (isset($this->bulkdata['sos_enabled']) && $this->bulkdata['sos_enabled'] != ''))
        {
            $contactdata['firstname'] = $this->bulkdata['firstname'];
            $contactdata['lastname'] = $this->bulkdata['lastname'];
            $contactdata['mobile'] = $this->bulkdata['mobile'];
            $contactdata['emails'] = $this->bulkdata['emails'];
            $contactdata['emails'] = strtolower($contactdata['emails']);
            $contactdata['sos_enabled'] = $this->bulkdata['sos_enabled'];
            $associated_id = explode(",", $this->bulkdata['associated_id']);
            
            $user_id = $this->user_id;
            $user_detail = $this->users->getuserdetail($user_id);
            if(!empty($this->bulkdata['contact_id']))
            {
                $contact_id = $this->bulkdata['contact_id'];
                $getduplicatecontect =  $this->contacts->chakeduplicatecontectforname($user_id,$contactdata['firstname'],$contactdata['lastname']);
                /*check name duplicate*/
                if(empty($getduplicatecontect) || $getduplicatecontect[0]['id'] == $contact_id)
                {
                 $getduplicatecontect =  $this->contacts->chakeduplicatecontectforemail($contactdata['emails'],$user_id);
				 /*check email duplicate*/
                 if(empty($getduplicatecontect) || $getduplicatecontect[0]['id'] == $contact_id)
                 {
                     $getduplicatecontect =  $this->contacts->chakeduplicatecontectformobile($contactdata['mobile'],$user_id);
			         /*check number duplicate*/
                     if(empty($getduplicatecontect)|| $getduplicatecontect[0]['id'] == $contact_id)    
                     {
                        $contact_detail = $this->contacts->getcontactdetailbyid($this->bulkdata['contact_id']);
                        
                        if($contactdata['sos_enabled'] == '2')
                        {
                            $contact_detail = $this->contacts->getcontactdetailbyid($this->bulkdata['contact_id']);
                            
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
                                $this->common->send_email($email_from, $email_from_name, $email_to, $email_subject, $email_message,NULL,$user_detail[0]['language_code']);
                                $contactdata['status'] = '0';
                            }
                        }
                        
                        $contactdata['updated_on'] = CURRENT_DATETIME;
                        /*update contact*/
                        $this->contacts->updatecontact($contactdata, $this->bulkdata['contact_id']);
                        $result_associated_id = $this->contacts->getassociatedbycontactid($contact_id);
                        $old_associated_id = array();
                        for($i=0; $i<count($result_associated_id); $i++)
                        {
                            $old_associated_id[] = $result_associated_id[$i]['contactlist_id'];
                        }
                        $associated_id_for_insert = array_values(array_diff($associated_id, $old_associated_id));
                        for($i=0; $i<count($associated_id_for_insert); $i++)
                        {
                            $associated_data['contactlist_id'] = $associated_id_for_insert[$i];
                            $associated_data['contact_id'] = $contact_id;
                            
                            /*create associated contact*/
                            $this->contacts->createassociatedcontact($associated_data);
                        }
                        
                        $associated_id_for_del = array_values(array_diff($old_associated_id, $associated_id));
                        if($associated_id_for_del)
                        {
                            /*delete associated contact*/
                            $this->contacts->deleteassociatedcontact(implode(",",$associated_id_for_del), $contact_id);
                        }
                        
                        $responsedata['message'] = $this->response_message['UPDATED'];
                        $responsedata['status'] = '1';
                        $sos_contactlist = $this->contacts->getsoscontactidbyuser($user_id);
                        if(empty($sos_contactlist) && $contact_detail[0]['sos_enabled'] == '1' && $contactdata['sos_enabled'] == '0')
                        {
                            $responsedata['message'] = $this->response_message['CONFIRMED_SOS_CONTACTS'];
                            $responsedata['status'] = '2';
                        }
                    }
                    else
                    {
                        $responsedata['message'] = $this->response_message['DUPLICATE_NUMBER'];
                        $responsedata['status'] = '0';
                    }
                }
                else
                {
                 $responsedata['message'] = $this->response_message['DUPLICATE_EMAIL'];
                 $responsedata['status'] = '0';
             }
         }
         else
         {
             $responsedata['message'] = $this->response_message['DUPLICATE_NAME'];
             $responsedata['status'] = '0';
         }
     }
     else
     {
        $contactdata['created_on'] = CURRENT_DATETIME;
        $getduplicatecontect =  $this->contacts->chakeduplicatecontectforname($user_id,$contactdata['firstname'],$contactdata['lastname']);
        if(empty($getduplicatecontect) )
        {
         $getduplicatecontect =  $this->contacts->chakeduplicatecontectforemail($contactdata['emails'],$user_id);
         if(empty($getduplicatecontect) )
         {
             $getduplicatecontect =  $this->contacts->chakeduplicatecontectformobile($contactdata['mobile'],$user_id);
             if(empty($getduplicatecontect))    
             {
                $result = $this->contacts->createcontact($contactdata);
                $contact_id = $result;
                $user_id = $this->user_id;
                $user_detail = $this->users->getuserdetail($user_id);
                if($contactdata['sos_enabled'] == '2')
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
                    $this->common->send_email($email_from, $email_from_name, $email_to, $email_subject, $email_message,NULL,$user_detail[0]['language_code']);
                    $contactdata['status'] = '0';
                }
                
                for($i=0; $i<count($associated_id); $i++)
                {
                    $inassociated = $this->contacts->inassociated($associated_id[$i],$contact_id);
                    if(empty($inassociated))
                    {
                        $associated_data['contactlist_id'] = $associated_id[$i];
                        $associated_data['contact_id'] = $contact_id;
                        
						/*create assoicated contact*/
                        $this->contacts->createassociatedcontact($associated_data);
                        $responsedata['message'] = $this->response_message['CREATED'];
                    }
                    else
                    {
                        $responsedata['message'] = $this->response_message['CONTACT_EXIST'];
                    }
                }
                $responsedata['status'] = '1';
            }
            else
            {
                $responsedata['message'] = $this->response_message['DUPLICATE_NUMBER'];
                $responsedata['status'] = '0';
            }
        }
        else
        {
         $responsedata['message'] = $this->response_message['DUPLICATE_EMAIL'];
         $responsedata['status'] = '0';
     }
 }
 else
 {
     $responsedata['message'] = $this->response_message['DUPLICATE_NAME'];
     $responsedata['status'] = '0';
 }
}
$responsedata['data'] = $contact_id;
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
 * This function will use to get user contacts with associated circle 
 *
 * @access	public
 *
 * @return void
 */
public function allcontacts()
{
    try
    {
        $responsedata = array();
        $result_new = array();
        if((isset($this->user_id) && $this->user_id != ''))
        {
            $user_id = $this->user_id;
            
            $result = $this->contacts->contactbyuserid($user_id);
            
            if(isset($result) && count($result) > 0)
            {
                for($i=0; $i<count($result); $i++)
                {
                    $result_new[$result[$i]['id']]['contact_id'] = $result[$i]['id'];
                    $result_new[$result[$i]['id']]['firstname'] = $result[$i]['firstname'];
                    $result_new[$result[$i]['id']]['lastname'] = $result[$i]['lastname'];
                    $result_new[$result[$i]['id']]['mobile'] = $result[$i]['mobile'];
                    $result_new[$result[$i]['id']]['emails'] = $result[$i]['emails'];
                    $result_new[$result[$i]['id']]['sos_enabled'] = $result[$i]['sos_enabled'];
                    $result_new[$result[$i]['id']]['associated_id'] = $result[$i]['associated_id'];
                    $result_new[$result[$i]['id']]['circle_typelist'][] = $result[$i]['circle'];
                    $result_new[$result[$i]['id']]['associated_circles'][] = array(
                        'contactlist_id' => $result[$i]['contactlist_id'],
                        'listname' => $result[$i]['listname'],
                        'circle' => $result[$i]['circle']
                        );
                }
                
                for($i=0; $i<count($result); $i++)
                {
                    if(in_array(1,$result_new[$result[$i]['id']]['circle_typelist']) && in_array(2,$result_new[$result[$i]['id']]['circle_typelist']))
                    {
                        $result_new[$result[$i]['id']]['contact_type'] = '4';
                    }
                    elseif(in_array(1,$result_new[$result[$i]['id']]['circle_typelist']))
                    {
                        $result_new[$result[$i]['id']]['contact_type'] = '1';
                    }
                    elseif(in_array(2,$result_new[$result[$i]['id']]['circle_typelist']))
                    {
                        $result_new[$result[$i]['id']]['contact_type'] = '2';
                    }
                }
                $responsedata['data'] = array_values($result_new);
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
 * This function will use to get user's contacts with circle type based on request
 *
 * @access	public
 *
 * @return void
 */
public function allexistingcontacts()
{
    try
    {
        $responsedata = array();
        $result_new = array();
        if(!empty($this->bulkdata) && (isset($this->user_id) && $this->user_id != '') && (isset($this->bulkdata['contactlist_id']) && $this->bulkdata['contactlist_id'] != ''))
        {
            $user_id = $this->user_id;
            $contectlist_id = $this->bulkdata['contactlist_id'];
            $result = $this->contacts->contactbyuserid($user_id);
            
            if(isset($result) && count($result) > 0)
            {
                for($i=0; $i<count($result); $i++)
                {
                    $result_new[$result[$i]['id']]['contact_id'] = $result[$i]['id'];
                    $result_new[$result[$i]['id']]['firstname'] = $result[$i]['firstname'];
                    $result_new[$result[$i]['id']]['lastname'] = $result[$i]['lastname'];
                    $result_new[$result[$i]['id']]['mobile'] = $result[$i]['mobile'];
                    $result_new[$result[$i]['id']]['emails'] = $result[$i]['emails'];
                    $result_new[$result[$i]['id']]['sos_enabled'] = $result[$i]['sos_enabled'];
                    $result_new[$result[$i]['id']]['associated_id'] = $result[$i]['associated_id'];
                    $result_new[$result[$i]['id']]['circle_typelist'][] = $result[$i]['circle'];
                    $associated_id = explode(',',$result[$i]['associated_id']);
                    if(in_array($contectlist_id, $associated_id))
                    {
                     $result_new[$result[$i]['id']]['contact_exist'] = '1';
                 }
                 else
                 {
                     $result_new[$result[$i]['id']]['contact_exist'] = '0';
                 }
             }
             for($i=0; $i<count($result); $i++)
             {
                if(in_array(1,$result_new[$result[$i]['id']]['circle_typelist']) && in_array(2,$result_new[$result[$i]['id']]['circle_typelist']))
                {
                 $result_new[$result[$i]['id']]['contact_type'] = '4';
             }
             elseif(in_array(1,$result_new[$result[$i]['id']]['circle_typelist']))
             {
                 $result_new[$result[$i]['id']]['contact_type'] = '1';
             }
             elseif(in_array(2,$result_new[$result[$i]['id']]['circle_typelist']))
             {
                 $result_new[$result[$i]['id']]['contact_type'] = '2';
             }
         }
         
         $responsedata['data'] = array_values($result_new);
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
 * This function will use to Update association of contacts
 *
 * @access	public
 *
 * @return void
 */
public function updatecontactlist()
{
	try
    {
        $responsedata = array();
        if(!empty($this->bulkdata) && (isset($this->bulkdata['contactlist_id']) && $this->bulkdata['contactlist_id'] != ''))
        {
          $contactlist_id = $this->bulkdata['contactlist_id'];
          $contact_id = $this->bulkdata['contact_id'];
          $list_contact = explode(',',$contact_id);
          $this->contacts->deleteassociatedcontactbylist($contactlist_id);
          for($i=0; $i<count($list_contact); $i++)
          {
              if($list_contact[$i] !='')
              {
                 $associated_data['contactlist_id'] = $contactlist_id;
                 $associated_data['contact_id'] = $list_contact[$i];
				 /*create associated contact*/
                 $this->contacts->createassociatedcontact($associated_data);
             }
         }
         $responsedata['message'] = $this->response_message['UPDATED'];
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
 * This function will use to get user's contacts for all contact list
 *
 * @access	public
 *
 * @return void
 */
public function allcirclewithcontacts()
{
    try
    {
        $responsedata = array();
        if( (isset($this->user_id) && $this->user_id != ''))
        {
            $user_id = (isset($this->user_id) && $this->user_id != '') ? $this->user_id : '';
            $circle = (isset($this->bulkdata['circle']) && $this->bulkdata['circle'] != '') ? $this->bulkdata['circle'] : '';
            $result = $this->contacts->contactlistbycircle($user_id, $circle);
            if(isset($result) && count($result) > 0)
            {
                $result_new =array();
                for($i=0; $i<count($result); $i++)
                {
                    $contactlist_id = $result[$i]['id'];
                    $result_new[$contactlist_id]['contactlist_id'] = $result[$i]['id'];
                    $result_new[$contactlist_id]['circle'] = $result[$i]['circle'];
                    $result_new[$contactlist_id]['listname'] = $result[$i]['listname'];
                    $result_new[$contactlist_id]['defaultstatus'] = $result[$i]['defaultstatus'];
                    
                    if($result[$i]['contact_id'] != '')
                    {
                     $result_new[$contactlist_id]['Contacts'][$result[$i]['contact_id']]['contact_id'] = $result[$i]['contact_id'];
                     $result_new[$contactlist_id]['Contacts'][$result[$i]['contact_id']]['firstname'] = $result[$i]['firstname'];
                     $result_new[$contactlist_id]['Contacts'][$result[$i]['contact_id']]['lastname'] = $result[$i]['lastname'];
                     $result_new[$contactlist_id]['Contacts'][$result[$i]['contact_id']]['mobile'] = $result[$i]['mobile'];
                     $result_new[$contactlist_id]['Contacts'][$result[$i]['contact_id']]['emails'] = $result[$i]['emails'];
                     $result_new[$contactlist_id]['Contacts'][$result[$i]['contact_id']]['sos_enabled'] = $result[$i]['sos_enabled'];
                     $result_new[$contactlist_id]['Contacts'][$result[$i]['contact_id']]['associated_id'] = $result[$i]['associated_ids'];
                     $result_new[$contactlist_id]['Contacts'][$result[$i]['contact_id']]['circle_typelist'][] = $result[$i]['associated_circle'];
                     $result_new[$contactlist_id]['Contacts'][$result[$i]['contact_id']]['associated_circles'][] = array(
                        'contactlist_id' => $result[$i]['associated_id'],
                        'listname' => $result[$i]['associated_listname'],
                        'circle' => $result[$i]['associated_circle']
                        );
                 }
                 else
                 {
                    $result_new[$contactlist_id]['Contacts'] = array();
                }
            }
            
            $result_new = array_values($result_new);
            for($j=0; $j<count($result_new); $j++)
            {
                $result_new[$j]['Contacts'] = array_values($result_new[$j]['Contacts']);
            }
            for($z=0; $z<count($result_new); $z++)
            {
             for($j=0;$j<count($result_new[$z]['Contacts']);$j++)
             {
                 if(in_array(1,$result_new[$z]['Contacts'][$j]['circle_typelist']) && in_array(2,$result_new[$z]['Contacts'][$j]['circle_typelist']))
                 {
                    $result_new[$z]['Contacts'][$j]['contact_type'] = '4';
                }
                elseif(in_array(1,$result_new[$z]['Contacts'][$j]['circle_typelist']))
                {
                    $result_new[$z]['Contacts'][$j]['contact_type'] = '1';
                }
                elseif(in_array(2,$result_new[$z]['Contacts'][$j]['circle_typelist']))
                {
                    $result_new[$z]['Contacts'][$j]['contact_type'] = '2';
                }
            }
        }
        $responsedata['data'] = $result_new;
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
 * This function will use to get contact list based on requested circle 
 *
 * @access	public
 *
 * @return void
 */
public function allcirclewithstatus()
{
    try
    {
        $responsedata = array();
        if(!empty($this->bulkdata) && (isset($this->user_id) && $this->user_id != ''))
        {
            $user_id = $this->user_id;
            $contact_id = (isset($this->bulkdata['contact_id']) && $this->bulkdata['contact_id'] != '') ? $this->bulkdata['contact_id'] : '';
            $circle = (isset($this->bulkdata['circle']) && $this->bulkdata['circle'] != '') ? $this->bulkdata['circle'] : '';
            $result = $this->contacts->contactlistbyuser($user_id, $contact_id, $circle);
            
            if(isset($result) && count($result) > 0)
            {
                $responsedata['data'] = array_values($result);
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
 * This function will use to delete user contacts
 * If contact is last contact of circle then also delete circle
 *
 * @access	public
 *
 * @return void
 */
public function deletecontact()
{
    try
    {
        $responsedata = array();
        if(!empty($this->bulkdata) && (isset($this->user_id) && $this->user_id != '') && (isset($this->bulkdata['contact_id']) && $this->bulkdata['contact_id'] != ''))
        {
            $user_id = $this->user_id;
            $contact_id = $this->bulkdata['contact_id'];
            
            $contactsdata = $this->contacts->getsoscontactidbyuser($user_id);
            $contactlist_id = $this->contacts->getcontactlistbycontact($contact_id,$user_id);
            
            $count_soscontact = $this->contacts->getcountsoscontactidbyuser($user_id,$contactlist_id[0]['contactlist_id']);
            /*chake this contact is last sos ot not if yes not able to delete*/
            if(count($contactsdata) == 1 && $contactsdata[0]['contact_id'] == $contact_id && $count_soscontact[0]['count_id'] == 1 && $count_soscontact[0]['contact_id'] == $contact_id)
            {
                $responsedata['message'] = $this->response_message['REMOVING_CONTACT_APP_LOCK'];
                $responsedata['status'] = '0';
            }
            else
            {
                $result = $this->contacts->deletecontactbyid($user_id, $contact_id);
                if(isset($result) && $result > 0)
                {
                    $responsedata['message'] = $this->response_message['DELETED'];
                    $responsedata['status'] = '1';
                }
                else
                {
                    $responsedata['message'] = $this->response_message['WRONG'];
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
 * This function will use to delete user circle
 *
 * @access	public
 *
 * @return void
 */
 
public function deletecircle()
{
    try
    {
        $responsedata = array();
        
        if(!empty($this->bulkdata) && (isset($this->user_id) && $this->user_id != '') && (isset($this->bulkdata['contactlist_id']) && $this->bulkdata['contactlist_id'] != ''))
        {
            $user_id = $this->user_id;
            $contactlist_id = $this->bulkdata['contactlist_id'];
            $contactsdata = $this->contacts->getcountsoscontactidbyuser($user_id);
            if(count($contactsdata) == 1 && $contactsdata[0]['contactlist_id'] == $contactlist_id)
            {
                $responsedata['message'] = $this->response_message['REMOVING_CONTACT_APP_LOCK'];
                $responsedata['status'] = '0';
            }
            else
            {
                $result = $this->contacts->deletecontactlistbylistid($user_id, $contactlist_id);
                if(isset($result) && $result > 0)
                {
                    $responsedata['message'] = $this->response_message['DELETED'];
                    $responsedata['status'] = '1';
                }
                else
                {
                    $responsedata['message'] = $this->response_message['WRONG'];
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
 * This function will use to change status of circle (1= active, 2= inactive)
 *
 * @access	public
 *
 * @return void
 */
public function changedefaultstatus()
{
    try
    {
        $responsedata = array();
        if(!empty($this->bulkdata) && (isset($this->user_id) && $this->user_id != '') && (isset($this->bulkdata['list_id']) && $this->bulkdata['list_id'] != '') && (isset($this->bulkdata['circle']) && $this->bulkdata['circle'] != ''))
        {
            $user_id = $this->user_id;
            $list_id = $this->bulkdata['list_id'];
            $circle = $this->bulkdata['circle'];
            if($circle == '2')
            {
                $defaultstatus = $this->bulkdata['defaultstatus'];
                /*change default public circle*/
                $this->contacts->changepublicdefaultstatus($user_id, $list_id, $circle,$defaultstatus);
            }
            else
            {
              /*change default circle*/
              $this->contacts->changedefaultstatus($user_id, $list_id, $circle);
          }
          $responsedata['message'] = $this->response_message['UPDATED'];
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