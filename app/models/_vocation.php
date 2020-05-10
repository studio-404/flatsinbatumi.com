<?php 
class _vocation
{
	public $data;

	public function index()
	{
		$out = array();
		$out["list"] = "";
		$out["count"] = 0;
		
		$out["count"] = count($this->data);


		$out["list"] .= "<h4 class=\"leftside-title\">Отдых в Батуми, недорого!</h4>\n";
		$out["list"] .= "<ul class=\"list-group\">\n";
		$out["list"] .= "<li class=\"list-group-item text-justify\">\n";
		if($out["count"])
		{
			foreach($this->data as $value)
			{
				$out["list"] .= strip_tags($value['description'],"<p><a><strong>");
			}
		}
		$out["list"] .= "</li>\n";
		$out["list"] .= "</ul>\n";
		
		return $out;
	}
}
