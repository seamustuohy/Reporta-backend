<?php
/**
* Name:  Users
* @package Users
* @version 1.0
*/
class Users extends CI_Model
{
	public $users = 'iwmf_user.users';
	public $devicetoken = 'devicetoken';
	public $friends = 'friends';
	public $broadcast = 'broadcast';
	public $sos = 'sos';
	public $contactlists = 'contactlists';
	public $checkin = 'checkin';
	public $headertoken = 'headertoken';
	public $login_attempt = 'login_attempt';
	public $oldpass = 'oldpassword';
	public $checkinhistory = 'checkinhistory';
	public $alerts = 'alerts';
	
	function __construct()
	{
		/* Call the Model constructor*/
		parent::__construct();
	}
	/**
	 * authenticate user
	 *
	 * @access 	public
	 * @param int user id
	 * @param string User password
	 * @return array
	 */
	public function authbyidpassword($user_id, $password)
	{
		$this->db->select();
		$this->db->from($this->users);
		$this->db->where('id', $user_id);
		$this->db->where('password', $password);
		
		$result = $this->db->get();
		return $result->result_array();
	} 
	/**
	 * checkotp
	 *
	 * @access 	public
	 * @param int user id
	 * @param string User password
	 * @param string otp 
	 * @return array
	 */
	public function unlockappauthbyuserid($user_id, $password, $otp)
	{
		$this->db->select();
		$this->db->from($this->users." AS u");
		$this->db->join($this->friends." AS f", 'f.user_id = u.id', 'LEFT');
		
		$this->db->where('u.id', $user_id);
		if(isset($password)&& !empty($password))
		{
			$this->db->where('u.password', $password);
		}
		
		$this->db->where('f.otp', $otp);
		$this->db->where('f.otp_status', '1');
		
		$result = $this->db->get();
		return $result->result_array();
	}
	/**
	 * update otp status
	 *
	 * @access 	public
	 * @param int user id
	 * @param string otp
	 * @param int checkin id 
	 * @return int
	 */
	public function updateotpstatus($user_id, $otp,$checkin_id)
	{
		$this->db->where('user_id', $user_id);
		$this->db->where('otp', $otp);
		$this->db->where('otp_status','1');
		$UpdateData_friends['otp_status'] = '0';
		$this->db->update($this->friends, $UpdateData_friends);
		
		$this->db->delete($this->sos, array('user_id' => $user_id, 'sos_enabled' => '1'));
		
		
		$this->db->where('user_id', $user_id);
		$this->db->where('checkin_enabled','1');
		$UpdateData_checkin['checkin_enabled'] = '0';
		$this->db->update($this->checkin, $UpdateData_checkin);
		
		$this->db->where('id', $user_id);
		$UpdateData_user['status'] = 1;
		$this->db->update($this->users, $UpdateData_user);
		
		return $this->db->affected_rows();
	}
	/**
	 * get misscheckin data bu user
	 *
	 * @access 	public
	 * @param int user id
	 * @return array
	 */
	public function getmisscheckinbyuser($user_id)
	{
		$this->db->select();
		$this->db->from($this->checkin);
		$this->db->where('user_id', $user_id);
		$this->db->where('checkin_enabled','1');
		$result = $this->db->get();
		return $result->result_array();
	}
	/**
	 * Get device token
	 *
	 * @access 	public
	 * @param int user id
	 * @return array
	 */
	public function getdevicetoken($user_id)
	{
		$this->db->select();
		$this->db->from($this->devicetoken);
		$this->db->where('user_id', $user_id);
		
		$result = $this->db->get();
		return $result->result_array();
	}
	/**
	 * get user language
	 *
	 * @access 	public
	 * @param int user id
	 * @return array
	 */
	public function getuserlanguage($user_id)
	{
		$this->db->select('language_code');
		$this->db->from($this->users);
		$this->db->where('id', $user_id);
		
		$result = $this->db->get();
		return $result->row();
	}
	/**
	 * Insert user
	 *
	 * @access 	public
	 * @param array user data
	 * @return int
	 */
	public function createuser($InsertData)
	{
		$this->db->insert($this->users, $InsertData);
		return $this->db->insert_id();
	}
	/**
	 * Update user
	 *
	 * @access 	public
	 * @param array UpdateData
	 * @param int user id
	 * @return int
	 */
	public function updateuser($UpdateData, $user_id)
	{
		$this->db->where('id', $user_id);
		$this->db->update($this->users, $UpdateData);
		return $this->db->affected_rows();
	}
	/**
	 * Get user detail by id
	 *
	 * @access 	public
	 * @param int user id
	 * @return array
	 */
	public function getuserdetailbyid($user_id)
	{
		$this->db->select('username, email, firstname, lastname,language_code, language,gender,gender_type, phone, jobtitle, affiliation_id, freelancer, origin_country, working_country, sendmail, status,app_encryption_key,type,send_update_repota_email, created_on, updated_on');
		$this->db->from($this->users." AS u");
		
		$this->db->where('u.id', $user_id);
		
		$result = $this->db->get();
		return $result->result_array();
	}
	/**
	 * check email id already exists or not
	 *
	 * @access 	public
	 * @param string email
	 * @return array
	 */
	public function getuserdetailbyemail($email)
	{
		$this->db->select();
		$this->db->from($this->users." AS u");
		
		$this->db->where('u.email', $email);
		
		$result = $this->db->get();
		return $result->result_array();
	}
	/**
	 * chake username already exists or not
	 *
	 * @access 	public
	 * @param string username
	 * @return array
	 */
	public function getuserdetailbyusername($username)
	{
		$this->db->select();
		$this->db->from($this->users." AS u");
		
		$this->db->where('u.username', $username);
		
		$result = $this->db->get();
		return $result->result_array();
	}
	
