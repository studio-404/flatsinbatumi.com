<?php 
class _top
{
	public  $data;

	public function index()
	{
		require_once("app/functions/l.php"); 
		require_once("app/functions/strip_output.php");


		$l = new functions\l();
		$out = "";
		// $currentUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		if(!isset($_SESSION["plane_flyed"])){
			$out .= "<img id=\"plane\" src=\"/public/img/plan.gif\" alt=\"plane\" class=\"flying-from-left-to-right fixed\">\n";
			$_SESSION["plane_flyed"] = "yeah";
		}
		$out .= "<nav class=\"navbar navbar-fixed-top\">\n";
		$out .= "<div class=\"container-fluid\">\n";
		$out .= "<div class=\"row\">\n";

		if(isset($_SESSION["bemyguest_user"])){
			$text = "Страница профиля";
			$url = "/".$_SESSION["LANG"]."/profile";
			$icon = "fa fa-user";
		}else{
			$text = "Сдать жильё"; 
			$url = "/".$_SESSION["LANG"]."/registration";
			$icon = "fa fa-building-o";
		}
		
		$out .= sprintf(
			"<a href=\"%s\" class=\"sellapartment\">\n",
			$url
		);
		$out .= sprintf(
			"%s", 
			$text
		);
		$out .= "</a>\n";
		
		$out .= sprintf(
			"<a href=\"%s%s/kantaqti\" class=\"btn btn-success pull-right\">\n",
			Config::WEBSITE,
			$_SESSION["LANG"]
		);
		
		$out .= "<table>\n";
		$out .= "<tr>\n";
		$out .= "<td>Контакт и&emsp;</td>\n";
		$out .= "<td><i class=\"fa fa-envelope-o\"></i></td>\n";
		$out .= "</tr>\n";
		$out .= "<tr>\n";
		$out .= "<td>бронирование&emsp;</td>\n";
		$out .= "<td><i class=\"fa fa-phone\"></i></td>\n";
		$out .= "</tr>\n";
		$out .= "</table>\n";

		$out .= "</a>\n";
		
		$out .= "</div>\n";
		$out .= "</div>\n";
		$out .= "</nav>\n";

		$out .= "<header>\n";
		$out .= sprintf(
			"<section class=\"logo-container\"><a href=\"%s\" class=\"logo\">Logo</a></section>\n",
			Config::WEBSITE
		);
		$out .= "<section class=\"title-container\">КВАРТИРЫ В БАТУМИ</section>\n";
		$out .= "<section class=\"sub-title-container\">Продажа и аренда недвижимости в Батуми</section>\n";
		$out .= "</header>\n";


		$out .= "<div id=\"g-messageBox\" class=\"modal\" tabindex=\"-1\" role=\"dialog\">";
  		$out .= "<div class=\"modal-dialog\" role=\"document\">\n";
    	$out .= "<div class=\"modal-content\">\n";
      	$out .= "<div class=\"modal-header\">\n";
        $out .= "<h5 class=\"modal-title\">Сообщение</h5>";
        $out .= "<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\" style=\"position:absolute; top:18px; right: 18px;\">";
        $out .= "<span aria-hidden=\"true\">&times;</span>";
        $out .= "</button>";
      	$out .= "</div>\n";
      	$out .= "<div class=\"modal-body\">\n";
        $out .= "<p id=\"g-messageBox-text\"></p>\n";
      	$out .= "</div>\n";
    	$out .= "</div>\n";
  		$out .= "</div>\n";
		$out .= "</div>\n";

		if(
			!isset($_SESSION["URL"][1]) || 
			($_SESSION["URL"][1]!="palezni-informacia" && 
			$_SESSION["URL"][1]!="otzivi" && 
			$_SESSION["URL"][1]!="registration" && 
			$_SESSION["URL"][1]!="login" && 
			$_SESSION["URL"][1]!="profile" && 
			$_SESSION["URL"][1]!="varianti" && 
			$_SESSION["URL"][1]!="rentapartment" && 
			$_SESSION["URL"][1]!="updateflat" && 
			$_SESSION["URL"][1]!="kantaqti")
		){
			$out .= "<div class=\"g-currency-box\">\n";
			$out .= "<h6>Сменить валюту</h6>\n";
			$out .= sprintf(
				"<p><a href=\"javascript:void(0)\" class=\"%s\" data-cur=\"gel\">GEL</a></p>\n",
				(!isset($_SESSION["currency"]) || $_SESSION["currency"]=="gel") ? "active" : ""
			);
			$out .= sprintf(
				"<p><a href=\"javascript:void(0)\" class=\"%s\" data-cur=\"usd\">USD</a></p>\n",
				(isset($_SESSION["currency"]) && $_SESSION["currency"]=="usd") ? "active" : ""
			);
			$out .= sprintf(
				"<p><a href=\"javascript:void(0)\" class=\"%s\" data-cur=\"rub\">RUB</a></p>\n",
				(isset($_SESSION["currency"]) && $_SESSION["currency"]=="rub") ? "active" : ""
			);
			$out .= "</div>\n";
		}
		
		
		return $out;
	}
}