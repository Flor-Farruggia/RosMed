<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="/RosMed/public/css/main.css">
</head>
<body class="bg-light">

<main class="section bg-light">
    <div class="container">
        <div class="mb-4 d-flex flex-column justify-content-center align-items-center">
            <h1 class="mb-4">Editar usuario</h1>

            <?php if (!empty($error['general'])): ?>
                <div class="alert alert-danger"><?= $error['general'] ?></div>
            <?php endif; ?>

            <form method="post" class="form row" action="<?= Controller::path() ?>admin/editar/<?= $id ?>">

                <!-- Nombre -->
                <div class="col-12 col-md-6 mt-3">
                    <div class="d-flex flex-column ps-2">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="nombre" class="form-control mt-2" 
                               value="<?= htmlspecialchars($datos['nombre'] ?? '') ?>">
                        <?php if (!empty($error['nombre'])): ?>
                            <div class="text-danger"><?= $error['nombre'] ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Apellido -->
                <div class="col-12 col-md-6 mt-3">
                    <div class="d-flex flex-column ps-2 ">
                        <label class="form-label mb-2">Apellido</label>
                        <input type="text" name="apellido" class="form-control" 
                            value="<?= htmlspecialchars($datos['apellido'] ?? '') ?>">
                        <?php if (!empty($error['apellido'])): ?>
                            <div class="text-danger"><?= $error['apellido'] ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- DNI -->
                <div class="col-12 col-md-6 mt-3">
                    <div class="d-flex flex-column ps-2">
                        <label class="form-label mb-2">DNI</label>
                        <input type="text" name="dni" class="form-control mt-2" 
                            value="<?= htmlspecialchars($datos['dni'] ?? '') ?>">
                        <?php if (!empty($error['dni'])): ?>
                            <div class="text-danger"><?= $error['dni'] ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Fecha de Nacimiento -->
                <div class="col-12 col-md-6 mt-3">
                    <div class="d-flex flex-column ps-2">
                        <label class="form-label mb-2">Fecha de Nacimiento</label>
                        <input type="date" name="fechaNac" class="form-control" 
                            value="<?= htmlspecialchars($datos['fechaNac'] ?? '') ?>">
                        <?php if (!empty($error['fechaNac'])): ?>
                            <div class="text-danger"><?= $error['fechaNac'] ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Teléfono -->
                <div class="col-12 col-md-6 mt-3">
                    <div class="d-flex flex-column ps-2">
                        <label class="form-label mb-2">Teléfono</label>
                        <input type="text" name="telefono" class="form-control" 
                            value="<?= htmlspecialchars($datos['telefono'] ?? '') ?>">
                        <?php if (!empty($error['telefono'])): ?>
                            <div class="text-danger"><?= $error['telefono'] ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Email -->
                <div class="col-12 col-md-6 mt-3">
                    <div class="d-flex flex-column ps-2">
                        <label class="form-label mb-2">Email</label>
                        <input type="email" name="email" class="form-control" 
                            value="<?= htmlspecialchars($datos['email'] ?? '') ?>">
                        <?php if (!empty($error['email'])): ?>
                            <div class="text-danger"><?= $error['email'] ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Contraseña -->
                <div class="col-12 col-md-6 mt-3">
                    <div class="d-flex flex-column ps-2">
                        <label class="form-label mb-2">Contraseña (si desea cambiarla)</label>
                        <input type="password" name="pass" class="form-control">
                        <?php if (!empty($error['pass'])): ?>
                            <div class="text-danger"><?= $error['pass'] ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Tipo de Usuario -->
                <div class="col-12 col-md-6 mt-3">
                    <div class="d-flex flex-column ps-2">
                        <label class="form-label mb-2">Tipo de Usuario</label>
                        <select name="tipoUser" class="form-control">
                            <option value="">-- Seleccionar --</option>
                            <option value="paciente" <?= ($datos['tipoUser'] ?? '') === 'paciente' ? 'selected' : '' ?>>Paciente</option>
                            <option value="medico" <?= ($datos['tipoUser'] ?? '') === 'medico' ? 'selected' : '' ?>>Médico</option>
                        </select>
                        <?php if (!empty($error['tipoUser'])): ?>
                            <div class="text-danger"><?= $error['tipoUser'] ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Botones -->
                <div class="col-12 text-center mt-5">
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    <a href="<?= Controller::path() ?>admin/panel" class="btn btn-secondary">Cancelar</a>
                </div>

            </form>

        </div>
    </div>
</main>

</body>
</html>
