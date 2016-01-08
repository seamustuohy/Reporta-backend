<?php
if (!defined('BASEPATH'))
{
	exit('No direct script access allowed');
}
/**
* Name:  Userinfo
*
*               Modified Function base on requirement 
* @package Sosrequest
* @version 1.0
*
*/
class Sosrequest extends CI_Controller
{

	public $bulkdata;
	public function __construct()
	{
		parent::__construct();
		
	}
	/**
	 * This function will use for display link on page to accept or reject sos request.
	 * It will first check user id and contact id, if both are valid then it will display page with two buttons : accept and reject SOS request. 
	 *
	 * @access 	public
	 * @return render view
	 */
	public function index()
	{
		$this->load->model('api/contacts');
		$this->load->model('api/common');
		$this->load->model('api/users');
		$data['content'] = $this->input->get();
		$contact_id = $this->common->decode($data['content']['cid']);
		$user_id = $this->common->decode($data['content']['uid']);
		/*Get contact detail*/
		$result = $this->contacts->getcontactdetailbyid($contact_id);
		/*Get user detail*/
		$result_user = $this->users->getuserdetail($user_id);
		$data['sos_enabled'] = $result[0]['sos_enabled'];
		$userdata = $result_user[0];

		$data['link_enabled'] = 1;
		if($userdata && $result)
		{
			$this->lang->load('mail', $result_user[0]['language_code']);
			$this->mail_template = $this->lang->language;
			$src = site_url('assets/include/iwmflogo.jpg');
			
			$data['text1'] = $this->mail_template['SOSREQUEST_TEXT1'];
			$message_string = array("%firstname%", "%lastname%");
			$replace_string   = array($userdata['firstname'], $userdata['lastname']);
			$data['text1'] = str_replace($message_string, $replace_string, $data['text1']);
			
			$data['text2'] = $this->mail_template['SOSREQUEST_TEXT2'];
			
			$data['text3'] = $this->mail_template['SOSREQUEST_TEXT3'];
			$message_string = array("%src%");
			$replace_string   = array($src);
			$data['text3'] = str_replace($message_string, $replace_string, $data['text3']);
			
			$data['text4'] = $this->mail_template['SOSREQUEST_TEXT4'];
			$sos_already_declined = $this->mail_template['SOSREQUEST_ALREADY_DECLINED'];
			$sos_already_accepted = $this->mail_template['SOSREQUEST_ALREADY_ACCEPTED'];
		}
		else
		{
			$this->load->view('error');
			exit;
		}
		
		$data['sos_already_declined'] = $sos_already_declined;
		$data['sos_already_accepted'] = $sos_already_accepted;

		$this->load->view('sosrequest',$data);
	}
	/**
	 * This function will use for accept SOS request
	 * It will change contact SOS status from pending to accept
	 *
	 * @access 	public
	 */
	public function sosaccept()
	{
		$this->load->model('api/common');
		$this->load->model('api/contacts');
		$this->load->model('api/users');
		
		$contact_id = $this->common->decode($this->input->post('contect_id'));
        /*Get Contact*/
		$result = $this->contacts->getcontactdetailbyid($contact_id);
		
		$updatedata['sos_enabled'] = '1';
		$updatedata['status'] = 1;
		/*Update Contact*/
		$result = $this->contacts->updatecontact($updatedata,$contact_id);
		
		echo $result_message ;
		
		exit;
	}
	/**
	 * This function will use for reject SOS request
	 * It will change contact SOS status from pending to reject
	 * @access 	public
	 */
	public function sosreject()
	{
		$this->load->model('api/common');
		$this->load->model('api/contacts');
		$this->load->model('api/users');
		
		$user_id = $this->common->decode($this->input->post('user_id'));
		$contact_id = $this->common->decode($this->input->post('contect_id'));
		
		$user_detail = $this->users->getuserdetail($user_id);
		
		$updatedata['sos_enabled'] = '0';
		$updatedata['status'] = '2';
		/*Update Contact*/
		$this->contacts->updatecontact($updatedata,$contact_id);
		
		$this->lang->load('mail', $user_detail[0]['language_code']);
		$this->mail_template = $this->lang->language;
		
		$result_message = $this->mail_template['SOS_REJECT'];

		echo $result_message;
		exit;
	}
}