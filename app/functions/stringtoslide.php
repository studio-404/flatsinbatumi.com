<?php 
namespace functions;

class stringtoslide
{
	private $out;

	public function __construct()
	{

	}

	public function index($string)
	{
		preg_match_all('/<p>\[sliderinfo=\d+\]<\/p>/', $string, $matches, PREG_OFFSET_CAPTURE);
		
		$finish = array();
		foreach ($matches[0] as $value):
			$strip = strip_tags($value[0]); 
			$ex = explode("sliderinfo=", $value[0]);
			$moduleId = (int)$ex[1];
			
			$Database = new \Database("photos", array(
				"method"=>"selectByParent", 
				"idx"=>$moduleId, 
				"lang"=>$_SESSION["LANG"],
				"type"=>"sliderinfo"
			));
			$getter = $Database->getter(); 
			$finish[$strip] = $this->photo($getter);
		endforeach;

		foreach ($finish as $key => $value) {
			$string = str_replace($key, $value, $string);
		}
		
		return $string;
	}

	private function photo($array){
		$this->out = "<section class=\"my-gallery text-center\">\n";
		$this->out .= "<section class=\"row\">\n";
		$this->out .= "<section class=\"col-sm-12\">\n";
		$this->out .= "<div class=\"gallery\">\n";
		
		$x = 1;
		foreach ($array as $value):
		$bigSmall = ($x==1) ? "informationBigImage" : "informationSmallImage";
		$dementions = explode("x", $value["size"]);
		$defaultWidth = $dementions[0];
		$defaultHeight = $dementions[1];
		$width = ($x==1) ? 718 : 100;
		$height = ($x==1) ? 381 : 100;
		$fullPath = \Config::WEBSITE_.$value["path"];

		$this->out .= sprintf(
			"<a href=\"%s%s/image/loadimage?f=%s&w=%s&h=%s\" data-size=\"%s\" class=\"gallery-image-link\" style=\"display: inline;\">\n", 
			\Config::WEBSITE,
			$_SESSION["LANG"],  
			base64_encode($fullPath), 
			base64_encode($defaultWidth), 
			base64_encode($defaultHeight), 
			$value["size"]
		);

		$this->out .= sprintf(
			"<img src=\"%s%s/image/loadimage?f=%s&w=%s&h=%s\" alt=\"\" class=\"fa loading img-thumbnail %s\" />\n", 
			\Config::WEBSITE,
			$_SESSION["LANG"],  
			base64_encode($fullPath), 
			base64_encode($width), 
			base64_encode($height), 
			$bigSmall
		);
		$this->out .= "</a>\n";
		$x = 2;
		endforeach;

		$this->out .= "</div>\n";
		$this->out .= "</section>\n";
		$this->out .= "</section>\n";
		$this->out .= "</section>\n";

		return $this->out;
	}
}