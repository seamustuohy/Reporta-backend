<?php
/**
* Name:  CheckIns
*
* Description:  class function use to perform curd operation for checkin
*               Modified Function base on requirement 
*
* @package CheckIns
* @version 1.0
*/
class CheckIns extends CI_Model
{
	public $checkin = 'checkin';
	public $alerts = 'alerts';
	public $users = 'iwmf_user.users';
	public $checkincontactlist = 'checkincontactlist';
	public $checkinhistory = 'checkinhistory';
	
	function __construct()
	{
		/* Call the Model constructor*/
		parent::__construct();
	}
	
	/**
	 * create checkin
	 *
	 * @access 	public
	 *@param checkin data to insert
	 * @return int
	 */
	public function createcheckin($InsertData)
	{
		$this->db->insert($this->checkin, $InsertData);
		return $this->db->insert_id();
	}
	
	/**
	 * update checkin
	 *
	 * @access 	public
	 * @param array checkin data to Update
	 * @param int checkin id
	 * @return int
	 */
	public function updatecheckin($UpdateData, $checkin_id)
	{
		$this->db->where('id', $checkin_id);
		$this->db->update($this->checkin, $UpdateData);
		return $this->db->affected_rows();
	}
	
	/**
	 * delete checkin
	 *
	 * @access 	public
	 * @param  int checkin id
	 * @return int
	 */
	public function deletecheckin($checkin_id)
	{
		$this->db->delete($this->checkinhistory, array('checkin_id' => $checkin_id));
		$this->db->delete($this->checkincontactlist, array('foreign_id' => $checkin_id, 'table_id' => '1'));
		$this->db->delete($this->checkin, array('id' => $checkin_id));
		return $this->db->affected_rows();
	}

	/**
	 * update checkin by user_id
	 *
	 * @access 	public
	  * @param $array  checkin data to Update
	 * @param int user id
	 * @return int
	 */
	public function updatecheckinByUserid($UpdateData, $user_id)
	{
		$this->db->where('user_id', $user_id);
		$this->db->update($this->checkin, $UpdateData);
		return $this->db->affected_rows();
	}
	
	/**
	 * insert checkin contactlist
	 *
	 * @access 	public
	  * @param array contactlist
	 * @return int
	 */
	public function insertcheckincontactlist($InsertData)
	{
		$this->db->insert_batch($this->checkincontactlist, $InsertData);
		return $this->db->insert_id();
	}
	
	/**
	 * delete checkin contactlist
	 *
	 * @access 	public
	 * @param $int checkin id
	 * @param int type 1= checkin, 2 = alert
	 * @return int
	 */
	public function deletecheckincontactlist($foreign_id, $table_id)
	{
		$this->db->delete($this->checkincontactlist, array('foreign_id' => $foreign_id, 'table_id' => $table_id));
		return $this->db->affected_rows();
	}
	
	/**
	 * update checkin
	 *
	 * @access 	public
	 * @param $array  checkin data
	 * @param int checkin id 
	 * @return int
	 */
	public function updatecheckinstatus($UpdateData, $checkin_id)
	{
		$this->db->where('id', $checkin_id);
		$this->db->update($this->checkin, $UpdateData);
		return $this->db->affected_rows();
	}
	
	/**
	 * Get checkin data with contactlist
	 *
	 * @access 	public
	 * @param int checkin id 
	 * @return array
	 */
	public function getcheckin($checkin_id)
	{
		$this->db->select("c.*, replace(GROUP_CONCAT(ccl.contactlist_id SEPARATOR ','), ' ', '') as contactlist",FALSE);
		$this->db->from($this->checkin.' AS c');
		$this->db->join($this->checkincontactlist." AS ccl", 'ccl.foreign_id = c.id', 'LEFT');
		$this->db->where('c.id', $checkin_id);
		$this->db->where('ccl.table_id', '1');
		$result = $this->db->get();
		return $result->result_array();
	}
	
	/**
	 * Get checkin data
	 *
	 * @access 	public
	 * @param int checkin id 
	 * @return array
	 */
	public function getcheckinbyid($checkin_id)
	{
		$this->db->select();
		$this->db->from($this->checkin);
		$this->db->where('id', $checkin_id);
		$result = $this->db->get();
		return $result->result_array();
	}
	
	
	/**
	 * get information of alert by id
	 *
	 * @access 	public
	 * @param int alert id 
	 * @return array
	 */
	public function getalertbyid($alert_id)
	{
		$this->db->select();
		$this->db->from($this->alerts);
		$this->db->where('id', $alert_id);
		$result = $this->db->get();
		return $result->result_array();
	}
	
