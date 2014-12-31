<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class ScheduleModel extends CI_Model
{
	function __construct()
	{
		parent::__construct();	
		$this->CI =& get_instance();
	}
	/* 
	 * gets the templates file details of the component
	 */	
	function getContentDates ()
	{
		$this->db->select('s.date, c.class, c.description, c.detail_url');
		$this->db->from('scheduler as s');
		$this->db->join('component as c','c.id = s.description','INNER');	
		$this->db->where('has_component_link = 1');		
		$this->db->where('s.status = 1');		
		$this->db->order_by('s.date','ASC');
		$dates = $this->db->get();
		// checks if there are any records to be retrieved from the DB		
		if ($dates->num_rows() > 0) {
			$details = $dates->result_array();
			$dates->free_result();
			return $details;
		} else {
			//echo 'cant find the requested section!';
		}		
	}
	function getTermDates ()
	{
		$this->db->select('s.date, s.description');
		$this->db->from('scheduler as s');
		$this->db->where('has_component_link = 0');		
		$this->db->where('status = 1');		
		$this->db->order_by('date','ASC');
		$dates = $this->db->get();
		// checks if there are any records to be retrieved from the DB		
		if ($dates->num_rows() > 0) {
			$details = $dates->result_array();
			$dates->free_result();
			return $details;
		} else {
			//echo 'cant find the requested section!';
		}	
	}
	
	function getTermLimits ()
	{
		$this->db->select('s.date');
		$this->db->from('scheduler as s');
		$this->db->where('has_component_link = 0');		
		$this->db->where('status = 1');		
		$this->db->like('date',date('Y'));		
		$this->db->like('description','term');		
		$this->db->like('description','starts');		
		$this->db->or_like('description','term');		
		$this->db->like('description','begins');
		$this->db->not_like('description','mid');
		$this->db->limit(3);		
		$this->db->order_by('date','ASC');
		$dates = $this->db->get();
		// checks if there are any records to be retrieved from the DB		
		if ($dates->num_rows() > 0) {
			$details = $dates->result_array();
			$dates->free_result();
			return $details;
		} else {
			//echo 'cant find the requested section!';
		}	
	}
	
	function organizeSchedule ()
	{
		$details = $this->getTermDates();
		$details1 = $this->getContentDates();
		foreach($details1 as $cdate) 
		{
			array_push($details,$cdate);
		}
		return $details;
		
	}
	
	function calendateTermDates()
	{
		$details = $this->organizeSchedule();
		$dates = array();
		$previousTime[$date] = '';
		foreach($details as $dt) {
		//print_r($dt);
			$year = substr($dt['date'],0,4);
			$month = substr($dt['date'],5,2);
			$date = (int)substr($dt['date'],8,2);
				//$comp = $this->getScheduledContent($dt['parent_id']);
				//echo $dt['description'];
				$time = (date("g:i", strtotime($dt['date'])) == '12:00')? '': date("g:i a", strtotime($dt['date']));
				if(!isset($dt['detail_url'])){
					if(!isset($dates[$year][$month][$date])) {
						$dates[$year][$month][$date] = array("<span class='link_date'>".$time."</span><div class='content term'><a href='/term_dates' class='calendar-links'>".$dt['description']."</a></div>");
						$previousTime[$date] = $time;
					} else {
						//echo $previousTime[$date];
						//echo $time;
						if($previousTime[$date] == $time) {
							array_push($dates[$year][$month][$date],"<div class='content term'><a href='/term_dates' class='calendar-links'>".$dt['description']."</a></div>");
						} else {
							array_push($dates[$year][$month][$date],"<span class='link_date'>".$time."</span><div class='content term'><a href='/term_dates' class='calendar-links'>".$dt['description']."</a></div>");
							$previousTime[$date] = $time;
						}	
					}
				} else {
				
					if(!isset($dates[$year][$month][$date])) {
						$dates[$year][$month][$date] = array("<span class='link_date'>".$time."</span><div class='content ".$dt['class']."'><a href='/".$dt['detail_url']."' class='calendar-links'>".$dt['description']."</a></div>");
						$previousTime[$date] = $time;
					} else {
						//echo $previousTime[$date];
						//echo $time;
						if($previousTime[$date] == $time) {
							array_push($dates[$year][$month][$date],"<div class='content ".$dt['class']."'><a href='/".$dt['detail_url']."' class='calendar-links'>".$dt['description']."</a></div>");
						} else {
							array_push($dates[$year][$month][$date],"<span class='link_date'>".$time."</span><div class='content ".$dt['class']."'><a href='/".$dt['detail_url']."' class='calendar-links'>".$dt['description']."</a></div>");
							$previousTime[$date] = $time;
						}	
					}
				}
			}
		
		//print_r($dates);
		return $dates;
	}
	
	function todaysSchedule()
	{
		$schedule = $this->calendateTermDates();
		//echo(int)date('d');
		if(isset($schedule[date('Y')][date('m')][(int) date('d')])) return $schedule[date('Y')][date('m')][(int) date('d')];
	}
	
	var $CI;
}