<?php 
class _footer
{
	public $data;

	public function index()
	{
		require_once("app/functions/l.php");
		require_once("app/functions/strip_output.php");  
		require_once("app/functions/string.php");  
		$l = new functions\l(); 		
		// $currentUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

		$out = sprintf(
			"<a href=\"/%s/varianti\" class=\"send-me-variants-title\">",
			$_SESSION["LANG"]
		);
		$out .= "<span class=\"desktop\">Пришлите мне варианты!&emsp;<i class=\"fa fa-paper-plane-o\"></i></span>";
		$out .= "<span class=\"mobile\">Варианты&emsp;<i class=\"fa fa-paper-plane-o\"></i></span>";
		$out .= "<div class=\"send-me-variants-close\"><i class=\"fa fa-times pull-right\"></i></div>";
		$out .= "<p>";
		$out .= "НАЖМИТЕ НА МЕНЯ, И Я ВАМ ПРИШЛЮ ЛУЧШИЕ ВАРИАНТЫ В ТЕЧЕНИИ<br>45 МИН.";
		$out .= "</p>";
		$out .= "</a>";
		
		$out .= "<button id=\"playMusic\" onclick=\"playMusic()\" style=\"position:absolute; visibility:hidden\">play</button>";
		$out .= "<audio id=\"audio\" style=\"height: 0;\">";
		$out .= "<source src=\"/public/audio/animal_stick.mp3\">";
		$out .= "<source src=\"/public/audio/animal_stick.ogg\">";
		$out .= "</audio>";

		$out .= "</body>";
		$out .= "</html>";
		
		return $out;
	}
}