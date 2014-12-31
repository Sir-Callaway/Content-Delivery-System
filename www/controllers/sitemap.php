<?php
/**
 * Sitemap Router Controller
 *
 * @author Muritu
 */
 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sitemap extends MY_Controller 
{

	public $data = array();
	
	public function index()
	{		
		$this->load->model('SitemapModel');		
		
		$this->SitemapModel->setSitemap(' ');
                $this->update();
		//$this->data['centerContent'] = $this->SitemapModel->getSitemapMenu();
		parent::index();
	}
	
	public function update() //createSiteMap()
	{
		$priority = 0.5;
		//create a new DOMDocument object
		$xml = new DOMDocument('1.0', 'UTF-8');
		// create urlset element
		$urlset = $xml->createElement("urlset");
		// create attribute node for urlset element
		$xmlns = $xml->createAttribute("xmlns");
		$urlset->appendChild($xmlns);
		// create attribute value node attribute node for urlset element
		$xmlnsValue = $xml->createTextNode("http://www.sitemaps.org/schemas/sitemap/0.9");
		$xmlns->appendChild($xmlnsValue);
		
		$xxsi = $xml->createAttribute("xmlns:xsi");
		$urlset->appendChild($xxsi);
		$xxsiValue = $xml->createTextNode("http://www.w3.org/2001/XMLSchema-instance");
		$xxsi->appendChild($xxsiValue);
		
		$xsi = $xml->createAttribute("xsi:schemaLocation");
		$urlset->appendChild($xsi);
		$xsiValue = $xml->createTextNode("http://www.sitemaps.org/schemas/sitemap/0.9");
		$xsi->appendChild($xsiValue);
		$xml->formatOutput=true;
		$xml->appendChild($urlset);
		$sm = $this->_setSiteMap();
		foreach($sm as $key =>$value) {
			if($key == "/") $priorityValue = 0.8; else $priorityValue = 0.6;
			// create child element
			$url = $xml->createElement("url");
			$urlset->appendChild($url);
			
			// create child element
			$loc = $xml->createElement("loc");
			$url->appendChild($loc);

			// create text node
			$text = $xml->createTextNode(base_url().$key);
			$loc->appendChild($text);
			
			// create child element
			$priority = $xml->createElement("priority");
			$url->appendChild($priority);

			// create text node
			$text = $xml->createTextNode($priorityValue);
			$priority->appendChild($text);
			
			foreach($value as $val) {
				if($val != $key) {
					$priorityValue = 0.4;
					// create child element
					$url = $xml->createElement("url");
					$urlset->appendChild($url);
					
					// create child element
					$loc = $xml->createElement("loc");
					$url->appendChild($loc);

					// create text node
					$text = $xml->createTextNode(base_url().$val);
					$loc->appendChild($text);
					
					// create child element
					$priority = $xml->createElement("priority");
					$url->appendChild($priority);

					// create text node
					$text = $xml->createTextNode($priorityValue);
					$priority->appendChild($text);
				}
			}
		}
		
		// display document in browser as plain text
		// for readability purposes

		// save and display tree
		$xml->save("sitemap.xml");
		//save("sitemap.xml");
	}
	private function _setSiteMap()
	{
		$sitemapData = $this->SitemapModel->getPages();
		$sitemapEvent = $this->SitemapModel->getEvents();
		$sitemapContent = $this->SitemapModel->getPagesContent();
		$sitemapOther = $this->SitemapModel->getOtherPages();
		$sitemapArranged = array();
		$sitemapTags = array();
		// remember that the query was for only events
		$key = 0;
		foreach($sitemapData as $dt) {					
			$sitemapArranged[$dt['url']][$key++] = $dt['url'].$dt['detail_url'];					
				
		}
		foreach($sitemapContent as $dt) {					
			$sitemapArranged[$dt['url']][$key++] = $dt['detail_url'];					
				
		}
		foreach($sitemapOther as $dt) {					
			$sitemapArranged[$dt['url']][$key++] = $dt['url'].$dt['detail_url'];					
				
		}
		$key = 0;
		foreach($sitemapEvent as $key=>$dt) {
			$sitemapArranged['calendar'][$key+1] = ''.$dt['detail_url'];
		}
		//print_r($sitemapData);
		//print_r($sitemapArranged);
		return $sitemapArranged;
	}
	
	private function _getSiteMap()
	{
		return '';
	}
	
}

/* End of file Sitemap.php */
/* Location: ./application/controllers/Sitemap.php */
?>