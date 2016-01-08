<?php
if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}
/**
* Name:  Termsandconditions
*
* Description:  This Class render "Terms and conditions" page based on language
*               Modified view base on requirement 
* @package Termsandconditions
* @version 1.0
*
*/
class Termsandconditions extends CI_Controller
{
	public function index()
    {
        $this->load->view('termsandconditions/english');
    }
    
    public function english()
    {
        $this->load->view('termsandconditions/english');
    }
    
    public function spanish()
    {
        $this->load->view('termsandconditions/spanish');
    }
    
    public function arabic()
    {
        $this->load->view('termsandconditions/arabic');
    }
    
    public function hebrew()
    {
        $this->load->view('termsandconditions/hebrew');
    }
    
    public function french()
    {
        $this->load->view('termsandconditions/french');
    }
    
    public function turkish()
    {
        $this->load->view('termsandconditions/turkish');
    }
}