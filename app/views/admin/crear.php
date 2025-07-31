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

                <h1 class="mb-4">Crear nuevo usuario</h1>

                <?php if (!empty($error['general'])): ?>
                    <div class="alert alert-danger"><?= $error['general'] ?></div>
                <?php endif; ?>

                <form method="post" class="form row g-3">
                    <?php 
                        function input($name, $label, $datos, $error, $type = 'text') {
                        ?>
                            <div class="col-12 col-md-6 mt-3">
                                <div class="d-flex flex-column ps-2">
                                    <label class="form-label"><?= ucfirst($label) ?></label>
                                    <input type="<?= $type ?>" name="<?= $name ?>" class="form-control"
                                        value="<?= htmlspecialchars($datos[$name] ?? '') ?>">
                                    <?php if (!empty($error[$name])): ?>
                                        <div class="text-danger"><?= $error[$name] ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php } ?>

                    <?php input('nombre', 'Nombre', $datos, $error) ?>
                    <?php input('apellido', 'Apellido', $datos, $error) ?>
                    <?php input('dni', 'DNI', $datos, $error) ?>
                    <?php input('fechaNac', 'Fecha de Nacimiento', $datos, $error, 'date') ?>
                    <?php input('telefono', 'Teléfono', $datos, $error) ?>
                    <?php input('email', 'Email', $datos, $error) ?>
                    <?php input('pass', 'Contraseña', $datos, $error) ?>

                    <div class="col-md-6 mt-3">
                        <div class="d-flex flex-column ps-2">
                            <label class="form-label">Tipo de Usuario</label>
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

                    <div class="col-12 text-center mt-5">
                        <button type="submit" class="btn btn-success">Crear usuario</button>
                        <a href="/RosMed/admin/panel" class="btn btn-secondary">Cancelar</a>
                    </div>

                </form>
            </div>
        </div>
    </main>

</body>
</html>