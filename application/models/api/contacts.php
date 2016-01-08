<?php
/**
* Name:  Contacts
* @package Contacts
* @version 1.0
*/
class Contacts extends CI_Model
{
	public $contactlists = 'contactlists';
	public $contacts = 'contacts';
	public $associated_contacts = 'associated_contacts';
	public $checkincontactlist = 'checkincontactlist';
	
	function __construct()
	{
		/* Call the Model constructor*/
		parent::__construct();
		$this->load->library('Subquery');
	}
	/**
	 * insert circle
	 *
	 * @access 	public
	 * @param array circle data
	 * @return int
	 */
	public function createcontactlist($InsertData)
	{
		$this->db->insert($this->contactlists, $InsertData);
		return $this->db->insert_id();
	}
	
	
	/**
	 * Get contact base on name for duplicate check 
	 *
	 * @access 	public
	 * @param int user id
	 * @param string first name 
	 * @param string last name 
	 * @return array
	 */
	public function chakeduplicatecontectforname($user_id,$firstname,$lastname)
	{
		$sql = "SELECT `c`.`id`
		FROM (`contacts` AS c)
		LEFT JOIN `associated_contacts` AS ac ON `c`.`id` = `ac`.`contact_id`
		LEFT JOIN `contactlists` AS cl ON `cl`.`id` = `ac`.`contactlist_id`
		WHERE `cl`.`user_id` = ? and
		( (c.firstname = ? and c.lastname =? )
			or (c.firstname = ? and c.lastname =? ))";

$result = $this->db->query($sql,array($user_id,$firstname,$lastname,$lastname,$firstname));
return $result->result_array();
}
	/**
	 * Get contact base on Email 
	 *
	 * @access 	public
	 * @param int user id
	 * @param string email
	 * @return array
	 */
public function chakeduplicatecontectforemail($email,$user_id)
{
	
	$email_list = explode(",",$email);
	$user_list = array($user_id) ;
	$result_where = array_merge($user_list, $email_list);
	
	$where="cl.user_id =? and (";
	if(count($email_list)>0)
	{
		$where .= " FIND_IN_SET(?, emails) ";
		for($i = 1; $i < count($email_list);$i++)
		{
			$where .= " or FIND_IN_SET(?, emails) ";
		}
	}
	$where .= " )";

	$sql = "SELECT `c`.`id`
	FROM (`contacts` AS c)
	LEFT JOIN `associated_contacts` AS ac ON `c`.`id` = `ac`.`contact_id`
	LEFT JOIN `contactlists` AS cl ON `cl`.`id` = `ac`.`contactlist_id`
	WHERE ".$where;
	$result = $this->db->query($sql,$result_where);
	return $result->result_array();

}
	/**
	 * Get contact base on Mobile 
	 *
	 * @access 	public
	 * @param int user id
	 * @param string phone number
	 * @return array
	 */
public function chakeduplicatecontectformobile($mobile,$user_id)
{
	$mobile_list = explode(",",$mobile);
	
	$user_list = array($user_id) ;
	$result_where = array_merge($user_list, $mobile_list);

	$where="cl.user_id =? and (";
		if(count($mobile_list)>0)
		{
			$where .= "FIND_IN_SET(?, mobile) ";
			for($i = 1; $i < count($mobile_list);$i++)
			{
				$where .= " or FIND_IN_SET(?, mobile) ";
			}
		}
		$where .= " )";

$sql = "SELECT `c`.`id`
FROM (`contacts` AS c)
LEFT JOIN `associated_contacts` AS ac ON `c`.`id` = `ac`.`contact_id`
LEFT JOIN `contactlists` AS cl ON `cl`.`id` = `ac`.`contactlist_id`
WHERE ".$where;

$result = $this->db->query($sql,$result_where);
return $result->result_array();
}

	
	/**
	 * get associated contact list 
	 *
	 * @access 	public
	 * @param int circle id
	 * @param int contact id
	 * @return array
	 */
public function inassociated($contactlist_id,$contact_id)
{
	$this->db->select();
	$this->db->from($this->associated_contacts);
	$this->db->where('contactlist_id', $contactlist_id);
	$this->db->where('contact_id',$contact_id);
	$result = $this->db->get();
	return $result->result_array();
}
	
