<?php

/**
* Name:  MediaFunc
* @package MediaFunc
* @version 1.0
*/
class MediaFunc extends CI_Model
{
	public $media = 'media';
	public $users = 'iwmf_user.users';
	public $checkin = 'checkin';
	
	function __construct()
	{
		/* Call the Model constructor*/
		parent::__construct();
	}
	/**
	 * Insert media
	 *
	 * @access 	public
	 * @param array Media data
	 * @return int
	 */
	public function addmedia($InsertData)
	{
		$this->db->insert($this->media, $InsertData);
		return $this->db->insert_id();
	}
	/**
	 * delete media
	 *
	 * @access 	public
	 * @param array Media data
	 * @return int
	 */
	public function deletemedia($media_id)
	{
		$this->db->delete($this->media, array('id' => $media_id));
		return $this->db->affected_rows();
	}
	/**
	 * Get media
	 *
	 * @access 	public
	 * @param int media id
	 * @return array
	 */
	public function getmediabyid($media_id)
	{
		$this->db->select();
		$this->db->from($this->media." AS m");
		$this->db->where('m.id', $media_id);
		
		$result = $this->db->get();
		return $result->result_array();
	}
	/**
	 * Get checkin media list 
	 *
	 * @access 	public
	 * @param int checkin id
	 * @return array
	 */
	public function getmediabycheckin($checkin_id)
	{
		$this->db->select();
		$this->db->from($this->media." AS m");
		$this->db->where('m.foreign_id', $checkin_id);
		
		$result = $this->db->get();
		return $result->result_array();
	}
	/**
	 * Get media list by user
	 *
	 * @access 	public
	 * @param int user id
	 * @return array
	 */
	public function getmediabyuser($user_id)
	{
		$this->db->select();
		$this->db->from($this->media." AS m");
		$this->db->where('m.user_id', $user_id);
		
		$result = $this->db->get();
		return $result->result_array();
	}
	
	/**
	 * Get media
	 *
	 * @access 	public
	 * @param int Checkin or alert id
	 * @param int type of foreign id (1=>checkin id, 2=>alert id)
	 * @return array
	 */
	public function getmediabycheckinwithtable($foreign_id , $table_id)
	{
		$this->db->select();
		$this->db->from($this->media." AS m");
		$this->db->where('m.foreign_id', $foreign_id);
		$this->db->where('m.table_id', $table_id);
		
		$result = $this->db->get();
		return $result->result_array();
	}
	/**
	 * Delete media
	 *
	 * @access 	public
	 * @param int Checkin or alert id
	 * @param int type of foreign id (1=>checkin id, 2=>alert id)
	 * @return int
	 */
	public function deletemediabyforeignid($foreign_id,$table_id)
	{
		$result_media = $this->getmediabycheckinwithtable($foreign_id,$table_id);
		for($i=0; $i < count($result_media);$i++)
		{
			if($result_media[$i]['mediatype'] == '1')
			{
				$folder='assets/uploads/audio/';
			}
			elseif($result_media[$i]['mediatype'] == '2')
			{
				$folder='assets/uploads/video/';
			}
			elseif($result_media[$i]['mediatype'] == '3')
			{
				$folder='assets/uploads/picture/';
			}
			$file = $folder . $result_media[$i]['medianame'];
			if(file_exists($file) )
			{
				unlink($file);
			}
		}
		
		$this->db->delete($this->media, array('foreign_id' => $foreign_id, 'table_id' => $table_id));
		return $this->db->affected_rows();
	}
	/**
	 * Insert media
	 *
	 * @access 	public
	 * @return void
	 */
	public function deletesevendaymedia()
	{
		$date = date('Y-m-d H:i:s',(strtotime(CURRENT_DATETIME) - 604800));
		$this->db->select();
		$this->db->from($this->media);
		$this->db->where('created_on < ', $date);
		
		$result = $this->db->get();
		$result_media=$result->result_array();
		
		for($i=0; $i < count($result_media);$i++)
		{
			if($result_media[$i]['mediatype'] == '1')
			{
				$folder='assets/uploads/audio/';
			}
			elseif($result_media[$i]['mediatype'] == '2')
			{
				$folder='assets/uploads/video/';
			}
			elseif($result_media[$i]['mediatype'] == '3')
			{
				$folder='assets/uploads/picture/';
			}
			$file = $folder . $result_media[$i]['medianame'];
			if(file_exists($file) )
			{
				unlink($file);
			}
			$this->db->delete($this->media, array('id' => $result_media[$i]['id']));
		}
	}
	/**
	 * upload media file
	 *
	 * @access 	public
	 * @param string data image
	 * @param int type of media ( 1=>audio, 2=>video, 3=>picture)
	 * @param string extension of media
	 * @return string
	 */
	public function uploadfile($imageData, $mediatype, $extension) 
	{
		if($mediatype == '1')
		{
			$folder='uploads/audio/';
		}
		elseif($mediatype == '2')
		{
			$folder='uploads/video/';
		}
		elseif($mediatype == '3')
		{
			$folder='uploads/picture/';
		}
		
		$filepath = DOCUMENT_ROOT.SUB_DIR.$folder;
		
		$filename = base_convert(str_replace(' ', '', microtime()) . rand(), 10, 36) .".".$extension;
		
		$file = fopen($filepath.$filename,"w");
		$imageData = base64_decode($imageData);
		fwrite($file,$imageData);
		fclose($file);
		
		return $filename;
	}
}