	/**
	 * give all detail of user by user_id
	 *
	 * @access 	public
	 * @param int user id
	 * @return array
	 */
	public function getuserdetail($user_id)
	{
		$this->db->select();
		$this->db->from($this->users);
		$this->db->where('id', $user_id);
		$result = $this->db->get();
		return $result->result_array();
	}
	/**
	 * get user detail by contact lists
	 *
	 * @access 	public
	 * @param int contactlists
	 * @return array
	 */
	public function getuserdetailbycontactlists($contactlists)
	{
		$this->db->select();
		$this->db->from($this->contactlists);
		$this->db->where_in('id', $contactlists);
		$this->db->group_by("user_id"); 
		$result = $this->db->get();
		return $result->result_array();
	}
	/**
	 * check forgot password code
	 *
	 * @access 	public
	 * @param int user id
	 * @param string validation code
	 * @return array
	 */
	public function checkforgotcodebyid($user_id,$forgotpassword_code)
	{
		$this->db->select();
		$this->db->from($this->users);
		$this->db->where('id', $user_id);
		$this->db->where('forgotpassword_code', $forgotpassword_code);
		$this->db->where('forgotpassword_code !=',"");
		$result = $this->db->get();
		return $result->result_array();
	}
	
	/**
	 * get new random password
	 *
	 * @access 	public
	 * @param int length of string
	 * @return string
	 */
	public function randomPassword($length = 4)
	{
		$cstrong = TRUE;
		$bytes = openssl_random_pseudo_bytes($length, $cstrong);
		$hex   = bin2hex($bytes);
		
		if(!$cstrong || $bytes == FALSE)
		{
			return $this->randomPassword($length);
		}
		else
		{
			return $hex;
		}
	}
	/**
	 * insert device token
	 *
	 * @access 	public
	 * @param array users device data
	 * @return int
	 */
	public function createdevicetoken($InsertData)
	{
		$this->db->insert($this->devicetoken, $InsertData);
		return $this->db->insert_id();
	}
	/**
	 * check device token
	 *
	 * @access 	public
	 * @param int user id
	 * @param string device token
	 * @param int type of device (1=ios,2=android)
	 * @return array
	 */
	public function checkdevicetoken($user_id, $devicetoken, $type)
	{
		$this->db->select();
		$this->db->from($this->devicetoken);
		
		$this->db->where('user_id', $user_id);
		$this->db->where('devicetoken', $devicetoken);
		$this->db->where('type', $type);
		
		$result = $this->db->get();
		return $result->result_array();
	}
	/**
	 * check username and password
	 *
	 * @access 	public
	 * @param string username
	 * @param string User password
	 * @param char user type ('1'=>admin, 2=>app user, 3=>supper admin)
	 * @return array
	 */
	public function checkloginauth($username, $password, $type = '2')
	{
		
		$this->db->select();
		$this->db->from($this->users." AS u");
		
		$this->db->where('u.username', $username);
		$this->db->where('u.password', $password);
		
		if($type != '2')
		{
			$this->db->where('u.type', $type);
		}
		$result = $this->db->get();
		
		return $result->result_array();
	}
	/**
	 * Check phone code
	 *
	 * @access 	public
	 * @param int user id
	 * @param string phone number
	 * @param string verification code
	 * @return array
	 */
	public function phonecodeauth($user_id,$phone,$code)
	{
		$this->db->select();
		$this->db->from($this->users." AS u");
		
		$this->db->where('u.id', $user_id);
		$this->db->where('u.forgotpassword_code', $code);
		$this->db->where('u.phone', $phone);
		$this->db->where_in('u.type', array('1','3'));
		$result = $this->db->get();
		
		return $result->result_array();
	}
	
