<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class ComponentModel extends CI_Model
{
	function __construct()
	{
		parent::__construct();	
		$this->CI =& get_instance();
	}
	/* 
	 * sets then gets the components found in the section being considered
	 */
	function getComponent($scID)
	{
		$this->Component = array();
		$this->ComponentDetails = array();
		$this->Files = array();
		$this->ParamsEx = '';
		$this->setComponentDetails($scID);
		//print_r($this->ComponentDetails);
		//print_r($scID);
		foreach ($this->ComponentDetails as $compDetail) {
			//print_r($compDetail['component_id']);
			array_push($this->Component, $this->setComponentContent($compDetail['component_id']));
		}
		//print_r($this->Component);
		return $this->Component;
	}
	
	function getSpecificComponent($componentID)
	{
		$this->Component = array();
		$this->ComponentDetails = array();
		$this->Files = array();
		$this->ParamsEx = '';
		return $this->setComponentContent($componentID);		
	}
	
	function getExternalComponent($pageID, $start, $limit)
	{
		$this->Component = array();
		$this->ComponentDetails = array();
		$this->Files = array();
		$this->ParamsEx = '';
		$this->setExternalComponentDetails($pageID, $start, $limit);
		foreach ($this->ComponentDetails as $compDetail) {
			array_push($this->Component, $this->setComponentContent($compDetail['component_id']));
		}
		return $this->Component;
	}
	/* 
	 * sets the id(s) of the components found in the section being considered
	 */
	function setComponentDetails($scID)
	{
		$this->db->select('position, component_id');
		$this->db->from('section_component');
		$this->db->where('id',$scID);
		$this->db->where('is_active',1);
		$this->db->order_by('position','ASC');
		$componentDetails = $this->db->get();
		// checks if there are any records to be retrieved from the DB		
		if ($componentDetails->num_rows() > 0) {
			$this->ComponentDetails = $componentDetails->result_array();
			//print_r($this->ComponentDetails);
			$componentDetails->free_result();	
		} else {
			//echo 'cant find the requested section!';
			$this->ComponentDetails = array();
		}				
	}
	function setExternalComponentDetails($pageID, $start, $limit)
	{
		$this->db->select('position, component_id');
		$this->db->from('section_component');
		$this->db->where('page_id',$pageID);
		$this->db->where('section_id',1);
		$this->db->where('is_active',1);
		$this->db->where('position > ',$start);		
		$this->db->order_by('position','ASC');
		$this->db->limit($limit);
		$componentDetails = $this->db->get();
		// checks if there are any records to be retrieved from the DB		
		if ($componentDetails->num_rows() > 0) {
			$this->ComponentDetails = $componentDetails->result_array();
			//print_r($this->ComponentDetails);
			$componentDetails->free_result();	
		} else {
			//echo 'cant find the requested section!';
			$this->ComponentDetails = array();
		}				
	}
	function getComponentID($title,$date)
	{
		$title = str_replace('_',' ',$title);
		$date = str_replace('_',' ',$date);
		
		$this->db->select('c.id');
		$this->db->from('component as c');
		$this->db->like('c.description',$title);
		$this->db->or_like('c.content',$date);
		//$this->db->or_like('c.content',$title);	
		$this->db->limit(1);
		$dates = $this->db->get();
		// checks if there are any records to be retrieved from the DB		
		if ($dates->num_rows() > 0) {
			$details = $dates->row();
			$dates->free_result();
			return $details->id;
		} else {
			//echo 'cant find the requested section!';
		}		
	}
	
	function getScheduledComponentID($title,$date)
	{
		$url = "calendar/".$title."/".$date;
		$dat = str_replace('_',' ',$date);
		
		$this->db->select('c.id');
		$this->db->from('component as c');
		$this->db->join('scheduler as s','s.description = c.id','INNER');
		$this->db->where('s.date',$dat);
		$this->db->where('c.detail_url',$url);	
		$this->db->limit(1);
		$dates = $this->db->get();
		// checks if there are any records to be retrieved from the DB		
		if ($dates->num_rows() > 0) {
			$details = $dates->row();
			$dates->free_result();
			return $details->id;
		} else {
			//echo 'cant find the requested section!';
		}		
	}
	/* 
	 * sets the contents of the component
	 */
	function setComponentContent($componentID)
	{
		$details = '';
		$this->db->select('c.class, c.style, c.template_id, c.content, c.description');
		$this->db->from('component as c');
		$this->db->where('c.id',$componentID);
		$this->db->limit(1);
		$component = $this->db->get();
		if ($component->num_rows() > 0 && $component->row()->template_id != 0) {
			$component->free_result();
			$this->db->select('c.class, c.style, c.template_id, t.param_settings, c.content, c.description');
			$this->db->from('component as c');
			$this->db->join('template as t','t.id = c.template_id','INNER');
			$this->db->where('c.id',$componentID);
			$this->db->limit(1);			
			$component = $this->db->get();
		}
		// checks if there are any records to be retrieved from the DB		
		if ($component->num_rows() > 0) {
			if ($component->row()->template_id != 0) {
				$parameter = array();
				$parameter['paramSettings'] = json_decode($component->row()->param_settings, true);
				$parameter['paramValues'] = json_decode($component->row()->content, true);
				//print_r($parameter);
				/*$params = explode('^', $component->row()->content); 
				foreach ($params as $param) { 
					$singleParam = explode('~', $param);
					$parameter[$singleParam[0]] = $singleParam[1];
					$singleParam = array();
				}
				//print_r($parameter);*/
				$this->getTemplateFiles(trim($component->row()->template_id));
				//print_r($this->Files);
				$details = array(
								'id' => $componentID,
								'template' => 1,
								'class'=>$component->row()->class,
								'description'=>$component->row()->description,
								'style'=>$component->row()->style,
								'files'=>$this->Files,						
								'param'=>$parameter
							);		
					//print_r($details);echo '<br />';
			} else {
				$details = array(
								'id' => $componentID,
								'template' => 0,
								'class'=>$component->row()->class,
								'description'=>$component->row()->description,
								'style'=>$component->row()->style,
								'content'=>json_decode($component->row()->content)
							);	
			}	
			$component->free_result();	
			
			return $details;
		}
	}
	/* 
	 * gets the templates file details of the component
	 */
	function getTemplateFiles($templateID)
	{
		$this->Files = array();
		/*SELECT p.template_value_exception, fs.file_name, fs.url, fs.type FROM file_source as fs INNER JOIN template_file_source as pfs ON pfs.file_source_id = fs.id INNER JOIN template as p ON p.id = pfs.template_id WHERE p.id=4*/
		$this->db->select('p.template_value_exception, fs.file_name, fs.url, fs.type, fs.is_external');
		$this->db->from('file_source as fs');
		$this->db->join('template_file_source as pfs','pfs.file_source_id = fs.id','INNER');
		$this->db->join('template as p','p.id = pfs.template_id','INNER');		
		$this->db->where('p.id',$templateID);
		$templateDetails = $this->db->get();
		// checks if there are any records to be retrieved from the DB		
		if ($templateDetails->num_rows() > 0) {
			$files = array();
			array_push($files,$templateDetails->result_array());
			$this->Files += $files[0];
			//print_r($this->Files);echo '<br>';
			$this->ParamsEx = trim($this->Files[0]['template_value_exception']);			
			$templateDetails->free_result();
			
		} else {
			//echo 'cant find the requested section!';
			//return array();
		}			
	}	
	
	/* 
	 * gets the templates file details of the component
	 */
	function getTemplateParams($componentID,$templateID)
	{
		$this->db->select('pp.param, cpp.value');
		$this->db->from('template_param as pp');
		$this->db->join('component_template_params as cpp','cpp.template_param_id = pp.id','INNER');
		$this->db->where('cpp.component_id',$componentID);
		$this->db->where('pp.template_id',$templateID);
		$templateDetails = $this->db->get();
		// checks if there are any records to be retrieved from the DB		
		if ($templateDetails->num_rows() > 0) {
			//echo 'done';
			$details = $templateDetails->result_array();
			array_push($details, array('param'=>'componentID','value'=>$componentID));
			//print_r($details);
			$templateDetails->free_result();
			return $details;
		} else {
			//echo 'cant find the requested template parameters!';
		}			
	}
	function initializePageSectionID()
	{
		$this->PageSectionID = 0;
	}
	function initializeComponentDetails()
	{
		$this->ComponentDetails = array();
	}
	var $PageSectionID;
	var $CI;
	var $Files;
	var $ParamsEx;
	var $ComponentDetails;
	var $Component;
}