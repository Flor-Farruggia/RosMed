<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Pacientes</title>
    <link rel="stylesheet" href="/RosMed/public/css/main.css">
</head>
<body class="bg-light">

    <main class="bg-light py-5">
        <div class="container">
            <h1 class="text-primary mb-4">Mis Pacientes</h1>

            <!-- Filtro por DNI -->
            <form method="get" class="mb-4">
                <div class="input-group">
                    <input type="text" name="dni" class="form-control" placeholder="Buscar por DNI" value="<?= htmlspecialchars($_GET['dni'] ?? '') ?>">
                    <button type="submit" class="btn btn-primary">Buscar</button>
                </div>
            </form>

            <!-- Botón agregar paciente -->
            <div class="mb-4">
                <a href="/RosMed/medico/agregarPaciente" class="btn btn-success">Agregar Paciente</a>
            </div>

            <!-- Tabla de pacientes -->
            <?php if (!empty($pacientes)) : ?>
            <div class="table-responsiv">
                <table class="responsive-table w-100">
                    <thead class="table-light">
                        <tr class="bg-secondary">
                            <th class="text-center text-regular text-white x-medium p-3">Nombre</th>
                            <th class="text-center text-regular text-white x-medium p-3">Apellido</th>
                            <th class="text-center text-regular text-white x-medium p-3">DNI</th>
                            <th class="text-center text-regular text-white x-medium p-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pacientes as $p) : ?>
                        <tr>
                            <td data-title="Nombre" class="text-center text-regular p-2"><?= htmlspecialchars($p['nombre']) ?></td>
                            <td data-title="Apellido" class="text-center text-regular p-2"><?= htmlspecialchars($p['apellido']) ?></td>
                            <td data-title="DNI" class="text-center text-regular p-2"><?= htmlspecialchars($p['dni']) ?></td>
                            <td class="text-center text-regular p-2">
                                <a href="<?= $path ?>medico/verPaciente/<?= $p['id'] ?>" class="btn btn-info me-1">Ver historia</a>
                                <a href="<?= $path ?>medico/eliminarPaciente/<?= $p['id'] ?>" class="btn btn-danger">Eliminar</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else : ?>
                <p>No se encontraron pacientes.</p>
            <?php endif; ?>

            <a href="/RosMed/medico/" class="btn btn-secondary">Volver</a>

        </div>
    </main>

</body>
</html>
