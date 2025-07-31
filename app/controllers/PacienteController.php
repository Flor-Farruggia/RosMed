<?php
namespace app\controllers;

use \Controller;
use \Response;
use app\models\UserModel;

class PacienteController extends Controller {
    public function actionIndex($var=null){

        SessionController::redirigirSiNoAutenticado();

        if ($_SESSION['tipoUser'] !== 'paciente') {
            header("Location: " . static::path());
            exit();
        }

        $usuario = UserModel::getById($_SESSION['id']);

        Response::render($this->viewDir(__NAMESPACE__), "index", [
            "title" => "Perfil de Paciente",
            "head" => SiteController::head(),
            "nav" => SiteController::nav(),
            "footer" => SiteController::footer(),
            "usuario" => $usuario,
            "path" => static::path()
        ]);
    }

    public function actionEditar($campo)
    {
        SessionController::redirigirSiNoAutenticado();

        if ($_SESSION['tipoUser'] !== 'paciente') {
            header("Location: " . static::path());
            exit();
        }

        $permitidos = ['nombre', 'apellido', 'dni', 'fechaNac', 'telefono', 'email', 'pass'];
        if (!in_array($campo, $permitidos)) {
            $_SESSION['msg'] = "Campo inválido";
            header("Location: " . static::path() . "paciente");
            exit();
        }

        $id = $_SESSION['id'];
        $error = '';
        $valor = '';

        // Obtener valor actual desde la DB
        try {
            $db = \DataBase::connection();
            $stmt = $db->prepare("SELECT $campo FROM usuarios WHERE id = ?");
            $stmt->execute([$id]);
            $resultado = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$resultado) {
                $_SESSION['msg'] = "Usuario no encontrado";
                header("Location: " . static::path() . "paciente");
                exit();
            }

            $valor = $campo === 'pass' ? '' : $resultado[$campo];

        } catch (\PDOException $e) {
            $error = "Error al conectar con la base de datos.";
        }

        // Procesamiento POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            switch ($campo) {
                case 'nombre':
                    $val = Controller::validarCampo($campo, 3, 100, $campo);
                    break;
                case 'apellido':
                    $val = Controller::validarCampo($campo, 3, 100, $campo);
                    break;

                case 'dni':
                    $val = Controller::validarCampoNum($campo, 7, 10, $campo);
                    break;

                case 'fechaNac':
                    $val = Controller::validarFechaNacimiento('fechaNac', 0, 100);
                    break;

                case 'telefono':
                    $val = Controller::validarCampo($campo, 7, 15, 'teléfono');
                    break;

                case 'email':
                    $val = Controller::validarEmail($campo, 5, 100, 'e-mail');
                    break;

                case 'pass':
                    $val = Controller::validarCampo($campo, 5, 10, 'contraseña');
                    break;

