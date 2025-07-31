<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="/RosMed/public/css/main.css">
     <title><?= $title ?></title>

</head>
<body class="bg-light">

<main class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-6">
            <div class="d-flex flex-column align-items-center">

                <h1 class="mb-4 text-danger">Eliminar Usuario</h1>

                <?php if (!empty($datos)) : ?>
                    <div class="alert alert-warning">
                        <p>¿Estás seguro de que querés eliminar al usuario <strong><?= htmlspecialchars($datos['nombre'] . ' ' . $datos['apellido']) ?></strong> (DNI: <?= htmlspecialchars($datos['dni']) ?>)?</p>
                    </div>

                    <form method="post" action="<?= Controller::path() ?>/admin/eliminar/<?= $id ?>">
                        <div class="d-flex gap-2">
                            <a href="/RosMed/admin/panel" class="btn btn-secondary me-3">Cancelar</a>
                            <button type="submit" name="confirmar" class="btn btn-danger">Eliminar</button>
                        </div>
                    </form>
                <?php else : ?>
                    <div class="alert alert-danger">
                        <p>Usuario no encontrado.</p>
                        <a href="/RosMed/admin/panel" class="btn btn-primary">Volver al panel</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

</body>
</html>
