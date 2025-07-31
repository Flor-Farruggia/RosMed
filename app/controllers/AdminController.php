<?php
namespace app\controllers;

use \Controller;
use \Response;
use app\models\UserModel;
use app\controllers\SessionController;


class AdminController extends Controller
{
        // Constructor
	public function __construct(){}

    public function actionIndex($var = null)
    {
        $error = [
            'usuario' => '',
            'pass' => '',
            'general' => ''
        ];

        $datos = [
            'usuario' => '',
            'pass' => ''
        ];

        $db = \DataBase::connection();

        try {
            // Verificar si ya existe el admin en la tabla
            $stmt = $db->query("SELECT * FROM admin LIMIT 1");
            $admin = $stmt->fetch(\PDO::FETCH_ASSOC);

            // Si no hay admin, lo creamos automáticamente
            if (!$admin) {
                $usuario = 'admin';
                $password = password_hash('admin1234', PASSWORD_DEFAULT);

                $insert = $db->prepare("INSERT INTO admin (usuario, password) VALUES (?, ?)");
                $insert->execute([$usuario, $password]);

                // Volvemos a obtener el registro recién insertado
                $stmt = $db->query("SELECT * FROM admin LIMIT 1");
                $admin = $stmt->fetch(\PDO::FETCH_ASSOC);
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $datos['usuario'] = trim($_POST['usuario'] ?? '');
                $datos['pass'] = trim($_POST['pass'] ?? '');

                if ($datos['usuario'] === $admin['usuario'] && password_verify($datos['pass'], $admin['password'])) {
                    session_start();
                    session_regenerate_id(true);
                    $_SESSION['id'] = -1;
                    $_SESSION['tipoUser'] = 'admin';
                    $_SESSION['email'] = 'admin';
                    $_SESSION['nombre'] = 'Administrador';
                    header("Location: " . Controller::path() . "admin/panel");
                    exit();
                } else {
                    $error['general'] = "Credenciales incorrectas.";
                }
            }

        } catch (\PDOException $e) {
            $error['general'] = "Error de conexión a la base de datos: " . $e->getMessage();
        }

        Response::render($this->viewDir(__NAMESPACE__), "index", [
            'title' => 'Ingreso de Admin',
            'head' => SiteController::head(),
            'nav' => SiteController::nav(),
            'footer' => SiteController::footer(),
            'datos' => $datos,
            'error' => $error
        ]);
    }

    public function actionPanel()
    {
         // Asegurar sesión iniciada
        SessionController::redirigirSiNoAutenticado();

        // Asegurar que sea admin
        if (SessionController::obtenerRol() !== 'admin') {
            $this->redirigirLogin();
        }

        // Valores iniciales
        $usuarios = [];
        $filtro = '';
        $tipoUser = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $filtro = trim($_POST['buscar'] ?? '');
            $tipoUser = $_POST['tipoUser'] ?? '';

            $usuarios = UserModel::buscarUsuarios($filtro, $tipoUser);
        } else {
            // Mostrar todos si no se envió búsqueda
            $usuarios = UserModel::listarTodos();
        }

        // Obtener filtros del GET
        $filtro = $_GET['buscar'] ?? '';
        $tipoUser = $_GET['tipoUser'] ?? '';

        // Consultar usuarios con o sin filtros
        if (!empty($filtro) || !empty($tipoUser)) {
            $usuarios = UserModel::buscarUsuarios($filtro, $tipoUser);
        } else {
            $usuarios = UserModel::listarTodos();
        }

        $head = SiteController::head();
        $nav = SiteController::nav();
        $footer = SiteController::footer();
        $path = static::path();

