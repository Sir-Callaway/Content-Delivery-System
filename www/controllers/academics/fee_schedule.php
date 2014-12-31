<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Home Router Controller
 *
 * @author Muritu
 */
 
class Fee_schedule extends MY_Controller 
{
	public $data = array();
	
	public function tuition_fees()
	{
		parent::index();
	}
	
	public function other_fees()
	{
		parent::index();
	}
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */
?>