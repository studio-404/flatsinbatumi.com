<?php 
class _slider
{
	public $data;

	public function index()
	{
		require_once("app/functions/strip_output.php"); 

		$out = array();
		$out["list"] = "";
		$out["count"] = 0;
		
		$out["count"] = count($this->data);

		$out["list"] .= "<section id=\"carousel-main\" data-ride=\"carousel\" class=\"carousel slide\">\n";
		
		$out["list"] .= "<section role=\"listbox\" class=\"carousel-inner\">\n";
		if($out["count"])
		{
			$x = 1;
			foreach($this->data as $value)
			{
				$photos = new Database("photos",array(
					"method"=>"selectByParent", 
					"idx"=>(int)$value['idx'],  
					"lang"=>strip_output::index($value['lang']),  
					"type"=>strip_output::index($value['type'])
				));
				if($photos->getter()){
					$pic = $photos->getter();
					$image = strip_output::index($pic[0]['path']);
				}else{
					$image = "/public/filemanager/noimage.png";
				}	
				$active = ($x==1) ? " active" : "";

				$out["list"] .= sprintf(
					"<section class=\"item%s\">\n",
					$active
				);
				$out["list"] .= sprintf(
					"<img src=\"%s%s/image/loadimage?f=%s&w=%s&h=%s\" alt=\"auto-transfer\" class=\"img-responsive\" />\n",
					Config::WEBSITE,
					$_SESSION["LANG"],
					base64_encode(Config::WEBSITE_.$image), 
					base64_encode("360"),
					base64_encode("133")
				);
				$out["list"] .= "<section class=\"carousel-caption\">\n";
				$out["list"] .= sprintf(
					"<h4>%s</h4>\n", 
					$value['title']
				);
				$out["list"] .= "</section>\n";		
				$out["list"] .= "</section>\n";


				$x = 2;
			}
		}
		$out["list"] .= "</section>\n";
		
		if($out["count"]>=2):	
		$out["list"] .= "<a href=\"#carousel-main\" role=\"button\" data-slide=\"prev\" class=\"left carousel-control\">\n";
		$out["list"] .= "<span class=\"control left-control\" style=\"left:0\">\n";
		$out["list"] .= "<i class=\"fa fa-chevron-left\"></i>\n";
		$out["list"] .= "</span>\n";
		$out["list"] .= "<span class=\"sr-only\">Previous</span>\n";
		$out["list"] .= "</a>\n";

		$out["list"] .= "<a href=\"#carousel-main\" role=\"button\" data-slide=\"next\" class=\"right carousel-control\">\n";
		$out["list"] .= "<span class=\"control rigth-control\" style=\"right:0\">\n";
		$out["list"] .= "<i class=\"fa fa-chevron-right\"></i>\n";
		$out["list"] .= "</span>\n";
		$out["list"] .= "<span class=\"sr-only\">Next</span>\n";
		$out["list"] .= "</a>\n";
		endif;

		$out["list"] .= "</section>\n";
		
		return $out;
	}
}