<?php
if (!defined('BASEPATH'))
{
	exit('No direct script access allowed');
}
/**
* Name:  Deletedatacron
*
* Description:  Cron to delete data from database
* @package Deletedatacron
* @version 1.0
*
*/
class Deletedatacron extends CI_Controller
{
	function __construct()
	{
		
		header("content-type: text/html; charset=utf-8");
		parent::__construct();
		$this->load->model('api/checkins');
		$this->load->model('api/mediafunc');
		$this->load->model('api/users');
		$this->load->model('api/contacts');
		$this->load->model('api/common');
	}
	
	/**
	 * This Cron function will run on every 5 minute to delete alert from database.
	 *
	 * @access	public
	 *
	 * @return void
	 */
	public function deletealert()
	{
        /*Get 7 day before all alert*/
		$alert_data = $this->checkins->getoldalert();
		
		for($i =0 ;$i<count($alert_data) ; $i++)
		{
            /*delete media using foreign id*/
			$this->mediafunc->deletemediabyforeignid($alert_data[$i]['id'],'2');
			
            /*delete alert using foreign id*/
			$this->checkins->deletealert($alert_data[$i]['id']);
		}
	}
	/**
	 * This Cron function will run on every 5 minute to delete Checkin who has status missed, close or delete.
	 *
	 * @access	public
	 *
	 * @return void
	 */
	public function deletecheckin()
	{
        /* Get close, delete or missed and mail sended mail*/
		
		$get_checkin = $this->checkins->getoldcheckin();
		
		for($i =0 ;$i<count($get_checkin) ; $i++)
		{
            /* delete checkin data as well as media*/
			if($get_checkin[$i]['status'] == 4  || $get_checkin[$i]['status'] == 3  || ($get_checkin[$i]['status'] == 5 && $get_checkin[$i]['is_mailsend'] == 1 ))
			{
				/*delete media using foreign id*/
				$this->mediafunc->deletemediabyforeignid($get_checkin[$i]['id'],'1');
				
				/*delete alert using foreign id*/
				$this->checkins->deletecheckin($get_checkin[$i]['id']);
			}

		}
	}

	/**
	 * This Cron function will run ones in a day.
	 * 
	 * If user will not login since last 12 month then send mail and delete user.
	 * If user will not login since lase 11 month or before one week of completion of 12 month then send reminder mail.
	 *
	 * @access	public
	 *
	 * @return void
	 */
	
	public function deleteuser()
	{
		error_reporting(E_ALL);
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
		
		
		
		/* get data of user before 12 month and have to delete all user data (alert, sos,checkin)*/
		/* 1= 12 month user data we have to delete it*/
		$get_12month_user = $this->users->getuserdeletedata(1);
		
		for($i = 0 ; $i< count($get_12month_user) ; $i++)
		{
			/* send mail to user */
			
			$subject = $this->response_message[$get_12month_user[$i]['language_code']]['DELETE_USER_SUBJECT'];
			$message = $this->response_message[$get_12month_user[$i]['language_code']]['DELETE_USER_MESSAGE'];
			
			$message_string = array("%firstname%");
			$replace_string   = array($get_12month_user[$i]['firstname']);
			$message = str_replace($message_string, $replace_string, $message);
			
			$this->common->send_email(EMAIL_FROM, EMAIL_NAME, $get_12month_user[$i]['email'], $subject, $message, NULL,$get_12month_user[$i]['language_code']);
			
			/*Get all alert of that user*/
			$user_alert = $this->checkins->getalertbyidwithuser(0,$get_12month_user[$i]['id'],2);
			for($j =0 ;$j<count($user_alert) ; $j++)
			{
				/*delete media using foreignid*/
				$this->mediafunc->deletemediabyforeignid($user_alert[$j]['id'],'2');
				
				/*delete alert using foreignid*/
				$this->checkins->deletealert($user_alert[$j]['id']);
			}
			
			
			/* Get all checkins*/
			$user_checkin = $this->checkins->getalertbyidwithuser(0,$get_12month_user[$i]['id'],1);
			for($j =0 ;$j<count($user_checkin) ; $j++)
			{
				/*delete media using foreignid*/
				$this->mediafunc->deletemediabyforeignid($user_checkin[$j]['id'],'1');
				
				/*delete alert using foreignid*/
				$this->checkins->deletecheckin($user_checkin[$j]['id']);
			}
			
			/* get contact list using usr id*/
			$contacts_list = $this->contacts->contactlistbyuser($get_12month_user[$i]['id']);
			for($j = 0 ; $j < count($contacts_list); $j++)
			{
				/*delete contactlist using list id*/
				$this->contacts->deletecontactlistbylistid($get_12month_user[$i]['id'], $contacts_list[$j]['contactlist_id']);
			}
			
			/* delete other user data (sos,OTP,oldpassword, login attempt, broadcast)*/
			$this->users->deleteuserdata($get_12month_user[$i]['id']);
		}
		
		/* Notification mail before 1 week*/
		/* 2= 12 month user data we have to delete it*/
		$get_week_user = $this->users->getuserdeletedata(2);
		
		$date_sec = strtotime(CURRENT_DATETIME);
	$date = date('m-d-Y',($date_sec + 604800)); 
	for($i = 0 ; $i< count($get_week_user) ; $i++)
	{
		$subject = $this->response_message[$get_week_user[$i]['language_code']]['DELETE_WEEK_SUBJECT'];
		$message = $this->response_message[$get_week_user[$i]['language_code']]['DELETE_WEEK_MESSAGE'];
		
		$message_string = array("%firstname%", "%date%");
		$replace_string   = array($get_week_user[$i]['firstname'], $date);
		$message = str_replace($message_string, $replace_string, $message);
		
		$this->common->send_email(EMAIL_FROM, EMAIL_NAME, $get_week_user[$i]['email'], $subject, $message, NULL,$get_week_user[$i]['language_code']);
		
	}
	
	
	/* Notification mail before 11 month*/
	/* 3= 11 month user data we have to delete it*/
	$get_11month_user = $this->users->getuserdeletedata(3);
	$date = date('m-d-Y',  strtotime("+1 months",strtotime(CURRENT_DATETIME)));
	for($i = 0 ; $i< count($get_11month_user) ; $i++)
	{
		
		$subject = $this->response_message[$get_11month_user[$i]['language_code']]['DELETE_MONTH_SUBJECT'];
		$message = $this->response_message[$get_11month_user[$i]['language_code']]['DELETE_MONTH_MESSAGE'];
		
		$message_string = array("%firstname%", "%date%");
		$replace_string   = array($get_11month_user[$i]['firstname'], $date);
		$message = str_replace($message_string, $replace_string, $message);
		
		$this->common->send_email(EMAIL_FROM, EMAIL_NAME, $get_11month_user[$i]['email'], $subject, $message, NULL,$get_11month_user[$i]['language_code']);
		
	}
	
	/* Delete mediya file which is older then 7 days.*/
	$this->mediafunc->deletesevendaymedia();
}
}
?>