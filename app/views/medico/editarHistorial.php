<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Historial</title>
    <link rel="stylesheet" href="/RosMed/public/css/main.css">
</head>
<body class="bg-light">

<main class="container py-5">
    <h1 class="text-primary mb-4">Editar historial de <?= htmlspecialchars($paciente['nombre'] . ' ' . $paciente['apellido']) ?></h1>

    <?php if ($error): ?>
        <div class="text-regular text-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-4">
            <label for="anotaciones" class="form-label text-medium text-secondary-two medium">Observaciones:</label>
            <textarea name="anotaciones" id="anotaciones" rows="10" class="form-control text-black text-regular mt-3"><?= htmlspecialchars($anotaciones ?? '') ?></textarea>
        </div>

        <button type="submit" class="btn btn-success me-2">Guardar cambios</button>
        <a href="<?= $path ?>medico/verPaciente/<?= $paciente['id'] ?>" class="btn btn-secondary">Cancelar</a>
    </form>
</main>

</body>
</html>
