<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class PageModel extends CI_Model
{
  var $PageDetails;
  var $PageHeading;
  var $ExternalPageID;
  var $PageBanner;
  var $PageFileSources;
  var $NavigationMenu = '';
  var $InHeadFileSources = '';
  var $InBodyFileSources = '';
  var $SubMenu;
  var $UnsortedNav = array();
  var $SortedNav = array();
  var $ParentMenu;
  var $PageSection;
  var $PageTitle = '';
  var $ShowMenu = true;
  var $ShowSidebar = true;
  var $ShowOnlyContent = false;
  
  
  function __construct()
  {
    parent::__construct();    
  }
  /* this function sets the page index via the name */
  function setPage($pageURL)
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
  
  function setExternalPageID($pageName)
  {
    $this->db->select('id');
    $this->db->from('page');
    $this->db->like('description', $pageName);
    $this->db->where('status', 1);
    $this->db->limit(1);
    $externalPageId = $this->db->get();
    // checks if there are any records to be retrieved from the DB    
    if ($externalPageId->num_rows() > 0) {
      return $this->ExternalPageID = $externalPageId->row()->id;      
      //$this->setPageProperties($pageURL, $startLevel, $startParent);
    } else {
      //echo 'cant find page detail or no details exist!';
      return $this->ExternalPageID = 0;
    }      
  }
  
  function getExternalPageID()
  {
    return (int) $this->ExternalPageID;
  }
  
  function setPageFileSources($pageId)
  {
    $this->db->select('fs.type, fs.url, fs.file_name, fs.is_external, pfs.position');
    $this->db->from('file_source AS fs');
    $this->db->join('page_file_source AS pfs','pfs.file_source_id = fs.id','INNER');
    $this->db->where('pfs.page_id', $pageId);
    $pageFileSources = $this->db->get();
    // checks if there are any records to be retrieved from the DB    
    if ($pageFileSources->num_rows() > 0) {
      $this->PageFileSources = $pageFileSources->result();
      //print_r($this->PageFileSources);
      $this->organizePageFileSources();
      $pageFileSources->free_result();
    } else {
      //echo 'cant find page section detail or no details exist!';
    }      
  }
  
  function setPageBanner ($pageId)
  {
    $this->db->select('m.name, m.url');
    $this->db->from('media AS m');
    $this->db->join('media_parent AS mp','mp.media_id = m.id','INNER');
    $this->db->where('mp.parent_id', $pageId);
    $this->db->where('mp.parent_type', 1);
    $this->db->limit(1);
    $pageBanner = $this->db->get();
    // checks if there are any records to be retrieved from the DB    
    if ($pageBanner->num_rows() > 0) {
      $this->PageBanner = $pageBanner->row()->url.$pageBanner->row()->name;
      //print_r($this->PageBanner);
      $pageBanner->free_result();
    } else {
      //echo 'cant find page section detail or no details exist!';
    }    
  }
  
  function organizePageFileSources()
  {
    $css = array(0 => '', 1 => '');
    $js = array(0 => '', 1 => '');
    $ico = array(0 => '', 1 => '');
    foreach($this->PageFileSources as $file) {
      //print_r($file);
      if ($file->is_external == 0) {
        if ($file->type == 2) // link      css/*.css
          $css[$file->position] .= '<link rel="stylesheet" type="text/css" href="/css/'.$file->url.$file->file_name.'.css" media="all">'."\n"."\t";
        if ($file->type == 4) // js      js/*.js
            $js[$file->position]  .= '<script type="text/javascript" src="/js/'.$file->url.$file->file_name.'.js"></script>'."\n"."\t";
        if ($file->type == 5) // icon        images/*.ico
            $ico[$file->position]  .= '<link rel="icon" type="image/ico" href="/images/'.$file->url.$file->file_name.'.ico" media="all">'."\n"."\t";
      } else {
        if ($file->type == 2) // link      css/*.css
          $css[$file->position] .= '<link rel="stylesheet" type="text/css" href="/'.$file->url.'" media="all">'."\n"."\t";
        if ($file->type == 4) // js      js/*.js
            $js[$file->position]  .= '<script type="text/javascript" src="/'.$file->url.'"></script>'."\n"."\t";
        if ($file->type == 5) // icon        images/*.ico
            $ico[$file->position]  .= '<link rel="icon" type="image/ico" href="/'.$file->url.'" media="all">'."\n"."\t";
      }    
    }
    $this->InHeadFileSources = $ico[0].$css[0].$js[0];
    $this->InBodyFileSources = $ico[1].$css[1].$js[1];
  }
  
  function setPageSections($pageId)
  {
    $this->db->select('sc.id, s.url, s.name');
    $this->db->from('section AS s');
    $this->db->join('section_component AS sc','sc.section_id = s.id','INNER');
    $this->db->where('sc.page_id', $pageId);
    $this->db->where('sc.is_active', 1);
    $this->db->order_by('s.id','ASC');
    $this->db->order_by('sc.position','ASC');
    $pageSection = $this->db->get();
    // checks if there are any records to be retrieved from the DB    
    if ($pageSection->num_rows() > 0) {
      $this->PageSection = array();
      $this->PageSection = $pageSection->result();
      //print_r($this->PageSection);
      $pageSection->free_result();
    } else {
      //echo 'cant find page section detail or no details exist!';
    }    
  }
  
  function setPageHeading($pageHeading)
  {
    $this->PageHeading = $pageHeading;
  }
  
  function getPageSections()
  {
    return (object) $this->PageSection;
  }
  
  function getPageHeading()
  {
    return $this->PageHeading;
  }
  
  function getPageBanner()
  {
    return $this->PageBanner;
  }
  
  function getPageInHeadFileSources()
  {
    //echo $this->InHeadFileSources;
    return $this->InHeadFileSources;
  }
  
  function getPageInBodyFileSources()
  {
    return $this->InBodyFileSources;
  }
  
  function setNavigationMenu($pageURL, $level) 
  {
    while ($level >= 0) {
      $this->buildNavigation((object)$this->UnsortedNav[$level], $level, $pageURL); 
      //echo '<br> level done <br>';$this->UnsortedNav[$page->menu_level][$page->id]['liClass']
      $level--;
    }
    //echo $this->NavigationMenu;
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
  
  function buildNavigation($item, $level)
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
        $this->NavigationMenu .=  (isset($details['list'])) ? $details['list']."\n".'</ul>'."\n".'</li>'."\n" 
                : '<li class="'.$details['liClass'].$liClass.'">'.$details['anchor'].'</li>'."\n";
      }
    }    
  }
  
  function getNavigationMenu()
  {
    return '<ul>'.$this->NavigationMenu.'</ul>';
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
      //print_r($page);
      //echo $pageURL."\n".$page->url."\n".$page->id."\n";
      // set the page details for the calling page
      if($page->url == $pageURL) {
        //echo $page->show_sidebar;
        $this->setPageSections($page->id);
        $this->setPageFileSources($page->id);
        $this->setPageBanner($page->id);
        $this->setPageHeading(trim($page->heading));
        
        if ($page->show_only_content == 1) {
          $this->ShowOnlyContent = true;
        } else {
          $this->ShowOnlyContent = false;
        }
        
        if ($page->show_sidebar == 1) {
          $this->ShowSidebar = true;
        } else {
          $this->ShowMenu = false;
        }
        
        if ($page->show_menu == 1) {
          $this->ShowMenu = true;
        } else {
          $this->ShowMenu = false;
        }
        $this->PageTitle = trim($page->description);
      }
      // handle the navigation menu from children to parent menu
      if($page->menu_group == 1) {
        if ($highestLevel < $page->menu_level) $highestLevel = $page->menu_level;
        //echo $page->url."\n";
        $this->UnsortedNav[$page->menu_level][$page->id]['parent'] = $page->menu_parent;
        $this->UnsortedNav[$page->menu_level][$page->id]['liClass'] = $this->analyzeSegments($page->url,$segArray);
        $this->UnsortedNav[$page->menu_level][$page->id]['anchor'] = '<a href="/'.$page->url.'">'.$page->description."</a>";  
        $this->UnsortedNav[$page->menu_level][$page->id]['url'] = $page->url;
        
      } else if($page->menu_group == 2) {
        // organize footer menu
      } else if($page->menu_group == 3) {
        // organize see also menu
      } else {
        // organize calendar links
        
      }
    }
    $this->setNavigationMenu($pageURL, $highestLevel);
  }
  
  /* these following functions are the getter methods for the view properties of the page */
  function getShowOnlyContent()
  {
    return $this->ShowOnlyContent;
  }
  function getShowMenu()
  {
    return $this->ShowMenu;
  }
  function getShowSidebar()
  {
    return $this->ShowSidebar;
  }
  // this function returns the page title from PageDetails array */
  function getPageTitle()
  {
    return $this->PageTitle;
  }
}
?>