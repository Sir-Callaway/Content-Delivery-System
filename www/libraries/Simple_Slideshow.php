<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Header Library
 *
 * @author Muritu
 */
 


class My_Simple_Slideshow
{
	var $CI;
	var $template = '';
	var $base;
	
	function My_Simple_Slideshow($config = array())
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
		
	function generater($data = array())
	{
		$images = '';
		foreach($data as $value) {
			//echo $value;
			$images .= '<li><img alt="" src="'.$this->base.$value.'" /></li>';
		}	
		return str_replace('{image}',$images,$this->template);
	}
}

/* End of file header.php */
/* Location: ./application/libraries/header.php */
?>