	/**
	 * update contactlist
	 *
	 * @access 	public
	 * @param array circle data
	 * @param int circle id
	 * @return int
	 */
public function updatecontactlist($UpdateData, $list_id)
{
	$this->db->where('id', $list_id);
	$this->db->update($this->contactlists, $UpdateData);
	return $this->db->affected_rows();
}
	
	/**
	 * change contact list states
	 *
	 * @access 	public
	 * @param int user id
	 * @param int circle id
	 * @param int circle type
	 * @return void
	 */
public function changedefaultstatus($user_id, $list_id, $circle)
{
	$update_status = $this->db->set('defaultstatus', '1');
	$update_status->where('user_id', $user_id);
	$update_status->where('circle', $circle);
	$update_status->where('id', $list_id);
	$update_status->update($this->contactlists);
	$affected = $this->db->affected_rows();
	
	if($affected > 0)
	{
		$update_old_status = $this->db->set('defaultstatus', '0');
		$update_old_status->where('user_id', $user_id);
		$update_old_status->where('circle', $circle);
		$update_status->where('id !=', $list_id);
		$update_old_status->update($this->contactlists);
	}
}
	
	/**
	 * change public contact list states
	 *
	 * @access 	public
	 * @param int user id
	 * @param int circle id
	 * @param int circle type
	 * @param int  circle active status
	 * @return void
	 */
public function changepublicdefaultstatus($user_id, $list_id, $circle,$defaultstatus)
{
	$update_status = $this->db->set('defaultstatus', $defaultstatus);
	$update_status->where('user_id', $user_id);
	$update_status->where('circle', $circle);
	$update_status->where('id', $list_id);
	$update_status->update($this->contactlists);
	$affected = $this->db->affected_rows();
	
	if($affected > 0 && $defaultstatus == '1')
	{
		$update_old_status = $this->db->set('defaultstatus', '0');
		$update_old_status->where('user_id', $user_id);
		$update_old_status->where('circle', $circle);
		$update_status->where('id !=', $list_id);
		$update_old_status->update($this->contactlists);
	}
}
	
	/**
	 * Insert contact
	 *
	 * @access 	public
	 * @param array insert contact
	 * @return int
	 */
public function createcontact($InsertData)
{
	$this->db->insert($this->contacts, $InsertData);
	return $this->db->insert_id();
}
	
	/**
	 * update contact
	 *
	 * @access 	public
	 * @param array contact data
	 * @param int contact id
	 * @param int circle type
	 * @param int circle active status
	 * @return int
	 */
public function updatecontact($UpdateData, $contact_id)
{
	$this->db->where('id', $contact_id);
	$this->db->update($this->contacts, $UpdateData);
	return $this->db->affected_rows();
}
	
	/**
	 * Get contact data
	 *
	 * @access 	public
	 * @param int contact id
	 * @return array
	 */
public function getcontactdetailbyid($contact_id)
{
	$this->db->select();
	$this->db->from($this->contacts);
	$this->db->where('id', $contact_id);
	
	$result = $this->db->get();
	return $result->result_array();
}
	
	/**
	 * insert associated contact
	 *
	 * @access 	public
	 * @param array Insert associated contact
	 * @return int
	 */
public function createassociatedcontact($InsertData)
{
	$this->db->insert($this->associated_contacts, $InsertData);
	return $this->db->insert_id();
}
	
	/**
	 * Get associated contact
	 *
	 * @access 	public
	 * @param int contact id
	 * @return array
	 */
public function getassociatedbycontactid($contact_id)
{
	$this->db->select('contactlist_id');
	$this->db->from($this->associated_contacts);
	$this->db->where('contact_id', $contact_id);
	
	$result = $this->db->get();
	return $result->result_array();
}
	/**
	 * delete associated contact 
	 *
	 * @access 	public
	 * @param int contact id
	 * @param int contactlist id
	 * @return array
	 */
public function deleteassociatedcontact($contactlist_id, $contact_id)
{
	
	$contactlist = implode(',',$contactlist_id);
	$this->db->where_in('contactlist_id',$contactlist);
	
	$this->db->where("contact_id", $contact_id);
	$this->db->delete($this->associated_contacts);
	return $this->db->affected_rows();
}
	/**
	 * delete associated contact
	 *
	 * @access 	public
	 * @param int contactlist id
	 * @return array
	 */
public function deleteassociatedcontactbylist($contactlist_id)
{
	$this->db->where("contactlist_id ",$contactlist_id);
	$this->db->delete($this->associated_contacts);
	return $this->db->affected_rows();
}
	
