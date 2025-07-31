<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar contraseña</title>
    <link rel="stylesheet" href="/RosMed/public/css/main.css">
</head>
<body class="bg-light">
    <main class="section">
        <div class="container">
            <h1 class="text-primary mb-4">Cambiar contraseña de administrador</h1>

            <?php if (!empty($msg)) : ?>
                <div class="text-regular text-success mb-3"><?= htmlspecialchars($msg) ?></div>
            <?php endif; ?>

            <?php if (!empty($error['general'])) : ?>
                <div class="text-regular text-danger mt-3"><?= htmlspecialchars($error['general']) ?></div>
            <?php endif; ?>

            <form method="post" class="form">

                <div class="mb-3">
                    <label class="form-label text-regular">Nueva contraseña</label>
                    <input type="password" name="pass1" class="form-control" required>
                    <?php if (!empty($error['pass1'])) : ?>
                        <div class=" text-regular text-danger mt-3"><?= $error['pass1'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label class="form-label text-regular">Repetir contraseña</label>
                    <input type="password" name="pass2" class="form-control" required>
                    <?php if (!empty($error['pass2'])) : ?>
                        <div class=" text-regular text-danger mt-3"><?= $error['pass2'] ?></div>
                    <?php endif; ?>
                </div>

                <button type="submit" class="btn btn-success">Guardar nueva contraseña</button>
                <a href="<?= $path ?>admin/panel" class="btn btn-info">Volver</a>
            </form>
        </div>
    </main>
</body>
</html>
