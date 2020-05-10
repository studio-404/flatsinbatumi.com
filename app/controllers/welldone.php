<?php 
class Welldone extends Controller
{
	public function __construct()
	{
		if(!isset($_SESSION["bemyguest_user"]) || $_SESSION["bemyguest_user"]==""){
			require_once("app/functions/redirect.php");
			functions\redirect::url(Config::WEBSITE."ru/login");
		}
	}

	public function index($name = '')
	{ 
		/* DATABASE */
		$db_langs = new Database("language", array(
			"method"=>"select"
		));

		$db_navigation = new Database("page", array(
			"method"=>"select", 
			"cid"=>0, 
			"nav_type"=>0,
			"lang"=>$_SESSION['LANG'],
			"status"=>0 
		));

		$s = (isset($_SESSION["URL"][1])) ? $_SESSION["URL"][1] : Config::MAIN_CLASS;
		$db_pagedata = new Database("page", array(
			"method"=>"selecteBySlug", 
			"slug"=>$s,
			"lang"=>$_SESSION['LANG'], 
			"all"=>true
		));	

		$db_user = new Database("user", array(
			"method"=>"select",
			"email"=>$_SESSION["bemyguest_user"]
		));	


		/* HEDARE */
		$header = $this->model('_header');
		$header->public = Config::PUBLIC_FOLDER; 
		$header->lang = $_SESSION["LANG"]; 
		$header->pagedata = $db_pagedata; 

		/* LANGUAGES */
		$languages = $this->model('_lang'); 
		$languages->langs = $db_langs->getter();

		/* NAVIGATION */
		$navigation = $this->model('_navigation');
		$navigation->data = $db_navigation->getter();

		/* editprofile */
		$editprofile = $this->model('_editprofile');
		$editprofile->data = $db_user->getter(); 

		/* editpassword */
		$editpassword = $this->model('_editpassword');

		/* header top */
		$headertop = $this->model('_top');
		$headertop->data["languagesModule"] = $languages->index();
		$headertop->data["navigationModule"] = $navigation->index();

		/*footer */
		$footer = $this->model('_footer');

		/* view */
		$this->view('weldone/index', [
			"header"=>array(
				"website"=>Config::WEBSITE,
				"public"=>Config::PUBLIC_FOLDER
			),
			"headerModule"=>$header->index(), 
			"headertop"=>$headertop->index(), 
			"editprofile"=>$editprofile->index(), 
			"editpassword"=>$editpassword->index(), 
			"pageData"=>$db_pagedata->getter(), 
			"footer"=>$footer->index() 
		]);
	}

}