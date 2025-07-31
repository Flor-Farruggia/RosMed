<!DOCTYPE html>
<html lang="es">
<head>
    <?= $head ?>
    <title><?= $title ?></title>
</head>
<body class="bg-light">
    <?=$nav?>
    <main class="section min-form">
        <div class="container">
            <div class="d-flex justify-content-center">
                <div class="d-flex flex-column align-items-center">
                    <h1 class="text-center text-primary">Ingreso de Admin</h1>
                    <form method="POST" action="<?= Controller::path() ?>admin/index" class="form d-flex flex-column">
    
                        <label for="email" class="text-regular text-start text-primary mb-2">Usuario</label>
                        <input class="form-control" type="usuario" id="usuario" name="usuario" required minlength="3" placeholder="Ingrese su usuario"
                                value="<?= htmlspecialchars($datos['usuario'] ?? '') ?>" autofocus>
                        <output class="msg_error"><?= $error['usuario'] ?? '' ?></output>
    
                        <label for="pass" class="text-regular text-start text-primary mb-2">Contraseña:</label>
                        <input class="form-control" type="password" id="pass" name="pass" required minlength="6" maxlength="10" placeholder="Ingrese su contraseña">
                        <output class="msg_error"><?= $error['pass'] ?? '' ?></output>
    
                        
                        <!-- Botón para enviar el formulario -->
                        <button type="submit" class="btnAdmin text-center btn btn-primary mt-3" name="adminLog">Ingresar</button>
                        
                        <!-- Error general -->
                        <?php if (!empty($error['general'])): ?>
                            <div class="alert alert-danger mt-2"><?= $error['general'] ?></div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </main>
    
    <?= $footer ?>

</body>
</html>
