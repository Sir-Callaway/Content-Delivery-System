<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Header Library
 *
 * @author Muritu
 */
 
class MY_Daily_Events
{
	var $CI;
	var $template = '';
	var $base;
	
	function MY_Daily_Events($config = array())
	{	
		$this->CI =& get_instance();
		$this->base = $this->CI->config->item('base_url');
		if (count($config) > 0)
		{			
			$this->initialize($config);
		}
		log_message('debug', "Banner_Slideshow Class Initialized");
	}
	
	function initialize($config = array())
	{
		foreach ($config as $key => $val)
		{
			if (isset($this->$key))
			{
				$this->$key = $val;
			}
		}
	}
	
	function evaluateEvents($key, $events)
	{
		$value = '';
		if (is_array($events)) {
			$value = '';
			foreach($events as $event) {
				$value .= '<h5 style="color: #000!important">'.$event.'</h5>';
			}
		} else $value = '<h5 style="color: #000!important">'.$events.'</h5>';
		return str_replace('{'.$key.'}',$value,$this->template);
	}
	
	function generater($data = array())
	{	//print_r($data);
		foreach($data as $key => $value) {
			//echo $value[date('j')];
			//echo $key;
			if ($key == 'dates') {
				if (isset($value[date('j')])) $this->template = $this->evaluateEvents('deEvents', $value[date('j')]);
				else $this->template = str_replace('{deEvents}','<h5>No Events today...</h5>',$this->template);
			}			
		}	
		return $this->template;
	}
}

/* End of file header.php */
/* Location: ./application/libraries/header.php */
?>