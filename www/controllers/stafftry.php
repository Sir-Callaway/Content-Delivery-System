<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Home Router Controller
 *
 * @author Muritu
 */
 
class Stafftry extends MY_Controller 
{
	public $data = array();
	
	public function index()
	{	
		$this->load->model('staffmodel');
		$this->data['centerContent'] = '<div class="content component"><div id="slider">
        <ul id="sliderContent">
            <li class="sliderImage">
                <a href=""><img src="/images/gallery/requiem_von_mozart_vijay/IMG_1125.JPG" alt="1" /></a>
                <span class="top"><strong>Mozart Requiem</strong><br />...</span>
            </li>
            <li class="sliderImage">
                <a href=""><img src="/images/gallery/requiem_von_mozart_vijay/L1030319.jpg" alt="2" /></a>
                <span class="top"><strong>Mozart Requiem</strong><br />...</span>
            </li>
            <li class="sliderImage">
                <a href=""><img src="/images/gallery/requiem_von_mozart_vijay/IMG_1159.JPG" alt="3" /></a>
                <span class="top"><strong>Mozart Requiem</strong><br />...</span>
            </li>
            <li class="sliderImage">
                <a href=""><img src="/images/gallery/requiem_von_mozart_vijay/IMG_1135.JPG" alt="4" /></a>
                <span class="top"><strong>Mozart Requiem</strong><br />...</span>
            </li>
            <li class="sliderImage">
                <a href=""><img src="/images/gallery/requiem_von_mozart_vijay/L1030263.jpg" alt="5" /></a>
                <span class="top"><strong>Mozart Requiem</strong><br />...</span>
            </li>
            <li class="sliderImage">
                <a href=""><img src="/images/gallery/requiem_von_mozart_vijay/L1030303.jpg" alt="6" /></a>
                <span class="top"><strong>Mozart Requiem</strong><br />...</span>
            </li>
            <li class="sliderImage">
                <a href=""><img src="/images/gallery/requiem_von_mozart_vijay/L1030283.jpg" alt="7" /></a>
                <span class="top"><strong>Mozart Requiem</strong><br />...</span>
            </li>
            <li class="sliderImage">
                <a href=""><img src="/images/gallery/requiem_von_mozart_vijay/L1030262.jpg" alt="8" /></a>
                <span class="top"><strong>Mozart Requiem</strong><br />...</span>
            </li>
            <li class="sliderImage">
                <a href=""><img src="/images/gallery/requiem_von_mozart_vijay/L1030244.jpg" alt="9" /></a>
                <span class="top"><strong>Mozart Requiem</strong><br />...</span>
            </li>
            <li class="sliderImage">
                <a href=""><img src="/images/gallery/requiem_von_mozart_vijay/IMG_1131.JPG" alt="10" /></a>
                <span class="top"><strong>Mozart Requiem</strong><br />...</span>
            </li>
            <li class="sliderImage">
                <a href=""><img src="/images/gallery/requiem_von_mozart_vijay/IMG_1119.JPG" alt="11" /></a>
                <span class="top"><strong>Mozart Requiem</strong><br />...</span>
            </li>
            <div class="clear sliderImage"></div>
        </ul>
    </div></div>';
		
        parent::index();
	}
}