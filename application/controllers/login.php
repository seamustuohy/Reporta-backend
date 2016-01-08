<?php
if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}
/**
* Name:  Login
*
* @package Login
* @version 1.0
*/
class Login extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('api/users');
        $this->load->library('session');
        $this->load->library('bcrypt');
        $this->load->model('api/common');
        $this->load->model('api/api');
        $this->lang->load('message', 'EN');
        $this->response_message = $this->lang->language;
    }
	/**
	 * This function will use for admin login
	 * If username and password are correct it send phone verification code.
	 * @access	public
	 * @return render view
	 */
    public function index()
    {
        $session = $this->session->userdata('admin_id');
        $data['errormsg']="";
		
		$this->lang->is_loaded = array();
		$this->lang->language = array();
		$this->lang->load('message', 'EN');
		$this->response_message = $this->lang->language;
		
        if(!empty($session))
        {
            redirect($this->input->get('last_url'));
        }
        
        if( $this->input->server('REQUEST_METHOD') == 'POST')
        {
            $this->form_validation->set_rules('username', 'User Name', 'required');
            $this->form_validation->set_rules('password', 'Password', 'required');
            $data['errormsg']="";
            if($this->form_validation->run() == FALSE)
            {
             $this->load->view('login',$data);
         }
         else
         {
            
            $username = $this->input->post('username');
			/*Get user data base on username*/
            $result_username = $this->users->getuserdetailbyusername($username);
            
            if($result_username)
            {
		      /*Add salt*/
              $salt_password = $this->input->post('password'). $result_username[0]['salt'];
              $password = md5($salt_password);
              /* checkpassword using bcrypt*/
              $hash_password = $this->bcrypt->check_password($salt_password, $result_username[0]['password']);
              /* checkpassword*/
              if(($password == $result_username[0]['password'] || $hash_password == TRUE) && ($result_username[0]['type'] == '1' || $result_username[0]['type'] == '3'))
              {
                $this->session->set_userdata('id', $result_username[0]['id']);
                $this->session->set_userdata('phone', $result_username[0]['phone']);
                $userdata['forgotpassword_code'] = rand(100000, 999999);
                
				/*Update user*/
                $this->users->updateuser($userdata, $result_username[0]['id']);
                $message = $this->response_message['ADMIN_PHONE_CODE'].$userdata['forgotpassword_code'];
                
				/*send sms*/
                $this->common->send_sms(SMS_FROM,$result_username[0]['phone'],$message);
                
                redirect(site_url('login/phoneverification'), 'refresh');
                exit;
            }
            else
            {
                $data['errormsg'] = $this->response_message['INVALID_PASSWORD'];
                $this->load->view('login',$data);
            }
        }
        else
        {
            $data['errormsg'] = $this->response_message['INVALID_USERNAME'];
            $this->load->view('login',$data);
        }
    }
}
else
{
    $this->load->view('login',$data);    
}
}

	/**
	 * This function will use to check admin phone verification code for login 
	 * If code is correct then start session and redirect to dashboard(home) page.
	 * @access	public
	 * @return render view
	 */
public function phoneverification()
{
	session_start();
    $user_id = $this->session->userdata('id');
    if(empty($user_id))
    {
        redirect(site_url('login'), 'refresh');
    }
    
    if($this->input->server('REQUEST_METHOD') == 'POST')
    {
        $data['id'] = $this->session->userdata('id');
        $data['phone'] = $this->session->userdata('phone');
        
        $this->form_validation->set_rules('code', 'Code', 'required');
        if($this->form_validation->run() == FALSE)
        {
            $this->load->view('phoneverification',$data);
        }
        else
        {
            $code = $this->input->post('code');
            /* check phone verification code*/
            $result = $this->users->phonecodeauth($data['id'], $data['phone'],$code);
            if($result)
            {
                $this->session->set_userdata('admin_id', $result[0]['id']);
                $this->session->set_userdata('firstname', $result[0]['firstname']);
                $this->session->set_userdata('lastname', $result[0]['lastname']);
                $this->session->set_userdata('email', $result[0]['email']);
                $this->session->set_userdata('type',$result[0]['type'] );
                $userdata['forgotpassword_code'] = '';
                
				/*Update user*/
                $this->users->updateuser($userdata, $result[0]['id']);
                
                redirect(site_url('home'), 'refresh');
                exit;
            }
            else
            {
                $data['errormsg'] = "Invalid Code.";
                $data['message'] = '';
                $data['id'] = $this->session->userdata('id');
                $data['phone'] = $this->session->userdata('phone');
                $this->load->view('phoneverification',$data);
            }
        }
    }
    else
    {
        $data['errormsg'] = '';
        $data['message'] = '';
        $data['id'] = $this->session->userdata('id');
        $data['phone'] = $this->session->userdata('phone');
        $this->load->view('phoneverification',$data);
    }
}
	/**
	 * This function will use for admin logout
	 *
	 * @access	public
	 * @return render view
	 */
