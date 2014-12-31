<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Term_Dates Router Controller
 *
 * @author Muritu
 */
class Term_Dates extends MY_Controller 
{
	public $data = array();
	
	public function index()
	{	
		$this->buildTermTables();		
        parent::index();
	}
	
	private function buildTermTables()
	{
		$limitCount = 0;
		$this->load->model('Schedulemodel');
		$limits = $this->Schedulemodel->getTermlimits();
		$dates = $this->Schedulemodel->getTermDates();
		foreach($dates as $date) {	
			//echo $limits[$limitCount];
			if($date['date'] === $limits[$limitCount]['date']) {
				//echo $limits[$limitCount];
				if($limitCount == 0) {
					$this->data['centerContent'] .= '<h1>Term '.($limitCount+1).'</h1> <table><tr><td>'.
										date('l d', strtotime($date['date'])).'<sup>'.date('S', strtotime($date['date'])).'</sup>'.
										date('\, F', strtotime($date['date'])).'</td><td>'.$date['description'].'</td></tr>';
				} else {
					$this->data['centerContent'] .= '</table><h1>Term '.($limitCount+1).'</h1> <table><tr><td>'.
										date('l d', strtotime($date['date'])).'<sup>'.date('S', strtotime($date['date'])).'</sup>'.
										date('\, F', strtotime($date['date'])).'</td><td>'.$date['description'].'</td></tr>';
				}
				$limitCount++;
			} else {
				$this->data['centerContent'] .= '<tr><td>'.date('l d', strtotime($date['date'])).'<sup>'.date('S', strtotime($date['date'])).'</sup>'.
										date('\, F', strtotime($date['date'])).'</td><td>'.$date['description'].'</td></tr>';
			} 
		}
		$this->data['centerContent'] .= '</table><br /><p>Click here for last year\'s dates <a href="/at_a_glance">at a glance</a></p>';
		
	}
	
	private $CenterContent = '';
}

/* End of file Term_Dates.php */
/* Location: ./application/controllers/Term_Dates.php */
?>