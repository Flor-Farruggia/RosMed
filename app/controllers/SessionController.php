<?php
namespace app\controllers;

class SessionController
{
    public static function iniciar()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function estaAutenticado()
    {
        self::iniciar();
        return isset($_SESSION['id']);
    }

    public static function redirigirSiNoAutenticado()
    {
        if (!self::estaAutenticado()) {
            header("Location: /RosMed/login");
            exit();
        }
    }

    public static function obtenerRol()
    {
        self::iniciar();
        return $_SESSION['tipoUser'] ?? null;
    }

    public static function obtenerEmail()
    {
        self::iniciar();
        return $_SESSION['email'] ?? null;
    }

    public static function cerrarSesion()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        session_unset();
        session_destroy(); 

        header("Location: /login/");
        exit;
    }
}
