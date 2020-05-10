<?php 
class Pogodapatumiplugin extends Controller
{
	public function __construct()
	{  
		
	}

	public function index($name = '')
	{
		require 'app/functions/request.php';
		$m = functions\request::index("GET", "m");

		$htmlContent = Config::CACHE."monthweather_".str_replace(array("-"," "), "", implode("_",$_SESSION['URL'])).$m.".html";
		
		if(file_exists($htmlContent)){
			include($htmlContent);
		}else{
			require_once('app/_plugins/simplehtmldom/simple_html_dom.php');
			if($m && !empty($m)){
				$html = file_get_html('https://yandex.com.ge/weather/batumi/month/'.$m.'?ncrnd=3617&via=cnav');
			}else{
				$html = file_get_html('https://yandex.com.ge/weather/batumi/month?via=cnav');
			}

			foreach($html->find('meta[name="viewport"]') as $meta) {
			    $meta->content = "width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no";
			}

			ob_start();
			echo "<html>";
			foreach($html->find('head') as $head) {
			    echo $head;
			}
			echo "<body>";
			echo "<style>";
			echo "body, html{ margin: 0; padding: 0 3px; }";
			echo ".climate-calendar{ margin:0; padding:0; max-width:100%; width:100%; }";
			echo ".months{ margin:0; padding:0; width:100%; height:36px; background-color:#f0f0f0; border-radius: 25px; overflow:hidden; }";
			
			echo ".climate-calendar-day{ margin:10px 5px !important; }";
			echo ".climate-calendar__cell{ width:auto !important; flex-basis:auto !important; min-width:103px; }";
			echo ".icon_size_48{ width:30px; height:30px; }";
			echo ".month{ margin:0; padding:0 12px; float:left; line-height:36px; }";
			echo ".active{ background-color:#fd5; }";
			echo ".month a{ color:#555; text-decoration:none; font-family: roboto; font-size:11px; }";
			echo ".climate-calendar-day__detailed-container-wide{ width:250% !important; }";
			echo ".climate-calendar-day__temp-day{ font-size:14px; }";
			echo ".climate-calendar-day__temp-night{ font-size:12px; }";
			echo ".months-select{ margin:0; padding: 0 5px; width:100%; line-height:30px; height:30px; display:none; box-shadow: inset 0 1px 1px rgba(0,0,0,.1); background-color: rgba(18,115,185,.7)!important; color:white; }";
			
			echo "@media (max-width:420px){";
			echo ".months{ display:none }";
			echo ".months-select{ display:block }";
			echo ".climate-calendar__row{ display:block; }";
			echo ".climate-calendar__row_header{ display:none; }";
			echo ".climate-calendar__cell{ min-width:100%; width:100%; }";
			echo ".climate-calendar-day__row{ display:block; text-align: center; }";
			echo ".icon_size_48{ display:inline-block; width:48px; height: 48px; }";
			echo ".climate-calendar-day__temp{ display:inline-block; }";
			echo ".climate-calendar-day_current_day .climate-calendar-day__day{ margin:0 auto; }";
			echo ".climate-calendar__cell{ border-bottom: solid 1px #cccccc; }";
			echo ".climate-calendar-day__detailed-container-wide{ width:100% !important; }";
			echo ".climate-calendar-day__detailed-container-wide{ margin-left: 0px !important; }";
			echo "}";
			
			echo "</style>";
			echo "<select class=\"months-select\">";
			echo "<option value=\"?m=\" ".(($m=="") ? 'selected="selected"' : '').">30 дней</option>";
			echo "<option value=\"?m=january\" ".(($m=="january") ? 'selected="selected"' : '').">Янв.</option>";
			echo "<option value=\"?m=february\" ".(($m=="february") ? 'selected="selected"' : '').">Февр.</option>";
			echo "<option value=\"?m=march\" ".(($m=="march") ? 'selected="selected"' : '').">Март</option>";
			echo "<option value=\"?m=april\" ".(($m=="april") ? 'selected="selected"' : '').">Апр.</option>";
			echo "<option value=\"?m=may\" ".(($m=="may") ? 'selected="selected"' : '').">Май.</option>";
			echo "<option value=\"?m=june\" ".(($m=="june") ? 'selected="selected"' : '').">Июнь</option>";
			echo "<option value=\"?m=july\" ".(($m=="july") ? 'selected="selected"' : '').">Июль</option>";
			echo "<option value=\"?m=august\" ".(($m=="august") ? 'selected="selected"' : '').">Авг.</option>";
			echo "<option value=\"?m=september\" ".(($m=="september") ? 'selected="selected"' : '').">Сент.</option>";
			echo "<option value=\"?m=october\" ".(($m=="october") ? 'selected="selected"' : '').">Октя.</option>";
			echo "<option value=\"?m=november\" ".(($m=="november") ? 'selected="selected"' : '').">Нояб.</option>";
			echo "<option value=\"?m=december\" ".(($m=="december") ? 'selected="selected"' : '').">Дека.</option>";
			echo "</select>";

			echo "<div class=\"months\">";
			
			echo "<div class=\"month".((!isset($_GET["m"]) || empty($_GET["m"])) ? " active" : "")."\">";
			echo "<a href=\"?m=\">30 дней</a>";
			echo "</div>";

			echo "<div class=\"month".((isset($_GET["m"]) && $_GET["m"]=="january") ? " active" : "")."\">";
			echo "<a href=\"?m=january\">Янв.</a>";
			echo "</div>";

			echo "<div class=\"month".((isset($_GET["m"]) && $_GET["m"]=="february") ? " active" : "")."\">";
			echo "<a href=\"?m=february\">Февр.</a>";
			echo "</div>";
			
			echo "<div class=\"month".((isset($_GET["m"]) && $_GET["m"]=="march") ? " active" : "")."\">";
			echo "<a href=\"?m=march\">Март</a>";
			echo "</div>";

			echo "<div class=\"month".((isset($_GET["m"]) && $_GET["m"]=="april") ? " active" : "")."\">";
			echo "<a href=\"?m=april\">Апр.</a>";
			echo "</div>";

			echo "<div class=\"month".((isset($_GET["m"]) && $_GET["m"]=="may") ? " active" : "")."\">";
			echo "<a href=\"?m=may\">Май</a>";
			echo "</div>";

			echo "<div class=\"month".((isset($_GET["m"]) && $_GET["m"]=="june") ? " active" : "")."\">";
			echo "<a href=\"?m=june\">Июнь</a>";
			echo "</div>";

			echo "<div class=\"month".((isset($_GET["m"]) && $_GET["m"]=="july") ? " active" : "")."\">";
			echo "<a href=\"?m=july\">Июль</a>";
			echo "</div>";

			echo "<div class=\"month".((isset($_GET["m"]) && $_GET["m"]=="august") ? " active" : "")."\">";
			echo "<a href=\"?m=august\">Авг.</a>";
			echo "</div>";

			echo "<div class=\"month".((isset($_GET["m"]) && $_GET["m"]=="september") ? " active" : "")."\">";
			echo "<a href=\"?m=september\">Сент.</a>";
			echo "</div>";

			echo "<div class=\"month".((isset($_GET["m"]) && $_GET["m"]=="october") ? " active" : "")."\">";
			echo "<a href=\"?m=october\">Октя.</a>";
			echo "</div>";

			echo "<div class=\"month".((isset($_GET["m"]) && $_GET["m"]=="november") ? " active" : "")."\">";
			echo "<a href=\"?m=november\">Нояб.</a>";
			echo "</div>";

			echo "<div class=\"month".((isset($_GET["m"]) && $_GET["m"]=="december") ? " active" : "")."\">";
			echo "<a href=\"?m=december\">Дека.</a>";
			echo "</div>";

			echo "</div>";
			foreach($html->find('.climate-calendar') as $content) {
			    echo $content;
			}
			echo "<script>";
			echo "document.getElementsByClassName(\"months-select\")[0].addEventListener('change', function(e){
				location.href = e.target.value;
			})";
			echo "</script>";
			echo "</body>";
			echo "</html>";

			$results = ob_get_contents();
			ob_clean();

			$fh = @fopen($htmlContent, 'w') or die("Error opening output file");
			@fwrite($fh, $results);
			@fclose($fh);

			echo $results;
		}
	}
}
?>