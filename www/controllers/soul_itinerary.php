<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Soul Router Controller
 *
 * @author Muritu
 */
class Soul_itinerary extends MY_Controller 
{
	public $data = array();
	
	public function index()
	{
		parent::index();
	}	
	
	public function soul_reservation($date, $timing)
	{
			parent::_initiateReadMore();
			// show or hide the sidebar
		$this->data['showSidebar'] = false;
		// hides the menu and sidebar if true
		$this->data['showOnlyContent'] = true;
		$this->data['pageHeading'] = 'Soul Reservation';
		$this->data['pageTitle'] = 'Soul Reservation';
		
		$this->data['inHeadFileSources'] = '<link rel="stylesheet" type="text/css" href="/css/template/soulform.css" media="all">';
		$this->data['centerContent'] = /*'<div class="content form soulreservform">
<h1>	
	Reserve SOUL-Kenya 2013 on '.$date.'
</h1>					
			<form id="reservationform" action="/soul_itinerary/send" method="POST" enctype="multipart/form-data" class="soulform">
				<ul>
					<li>
						<label for="soulname">Your Name</label>
						<input id="soulname" name="soulname" type="text" required>
					</li>
					<li>
						<label for="soulnumber">Phone Number</label>
						<input id="soulnumber" name="soulnumber" type="text" required>
					</li>
									
					<li>
						<label for="soulmail">Your Email</label>
						<input id="soulmail" name="soulmail" type="text" required>
					</li>
					<li>
						<label for="soulinstitution">School/Institution</label>
						<input id="soulinstitution" name="soulinstitution" type="text" required>
					</li>	
					<li class="clear">
						<label for="soultext">Message</label>
						<textarea id="soultext" name="soultext" required>I\'d like to book SOUL-Kenya 2013 for the '.$timing.' on the '.$date.'.</textarea>
					</li>
					<li>						
						<button id="sousend" class="btns green" type="submit" style="margin-top: 1em; float: right;">Reserve</button>
					</li>	
					<li>
						<div id="soulresponse" style="margin-top: 1em;"></div>
					</li>
				</ul>
			</form></div>';*/
			parent::_setBreadcrumbs(array(1=>$this->uri->segment(1),2=>$this->uri->segment(2)));
			parent::_loadPages();
	}
	
	public function send()
	{
		$from = trim($_POST['soulmail']);
		$name = trim($_POST['soulname']);
		$text = $_POST['soultext']."Number: ".$_POST['soulnumber']."Institution: ".$_POST['soulinstitution'];
		$test = $this->to_us($name, $from, $text);
//if($test) {

		//echo "Successfully Sent!";		     
		//} else echo "Sending Failed!";

			parent::_initiateReadMore();
		$this->data['showSidebar'] = false;
		// hides the menu and sidebar if true
		$this->data['showOnlyContent'] = true;
		$this->data['pageHeading'] = 'Soul Reservation Sent';
		$this->data['pageTitle'] = 'Sent';
$this->data['centerContent'] = '<div class="component content" style="background-color:rgba(98, 146, 33, 0.7); font-weight: bold; font-size: 1.5em; padding: 10px; margin-bottom: 10px;">Soul Reservation Successfully Sent!</div><a href="/soul"><button class="btns blue" style="margin: 0px auto;">Go Back</button></a>';

			parent::_setBreadcrumbs(array(1=>$this->uri->segment(1),2=>$this->uri->segment(2)));
			parent::_loadPages();
	}
	public function to_us($from, $name, $text)	
	{
		$this->email->clear();
		$this->email->from($from, $name);
		$this->email->to('director@conservatoire.co.ke');

		$this->email->subject('Soul Booking');
		$this->email->message($text);

		$this->email->send();
return true;
	}
}

/* End of file Soul.php */
/* Location: ./application/controllers/Soul.php */
?>