	/**
	 * Get contact list with associated contacts
	 *
	 * @access 	public
	 * @param int user id
	 * @param int circle id
	 * @return array
	 */
public function contactlistbycircle($user_id, $circle = '')
{
	$this->db->select('cl.id, cl.user_id, cl.circle, cl.listname, cl.defaultstatus, c.id as contact_id, c.firstname, c.lastname, c.mobile, c.emails, c.sos_enabled, c.status, ocl.id as associated_id, ocl.listname as associated_listname, ocl.circle as associated_circle');
	$sub = $this->subquery->start_subquery('select');
	$sub->select('GROUP_CONCAT(contactlist_id) AS id');
	$sub->from($this->associated_contacts);
	$sub->where('contact_id = c.id');
	$this->subquery->end_subquery('associated_ids');
	$this->db->from($this->contactlists." AS cl");
	$this->db->join($this->associated_contacts." AS ac", 'cl.id = ac.contactlist_id', 'LEFT');
	$this->db->join($this->contacts." AS c", 'ac.contact_id = c.id', 'LEFT');
	$this->db->join($this->associated_contacts." AS oac", 'c.id = oac.contact_id', 'LEFT');
	$this->db->join($this->contactlists." AS ocl", 'oac.contactlist_id = ocl.id', 'LEFT');
	$this->db->where('cl.user_id', $user_id);
	if($circle != '')
	{
		$this->db->where('cl.circle', $circle);
	}
	$result = $this->db->get();
	return $result->result_array();
}
	
	/**
	 * Get contactlist by user
	 *
	 * @access 	public
	 * @param int user id
	 * @param int contact id
	 * @param int circle id
	 * @return array
	 */
public function contactlistbyuser($user_id, $contact_id = '', $circle = '')
{
	$contact_id = ($contact_id == '') ? '0' : $contact_id;
	$value = array($contact_id,$user_id);
	
	$sql = "SELECT
	cl.id as contactlist_id, cl.circle, cl.listname, cl.defaultstatus,
	IF((
		SELECT contact_id
		FROM associated_contacts
		WHERE contact_id = ? AND contactlist_id = cl.id GROUP BY contact_id)
IS NOT NULL, 1, 0)
AS is_associated FROM (`contactlists` AS cl) WHERE `user_id` = ?";

if($circle != '')
{
	$sql.= "  ADN circle = ?";
	$value = array_merge($value, array($circle));
	
}

$result = $this->db->query($sql,$value);
return $result->result_array();

}
	/**
	 * Get contact 
	 *
	 * @access 	public
	 * @param int user id
	 * @return array
	 */
public function contactbyuserid($user_id)
{
	$this->db->select("c.id, c.firstname, c.lastname, c.mobile, c.emails, c.sos_enabled, cl.id AS contactlist_id,cl.circle AS circletype, cl.listname, cl.circle");
	$sub = $this->subquery->start_subquery('select');
	$sub->select('GROUP_CONCAT(contactlist_id) AS id');
	$sub->from($this->associated_contacts);
	$sub->where('contact_id = c.id');
	$this->subquery->end_subquery('associated_id');
	
	$this->db->from($this->contacts." AS c");
	$this->db->join($this->associated_contacts." AS ac", 'c.id = ac.contact_id', 'LEFT');
	$this->db->join($this->contactlists." AS cl", 'ac.contactlist_id = cl.id', 'LEFT');
	$this->db->where('cl.user_id', $user_id);
	$this->db->order_by('c.firstname, c.lastname DESC');
	
	$result = $this->db->get();
	return $result->result_array();
}
	/**
	 * Get contact by contactlist
	 *
	 * @access 	public
	 * @param int contactlist id
	 * @return array
	 */
public function contactbycontactlist($contactlist_id)
{
	$this->db->select();
	$this->db->from($this->contacts." AS c");
	$this->db->join($this->associated_contacts." AS ac", 'c.id = ac.contact_id', 'LEFT');
	$this->db->where('ac.contactlist_id IN('.$this->db->escape_str($contactlist_id).')');
	$this->db->group_by('c.id');
	
	$result = $this->db->get();
	return $result->result_array();
}
	/**
	 * Get sos enabled contacts
	 *
	 * @access 	public
	 * @param int contactlist id
	 * @return array
	 */
public function getsoscontactbyuser($user_id)
{
	$this->db->select();
	$this->db->from($this->contacts." AS c");
	$this->db->join($this->associated_contacts." AS ac", 'ac.contact_id = c.id', 'LEFT');
	$this->db->join($this->contactlists." AS cl", 'cl.id = ac.contactlist_id', 'LEFT');
	$this->db->where('cl.user_id', $user_id);
	$this->db->where('c.sos_enabled', '1');
	$this->db->group_by('c.id');
	
	$result = $this->db->get();
	return $result->result_array();
}
	
