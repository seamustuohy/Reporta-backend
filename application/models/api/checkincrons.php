<?php
/**
* Name:  CheckInCrons
*
* Description:  class function use to return data fro cron
*               Modified Function base on requirement 
* @package CheckInCrons
* @version 1.0
*
*/
class CheckInCrons extends CI_Model
{
	public $checkin = 'checkin';
	public $checkincontactlist = 'checkincontactlist';
	public $broadcast = 'broadcast';
	function __construct()
	{
		/* Call the Model constructor*/
		parent::__construct();
	}
	
	/**
	 * Get checkin data 
	 *
	 * @access 	public
	 * @return array
	 */
	public function getremindercheckinlist()
	{
		$status = array('1','2');
		$date = date('Y-m-d H:i:s',(strtotime(CURRENT_DATETIME) + 670));
		
		$this->db->select("c.* ,bc.id AS broadcast_id " );
		$this->db->from($this->checkin.' AS c');
		$this->db->join($this->broadcast." AS bc", 'bc.foreign_id = c.id', 'LEFT');
		$this->db->where_in("c.status",$status);
		$this->db->where('bc.table_id = "1"');
		$this->db->where("(c.nextconfirmationtime) <",$date);
		$result = $this->db->get();
		return $result->result_array();
	}
	/**
	 * Get pending checkin
	 *
	 * @access 	public
	 * @return array
	 */
	public function getpendingcheckin()
	{
		
		$this->db->select("c.*");
		$this->db->from($this->checkin.' AS c');
		$this->db->where("c.status",0);
		$this->db->where("c.starttime <= ",CURRENT_DATETIME);
		$result = $this->db->get();
		return $result->result_array();
	}
        
	/**
	 * Get close checkin
	 *
	 * @access 	public
	 * @return array
	 */
	public function getclosecheckin()
	{
		$status = array('0','1','2');
		$this->db->select("c.*");
		$this->db->from($this->checkin.' AS c');
		$this->db->where_in("c.status", $status);
		$this->db->where("c.endtime <= ",CURRENT_DATETIME);
		$result = $this->db->get();
		return $result->result_array();
	}
}
