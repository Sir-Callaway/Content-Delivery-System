<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Header Library
 *
 * @author Muritu
 */
 

class MY_Templates
{
	var $CI;
	var $externalLibrary = null;
	var $template = '';
	var $limitRequired = true;
	var	$paramLimitKey = '';
	
	function MY_Templates($config = array())
	{	
		$this->CI =& get_instance();
		$this->CI->load->helper('text');
		if (count($config) > 0)
		{			
			$this->initialize($config);
		}
		log_message('debug', "Intro_Article Class Initialized");
	}
	
	function initialize($config = array())
	{	//print_r($config);
		foreach ($config as $key => $val)
		{
			if (isset($this->$key))
			{
				$this->$key = $val;
			}
		}
	}
	
	
		
	function generate($data = array())
	{			
		//print_r($data['paramValue']);
		foreach($data['paramValues'] as $key => $value) {
			$type = $data['paramSettings'][$key]['type'];
			$repeat = $data['paramSettings'][$key]['repeats'];
			
			if ($type == 'image') {
				$imageString = '';
				if ($repeat == 1) {
					for ($i = 0; $i<count($value); $i++) {						 
						$imageValue = str_replace('<img','',$value[$i]);
						$imageValue = str_replace('/>','',$imageValue);
						$imageValue = str_replace('*','/',$imageValue);
						$imageString .= '<img '.trim($imageValue).'/>'."\n";
						if ($i == count($value) - 1) $this->template = str_replace('{'.$key.'}',$imageString,$this->template);
					}
				} else {		 
					$imageValue = str_replace('<img','',$value);
					$imageValue = str_replace('/>','',$imageValue);
					$imageValue = str_replace('*','/',$imageValue);
					$this->template = str_replace('{'.$key.'}','<img '.trim($imageValue).'/>',$this->template);
				}			
			} else if ($type == 'image_list') {
				$imageListString = '';
				if ($repeat == 1) {
					for ($i = 0; $i<count($value); $i++) {		 
						$imageValue = str_replace('<img','',$value[$i]);
						$imageValue = str_replace('/>','',$imageValue);
						$imageValue = str_replace('*','/',$imageValue);
						$imageListString .= '<li><img '.trim($imageValue).'/></li>'."\n";
						if ($i == count($value) - 1) $this->template = str_replace('{'.$key.'}',$imageListString,$this->template);
					}
				} else {
					$imageValue = str_replace('<img','',$value);
					$imageValue = str_replace('/>','',$imageValue);
					$imageValue = str_replace('*','/',$imageValue);
					$this->template = str_replace('{'.$key.'}','<li><img '.trim($imageValue).'/></li>',$this->template);
				}
			} else if ($type == 'article') {
				$wordlimit = $data['paramSettings'][$key]['wordlimit'];				
				if($this->limitRequired == 1 && $wordlimit != 0) {
					$this->template = str_replace("/<img[^>]+\>/i", "", $this->template); 
					$this->template = str_replace('{'.$key.'}',word_limiter($value, $wordlimit),$this->template);
				} else {
					$this->template = str_replace("/<img[^>]+\>/i", "", $this->template); 
					$this->template = str_replace('{'.$key.'}',$value,$this->template);
				}
			} else if ($type == 'heading') {
				$this->template = str_replace('{'.$key.'}',$this->strtotitle($value),$this->template);
			} else if ($type == 'link') {
				$linkString = '';
				$keyRep = str_replace('ReadMore','~',$key);		
				if ($key != $keyRep && $this->limitRequired == 0) {
					$this->template = str_replace("/<img[^>]+\>/i", "", $this->template);
					$this->template = str_replace('{'.$key.'}',$linkString,$this->template);
				} else {
					if ($repeat == 1) {
						for ($i = 0; $i<count($value); $i++) {
							$linkValue = str_replace('*','/',$value);
							$linkString .= $linkValue."\n";
							if ($i == count($value) - 1) $this->template = str_replace('{'.$key.'}',$linkString,$this->template);
						}
					} else {
						$linkValue = str_replace('*','/',$value);
					$this->template = str_replace("/<img[^>]+\>/i", "", $this->template);
						$this->template = str_replace('{'.$key.'}',$linkValue,$this->template);
					}
				}
			} else if ($type == 'link_list') {
				$linkListString = '';
				if ($repeat == 1) {
					for ($i = 0; $i<count($value); $i++) {
						$linkValue = str_replace('*','/',$img);
						$linkListString .= '<li><a '.$linkValue.'/></li>'."\n";
						if ($i == count($value) - 1) $this->template = str_replace('{'.$key.'}',$linkListString,$this->template);
					}
				} else {
					$this->template = str_replace("/<img[^>]+\>/i", "", $this->template);
					$this->template = str_replace('{'.$key.'}','<li><a '.$value.'/></li>',$this->template);
				}
			} else if ($type == 'date') {
				$date = date("l F jS, Y", strtotime($value));
				if ($repeat == 1) {} else {$this->template = str_replace('{'.$key.'}',$date,$this->template);}
			}  else if ($type == 'time') {
				$time = date("g:i a", strtotime($value));
				if ($repeat == 1) {} else {$this->template = str_replace('{'.$key.'}',$time,$this->template);}
			} else if ($type == 'datetime') {
				$time = date("l F jS, Y \\a\\t g:i a", strtotime($value));
				if ($repeat == 1) {} else {$this->template = str_replace('{'.$key.'}',$time,$this->template);}
			} else if ($type == 'caltoday') {
				$this->CI->load->model('Schedulemodel');
				$schedule = $this->CI->Schedulemodel->todaysSchedule();
				//print_r($schedule);
				$evs = '';
				if(isset($schedule[0])) {
					foreach($schedule as $event) {
						$evs .= $event;
					}
					$this->template = str_replace('{'.$key.'}',$evs,$this->template);
				} else {
					$this->template = str_replace('{'.$key.'}',$value,$this->template);
				} 
			}
		}
		
		return $this->template;
	}
	
		
	function strtotitle($title)  
	{ /* Converts $title to Title Case, and returns the result.
		Our array of 'small words' which shouldn't be capitalised if 
		they aren't the first word. Add your own words to taste. */
		$smallwordsarray = array( 'of','a','the','and','an','or','nor','but','is','if','then','else','when', 'at','from','by','for','in','out','over','to','into','with' ); 
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
	
	function imageALT($imgStr) 
	{
		//create a new DOMDocument object
		$xmlDoc = new DOMDocument();  
		//load your html for parsing
		$xmlDoc->loadHTML("<html><body><img id='imgTag'".$imgStr." /><br></body></html>");
		//select the element that you want the attribute from...you may need to use $xmlDoc->getElementsByTagName('p');
		$p_element = $xmlDoc->getElementById('imgTag');
		//get the attribute alt of the selected element
		$alt = $p_element->getAttribute('alt');
		//show alt attribute value
		$counter = str_word_count($alt);
		
		if ($counter != 0 || $counter > 25) {		
			if ($counter <= 10) {
				$limit = $counter;
			} else {
				$limit = $counter/2.3;
			}		
			return '<h6>'.word_limiter($alt, $limit).'</h6>';
		} else {
			return '';
		}
	}	
	
	function replaceImages($html)
	{
		$imgArray = array();
		$dom = new DOMDocument();
		@$dom->loadHTML($html);
		$xp = new DOMXPath($dom);
		$imgs = $xp->evaluate("//img");
		for ($i = 0; $i < $imgs->length; $i++) {
			$img = $imgs->item($i);
			$alt = $img->getAttribute('alt');
			$counter = str_word_count($alt);
			$limit=0;
			if ($counter != 0 || $counter > 40) {		
				if ($counter <= 20) {
					$limit = $counter;
				} else {
					$limit = $counter/2.3;
				}		
				$alt = '<h6>'.word_limiter($alt, $limit).'</h6>';
			} else {
				$alt = '';
			}
			//str_replace('{'.$key.'}','<li><a '.$value.'/></li>',$this->template)
			//echo $dom->saveXML($img);
			$html = str_replace((string)$dom->saveXML($img),'<div class="img">'.$dom->saveXML($img).$alt.'</div>',$html);			
		}
		//echo $html;
		return $html;
	}
}

/* End of file header.php */
/* Location: ./application/libraries/header.php */
?>