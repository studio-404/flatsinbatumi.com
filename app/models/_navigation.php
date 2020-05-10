<?php 
class _navigation
{
	public $data;

	public function index(){
		require_once("app/functions/strip_output.php");

		$out = sprintf(
			"<a href=\"/%s/palezni-informacia\" class=\"polezni animation1\" style=\"margin:5px 0 0 0; line-height: 30px\">Советы туристам<br>приезжаюшим в батуми</a>\n",
			$_SESSION["LANG"]
		);
		// $out .= sprintf(
		// 	"<a href=\"/%s/uslugi\" class=\"uslugi animation2\">Услуги</a>\n",
		// 	$_SESSION["LANG"]
		// );

		// $out .= "<a href=\"http://zectour.ge\" target=\"_blank\" class=\"zectour animation1\" style=\"margin:5px 0 0 0\">Экскурсии, трансферы, домашний вино и чача</a>\n";

		$out .= "<a href=\"http://zectour.com\" target=\"_blank\" class=\"uslugi animation2\" style=\"margin:5px 0 0 0; line-height: 30px\">Екскурсии, трансферы, грузинский домашний вино и чача</a>\n";

		$out .= sprintf(
			"<a href=\"/%s/pagodabatumi\" class=\"polezni animation1\" style=\"margin:3px 0 0 0; line-height: 40px\">Погода в Батуми</a>\n",
			$_SESSION["LANG"]
		);
		
		$out .= sprintf(
			"<a href=\"/%s/otzivi\" class=\"otzivi\" style=\"margin:3px 0 0 0\">Отзывы туристов</a>\n",
			$_SESSION["LANG"]
		);		
		
		return $out;
	}
}