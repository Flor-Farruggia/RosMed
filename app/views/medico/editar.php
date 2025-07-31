<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php
        echo match ($campo) {
            'pass' => 'Editar Contraseña',
            'fechaNac' => 'Editar Fecha de Nacimiento',
            default => 'Editar ' . ucfirst($campo)
        };
        ?>
    </title>
    <link rel="stylesheet" href="/RosMed/public/css/main.css">
</head>
<body class="bg-light">

<main class="section bg-light">
    <div class="container">
        <h1 class="text-primary mb-4">
            <?php
            echo match ($campo) {
                'pass' => 'Editar Contraseña',
                'fechaNac' => 'Editar Fecha de Nacimiento',
                default => 'Editar ' . ucfirst($campo)
            };
            ?>
        </h1>

        <form method="POST" class="form-group d-flex flex-column gap-3 col-md-6">

            <label for="valor" class="text-primary text-regular mb-3">
                <?php
                echo match ($campo) {
                    'pass' => 'Contraseña',
                    'fechaNac' => 'Fecha de Nacimiento',
                    default => ucfirst($campo)
                };
                ?>
            </label>

            <?php if ($campo === 'pass'): ?>
                <input 
                    type="password" 
                    name="pass" 
                    id="pass" 
                    placeholder="Ingrese nueva contraseña" 
                    class="form-control"
                    minlength="8"
                    maxlength="20"
                    autocomplete="new-password"
                    required
                >
            <?php elseif ($campo === 'fechaNac'): ?>
                <input 
                    type="date" 
                    name="fechaNac" 
                    id="fechaNac" 
                    value="<?= htmlspecialchars($valor) ?>" 
                    class="form-control"
                    required
                >
            <?php else: ?>
                <input 
                    type="text" 
                    name="<?= $campo ?>" 
                    id="<?= $campo ?>" 
                    value="<?= htmlspecialchars($valor) ?>" 
                    class="form-control"
                    required
                >
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <div class="text-danger text-regular mt-3"><?= $error ?></div>
            <?php endif; ?>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary me-3">Guardar</button>
                <a href="<?= $path ?>medico" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</main>

</body>
</html>
