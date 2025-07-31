<?php 
namespace app\controllers;

use \Controller;
use \Response;
use app\models\UserModel;

class LoginController extends Controller
{
    public function actionIndex($var = null)
    {
        $error = [
            'email' => '',
            'pass' => '',
            'general' => ''
        ];

        $datos = [
            'email' => '',
            'pass' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $checkEmail = Controller::validarEmail('email', 5, 120, 'e-mail');
            $checkPass  = Controller::validarCampo('pass', 5, 20, 'contraseña');

            $datos['email'] = $checkEmail['campo2'] ?? '';
            $datos['pass']  = $checkPass['campo2'] ?? '';


            if (!$checkEmail['error'] && !$checkPass['error']) {
                $user = UserModel::findEmail(trim($datos['email']));
                if (!$user) {
                    $error['general'] = 'Usuario no encontrado.';
                } elseif ($user->activo == 0) {
                    $mostrarModal = true;
                } elseif (!password_verify($datos['pass'], $user->pass)) {
                    $error['general'] = 'Contraseña incorrecta.';
                } else {
                    session_start();
                    session_regenerate_id(true);

                    $_SESSION['id']        = $user->id;
                    $_SESSION['nombre']    = $user->nombre;
                    $_SESSION['email']     = $user->email;
                    $_SESSION['tipoUser']  = $user->tipoUser;

                    switch ($user->tipoUser) {
                        case 'medico': header("Location:". Controller::path() . "medico"); break;
                        case 'paciente': header("Location:". Controller::path() . "paciente"); break;
                        default: header("Location:". Controller::path() ); break;
                    }
                    exit();
                }
            } else {
                $error['email'] = $checkEmail['msg'];
                $error['pass'] = $checkPass['msg'];
            }
        }

        Response::render($this->viewDir(__NAMESPACE__), "index", [
            'title'  => 'Iniciar Sesión',
            'head'   => SiteController::head(),
            'nav'    => SiteController::nav(),
            'footer' => SiteController::footer(),
            'datos'  => $datos,
            'error'  => $error,
            'mostrarModal' => $mostrarModal ?? false,
            'user' => $user ?? null,
            'path' => static::path()
        ]);
    }

    public function actionReactivarCuenta()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $email = $_POST['email'] ?? '';
            $tipo = $_POST['tipoUser'] ?? '';

            if ($id && $email && $tipo) {
                try {
                    $db = \DataBase::connection();
                    $stmt = $db->prepare("UPDATE usuarios SET activo = 1 WHERE id = ?");
                    $stmt->execute([$id]);

                    // Iniciar sesión automática después de reactivar
                    session_start();
                    session_regenerate_id(true);
                    $_SESSION['id'] = $id;
                    $_SESSION['email'] = $email;
                    $_SESSION['tipoUser'] = $tipo;
                    $_SESSION['nombre'] = ''; // Podés hacer una consulta si querés mostrar el nombre

                    header("Location: " . static::path() . $tipo);
                    exit();
                } catch (\PDOException $e) {
                    echo "Error al reactivar cuenta. Intente más tarde.";
                }
            } else {
                header("Location: " . static::path() . "login");
                exit();
            }
        } else {
            header("Location: " . static::path() . "login");
            exit();
        }
    }

}
