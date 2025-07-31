<?php 
namespace app\controllers;
use \Controller;


class SiteController extends Controller
{
    // Constructor
    public function __construct(){
        // self::$sessionStatus = SessionController::sessionVerificacion();
    }

	public static function head(){
		static::path();
		$head = file_get_contents(APP_PATH . '/views/inc/head.php');
		$head = str_replace('#PATH#', self::$ruta, $head);
		return $head;
	}
	public static function header(){
		static::path();
		$header = file_get_contents(APP_PATH.'/views/inc/header.php');
		$header = str_replace('#PATH#', self::$ruta, $header);
		return $header;
	}
	public static function nav(){
		static::path();

		$usuarioHref = self::$ruta . 'login/';
		if (isset($_SESSION['tipoUser'])) {
			if ($_SESSION['tipoUser'] === 'paciente') {
				$usuarioHref = self::$ruta . 'paciente';
			} elseif ($_SESSION['tipoUser'] === 'medico') {
				$usuarioHref = self::$ruta . 'medico';
			} elseif ($_SESSION['tipoUser'] === 'admin') {
				$usuarioHref = self::$ruta . 'admin/panel';
			}
		}

		$nav = file_get_contents(APP_PATH.'/views/inc/nav.php');

		$nav = str_replace('#PATH#', self::$ruta, $nav);
		$nav = str_replace('#HREF_USUARIO#', $usuarioHref, $nav); // Agregamos esto 👈

		return $nav;
	}
	public static function home(){
		static::path();
		$home = file_get_contents(APP_PATH.'/views/inc/home.php');
		$home = str_replace('#PATH#', self::$ruta, $home);
		return $home;
	}
	public static function footer(){
		static::path();
		$footer = file_get_contents(APP_PATH.'/views/inc/footer.php');
		$footer = str_replace('#PATH#', self::$ruta, $footer);
		return $footer;
	}

}