public function logout()
{
    $this->session->flashdata('admin_id');
    $this->session->flashdata('firstname');
    $this->session->flashdata('lastname');
    $this->session->flashdata('email');
    $this->session->flashdata('status');
    $this->session->sess_destroy();
    redirect(site_url('login'), 'refresh');
    
}
    /**
	 * This function will use to Change Password
	 * It will check below criteria
	 *     - Password string should contain at least one special character, at least one number and at least one upper case letter and minimum 8 chars.
     *     - Password string should not contain username.
     *     - Password string should not contain past three password.
	 *
	 * @access	public
	 * @return render view
	 */
public function changepassword()
{
    $user_id = $this->session->userdata('admin_id');
    if(empty($user_id))
    {
        redirect(site_url('login'), 'refresh');
    }
    
    if( $this->input->server('REQUEST_METHOD') == 'POST')
    {
        $this->form_validation->set_rules('currentpassword', 'Current Password', 'required');
        $this->form_validation->set_rules('newpassword', 'New Password', 'required|min_length[8]|max_length[25]|matches[confirmpassword]|regex_match[((?=.*\\d)(?=.*[A-Z])(?=.*?[#?!@$%^&*-_+.]).{6,25})]');
        $this->form_validation->set_rules('confirmpassword', 'Confirm Password', 'required');
        $this->form_validation->set_message('regex_match','Please enter a valid password, which should have At least one special character, At least one number and at least one Upper case letter and minimum 8 chars.'); 
        
        $data['errormessage'] = "";
        $data['message'] = "";
        if($this->form_validation->run() == FALSE)
        {
            $data['errormessage'] = "";
            $this->load->view('changepassword',$data);
        }
        else
        {
            $result = $this->users->getuserdetail($user_id);
            $salt_password = $this->input->post('currentpassword').$result[0]['salt'];
            $currentpassword = md5($this->input->post('currentpassword').$result[0]['salt']);
            
            $hash_password = $this->bcrypt->check_password($salt_password, $result[0]['password']);
            
            if(isset($result) && count($result) > 0 && ($result[0]['type'] == '1' || $result[0]['type'] == '3') && ($result[0]['password'] == $currentpassword || $hash_password == TRUE))
            {
              $newpassword = $this->input->post('newpassword').$result[0]['salt'];
              
              if(stripos($newpassword,$result[0]['username']) === FALSE && stripos($newpassword,$result[0]['firstname']) === FALSE && stripos($newpassword,$result[0]['lastname']) === FALSE)
              {
				/*chake new password with last 3 password*/
                 $result_password = $this->users->getoldpassword($result[0]['id']);
                 $password_match = 0;
                 for($pass_i = 0 ;$pass_i < count($result_password); $pass_i++)
                 {    
                     if($this->bcrypt->check_password($newpassword,$result_password[$pass_i]['password']))
                     {
                        $password_match = 1;
                        break;
                    }
                }
				/*check new password match with last 3 password or not*/
                if($password_match == 0)
                {
                 $userdata['password'] = $this->bcrypt->hash_password($newpassword);
                 $userdata['password_change'] = CURRENT_DATETIME;
                 $count_username = $this->users->updateuser($userdata, $result[0]['id']);
                 if($count_username > 0)
                 {
                    $oldpass['user_id'] = $result[0]['id'];
                    $oldpass['password'] = $userdata['password'];
                    
					/*Insert old password*/
                    $this->users->insertoldpassword($oldpass);
                    
                    $data['message'] = 'Password Change Successfully.';
                    $this->load->view('changepassword',$data);
                }
                else
                {
                    $data['errormessage'] = $this->response_message['PASSWORD_NOT_CHANGE'];
                    $this->load->view('changepassword',$data);
                }
            }
            else
            {
             $data['errormessage'] = $this->response_message['MATCH_OLD_PASS'];
             $this->load->view('changepassword',$data);
         }
     }
     else
     {
         
         $data['errormessage'] = $this->response_message['PASSWORD_NOT_NAME'];	
         $this->load->view('changepassword',$data);
     }
 }
 else
 {
    $data['errormessage'] = $this->response_message['ENTER_CORRECT_PASSWORD'];
    $this->load->view('changepassword',$data);
}
}
}
else
{
    $data['id'] = $user_id;
    $data['errormessage'] = "";
    $data['message'] = "";
    $this->load->view('changepassword',$data);
}

}
/**
 * This function will use for forgotpassword
 * check mail id and send reset password link in mail.
 *
 * @access	public
 * @return render view
 */
