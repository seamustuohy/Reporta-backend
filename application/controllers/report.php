<?php
if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}
/**
* Name:  Userinfo
*
* Description:  Class function use to view alert
*               Modified Function base on requirement 
* @package Report
* @version 1.0
*
*/
class Report extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('api/users');
        $this->load->model('api/checkins');
        $this->load->model('api/mediafunc');
        
        $this->load->model('api/api');
        $this->load->library('session');
    }
    
	/**
	 * This function will use for alert display
	 *
	 * @access 	public
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
    
    $xcrud->table('alerts');
    $xcrud->subselect('Media',"select count(*) from media where foreign_id = {id} AND  table_id = '2' ");
    $xcrud->columns('id,username,created_on,situation,Media');
    $xcrud->column_name('id', 'Alert No');
    $xcrud->column_name('username', 'Name');
    $xcrud->column_name('situation', 'Status');
    $xcrud->order_by('id','desc');
    $xcrud->where('delete',0);
    $xcrud->button(site_url('media/reportmedia/{id}'),'Media','glyphicon glyphicon-picture','','',array('Media','!=','0'));

    $xcrud->button(site_url('report/reportinfo/{id}'),'INFO','glyphicon glyphicon-search');
    $xcrud->unset_add();
    $xcrud->unset_edit();
    $xcrud->unset_view();
    $xcrud->unset_remove();
    $xcrud->unset_title();
    $xcrud->unset_print();
    
    $data['content'] = $xcrud->render();
    
    $this->load->view('report', $data);
    
}

	/**
	 * This function will use for displaying detail Information of alert based on alert id
	 *
	 * @access 	public
	 * @return render view
	 */
public function reportinfo($alert_id)
{
    $session = $this->session->userdata('admin_id');
    if(empty($session))
    {
        redirect(site_url('login'), 'refresh');
    }

    $result = $this->checkins->getalertbyid($alert_id);
    
    $data['report'] = $result[0];

    $result_user = $this->users->getuserdetail($result[0]['user_id']);
    $data['user'] = $result_user[0];

    $result = $this->mediafunc->getmediabycheckinwithtable($alert_id,2);
    $data['media'] = $result;
    
    $this->load->view('reportinfo', $data);
}
}
