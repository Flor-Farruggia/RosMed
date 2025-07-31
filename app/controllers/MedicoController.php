<?php
namespace app\controllers;

use \Controller;
use \Response;
use app\models\UserModel;

class MedicoController extends Controller {
    public function actionIndex($var=null) {
        SessionController::redirigirSiNoAutenticado();

        if ($_SESSION['tipoUser'] !== 'medico') {
            header("Location: " . static::path());
            exit();
        }

        $usuario = UserModel::getById($_SESSION['id']);
        $matricula = ''; // valor por defecto

        try {
            $db = \DataBase::connection();
            $stmt = $db->prepare("SELECT matricula FROM matriculas WHERE id_usuario = ?");
            $stmt->execute([$usuario->id]);
            $resultado = $stmt->fetch(\PDO::FETCH_ASSOC);
            if ($resultado && isset($resultado['matricula'])) {
                $matricula = $resultado['matricula'];
            }
        } catch (\PDOException $e) {
            // podés loguear el error si querés
        }

        Response::render($this->viewDir(__NAMESPACE__), "index", [
            "title" => "Perfil de médico",
            "head" => SiteController::head(),
            "nav" => SiteController::nav(),
            "footer" => SiteController::footer(),
            "usuario" => $usuario,
            "matricula" => $matricula,
            "path" => static::path()
        ]);

    }

    public function actionEditar($campo) {
        SessionController::redirigirSiNoAutenticado();

        if ($_SESSION['tipoUser'] !== 'medico') {
            header("Location: " . static::path());
            exit();
        }

        $permitidos = ['nombre', 'apellido', 'dni', 'fechaNac', 'telefono', 'email', 'pass', 'matricula'];
        if (!in_array($campo, $permitidos)) {
            $_SESSION['msg'] = "Campo inválido";
            header("Location: " . static::path() . "medico");
            exit();
        }

        $id = $_SESSION['id'];
        $error = '';
        $valor = '';

        try {
            $db = \DataBase::connection();
            if ($campo === 'matricula') {
                $stmt = $db->prepare("SELECT matricula FROM matriculas WHERE id_usuario = ?");
                $stmt->execute([$id]);
                $resultado = $stmt->fetch(\PDO::FETCH_ASSOC);
                $valor = $resultado['matricula'] ?? '';
            } else {
                $stmt = $db->prepare("SELECT $campo FROM usuarios WHERE id = ?");
                $stmt->execute([$id]);
                $resultado = $stmt->fetch(\PDO::FETCH_ASSOC);
                $valor = $campo === 'pass' ? '' : $resultado[$campo];
            }
        } catch (\PDOException $e) {
            $error = "Error al conectar con la base de datos.";
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            switch ($campo) {
                case 'nombre':
                    $val = Controller::validarCampo($campo, 3, 100, $campo);
                    break;
                case 'apellido':
                    $val = Controller::validarCampo($campo, 3, 100, $campo);
                    break;

                case 'dni':
                    $val = Controller::validarCampoNum($campo, 7, 10, 'DNI');
                    break;

                case 'fechaNac':
                    $val = Controller::validarFechaNacimiento('fechaNac', 0, 100);
                    break;

                case 'telefono':
                    $val = Controller::validarCampo($campo, 9, 20, 'teléfono');
                    break;

                case 'email':
                    $val = Controller::validarEmail($campo, 5, 100, 'e-mail');
                    break;

                case 'pass':
                    $val = Controller::validarCampo($campo, 5, 10, 'contraseña');
                    break;

                case 'matricula':
                    $val = Controller::validarCampoNum($campo, 4, 15, 'matrícula');
                    break;

                default:
                    $val = ['error' => true, 'msg' => 'Campo inválido'];
            }

            if ($val['error']) {
                $error = $val['msg'];
            } else {
                $nuevoValor = $val['campo2'];

                if ($campo === 'pass') {
                    $nuevoValor = password_hash($nuevoValor, PASSWORD_DEFAULT);
                }

                try {
                    if ($campo === 'matricula') {
                        // Actualizar o insertar matrícula
                        $check = $db->prepare("SELECT id FROM matriculas WHERE id_usuario = ?");
                        $check->execute([$id]);

                        if ($check->rowCount() > 0) {
                            $update = $db->prepare("UPDATE matriculas SET matricula = ? WHERE id_usuario = ?");
                        } else {
                            $update = $db->prepare("INSERT INTO matriculas (matricula, id_usuario) VALUES (?, ?)");
                        }
                        $ejecutado = $update->execute([$nuevoValor, $id]);
                    } else {
                        // Actualizar en la tabla usuarios
                        $update = $db->prepare("UPDATE usuarios SET $campo = ? WHERE id = ?");
                        $ejecutado = $update->execute([$nuevoValor, $id]);
                    }

                    if ($ejecutado) {
                        $_SESSION['msg'] = "Campo actualizado con éxito";
                        if ($campo === 'nombre') {
                            $_SESSION['nombre'] = $val['campo2'];
                        }
                        header("Location: " . static::path() . "medico");
                        exit();
                    } else {
                        $error = "No se pudo actualizar el campo.";
                    }
                } catch (\PDOException $e) {
                    $error = "Error al actualizar en base de datos.";
                }
            }
        }

        $stmt = $db->prepare("SELECT matricula FROM matriculas WHERE id_usuario = ?");
        $stmt->execute([$id]);
        $matricula = $stmt->fetchColumn();

        Response::render($this->viewDir(__NAMESPACE__), "editar", [
            "matricula" => $matricula,
            "title" => "Editar $campo",
            "head" => SiteController::head(),
            "nav" => SiteController::nav(),
            "footer" => SiteController::footer(),
            "campo" => $campo,
            "valor" => $valor,
            "error" => $error,
            "path" => static::path()
        ]);
    }

