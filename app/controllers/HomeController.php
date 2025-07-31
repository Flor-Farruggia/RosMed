<?php 
namespace app\controllers;
use \Controller;
use \Response;
use \DataBase;

class HomeController extends Controller
{

    // Constructor
    public function __construct(){

    }

	public function actionIndex($var = null){
		$head = SiteController::head();
		$nav = SiteController::nav();
		$home = SiteController::home();
		$footer = SiteController::footer();
		$path = static::path();
		Response::render($this->viewDir(__NAMESPACE__),"inicio", [
																"title" => 'RosMed',
																 "head" => $head,
																 "nav" => $nav,
																 "home" => $home,
																 "footer" => $footer,
																]);
	}

	public function action404(){
		$head = SiteController::head();
		$nav = SiteController::nav();
		$footer = SiteController::footer();
		$path = static::path();
		Response::render($this->viewDir(__NAMESPACE__),"404", [
																"title" => $this->title.' 404',
																"head" => $head,
																"nav" => $nav,
																"footer" => $footer,
															   ]);
	}


}