	/**
	 * check user last checkin data
	 *
	 * @access 	public
	 * @param int user id
	 * @return array
	 */
	public function lockcheckinstatusbyuserid($user_id)
	{
		$this->db->select();
		$this->db->from($this->checkin.' AS c');
		$this->db->where("c.checkin_enabled",'1');
		$this->db->where("c.status",5);
		$this->db->where("c.user_id",$user_id);
		$this->db->order_by('id', 'DESC');
		$this->db->limit('1');
		$result = $this->db->get();
		return $result->result_array();
	}
	
	/**
	 * check sos lastcheckin statusis lock or not
	 *
	 * @access 	public
	 * @param int user id
	 * @return array
	 */
	public function locksosstatusbyuserid($user_id)
	{
		$this->db->select();
		$this->db->from($this->sos.' AS s');
		$this->db->where("s.sos_enabled",'1');
		$this->db->where("s.user_id",$user_id);
		$this->db->order_by('id', 'DESC');
		$this->db->limit('1');
		$result = $this->db->get();
		return $result->result_array();
		
	}
	/**
	 * Get lase four user password
	 *
	 * @access 	public
	 * @param int user id
	 * @return array
	 */
	public function getoldpassword($user_id)
	{
		$this->db->select();
		$this->db->from($this->oldpass);
		$this->db->where("user_id",$user_id);
		$this->db->order_by("oldpassword_id", "desc"); 
		$this->db->limit(4);
		$result = $this->db->get();
		return $result->result_array();
		
	}
	
	/**
	 * insert into oldpassword
	 *
	 * @access 	public
	 * @param array password of user 
	 * @return int
	 */
	public function insertoldpassword($InsertData)
	{
		$this->db->insert($this->oldpass, $InsertData);
		return $this->db->insert_id();
	}
	
