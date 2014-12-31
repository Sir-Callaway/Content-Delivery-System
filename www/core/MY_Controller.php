<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package    CodeIgniter
 * @author    ExpressionEngine Dev Team
 * @copyright  Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license    http://codeigniter.com/user_guide/license.html
 * @link    http://codeigniter.com
 * @since    Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter Controller Class
 *
 * This class enables the creation of controllers
 *
 * @package    CodeIgniter
 * @subpackage  Core
 * @category  Core
 * @author    ExpressionEngine Dev Team
 */
class MY_Controller extends CI_Controller {

  public $thisPath;
  public $ComponentDescription;
  /**
   * Constructor
   */
  public function __construct($config = array())
  {
    parent::__construct($config);
  }
  
  public function index()
  {    
    $this->_initiatePage();
    
    $this->_loadSectionComponents();
    
    $this->_loadPages();    
  }
  
  public function read_more($singleTitle, $singleDate)
  {  
    $this->_initiateReadMore();
    if($this->data['controller'] == 'calendar') {
      $this->data['inHeadFileSources'] .= '<link rel="stylesheet" type="text/css" href="/css/template/events.css" media="all">'."\n"."\t";
              
      $this->data['centerContent'] = $this->_loadSingleComponent($this->ComponentModel->getSpecificComponent((int) $this->ComponentModel->getScheduledComponentID($singleTitle, $singleDate)));
    } else {
      $this->data['centerContent'] = $this->_loadSingleComponent($this->ComponentModel->getSpecificComponent((int) $this->ComponentModel->getComponentID($singleTitle, $singleDate)));
    }
    $this->_loadPages();
  }
  
  public function _loadPages()
  {
    
      /*=========================return the header view=========================*/    
      $this->data['header'] = auto_link($this->parser->parse('parts/header',$this->data,true),'email');
      
      /*=========================return the body view=========================*/
      $this->data['body'] = auto_link($this->parser->parse('parts/body',$this->data,true),'email');    
      
      /*=========================return the footer view=========================*/    
      $this->data['footer'] = auto_link($this->parser->parse('parts/footer',$this->data,true), 'email');
      
      /*=========================loading the main view=========================*/    
      $this->parser->parse('main',$this->data);  
  }
  
  public function _initiateReadMore()
  {
    // initialize subview variables
    $this->data['bannerSlideShow'] = '';
    $this->data['centerContent'] = '';
    $this->data['advertContent'] = '';
    $this->data['description'] = '';
    $this->data['pageTitle'] = '';
    $this->data['pageHeading'] = '';
    $this->data['sidebarContent'] = '';
    $this->data['footerRibbon'] = '';
    $this->data['footerPrimary'] = '';
    $this->data['noticeboard'] = '';
    $this->data['news'] = '';
    $this->data['limitRequired'] = false; // get rid of any word limits that are set
    $this->thisPath = $this->uri->uri_string();
    $segmentCount = $this->uri->total_segments();
    $this->data['controller'] = $this->uri->segment($segmentCount-2);
    $this->PageModel->setPage($this->uri->segment($segmentCount-2));
    
    // set inhead file sources and inbody filesources
    $this->_setInHeadFileSources();
    $this->_setInBodyFileSources();
    // hide or show the upper navigation including nav container      
    $this->data['showMenu'] = $this->PageModel->getShowMenu();  
    // show or hide the sidebar
    $this->data['showSidebar'] = $this->PageModel->getShowSidebar();
    // hides the menu and sidebar if true
    $this->data['showOnlyContent'] = $this->PageModel->getShowOnlyContent();
    // get banner image
    $this->data['banner'] = 'url('.$this->PageModel->getPageBanner().')';
    // set title of the page
    $this->_setPageTitle($this->PageModel->getPageHeading());
    // set heading of the page
    $this->_setPageHeading($this->PageModel->getPageHeading());
    // set breadcrumbs
    $this->_setBreadcrumbs(array(1=>$this->uri->segment($segmentCount-2),2=>$this->uri->segment($segmentCount-1)));
    // set the navigation menu and sections
    $this->_setNavigationMenu();
  }
  
