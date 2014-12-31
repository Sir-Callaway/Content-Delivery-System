<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Header Library
 *
 * @author Muritu
 */
 

class MY_Intro_Article
{
	var $CI;
	var $template = '';
	var $base;
	
	function MY_Intro_Article($config = array())
	{	
		$this->CI =& get_instance();
		$this->base = $this->CI->config->item('base_url');
		if (count($config) > 0)
		{			
			$this->initialize($config);
		}
		log_message('debug', "Intro_Article Class Initialized");
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
	{	//print_r($data);
		foreach($data as $key => $value) {
			if ($key == 'inImage') {	
				$images = explode('|', $value); 
				$imageString = '';
				foreach ($images as $image) {
					$imageValue = str_replace('*',$this->base,$image);
					$imageString .= '<img '.$imageValue.' />'."\n";
				}
				$this->template = str_replace('{'.$key.'}',$value,$this->template);
			} else if ($key == 'inParagraph') {
				$this->template = str_replace('{'.$key.'}',$value,$this->template);
			}else {
				$this->template = str_replace('{'.$key.'}',$value,$this->template);
			}			
		}	
		return $this->template;
	}
}

/* End of file header.php */
/* Location: ./application/libraries/header.php */
?>