<?php
namespace app\controllers;
use \Controller;
use \Response;
use \DataBase;
use app\models\UserModel;

class RegistroController extends Controller
{
	public function actionIndex($var = null){
	    $error['nombre']='';
	    $error['apellido']='';
	    $error['dni']='';
	    $error['fechaNac']='';
	    $error['telefono']='';
	    $error['tipoUser']='';
	    $error['email']='';
	    $error['pass']='';

	    $datos['nombre']='';
	    $datos['apellido']='';
	    $datos['dni']='';
	    $datos['fechaNac']='';
	    $datos['telefono']='';
	    $datos['tipoUser']='';
	    $datos['email']='';
	    $datos['pass']='';
        $status = false;

        if (isset($_POST['registrar'])) {
            /**INICIO VALIDACION CAMPOS**/
			#VALIDACIONES NOMBRE ##
			$valNombre = Controller::validarCampo('nombre', 3, 100, 'nombre');

			if ($valNombre['error']==true) {
				$error['nombre'] = $valNombre['msg'];
				$status = true;
			} else {
				$datos['nombre']= $valNombre['campo2'];
			}
			#FINAL validaciones NOMBRE ##
			#VALIDACION APELLIDO ##
			$valApellido= Controller::validarCampo('apellido', 3, 100, 'apellido');

			if ($valApellido['error']==true) {
				$error['apellido'] = $valApellido['msg'];
				$status = true;
			} else {
				$datos['apellido']= $valApellido['campo2'];
			}
			#FINAL validaciones NOMBRE ##

			#VALIDACION DNI
			$valDni = Controller::validarCampoNum('dni', 7, 10, 'dni');
			
			if ($valDni['error']==true) {
				$error['dni'] = $valDni['msg'];
				$status = true;
			} else {
				$datos['dni']= $valDni['campo2'];
			}

			#FINAL validaciones DNI

			#VALIDACION FECHA NACIMIENTO 

			$valFecha = Controller::validarFechaNacimiento('fechaNac', 0, 100);

			if ($valFecha['error']) {
				$error['fechaNac'] = $valFecha['msg'];
				$status = true;
			} else {
				$datos['fechaNac'] = $valFecha['campo2'];
			}
			#FINAL validaciones FECHA NACIMIENTO 
			##VALIDACION TIPO USER
			$campoValTipoUser = array ('paciente', 'medico');

			$valTipoUser = Controller::validarCheck('tipoUser', 'tipo de usuario', $campoValTipoUser);
			
			if ( $valTipoUser['error']==true) {
				$error ['tipoUser']=  $valTipoUser['msg'];
				$status = true;
			} else {
				$datos['tipoUser']=  $valTipoUser['campo2'];
			}
			#FINAL validaciones TIPO USER
			##VALIDACION TELEFONO
			$valTel = Controller::validarCampo('telefono', 7, 15, 'teléfono');
			
			if ($valTel['error']==true) {
				$error['telefono'] = $valTel['msg'];
				$status = true;
			} else {
				$datos['telefono']= $valTel['campo2'];
			}
			#FINAL validaciones TELEFONO
			##VALIDACION EMAIL
            $checkEmail = Controller::validarEmail('email', 5, 100, 'e-mail');
            if($checkEmail['error']==true){
                $error['email']=$checkEmail['msg'];
				$status = true;
            }else {
				$datos['email']= $checkEmail['campo2'];
			}
            /**FIN VALIDACION CAMPO EMAIL**/
            /**INICIO VALIDACION CAMPO PASS**/
            $checkPass = Controller::validarCampo('pass', 5, 10, 'contraseña');
			if ($checkPass['error'] == true) {
				$error['pass'] = $checkPass['msg'];
				$status = true;
			} else {
				$datos['pass'] = password_hash($checkPass['campo2'], PASSWORD_DEFAULT);
			}
            /**FIN VALIDACION CAMPO PASS**/

			##check registro##
			if ($status == false) {
				// Guardar datos en la base de datos
				$userCreated = UserModel::createUser($datos);
				if ($userCreated) {
					$userCreated = UserModel::findEmail($datos['email']);

					// Iniciar sesión automáticamente tras el registro
					session_start();
					session_regenerate_id(true);

					$_SESSION['id']        = $userCreated->id;
					$_SESSION['nombre']    = $userCreated->nombre;
					$_SESSION['email']     = $userCreated->email;
					$_SESSION['tipoUser']  = $userCreated->tipoUser;

					// Redirigir según tipo de usuario
					switch ($userCreated->tipoUser) {
						case 'medico':
							header("Location:". Controller::path() ."medico/index");
							break;
						case 'paciente':
							header("Location:". Controller::path() ."paciente/index");
							break;
						default:
							header("Location: /");
							break;
					}
					exit;
				} else {
					echo "Error al registrar el usuario";
				}
			}

        } else{
        }

        //recursos de vistas
            $head = SiteController::head();
            $nav = SiteController::nav();
            $footer = SiteController::footer();
            $path = static::path();
        //fin --- rescursos de vista

        //carga datos de vista
        $datosVista =["title" => 'RosMed',"head" => $head,"nav" => $nav,'footer' => $footer,
        "error"=>$error, "datos"=>$datos];
        //carga
        Response::render($this->viewDir(__NAMESPACE__),"index", $datosVista);
	}
}