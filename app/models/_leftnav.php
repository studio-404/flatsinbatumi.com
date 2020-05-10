<?php 
class _leftnav
{
	public $data;
	public $navigation;
	public $slider;
	public $whyus;
	public $video;
	public $rules;
	public $social;
	public $contactinfo;
	public $excursion;
	public $vocation;
	public $cols = 4;

	public function index()
	{
		$out = sprintf(
			"<section class=\"col-md-%d left\">\n",
			$this->cols
		);
		$out .= $this->navigation;

		$out .= $this->slider["list"];

		$out .= "<section class=\"batumi-info\">\n";

		$out .= $this->whyus["list"];

		/* sights seeings Start */
		$out .= "<h4 class=\"leftside-title\">Достопримечательности Батуми:</h4>\n";
		$out .= "<section class=\"youtube-video\">\n";
		if(isset($this->video[0]["description"])){
			$out .= strip_tags($this->video[0]["description"], "<iframe>");
		}
		$out .= "</section>\n";
		/* sights seeings End */

		/* Rules booking start */
		// $out .= $this->rules["list"];
		// $out .= "--------------";
		/* Rules booking end */

		/* Join now Start */
		$out .= $this->social["list"];
		/* Join now End */

		/* Contact me start */
		// $out .= $this->contactinfo["list"];
		/* Contact me end */

		/* Excursion start */
		// $out .= $this->excursion["list"];
		/* Excursion end */

		/* Recreation start */
		// $out .= $this->vocation["list"];
		

		$out .= "</section>\n";
		$out .= "</section>\n";
		
		return $out;	
	}
}