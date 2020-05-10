<?php 
class Rentapartment extends Controller
{
	public $postMessage;
	public function __construct()
	{
		if(!isset($_SESSION["bemyguest_user"]) || $_SESSION["bemyguest_user"]==""){
			require_once("app/functions/redirect.php");
			functions\redirect::url(Config::WEBSITE."ru/login");
		}

		$this->postMessage = "";
		if(isset($_POST["title"]) && !empty($_POST["title"]))
		{
			require_once("app/functions/addNewCatalogItemByUser.php");
			$addNewCatalogItemByUser = new functions\addNewCatalogItemByUser();
			$this->postMessage = $addNewCatalogItemByUser->index();
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

		$db_salestype = new Database("modules", array(
			"method"=>"selectModuleByType", 
			"type"=>"salestype"
		));

		$db_rooms = new Database("modules", array(
			"method"=>"selectModuleByType", 
			"type"=>"rooms"
		));

		$db_type = new Database("modules", array(
			"method"=>"selectModuleByType", 
			"type"=>"type"
		));

		$db_additionalinfo = new Database("modules", array(
			"method"=>"selectModuleByType", 
			"type"=>"additionalinfo",
			"order"=>"date",
			"by"=>"DESC",
			"from"=>0, 
			"num"=>100
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
		$this->view('profile/rent', [
			"header"=>array(
				"website"=>Config::WEBSITE,
				"public"=>Config::PUBLIC_FOLDER
			),
			"headerModule"=>$header->index(), 
			"headertop"=>$headertop->index(), 
			"editprofile"=>$editprofile->index(), 
			"editpassword"=>$editpassword->index(), 
			"pageData"=>$db_pagedata->getter(), 
			"salestype"=>$db_salestype->getter(), 
			"rooms"=>$db_rooms->getter(), 
			"type"=>$db_type->getter(), 
			"user"=>$db_user->getter(), 
			"postMessage"=>$this->postMessage, 
			"additionalinfo"=>$db_additionalinfo->getter(), 
			"footer"=>$footer->index() 
		]);
	}

}