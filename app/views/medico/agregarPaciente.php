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

                <h2 class="text-primary mb-4">Agregar Paciente</h2>
                
                <form method="post" class="mb-4">
                    <div class="mb-3">
                        <label for="dni" class="form-label">DNI del paciente</label>
                        <input type="text" name="dni" id="dni" class="form-control" required value="<?= htmlspecialchars($datos['dni'] ?? '') ?>">
                    </div>
                    <button type="submit" name="buscar" class="btn btn-secondary">Buscar paciente</button>
                    <a href="<?= $path ?>medico/listaPacientes" class="btn btn-primary">Volver</a>
                </form>
                
                <?php if (!empty($usuarioEncontrado)) : ?>

                    <div class="alert alert-success text-success x-medium">Paciente encontrado: <span class="text-black regular"><?= $usuarioEncontrado['nombre'] ?> <?= $usuarioEncontrado['apellido'] ?></span></div>
                    <form method="post">
                        <input type="hidden" name="id_usuario" value="<?= $usuarioEncontrado['id'] ?>">
                        <input type="hidden" name="dni" value="<?= htmlspecialchars($datos['dni']) ?>">
                        <button type="submit" name="asociar" class="btn btn-success">Asociar a mi lista</button>
                    </form>

                <?php elseif ($mostrarFormularioManual) : ?>
                    <form method="post">
                        <h4 class="mt-4 text-danger x-medium">Paciente no registrado. Cargar datos:</h4>
                        <div class="mb-3"><input name="nombre" class="form-control" placeholder="Nombre" required></div>
                        <div class="mb-3"><input name="apellido" class="form-control" placeholder="Apellido" required></div>
                        <input type="hidden" name="dni" value="<?= htmlspecialchars($datos['dni']) ?>">
                        <button type="submit" name="crear" class="btn btn-success">Agregar Paciente</button>
                    </form>
                <?php elseif (isset($error)) : ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

            </div>

        </main>

    </body>
</html>