  public function _initiatePage()
  {    
    // initialize subview variables
    //$this->data = array();
        if(!isset($this->data['centerContent'])) {
        $this->data['centerContent'] = '';
        }
    $this->data['sidebarContent'] = '';
    $this->data['advertContent'] = '';
    $this->data['bannerSlideShow'] = '';
    $this->data['description'] = '';
    $this->data['pageTitle'] = '';
    $this->data['footerRibbon'] = '';
    $this->data['footerPrimary'] = '';
    $this->data['noticeboard'] = '';
    $this->data['news'] = '';
    $this->data['limitRequired'] = true;
    $this->thisPath = $this->uri->uri_string();
    $this->data['controller'] = $this->thisPath;
    $this->PageModel->setPage($this->thisPath);
    
    // set inhead file sources and inbody filesources
    $this->_setInHeadFileSources();
    $this->_setInBodyFileSources();
    // hide or show the upper navigation including nav container      
    $this->data['showMenu'] = $this->PageModel->getShowMenu();  
    // show or hide the sidebar
    $this->data['showSidebar'] = $this->PageModel->getShowSidebar();
    // hides the menu and sidebar if true
    $this->data['showOnlyContent'] = $this->PageModel->getShowOnlyContent();
    // get banner image
    $this->data['banner'] = 'url(/'.$this->PageModel->getPageBanner().')';
    // set title of the page
    $this->_setPageTitle($this->PageModel->getPageHeading());
    // set heading of the page
    $this->_setPageHeading($this->PageModel->getPageHeading());
    // set breadcrumbs
    $this->_setBreadcrumbs($this->uri->segment_array());
    // set the navigation menu and sections
    $this->_setNavigationMenu();
  }
  private function _setPageTitle($pageTitle)
  {
    $this->data['pageTitle'] = $pageTitle;
  }
  public function components($pageName, $start, $limit)
  {
    $this->PageModel->setExternalPageID($pageName);
    $scID = $this->PageModel->getExternalPageID();
    $componentCode = null;
    $components = array();
    $libr = '';
    $css = '';
    $js = '';
    $pref['template'] = '';
    $pref['limitRequired'] = true;
    $components = $this->ComponentModel->getExternalComponent($scID, $start, $limit);
    foreach ($components as $comp) {
      //print_r($comp);
      if ($comp['template'] == 1) {
        $params= array();
        $paramCount = 0;
        $template = '';
        $previousParam = '';
        $params['callerPath'] = $this->thisPath;
        //echo $this->thisPath;
        // get the templates parameters        
        // pass params to the template and include other files
        foreach($comp['files'] as $files) {  
            //print_r($files);echo '<br />';
            if ($files['is_external'] == 0) {
              if($files['type']== 1) {// template   view/templates/*.php
                $pref['template'] = $this->load->view($files['url'].$files['file_name'],null,true);
                $template = $pref['template'];
              } else if($files['type']== 2){ // link      css/*.css
                $css .= '<link rel="stylesheet" type="text/css" href="/css/'.$files['url'].$files['file_name'].'.css" media="all">'."\n"."\t";
              } else if($files['type']== 4){ // js      js/*.js
                $js .= '<script type="text/javascript" src="/js/'.$files['url'].$files['file_name'].'.js"></script>'."\n"."\t";  
              }
            } else {
              if($files['type']== 2){ // link      css/*.css
                $css .= '<link rel="stylesheet" type="text/css" href="/'.$files['url'].'" media="all">'."\n"."\t";
              } else if($files['type']== 4){ // js      js/*.js
                $js .= '<script type="text/javascript" src="/'.$files['url'].'"></script>'."\n"."\t";  
              }
            }
          }
          $ins = new $this->templates($pref);
          $template = $ins->generate($comp['param']);
          $template = preg_replace("/<img[^>]+\>/i", " ", $template);
                  
          $componentCode .= '<div class="'.$comp['class'].'" id="comp-'.$comp['id'].'" style="'.$comp['style'].'">'.$template.'</div>';
          
      } else {
        //echo $secName;
        $componentCode .= '<div class="'.$comp['class'].'" id="comp-'.$comp['id'].'" style="'.$comp['style'].'">'.$comp['content'].'</div>';
      }        
    }    
    
    echo json_encode(array('components'=>auto_link($componentCode)));
    //echo $componentCode;
    //array_push ($this->SectionComponent,array('sectionName' => (string)trim($psec->name), 'sectionCode' => $componentCode));      
  }
  
