<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class SitemapModel extends CI_Model
{
	function __construct()
	{
		parent::__construct();	
		$this->CI =& get_instance();
	}
	
	function getPages()
	{	
		$this->db->select('p.menu_level, p.menu_position, p.url');
		$this->db->from('page as p');
		$this->db->where('p.status', 1);
		$this->db->where('p.menu_group =', 1);
		$this->db->order_by('p.menu_level ASC, p.menu_position ASC');
		$sitemapDetail = $this->db->get();
		// checks if there are any records to be retrieved from the DB		
		if ($sitemapDetail->num_rows() > 0) {
			$this->SitemapDetail = $sitemapDetail->result_array();
			$sitemapDetail->free_result();	
			return $this->SitemapDetail;		
		} else {
			//echo 'cant find page detail or no details exist!';
		}			
	}
	
	function getPagesContent()
	{	
		$this->db->select('p.menu_level, p.menu_position, p.url, c.detail_url');
		$this->db->from('page as p');
		$this->db->join('section_component as sc','sc.page_id = p.id','INNER');
		$this->db->join('component as c','c.id = sc.component_id','INNER');
		$this->db->where('p.status', 1);
		$this->db->where('p.menu_group =', 3);
		$this->db->where('sc.is_active', 1);
		$this->db->where('sc.section_id', 1);
		$this->db->order_by('p.menu_level ASC, p.menu_position ASC');
		$sitemapDetail = $this->db->get();
		// checks if there are any records to be retrieved from the DB		
		if ($sitemapDetail->num_rows() > 0) {
			$this->SitemapPagesContent = $sitemapDetail->result_array();
			$sitemapDetail->free_result();	
			return $this->SitemapPagesContent;		
		} else {
			//echo 'cant find page detail or no details exist!';
		}			
	}
	
	function getOtherPages()
	{	
		$this->db->select('p.menu_level, p.menu_position, p.url');
		$this->db->from('page as p');
		$this->db->where('p.status', 1);
		$this->db->where('p.menu_group =', 2);
		$this->db->order_by('p.menu_level DESC, p.menu_position ASC');
		$sitemapDetail = $this->db->get();
		// checks if there are any records to be retrieved from the DB		
		if ($sitemapDetail->num_rows() > 0) {
			$this->SitemapOtherPages = $sitemapDetail->result_array();
			$sitemapDetail->free_result();	
			return $this->SitemapOtherPages;		
		} else {
			//echo 'cant find page detail or no details exist!';
		}			
	}
	
	function getEvents()
	{	
		$this->db->select('c.detail_url');
		$this->db->from('component as c');
		$this->db->join('scheduler as s','s.description = c.id','INNER');
		$this->db->like('c.detail_url', 'calendar/');
		$this->db->where('s.status', 1);
		$this->db->order_by('s.date ASC');
		$sitemapDetail = $this->db->get();
		// checks if there are any records to be retrieved from the DB		
		if ($sitemapDetail->num_rows() > 0) {
			$this->SitemapEvents = $sitemapDetail->result_array();
			$sitemapDetail->free_result();	
			return $this->SitemapEvents;		
		} else {
			//echo 'cant find page detail or no details exist!';
		}			
	}
	
	function setSitemap($pageURL)
	{	
		$this->db->select('id, url, description, heading, menu_group, menu_level, menu_position, menu_parent, show_menu, show_sidebar, show_only_content');
		$this->db->from('page');
		$this->db->where('status', 1);
		$this->db->order_by('menu_level DESC, menu_parent DESC, menu_position ASC');
		$pageDetails = $this->db->get();
		// checks if there are any records to be retrieved from the DB		
		if ($pageDetails->num_rows() > 0) {
			$this->PageDetails = $pageDetails->result();
			$pageDetails->free_result();
			$this->setPageProperties($pageURL);
			
			//$this->setPageProperties($pageURL, $startLevel, $startParent);
		} else {
			//echo 'cant find page detail or no details exist!';
		}			
	}
	
	function setSitemapMenu($pageURL, $level) 
	{
		while ($level >= 0) {
			$this->buildSitemap((object)$this->UnsortedNav[$level], $level, $pageURL); 
			//echo '<br> level done <br>';$this->UnsortedNav[$page->menu_level][$page->id]['liClass']
			$level--;
		}
		//echo $this->SitemapMenu;
	}
	
	function analyzeSegments($pageUrl,$segArray)
	{
		$liClass = "unselected";
		if ($pageUrl == '' && empty($segArray)) {
			$liClass = 'selected';
		} else {
			foreach ($segArray as $seg) {
				//print_r($seg);
				if (trim($pageUrl) == trim($seg)) $liClass='selected';
			}
		}
		return $liClass;
	}
	
	function buildSitemap($item, $level)
	{
		$rootCount = 0;
		foreach ($item as $k => $details) {
			if ($details['parent'] != 0) {
				if (!isset($this->UnsortedNav[$level-1][$details['parent']]['list'])) {
					$this->UnsortedNav[$level-1][$details['parent']]['list'] = //set parent list									
						'<li class="'.$this->UnsortedNav[$level-1][$details['parent']]['liClass'].' parent">'."\n \t".$this->UnsortedNav[$level-1][$details['parent']]['anchor']."\n \t"//get parent anchor
																.'<ul>'."\n \t \t";//check if child list is set
					$this->UnsortedNav[$level-1][$details['parent']]['list'] .= (isset($details['list'])) ? 
										$details['list'].'</ul>'."\t".'</li>'."\n" : '<li class="'.$details['liClass'].' child">'.$details['anchor'].'</li>'."\n";													 																
					
				} else {
					$this->UnsortedNav[$level-1][$details['parent']]['list'] .= (isset($details['list'])) ? 
																					$details['list'].'</ul>'."\n".'</li>'."\n" : '<li class="'.$details['liClass'].' child">'.$details['anchor'].'</li>'."\n";													 																
																
				}				
			} else {	
				$liClass = '';
				if (count($this->UnsortedNav[0]) == ++$rootCount) $liClass = ' lastitem';					
				$this->SitemapMenu .=  (isset($details['list'])) ? $details['list']."\n".'</ul>'."\n".'</li>'."\n" 
								: '<li class="'.$details['liClass'].$liClass.'">'.$details['anchor'].'</li>'."\n";
			}
		}		
	}
	
	function getSitemapMenu()
	{
		return '<ul>'.$this->SitemapMenu.'</ul>';
	}	
	
	
	function setPageProperties($pageURL)
	{
		$highestLevel = 0;
		$segArray = array();
		$segments =  $this->uri->segment_array();
		foreach ($segments as $key => $seg)
		{	
			//echo "\n".$seg;
			if(!isset($segArray[0])) array_push ( $segArray ,$seg);
			else array_push ( $segArray ,$segArray[$key-2].'/'.$seg);
		}
		//print_r($this->PageDetails);
		// loop thru the pages
		foreach($this->PageDetails as $page)  {	
			
			// handle the Sitemap menu from children to parent menu			
			if ($highestLevel < $page->menu_level) $highestLevel = $page->menu_level;
			//echo $page->url."\n";
			$this->UnsortedNav[$page->menu_level][$page->id]['parent'] = $page->menu_parent;
			$this->UnsortedNav[$page->menu_level][$page->id]['liClass'] = $this->analyzeSegments($page->url,$segArray);
			$this->UnsortedNav[$page->menu_level][$page->id]['anchor'] = '<a href="/'.$page->url.'">'.$page->description."</a>";	
			$this->UnsortedNav[$page->menu_level][$page->id]['url'] = $page->url;
				
			
		}
		$this->setSitemapMenu($pageURL, $highestLevel);
	}
	
	var $UnsortedNav;
	var $PageDetails;
	var $SitemapDetail;
	var $SitemapOthers;
	var $SitemapPagesContent;
	var $SitemapEvents;
	var $CI;
}