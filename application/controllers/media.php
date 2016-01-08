<?php
if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}
/**
* Name:  Media
*
* @package Admin Media
* @version 1.0
*
*/
class Media extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('api/mediafunc');
    }
    /**
	 * media list
	 *
	 * @access	public
	 * @return render view
	 */
    public function index()
    {
        $session = $this->session->userdata('admin_id');
        if(empty($session))
        {
            redirect(site_url('login'), 'refresh');
        }         
    }
    
	/**
	 * This function will use for media display based on checkin
	 *
	 * @access	public
	 * @return render view
	 */
    public function checkinmedia($id)
    {
        $session = $this->session->userdata('admin_id');
        if(empty($session))
        {
            redirect(site_url('login'), 'refresh');
        }
        /*Display Media base on checkin id(1 = checkin)*/
        $result = $this->mediafunc->getmediabycheckinwithtable($id,1);
        $data['media'] = $result;
        $this->load->view('newmidiya', $data);
    }
    /**
	 * This function will use for media display based on alert
	 *
	 * @access	public
	 * @return render view
	 */    
    public function reportmedia($id)
    {
        $session = $this->session->userdata('admin_id');
        if(empty($session))
        {
            redirect(site_url('login'), 'refresh');
        }
        /*Display Media base on alert id (2 = alert)*/
        $result = $this->mediafunc->getmediabycheckinwithtable($id,2);
        $data['media'] = $result;
        $this->load->view('newmidiya', $data);
    }
}
