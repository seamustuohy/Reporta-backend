<?php
/**
* Name:  History
* @package History
* @version 1.0
*/
class History extends CI_Model
{
	public $checkinhistory = 'checkinhistory';
	public $broadcast = 'broadcast';
	public $friends = 'friends';
	public $sos = 'sos';
	public $users = 'iwmf_user.users';
	public $checkin = 'checkin';
	
	function __construct()
	{
		/* Call the Model constructor*/
		parent::__construct();
	}
	
	/**
	 * Insert checkin data in to checkinhistory
	 *
	 * @access 	public
	 * @param array Checkin data
	 * @return int
	 */
	public function createcheckinhistory($InsertData)
	{
		$this->db->select();
		$this->db->from($this->checkin);
		$this->db->where('id',$InsertData['checkin_id']); 
		$result = $this->db->get();
		$user =  $result->result_array();
		
		$userdata['checkin_status'] = $InsertData['status'];
		$this->db->where('id', $user[0]['user_id']);
		$this->db->update($this->users, $userdata);
		
		$this->db->insert($this->checkinhistory, $InsertData);
		return $this->db->insert_id();
	}
	/**
	 * Insert broadcast
	 *
	 * @access 	public
	 * @param array broadcast data
	 * @return int
	 */
	public function createbroadcast($InsertData)
	{
		$this->db->insert($this->broadcast, $InsertData);
		return $this->db->insert_id();
	}
	/**
	 * Insert firend (otp)
	 *
	 * @access 	public
	 * @param array Otp data
	 * @return int
	 */
	public function createfriendsdetail($InsertData)
	{
		
		$this->db->insert_batch($this->friends, $InsertData);
		return $this->db->insert_id();
	}
	/**
	 * Insert SOS
	 *
	 * @access 	public
	 * @param array SOS data
	 * @return int
	 */
	public function createsos($InsertData)
	{
		$this->db->insert($this->sos, $InsertData);
		return $this->db->insert_id();
	}
	/**
	 * Get SOS 
	 *
	 * @access 	public
	 * @param array user id 
	 * @return array
	 */
	public function getsosbyuserid($user_id)
	{
		$this->db->select("s.*, b.id AS broadcast_id");
		$this->db->from($this->sos." AS s");
		$this->db->join($this->broadcast." AS b", 'b.foreign_id = s.id AND b.table_id = "3"', 'LEFT');
		$this->db->where('s.user_id', $user_id);
		$this->db->order_by("s.id", "desc");
		$this->db->limit(1);
		$result = $this->db->get();
		return $result->result_array();
	}
}
