<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class SchedulerModel extends CI_Model
{
	function __construct()
	{
		parent::__construct();	
		$this->CI =& get_instance();
	}
	/* 
	 * gets the templates file details of the component
	 */
	function getDates()
	{
		$this->db->select('s.date, c.class, c.description, c.detail_url');
		$this->db->from('scheduler as s');
		$this->db->join('component as c','c.id = s.parent_id','INNER');
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
	
	function sortDates()
	{
		$details = $this->getDates();
		$dates = array();
		foreach($details as $dt) {
		//print_r($dt);
			$year = (int)substr($dt['date'],0,5);
			$month = (int)substr($dt['date'],5,9);
			$date = (int)substr($dt['date'],8,10);
			if(substr($dt['date'], 0,4) == date('Y')) {	
				//$comp = $this->getScheduledContent($dt['parent_id']);
				//echo $dt['description'];
				if(!isset($dates[$year][$month][$date])) {
					$dates[$year][$month][$date] = array("<div class='content ".$dt['class']."'><span class='link_date'>".date("g:i a", strtotime($dt['date']))."</span> - <a href='/event_calendar/".str_replace(' ','_',strtolower($dt['description']))."/".str_replace(' ','_',$dt['date'])."' class='calendar-links'>".$dt['description']."</a></div>");
				} else {
					array_push($dates[$year][$month][$date],"<div class='content ".$dt['class']."'><span class='link_date'>".date("g:i a", strtotime($dt['date']))."</span> - <a href='/event_calendar/".str_replace(' ','_',strtolower($dt['description']))."/".str_replace(' ','_',$dt['date'])."' class='calendar-links'>".$dt['description']."</a></div>");
				}
			}
		}
		//print_r($dates[date('Y')][date('m')]);
		return $dates;
	}
	
	function todaysSchedule()
	{
		$schedule = $this->sortDates();
		//echo(int)date('d');
		if(isset($schedule[date('Y')][(int) date('m')][(int) date('d')])) return $schedule[date('Y')][(int) date('m')][(int) date('d')];
	}
	
	var $CI;
}