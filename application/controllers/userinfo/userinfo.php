<?php
if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}
/**
* Name:  Userinfo
*
* Description:  class contain function for single user 
*               Modified Function base on requirement 
*
* @package Userinfo
* @version 1.0
*
*/
class Userinfo extends CI_Controller
{
    /**
	 * Constructor
	 *
	 * @access 	public
	 */
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('api/users');
        $this->load->helper('xcrud');
        $this->load->model('api/api');
        $this->load->library('session');
        $this->load->model('api/common');
	$this->lang->load('message', 'EN');
	$this->response_message = $this->lang->language;
        $this->country = $this->countrylist();
        $this->load->model('api/notification');
        
    }
    
	/**
	 * This function will use to get user data based on user id
	 * 
	 * @access 	public
	 * @return render view
	 */
    public function index($id)
    {
        $session = $this->session->userdata('admin_id');
        if(empty($session))
        {
            redirect(site_url('login'), 'refresh');
        }
        
	$login_type = $this->session->userdata('type');
        if($login_type == 1)
        {
            $url = site_url()."userinfo/userinfo/viewuser/".$id;
            redirect($url, 'refresh');
        }
		/* Get user detail 	*/
        $result = $this->users->getuserdetail($id);
        $data['container'] = $result[0];
        $data['errormsg'] = "";
        $data['Updatemsg'] = "";
        $data['countrylist'] = $this->country;
        
        $this->load->view('user/userinfo' , $data);    
    }
	/**
	 *  This function will use for update user data
	 *  If user status will change from "active" to "lock" then send notification with "lock" status to lock the app
	 *  If user status will change from "lock" to "active" then send notification with "active" status to unlock the app
	 * @access 	public
	 * @return render view
	 */
    public function updateuser($id)
    {
        $session = $this->session->userdata('admin_id');
        if(empty($session))
        {
            redirect(site_url('login'), 'refresh');
        }
	/*if admin is not supper admin redirect it to view page	*/
	$login_type = $this->session->userdata('type');
        if($login_type == 1)
        {
            $url = site_url()."userinfo/userinfo/viewuser/".$id;
            redirect($url, 'refresh');
        }
	
        $data['errormsg'] = "";
        $data['Updatemsg'] = "";
        
        $this->form_validation->set_rules('firstname', 'firstname', 'required');
        $this->form_validation->set_rules('lastname', 'lastname', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('origin_country', 'Origin country', 'required');
        $this->form_validation->set_rules('working_country', 'Working country', 'required');
        
        $container['id'] = $id;
        
        if ($this->form_validation->run() == FALSE)
        {
            $data = array();
            $container = array();
            $container['id'] = $id;
            $container['username'] = $this->input->post('username');
            $container['firstname'] = $this->input->post('firstname');
            $container['lastname'] = $this->input->post('lastname');
            $container['created_on'] = $this->input->post('accountcreated');
            $container['email'] = $this->input->post('email');
            $container['jobtitle'] = $this->input->post('jobtitle');
            $container['affiliation_id'] = $this->input->post('affiliation');
            $container['freelancer'] = $this->input->post('freelancer');
            $container['origin_country'] = $this->input->post('origin_country');
            $container['working_country'] = $this->input->post('working_country');
            $container['phone'] = $this->input->post('phone');
            
            $container['language_code'] = $this->input->post('language_code');
                    
            if($container['language_code'] == 'AR')
            {
                $container['language'] = 'Arabic';
            }
            elseif($container['language_code'] == 'FR')
            {
                $container['language'] = 'French';
            }
            elseif($container['language_code'] == 'IW')
            {
                $container['language'] = 'Hebrew';
            }
            elseif($container['language_code'] == 'ES')
            {
                $container['language'] = 'Spanish';
            }
            elseif($container['language_code'] == 'TR')
            {
                $container['language'] = 'Turkish';
            }
            elseif($container['language_code'] == 'EN')
            {
                $container['language'] = 'English';
            }
            
            $container['status'] = $this->input->post('status');
            
            $container['gender_type'] = $this->input->post('gender_type');
            if($container['gender_type'] == '1')
            {
                $container['gender'] = 'Mail';
            }
            elseif($container['gender_type'] == '2')
            {
                $container['gender'] = 'Female';
            }
            else
            {
                $container['gender'] = $this->input->post('othergender');
            }

            $data['container'] = $container;
            $data['errormsg'] = "";
            $data['Updatemsg'] = "";
            $data['countrylist'] = $this->country;
        
            $this->load->view('user/userinfo' , $data);    
        }
        else
        {
            /* Update Userdata*/
            $container['id'] = $id;  
            $container['username'] = $this->input->post('username');
            $container['firstname'] = $this->input->post('firstname');
            $container['lastname'] = $this->input->post('lastname');
            $container['created_on'] = $this->input->post('accountcreated');
            $container['email'] = $this->input->post('email');
            $container['jobtitle'] = $this->input->post('jobtitle');
            $container['affiliation_id'] = $this->input->post('affiliation');
            $container['freelancer'] = $this->input->post('freelancer');
            $container['origin_country'] = $this->input->post('origin_country');
            $container['working_country'] = $this->input->post('working_country');
            $container['phone'] = $this->input->post('phone');
            
            $container['language_code'] = $this->input->post('language_code');
                    
            if($container['language_code'] == 'AR')
            {
                $container['language'] = 'Arabic';
            }
            elseif($container['language_code'] == 'FR')
            {
                $container['language'] = 'French';
            }
            elseif($container['language_code'] == 'IW')
            {
                $container['language'] = 'Hebrew';
            }
            elseif($container['language_code'] == 'ES')
            {
                $container['language'] = 'Spanish';
            }
            elseif($container['language_code'] == 'TR')
            {
                $container['language'] = 'Turkish';
            }
            elseif($container['language_code'] == 'EN')
            {
                $container['language'] = 'English';
            }
            
            $container['status'] = $this->input->post('status');
            
            $container['gender_type'] = $this->input->post('gender_type');
            if($container['gender_type'] == '1')
            {
                $container['gender'] = 'Mail';
            }
            elseif($container['gender_type'] == '2')
            {
                $container['gender'] = 'Female';
            }
            elseif($container['gender_type'] == '3')
            {
                $container['gender'] = $this->input->post('othergender');
            }
                    
            
            $data['errormsg'] = "";
            $data['Updatemsg'] = "";
            $data['countrylist'] = $this->countrylist();
            $result_email =  $this->users->getuserdetailbyemail($container['email']);
            $result_user  =  $this->users->getuserdetailbyusername($container['username']);
            
            /* for send push in mobile app*/
            $user_old_data = $this->users->getuserdetail($id);
            $devicetokenstype = $user_old_data[0]['device_type'];
            $devicetoken = $user_old_data[0]['devicetoken'];
            
            if (count($result_email) > 0 || count($result_user) > 0)
            {
				/*Chake mail duplication */
                if($result_email[0]['id'] != $id  && count($result_email) > 0 )
                {
                    $data['errormsg'] = $this->response_message['EMAILEXITS'];
                }
				/*Chake username duplication */
                if($result_user[0]['id'] != $id  && count($result_user) > 0 )
                {
                    $data['errormsg'] .= "<br>".$this->response_message['USEREXITS'];
                }
                if($result_email[0]['id'] == $id &&  $result_user[0]['id'] == $id)
                {
                    $container['updated_on'] = CURRENT_DATETIME;
                    
                    if($container['status'] != -1 && $user_old_data[0]['status'] == -1)
                    {
                        /*change all otp status*/
						$this->users->updateotpstatus($id,'','');
                        
                        $message = "App Unlock By Admin";
                        if($devicetokenstype == '1') 
                        {
                            $this->notification->sendToIphone('', $devicetoken, $message,'unlockapp');
                        }
                        elseif($devicetokenstype == '2') 
                        {
                            $this->notification->sendToAndroid('', $devicetoken, $message,2);
                        }
                        $container['lock_admin'] = 0;
                    }
                    elseif($container['status'] == -1 && $user_old_data[0]['status'] == 1)
                    {
                        /*change all otp status*/
						$this->users->updateotpstatus($id,'','');
                        
						/* send push in mobile to lock app */
                        $message = "App lock By Admin";
                        if($devicetokenstype == '1') 
                        {
                            $this->notification->sendToIphone('', $devicetoken, $message,'lockapp');
                        }
                        elseif($devicetokenstype == '2')
                        {
                            $this->notification->sendToAndroid('', $devicetoken, $message,3);
                        }
                        $container['lock_admin'] = 1;
                        
                        /* GET and close any active checkin for this users*/
                        $result_avtivecheckin = $this->checkins->getsignoutcheckinlist($id);
                        if(isset($result_avtivecheckin) && count($result_avtivecheckin)>0)
                        {
                            $checkindata['status'] = 3;
                            
							/*Update checkin data*/
							$this->checkins->updatecheckin($checkindata, $result_avtivecheckin[0]['id']);
                            
                            $checkinhistorydata['checkin_id'] = $result_avtivecheckin[0]['id'];
                            $checkinhistorydata['status'] = $result_avtivecheckin[0]['status'];
                            $checkinhistorydata['timezone_id'] = $result_avtivecheckin[0]['timezone_id'];
                            $checkinhistorydata['time'] = !empty($result_avtivecheckin[0]['time'])?$result_avtivecheckin[0]['time']:'0000-00-00 00:00:00';
                            $checkinhistorydata['latitude'] = $result_avtivecheckin[0]['latitude'];
                            $checkinhistorydata['longitude'] = $result_avtivecheckin[0]['longitude'];
                            $checkinhistorydata['created_on'] = CURRENT_DATETIME;
                            
                            /*Insert checkin histry*/
							$this->history->createcheckinhistory($checkinhistorydata);
                        }
                    }
                    
					/*Update User data*/
					$this->users->updateuser($container,$id);
		    
                    $result = $this->users->getuserdetail($id);
                    $data['container'] = $result[0];
                    $data['container']['affiliation'] =  $result[0]['affiliation_id'];
                    $data['Updatemsg'] = "Updated Successfully";
                    $data['countrylist'] = $this->country;
                    $this->load->view('user/userinfo' , $data);
                }
                else
                {
                    $data['container'] = $container;
                    $data['countrylist'] = $this->country;
                    $this->load->view('user/userinfo',$data);
                }
            }

            else
            {
                if($container['status'] != -1 && $user_old_data[0]['status'] == -1)
                {
                    /*change all otp status*/
					$this->users->updateotpstatus($id,'','');
					/* send unlock push to unlock app*/
                    $message = "App Unlock By Admin";
                    if($devicetokenstype == '1')
                    {
                        $this->notification->sendToIphone('', $devicetoken, $message,'unlockapp');
                    }
                    elseif($devicetokenstype == '2')
                    {
                        $this->notification->sendToAndroid('', $devicetoken, $message,2);
                    }
                    $container['lock_admin'] = 0;
                }
                elseif($container['status'] == -1 && $user_old_data[0]['status'] == 1)
                {
                    /*update otp status*/
					$this->users->updateotpstatus($id,'','');
                    /* send lock push to lock app*/
                    $message = "App lock By Admin";
                    if($devicetokenstype == '1')
                    {
                        $this->notification->sendToIphone('', $devicetoken, $message,'lockapp');
                    }
                    elseif($devicetokenstype == '2')
                    {
                        $this->notification->sendToAndroid('', $devicetoken, $message,3);
                    }
                    $container['lock_admin'] = 1;
                }
                $container['updated_on'] = CURRENT_DATETIME;
	        
				/*Update User data*/
				$this->users->updateuser($container,$id);
	    
                $result = $this->users->getuserdetail($id);
                $data['container'] = $result[0];
                $data['container']['affiliation'] =  $result[0]['affiliation_id'];
                $data['Updatemsg'] = "Updated Successfully";
                $data['countrylist'] = $this->country;
                $this->load->view('user/userinfo' , $data);
            }

        }
        
    }
    
    /**
	 * This function will use for display list of single user checkin based on user id
	 *
	 * @access 	public
	 * @return render view
	 */
	public function alert()
    {
        $session = $this->session->userdata('admin_id');
        if(empty($session))
        {
            redirect(site_url('login'), 'refresh');
        }
        $id = $this->input->post('id');
        $data['user_id'] = $id;
        
        $xcrud = Xcrud::get_instance();

        $xcrud->table('checkin');
        $xcrud->subselect('Media',"select IF(count(id) > 0, count(id), 0) from media where foreign_id = {id}  AND table_id = '1'");
        $xcrud->columns('location,description,starttime,endtime,laststatustime,status,description,Media');
        $xcrud->where('user_id = ',$id );
        
        $xcrud->order_by('id','desc');
        $xcrud->column_name('id', 'Checkin#');
        $xcrud->column_name('starttime', 'Start Time');
        $xcrud->column_name('endtime', 'End Time');
        $xcrud->column_name('laststatustime', 'Last Status Time');

        $xcrud->button(site_url('media/checkinmedia/{id}'),'Media','glyphicon glyphicon-picture','','',array('Media','!=','0'));
        $xcrud->button(site_url('alert/alertinfo/{id}'),'INFO','glyphicon glyphicon-search');
        
        $xcrud->unset_add();
        $xcrud->unset_edit();
        $xcrud->unset_view();
        $xcrud->unset_remove();

        $xcrud->highlight('status','=','5','red');
        $xcrud->unset_title();
        $xcrud->unset_print();
        /* Checkin Status: 0-Pending, 1-Started, 2-Confirmed, 3-Deleted, 4-Closed, 5-Missed*/
		$xcrud->change_type('status','select','',array('values'=>array('0'=>'Pending','1'=>'Started','2'=>'Confirmed','3'=>'Deleted','4'=>'Closed','5'=>'Missed')));
        
        $xcrud->search_columns('location,status','location');
        
        if( $this->input->server('REQUEST_METHOD') == 'POST' && $this->input->post('status') == 1 )
        {    
            if($this->input->post('selectstatus') >=0)
            {
                $xcrud->where('status = ',$this->input->post('selectstatus') );
                $data['content'] = $xcrud->render();
                echo $data['content'];
                exit;
            }
            else
            {
                $data['content'] = $xcrud->render();
                echo $data['content'];
                exit;
            }
        }
        
        $data['content'] = $xcrud->render();
        $this->load->view('user/user_alter', $data);
    }
    /**
	 * This function will use for display list of "single user Circle" based on user id
	 * @access 	public
	 * @return render view
	 */
    public function contactlist()
    {
        $session = $this->session->userdata('admin_id');
        if(empty($session))
        {
            redirect(site_url('login'), 'refresh');
        }
        $id = $this->input->post('id');
        $xcrud = Xcrud::get_instance();
        $xcrud->table('contactlists');
        
        $xcrud->columns('listname,created_on,circle');
        $xcrud->column_name('circle', 'Type');
        $xcrud->column_name('listname', 'List Name');
        $xcrud->where('user_id = ',$id );
        $xcrud->button(site_url('userinfo/userinfo/contact/{id}'),'INFO','glyphicon glyphicon-search');
        $xcrud->change_type('circle','select','',array('values'=>array('1'=>'private','2'=>'public','3'=>'Social')));
        
        $xcrud->unset_add();
        $xcrud->unset_edit();
        $xcrud->unset_view();
        $xcrud->unset_remove();
        $xcrud->unset_title();
        $xcrud->unset_print();
        
        $xcrud->search_columns('listname,circle','listname');
        
        $data['user_id'] = $id;
        
        if( $this->input->server('REQUEST_METHOD') == 'POST' && $this->input->post('status') == 1 )
        {
            if($this->input->post('circle') >0)
            {
                $xcrud->where('circle' ,$this->input->post('circle'));
                $data['content'] = $xcrud->render();
                
                echo $data['content'];
                exit;
            }
            else
            {
                $data['content'] = $xcrud->render();
                echo $data['content'];
                exit;
            }
        }
        $data['content'] = $xcrud->render();
       
        $this->load->view('user/contactlist', $data);
    }
   
	/**
	 * This function will use for display list of Contact list based on circle id of the user
	 * @access 	public
	 * @return render view
	 */
	public function contact($id)
    {
         $session = $this->session->userdata('admin_id');
        if(empty($session))
        {
            redirect(site_url('login'), 'refresh');
        }
        $contactlist_id = $id;
        
        $xcrud = Xcrud::get_instance();
        $xcrud->table('contacts');
        $xcrud->join('id','associated_contacts','contact_id');
        $xcrud->subselect('Name',"CONCAT_WS(' ',contacts.firstname,contacts.lastname)");
        $xcrud->columns('Name,mobile,emails');
        $xcrud->column_name('mobile', 'Mobile Number');
        $xcrud->column_name('emails', 'Email');
        $xcrud->unset_view();
        $xcrud->unset_remove();
        $xcrud->where('associated_contacts.contactlist_id',$contactlist_id );

        $xcrud->unset_add();
        $xcrud->unset_edit();
        $xcrud->unset_title();
        $xcrud->unset_print();
        
        $data['content'] = $xcrud->render();
        $this->load->view('user/contact' , $data);    
    }
    
    /**
	 * This function will use for display list of "active(started, Confirmed) checkin" based on user id
	 * @access 	public
	 * @return render view
	 */
	public function checkinschedules()
    {
        $session = $this->session->userdata('admin_id');
        if(empty($session))
        {
            redirect(site_url('login'), 'refresh');
        }
        $id = $this->input->post('id');
        $data['user_id'] = $id;
        $this->load->helper('xcrud');
        
        $xcrud = Xcrud::get_instance();
        $xcrud->table('checkin');
        $xcrud->subselect('Media',"select IF(count(id) > 0, count(id), 0) from media where foreign_id = {id} AND table_id = '1'");
        
        $xcrud->columns('username,starttime,nextconfirmationtime,status,Media');
        $xcrud->column_name('starttime', 'Start Time');
        
        $xcrud->button(site_url('media/checkinmedia/{id}'),'Media','glyphicon glyphicon-picture','','',array('Media','!=','0'));
        $xcrud->button(site_url('alert/alertinfo/{id}'),'INFO','glyphicon glyphicon-search');
        
        $xcrud->where('user_id',$id );
        $xcrud->column_name('nextconfirmationtime', 'Next Check In Time');
        $xcrud->column_name('username', 'Name');
        $xcrud->change_type('status','select','',array('values'=>array('0'=>'Pending','1'=>'Started','2'=>'Confirmed','3'=>'Deleted','4'=>'Closed','5'=>'Missed')));
        /* Get list of started, Confirmed checkin*/
		$xcrud->where('status', array('1','2'));
        
		$xcrud->unset_add();
        $xcrud->unset_view();
        $xcrud->unset_remove();
        $xcrud->unset_title();
        $xcrud->unset_print();
        $xcrud->unset_edit();
        
        if( $this->input->server('REQUEST_METHOD') == 'POST' && $this->input->post('status') == 1 )
        {
			/* filter list base on checkin status*/
            if($this->input->post('status_select') >=0)
            {
                $xcrud->where('status =' ,$this->input->post('status_select'));
                $data['content'] = $xcrud->render();
                
                echo $data['content'];
                exit;
            }
            else
            {
                $data['content'] = $xcrud->render();
                echo $data['content'];
                exit;
            }
        }
        
        $data['content'] = $xcrud->render();
        $this->load->view('user/checkinlist', $data);
    }
    
	/**
	 * This function will use for display list of "single user Alert" based on user id
	 * @access 	public
	 * @return render view
	 */
    public function report()
    {
        $session = $this->session->userdata('admin_id');
        if(empty($session))
        {
            redirect(site_url('login'), 'refresh');
        }
        $id = $this->input->post('id');
      
        $this->load->helper('xcrud');
        $xcrud = Xcrud::get_instance();
        
        $xcrud->table('alerts');
        $xcrud->subselect('Media',"select count(*) from media where foreign_id = {id}  AND table_id = '2' ");
        $xcrud->columns('id,username,created_on,situation,Media');
        $xcrud->column_name('id', 'Alert No');
        $xcrud->column_name('username', 'Name');
        $xcrud->column_name('situation', 'Status');
        $xcrud->where('user_id',$id);
        $xcrud->order_by('id','desc');
        $xcrud->button(site_url('media/reportmedia/{id}'),'Media','glyphicon glyphicon-picture','','',array('Media','!=','0'));
        $xcrud->button(site_url('report/reportinfo/{id}'),'INFO','glyphicon glyphicon-search');

        $xcrud->unset_add();
        $xcrud->unset_edit();
        $xcrud->unset_title();
        $xcrud->unset_print();
        $xcrud->unset_view();
        $xcrud->unset_remove();
     
        $data['content'] = $xcrud->render();
        $this->load->view('user/report', $data);
    }
    
	/**
	 * This function will use for View single user data
	 * @access 	public
	 * @return render view
	 */
    public function viewuser($user_id ='')
    {
        
        $session = $this->session->userdata('admin_id');
        if(empty($session))
        {
            redirect(site_url('login'), 'refresh');
        }
	
        if($this->input->server('REQUEST_METHOD') == 'POST' )
        {
            $user_id = $this->input->post('id');
        }
        
        $result = $this->users->getuserdetail($user_id);
        $data['container'] = $result[0];
        $this->load->view('user/viewuser' , $data);   
    }
    
	/**
	 * This function will use for add new User
	 * Only super admin can use this function
	 * @access 	public
	 * @return render view
	 */
    public function useradd()
    {
        $session = $this->session->userdata('admin_id');
        if(empty($session))
        {
            redirect(site_url('login'), 'refresh');
        }
	
	$login_type = $this->session->userdata('type');
        if($login_type == 1)
        {
            $url = site_url()."home/userinfo";
            redirect($url, 'refresh');
        }
		
        if( $this->input->server('REQUEST_METHOD') == 'POST')
        {
                $data['errormsg'] = "";
                $data['Updatemsg'] = "";
                $this->form_validation->set_rules('firstname', 'firstname', 'required');
                $this->form_validation->set_rules('lastname', 'lastname', 'required');
                $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
                $this->form_validation->set_rules('origin_country', 'Origin country', 'required');
                $this->form_validation->set_rules('phone', 'Phone Noumber', 'required|is_natural');
                $this->form_validation->set_rules('working_country', 'Working country', 'required');
				/* Check form validation*/
                if ($this->form_validation->run() == FALSE)
                {
                    $data = array();
                    $container = array();
                    $container['username'] = $this->input->post('username');
                    $container['firstname'] = $this->input->post('firstname');
                    $container['lastname'] = $this->input->post('lastname');
                    $container['email'] = $this->input->post('email');
                    $container['jobtitle'] = $this->input->post('jobtitle');
                    $container['affiliation_id'] = $this->input->post('affiliation');
                    $container['freelancer'] = $this->input->post('freelancer');
                    $container['origin_country'] = $this->input->post('origin_country');
                    $container['working_country'] = $this->input->post('working_country');
                    
                    $container['language_code'] = $this->input->post('language_code');
                    
                    if($container['language_code'] == 'AR')
                    {
                        $container['language'] = 'Arabic';
                    }
                    elseif($container['language_code'] == 'FR')
                    {
                        $container['language'] = 'French';
                    }
                    elseif($container['language_code'] == 'IW')
                    {
                        $container['language'] = 'Hebrew';
                    }
                    elseif($container['language_code'] == 'ES')
                    {
                        $container['language'] = 'Spanish';
                    }
                    elseif($container['language_code'] == 'TR')
                    {
                        $container['language'] = 'Turkish';
                    }
                    else
                    {
                        $container['language'] = 'English';
                    }
                    
                    $container['phone'] = $this->input->post('phone');
                    $container['status'] = $this->input->post('status');
                    $container['gender_type'] = $this->input->post('gender_type');
                    if($container['gender_type'] == '1')
                    {
                        $container['gender'] = 'Mail';
                    }
                    elseif($container['gender_type'] == '2')
                    {
                        $container['gender'] = 'Female';
                    }
                    else
                    {
                        $container['gender'] = $this->input->post('othergender');
                    }
                    $data['container'] = $container;
                    $data['errormsg'] = "";
                    $data['Updatemsg'] = "";
                    $data['countrylist'] = $this->country;

                    $this->load->view('user/useradd' , $data);    
                }
                else
                {
                    /* Add Userdata*/
                    
                    $container['username'] = $this->input->post('username');
                    $container['firstname'] = $this->input->post('firstname');
                    $container['lastname'] = $this->input->post('lastname');
                    $container['email'] = $this->input->post('email');
                    $container['jobtitle'] = $this->input->post('jobtitle');
                    $container['affiliation_id'] = $this->input->post('affiliation');
                    $container['freelancer'] = $this->input->post('freelancer');
                    $container['origin_country'] = $this->input->post('origin_country');
                    $container['working_country'] = $this->input->post('working_country');
                    $container['status'] = $this->input->post('status');
                    $container['type'] = $this->input->post('type');
                    $container['phone'] = $this->input->post('phone');
                    $container['language'] = $this->input->post('language');
                    
                    $container['gender_type'] = $this->input->post('gender_type');
                    if($container['gender_type'] == '1')
                    {
                        $container['gender'] = 'Mail';
                    }
                    elseif($container['gender_type'] == '2')
                    {
                        $container['gender'] = 'Female';
                    }
                    else
                    {
                        $container['gender'] = $this->input->post('othergender');
                    }
                    
                    $container['language_code'] = $this->input->post('language_code');
                    
                    if($container['language_code'] == 'AR')
                    {
                        $container['language'] = 'Arabic';
                    }
                    elseif($container['language_code'] == 'FR')
                    {
                        $container['language'] = 'French';
                    }
                    elseif($container['language_code'] == 'IW')
                    {
                        $container['language'] = 'Hebrew';
                    }
                    elseif($container['language_code'] == 'ES')
                    {
                        $container['language'] = 'Spanish';
                    }
                    elseif($container['language_code'] == 'TR')
                    {
                        $container['language'] = 'Turkish';
                    }
                    else
                    {
                        $container['language'] = 'English';
                    }
                    
                    $data['errormsg'] = "";
                    $data['Updatemsg'] = "";
                    $data['countrylist'] = $this->countrylist();
                    $result_email =  $this->users->getuserdetailbyemail($container['email']);
                    $result_user  =  $this->users->getuserdetailbyusername($container['username']);
                    if (count($result_email) > 0)
                    {
                        $data['errormsg'] = $this->response_message['EMAILEXITS'];
                        $data['container'] = $container;
                        $data['countrylist'] = $this->country;
                        $this->load->view('user/useradd',$data);

                    }
                    elseif(count($result_user) > 0)
                    {
                        $data['errormsg'] = $this->response_message['USEREXITS'];
                        $data['container'] = $container;
                        $data['countrylist'] = $this->country;
                        $this->load->view('user/useradd',$data);
                    }
                    else
                    {
                        $container['created_on'] = CURRENT_DATETIME;
                        $container['forgotpassword_code'] = $this->users->randomPassword();
                        $container['last_login_time'] = CURRENT_DATETIME;
                        $container['app_encryption_key'] = $this->api->randomPassword(16);
                        $container['islogin'] = 1 ;
                        $container['status'] = 1 ;
                        $container['id'] =  microtime(true) * 10000;
                        $result = $this->users->createuser($container);
                        $result = $this->users->getuserdetail($result);

						/*Password reset link*/
						$link = "<a href = ".site_url() ."newpassword?uid=".$this->common->encode($result[0]['id'])."&fc=".$this->common->encode($container['forgotpassword_code'])." mc:disable-tracking>click here</a>";
									
						$this->lang->load('mail',$result[0]['language_code']);
						$this->mail_template = $this->lang->language;
						
						$subject = $this->mail_template['FORGOTPASSWORD_SUBJECT'];
						$email_message = $this->mail_template['FORGOTPASSWORD_MESSAGE'];
						$message_string = array("%email%", "%username%", "%link%");
						$replace_string   = array($result[0]['email'], $result[0]['username'], $link);
						$email_message = str_replace($message_string, $replace_string, $email_message);
						
						/* Send mail to re set password*/
                        $this->common->send_email(EMAIL_FROM, EMAIL_NAME,$result[0]['email'],$subject,$email_message,NULL,$result[0]['language_code']);
                        
                        $data['container'] = $result[0];
                        /* send welcome mail*/
                        $message = "Thank you for downloading [registering with?] Reporta. Reporta is a security app designed by the International Women’s Media Foundation (IWMF) for journalists working in dangerous environments to implement their security protocols at the touch of a button. Reporta is the only comprehensive security app available worldwide designed specifically for journalists.";
                        $this->common->send_email(EMAIL_FROM, EMAIL_NAME,$container['email'],"Welcome to REPORTA",$message);
                        $data['Updatemsg'] = "User Add Successfully";
                        $data['countrylist'] = $this->country;
                        $this->load->view('user/useradd' , $data);
                    }
        
                }
        }
        else
        {
                $data['errormsg'] = "";
                $data['Updatemsg'] = "";
                $container['username'] = "";
                $container['firstname'] = "";
                $container['lastname'] = "";
                $container['created_on'] ="";
                $container['email'] = "";
                $container['jobtitle'] ="";
                $container['affiliation_id'] = "";
                $container['freelancer'] = "";
                $container['origin_country'] ="";
                $container['working_country'] = "";
                $container['status'] = "";
                $container['phone'] = "";
                $container['gender'] = '';
                $container['language_code'] = '';
                $container['gender_type'] = '';
                $data['countrylist'] = $this->country;
                $data['container'] = $container;
                $this->load->view('user/useradd' , $data);
        }
    }
    
	/**
	 * This function will return list of country.
	 * @access 	public
	 * @return render view
	 */
    public function countrylist()
    {
        $config['country_list'] = array("AF"=>"Afghanistan","AX"=>"Aland Islands","AL"=>"Albania","DZ"=>"Algeria","AS"=>"American Samoa","AD"=>"Andorra","AO"=>"Angola","AI"=>"Anguilla","AQ"=>"Antarctica","AG"=>"Antigua and Barbuda","AR"=>"Argentina","AM"=>"Armenia","AW"=>"Aruba","AU"=>"Australia","AT"=>"Austria","AZ"=>"Azerbaijan","BS"=>"Bahamas","BH"=>"Bahrain","BD"=>"Bangladesh","BB"=>"Barbados","BY"=>"Belarus","BE"=>"Belgium","BZ"=>"Belize","BJ"=>"Benin","BM"=>"Bermuda","BT"=>"Bhutan","BO"=>"Bolivia, Plurinational State of","BQ"=>"Bonaire, Sint Eustatius and Saba","BA"=>"Bosnia and Herzegovina","BW"=>"Botswana","BV"=>"Bouvet Island","BR"=>"Brazil","IO"=>"British Indian Ocean Territory","BN"=>"Brunei Darussalam","BG"=>"Bulgaria","BF"=>"Burkina Faso","BI"=>"Burundi","KH"=>"Cambodia","CM"=>"Cameroon","CA"=>"Canada","CV"=>"Cape Verde","KY"=>"Cayman Islands","CF"=>"Central African Republic","TD"=>"Chad","CL"=>"Chile","CN"=>"China","CX"=>"Christmas Island","CC"=>"Cocos (Keeling) Islands","CO"=>"Colombia","KM"=>"Comoros","CG"=>"Congo","CD"=>"Congo, the Democratic Republic of the","CK"=>"Cook Islands","CR"=>"Costa Rica","CI"=>"Cote d'Ivoire","HR"=>"Croatia","CU"=>"Cuba","CW"=>"Curacao","CY"=>"Cyprus","CZ"=>"Czech Republic","DK"=>"Denmark","DJ"=>"Djibouti","DM"=>"Dominica","DO"=>"Dominican Republic","EC"=>"Ecuador","EG"=>"Egypt","SV"=>"El Salvador","GQ"=>"Equatorial Guinea","ER"=>"Eritrea","EE"=>"Estonia","ET"=>"Ethiopia","FK"=>"Falkland Islands (Malvinas)","FO"=>"Faroe Islands","FJ"=>"Fiji","FI"=>"Finland","FR"=>"France","GF"=>"French Guiana","PF"=>"French Polynesia","TF"=>"French Southern Territories","GA"=>"Gabon","GM"=>"Gambia","GE"=>"Georgia","DE"=>"Germany","GH"=>"Ghana","GI"=>"Gibraltar","GR"=>"Greece","GL"=>"Greenland","GD"=>"Grenada","GP"=>"Guadeloupe","GU"=>"Guam","GT"=>"Guatemala","GG"=>"Guernsey","GN"=>"Guinea","GW"=>"Guinea-Bissau","GY"=>"Guyana","HT"=>"Haiti","HM"=>"Heard Island and McDonald Mcdonald Islands","VA"=>"Holy See (Vatican City State)","HN"=>"Honduras","HK"=>"Hong Kong","HU"=>"Hungary","IS"=>"Iceland","IN"=>"India","ID"=>"Indonesia","IR"=>"Iran, Islamic Republic of","IQ"=>"Iraq","IE"=>"Ireland","IM"=>"Isle of Man","IL"=>"Israel","IT"=>"Italy","JM"=>"Jamaica","JP"=>"Japan","JE"=>"Jersey","JO"=>"Jordan","KZ"=>"Kazakhstan","KE"=>"Kenya","KI"=>"Kiribati","KP"=>"Korea, Democratic People's Republic of","KR"=>"Korea, Republic of","KW"=>"Kuwait","KG"=>"Kyrgyzstan","LA"=>"Lao People's Democratic Republic","LV"=>"Latvia","LB"=>"Lebanon","LS"=>"Lesotho","LR"=>"Liberia","LY"=>"Libya","LI"=>"Liechtenstein","LT"=>"Lithuania","LU"=>"Luxembourg","MO"=>"Macao","MK"=>"Macedonia, the Former Yugoslav Republic of","MG"=>"Madagascar","MW"=>"Malawi","MY"=>"Malaysia","MV"=>"Maldives","ML"=>"Mali","MT"=>"Malta","MH"=>"Marshall Islands","MQ"=>"Martinique","MR"=>"Mauritania","MU"=>"Mauritius","YT"=>"Mayotte","MX"=>"Mexico","FM"=>"Micronesia, Federated States of","MD"=>"Moldova, Republic of","MC"=>"Monaco","MN"=>"Mongolia","ME"=>"Montenegro","MS"=>"Montserrat","MA"=>"Morocco","MZ"=>"Mozambique","MM"=>"Myanmar","NA"=>"Namibia","NR"=>"Nauru","NP"=>"Nepal","NL"=>"Netherlands","NC"=>"New Caledonia","NZ"=>"New Zealand","NI"=>"Nicaragua","NE"=>"Niger","NG"=>"Nigeria","NU"=>"Niue","NF"=>"Norfolk Island","MP"=>"Northern Mariana Islands","NO"=>"Norway","OM"=>"Oman","PK"=>"Pakistan","PW"=>"Palau","PS"=>"Palestine, State of","PA"=>"Panama","PG"=>"Papua New Guinea","PY"=>"Paraguay","PE"=>"Peru","PH"=>"Philippines","PN"=>"Pitcairn","PL"=>"Poland","PT"=>"Portugal","PR"=>"Puerto Rico","QA"=>"Qatar","RE"=>"Reunion","RO"=>"Romania","RU"=>"Russian Federation","RW"=>"Rwanda","BL"=>"Saint Barthelemy","SH"=>"Saint Helena, Ascension and Tristan da Cunha","KN"=>"Saint Kitts and Nevis","LC"=>"Saint Lucia","MF"=>"Saint Martin (French part)","PM"=>"Saint Pierre and Miquelon","VC"=>"Saint Vincent and the Grenadines","WS"=>"Samoa","SM"=>"San Marino","ST"=>"Sao Tome and Principe","SA"=>"Saudi Arabia","SN"=>"Senegal","RS"=>"Serbia","SC"=>"Seychelles","SL"=>"Sierra Leone","SG"=>"Singapore","SX"=>"Sint Maarten (Dutch part)","SK"=>"Slovakia","SI"=>"Slovenia","SB"=>"Solomon Islands","SO"=>"Somalia","ZA"=>"South Africa","GS"=>"South Georgia and the South Sandwich Islands","SS"=>"South Sudan","ES"=>"Spain","LK"=>"Sri Lanka","SD"=>"Sudan","SR"=>"Suriname","SJ"=>"Svalbard and Jan Mayen","SZ"=>"Swaziland","SE"=>"Sweden","CH"=>"Switzerland","SY"=>"Syrian Arab Republic","TW"=>"Taiwan, Province of China","TJ"=>"Tajikistan","TZ"=>"Tanzania, United Republic of","TH"=>"Thailand","TL"=>"Timor-Leste","TG"=>"Togo","TK"=>"Tokelau","TO"=>"Tonga","TT"=>"Trinidad and Tobago","TN"=>"Tunisia","TR"=>"Turkey","TM"=>"Turkmenistan","TC"=>"Turks and Caicos Islands","TV"=>"Tuvalu","UG"=>"Uganda","UA"=>"Ukraine","AE"=>"United Arab Emirates","GB"=>"United Kingdom","US"=>"United States","UM"=>"United States Minor Outlying Islands","UY"=>"Uruguay","UZ"=>"Uzbekistan","VU"=>"Vanuatu","VE"=>"Venezuela, Bolivarian Republic of","VN"=>"Viet Nam","VG"=>"Virgin Islands, British","VI"=>"Virgin Islands, U.S.","WF"=>"Wallis and Futuna","EH"=>"Western Sahara","YE"=>"Yemen","ZM"=>"Zambia","ZW"=>"Zimbabwe");
        return $config['country_list'];
    }

}
