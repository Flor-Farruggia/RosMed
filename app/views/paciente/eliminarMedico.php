<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Médico</title>
    <link rel="stylesheet" href="/RosMed/public/css/main.css">
</head>
<body class="bg-light">

<main class="py-5 bg-light">
    <div class="container">

        <h1 class="text-danger mb-4">¿Desea eliminar al médico <?= htmlspecialchars($medico['nombre'] . ' ' . $medico['apellido']) ?> de su lista?</h1>

        <form method="post">
            <button type="submit" name="confirmar" class="btn btn-danger">Eliminar</button>
            <a href="<?= $path ?>paciente/verMedicos" class="btn btn-secondary">Cancelar</a>
        </form>

        <?php if (!empty($error)): ?>
            <div class="text-regular text-danger mt-3"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

    </div>
</main>

</body>
</html>
