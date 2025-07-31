<!DOCTYPE html>
<html lang="es">
	<head>
		<?=$head?>
		<title><?=$title?></title>
	</head>
	<body>
		<?=$nav?>
        <section class="bg-light section">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 col-lg-8">
                        <div class="d-flex flex-column justify-content-center mb-4">
                            <h1 class="text-xl text-primary mb-4">Registro:</h1>
                            <p class="text-medium regular text-dark mb-5">
                                Quieres registrarte, maravilloso, gracias por confiar en nosotros! Ahora vamos a eso!
                            </p>
                        </div>
                    </div>
                    <form action="" method="post" class="form row">
    
                        <div class="col-12 col-md-6 mt-3">
                            <div class="d-flex flex-column ">
                                <label for="nombre" class="text-start text-primary text-regular mb-3">Nombre:</label>
                                <input class="input-intern form-control" type="text" id="nombre" name="nombre" autofocus placeholder="Escriba su nombre" value="<?=$datos['nombre']?>">
                                <output class="col_12 msg_error "><?=$error['nombre']?></output>
                            </div>
                        </div>
    
                        <div class="col-12 col-md-6 mt-3">
                            <div class="d-flex flex-column ps-2">
                                <div class="d-flex flex-column">
                                    <label for="apellido" class="text-start text-primary text-regular mb-3">Apellido:</label>
                                    <input class="input-intern form-control" type="text" id="apellido" name="apellido" placeholder="Escriba su apellido" value="<?=$datos['apellido']?>">
                                    <output class="col_12 msg_error "><?=$error['apellido']?></output>
                                </div>
                            </div>
                        </div>
    
                        <div class="col-12 col-md-6 mt-3">
                            <div class="d-flex flex-column">
                                <label for="dni" class="text-start text-primary text-regular mb-3">DNI:</label>
                                <input class="input-intern form-control" type="text" id="dni" name="dni" autofocus 
                                placeholder="Escriba su documento" value="<?=$datos['dni']?>">
                                <output class="col_12 msg_error "><?=$error['dni']?></output>
                            </div>
                        </div>
    
                        <div class="col-12 col-md-6 mt-3">
                            <div class="d-flex flex-column">
                                <label for="fechaNac" class="text-start text-primary text-regular mb-3">Fecha de Nacimiento:</label>
                                <input class="input-intern form-control" type="date" id="fechaNac" name="fechaNac" 
                                placeholder="Escriba su fecha de nacimiento" value="<?=$datos['fechaNac']?>">
                                <output class="col_12 msg_error "><?=$error['fechaNac']?></output>
                            </div>
                        </div>
    
                        <div class="col-12 col-md-6 mt-3">
                            <div class="d-flex flex-column">
                                <label for="telefono" class="text-start text-primary text-regular mb-3">Teléfono:</label>
                                <input class="input-intern form-control" type="text" id="telefono" name="telefono" placeholder="Escriba su teléfono" value="<?=$datos['telefono']?>">
                                <output class="msg_error"><?=$error['telefono']?></output>
                            </div>
                        </div>
    
                        <div class="col-12 col-md-6 mt-3">
                            <div class="d-flex flex-column">
                                <label for="tipoUser" class="text-start text-primary text-regular mb-3">Tipo de Usuario:</label>
                                <select class="select form-control" id="tipoUser" name="tipoUser">
                                    <option value="placeholder" disabled>Seleccione el tipo de usuario:</option>
                                    <option value="paciente" <?= ($datos['tipoUser'] == 'paciente') ? 'selected' : '' ?>>Paciente</option>
                                    <option value="medico" <?= ($datos['tipoUser'] == 'medico') ? 'selected' : '' ?>>Médico</option>
                                </select>
                                <output class="col_12 msg_error "><?=$error['tipoUser']?></output>
                            </div>
                        </div>
    
                        <div class="col-12 col-md-6 mt-3">
                            <div class="d-flex flex-column">
                                <label for="email" class="text-start text-primary text-regular mb-3">E-mail:</label>
                                <input class="input-intern form-control" type="email" id="email" name="email" placeholder="Escriba su mail" value="<?=$datos['email']?>">
                                <output class="col_12 msg_error "><?=$error['email']?></output>
                            </div>
                        </div>
    
                        <div class="col-12 col-md-6 mt-3">
                            <div class="d-flex flex-column">
                                <label for="pass" class="text-start text-primary text-regular mb-3">Contraseña:</label>
                                <input class="form-control input-intern " type="password" id="pass" name="pass" placeholder="Escriba su contraseña">
                                <output class="col_12 msg_error "><?=$error['pass']?></output>
                            </div>
                        </div>
    
                        <div class="col-11 mt-3">
                            <div class="d-flex justify-content-center">
                                <button type="submit" class="btn btn-primary px-3 py-2" name="registrar">Registrar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        <?=$footer?>

    <script src="/public/js/main.js"></script>
	</body>
</html>