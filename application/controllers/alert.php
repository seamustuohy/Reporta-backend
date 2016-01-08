<?php
if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}
/**
* Name:  Alert
*
* Description:  This Class contain functions which will provide information of checkin for admin 
*               Modified Function base on requirement 
* @package Alert
* @version 1.0
*
*/
class Alert extends CI_Controller
{

	/**
	 * Constructor
	 *
	 * @access 	public
	 */
    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('api/users');
        $this->load->model('api/checkins');
        $this->load->model('api/history');
        
        $this->load->model('api/api');
        $this->load->library('session');
        $this->load->library('Googlemaps');
    }
    
	/**
	 * This function will use to display List Of All Checkin
	 *
	 * @access	public
	 *
	 * @return void
	 */
    public function index()
    {
        $session = $this->session->userdata('admin_id');
        if(empty($session))
        {
            redirect(site_url('login'), 'refresh');
        }
        $this->load->helper('xcrud');
        $xcrud = Xcrud::get_instance();

        $xcrud->table('checkin');
		/* Get count of media*/
        $xcrud->subselect('Media',"select IF(count(id) > 0, count(id), 0) from media where foreign_id = {id}  AND table_id = '1'");
        $xcrud->columns('id,username,starttime,endtime,laststatustime,status,description,Media');
        
        $xcrud->order_by('id','desc');
        $xcrud->column_name('id', 'Check In No');
        $xcrud->column_name('username', 'Name');
        $xcrud->column_name('starttime', 'Start Time');
        $xcrud->column_name('endtime', 'End Time');
        $xcrud->where('delete',0);
        $xcrud->column_name('laststatustime', 'Last Status Time');
        $xcrud->search_columns('id,Name,status','Name');
        /* If checkin missed marke it as red  (5= Missed checkin)*/
        $xcrud->highlight('status','=','5','red');
        $xcrud->change_type('status','select','',array('values'=>array('0'=>'Pending','1'=>'Started','2'=>'Confirmed','3'=>'Deleted','4'=>'Closed','5'=>'Missed')));
        
        $xcrud->button(site_url('media/checkinmedia/{id}'),'Media','glyphicon glyphicon-picture','','',array('Media','!=','0'));        
        $xcrud->button(site_url('alert/alertinfo/{id}'),'INFO','glyphicon glyphicon-search');

        $xcrud->unset_add();
        $xcrud->unset_edit();
        $xcrud->unset_view();
        $xcrud->unset_remove();
        $xcrud->unset_title();
        $xcrud->unset_print();
        
        if( $this->input->server('REQUEST_METHOD') == 'POST')
        {
            if($this->input->post('status') >=0)
            {
                $xcrud->where('status',$this->input->post('status'));
            }
            echo $xcrud->render();
            exit;
        }
        
        $data['content'] = $xcrud->render();
        $this->load->view('alert', $data);
    }
    
	/**
	 * This function will use to display Checkin detail with checkin history
	 *
	 * @access	public
	 *
	 * @return void
	 */
    public function alertinfo($checkin_id)
    {
        $session = $this->session->userdata('admin_id');
        if(empty($session))
        {
            redirect(site_url('login'), 'refresh');
        }
        $this->load->helper('xcrud');
        $xcrud = Xcrud::get_instance();
        /* Get Checkin data*/
        
        $result = $this->checkins->getcheckinbyid($checkin_id);
        $data['checkin'] = $result[0];

		/* Get User data*/
        $result_user = $this->users->getuserdetail($result[0]['user_id']);
        $data['user'] = $result_user[0];
        
        $xcrud->table('checkinhistory');
        $xcrud->columns('time,status,latitude,longitude');
        $xcrud->fields('updated_on',true,true,'view');
        $xcrud->change_type('time','date_format','','');
		/* If checkin missed marke it as red  (5= Missed checkin)*/
        $xcrud->highlight('status','=','5','red');
        $xcrud->change_type('status','select','',array('values'=>array('0'=>'Pending','1'=>'Started','2'=>'Confirmed','3'=>'Deleted','4'=>'Closed','5'=>'Missed')));
        
        $xcrud->where('checkin_id',$checkin_id);
        $xcrud->unset_add();
        $xcrud->unset_edit();
        $xcrud->unset_remove();
        $xcrud->unset_view();
        $xcrud->unset_title();
        $xcrud->unset_print();
        $data['content'] = $xcrud->render();
        
        /* Load Google Map*/
		try
		{
			$map_pos = $result[0]['latitude'].",".$result[0]['longitude'];
			$config['center'] = $map_pos;
			$config['zoom'] = 10;
			$this->googlemaps->initialize($config);
			$marker = array();
			$marker['position'] = $map_pos;
			$this->googlemaps->add_marker($marker);
			$data['map'] = $this->googlemaps->create_map();
		}
		catch(Exception $e)
		{
			$this->load->view('error');
			exit;
		}
        
        $this->load->view('alertinfo', $data);
    }
}