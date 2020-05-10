<?php 
class Profile extends Controller
{
	
	public function __construct()
	{
		require_once("app/functions/redirect.php");
		require_once("app/functions/request.php");
		if(!isset($_SESSION["bemyguest_user"]) || $_SESSION["bemyguest_user"]==""){
			functions\redirect::url(Config::WEBSITE."ru/login");
		}

		
		if(functions\request::index("GET","remove")){// remove product
			$user = new Database("user",array(
				"method"=>"select",
				"email"=>$_SESSION["bemyguest_user"]
			));
			$fetch = $user->getter();

			$remove_product = new Database("products", array(
				"method"=>"remove_from_user",
				"val"=>(int)functions\request::index("GET","remove"),
				"user"=>(int)$fetch['id']
			));
			functions\redirect::url(Config::WEBSITE."ru/profile");
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

		$db_user= new Database("user", array(
			"method"=>"select",
			"email"=>$_SESSION["bemyguest_user"]
		));	

		$db_usersProducts = new Database("products", array(
			"method"=>"usersProducts",
			"lang"=>$_SESSION["LANG"]
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

		if(
			!isset($_SESSION["_GEL_"]) || 
			!isset($_SESSION["_RUB_"]) || 
			!isset($_SESSION["_USD_"])  
		){
			$currencymod = new Database('currencymod', array(
				"method"=>"select"
			));
			$fetchCur = $currencymod->getter();
			$_SESSION["_GEL_"] = 1;
			$_SESSION["_RUB_"] = $fetchCur[0]["value"] / 100;
			$_SESSION["_USD_"] = $fetchCur[1]["value"] ;
		}

		/* view */
		$this->view('profile/index', [
			"header"=>array(
				"website"=>Config::WEBSITE,
				"public"=>Config::PUBLIC_FOLDER
			),
			"headerModule"=>$header->index(), 
			"headertop"=>$headertop->index(), 
			"editprofile"=>$editprofile->index(), 
			"editpassword"=>$editpassword->index(), 
			"pageData"=>$db_pagedata->getter(), 
			"user"=>$db_user->getter(), 
			"usersProducts"=>$db_usersProducts->getter(), 
			"footer"=>$footer->index() 
		]);
	}

}