    public function actionListaPacientes() {
        SessionController::redirigirSiNoAutenticado();

        if ($_SESSION['tipoUser'] !== 'medico') {
            header("Location: " . static::path());
            exit();
        }

        $id_medico = $_SESSION['id'];
        $dniFiltro = $_GET['dni'] ?? '';
        $pacientes = [];

        try {
            $db = \DataBase::connection();

            // Pacientes locales (tabla pacientes)
            $sql1 = "
                SELECT p.id, p.nombre, p.apellido, p.dni
                FROM medico_paciente mp
                JOIN pacientes p ON mp.id_paciente_local = p.id
                WHERE mp.id_medico = ?
            ";

            // Pacientes usuarios registrados
            $sql2 = "
                SELECT u.id, u.nombre, u.apellido, u.dni
                FROM medico_paciente mp
                JOIN usuarios u ON mp.id_paciente_user = u.id
                WHERE mp.id_medico = ? AND u.tipoUser = 'paciente'
            ";

            $params1 = [$id_medico];
            $params2 = [$id_medico];

            if (!empty($dniFiltro)) {
                $sql1 .= " AND p.dni LIKE ?";
                $sql2 .= " AND u.dni LIKE ?";
                $params1[] = "%$dniFiltro%";
                $params2[] = "%$dniFiltro%";
            }

            // Ejecutar ambas
            $stmt1 = $db->prepare($sql1);
            $stmt1->execute($params1);
            $pacientes1 = $stmt1->fetchAll(\PDO::FETCH_ASSOC);

            $stmt2 = $db->prepare($sql2);
            $stmt2->execute($params2);
            $pacientes2 = $stmt2->fetchAll(\PDO::FETCH_ASSOC);

            // Unir resultados
            $pacientes = array_merge($pacientes1, $pacientes2);

        } catch (\PDOException $e) {
            $_SESSION['msg'] = "Error al obtener pacientes.";
        }

        Response::render($this->viewDir(__NAMESPACE__), "listaPacientes", [
            "title" => "Lista de Pacientes",
            "head" => SiteController::head(),
            "nav" => SiteController::nav(),
            "footer" => SiteController::footer(),
            "pacientes" => $pacientes,
            "path" => static::path()
        ]);
    }

