<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Historial</title>
    <link rel="stylesheet" href="/RosMed/public/css/main.css">
</head>
<body class="bg-light">

<main class="py-5 bg-light" style="min-height: 60vh;">
    <div class="container">

        <h1 class="text-primary mb-5">Editar historial con el Dr. <?= htmlspecialchars($medico['nombre'] . ' ' . $medico['apellido']) ?></h1>

        <?php if (!empty($error)): ?>
            <div class="text-danger text-regular"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="mb-3">
                <label for="anotaciones" class="form-label text-primary x-medium text-regular">Anotaciones del historial:</label>
                <textarea name="anotaciones" id="anotaciones" class="form-control text-regular mt-4" rows="8" placeholder="Ingrese o edite las anotaciones"><?= htmlspecialchars($anotaciones ?? '') ?></textarea>
            </div>
            <button type="submit" class="btn btn-success">Guardar cambios</button>
            <a href="<?= $path ?>paciente/verHistorial/<?= $medico['id'] ?>" class="btn btn-danger">Cancelar</a>
        </form>

    </div>
</main>

</body>
</html>