	/**
	 * Get sos enabled contacts
	 *
	 * @access 	public
	 * @param int user id
	 * @return array
	 */
public function getsoscontactidbyuser($user_id)
{
	$this->db->select('c.id AS contact_id ,cl.id AS contactlist_id');
	$this->db->from($this->contacts." AS c");
	$this->db->join($this->associated_contacts." AS ac", 'ac.contact_id = c.id', 'LEFT');
	$this->db->join($this->contactlists." AS cl", 'cl.id = ac.contactlist_id', 'LEFT');
	$this->db->where('cl.user_id', $user_id);
	$this->db->where('c.sos_enabled', '1');
	$this->db->group_by('c.id');
	
	$result = $this->db->get();
	return $result->result_array();
}
	
	/**
	 * Get contacts list by contact
	 *
	 * @access 	public
	 * @param int user id
	 * @param int contact id
	 * @return array
	 */
public function getcontactlistbycontact($contact_id,$user_id)
{
	$this->db->select('c.id AS contact_id,cl.id AS contactlist_id');
	$this->db->from($this->contacts." AS c");
	$this->db->join($this->associated_contacts." AS ac", 'ac.contact_id = c.id', 'LEFT');
	$this->db->join($this->contactlists." AS cl", 'cl.id = ac.contactlist_id', 'LEFT');
	$this->db->where('cl.user_id', $user_id);
	$this->db->where('c.id', $contact_id);
	$this->db->where('c.sos_enabled', '1');
	$this->db->group_by('c.id');
	
	$result = $this->db->get();
	return $result->result_array();
}
/**
	 * Get Count sos created by user
	 *
	 * @access 	public
	 * @param int user id
	 * @param int contactlist id
	 * @return array
	 */
public function getcountsoscontactidbyuser($user_id,$contactlist_id = 0)
{
	$this->db->select('c.id AS contact_id, cl.id AS contactlist_id,COUNT( c.id ) AS count_id');
	$this->db->from($this->contacts." AS c");
	$this->db->join($this->associated_contacts." AS ac", 'ac.contact_id = c.id', 'LEFT');
	$this->db->join($this->contactlists." AS cl", 'cl.id = ac.contactlist_id', 'LEFT');
	$this->db->where('cl.user_id', $user_id);
	if($contactlist_id != 0)
	{
		$this->db->where('cl.id', $contactlist_id);
	}
	$this->db->where('c.sos_enabled', '1');
	$this->db->group_by('ac.contactlist_id');
	
	$result = $this->db->get();
	return $result->result_array();
}
	
	/**
	 * Get otp enable contact
	 *
	 * @access 	public
	 * @param int user id
	 * @param int contact id
	 * @return array
	 */
public function getcontactforotp($user_id,$contact_id)
{
	$this->db->select('c.id as cid,cl.id as clid,cl.user_id as uid');
	$this->db->from($this->contacts." AS c");
	$this->db->join($this->associated_contacts." AS ac", 'ac.contact_id = c.id', 'LEFT');
	$this->db->join($this->contactlists." AS cl", 'cl.id = ac.contactlist_id', 'LEFT');
	$this->db->where('cl.user_id', $user_id);
	$this->db->where('c.sos_enabled', '1');
	$this->db->where('c.id', $contact_id);
	$this->db->group_by('c.id');
	
	$result = $this->db->get();
	return $result->result_array();
}
	