    public function actionAgregarPaciente() {
        SessionController::redirigirSiNoAutenticado();

        if ($_SESSION['tipoUser'] !== 'medico') {
            header("Location: " . Controller::path() . "login");
            exit;
        }

        $db = \DataBase::connection();
        $datos = [];
        $usuarioEncontrado = null;
        $mostrarFormularioManual = false;
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // 🔍 Buscar por DNI
            if (isset($_POST['buscar'])) {
                $dni = trim($_POST['dni']);
                $datos['dni'] = $dni;

                try {
                    $stmt = $db->prepare("SELECT * FROM usuarios WHERE dni = :dni AND tipoUser = 'paciente'");
                    $stmt->execute(['dni' => $dni]);
                    $usuarioEncontrado = $stmt->fetch(\PDO::FETCH_ASSOC);

                    if (!$usuarioEncontrado) {
                        $mostrarFormularioManual = true;
                    }
                } catch (\PDOException $e) {
                    $error = "Error al buscar paciente: " . $e->getMessage();
                }
            }

            // ➕ Asociar paciente ya registrado
            if (isset($_POST['asociar']) && isset($_POST['id_usuario'])) {
                $idUsuarioPaciente = intval($_POST['id_usuario']);
                $idMedico = $_SESSION['id'];

                try {
                    // Verificar si ya está asociado
                    $check = $db->prepare("SELECT 1 FROM medico_paciente WHERE id_medico = ? AND id_paciente_user = ?");
                    $check->execute([$idMedico, $idUsuarioPaciente]);

                    if ($check->fetch()) {
                        $error = "Este paciente ya está en tu lista.";
                    } else {
                        $stmt = $db->prepare("INSERT INTO medico_paciente (id_medico, id_paciente_user) VALUES (?, ?)");
                        $stmt->execute([$idMedico, $idUsuarioPaciente]);

                        header("Location: " . Controller::path() . "medico/listaPacientes");
                        exit;
                    }
                } catch (\PDOException $e) {
                    $error = "Error al asociar paciente: " . $e->getMessage();
                }
            }

            // ➕ Crear nuevo paciente manualmente
            if (isset($_POST['crear'])) {
                $nombre    = trim($_POST['nombre'] ?? '');
                $apellido  = trim($_POST['apellido'] ?? '');
                $dni       = trim($_POST['dni'] ?? '');

                $datos['dni'] = $dni;

                if (empty($nombre) || empty($apellido) || empty($dni)) {
                    $error = "Todos los campos son obligatorios.";
                } else {
                    try {
                        // Verificar si el DNI ya está en usuarios
                        $stmt1 = $db->prepare("SELECT COUNT(*) FROM usuarios WHERE dni = ?");
                        $stmt1->execute([$dni]);
                        $existeEnUsuarios = $stmt1->fetchColumn() > 0;

                        // Verificar si el DNI ya está en pacientes
                        $stmt2 = $db->prepare("SELECT COUNT(*) FROM pacientes WHERE dni = ?");
                        $stmt2->execute([$dni]);
                        $existeEnPacientes = $stmt2->fetchColumn() > 0;

                        if ($existeEnUsuarios) {
                            $error = "Este DNI pertenece a un usuario registrado. Por favor, usá el botón 'Buscar' y asociá desde ahí.";
                        } elseif ($existeEnPacientes) {
                            // Verificamos si ya está asociado al médico
                            $stmtCheck = $db->prepare("SELECT 1 FROM medico_paciente WHERE id_medico = ? AND id_paciente_local = (SELECT id FROM pacientes WHERE dni = ?)");
                            $stmtCheck->execute([$_SESSION['id'], $dni]);

                            if ($stmtCheck->fetch()) {
                                $error = "Este paciente ya está en tu lista.";
                            } else {
                                // Obtener el ID del paciente existente
                                $stmtId = $db->prepare("SELECT id FROM pacientes WHERE dni = ? LIMIT 1");
                                $stmtId->execute([$dni]);
                                $idPacienteLocal = $stmtId->fetchColumn();

                                // Asociar al médico
                                $stmt2 = $db->prepare("INSERT INTO medico_paciente (id_medico, id_paciente_local) VALUES (?, ?)");
                                $stmt2->execute([$_SESSION['id'], $idPacienteLocal]);

                                header("Location: " . Controller::path() . "medico/listaPacientes");
                                exit;
                            }
                        } else {
                            // Insertar nuevo paciente
                            $stmt = $db->prepare("INSERT INTO pacientes (nombre, apellido,  dni) VALUES (?, ?, ?)");
                            $stmt->execute([$nombre, $apellido, $dni]);

                            $idPacienteLocal = $db->lastInsertId();

                            // Asociar al médico
                            $stmt2 = $db->prepare("INSERT INTO medico_paciente (id_medico, id_paciente_local) VALUES (?, ?)");
                            $stmt2->execute([$_SESSION['id'], $idPacienteLocal]);

                            header("Location: " . Controller::path() . "medico/listaPacientes");
                            exit;
                        }
                    } catch (\PDOException $e) {
                        $error = "Error al crear paciente: " . $e->getMessage();
                    }
                }
            }
        }

