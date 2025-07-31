<!DOCTYPE html>
<html lang="es">
<head>
    <?= $head ?>
    <title><?= $title ?></title>
</head>
<body class="bg-light">
    <main class="section bg-light">
        <div class="container">
            <div class="mb-4 d-flex justify-content-between align-items-center">
            <h1 class="text-primary x-medium">Panel de Administración</h1>
            <a href="/RosMed/admin/crear" class="btn btn-success">+ Nuevo Usuario</a>
            </div>

            <form method="get" action="/RosMed/admin/buscar" class="d-flex gap-2 mb-4">
                <input type="text" name="buscar" class="form-control w-50" placeholder="Buscar por apellido o DNI">
                <select name="tipoUser" class="form-control w-auto  me-3">
                    <option value="">-- Filtrar por tipo --</option>
                    <option value="medico">Médico</option>
                    <option value="paciente">Paciente</option>
                </select>
                <button type="submit" class="btn btn-primary">Buscar</button>
            </form>

            <div class="table-responsiv">
                <table class="responsive-table w-100">
                    <thead class="bg-primary text-white">
                    <tr>
                        <th class="p-2 text-start x-medium">Nombre</th>
                        <th class="p-2 text-start x-medium">Apellido</th>
                        <th class="p-2 text-start x-medium">DNI</th>
                        <th class="p-2 text-start x-medium">Tipo</th>
                        <th class="p-2 text-start x-medium">Email</th>
                        <th class="p-2 text-start x-medium">Teléfono</th>
                        <!-- <th class="p-2 text-start x-medium">Estado</th> -->
                        <th class="p-2 text-start x-medium">Acciones</th>
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
                            <td data-title="Teléfono" class="p-2"><?= htmlspecialchars($usuario->telefono) ?></td>
                            <!-- <td class="p-2">
                                <?php if (!isset($usuario->activo)): ?>
                                    <span class="text-muted">Sin registro</span>
                                <?php elseif ($usuario->activo): ?>
                                    <span class="text-success">Activo</span>
                                <?php else: ?>
                                    <span class="text-danger">Inactivo</span>
                                <?php endif; ?>
                            </td> -->
                            <td data-title="Acciones" class="p-2">
                                <a href="/RosMed/admin/editar/<?= $usuario->id ?>" class="btn btn-warning">Editar</a>
                                <a href="/RosMed/admin/eliminar/<?= $usuario->id ?>" class="btn btn-danger">Eliminar</a>
                                <a href="/RosMed/admin/toggleActivo/<?= $usuario->id ?>" class="btn btn-<?= ($usuario->activo ?? null) ? 'desactivar' : 'activar' ?>"><?= ($usuario->activo ?? null) ? 'Dar de baja' : 'Activar' ?></a>
                            </td>
                        </tr>
                    <?php endforeach ?>
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="/RosMed/logout/logout" class="btn btn-logout border border-danger text-danger mt-4">Cerrar sesión</a>

                <a href="<?= Controller::path() ?>admin/cambioCredencial" class="btn btn-info mt-4">Cambio de contraseña</a>
            </div>
        </div>
    </main>
</body>
</html>