	/**
	 * Get user language
	 *
	 * @access 	public
	 * @param int checkin id 
	 * @return array
	 */
	public function getuserlanguagebycheckin($checkin_id)
	{
		$this->db->select('u.language');
		$this->db->from($this->users." AS u");
		$this->db->join($this->checkin." AS c", 'c.user_id = u.id', 'LEFT');
		$this->db->where('c.id', $checkin_id);
		
		$result = $this->db->get();
		return $result->row();
	}
	
	/**
	 * Get alert or checkin 
	 *
	 * @access 	public
	 * @param int checkin id  or alert id
	 * @param int user id
	 * @param int 1=>checkin , 2=>alert
	 * @return array
	 */
	public function getalertbyidwithuser($id, $user_id,$type)
	{
		$this->db->select();
		if($type == 1)
		{
			$this->db->from($this->checkin);
		}
		else
		{
			$this->db->from($this->alerts);
		}
		if($id != 0)
		{
			$this->db->where('id', $id);
		}
		$this->db->where('user_id', $user_id);
		$result = $this->db->get();
		return $result->result_array();
	}
	
	/**
	 * Get contactlist
	 *
	 * @access 	public
	 * @param int checkin id  or alert id
	 * @param int user id
	 * @param int 1=>checkin , 2=>alert
	 * @return array
	 */
	public function getcontactlistbyalert($id,$type)
	{
		$this->db->select("replace(GROUP_CONCAT(contactlist_id SEPARATOR ','), ' ', '') as contactlist",FALSE);
		$this->db->from($this->checkincontactlist);
		$this->db->where('foreign_id', $id);
		$this->db->where('table_id', $type);
		$result = $this->db->get();
		return $result->result_array();
	}
	
	/**
	 * Insert alert
	 *
	 * @access 	public
	 * @param array alert data
	 * @return array
	 */
	public function createalert($InsertData)
	{
		$this->db->insert($this->alerts, $InsertData);
		return $this->db->insert_id();
	}
	
	/**
	 * update alert
	 *
	 * @access 	public
	 * @param array alert data
	 * @param int alert id
	 * @return array
	 */
	public function updatealert($UpdateData, $alert_id)
	{
		$this->db->where('id', $alert_id);
		$this->db->update($this->alerts, $UpdateData);
		return $this->db->affected_rows();
	}
	/**
	 * Delete alert
	 *
	 * @access 	public
	 * @param int alert id
	 * @return array
	 */
	public function deletealert($alert_id)
	{
		$this->db->delete($this->checkincontactlist, array('foreign_id' => $alert_id, 'table_id' => '2'));
		$this->db->delete($this->alerts, array('id' => $alert_id));
		return $this->db->affected_rows();
	}
	
	/**
	 * get chakin list for sign out 
	 *
	 * @access 	public
	 * @param int user id
	 * @return array
	 */
	public function	getsignoutcheckinlist($user_id)
	{
		$this->db->select();
		$this->db->from($this->checkin.' AS c');
		$status = array('0','1','2');
		$this->db->where_in('c.status',$status);
		$this->db->where("c.user_id",$user_id);
		$this->db->order_by('id', 'DESC');
		$this->db->limit('1');
		$result = $this->db->get();
		return $result->result_array();
	}
	
	/**
	 * conform checkin count
	 *
	 * @access 	public
	 * @param int checkin id
	 * @return array
	 */
	public function checkinconfirmedcount($checkin_id)
	{
		$this->db->select('count(`checkin_id`) AS count');
		$this->db->from($this->checkinhistory);
		$this->db->where("checkin_id",$checkin_id);
		$this->db->where("status", 2);
		$result = $this->db->get();
		return $result->result_array();
		
	}
	/**
	 * Get deleted alert
	 *
	 * @access 	public
	 * @param int checkin id
	 * @return array
	 */
	public function getoldalert()
	{
		$date = date('Y-m-d H:i:s',(strtotime(CURRENT_DATETIME) - 604800)); 
		$this->db->select();
		$this->db->from($this->alerts);
		$this->db->where("created_on <",$date);
		$this->db->or_where('is_mediasend', 1); 
		$result = $this->db->get();
		return $result->result_array();
	}
	
	/**
	 * Get deleted Checkin
	 *
	 * @access 	public
	 * @return array
	 */
	public function getoldcheckin()
	{
		$this->db->select();
		$this->db->from($this->checkin);
		$this->db->where_in('status', array(3,4,5)); 
		$result = $this->db->get();
		return $result->result_array();
	}
}