public function forgotpassword()
{
    $session = $this->session->userdata('admin_id');
    
    if(!empty($session))
    {
        
        redirect($this->input->get('last_url'));
    }
    
    if($this->input->server('REQUEST_METHOD') == 'POST')
    {
		$this->lang->is_loaded = array();
		$this->lang->language = array();
		$this->lang->load('mail', 'EN');
		$this->mail_template = $this->lang->language;
		
        $this->form_validation->set_rules('email', 'Email', 'required');
        $data['errormessage'] = "";
        $data['message'] = "";
        if($this->form_validation->run() == FALSE)
        {
            $data['errormessage'] = "";
            $this->load->view('forgotpassword',$data);
        }
        else
        {
            $email = $this->input->post('email');
			/*Get user data using email id*/
            $result = $this->users->getuserdetailbyemail($email);
            if(isset($result) && count($result) > 0 && ($result[0]['type'] == '1' || $result[0]['type'] == '3'))
            {
                $userdata['forgotpassword_code'] = $this->users->randomPassword();
                $userdata['forgotpassword_code'] = $userdata['forgotpassword_code']."_".strtotime(CURRENT_DATETIME);
                $count_username = $this->users->updateuser($userdata, $result[0]['id']);
                if($count_username > 0)
                {
                    $link = "<a href = ".site_url() ."newpassword?uid=".$this->common->encode($result[0]['id'])."&fc=".$this->common->encode($userdata['forgotpassword_code'])." mc:disable-tracking >click here</a>";
                    /*send mail to reset password*/
                    
					$subject = $this->mail_template['FORGOTPASSWORD_SUBJECT'];
					$email_message = $this->mail_template['FORGOTPASSWORD_MESSAGE'];
					$message_string = array("%email%", "%username%", "%link%");
					$replace_string   = array($result[0]['email'], $result[0]['username'], $link);
					$email_message = str_replace($message_string, $replace_string, $email_message);
                 
					
					
					$this->common->send_email(EMAIL_FROM, EMAIL_NAME,$result[0]['email'],$subject,$email_message);
                    
                    $data['message'] = $this->response_message['PASSWORD_REQUEST_SEND'];
                    $this->load->view('forgotpassword',$data);
                }
                else
                {
                    $data['errormessage'] = $this->response_message['PASSWORD_NOT_CHANGE'];
                    $this->load->view('forgotpassword',$data);
                }
            }
            else
            {
                $data['errormessage'] = $email . " Not Exist. Enter correct Email";
                $this->load->view('forgotpassword',$data);
            }
        }
    }
    else
    {
        $data['errormessage'] = "";
        $data['message'] = "";
        $this->load->view('forgotpassword',$data);      
    }
}
}
