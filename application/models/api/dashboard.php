<?php

/**
* Name:  Dashboard
* @package Dashboard
* @version 1.0
* Description:  class function use to display data on Dashboard
*               Modified Function base on requirement 
*/
class Dashboard extends CI_Model
{
	public $users = 'iwmf_user.users';
	public $alerts = 'alerts';
	public $checkin = 'checkin';
	
	
	function __construct()
	{
		/* Call the Model constructor*/
		parent::__construct();
	}
	
	/**
	 * Get Totel user 
	 *
	 * @access 	public
	 * @return array
	 */
	public function totaluser()
	{
		$this->db->select('COUNT(id) as count');
		$this->db->from($this->users);
		$result = $this->db->get();
		
		return $result->result_array();
	}
	/**
	 *  Get totel Lock user
	 *
	 * @access 	public
	 * @return array
	 */
	public function lockuser()
	{
		$this->db->select('COUNT(id) as count');
		$this->db->from($this->users);
		$this->db->where('status', -1);
		$result = $this->db->get();
		return $result->result_array();
	}
	
	/**
	 * Get count of user who signup in lase 30 days
	 *
	 * @access 	public
	 * @return array
	 */
	public function last30dayusers()
	{
		$this->db->select('COUNT(id) as count');
		$this->db->from($this->users);
		$this->db->where('created_on >',' current_date() - interval 30 day');
		$result = $this->db->get();
		return $result->result_array();
	}
	/**
	 * get count of active(Started, Confirmed) checkin
	 *
	 * @access 	public
	 * @return array
	 */
	public function activecheckin()
	{
		$this->db->select('COUNT(id) as count');
		$this->db->from($this->checkin);
		$this->db->where_in("status",array('1','2'));
		$result = $this->db->get();
		
		return $result->result_array();
	}
	
	/**
	 * Get count of alert created in past 24 hours
	 *
	 * @access 	public
	 * @return array
	 */
	public function alerts24hr()
	{
		$this->db->select('COUNT(id) as count');
		$this->db->from($this->alerts);
		$this->db->where("created_on >"," DATE_SUB(NOW(), INTERVAL 24 HOUR)");
		$result = $this->db->get();
		return $result->result_array();
	}
	/**
	 * Get count of Misscheckin created in past 24 hours
	 *
	 * @access 	public
	 * @return array
	 */
	public function misscheckin24hr()
	{
		$this->db->select('COUNT(id) as count');
		$this->db->from($this->checkin);
		$this->db->where("status",5);
		$this->db->where("created_on >"," DATE_SUB(NOW(), INTERVAL 24 HOUR)");
		$result = $this->db->get();
		return $result->result_array();
	}
	/**
	 * Get total no of diffrent users-origin country
	 *
	 * @access 	public
	 * @return array
	 */
	public function origincountry()
	{
		$this->db->distinct();
		$this->db->from($this->users);
		$this->db->group_by('origin_country');
		$result = $this->db->get();
		return $result->result_array();
	}
	/**
	 * Get total no of diffrent users-working country
	 *
	 * @access 	public
	 * @return array
	 */
	public function workingcountry()
	{
		$this->db->distinct();
		$this->db->from($this->users);
		$this->db->group_by('working_country');
		$result = $this->db->get();
		return $result->result_array();
	}
	
	/**
	 * Get total no of non english users
	 *
	 * @access 	public
	 * @return array
	 */
	public function nonenglishuser()
	{
		$this->db->select('COUNT(id) as count');
		$this->db->from($this->users);
		$this->db->where("language_code !=", 'EN');
		$result = $this->db->get();
		return $result->result_array();
	}
}
