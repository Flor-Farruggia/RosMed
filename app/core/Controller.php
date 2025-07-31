<?php 
use app\models\UserModel;


class Controller
{
    protected $title = 'Ros Med ';
    protected static $sessionStatus;
    public static $ruta;

    public function actionIndex($var = null){
        $this->action404();
        // echo "funcionando";
    }

    // Obtiene el path base para la URL
    public static function path()
    {
        $reemplazar = str_replace('url=', '', $_SERVER['QUERY_STRING']);
        $camino =str_replace($reemplazar, '', $_SERVER["REQUEST_URI"]);
        self::$ruta = $camino;
        return self::$ruta;
    }

    protected function viewDir($nameSpace){
        $replace = array($nameSpace,'Controller');
        $viewDir = str_replace($replace , '', get_class($this)).'/';
        $viewDir = str_replace('\\', '', $viewDir);
        $viewDir = strtolower($viewDir);
        return $viewDir;
    }

    public function action404(){
        // echo "Error 404 - Página no encontrada - CONTROLLER";
        static::path();
        header('Location:'.self::$ruta.'404');
    }

    // Redireccion al login
    protected function redirigirLogin() {
        header("Location: " . static::path() . "login");
        exit();
    }

    // Genera un token de seguridad
    public static function generarToken($longitud = 32)
    {
        return bin2hex(random_bytes($longitud));
    }

    // Genera un token de seguridad simplificado
    protected static function tokenSeguro($longitud = 25)
    {
        return self::generarToken($longitud);
    }

    public static function validarCampo($campo, $min, $max, $campoName) {

        $error = false; //(false:no hay error, True: hay error)
        $msg = '';//(si existe)
        $campo2 = '';

        if (!isset($_POST[$campo])) {
            $msg = "No existe campo ".$campoName;
            $error = true;
        } else {
            $campo2 = trim($_POST[$campo]);
            if (empty($campo2)) {
                $msg = $campoName.' no puede estar vacío';
                $error = true;
            } else {
                if (strlen($campo2) < $min || strlen($campo2) > $max) {
                    $msg = 'Por favor ingrese entre '.$min.' y '.$max.' caracteres';
                    $error = true;
                }
            }
        }
        $result['msg'] =$msg;
        $result['error'] =$error;
        $result['campo2'] =$campo2;

        return $result;
    }

    public static function validarFechaNacimiento($campo, $minEdad, $maxEdad, $name = "fecha de nacimiento") {
        $msg = '';
        $error = false;
        $campo2 = '';
        $edad = 0;

        if (!isset($_POST[$campo])) {
            $msg = "El campo $name no existe";
            $error = true;
        } else {
            $campo2 = htmlspecialchars(trim($_POST[$campo]));

            if (empty($campo2)) {
                $msg = "La fecha no puede estar vacía";
                $error = true;
            } else {
                $partes = explode('-', $campo2);
                if (count($partes) === 3 && checkdate((int)$partes[1], (int)$partes[2], (int)$partes[0])) {

                    $fechaNacimiento = new DateTime($campo2);
                    $hoy = new DateTime();

                    // Fecha futura
                    if ($fechaNacimiento > $hoy) {
                        $msg = "La fecha no puede ser futura";
                        $error = true;
                    } else {
                        $intervalo = $fechaNacimiento->diff($hoy);
                        $edad = $intervalo->y;

                        if ($intervalo->days < 1) {
                            $msg = "Debe tener al menos 1 día de edad";
                            $error = true;
                        } elseif ($edad < $minEdad || $edad > $maxEdad) {
                            $msg = "La edad debe estar entre $minEdad y $maxEdad años (Edad calculada: $edad)";
                            $error = true;
                        }
                    }
                } else {
                    $msg = "Formato de fecha inválido. Use AAAA-MM-DD";
                    $error = true;
                }
            }
        }

        return [
            'msg' => $msg,
            'error' => $error,
            'campo2' => $campo2,
            'edad' => $edad,
        ];
    }

    public static function validarCheck($campo, $campoName, $array) {
        $error = '';
        $msg = '';
        $campo2 = '';
        if (!isset($_POST[$campo])) {
            $msg = "El campo ".$campoName." no existe";
            $error = true;
        } else {
            $campo2 = trim($_POST[$campo]);
            $campoValido = false;
            foreach ($array as $valid) {
                if ($campo2 === $valid) {
                    $campoValido = true;
                    break;
                }
            }
            if (!$campoValido) {
                $msg= "Debe seleccionar campo ".$campoName." válido";
                $error = true;
            }
        }
        $result['msg'] =$msg;
        $result['error'] =$error;
        $result['campo2'] =$campo2;

        return $result;

    }

    public static function validarEmail($campo, $min, $max, $campoName, $checkDuplicacion = false) {
        $msg = '';
        $error = false;
        $campo2 = '';

        $validar = self::validarCampo($campo, $min, $max, $campoName);

        $msg = $validar['msg'];
        $error = $validar['error'];
        $campo2 = $validar['campo2'];

        if (!$error) {
            if (!filter_var($campo2, FILTER_VALIDATE_EMAIL)) {
                $msg = 'Formato de ' . $campoName . ' inválido';
                $error = true;
            }

            if ($checkDuplicacion && UserModel::emailExists($campo2)) {
                $msg = 'Este email ya está registrado';
                $error = true;
            }
        }

        return ['msg' => $msg, 'error' => $error, 'campo2' => $campo2];
    }

    public static function validarCampoNum($campo, $min, $max, $name){

            $error=false;
            $msg='';
            $campo2='';

            if(!isset($_POST[$campo])){
                $error=true;
                $msg='El campo '.$name.' no existe';
            }else{
                $campo2=trim($_POST[$campo]);
                if(empty($campo2)){
                    $error=true;
                    $msg='El campo no puede estar vacío';
                }else{
                    if(strlen($campo2)< $min || strlen($campo2)> $max){
                        $error=true;
                        $msg='El campo debe tener entre '.$min.' y '.$max.' de caracteres';
                    } else {
                        if (!is_numeric($campo2)){
                            $error=true;
                            $msg='El campo debe ser solo caracteres numéricos';
                        }
                    }
                }
            }

        return ['msg' => $msg, 'error' => $error, 'campo2' => $campo2];
    }

    public static function getRecord($sql, $params=[]){
        $statement = static::connection()->prepare($sql);
        try {
            $statement->execute($params);
            $result=$statement->fetch(PDO::FETCH_OBJ);
        } catch (PDOException $e){
            echo '<pre>';
            var_dump($e);
            echo '</pre>';
            $result=false;
        }
        return $result;
    }

}
