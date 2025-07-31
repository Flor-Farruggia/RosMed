<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Médico</title>
    <link rel="stylesheet" href="/RosMed/public/css/main.css">
</head>
<body class="bg-light">

<main class="bg-light py-5">
    <div class="container">

        <h2 class="text-primary mb-4">Agregar Médico</h2>

        <!-- Buscar por matrícula -->
        <form method="post" class="mb-4">
            <div class="mb-3">
                <label for="matricula" class="form-label text-regular">Matrícula del médico</label>
                <input type="text" name="matricula" id="matricula" class="form-control text-regular" required value="<?= htmlspecialchars($datos['matricula'] ?? '') ?>">
            </div>
            <button type="submit" name="buscar" class="btn btn-secondary">Buscar Médico</button>
        </form>

        <?php if (!empty($medicoEncontrado)): ?>
            <div class="text-regular x-medium text-success mb-3">
                Médico encontrado: <span class="text-regular regular text-dark"><?= htmlspecialchars($medicoEncontrado['nombre']) . ' ' . htmlspecialchars($medicoEncontrado['apellido']) ?></span>
            </div>
            <form method="post">
                <input type="hidden" name="id_medico" value="<?= $medicoEncontrado['id'] ?>">
                <button type="submit" name="asociar" class="btn btn-success">Asociar Médico</button>
            </form>


        <?php elseif ($mostrarFormularioManual): ?>
            <div class="text-regular mb-3">No se encontró un médico con esa matrícula. Ingrese los datos para agregarlo.</div>
            <form method="post">
                <div class="mb-3">
                    <input type="text" name="nombre" class="form-control text-regular" placeholder="Nombre" required>
                </div>
                <div class="mb-3">
                    <input type="text" name="apellido" class="form-control text-regular" placeholder="Apellido" required>
                </div>
                <input type="hidden" name="matricula" value="<?= htmlspecialchars($datos['matricula']) ?>">
                <button type="submit" name="crear" class="btn btn-success">Crear y Asociar Médico</button>
            </form>
        <?php endif; ?>


        <?php if (!empty($error)): ?>
            <div class="text-danger text-regular mt-3"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <a href="<?= $path ?>paciente/verMedicos" class="btn btn-primary mt-4">Volver a la lista</a>

    </div>
</main>

</body>
</html>