  private function _loadSingleComponent($comp)
  {  
    $componentCode = '';
    $components = array();
    $libr = '';
    $css = '';
    $js = '';
    $pref['template'] = '';
    $pref['limitRequired'] = $this->data['limitRequired'];
    if ($comp['template'] == 1) {
      $params= array();
      $paramCount = 0;
      $template = '';
      $previousParam = '';          
      $params['callerPath'] = $this->thisPath;
      //echo $this->thisPath;
      // get the templates parameters
      //print_r($comp['files']);
      // pass params to the template and include other files
      foreach($comp['files'] as $files) {  
        //print_r($files);
          if ($files['is_external'] == 0) {
            if($files['type']== 1) {// template   view/templates/*.php
              $pref['template'] = $this->load->view($files['url'].$files['file_name'],null,true);
              $template = $pref['template'];
            } else if($files['type']== 2){ // link      css/*.css
              $css .= '<link rel="stylesheet" type="text/css" href="/css/'.$files['url'].$files['file_name'].'.css" media="all">'."\n"."\t";
            } else if($files['type']== 4){ // js      js/*.js
              $js .= '<script type="text/javascript" src="/js/'.$files['url'].$files['file_name'].'.js"></script>'."\n"."\t";  
            }
          } else {
            if($files['type']== 2){ // link      css/*.css
              $css .= '<link rel="stylesheet" type="text/css" href="/'.$files['url'].'" media="all">'."\n"."\t";
            } else if($files['type']== 4){ // js      js/*.js
              $js .= '<script type="text/javascript" src="/'.$files['url'].'"></script>'."\n"."\t";  
            }
          }
        }
        $ins = new $this->templates($pref);
        $template = $ins->generate($comp['param']);
                
        $componentCode .= '<div class="'.$comp['class'].'" id="comp-'.$comp['id'].'" style="'.$comp['style'].'">'.$template.'</div>';
        
    } else {
      //echo $secName;
      $componentCode .= '<div class="'.$comp['class'].'" id="comp-'.$comp['id'].'" style="'.$comp['style'].'">'.$comp['content'].'</div>';
    }        
    $this->data['description'] .= ' '.$comp['description'];
    $this->data['inHeadFileSources'] .= $css;
    $this->data['inBodyFileSources'] .= $js;
    return $this->templates->replaceImages($componentCode);
     //$componentCode;
  }
  
  private function _loadSectionComponents()
  {
    $components = array();
    $pageSection = $this->PageModel->getPageSections();
    //print_r($pageSection);
    foreach($pageSection as $psec) {
      $components = $this->ComponentModel->getComponent($psec->id);
      //print_r($components);
      foreach ($components as $comp) {
        if (!isset($this->data[(string)trim($psec->name)])) {
          $this->data[(string)trim($psec->name)] =  $this->_loadSingleComponent($comp);
        } else $this->data[(string)trim($psec->name)] .=  $this->_loadSingleComponent($comp);              
      }
      //echo $psec->id.' '.$psec->name.' '.$componentCode;
      //array_push ($this->'.trim($imageValue).',array('sectionName' => (string)trim($psec->name), 'sectionCode' => $componentCode));      
    }
  }
  
  public function _setNavigationMenu()
  {    
    $this->data['navigationMenu'] = $this->PageModel->getNavigationMenu();
  }
  
  public function _setInHeadFileSources()
  {
    if(isset($this->data['inHeadFileSources'])) {
      $this->data['inHeadFileSources'] .= $this->PageModel->getPageInHeadFileSources();      
    } else $this->data['inHeadFileSources'] = $this->PageModel->getPageInHeadFileSources();
    //echo $this->PageModel->getPageInHeadFileSources();
  }
  
  public function _setInBodyFileSources()
  {
    if(isset($this->data['inBodyFileSources'])) {
      $this->data['inBodyFileSources'] .= $this->PageModel->getPageInBodyFileSources();
    } else $this->data['inBodyFileSources'] = $this->PageModel->getPageInBodyFileSources();
  }
  
  public function _setPageHeading($pageHeading)
  {
    $this->data['pageHeading'] = $pageHeading;
  }
  
  public function _setBreadcrumbs($segs)
  {          
    $bcList = '';    
    if($this->uri->total_segments() == 0 || $segs == 0 ) {
      $bcList .= 'Home';
    } else {
      $bcList .= '<a href="/">Home</a> &raquo; ';
      $segUrl = '';
      for ($i=1;$i<=count($segs);$i++) {
        $segUrl .= $segs[$i].'/';
        $tok = strtok($segs[$i],"_");
        $segmentName = '';
        while ($tok !== false) {
          $segmentName .= ucfirst($tok).' ';
          $tok = strtok("_");
        }
        if($i==count($segs)) {
          $bcList .= '<span>'.trim($segmentName).'</span>';
        } else {
          $bcList .= '<a href="/'.$segUrl.'">'.trim($segmentName).'</a> &raquo; ';
          $this->data['parentSeg'] = trim($segmentName);
        }
      }
    }
    $this->data['breadcrumbs'] = trim($bcList);
  }
}