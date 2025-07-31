<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial Clínica</title>
    <link rel="stylesheet" href="/RosMed/public/css/main.css">
</head>
<body class="bg-light">

<main class="container py-5" style="min-height: 60vh;">

    <h1 class="text-primary mb-4">Historial de <?= htmlspecialchars($paciente['nombre']) ?> <?= htmlspecialchars($paciente['apellido']) ?></h1>

    <?php if (!empty($anotaciones)) : ?>
        <div class="bg-white border rounded-3 p-4 shadow-sm mb-4">
            <h4 class="text-secondary-two mb-3">Anotaciones:</h4>
            <p class="text-dark text-regular"><?= nl2br(htmlspecialchars($anotaciones)) ?></p>
        </div>
    <?php else : ?>
        <div class="text-warning x-medium text-regular mb-5">>><span class="text-primary">Este paciente no tiene anotaciones aún.</span><<</div>
    <?php endif; ?>

    <div class="d-flex gap-3">
        <a href="<?= $path ?>medico/editarHistorial/<?= $paciente['id'] ?>" class="btn btn-warning me-3">Editar Historial</a>
        <a href="<?= $path ?>medico/listaPacientes" class="btn btn-secondary">Volver a la lista</a>
    </div>

</main>

</body>
</html>
