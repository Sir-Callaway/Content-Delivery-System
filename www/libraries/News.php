<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Header Library
 *
 * @author Muritu
 */
 

class MY_News
{
	var $CI;
	var $template = '';
	var $base;
	
	function MY_News($config = array())
	{	
		$this->CI =& get_instance();
		$this->base = $this->CI->config->item('base_url');
		$this->CI->load->helper('text');
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
	function strtotitle($title)  
	{ /* Converts $title to Title Case, and returns the result.
		Our array of 'small words' which shouldn't be capitalised if 
		they aren't the first word. Add your own words to taste. */
		$smallwordsarray = array( 'of','a','the','and','an','or','nor','but','is','if','then','else','when', 'at','from','by','on','off','for','in','out','over','to','into','with' ); 
		$acronymwordsarray = array('kcm','abrsm');
		// Split the string into separate words 
		$words = explode(' ', $title); 
		foreach ($words as $key => $word) { 
			// If this word is the first, or it's not one of our small words, capitalise it 
			// with ucwords(). 
			if ($key == 0 or !in_array($word, $smallwordsarray)) $words[$key] = ucwords($word); 
			foreach ($acronymwordsarray as $acr) {
				if ($word == $acr) $words[$key] =  strtoupper($word);			
			}
		} // Join the words back into a string 
		$newtitle = implode(' ', $words); 
		return $newtitle; 
	}
		
	function generater($data = array())
	{	//print_r($data);
		$callerPath = $data['callerPath'];
		$componentID = $data['componentID'];
		$readMore = '';
		foreach($data as $key => $value) {
			if ($key == 'artTitleAnchor') {	
				$readMore = '<a href="'.$value.'/'.$componentID.'">&rarr; read more</a>';
				$realTitle = str_replace('news/',' ',str_replace('noticeboard/',' ',str_replace('_',' ',$value)));
				$theTitle = $this->strtotitle($realTitle);
				$this->template = str_replace('{'.$key.'}','<a  href="'.$value.'/'.$componentID.'">'.$theTitle.'</a>',$this->template);
			} else if ($key == 'artAuthorAnchor') {
				$this->template = str_replace('{'.$key.'}',$value,$this->template);// no anchor for now
			} else if ($key == 'artTime') {
				$pubdate = substr(str_replace(' ','',str_replace('-','',str_replace(':','',$value))),0,8);
				//echo date("F jS, Y", strtotime($pubdate)).'<br />'.$pubdate.'<br />';
				$this->template = str_replace('{'.$key.'}','<time datetime="'.$value.'" pubdate>'.'<span class="kcmnews_month">'.date("M", strtotime($pubdate)).'</span><br/><span class="kcmnews_dayspan"><span class="kcmnews_day">'.date("j", strtotime($pubdate)).'</span><span class="kcmnews_datesuffix">'.date("S", strtotime($pubdate)).'</span></span></time>',$this->template);
			} else if ($key == 'artParagraph') {				
				if($callerPath == '' || $callerPath == 'noticeboard' || $callerPath == 'news') {
					$this->template = str_replace("/<img[^>]+\>/i", "", $this->template); 
					$this->template = str_replace('{'.$key.'}', word_limiter($value, 30), $this->template);
					$this->template = str_replace('{artReadMore}',$readMore,$this->template);
				} else {
					$this->template = preg_replace("/<img[^>]+\>/i", "(image) ", $this->template); 		
					$this->template = str_replace('{'.$key.'}', $value, $this->template);
					$this->template = str_replace('{artReadMore}',null,$this->template);
				}
			}
		}	
		return $this->template;
	}
	
}

/* End of file header.php */
/* Location: ./application/libraries/header.php */
?>