                default:
                    $val = ['error' => true, 'msg' => 'Campo no permitido'];
            }

            if ($val['error']) {
                $error = $val['msg'];
            } else {
                $nuevoValor = $val['campo2'];

                if ($campo === 'pass') {
                    $nuevoValor = password_hash($nuevoValor, PASSWORD_DEFAULT);
                }

                try {
                    $update = $db->prepare("UPDATE usuarios SET $campo = ? WHERE id = ?");
                    $ejecutado = $update->execute([$nuevoValor, $id]);

                    if ($ejecutado) {
                        $_SESSION['msg'] = "Campo actualizado con éxito";

                        if ($campo === 'nombre') {
                            $_SESSION['nombre'] = $val['campo2'];
                        }

                        header("Location: " . static::path() . "paciente");
                        exit();
                    } else {
                        $error = "No se pudo actualizar el campo.";
                    }
                } catch (\PDOException $e) {
                    $error = "Error al actualizar en base de datos.";
                }
            }
        }

        Response::render($this->viewDir(__NAMESPACE__), "editar", [
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

    public function actionVerMedicos() {
        SessionController::redirigirSiNoAutenticado();

        if ($_SESSION['tipoUser'] !== 'paciente') {
            header("Location: " . Controller::path());
            exit();
        }

        $db = \DataBase::connection();
        $idPaciente = $_SESSION['id'];
        $busqueda = $_GET['busqueda'] ?? '';
        $medicos = [];

        try {
            // Médicos con cuenta (tabla usuarios)
            $sql1 = "
                SELECT u.id, u.nombre, u.apellido, m.matricula
                FROM medico_paciente mp
                JOIN usuarios u ON mp.id_medico = u.id
                JOIN matriculas m ON m.id_usuario = u.id
                WHERE mp.id_paciente_user = ?
            ";

            // Médicos sin cuenta (tabla medicos)
            $sql2 = "
                SELECT med.id, med.nombre, med.apellido, med.matricula
                FROM medico_paciente mp
                JOIN medicos med ON mp.id_medico_sin_usuario = med.id
                WHERE mp.id_paciente_user = ?
            ";

            $params = [$idPaciente];

            // Si hay filtro por nombre o apellido
            if (!empty($busqueda)) {
                $sql1 .= " AND (u.nombre LIKE ? OR u.apellido LIKE ?)";
                $sql2 .= " AND (med.nombre LIKE ? OR med.apellido LIKE ?)";
                $params[] = "%$busqueda%";
                $params[] = "%$busqueda%";
            }

            // Ejecutar ambas consultas
            $stmt1 = $db->prepare($sql1);
            $stmt1->execute($params);
            $medicos1 = $stmt1->fetchAll(\PDO::FETCH_ASSOC);

            $stmt2 = $db->prepare($sql2);
            $stmt2->execute($params);
            $medicos2 = $stmt2->fetchAll(\PDO::FETCH_ASSOC);

            // Combinar los resultados
            $medicos = array_merge($medicos1, $medicos2);

        } catch (\PDOException $e) {
            $_SESSION['msg'] = "Error al obtener la lista de médicos.";
        }

        Response::render($this->viewDir(__NAMESPACE__), "verMedicos", [
            "title" => "Mis Médicos",
            "head" => SiteController::head(),
            "nav" => SiteController::nav(),
            "footer" => SiteController::footer(),
            "medicos" => $medicos,
            "path" => static::path()
        ]);
    }

    public function actionAgregarMedico() {
        SessionController::redirigirSiNoAutenticado();

        if ($_SESSION['tipoUser'] !== 'paciente') {
            header("Location: " . static::path());
            exit();
        }

        $db = \DataBase::connection();
        $datos = [];
        $medicoEncontrado = null;
        $mostrarFormularioManual = false;
        $error = null;
        $idPaciente = $_SESSION['id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Buscar médico por matrícula
            if (isset($_POST['buscar'])) {
                $matricula = trim($_POST['matricula']);
                $datos['matricula'] = $matricula;

                try {
                    // Buscar en usuarios con matrícula
                    $stmt = $db->prepare("
                        SELECT u.id, u.nombre, u.apellido, 'con_usuario' AS tipo
                        FROM usuarios u 
                        JOIN matriculas m ON u.id = m.id_usuario 
                        WHERE m.matricula = ?
                    ");
                    $stmt->execute([$matricula]);
                    $medicoEncontrado = $stmt->fetch(\PDO::FETCH_ASSOC);

                    if ($medicoEncontrado) {
                        // Verificar si ya está asociado
                        $check = $db->prepare("SELECT 1 FROM medico_paciente WHERE id_medico = ? AND id_paciente_user = ?");
                        $check->execute([$medicoEncontrado['id'], $idPaciente]);

                        if ($check->fetch()) {
                            $error = "Médico encontrado: " . $medicoEncontrado['nombre'] . " " . $medicoEncontrado['apellido'] . ". Ya está en su lista.";
                            $medicoEncontrado = null; // para no mostrar botón de asociar
                        }
                    } else {
                        // Buscar en tabla medicos (usuario sin registro)
                        $stmt2 = $db->prepare("
                            SELECT id, nombre, apellido, 'sin_usuario' AS tipo 
                            FROM medicos 
                            WHERE matricula = ?
                        ");
                        $stmt2->execute([$matricula]);
                        $medicoEncontrado = $stmt2->fetch(\PDO::FETCH_ASSOC);

                        if ($medicoEncontrado) {
                            // Verificar si ya está asociado
                            $check = $db->prepare("SELECT 1 FROM medico_paciente WHERE id_medico_sin_usuario = ? AND id_paciente_user = ?");
                            $check->execute([$medicoEncontrado['id'], $idPaciente]);

                            if ($check->fetch()) {
                                $error = "Médico encontrado: " . $medicoEncontrado['nombre'] . " " . $medicoEncontrado['apellido'] . ". Ya está en su lista.";
                                $medicoEncontrado = null;
                            } else {
                                $mostrarFormularioManual = false; // se encontró pero aún no asociado
                            }
                        } else {
                            $mostrarFormularioManual = true; // no se encontró en ninguna tabla
                        }
                    }

                } catch (\PDOException $e) {
                    $error = "Error al buscar médico: " . $e->getMessage();
                }
            }

            // Asociar médico registrado
            if (isset($_POST['asociar']) && isset($_POST['id_medico'])) {
                $idMedico = intval($_POST['id_medico']);

                try {
                    $check = $db->prepare("SELECT 1 FROM medico_paciente WHERE id_medico = ? AND id_paciente_user = ?");
                    $check->execute([$idMedico, $idPaciente]);

                    if ($check->fetch()) {
                        $error = "Este médico ya está en tu lista.";
                    } else {
                        $stmt = $db->prepare("INSERT INTO medico_paciente (id_medico, id_paciente_user) VALUES (?, ?)");
                        $stmt->execute([$idMedico, $idPaciente]);

                        header("Location: " . static::path() . "paciente/verMedicos");
                        exit();
                    }
                } catch (\PDOException $e) {
                    $error = "Error al asociar médico: " . $e->getMessage();
                }
            }

            // Crear médico sin usuario registrado
            if (isset($_POST['crear'])) {
                $nombre = trim($_POST['nombre']);
                $apellido = trim($_POST['apellido']);
                $matricula = trim($_POST['matricula']);
                $datos['matricula'] = $matricula;

                if (empty($nombre) || empty($apellido) || empty($matricula)) {
                    $error = "Todos los campos son obligatorios.";
                } else {
                    try {
                        // Verificar si la matrícula ya existe
                        $check = $db->prepare("SELECT 1 FROM medicos WHERE matricula = ?");
                        $check->execute([$matricula]);

                        if ($check->fetch()) {
                            $error = "La matrícula ya está registrada.";
                        } else {
                            // Insertar en tabla `medicos`
                            $stmt1 = $db->prepare("INSERT INTO medicos (nombre, apellido, matricula) VALUES (?, ?, ?)");
                            $stmt1->execute([$nombre, $apellido, $matricula]);
                            $idMedicoSinUsuario = $db->lastInsertId();

                            // Asociar al paciente usando `id_medico_sin_usuario`
                            $stmt2 = $db->prepare("INSERT INTO medico_paciente (id_medico_sin_usuario, id_paciente_user) VALUES (?, ?)");
                            $stmt2->execute([$idMedicoSinUsuario, $idPaciente]);

                            header("Location: " . static::path() . "paciente/verMedicos");
                            exit();
                        }
                    } catch (\PDOException $e) {
                        $error = "Error al crear y asociar médico: " . $e->getMessage();
                    }
                }
            }
        }

        Response::render($this->viewDir(__NAMESPACE__), "agregarMedico", [
            "title" => "Agregar Médico",
            "head" => SiteController::head(),
            "nav" => SiteController::nav(),
            "footer" => SiteController::footer(),
            "datos" => $datos,
            "medicoEncontrado" => $medicoEncontrado,
            "mostrarFormularioManual" => $mostrarFormularioManual,
            "error" => $error,
            "path" => static::path()
        ]);
    }

    public function actionVerHistorial($idMedico)
    {
        SessionController::redirigirSiNoAutenticado();

        if ($_SESSION['tipoUser'] !== 'paciente') {
            header("Location: " . static::path());
            exit();
        }

        $idPaciente = $_SESSION['id'];
        $db = \DataBase::connection();

        $medico = null;
        $anotaciones = '';

        try {
            //medcio es usuario registrado??
            $stmt = $db->prepare("
                SELECT u.id, u.nombre, u.apellido
                FROM medico_paciente mp
                JOIN usuarios u ON mp.id_medico = u.id
                WHERE mp.id_paciente_user = ? AND u.id = ?
            ");
            $stmt->execute([$idPaciente, $idMedico]);
            $medico = $stmt->fetch(\PDO::FETCH_ASSOC);

            if ($medico) {
                //anotaciones existe relación??
                $stmt = $db->prepare("SELECT anotaciones FROM medico_paciente WHERE id_paciente_user = ? AND id_medico = ?");
                $stmt->execute([$idPaciente, $idMedico]);
                $fila = $stmt->fetch(\PDO::FETCH_ASSOC);
                $anotaciones = $fila['anotaciones'] ?? '';
            } else {
                // es un médico sin registro??
                $stmt = $db->prepare("
                    SELECT m.id, m.nombre, m.apellido
                    FROM medico_paciente mp
                    JOIN medicos m ON mp.id_medico_sin_usuario = m.id
                    WHERE mp.id_paciente_user = ? AND m.id = ?
                ");
                $stmt->execute([$idPaciente, $idMedico]);
                $medico = $stmt->fetch(\PDO::FETCH_ASSOC);

                if ($medico) {
                    $stmt = $db->prepare("SELECT anotaciones FROM medico_paciente WHERE id_paciente_user = ? AND id_medico_sin_usuario = ?");
                    $stmt->execute([$idPaciente, $idMedico]);
                    $fila = $stmt->fetch(\PDO::FETCH_ASSOC);
                    $anotaciones = $fila['anotaciones'] ?? '';
                } else {
                    $_SESSION['msg'] = "Médico no encontrado o no asociado.";
                    header("Location: " . static::path() . "paciente/verMedicos");
                    exit();
                }
            }

        } catch (\PDOException $e) {
            $_SESSION['msg'] = "Error al cargar historial médico.";
            header("Location: " . static::path() . "paciente/verMedicos");
            exit();
        }

        Response::render($this->viewDir(__NAMESPACE__), "verHistorial", [
            "title" => "Historial Médico",
            "head" => SiteController::head(),
            "nav" => SiteController::nav(),
            "footer" => SiteController::footer(),
            "medico" => $medico,
            "anotaciones" => $anotaciones,
            "path" => static::path()
        ]);
    }

    public function actionEditarHistorial($idMedico)
    {
        SessionController::redirigirSiNoAutenticado();

        if ($_SESSION['tipoUser'] !== 'paciente') {
            header("Location: " . static::path());
            exit();
        }

        $idPaciente = $_SESSION['id'];
        $db = \DataBase::connection();

        $medico = null;
        $anotaciones = '';
        $error = '';

        try {
            // Buscar si es un médico registrado
            $stmt = $db->prepare("
                SELECT u.id, u.nombre, u.apellido
                FROM medico_paciente mp
                JOIN usuarios u ON mp.id_medico = u.id
                WHERE mp.id_paciente_user = ? AND u.id = ?
            ");
            $stmt->execute([$idPaciente, $idMedico]);
            $medico = $stmt->fetch(\PDO::FETCH_ASSOC);
            $esRegistrado = true;

            if (!$medico) {
                // Buscar si es un médico sin cuenta
                $stmt = $db->prepare("
                    SELECT m.id, m.nombre, m.apellido
                    FROM medico_paciente mp
                    JOIN medicos m ON mp.id_medico_sin_usuario = m.id
                    WHERE mp.id_paciente_user = ? AND m.id = ?
                ");
                $stmt->execute([$idPaciente, $idMedico]);
                $medico = $stmt->fetch(\PDO::FETCH_ASSOC);
                $esRegistrado = false;
            }

            if (!$medico) {
                $_SESSION['msg'] = "Médico no asociado al paciente.";
                header("Location: " . static::path() . "paciente/verMedicos");
                exit();
            }

            // Obtener anotaciones actuales
            if ($esRegistrado) {
                $stmt = $db->prepare("SELECT anotaciones FROM medico_paciente WHERE id_paciente_user = ? AND id_medico = ?");
                $stmt->execute([$idPaciente, $idMedico]);
            } else {
                $stmt = $db->prepare("SELECT anotaciones FROM medico_paciente WHERE id_paciente_user = ? AND id_medico_sin_usuario = ?");
                $stmt->execute([$idPaciente, $idMedico]);
            }
            $fila = $stmt->fetch(\PDO::FETCH_ASSOC);
            $anotaciones = $fila['anotaciones'] ?? '';

            // Procesar edición
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $nuevoTexto = trim($_POST['anotaciones'] ?? '');

                if (strlen($nuevoTexto) > 2000) {
                    $error = "El texto es demasiado largo.";
                } else {
                    if ($esRegistrado) {
                        $stmt = $db->prepare("UPDATE medico_paciente SET anotaciones = ? WHERE id_paciente_user = ? AND id_medico = ?");
                        $stmt->execute([$nuevoTexto, $idPaciente, $idMedico]);
                    } else {
                        $stmt = $db->prepare("UPDATE medico_paciente SET anotaciones = ? WHERE id_paciente_user = ? AND id_medico_sin_usuario = ?");
                        $stmt->execute([$nuevoTexto, $idPaciente, $idMedico]);
                    }

                    header("Location: " . static::path() . "paciente/verHistorial/$idMedico");
                    exit();
                }
            }

        } catch (\PDOException $e) {
            $error = "Error al editar el historial.";
        }

        Response::render($this->viewDir(__NAMESPACE__), "editarHistorial", [
            "title" => "Editar Historial",
            "head" => SiteController::head(),
            "nav" => SiteController::nav(),
            "footer" => SiteController::footer(),
            "medico" => $medico,
            "anotaciones" => $anotaciones,
            "error" => $error,
            "path" => static::path()
        ]);
    }

    public function actionEliminarMedico($id)
    {
        SessionController::redirigirSiNoAutenticado();

        if ($_SESSION['tipoUser'] !== 'paciente') {
            header("Location: " . static::path());
            exit();
        }

        $db = \DataBase::connection();
        $idPaciente = $_SESSION['id'];
        $error = null;
        $medico = null;

        try {
            // Buscar si es un médico sin usuario
            $stmt = $db->prepare("
                SELECT m.id, m.nombre, m.apellido, 1 as sin_usuario
                FROM medicos m
                JOIN medico_paciente mp ON mp.id_medico_sin_usuario = m.id
                WHERE m.id = ? AND mp.id_paciente_user = ?
                UNION
                SELECT u.id, u.nombre, u.apellido, 0 as sin_usuario
                FROM usuarios u
                JOIN medico_paciente mp ON mp.id_medico = u.id
                WHERE u.id = ? AND mp.id_paciente_user = ?
            ");
            $stmt->execute([$id, $idPaciente, $id, $idPaciente]);
            $medico = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$medico) {
                $_SESSION['msg'] = "Médico no encontrado.";
                header("Location: " . static::path() . "paciente/verMedicos");
                exit();
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmar'])) {
                // Eliminar relación
                $stmt = $db->prepare("
                    DELETE FROM medico_paciente 
                    WHERE (id_medico = :id OR id_medico_sin_usuario = :id)
                    AND id_paciente_user = :paciente
                ");
                $stmt->execute([
                    ':id' => $id,
                    ':paciente' => $idPaciente
                ]);

                // Si es un médico sin usuario, eliminar también de tabla medicos y matriculas
                if ($medico['sin_usuario'] == 1) {
                    $stmtDel1 = $db->prepare("DELETE FROM medicos WHERE id = ?");
                    $stmtDel1->execute([$id]);

                    $stmtDel2 = $db->prepare("DELETE FROM matriculas WHERE id_usuario = ?");
                    $stmtDel2->execute([$id]); // Solo si por error hubo una matrícula asociada
                }

                $_SESSION['msg'] = "Médico eliminado correctamente.";
                header("Location: " . static::path() . "paciente/verMedicos");
                exit();
            }

        } catch (\PDOException $e) {
            $error = "Error al intentar eliminar el médico: " . $e->getMessage();
        }

        Response::render($this->viewDir(__NAMESPACE__), "eliminarMedico", [
            "title" => "Eliminar Médico",
            "head" => SiteController::head(),
            "nav" => SiteController::nav(),
            "footer" => SiteController::footer(),
            "medico" => $medico,
            "error" => $error,
            "path" => static::path()
        ]);
    }

    public function actionArchivos()
    {
        SessionController::redirigirSiNoAutenticado();

        if ($_SESSION['tipoUser'] !== 'paciente') {
            header("Location: " . static::path());
            exit();
        }

        $db = \DataBase::connection();
        $idPaciente = $_SESSION['id'];
        $archivos = [];
        $error = '';

        $busqueda = $_GET['busqueda'] ?? '';

        try {
            if (!empty($busqueda)) {
                $stmt = $db->prepare("SELECT * FROM archivos_medicos WHERE id_paciente = ? AND nombre_original LIKE ?");
                $stmt->execute([$idPaciente, "%$busqueda%"]);
            } else {
                $stmt = $db->prepare("SELECT * FROM archivos_medicos WHERE id_paciente = ?");
                $stmt->execute([$idPaciente]);
            }

            $archivos = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            $error = "Error al obtener los archivos: " . $e->getMessage();
        }

        Response::render($this->viewDir(__NAMESPACE__), "archivos", [
            "title" => "Mis Archivos Médicos",
            "head" => SiteController::head(),
            "nav" => SiteController::nav(),
            "footer" => SiteController::footer(),
            "path" => static::path(),
            "archivos" => $archivos,
            "error" => $error
        ]);
    }

    public function actionSubirArchivo()
    {
        SessionController::redirigirSiNoAutenticado();

        if ($_SESSION['tipoUser'] !== 'paciente') {
            header("Location: " . static::path());
            exit();
        }

        $error = '';
        $success = '';
        $idPaciente = $_SESSION['id'];

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
                        $stmt = $db->prepare("INSERT INTO archivos_medicos (id_paciente, nombre_original, nombre_guardado, tipo) VALUES (?, ?, ?, ?)");
                        $stmt->execute([$idPaciente, $nombreOriginal, $nombreGuardado, $archivo['type']]);
                        $_SESSION['msg'] = "Archivo subido correctamente.";
                        header("Location: " . static::path() . "paciente/archivos");
                        exit();
                    } catch (\PDOException $e) {
                        $error = "Error al guardar el archivo en base de datos.";
                    }
                } else {
                    $error = "No se pudo guardar el archivo.";
                }
            }
        }

        Response::render($this->viewDir(__NAMESPACE__), "subirArchivo", [
            "title" => "Subir Archivo",
            "head" => SiteController::head(),
            "nav" => SiteController::nav(),
            "footer" => SiteController::footer(),
            "error" => $error,
            "path" => static::path()
        ]);
    }

    public function actionDescargarArchivo($id)
    {
        SessionController::redirigirSiNoAutenticado();

        $db = \DataBase::connection();
        $stmt = $db->prepare("SELECT * FROM archivos_medicos WHERE id = ? AND id_paciente = ?");
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

        $idPaciente = $_SESSION['id'];
        $db = \DataBase::connection();

        // Obtener el archivo
        $stmt = $db->prepare("SELECT * FROM archivos_medicos WHERE id = ? AND id_paciente = ?");
        $stmt->execute([$id, $idPaciente]);
        $archivo = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$archivo) {
            $_SESSION['msg'] = "Archivo no encontrado o no autorizado.";
            header("Location: " . static::path() . "paciente/archivos");
            exit();
        }

        // Mostrar confirmación
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Response::render($this->viewDir(__NAMESPACE__), "eliminarArchivo", [
                "archivo" => $archivo,
                "path" => static::path(),
                "title" => "Confirmar eliminación",
                "head" => SiteController::head(),
                "nav" => SiteController::nav(),
                "footer" => SiteController::footer()
            ]);
            return;
        }

        // Eliminar archivo físico
        $ruta = __DIR__ . '/../privado/archivos_medicos/' . $archivo['nombre_guardado'];
        if (file_exists($ruta)) {
            unlink($ruta);
        }

        // Eliminar de la base de datos
        $delStmt = $db->prepare("DELETE FROM archivos_medicos WHERE id = ? AND id_paciente = ?");
        $delStmt->execute([$id, $idPaciente]);

        $_SESSION['msg'] = "Archivo eliminado correctamente.";
        header("Location: " . static::path() . "paciente/archivos");
        exit();
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
