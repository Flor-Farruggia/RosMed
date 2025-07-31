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
            <h1 class="text-primary mb-4">Mis Médicos</h1>
            <form method="get" class="mb-4">
                <input type="text" name="matricula" placeholder="Buscar por matrícula" class="form-control" value="<?= htmlspecialchars($_GET['matricula'] ?? '') ?>">
                <button type="submit" class="btn btn-primary">Buscar</button>
            </form>

            <div class="mb-4">
                <a class="btn btn-success" href="/RosMed/medico/agregarMedico">Agregar médico +</a>
            </div>

            <?php if (!empty($medicos)): ?>
                <div class="table-responsiv mt-4">
                    <table class="responsive-table w-100">
                        <thead class="table-light">
                            <tr class="bg-secondary">
                                <th class="text-center text-regular text-white x-medium p-3">Nombre</th>
                                <th class="text-center text-regular text-white x-medium p-3">Apellido</th>
                                <th class="text-center text-regular text-white x-medium p-3">Matrícula</th>
                                <th class="text-center text-regular text-white x-medium p-3">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($medicos as $medico): ?>
                            <tr >
                                <td data-title="Nombre" class="text-center text-regular p-2"><?= htmlspecialchars($medico['nombre']) ?></td>
                                <td data-title="Apellido" class="text-center text-regular p-2"><?= htmlspecialchars($medico['apellido']) ?></td>
                                <td data-title="Matricula" class="text-center text-regular p-2"><?= htmlspecialchars($medico['matricula']) ?></td>
                                <td class="text-center text-regular p-2">
                                    <a href="<?= $path ?>medico/verHistorial/<?= $medico['id'] ?>" class="btn btn-info">Ver historial</a>
                                    <a href="<?= $path ?>medico/eliminarMedico/<?= $medico['id'] ?>" class="btn btn-danger">Eliminar</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p>No hay médicos asociados.</p>
            <?php endif; ?>

            <a href="/RosMed/medico" class="btn btn-secondary">Volver</a>
        </div>
    </main>

</body>
</html>