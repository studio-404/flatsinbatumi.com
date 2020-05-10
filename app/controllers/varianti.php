<?php
class Varianti extends Controller
{
	public function __construct()
	{

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

		$db_slider = new Database("modules", array(
			"method"=>"selectModuleByType", 
			"type"=>"slider",
			"from"=>0, 
			"num"=>15
		));

		$db_whyus = new Database("modules", array(
			"method"=>"selectModuleByType", 
			"type"=>"whyus",
			"order"=>"date",
			"by"=>"DESC",
			"from"=>0, 
			"num"=>15
		));

		$db_video = new Database("modules", array(
			"method"=>"selectModuleByType", 
			"type"=>"video",
			"from"=>0, 
			"num"=>15
		));

		$db_rules = new Database("modules", array(
			"method"=>"selectModuleByType", 
			"type"=>"rules",
			"order"=>"date",
			"by"=>"DESC",
			"from"=>0, 
			"num"=>15
		));

		$db_social = new Database("modules", array(
			"method"=>"selectModuleByType", 
			"type"=>"social",
			"order"=>"date",
			"by"=>"DESC",
			"from"=>0, 
			"num"=>15
		));

		$db_contactinfo = new Database("modules", array(
			"method"=>"selectModuleByType", 
			"type"=>"contactinfo",
			"order"=>"date",
			"by"=>"DESC",
			"from"=>0, 
			"num"=>15
		));

		$db_excursion = new Database("modules", array(
			"method"=>"selectModuleByType", 
			"type"=>"excursion",
			"order"=>"date",
			"by"=>"DESC",
			"from"=>0, 
			"num"=>15
		));

		$db_vocation = new Database("modules", array(
			"method"=>"selectModuleByType", 
			"type"=>"vocation",
			"order"=>"date",
			"by"=>"DESC",
			"from"=>0, 
			"num"=>15
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

		/* slidr */
		$slider = $this->model('_slider');
		$slider->data = $db_slider->getter(); 

		/* navigation */
		$navigation = $this->model('_navigation');

		/* Why us */
		$whyus = $this->model('_whyus');
		$whyus->data = $db_whyus->getter(); 

		/* rules */
		$rules = $this->model('_rules');
		$rules->data = $db_rules->getter(); 

		/* rules */
		$social = $this->model('_social');
		$social->data = $db_social->getter(); 

		/* contactinfo */
		$contactinfo = $this->model('_contactinfo');
		$contactinfo->data = $db_contactinfo->getter(); 

		/* excursion */
		$excursion = $this->model('_excursion');
		$excursion->data = $db_excursion->getter(); 

		/* excursion */
		$vocation = $this->model('_vocation');
		$vocation->data = $db_vocation->getter(); 

		/* header top */
		$headertop = $this->model('_top');
		$headertop->data["languagesModule"] = $languages->index();
		$headertop->data["navigationModule"] = $navigation->index();

		/* Left Navigarion */
		$leftnav = $this->model('_leftnav');
		$leftnav->navigation = $navigation->index();
		$leftnav->slider = $slider->index(); 
		$leftnav->whyus = $whyus->index(); 
		$leftnav->video = $db_video->getter(); 
		$leftnav->rules = $rules->index(); 
		$leftnav->social = $social->index(); 
		$leftnav->contactinfo = $contactinfo->index(); 
		$leftnav->excursion = $excursion->index(); 
		$leftnav->vocation = $vocation->index(); 

		/*footer */
		$footer = $this->model('_footer');

		/* view */
		$this->view('weoffer/index', [
			"header"=>array(
				"website"=>Config::WEBSITE,
				"public"=>Config::PUBLIC_FOLDER
			),
			"headerModule"=>$header->index(), 
			"headertop"=>$headertop->index(), 
			"pageData"=>$db_pagedata->getter(), 
			"leftnav"=>$leftnav->index(), 
			"footer"=>$footer->index() 
		]);
	}

}