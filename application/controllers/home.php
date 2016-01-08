<?php
if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}
/**
* Name:  Home
*
* @package Home
* @version 1.0
*/
class Home extends CI_Controller
{
	/**
	 * Constructor
	 *
	 * @access 	public
	 */
    public function __construct()
    {
        parent::__construct();
    }
	/**
	 * This function will use to display data on dashboard
	 *
	 * @access	public
	 * @return render view
	 */
    public function index()
    {
        $this->load->model('api/dashboard');

        $session = $this->session->userdata('admin_id');
        if(empty($session))
        {
            redirect(site_url('login'), 'refresh');
        }
		/* Get Total no of users*/
		$totaluser=$this->dashboard->totaluser();
		/* Get count of user who signup in lase 30 days*/
		$newuser=$this->dashboard->last30dayusers();
		/* Get totel Lock user*/
		$lockuser=$this->dashboard->lockuser();

		/* Get total no of diffrent users-origin country*/
		$totalcountriesoforigin=$this->dashboard->origincountry();
		/* Get total no of diffrent users-working country*/
		$totalcountrieswhereworking=$this->dashboard->workingcountry();
		/* Get total no of non english users*/
		$nonenglishusers=$this->dashboard->nonenglishuser();

		/* get count of active(Started, Confirmed) checkin*/
		$activecheckins=$this->dashboard->activecheckin();
		/* Get count of Misscheckin created in past 24 hours*/
		$missedcheckins=$this->dashboard->misscheckin24hr();
		/* Get count of alert created in past 24 hours*/
		$pastalerts=$this->dashboard->alerts24hr();

        $data['totaluser']=$totaluser[0]['count'];
        $data['newuser']=$newuser[0]['count'];
        $data['lockuser']=$lockuser[0]['count'];

        $data['totalcountriesoforigin']=count($totalcountriesoforigin);
        $data['totalcountrieswhereworking']=count($totalcountrieswhereworking);
        $data['nonenglishusers']=$nonenglishusers[0]['count'];

        $data['activecheckins']=$activecheckins[0]['count'];
        $data['missedcheckins']=$missedcheckins[0]['count'];
        $data['pastalerts']=$pastalerts[0]['count'];

        $this->load->view('dashboard', $data);
    }

	/**
	 * This function will use to display all user list 
	 *
	 * @access	public
	 * @return render view
	 */
    public function userinfo()
    {
        $session = $this->session->userdata('admin_id');
        if(empty($session))
        {
            redirect(site_url('login'), 'refresh');
        }

        $login_type = $this->session->userdata('type');
        $this->load->helper('xcrud');

        Xcrud_config::$dbname='iwmf_user';
        $xcruduser = Xcrud::get_instance();

        $xcruduser->table('users');
        $xcruduser->subselect('Name',"CONCAT_WS(' ',firstname,lastname)");
        $xcruduser->columns('Name,checkin_count,alert_count, language, phone, jobtitle,created_on,status,last_checkin_time,checkin_status');
        $xcruduser->change_type('checkin_status','select','',array('values'=>array('0'=>'Pending','1'=>'Started','2'=>'Confirmed','3'=>'Deleted','4'=>'Closed','5'=>'Missed')));
        $xcruduser->where('delete',0);
        $xcruduser->column_name('jobtitle', 'Title');
        $xcruduser->column_name('created_on', 'Join Date');
        $xcruduser->column_name('alert_count', '# Alerts');
        $xcruduser->column_name('checkin_count', '# Check-Ins');
        $xcruduser->column_name('last_checkin_time', 'Last Check-in');
        $xcruduser->column_name('checkin_status', 'Check-in Status');

        $xcruduser->change_type('status','select','',array('values'=>array('-1'=>'Locked','0'=>'Inactive','1'=>'Active')));
		/*view user link for normal admin*/
        if($login_type == 1)
        {
            $url = site_url('userinfo/userinfo/viewuser/{id}');
        }
        else
        {
			/*Edit user link for supper admin*/
            $url = site_url('userinfo/userinfo/index/{id}');
        }

        $xcruduser->column_pattern('Name', '<a href='.$url.' class="" data-task="" data-primary="{id}">{Name}</a>');

        $xcruduser->change_type('status','select','',array('values'=>array('-1'=>'Locked','0'=>'Inactive','1'=>'Active')));
        $xcruduser->change_type('type','select','',array('values'=>array('1'=>'Admin','2'=>'User')));
        $xcruduser->change_type('Check-in Status','select','',array('values'=>array('0'=>'Pending','1'=>'Started','2'=>'Confirmed','3'=>'Deleted','4'=>'Closed','5'=>'Missed')));  
        $xcruduser->search_columns('Name,language,jobtitle,status','Name');

        $xcruduser->unset_edit();
        $xcruduser->unset_add();
        $xcruduser->unset_view();
        $xcruduser->unset_title();
        $xcruduser->unset_print();
        $xcruduser->unset_remove();

        if($login_type == 1)
        {
            $xcruduser->button(site_url('userinfo/userinfo/viewuser/{id}'),'EDIT','glyphicon glyphicon-eye-open');
        }
        else
        {
            $xcruduser->button(site_url('userinfo/userinfo/index/{id}'),'EDIT','glyphicon glyphicon-pencil');
            $xcruduser->button('{id}', 'Delete', 'glyphicon glyphicon-trash', 'ref','',array('type','!=',3));
        }

        $data['content'] = $xcruduser->render();
        $this->load->view('home', $data);    
    }


	/**
	 * This function use to delete user (it's just change delete status)
	 *
	 * @access	public
	 * @return void
	 */
    public function deleteuser()
    {
        $this->load->model('api/users');
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

        $post = $this->input->post();
        $user_id = $post['user_id'];

		/*Change status of user as deleted*/
        $this->users->deleteuserbyadmin($user_id);
    }
}