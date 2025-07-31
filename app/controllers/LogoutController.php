<?php
namespace app\controllers;

use \Controller;

class LogoutController extends Controller
{
    public function actionLogout()
    {
        session_start();

        // Destruir todas las variables de sesión
        $_SESSION = [];

        // Destruir la sesión
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        session_destroy();

        // Redirigir al login
        header("Location: /RosMed/home/");
        exit();
    }
}