        Response::render($this->viewDir(__NAMESPACE__), 'panel', [
            'title' => 'Panel de Administración',
            'head' => $head,
            'nav' => $nav,
            'footer' => $footer,
            'usuarios' => $usuarios,
            'filtro' => $filtro,
            'tipoUser' => $tipoUser,
            'ruta'=>self::$ruta,
        ]);
    }

    public function actionToggleActivo($id)
    {
        SessionController::redirigirSiNoAutenticado();
        if ($_SESSION['tipoUser'] !== 'admin') {
            header("Location: " . Controller::path() . "login/index");
            exit;
        }

        try {
            $db = \DataBase::connection();

            $stmt = $db->prepare("SELECT activo FROM usuarios WHERE id = ?");
            $stmt->execute([$id]);
            $usuario = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$usuario) {
                $_SESSION['msg'] = "Usuario no encontrado.";
            } else {
                $nuevoEstado = $usuario['activo'] ? 0 : 1;
                $update = $db->prepare("UPDATE usuarios SET activo = ? WHERE id = ?");
                $update->execute([$nuevoEstado, $id]);
                $_SESSION['msg'] = "Estado actualizado correctamente.";
            }

        } catch (\PDOException $e) {
            $_SESSION['msg'] = "Error: " . $e->getMessage();
        }

        header("Location: " . Controller::path() . "admin/panel");
        exit;
    }

    public function actionCambioCredencial()
    {
        SessionController::redirigirSiNoAutenticado();
        if ($_SESSION['tipoUser'] !== 'admin') {
            header("Location: " . Controller::path() . "login/index");
            exit();
        }

        $error = ['pass1' => '', 'pass2' => '', 'general' => ''];
        $msg = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pass1 = trim($_POST['pass1'] ?? '');
            $pass2 = trim($_POST['pass2'] ?? '');

            // Validaciones
            if (strlen($pass1) < 6 || strlen($pass1) > 10) {
                $error['pass1'] = 'La contraseña debe tener entre 6 y 10 caracteres.';
            }

            if ($pass1 !== $pass2) {
                $error['pass2'] = 'Las contraseñas no coinciden.';
            }

            if (empty($error['pass1']) && empty($error['pass2'])) {
                try {
                    $db = \DataBase::connection();
                    $hash = password_hash($pass1, PASSWORD_DEFAULT);

                    $stmt = $db->prepare("UPDATE admin SET password = ? WHERE usuario = 'admin'");// ID fijo si solo hay 1
                    $stmt->execute([$hash]);

                    if ($stmt->rowCount() > 0) {
                        $msg = "Contraseña actualizada correctamente.";
                    } else {
                        $error['general'] = "No se pudo actualizar la contraseña. Verificá que el usuario admin exista.";
                    }

                } catch (\PDOException $e) {
                    $error['general'] = "Error al actualizar contraseña: " . $e->getMessage();
                }
            }
        }

        Response::render($this->viewDir(__NAMESPACE__), "cambioCredencial", [
            'title' => 'Cambiar contraseña admin',
            'head' => SiteController::head(),
            'nav' => SiteController::nav(),
            'footer' => SiteController::footer(),
            'error' => $error,
            'msg' => $msg,
            'path' => static::path()
        ]);
    }

    public function actionBuscar()
    {
        SessionController::redirigirSiNoAutenticado();

        if (SessionController::obtenerRol() !== 'admin') {
            header("Location: /login/index");
            exit();
        }

        $filtro = $_GET['buscar'] ?? '';
        $tipoUser = $_GET['tipoUser'] ?? '';

        $usuarios = UserModel::buscarUsuarios($filtro, $tipoUser);


        $head = SiteController::head();
        $nav = SiteController::nav();
        $footer = SiteController::footer();
        $path = static::path();

        Response::render($this->viewDir(__NAMESPACE__), 'buscar', [
            'title' => 'Filtro',
            'head' => $head,
            'nav' => $nav,
            'footer' => $footer,
            'usuarios' => $usuarios,
            'filtro' => $filtro,
            'tipoUser' => $tipoUser,
            'ruta'=>self::$ruta,
        ]);
    }

    public function actionCrear()
    {
        SessionController::redirigirSiNoAutenticado();

        if (SessionController::obtenerRol() !== 'admin') {
            header("Location: /login/index");
            exit();
        }

        $datos = [
            'nombre' => '', 'apellido' => '', 'dni' => '', 'fechaNac' => '',
            'tipoUser' => '', 'telefono' => '', 'email' => '', 'pass' => '',
        ];

        $error = [
            'nombre' => '', 'apellido' => '', 'dni' => '', 'fechaNac' => '',
            'tipoUser' => '', 'telefono' => '', 'email' => '', 'pass' => '', 'general' => '',
        ];

        $status = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {


            $campos = ['nombre' => [3, 100], 'apellido' => [3, 100], 'telefono' => [7, 16], 'pass' => [5, 20]];
            foreach ($campos as $campo => [$min, $max]) {
                $val = Controller::validarCampo($campo, $min, $max, $campo);
                $datos[$campo] = $val['campo2'];
                if ($val['error']) {
                    $error[$campo] = $val['msg'];
                    $status = true;
                }
            }

            // DNI
            $valDni = Controller::validarCampoNum('dni', 7, 11, 'dni');
            $datos['dni'] = $valDni['campo2'];
            if ($valDni['error']) {
                $error['dni'] = $valDni['msg'];
                $status = true;
            }

            // Fecha de nacimiento
            $valFecha = Controller::validarFechaNacimiento('fechaNac', 0, 100);
            $datos['fechaNac'] = $valFecha['campo2'];
            if ($valFecha['error']) {
                $error['fechaNac'] = $valFecha['msg'];
                $status = true;
            }

            // Email
            $valEmail = Controller::validarEmail('email', 5, 100, 'e-mail');
            $datos['email'] = $valEmail['campo2'];
            if ($valEmail['error']) {
                $error['email'] = $valEmail['msg'];
                $status = true;
            }

            // Tipo de usuario
            $validTipo = Controller::validarCheck('tipoUser', 'tipo de usuario', ['paciente', 'medico']);
            $datos['tipoUser'] = $validTipo['campo2'];
            if ($validTipo['error']) {
                $error['tipoUser'] = $validTipo['msg'];
                $status = true;
            }

            // Validaciones de duplicado
            if (!$status && UserModel::emailExists($datos['email'])) {
                $error['email'] = "Este email ya está registrado.";
                $status = true;
            }

            if (!$status && UserModel::dniExists($datos['dni'])) {
                $error['dni'] = "Este DNI ya está registrado.";
                $status = true;
            }

            // Creación
            if (!$status) {
                if (UserModel::createUser($datos)) {
                    $_SESSION['msg'] = "Usuario creado correctamente";
                    header("Location: " . static::path() . "admin/panel");
                    exit();
                } else {
                    $error['general'] = "Error al crear el usuario.";
                }
            }
        }

        $head = SiteController::head();
        $nav = SiteController::nav();
        $footer = SiteController::footer();

        Response::render($this->viewDir(__NAMESPACE__), 'crear', [
            'title' => 'Nuevo Usuario',
            'head' => $head,
            'nav' => $nav,
            'footer' => $footer,
            'datos' => $datos,
            'error' => $error,
        ]);
    }

    public function actionEditar($id = null)
    {
        SessionController::redirigirSiNoAutenticado();

        if (SessionController::obtenerRol() !== 'admin') {
            header("Location: /login/index");
            exit();
        }

        if (!$id || !is_numeric($id)) {
            header("Location: " . Controller::path() . "admin/panel");
            exit();
        }

        $usuario = UserModel::getById($id);
        if (!$usuario) {
            $_SESSION['msg'] = "Usuario no encontrado";
            header("Location: " . Controller::path() . "admin/panel");
            exit();
        }

        $error = [];
        $datos = (array) $usuario;
        $status = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $campos = ['nombre' => [3, 100], 'apellido' => [3, 100], 'telefono' => [7, 15]];
            foreach ($campos as $campo => [$min, $max]) {
                $val = Controller::validarCampo($campo, $min, $max, $campo);
                if ($val['error']) {
                    $error[$campo] = $val['msg'];
                    $status = true;
                } else {
                    $datos[$campo] = $val['campo2'];
                }
            }

            // Contraseña opcional
            $valPass = Controller::validarCampo('pass', 5, 20, 'contraseña');
            if (!$valPass['error'] && !empty($valPass['campo2'])) {
                $datos['pass'] = password_hash($valPass['campo2'], PASSWORD_DEFAULT);
            }

            // DNI
            $valDni = Controller::validarCampoNum('dni', 7, 11, 'dni');
            if ($valDni['error']) {
                $error['dni'] = $valDni['msg'];
                $status = true;
            } else {
                $datos['dni'] = $valDni['campo2'];
            }

            // Fecha de nacimiento
           $valFecha = Controller::validarFechaNacimiento('fechaNac', 0, 100);
            $datos['fechaNac'] = $valFecha['campo2'];
            if ($valFecha['error']) {
                $error['fechaNac'] = $valFecha['msg'];
                $status = true;
            }

            // Email
            $valEmail = Controller::validarEmail('email', 5, 120, 'e-mail');
            if ($valEmail['error']) {
                $error['email'] = $valEmail['msg'];
                $status = true;
            } else {
                $datos['email'] = $valEmail['campo2'];
            }

            // Tipo de usuario
            $validTipo = Controller::validarCheck('tipoUser', 'tipo de usuario', ['paciente', 'medico']);
            if ($validTipo['error']) {
                $error['tipoUser'] = $validTipo['msg'];
                $status = true;
            } else {
                $datos['tipoUser'] = $validTipo['campo2'];
            }

            if (!$status) {
                try {
                    $sql = "UPDATE usuarios SET
                        nombre = ?, apellido = ?, dni = ?, fechaNac = ?, tipoUser = ?,
                        telefono = ?, email = ?" . (isset($datos['pass']) ? ", pass = ?" : "") . "
                        WHERE id = ?";

                    $params = [
                        $datos['nombre'], $datos['apellido'], $datos['dni'], $datos['fechaNac'],
                        $datos['tipoUser'], $datos['telefono'], $datos['email']
                    ];

                    if (isset($datos['pass'])) {
                        $params[] = $datos['pass'];
                    }

                    $params[] = $id;

                    $stmt = \DataBase::connection()->prepare($sql);
                    if ($stmt->execute($params)) {
                        $_SESSION['msg'] = "Usuario actualizado correctamente";
                        header("Location: " . Controller::path() . "admin/panel");
                        exit();
                    } else {
                        $error['general'] = "Error al actualizar el usuario.";
                    }
                } catch (\PDOException $e) {
                    $error['general'] = "Error de base de datos: " . $e->getMessage();
                }
            }
        }

        $head = SiteController::head();
        $nav = SiteController::nav();
        $footer = SiteController::footer();

        if (isset($datos['fechaNac']) && ($datos['fechaNac'] === '0000-00-00' || empty($datos['fechaNac']))) {
            $datos['fechaNac'] = '';
        }

        Response::render($this->viewDir(__NAMESPACE__), 'editar', [
            'title' => 'Editar Usuario',
            'head' => $head,
            'nav' => $nav,
            'footer' => $footer,
            'datos' => $datos,
            'error' => $error,
            'id' => $id,
        ]);
    }

    public function actionEliminar($id = null)
    {
        SessionController::redirigirSiNoAutenticado();

        if (SessionController::obtenerRol() !== 'admin') {
            header("Location: /login/");
            exit();
        }

        if (!$id || !is_numeric($id)) {
            header("Location: " . Controller::path() . "admin/panel");
            exit();
        }

        // Buscar usuario existente
        $usuario = UserModel::getById($id);
        if (!$usuario) {
            $_SESSION['msg'] = "Usuario no encontrado";
            header("Location: " . Controller::path() . "admin/panel");
            exit();
        }

        // Si envía POST con confirmación, eliminar desde la base directamente
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmar'])) {
            try {
                $stmt = \DataBase::connection()->prepare("DELETE FROM usuarios WHERE id = ?");
                $stmt->execute([$id]);

                if ($stmt->rowCount() > 0) {
                    $_SESSION['msg'] = "Usuario eliminado correctamente";
                } else {
                    $_SESSION['msg'] = "No se pudo eliminar el usuario o ya fue eliminado.";
                }

                header("Location: " . Controller::path() . "admin/panel");
                exit();
            } catch (\PDOException $e) {
                $_SESSION['msg'] = "Error al eliminar el usuario: " . $e->getMessage();
                header("Location: " . Controller::path() . "admin/panel");
                exit();
            }
        }

        // Mostrar vista de confirmación
        $head = SiteController::head();
        $nav = SiteController::nav();
        $footer = SiteController::footer();
        $path = static::path();

        Response::render($this->viewDir(__NAMESPACE__), 'eliminar', [
            'title' => 'Eliminar Usuario',
            'head' => $head,
            'nav' => $nav,
            'footer' => $footer,
            'datos' => (array) $usuario,
            'id' => $id
        ]);
    }

    public function actionEliminarPerfil()
    {
        SessionController::redirigirSiNoAutenticado();

        $id = $_SESSION['id'];
        $db = \DataBase::connection();

        try {
            $stmt = $db->prepare("UPDATE usuarios SET activo = 0 WHERE id = ?");
            $stmt->execute([$id]);

            session_destroy();
            header("Location: " . static::path() . "login");
            exit();
        } catch (\PDOException $e) {
            echo "Error al eliminar perfil: " . $e->getMessage();
        }
    }



}
