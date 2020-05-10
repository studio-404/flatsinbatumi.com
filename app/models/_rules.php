<?php 
class _rules
{
	public $data;

	public function index()
	{
		$out = array();
		$out["list"] = "";
		$out["count"] = 0;
		
		$out["count"] = count($this->data);


		// $out["list"] .= "<h4 class=\"leftside-title\">Правила бронирования квартир:</h4>\n";
		$out["list"] .= "<ul class=\"list-group\" style=\"margin-top:40px;\">\n";

		if($out["count"])
		{
			foreach($this->data as $value)
			{
				$out["list"] .= "<li class=\"list-group-item\">\n";
				$out["list"] .= "<i class=\"fa fa-check\"></i>&emsp;\n";
				$out["list"] .= strip_tags($value['title'])."\n";
				$out["list"] .= "</li>\n";
			}
		}
		$out["list"] .= "</ul>\n";
		
		return $out;
	}
}