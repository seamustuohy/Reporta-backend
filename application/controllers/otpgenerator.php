<?php
if (!defined('BASEPATH'))
{
	exit('No direct script access allowed');
}
/**
* Name:  Userinfo
*
* Description:  Class function use to generate otp
*               Modified Function base on requirement 
*
* @package Otpgenerator
* @version 1.0
*
*/
class Otpgenerator extends CI_Controller
{

	public $bulkdata;
	
	/**
	 * This function will use to check user id and contact id for otp generation.
	 * It will check contact id and user id from url using get method.
	 * If contact id and user id are correct, it will display otp link on page.
	 * @access 	public
	 * @return render view
	 */
	public function index()
	{
		$this->load->model('api/common');
		$this->load->model('api/contacts');
		$this->load->model('api/users');
		$data['content'] = $this->input->get();
		
		$user_id = $this->common->decode($data['content']['uid']);
		$contact_id = $this->common->decode($data['content']['cid']);
		/*Get contacts data*/
		$result = $this->contacts->getcontactforotp($user_id,$contact_id);
		$data['statusotp'] = '0';
		/*Get user data*/
		$result_user = $this->users->getuserdetail($user_id);
		if(count($result)>0 && count($result_user))
		{
			$data['statusotp'] = '1';
			
			$this->lang->load('mail', $result_user[0]['language_code']);
			$this->mail_template = $this->lang->language;
			
			$data['text'] = $this->mail_template['OTPGENERATOR_TEXT'];
			$data['text1'] = $this->mail_template['OTPGENERATOR_TEXT1'];
			$message_string = array("%firstname%", "%lastname%");
			$replace_string   = array($result_user[0]['firstname'], $result_user[0]['lastname']);
			$data['text1'] = str_replace($message_string, $replace_string, $data['text1']);
			
			$data['text2'] = $this->mail_template['OTPGENERATOR_TEXT2'];
			
		}
		else
		{
			$this->load->view('error');
			exit;
		}
		$data['otp'] ='';
		
		$this->load->view('otpgenerator',$data);
	}
	/**
	 * This function will use for otp generation.
	 * @access 	public
	 */
	public function generate()
	{
		$this->load->model('api/common');
		$this->load->model('api/history');
		
		$user_id = $this->common->decode($this->input->post('user_id'));
		$contact_id = $this->common->decode($this->input->post('contect_id'));
		/*Get user data*/
		$result = $this->history->getsosbyuserid($user_id);
		/*Genreat otp */
		$otp = rand(100000, 999999);
		if($result[0]['broadcast_id'] && $result[0]['sos_enabled'])
		{
			$friendsdata[] = array(
				'broadcast_id' => $result[0]['broadcast_id'],
				'contact_id' => $contact_id,
				'user_id' => $user_id,
				'time' => CURRENT_DATETIME,
				'otp' => $otp,
				'otp_status' => '1',
				'created_on' => CURRENT_DATETIME,
				);
			
            /*Insert otp */
			$this->history->createfriendsdetail($friendsdata);
		}
		else
		{
			$friendsdata[] = array(
				'contact_id' => $contact_id,
				'user_id' => $user_id,
				'time' => CURRENT_DATETIME,
				'otp' => $otp,
				'otp_status' => '1',
				'created_on' => CURRENT_DATETIME,
				);
            /*Genreat otp*/
			$this->history->createfriendsdetail($friendsdata);
		}
		$text = "<b>".$otp."</b>";
		echo $text;
	}
}
