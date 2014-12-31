<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Calendar Router Controller
 *
 * @author Muritu
 */
 
class Calendar extends MY_Controller 
{
	public $data = array();

	public function index()
	{	
		$this->load->model('Schedulemodel');
		$this->data['centerContent'] = '<div class="calendar component" id="calendaring">'.$this->goToHelper(date('Y'),date('m')).'</div>';
		
        parent::index();
	}
	
	public function go_to($year,$month) 
	{	
		echo json_encode(array('calendar' => $this->goToHelper($year,$month)));		
	}
	
	public function goToHelper($year,$month) 
	{		
		$this->load->model('Schedulemodel');
		$this->data['inHeadFileSources'] = '<link rel="stylesheet" type="text/css" href="/css/template/calendar.css" media="all" />'."\n"."\t";		
	
		// toggle calendar 
		$prefs = array(
			'start_day' => 'monday',
			'month_type' => 'long',
			'day_type' => 'short',
			'show_next_prev' => TRUE,
			'next_prev_url' => base_url().'calendar',
			'template' => $this->parser->parse('template/calendar',$this->data,true)
		);
		$calendar_data = $this->Schedulemodel->calendateTermDates();
		$calendar_data['year'] = $year;
		$calendar_data['month'] = $month;
		
		$this->load->library('Calendar', $prefs);
		return $this->calendar->generate($calendar_data);		
	}
	
}

/* End of file Calendar.php */
/* Location: ./application/controllers/Calendar.php */
?>