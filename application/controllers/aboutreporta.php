<?php
if (!defined('BASEPATH'))
{
    exit('No direct script access allowed');
}
/**
* Name:  Aboutreporta
*
* Description:  This Class render "Aboutreporta" page based on language
*               Modified view base on requirement 
*
* @package Aboutreporta
* @version 1.0
*/
class Aboutreporta extends CI_Controller
{
    public function index()
    {
        $this->load->view('about_reporta/english');
    }
    
    public function english()
    {
        $this->load->view('about_reporta/english');
    }
    
    public function spanish()
    {
        $this->load->view('about_reporta/spanish');
    }
    
    public function arabic()
    {
        $this->load->view('about_reporta/arabic');
    }
    
    public function hebrew()
    {
        $this->load->view('about_reporta/hebrew');
    }
    
    public function french()
    {
        $this->load->view('about_reporta/french');
    }
    
    public function turkish()
    {
        $this->load->view('about_reporta/turkish');
    }
}