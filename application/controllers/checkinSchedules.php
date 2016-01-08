<?php
if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}
/**
* Name:  CheckinSchedules
*
* Description:  This Class contain function which will display information of Checkin
*               Modified Function base on requirement 
* @package CheckinSchedules
* @version 1.0
*
*/
class CheckinSchedules extends CI_Controller
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
	 *This function will use for display all active(Pending, started, Confirmed) Checkin
	 *
	 * @access	public
	 *
	 * @return render view
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
		/* Get media count*/
        $xcrud->subselect('Media',"select IF(count(id) > 0, count(id), 0) from media where foreign_id = {id}  AND table_id = '1'");
        $xcrud->where('status', array('0','1','2'));
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
            /*Filter checkin base on status*/
            if($this->input->post('status') >=0)
            {
                $xcrud->where('status' ,$this->input->post('status'));
            }
            echo $xcrud->render();
            exit;
        }

        $data['content'] = $xcrud->render();
        $this->load->view('checkinSchedules', $data);
        
    }
}
