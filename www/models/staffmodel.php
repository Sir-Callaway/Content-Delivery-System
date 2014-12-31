<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class StaffModel extends CI_Model
{
	function __construct()
	{
		parent::__construct();	
		$this->CI =& get_instance();
	}

	/* 
	 * gets the information required to make the staff's namecard
	 */
	function getNameCard()
	{
		$this->db->select('s.fname, s.sname, so.office_email');
		$this->db->from('staff as s');
		$this->db->join('staff_occu as so','so.staff_id = s.id','INNER');
		$nameCard = $this->db->get();
		// checks if there are any records to be retrieved from the DB		
		if ($nameCard->num_rows() > 0) {
			$this->NameCard = $nameCard->result_array();
			$nameCard->free_result();
			print_r($this->NameCard);
		} else {
			//echo 'cant find the requested section!';
		}		
	}
	
	
	
	var $CI;
	var $NameCard;
}