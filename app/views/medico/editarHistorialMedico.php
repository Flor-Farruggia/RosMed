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
            <h1 class="mb-4 text-primary">Editar historial médico con el Dr. <?= htmlspecialchars($medico['nombre'] . ' ' . $medico['apellido']) ?></h1>

            <?php if ($error): ?>
                <div class="text-regular text-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="post">
                <div class="mb-4 d-flex flex-column">
                    <label for="anotaciones" class="form-label text-medium text-secondary-two medium mb-3">Observaciones:</label>
                    <textarea name="anotaciones" id="anotaciones" rows="10" class="form-control text-black text-regular mt-3"><?= htmlspecialchars($anotaciones ?? '') ?></textarea>
                </div>

                <button type="submit" class="btn btn-success me-2">Guardar cambios</button>
                <a href="<?= $path ?>medico/verMedicos" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </main>

</body>
</html>