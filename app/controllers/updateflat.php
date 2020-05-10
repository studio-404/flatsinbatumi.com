<?php 
class Updateflat extends Controller
{
	public $postMessage;
	public $product;
	public $photos;
	public function __construct()
	{
		require_once("app/functions/redirect.php");
		require_once("app/functions/request.php");

		/* if not signed user go to login page */
		if(!isset($_SESSION["bemyguest_user"]) || $_SESSION["bemyguest_user"]==""){
			functions\redirect::url(Config::WEBSITE."ru/login");
		}

		/* if not isset product id go to login page */
		if(!functions\request::index("GET","id")){
			functions\redirect::url(Config::WEBSITE."ru/login");
		}else{
			$user = new Database("user",array(
				"method"=>"select",
				"email"=>$_SESSION["bemyguest_user"]
			));
			$fetch = $user->getter();

			$selectProduct = new Database("products", array(
				"method"=>"selectById",
				"idx"=>(int)functions\request::index("GET","id"),
				"insert_admin"=>$fetch['id'],
				"lang"=>$_SESSION["LANG"]
			));

			if($selectProduct->getter()){
				$this->product = $selectProduct->getter();
				$photos = new Database("photos", array(
					"method"=>"selectByParent",
					"idx"=>(int)functions\request::index("GET","id"),
					"lang"=>$_SESSION["LANG"],
					"type"=>"products"
				));
				$this->photos = $photos->getter();
			}else{
				functions\redirect::url(Config::WEBSITE."ru/login");
			}
		}

		$this->postMessage = "";
		if(isset($_POST["title"]) && !empty($_POST["title"]))
		{
			require_once("app/functions/editNewCatalogItemByUser.php");
			$editNewCatalogItemByUser = new functions\editNewCatalogItemByUser();
			$this->postMessage = $editNewCatalogItemByUser->index();
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
		$this->view('profile/edit', [
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
			"editdata"=>$this->product,
			"photos"=>$this->photos,
			"additionalinfo"=>$db_additionalinfo->getter(), 
			"footer"=>$footer->index() 
		]);
	}
}