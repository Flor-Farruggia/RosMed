<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel="stylesheet" href="/RosMed/public/css/main.css">

</head>
<body class="bg-light">

    <main class="section bg-light" style="min-height: 55vh;">
        <div class="container">
            <div class="mb-4 d-flex justify-content-between align-items-center">
                <h2 class="text-primary text-medium">Resultados de la Búsqueda</h2>
                <a href="/RosMed/admin/panel" class="btn btn-secondary">Volver al Panel</a>
            </div>

            <form method="get" action="/RosMed/admin/buscar" class="d-flex gap-2 mb-4">
                <input type="text" name="buscar" value="<?= htmlspecialchars($filtro) ?>" class="form-control w-50" placeholder="Buscar por apellido o DNI">
                <select name="tipoUser" class="form-control w-auto">
                    <option value="">-- Filtrar por tipo --</option>
                    <option value="medico" <?= $tipoUser == 'medico' ? 'selected' : '' ?>>Médico</option>
                    <option value="paciente" <?= $tipoUser == 'paciente' ? 'selected' : '' ?>>Paciente</option>
                </select>
                <button type="submit" class="btn btn-primary ms-3">Buscar</button>
            </form>

            <?php if (empty($usuarios)): ?>
                <p class="text-muted">No se encontraron usuarios con esos criterios.</p>
            <?php else: ?>
                <div class="table-responsiv">
                    <table class="responsive-table w-100">
                        <thead class="bg-primary text-white">
                        <tr>
                            <th class="p-2 text-start">Nombre</th>
                            <th class="p-2 text-start">Apellido</th>
                            <th class="p-2 text-start">DNI</th>
                            <th class="p-2 text-start">Tipo</th>
                            <th class="p-2 text-start">Email</th>
                            <th class="p-2 text-start">Teléfono</th>
                            <th class="p-2 text-start">Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($usuarios as $usuario): ?>
                            <tr>
                                <td data-title="Nombre" class="p-2"><?= htmlspecialchars($usuario->nombre) ?></td>
                                <td data-title="Apellido" class="p-2"><?= htmlspecialchars($usuario->apellido) ?></td>
                                <td data-title="DNI" class="p-2"><?= htmlspecialchars($usuario->dni) ?></td>
                                <td data-title="Tipo" class="p-2"><?= isset($usuario->tipoUser) ? htmlspecialchars($usuario->tipoUser) : '<span class="text-danger">Sin tipo</span>' ?></td>
                                <td data-title="Email" class="p-2"><?= htmlspecialchars($usuario->email) ?></td>
                                <td data-title="Teléfono"class="p-2"><?= htmlspecialchars($usuario->telefono) ?></td>
                                <td class="p-2">
                                    <a href="/RosMed/admin/editar/<?= $usuario->id ?>" class="btn btn-warning btn-sm">Editar</a>
                                    <a href="/RosMed/admin/eliminar/<?= $usuario->id ?>" class="btn btn-danger btn-sm">Eliminar</a>
                                </td>
                            </tr>
                        <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            <?php endif ?>
        </div>
    </main>

</body>
</html>