	/**
	 * insert data in header table
	 *
	 * @access 	public
	 * @param array header token data
	 * @return int
	 */
	public function insertheaderdata($InsertData)
	{
		$this->db->insert($this->headertoken, $InsertData);
		return $this->db->insert_id();
	}
	/**
	 * delete data from headertoken
	 *
	 * @access 	public
	 * @param int user id
	 * @return int
	 */
	public function deleteheaderdata($user_id)
	{
		$this->db->where("user_id", $user_id);
		$this->db->delete($this->headertoken);
		return $this->db->affected_rows();
	}
	/**
	 * delete data from headertoken
	 *
	 * @access 	public
	 * @param int user id
	 * @param string  devicetoken
	 * @return int
	 */
	public function deleteheaderdatabydeviceid($devicetoken,$user_id)
	{
		$this->db->where("devicetoken", $devicetoken);
		$this->db->delete($this->headertoken);
		
		$UpdateData['devicetoken'] = '';
		$this->db->where('devicetoken', $devicetoken);
		$this->db->update($this->users, $UpdateData);
		
		$this->db->where('devicetoken', $devicetoken);
		$this->db->update($this->checkin, $UpdateData);
		
		$this->db->where("user_id", $user_id);
		$this->db->delete($this->headertoken);

		return $this->db->affected_rows();
	}
	/**
	 * get user token
	 *
	 * @access 	public
	 * @param int user id
	 * @return array
	 */
	public function getheadertoken($user_id)
	{
		$this->db->select();
		$this->db->from($this->headertoken);
		$this->db->where("user_id", $user_id);
		$this->db->limit('1');
		$result = $this->db->get();
		return $result->result_array();
	}
	/**
	 * This funciton generate token
	 *
	 * @access 	public
	 * @param string devicetoken
	 * @return string
	 */
	public function generatetoken($device_id)
	{
		$api_version = $this->uri->segment(1);
		if($api_version == 'api2')
		{
			$tmptoken = str_replace(array(" ","-","/",";",".","(",")",","), '', $_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT']);
		}
		else
		{
			$tmptoken = str_replace(array(" ","-","/",";",".","(",")",","), '', $_SERVER['HTTP_USER_AGENT']);
		}
		$tmptoken = $device_id.$tmptoken;
		$token = sha1(stripslashes($tmptoken));
		return $token;
	}
	/**
	 * functions get check secret token number is valid or not
	 *
	 * @access 	public
	 * @param array header data
	 * @return array
	 */
	public function getsecret($params)
	{
		
		$this->load->driver('cache');
		
		$user_id 	= $params['user_id'];
		$token 		= $params['headertoken'];
		$devicetoken 	= $params['devicetoken'];
		try
		{
			$this->db->select();
			$this->db->from($this->headertoken);
			$this->db->where("user_id",$user_id);
			$this->db->where("token",$token);
			$this->db->where("devicetoken",$devicetoken);
			$this->db->order_by('headertoken_id', 'DESC');
			$this->db->limit('1');
			$resulttoken = $this->db->get();
			$result_token = $resulttoken->result_array();
			
			if(!empty($result_token))
			{
				if($token == $this->generatetoken($devicetoken) && $token == $result_token[0]['token'])
				{
					$response = array('status' => '1', 'message' => SUCCESS.': Token validated');
				}
				else
				{
					$response = array('status' => '0', 'message' => FAILED.': Access deny, token not validated');
				}
			}
			else
			{
				$response = array('status' => '0', 'message' => 'Token not validated');
			}
		}
		catch(Exception $e)
		{
			$response = array('status' => '0', 'message' => FAILED.': '.$e->getMessage());
			throw new Exception($e->getMessage());
		}        
		return $response;
	}
	/**
	 * Get loginattempt
	 *
	 * @access 	public
	 * @param int user id
	 * @param string devicetoken
	 * @return array
	 */
	public function getloginattempt($user_id,$devicetoken)
	{
		$this->db->select();
		$this->db->from($this->login_attempt);
		$this->db->where("user_id",$user_id);
		$this->db->where("devicetoken",$devicetoken);
		$resulttoken = $this->db->get();
		return $resulttoken->result_array();
	} 
	/**
	 * Insert loginattempt
	 *
	 * @access 	public
	 * @param array login attempt data
	 * @return int
	 */
	public function insertloginattempt($InsertData)
	{
		$this->db->insert($this->login_attempt, $InsertData);
		return $this->db->insert_id();
	}
	/**
	 * update loginattempt
	 *
	 * @access 	public
	 * @param array count login attempt.
	 * @param int login attempt id
	 * @return void
	 */
	public function updateloginattempt($UpdateData,$login_attempt_id)
	{
		$this->db->where('login_attempt_id', $login_attempt_id);
		$this->db->update($this->login_attempt, $UpdateData);
	}
	/**
	 * Get user data base on last login time
	 *
	 * @access 	public
	 * @param int  value(1=>not login since last 12 month, 2=>onr week before complementing 12 month, 
	 *                    3=> not login since last 11 month,4=> list of data  which delete after one day)
	 * @return array
	 */
	public function getuserdeletedata($type)
	{
		$this->db->select();
		$this->db->from($this->users);
		if($type == 1)
		{
			$date = date('Y-m-d',  strtotime("-12 months",strtotime(CURRENT_DATETIME))); 
			$this->db->where("last_login_time < ",$date);
			$this->db->where("type",'2');
			$this->db->or_where("delete",1);
		}
		elseif($type == 2)
		{
			$date_sec = strtotime("-12 months",strtotime(CURRENT_DATETIME)); 
			$date = date('Y-m-d',($date_sec + 604800)); 
			$date_befort = date('Y-m-d',($date_sec + 691200)); 
			$this->db->where("last_login_time > ",$date);
			$this->db->where("last_login_time < ",$date_befort);
			
		}
		elseif($type == 3)
		{
			$date = date('Y-m-d',  strtotime("-11 months",strtotime(CURRENT_DATETIME))); 
			$date_befort = date('Y-m-d',(strtotime($date) + 86400)); 
			$this->db->where("last_login_time > ",$date);
			$this->db->where("last_login_time < ",$date_befort);										
		}
		elseif($type == 4)
		{
			$date_sec = strtotime("-12 months",strtotime(CURRENT_DATETIME)); 
			$date = date('Y-m-d',($date_sec + 86400)); 
			$date_befort = date('Y-m-d',($date_sec + (172800))); 
			$this->db->where("last_login_time >= ",$date);
			$this->db->where("last_login_time < ",$date_befort);
			$this->db->or_where("delete",1);
		}
		
		$resulttoken = $this->db->get();
		return $resulttoken->result_array();
	}
	/**
	 * delete user data
	 *
	 * @access 	public
	 * @param int user id
	 * @return void
	 */
	public function deleteuserdata($user_id)
	{
		
		/*delete SOS*/
		$this->db->delete($this->sos, array('user_id' => $user_id));
		
		/*delete OTP from friend data*/
		$this->db->delete($this->friends, array('user_id' => $user_id));
		
		/*delete OLDPASSWORD from PASSWORD data*/
		$this->db->delete($this->oldpass, array('user_id' => $user_id));
		
		/*delete Login attempt*/
		$this->db->delete($this->login_attempt, array('user_id' => $user_id));
		
		/*delete broadcast data*/
		$this->db->delete($this->broadcast, array('user_id' => $user_id));
		
		/*delete broadcast data*/
		$this->db->delete($this->users, array('id' => $user_id));
		
	}
	/**
	 * delete user data from admin (change status only)
	 *
	 * @access 	public
	 * @param int user id
	 * @return void
	 */
	public function deleteuserbyadmin($user_id)
	{
		
		/*delete SOS*/
		$this->db->delete($this->sos, array('user_id' => $user_id));
		
		/*delete OTP from friend data*/
		$this->db->delete($this->friends, array('user_id' => $user_id));
		
		/*delete OLDPASSWORD from PASSWORD data*/
		$this->db->delete($this->oldpass, array('user_id' => $user_id));
		
		/*delete Login attempt*/
		$this->db->delete($this->login_attempt, array('user_id' => $user_id));
		
		/*delete broadcast data*/
		$this->db->delete($this->broadcast, array('user_id' => $user_id));
		
		/*update checkin */
		$Update_checkin['delete'] = 1;
		$Update_checkin['status'] = 4; 	
		$this->db->where('user_id', $user_id);
		$this->db->update($this->checkin, $Update_checkin);
		$Update_alert['delete'] = 1;
		$this->db->where('user_id', $user_id);
		$this->db->update($this->alerts, $Update_alert);
		$UpdateData['delete'] = 1;
		$this->db->where('id', $user_id);
		$this->db->update($this->users, $UpdateData);
		
	}
	/**
	 * Update user checkin count
	 *
	 * @access 	public
	 * @param int user id
	 * @return int
	 */
	public function updatecheckincount($user_id)
	{
		$this->db->select('count(id) as checkincount');
		$this->db->from($this->checkin);
		$this->db->where('user_id',$user_id);
		$resultcount = $this->db->get();
		$result_count = $resultcount->result_array();
		
		$userdata['last_checkin_time'] = CURRENT_DATETIME;
		$userdata['checkin_count'] = $result_count[0]['checkincount'];
		
		$this->db->where('id', $user_id);
		$this->db->update($this->users, $userdata);
		return $this->db->affected_rows();
		
	}
	/**
	 * Update user alert count
	 *
	 * @access 	public
	 * @param int user id
	 * @return int
	 */
	public function updatealertcount($user_id)
	{
		$this->db->select('count(id) as alertcount');
		$this->db->from($this->alerts);
		$this->db->where('user_id',$user_id);
		$resultcount = $this->db->get();
		$result_count = $resultcount->result_array();
		
		$userdata['alert_count'] = $result_count[0]['alertcount'];
		
		$this->db->where('id', $user_id);
		$this->db->update($this->users, $userdata);
		return $this->db->affected_rows();
	}
}