	/**
	 * Get confirmed sos contact
	 *
	 * @access 	public
	 * @param int user id
	 * @param int contactlist id
	 * @return array
	 */
public function getconfirmedsoscontactbyuser($user_id, $contactlist_ids = '')
{
	$this->db->select();
	$this->db->from($this->contacts." AS c");
	$this->db->join($this->associated_contacts." AS ac", 'ac.contact_id = c.id', 'LEFT');
	$this->db->join($this->contactlists." AS cl", 'cl.id = ac.contactlist_id', 'LEFT');
	$this->db->where('cl.user_id', $user_id);
	$this->db->where('c.sos_enabled = "1"');
	if($contactlist_ids)
	{
		$contactlist = implode(',',$contactlist_ids);
		$this->db->where_in('cl.id',$contactlist);
	}
	
	$result = $this->db->get();
	return $result->result_array();
}
	
	/**
	 * Delete contact
	 *
	 * @access 	public
	 * @param int user id
	 * @param int  contact id
	 * @return int
	 */
public function deletecontactbyid($user_id, $contact_id)
{
	$select_result = $this->db->select();
	$select_result->from($this->contacts." AS c");
	$select_result->join($this->associated_contacts." AS ac", 'c.id = ac.contact_id', 'LEFT');
	$select_result->join($this->contactlists." AS cl", 'cl.id = ac.contactlist_id', 'LEFT');
	$select_result->where('cl.user_id', $user_id);
	$select_result->where('c.id', $contact_id);
	$result = $this->db->get()->num_rows;
	
	if($result > 0)
	{
		$delete_circle = $this->db->where_in('contact_id',$contact_id);
		$delete_circle->delete($this->associated_contacts);
		
		$delete_contacts = $this->db->where_in('id',$contact_id);
		$delete_contacts->delete($this->contacts);
		
		return $delete_contacts->affected_rows();
	}
	return 0;
}
	
	/**
	 * Get checkin contectlist
	 *
	 * @access 	public
	 * @param int checkin id
	 * @return array
	 */
public function getcheckincontectlist($checkin_id)
{
	
	$sql = "SELECT replace(GROUP_CONCAT(c.contactlist_id SEPARATOR ','), ' ', '') as contactlist FROM `checkincontactlist` as c
	where foreign_id = ? and table_id = '1'";
	
	$result = $this->db->query($sql,array($checkin_id));
	return $result->result_array();
}
	
	/**
	 * delete circle
	 *
	 * @access 	public
	 * @param int user id
	 * @param int contactlist id
	 * @return int
	 */
public function deletecontactlistbylistid($user_id, $contactlist_id)
{
	$select_result = $this->db->select('c.id');
	$select_result->from($this->contactlists." AS cl");
	$select_result->join($this->associated_contacts." AS ac", 'cl.id = ac.contactlist_id', 'LEFT');
	$select_result->join($this->contacts." AS c", 'c.id = ac.contact_id', 'LEFT');
	$select_result->where('cl.user_id', $user_id);
	$select_result->where('cl.id', $contactlist_id);
	$result = $select_result->get()->result_array();
	
	for($i=0; $i<count($result); $i++)
	{
		$contact_id = $result[$i]['id'];
		if($contact_id != '')
		{
			$check_associated = $this->db->select();
			$check_associated->from($this->associated_contacts);
			$check_associated->where("contact_id", $contact_id);
			$result[$i] = $check_associated->get()->num_rows();
			
			$this->db->delete($this->associated_contacts, array('contact_id' => $contact_id, 'contactlist_id' => $contactlist_id));
			
			if($result[$i] == 1)
			{
				$this->db->delete($this->contacts, array('id' => $contact_id));
			}
		}
	}
	
	$this->db->delete($this->contactlists, array('user_id' => $user_id, 'id' => $contactlist_id));
	
	return $this->db->affected_rows();
}
}
