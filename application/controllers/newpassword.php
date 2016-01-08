<?php
if (!defined('BASEPATH'))
{
	exit('No direct script access allowed');
}
/**
* Name:  Userinfo
*
* Description:  This Class function will use to update password 
*               Modified Function base on requirement 
*
* @package Newpassword
* @version 1.0
*
*/
class Newpassword extends CI_Controller
{
	/**
	 * Constructor
	 *
	 * @access 	public
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->library('bcrypt');
		
		$this->lang->is_loaded = array();
		$this->lang->language = array();
		$this->lang->load('message', 'EN');
		$this->response_message = $this->lang->language;
		
	}
	/**
	 * This function will use to change password
	 * It will check token and user id from url using get method.
	 * If url contain wrong token or user id then it will not display form and provide the "Invalid Link" message 
	 *
	 * @access 	public
	 * @return render view
	 */
	public function index()
	{
		$this->load->library('form_validation');
		$this->load->model('api/users');
		$this->load->model('api/common');
		$data['content'] = $this->input->get();
		
		$data['status']=0;
		/*Decode userid*/
		$user_id = $this->common->decode($data['content']['uid']);
        /*Decode token*/
		$data['forgotpassword_code'] = $this->common->decode($data['content']['fc']);
		$tokentime = explode('_', $data['forgotpassword_code']);
		/*check token expire*/
		if(($tokentime[1] + 3600) > strtotime(CURRENT_DATETIME))
		{
			$result = $this->users->checkforgotcodebyid($user_id, $data['forgotpassword_code']);
			$data['user_id'] = $data['content']['uid'];
			$data['fc'] = $data['content']['fc'];
		}
		$data['errormessage'] = "";
		$data['message'] = "";
		
		if($result)
		{
			$data['status']=1;
		}
		$this->load->view('newpassword', $data);
	}
	/**
	 * This function will use to update password
	 * It will check token and user id from form post data.
	 * If token or user id will correct then it will update password.
	 * It will check below criteria to update password 
	 *     - Password string should contain at least one special character, at least one number and at least one upper case letter and minimum 8 chars.
     *     - Password string should not contain username.
     *     - Password string should not contain past three password.
	 * @access 	public
	 * @return render view
	 */
	public function updatepassword()
	{
		$this->load->library('form_validation');
		$this->load->model('api/users');
		$this->load->model('api/common');
		$uid = $this->input->post('user_id');
		$fc = $this->input->post('fc');
		$user_id = $this->common->decode($uid);
		$fc = $this->common->decode($fc);
		$tokentime = explode('_', $fc);
		/*chake token expire*/
		if(($tokentime[1] + 3600) > strtotime(CURRENT_DATETIME))
		{
			$result_fc = $this->users->checkforgotcodebyid($user_id, $fc);
		}
		if(($this->input->server('REQUEST_METHOD') != 'POST') || empty($result_fc || count($result_fc) == 0))
		{
			$data['status']=0;
			$data['errormessage'] = "";
			$data['message'] = "";
			$data['user_id'] = '';
			$data['fc'] = '';
			$this->load->view('newpassword', $data);
		}
		else
		{
			$data['status']=0;
			$this->load->library('form_validation');
			$this->load->model('api/users');
			$this->load->model('api/common');

			$this->form_validation->set_rules('password', 'Password', 'required|min_length[8]|max_length[25]|matches[repassword]|regex_match[((?=.*\\d)(?=.*[A-Z])(?=.*?[#?!@$%^&*-_+.]).{6,25})]');
			$this->form_validation->set_rules('repassword', 'password confirmation', 'trim');
			$this->form_validation->set_message('regex_match','Please enter a valid password, which should have At least one special character, At least one number and at least one Upper case letter and minimum 8 chars.'); 
			if ($this->form_validation->run() == FALSE && $this->input->server('REQUEST_METHOD') == 'POST' )
			{
				$data = array();
				$data['user_id'] = $this->input->post('user_id');
				$data['errormessage'] = "";
				$data['message'] = "";
				$data['errormsg'] = "";
				$data['Updatemsg'] = "";
				$data['status']=1;
				$data['user_id'] = $this->input->post('user_id');
				$data['fc'] = $this->input->post('fc');
				$this->load->view('newpassword' , $data);    
			}
			elseif($this->input->server('REQUEST_METHOD') == 'POST')
			{
				/* Add Userdata*/
				$userdata['forgotpassword_code'] = '';
				$new_password = $this->input->post('password');
				$user_id = $this->input->post('user_id');
				$user_id = $this->common->decode($user_id);
				/*Get User detail*/
				$result = $this->users->getuserdetail($user_id);

				if(stripos($new_password,$result[0]['username']) === FALSE && stripos($new_password,$result[0]['firstname']) === FALSE && stripos($new_password,$result[0]['lastname']) === FALSE)
				{
					$new_password = $new_password.$result[0]['salt'];
					$result_password = $this->users->getoldpassword($result[0]['id']);
					$password_match = 0;
					/* check new password with last 3 password*/
					for($pass_i = 0 ;$pass_i < count($result_password); $pass_i++)
					{    
						if($this->bcrypt->check_password($new_password,$result_password[$pass_i]['password']))
						{
							$password_match = 1;
							break;
						}
					}
					if($password_match == 0)
					{
						$userdata['password'] = $this->bcrypt->hash_password($new_password);
						$result_update = $this->users->updateuser($userdata, $user_id);
						if($result_update)
						{
							$subject='';
							$this->lang->load('mail', $result[0]['language_code']);
							$this->mail_template = $this->lang->language;
							
							$subject = $this->mail_template['PASSWORD_CHANGE_SUBJECT'];
							$email_message = $this->mail_template['PASSWORD_CHANGE_MESSAGE'];
							/*Send mail */
							$this->common->send_email(EMAIL_FROM, EMAIL_NAME,$result[0]['email'],$subject,$email_message,NULL,$result[0]['language_code']);
						}
						$oldpass['user_id'] = $user_id;
						$oldpass['password'] = $userdata['password'];
						/*insert oldpassword*/
						$this->users->insertoldpassword($oldpass);
						
						$data['errormessage'] = "";
						$data['errormsg'] = "";
						$data['Updatemsg'] = "";
						$data['status']=2;
						$data['message'] = $this->response_message['PASS_UPDATED'];
						$this->load->view('newpassword' , $data);
					}
					else
					{
						$data['user_id'] = $this->input->post('user_id');
						$data['errormessage'] = $this->response_message['MATCH_OLD_PASS'];
						$data['message'] = "";
						$data['errormsg'] = "";
						$data['Updatemsg'] = "";
						$data['status']=1;
						$data['user_id'] = $this->input->post('user_id');
						$data['fc'] = $this->input->post('fc');
						$this->load->view('newpassword' , $data);  
					}
				}
				else
				{
					$data['user_id'] = $this->input->post('user_id');
					$data['errormessage'] = $this->response_message['PASSWORD_NOT_NAME'];
					$data['message'] = "";
					$data['errormsg'] = "";
					$data['Updatemsg'] = "";
					$data['status']=1;
					$data['user_id'] = $this->input->post('user_id');
					$data['fc'] = $this->input->post('fc');
					$this->load->view('newpassword' , $data);  
				}
			}
			else
			{
				$data['content'] = $this->input->get();
				$data['status']=0;
				$data['errormessage'] = "";
				$data['message'] = "";
				$data['user_id'] = $data['content']['uid'];
				
				$this->load->view('newpassword', $data);
			}
		}
	}
}