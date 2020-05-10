<?php 
class _contactinfo
{
	public $data;

	public function index()
	{
		$out = array();
		$out["list"] = "";
		$out["count"] = 0;
		
		$out["count"] = count($this->data);


		$out["list"] .= "<h4 class=\"leftside-title\">Связаться с мной:</h4>\n";
		$out["list"] .= "<ul class=\"list-group\">\n";

		if($out["count"])
		{
			foreach($this->data as $value)
			{
				$out["list"] .= "<li class=\"list-group-item\">\n";
				$out["list"] .= sprintf(
					"<i class=\"%s\"></i>&emsp; %s\n", 
					htmlentities($value['classname']), 
					strip_tags($value['title'])
				);
				$out["list"] .= "</li>\n";
			}
		}
		$out["list"] .= "</ul>\n";
		
		return $out;
	}
}
