<?php
if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}
/**
* Name:  Xcrud ajax
*
* Description:  This Class use to handle ajax request from admin
*               Modified view base on requirement 
* @package Xcrud_ajax
* @version 1.0
*
*/
class Xcrud_ajax extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->load->library('session');
        $this->load->helper(array('url', 'xcrud'));
        Xcrud_config::$scripts_url = base_url('');
        $this->output->set_output(Xcrud::get_requested_instance());
    }
}

/* End of file xcrud_ajax.php */
/* Location: ./application/controllers/xcrud_ajax.php */