        Response::render($this->viewDir(__NAMESPACE__), "agregarPaciente", [
            'title' => 'Agregar Paciente',
            'head'  => SiteController::head(),
            'nav'   => SiteController::nav(),
            'footer'=> SiteController::footer(),
            'datos' => $datos,
            'usuarioEncontrado' => $usuarioEncontrado,
            'mostrarFormularioManual' => $mostrarFormularioManual,
            'error' => $error,
            'path'  => static::path()
        ]);
    }

    public function actionEliminarPaciente($idPaciente) {
        SessionController::redirigirSiNoAutenticado();

        if ($_SESSION['tipoUser'] !== 'medico') {
            header("Location: " . static::path());
            exit();
        }

        $idMedico = $_SESSION['id'];
        $db = \DataBase::connection();
        $paciente = null;
        $esUsuario = false;

        try {
            // Buscar primero en pacientes locales
            $stmt = $db->prepare("
                SELECT p.id, p.nombre, p.apellido 
                FROM pacientes p 
                JOIN medico_paciente mp ON mp.id_paciente_local = p.id 
                WHERE p.id = ? AND mp.id_medico = ?
            ");
            $stmt->execute([$idPaciente, $idMedico]);
            $paciente = $stmt->fetch(\PDO::FETCH_ASSOC);

            // Si no está en pacientes locales, buscar en usuarios
            if (!$paciente) {
                $stmt = $db->prepare("
                    SELECT u.id, u.nombre, u.apellido 
                    FROM usuarios u 
                    JOIN medico_paciente mp ON mp.id_paciente_user = u.id 
                    WHERE u.id = ? AND mp.id_medico = ? AND u.tipoUser = 'paciente'
                ");
                $stmt->execute([$idPaciente, $idMedico]);
                $paciente = $stmt->fetch(\PDO::FETCH_ASSOC);
                $esUsuario = true;
            }

            if (!$paciente) {
                $_SESSION['msg'] = "Paciente no encontrado o no está en tu lista.";
                header("Location: " . static::path() . "medico/listaPacientes");
                exit;
            }

            // Si el usuario confirmó
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmar'])) {
                if ($esUsuario) {
                    $stmt = $db->prepare("DELETE FROM medico_paciente WHERE id_medico = ? AND id_paciente_user = ?");
                } else {
                    $stmt = $db->prepare("DELETE FROM medico_paciente WHERE id_medico = ? AND id_paciente_local = ?");
                }
                $stmt->execute([$idMedico, $idPaciente]);

                $_SESSION['msg'] = "Paciente eliminado de tu lista.";
                header("Location: " . static::path() . "medico/listaPacientes");
                exit;
            }

            // Mostrar vista de confirmación
            Response::render($this->viewDir(__NAMESPACE__), "eliminarPaciente", [
                "title" => "Eliminar paciente",
                "head" => SiteController::head(),
                "nav" => SiteController::nav(),
                "footer" => SiteController::footer(),
                "paciente" => $paciente,
                "idPaciente" => $idPaciente,
                "path" => static::path()
            ]);

        } catch (\PDOException $e) {
            $_SESSION['msg'] = "Error al eliminar paciente: " . $e->getMessage();
            header("Location: " . static::path() . "medico/listaPacientes");
            exit;
        }
    }

    public function actionVerPaciente($idPaciente) {
        SessionController::redirigirSiNoAutenticado();

        if ($_SESSION['tipoUser'] !== 'medico') {
            header("Location: " . static::path());
            exit;
        }

        $idMedico = $_SESSION['id'];
        $db = \DataBase::connection();
        $paciente = null;
        $anotaciones = null;

        try {
            // Verificamos si el paciente pertenece a este médico (usuario o local)
            $stmt = $db->prepare("
                SELECT 
                    COALESCE(u.nombre, p.nombre) AS nombre,
                    COALESCE(u.apellido, p.apellido) AS apellido,
                    mp.anotaciones
                FROM medico_paciente mp
                LEFT JOIN usuarios u ON mp.id_paciente_user = u.id
                LEFT JOIN pacientes p ON mp.id_paciente_local = p.id
                WHERE mp.id_medico = ? AND (mp.id_paciente_user = ? OR mp.id_paciente_local = ?)
                LIMIT 1
            ");
            $stmt->execute([$idMedico, $idPaciente, $idPaciente]);
            $resultado = $stmt->fetch(\PDO::FETCH_ASSOC);

            if ($resultado) {
                $paciente = [
                    'id' => $idPaciente,
                    'nombre' => $resultado['nombre'],
                    'apellido' => $resultado['apellido']
                ];
                $anotaciones = $resultado['anotaciones'];
            } else {
                $_SESSION['msg'] = "No se encontró el paciente en su lista.";
                header("Location: " . static::path() . "medico/listaPacientes");
                exit;
            }
        } catch (\PDOException $e) {
            $_SESSION['msg'] = "Error al obtener datos del paciente.";
            header("Location: " . static::path() . "medico/listaPacientes");
            exit;
        }

        Response::render($this->viewDir(__NAMESPACE__), "verPaciente", [
            'title' => 'Historial del Paciente',
            'head'  => SiteController::head(),
            'nav'   => SiteController::nav(),
            'footer'=> SiteController::footer(),
            'paciente' => $paciente,
            'anotaciones' => $anotaciones,
            'path' => static::path()
        ]);
    }

    public function actionEditarHistorial($idPaciente) {
        SessionController::redirigirSiNoAutenticado();

        if ($_SESSION['tipoUser'] !== 'medico') {
            header("Location: " . static::path());
            exit;
        }

        $idMedico = $_SESSION['id'];
        $db = \DataBase::connection();
        $error = null;
        $anotaciones = '';
        $paciente = null;

        try {
            // Verificar si el paciente pertenece a este médico
            $stmt = $db->prepare("
                SELECT 
                    COALESCE(u.nombre, p.nombre) AS nombre,
                    COALESCE(u.apellido, p.apellido) AS apellido,
                    mp.anotaciones
                FROM medico_paciente mp
                LEFT JOIN usuarios u ON mp.id_paciente_user = u.id
                LEFT JOIN pacientes p ON mp.id_paciente_local = p.id
                WHERE mp.id_medico = ? AND (mp.id_paciente_user = ? OR mp.id_paciente_local = ?)
                LIMIT 1
            ");
            $stmt->execute([$idMedico, $idPaciente, $idPaciente]);
            $resultado = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$resultado) {
                $_SESSION['msg'] = "Paciente no encontrado o no está en su lista.";
                header("Location: " . static::path() . "medico/listaPacientes");
                exit;
            }

            $paciente = [
                'id' => $idPaciente,
                'nombre' => $resultado['nombre'],
                'apellido' => $resultado['apellido']
            ];
            $anotaciones = $resultado['anotaciones'] ?? '';

            // Si envió el formulario
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $nuevasAnotaciones = trim($_POST['anotaciones'] ?? '');

                $update = $db->prepare("
                    UPDATE medico_paciente 
                    SET anotaciones = ? 
                    WHERE id_medico = ? AND (id_paciente_user = ? OR id_paciente_local = ?)
                ");
                $update->execute([$nuevasAnotaciones, $idMedico, $idPaciente, $idPaciente]);

                $_SESSION['msg'] = "Anotaciones actualizadas con éxito.";
                header("Location: " . static::path() . "medico/verPaciente/" . $idPaciente);
                exit;
            }

        } catch (\PDOException $e) {
            $error = "Error al actualizar las anotaciones.";
        }

        Response::render($this->viewDir(__NAMESPACE__), "editarHistorial", [
            'title' => 'Editar Historial',
            'head' => SiteController::head(),
            'nav' => SiteController::nav(),
            'footer' => SiteController::footer(),
            'paciente' => $paciente,
            'anotaciones' => $anotaciones,
            'error' => $error,
            'path' => static::path()
        ]);
    }

    public function actionArchivos()
    {
        SessionController::redirigirSiNoAutenticado();

        if ($_SESSION['tipoUser'] !== 'medico') {
            header("Location: " . static::path());
            exit();
        }

        $db = \DataBase::connection();
        $idMedico = $_SESSION['id'];
        $archivos = [];
        $error = '';

        $busqueda = $_GET['busqueda'] ?? '';

        try {
            if (!empty($busqueda)) {
                $stmt = $db->prepare("SELECT * FROM archivos_medicos WHERE id_medico = ? AND nombre_original LIKE ?");
                $stmt->execute([$idMedico, "%$busqueda%"]);
            } else {
                $stmt = $db->prepare("SELECT * FROM archivos_medicos WHERE id_medico = ?");
                $stmt->execute([$idMedico]);
            }

            $archivos = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            $error = "Error al obtener los archivos: " . $e->getMessage();
        }

        Response::render($this->viewDir(__NAMESPACE__), "archivos", [
            "title" => "Mis Archivos Médicos",
            "head" => \app\controllers\SiteController::head(),
            "nav" => \app\controllers\SiteController::nav(),
            "footer" => \app\controllers\SiteController::footer(),
            "path" => static::path(),
            "archivos" => $archivos,
            "error" => $error
        ]);
    }

    public function actionSubirArchivo()
    {
        SessionController::redirigirSiNoAutenticado();

        if ($_SESSION['tipoUser'] !== 'medico') {
            header("Location: " . static::path());
            exit();
        }

        $error = '';
        $success = '';
        $idMedico = $_SESSION['id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['archivo'])) {
            $archivo = $_FILES['archivo'];

            $permitidos = ['application/pdf', 'image/png', 'image/jpeg'];
            if (!in_array($archivo['type'], $permitidos)) {
                $error = "Formato de archivo no permitido. Solo se permiten PDF, PNG y JPG.";
            } elseif ($archivo['error'] !== UPLOAD_ERR_OK) {
                $error = "Error al subir el archivo.";
            } else {
                $nombreOriginal = $archivo['name'];
                $nombreGuardado = uniqid() . '_' . basename($nombreOriginal);
                $directorio = __DIR__ . '/../privado/archivos_medicos/';
                $rutaCompleta = $directorio . $nombreGuardado;

                if (move_uploaded_file($archivo['tmp_name'], $rutaCompleta)) {
                    try {
                        $db = \DataBase::connection();
                        $fecha = date('Y-m-d H:i:s');
                        $stmt = $db->prepare("INSERT INTO archivos_medicos (id_medico, nombre_original, nombre_guardado, tipo, fecha_subida)
                        VALUES (?, ?, ?, ?, ?)");
                        $stmt->execute([$idMedico, $nombreOriginal, $nombreGuardado, $archivo['type'], $fecha]);
                        $_SESSION['msg'] = "Archivo subido correctamente.";
                        header("Location: " . static::path() . "medico/archivos");
                        exit();
                    } catch (\PDOException $e) {
                        $error = "Error al guardar el archivo en base de datos.";
                    }
                } else {
                    $error = "No se pudo guardar el archivo.";
                }
            }
        }
        // Si hay error, recargar la vista de archivos con mensaje
        try {
            $db = \DataBase::connection();
            $stmt = $db->prepare("SELECT * FROM archivos_medicos WHERE id_medico = ?");
            $stmt->execute([$idMedico]);
            $archivos = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            $archivos = [];
            $error .= " | No se pudieron obtener los archivos para mostrar.";
        }

        Response::render($this->viewDir(__NAMESPACE__), "archivos", [
            "title" => "Mis Archivos Médicos",
            "head" => SiteController::head(),
            "nav" => SiteController::nav(),
            "footer" => SiteController::footer(),
            "error" => $error,
            "archivos" => $archivos,
            "path" => static::path()
        ]);
    }

    public function actionDescargarArchivo($id)
    {
        SessionController::redirigirSiNoAutenticado();

        $db = \DataBase::connection();
        $stmt = $db->prepare("SELECT * FROM archivos_medicos WHERE id = ? AND id_medico = ?");
        $stmt->execute([$id, $_SESSION['id']]);
        $archivo = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$archivo) {
            http_response_code(403);
            echo "Acceso no autorizado o archivo no encontrado.";
            return;
        }

        $ruta = __DIR__ . "/../privado/archivos_medicos/" . $archivo['nombre_guardado'];

        if (!file_exists($ruta)) {
            http_response_code(404);
            echo "Archivo no encontrado.";
            return;
        }

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($archivo['nombre_original']) . '"');
        header('Content-Length: ' . filesize($ruta));
        readfile($ruta);
        exit;
    }

    public function actionEliminarArchivo($id)
    {
        SessionController::redirigirSiNoAutenticado();

        $idMedico = $_SESSION['id'];
        $db = \DataBase::connection();

        $stmt = $db->prepare("SELECT * FROM archivos_medicos WHERE id = ? AND id_medico = ?");
        $stmt->execute([$id, $idMedico]);
        $archivo = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$archivo) {
            $_SESSION['msg'] = "Archivo no encontrado o no autorizado.";
            header("Location: " . static::path() . "medico/archivos");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Response::render($this->viewDir(__NAMESPACE__), "eliminarArchivo", [
                "archivo" => $archivo,
                "path" => static::path(),
                "title" => "Confirmar eliminación",
                "head" => \app\controllers\SiteController::head(),
                "nav" => \app\controllers\SiteController::nav(),
                "footer" => \app\controllers\SiteController::footer()
            ]);
            return;
        }

        $ruta = __DIR__ . '/../privado/archivos_medicos/' . $archivo['nombre_guardado'];
        if (file_exists($ruta)) {
            unlink($ruta);
        }

        $delStmt = $db->prepare("DELETE FROM archivos_medicos WHERE id = ? AND id_medico = ?");
        $delStmt->execute([$id, $idMedico]);

        $_SESSION['msg'] = "Archivo eliminado correctamente.";
        header("Location: " . static::path() . "medico/archivos");
        exit();
    }

    public function actionVerMedicos() {
        SessionController::redirigirSiNoAutenticado();

        $idMedicoActual = $_SESSION['id'];
        $busqueda = $_GET['matricula'] ?? '';
        $medicos = [];

        try {
            $db = \DataBase::connection();

            $sql = "
                SELECT u.id, u.nombre, u.apellido, m.matricula
                FROM medico_paciente mp
                JOIN usuarios u ON mp.id_medico = u.id
                LEFT JOIN matriculas m ON u.id = m.id_usuario
                WHERE mp.id_paciente_user = ? AND u.tipoUser = 'medico'
            ";

            $params = [$idMedicoActual];

            if (!empty($busqueda)) {
                $sql .= " AND m.matricula LIKE ?";
                $params[] = "%$busqueda%";
            }

            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            $medicos = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        } catch (\PDOException $e) {
            $_SESSION['msg'] = "Error al obtener médicos tratantes.";
        }

        Response::render($this->viewDir(__NAMESPACE__), "verMedicos", [
            "title" => "Mis médicos tratantes",
            "head" => SiteController::head(),
            "nav" => SiteController::nav(),
            "footer" => SiteController::footer(),
            "medicos" => $medicos,
            "path" => static::path()
        ]);
    }

    public function actionAgregarMedico()
    {
        SessionController::redirigirSiNoAutenticado();

        $idPaciente = $_SESSION['id'];
        $db = \DataBase::connection();
        $datos = [];
        $medicoEncontrado = null;
        $error = null;
        $mostrarFormularioManual = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Acción: Buscar médico
            if (isset($_POST['buscar'])) {
                $matricula = trim($_POST['matricula'] ?? '');
                $datos['matricula'] = $matricula;

                try {
                    $stmt = $db->prepare("SELECT u.id, u.nombre, u.apellido FROM usuarios u JOIN matriculas m ON u.id = m.id_usuario WHERE m.matricula = ? AND u.tipoUser = 'medico'");
                    $stmt->execute([$matricula]);
                    $medicoEncontrado = $stmt->fetch(\PDO::FETCH_ASSOC);

                    if (!$medicoEncontrado) {
                        $mostrarFormularioManual = true; // Mostrar formulario para creación manual
                    } else {
                        // Ya existe, verificar si está asociado
                        $check = $db->prepare("SELECT 1 FROM medico_paciente WHERE id_medico = ? AND id_paciente_user = ?");
                        $check->execute([$medicoEncontrado['id'], $idPaciente]);

                        if ($check->fetch()) {
                            $error = "Este médico ya está en tu lista.";
                        }
                    }

                } catch (\PDOException $e) {
                    $error = "Error al buscar médico: " . $e->getMessage();
                }
            }

            // Acción: Asociar médico existente
            elseif (isset($_POST['asociar']) && isset($_POST['id_medico'])) {
                try {
                    $idMedico = intval($_POST['id_medico']);
                    $insert = $db->prepare("INSERT INTO medico_paciente (id_medico, id_paciente_user) VALUES (?, ?)");
                    $insert->execute([$idMedico, $idPaciente]);
                    $_SESSION['msg'] = "Médico asociado correctamente.";
                    header("Location: " . static::path() . "medico/verMedicos");
                    exit;
                } catch (\PDOException $e) {
                    $error = "Error al asociar médico: " . $e->getMessage();
                }
            }

            // Acción: Crear médico manualmente
            elseif (isset($_POST['crear'])) {
                $nombre = trim($_POST['nombre'] ?? '');
                $apellido = trim($_POST['apellido'] ?? '');
                $matricula = trim($_POST['matricula'] ?? '');
                $datos = compact('nombre', 'apellido', 'matricula');

                try {
                    // Crear nuevo usuario médico
                    $stmt = $db->prepare("INSERT INTO usuarios (nombre, apellido, tipoUser) VALUES (?, ?, 'medico')");
                    $stmt->execute([$nombre, $apellido]);
                    $idNuevo = $db->lastInsertId();

                    // Insertar en tabla matriculas
                    $stmt = $db->prepare("INSERT INTO matriculas (id_usuario, matricula) VALUES (?, ?)");
                    $stmt->execute([$idNuevo, $matricula]);

                    // Asociar al usuario actual
                    $stmt = $db->prepare("INSERT INTO medico_paciente (id_medico, id_paciente_user) VALUES (?, ?)");
                    $stmt->execute([$idNuevo, $idPaciente]);

                    $_SESSION['msg'] = "Médico creado y asociado correctamente.";
                    header("Location: " . static::path() . "medico/verMedicos");
                    exit;
                } catch (\PDOException $e) {
                    $error = "Error al crear médico: " . $e->getMessage();
                }
            }
        }

        Response::render($this->viewDir(__NAMESPACE__), "agregarMedico", [
            'title' => 'Agregar Médico',
            'head' => SiteController::head(),
            'nav' => SiteController::nav(),
            'footer' => SiteController::footer(),
            'datos' => $datos,
            'medicoEncontrado' => $medicoEncontrado,
            'mostrarFormularioManual' => $mostrarFormularioManual,
            'error' => $error,
            'path' => static::path()
        ]);
    }

    public function actionEliminarMedico($idMedico) {
        SessionController::redirigirSiNoAutenticado();
        $idPaciente = $_SESSION['id'];

        $db = \DataBase::connection();
        try {
            $stmt = $db->prepare("SELECT nombre, apellido FROM usuarios WHERE id = ? AND tipoUser = 'medico'");
            $stmt->execute([$idMedico]);
            $medico = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$medico) {
                $_SESSION['msg'] = "Médico no encontrado.";
                header("Location: " . static::path() . "medico/verMedicos");
                exit;
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $stmt = $db->prepare("DELETE FROM medico_paciente WHERE id_medico = ? AND id_paciente_user = ?");
                $stmt->execute([$idMedico, $idPaciente]);
                $_SESSION['msg'] = "Médico eliminado de tu lista.";
                header("Location: " . static::path() . "medico/verMedicos");
                exit;
            }

            Response::render($this->viewDir(__NAMESPACE__), "eliminarMedico", [
                "title" => "Eliminar Médico",
                "medico" => $medico,
                "idMedico" => $idMedico,
                "path" => static::path(),
                "head" => SiteController::head(),
                "nav" => SiteController::nav(),
                "footer" => SiteController::footer()
            ]);
        } catch (\PDOException $e) {
            $_SESSION['msg'] = "Error al eliminar médico.";
            header("Location: " . static::path() . "medico/verMedicos");
            exit;
        }
    }

    public function actionVerHistorial($idMedico) {
        SessionController::redirigirSiNoAutenticado();
        $idPaciente = $_SESSION['id'];

        $db = \DataBase::connection();
        try {
            $stmt = $db->prepare("SELECT u.nombre, u.apellido, mp.anotaciones FROM medico_paciente mp JOIN usuarios u ON mp.id_medico = u.id WHERE mp.id_medico = ? AND mp.id_paciente_user = ? LIMIT 1");
            $stmt->execute([$idMedico, $idPaciente]);
            $resultado = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$resultado) {
                $_SESSION['msg'] = "No se encontró el médico o no está en tu lista.";
                header("Location: " . static::path() . "medico/verMedicos");
                exit;
            }

            Response::render($this->viewDir(__NAMESPACE__), "verHistorial", [
                'title' => 'Historial Médico',
                'medico' => $resultado,
                'idMedico' => $idMedico,
                'anotaciones' => $resultado['anotaciones'],
                'path' => static::path(),
                'head' => SiteController::head(),
                'nav' => SiteController::nav(),
                'footer' => SiteController::footer()
            ]);

        } catch (\PDOException $e) {
            $_SESSION['msg'] = "Error al obtener historial médico.";
            header("Location: " . static::path() . "medico/verMedicos");
        }
    }

    public function actionEditarHistorialMedico($idMedico) {
        SessionController::redirigirSiNoAutenticado();
        $idPaciente = $_SESSION['id']; // El médico actual actuando como paciente

        $db = \DataBase::connection();

        $error = '';
        $anotaciones = '';

        // Traer datos actuales del historial
        $stmt = $db->prepare("SELECT u.nombre, u.apellido, mp.anotaciones FROM medico_paciente mp JOIN usuarios u ON mp.id_medico = u.id WHERE mp.id_medico = ? AND mp.id_paciente_user = ? LIMIT 1");
        $stmt->execute([$idMedico, $idPaciente]);
        $resultado = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$resultado) {
            $_SESSION['msg'] = "No se encontró el médico o no está en tu lista.";
            header("Location: " . static::path() . "medico/verMedicos");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $anotaciones = trim($_POST['anotaciones'] ?? '');
            if (strlen($anotaciones) < 3) {
                $error = "Las anotaciones deben tener al menos 3 caracteres.";
            } else {
                $update = $db->prepare("UPDATE medico_paciente SET anotaciones = ? WHERE id_medico = ? AND id_paciente_user = ?");
                $update->execute([$anotaciones, $idMedico, $idPaciente]);
                $_SESSION['msg'] = "Historial actualizado correctamente.";
                header("Location: " . static::path() . "medico/verHistorial/$idMedico");
                exit;
            }
        } else {
            $anotaciones = $resultado['anotaciones'];
        }

        Response::render($this->viewDir(__NAMESPACE__), 'editarHistorialMedico', [
            'title' => 'Editar Historial Médico',
            'medico' => $resultado,
            'anotaciones' => $anotaciones,
            'error' => $error,
            'path' => static::path(),
            'head' => SiteController::head(),
            'nav' => SiteController::nav(),
            'footer' => SiteController::footer()
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

            session_destroy(); // Cierra la sesión
            header("Location: " . static::path() . "login");
            exit();
        } catch (\PDOException $e) {
            echo "Error al eliminar perfil: " . $e->getMessage();
        }
    }


}