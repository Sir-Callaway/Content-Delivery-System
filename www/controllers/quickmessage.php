<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Home Router Controller
 *
 * @author Muritu
 */
 
class Quickmessage extends CI_Controller 
{
	function send()
	{
		$from = $_POST['mail'];
		$name = $_POST['name'];
		$text = $_POST['text'];

		$test = $this->to_them($from);
		if($test) {
		     $this->to_us($name, $from, $text);
		} else echo "Sending Failed!";
	}

	function to_us($from, $name, $text)
	{
		$this->email->clear();
		$this->email->from($from, $name);
		$this->email->to('enquiries@conservatoire.co.ke');

		$this->email->subject('inquiry: '.$name);
		$this->email->message($text);

		$this->email->send();

		echo "Successfully Sent!";
	}

	function to_them($from)
	{
                // the email to validate  
               // $email = array('enquiries@conservatoire.co.ke',$from);  
                // an optional sender  
               // $sender = 'enquiries@conservatoire.co.ke'; 
                // do the validation  
               // $result = $this->smtp_validate_email->validate($email, $sender);   
  
                // send email?   
               // if ($result) {  
                  //echo $result[0][0];

		  $this->email->clear();
		  $this->email->from('enquiries@conservatoire.co.ke');
		  $this->email->to($from);

		  $this->email->subject('Inquiry Received');
		  $this->email->message('We have received your inquiry. We will get back to you as soon as possible. Thank You.');

		  $this->email->send();

		  return TRUE;
               // }
	}

}
