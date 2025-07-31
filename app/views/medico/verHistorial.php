<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Médicos</title>
    <link rel="stylesheet" href="/RosMed/public/css/main.css">
</head>
<body class="bg-light">

    <main class="section bg-light">
        <div class="container">

            <h1 class="text-primary mb-4">Historial con el Dr. <?= htmlspecialchars($medico['nombre'] . ' ' . $medico['apellido']) ?></h1>

            <!-- Anotaciones médicas -->
            <div class="card shadow-sm border rounded p-4 mb-4 ms-0">
                <?php if (!empty($anotaciones)) : ?>
                    <p class="text-dark text-regular"><?= nl2br(htmlspecialchars($anotaciones)) ?></p>
                <?php else: ?>
                    <p class="text-primary text-regular">No hay anotaciones médicas registradas todavía.</p>
                <?php endif; ?>
            </div>

            <!-- Botones de acción -->
            <div class="d-flex">
                <a href="<?= $path ?>medico/editarHistorialMedico/<?= $idMedico ?>" class="btn btn-warning me-3">Editar historial</a>
                <a href="<?= $path ?>medico/verMedicos" class="btn btn-secondary">Volver</a>
            </div>

        </div>
    </main>

</body>
</html>