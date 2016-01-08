<?php
if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}
/**
* Name:  Media
*
* Description:  This Class contain API function related to Media
*               Modified Function base on requirement 
* @package Media
* @version 1.0
*
*/
class Media extends CI_Controller
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
        
        $this->load->model('api/mediafunc');
        $this->load->model('api/api');
        $this->load->model('api/users');
        $this->load->model('api/notification');
        
        $header = getallheaders();
        $key = substr($header['language_code'],0,32);
        $str = substr($header['language_code'],32,strlen($header['language_code'])-45);
        $language = $this->api->AES_Decode($str,$key);
        $language = (isset($language) && $language != '') ? $language : 'EN';
        
        $this->lang->load('message', $language);
        $this->response_message = $this->lang->language;
        
        $this->bulkdata = $this->api->iwmf_jsondecode($bulkdata['bulkdata']);
        if(!isset($this->bulkdata) && count($this->bulkdata) == 0)
        {
            $postdata = $this->input->post();
            $this->logarray = json_decode($postdata['bulkdata'], TRUE);
            $this->bulkdata = $this->api->iwmf_jsondecode($postdata['bulkdata']);
        }
        
		/* header check	*/
        $checkheader = $this->api->verifyheader($header);
        $key = substr($header['language_code'],0,32);
        $str = substr($header['language_code'],32,strlen($header['language_code'])-45);
        $language = $this->api->AES_Decode($str,$key);
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
   
   if(isset($this->user_id) && $this->user_id > 0)
   {
    $this->language = $this->users->getuserlanguage($this->user_id)->language_code;
}
$this->language = (isset($this->language) && $this->language != '') ? $this->language : 'EN';
if($language != $this->language)
{
   $this->lang->is_loaded = array();
   $this->lang->language = array();
   $this->lang->load('message', $language);
   $this->response_message = $this->lang->language;
}
}


/**
 * This function will use for upload media 
 *
 * @access	public
 *
 * @return void
 */
public function addmedia()
{
    try
    {
       $responsedata = array();
       if(!empty($this->bulkdata))
       {
        $mediadata['user_id'] = $this->user_id;
        $mediadata['foreign_id'] = $this->bulkdata['foreign_id'];
        $mediadata['mediatype'] = $this->bulkdata['mediatype'];
        $mediadata['table_id'] = $this->bulkdata['table_id'];
        $mediadata['created_on'] = CURRENT_DATETIME;
        $media = $this->bulkdata['mediafile'];
        $extension = $this->bulkdata['extension'];
        $valid_extension = array('jpg','mp4','caf','3gp');    
		/* check valid extension*/
        if(in_array($extension, $valid_extension,true))
        {
          /* Upload Image */
          $mediadata['medianame'] = $this->mediafunc->uploadfile($media, $mediadata['mediatype'], $extension);
          $result = $this->mediafunc->addmedia($mediadata);
          $responsedata['media_id'] = $result;
          $responsedata['message'] = $this->response_message['ADDED'];
          $responsedata['status'] = '1';
      }
      else
      {
          $responsedata['message'] = $this->response_message['INVALID_EXTENSION'];
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
 * This function will use for test API internet speed.
 *
 * @access	public
 *
 * @return void
 */
public function addedmedia()
{
    try
    {
        $responsedata = array();
        $responsedata['message'] = $this->response_message['INVALID_PARAMS'];
        $responsedata['status'] = '0';
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
 * This function will use to Get media List
 *
 * @access	public
 *
 * @return void
 */

public function getmedialist()
{
    try
    {
        $responsedata = array();
        if(!empty($this->bulkdata))
        {
            $foreign_id = $this->bulkdata['checkin_id'];
            $result = $this->mediafunc->getmediabycheckin($foreign_id);
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
 * This function will use for Delete media
 *
 * @access	public
 *
 * @return void
 */
public function deletemedia()
{
    try
    {
        $responsedata = array();
        if(!empty($this->bulkdata))
        {
            $media_id = 1;
            $getmediadetail = $this->mediafunc->getmediabyid($media_id);
            if($getmediadetail)
            {
              /*delete media using media id*/
              $result = $this->mediafunc->deletemedia($media_id);
              $responsedata['data'] = $result;
              $responsedata['message'] = $this->response_message['DELETED'];
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
 * * This function will use for test API internet speed.
 *
 * @access	public
 *
 * @return void
 */
public function testupload()
{
    try
    {
        $responsedata = array();
        if(!empty($this->bulkdata))
        {
            $mediadata['mediatype'] = '3';
            $media = $this->bulkdata['mediafile'];
            $extension = $this->bulkdata['extension'];
            
            /* Upload Image */
            $mediadata['medianame'] = $this->mediafunc->uploadfile($media, $mediadata['mediatype'], $extension);
            $responsedata['message'] = $this->response_message['ADDED'];
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
            'message'  => $e->getMessage(),
            'status'  => '0'
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