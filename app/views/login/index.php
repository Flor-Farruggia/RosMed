<!DOCTYPE html>
<html lang="es">
<head>
    <?= $head ?>
    <title><?= $title ?></title>
</head>
<body>
    <?= $nav ?>

    <main class="bg-light section min-form">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-6">
                    <div class="d-flex flex-column justify-content-center align-items-center mb-4">
                        <h1 class="text-center text-primary bold mb-4">Bienvenido!</h1>
                        <p class="text-start text-regular text-primary w-75 mb-4">Por favor, rellena el siguiente formulario con tus datos para ingresar como usuario.</p>
                        <form method="POST" action="<?= Controller::path() ?>login/index" class="form d-flex flex-column align-items-center">
                            <div class="d-flex flex-column ">
                                <label for="email" class="text-start text-primary text-regular mb-3">E-mail:</label>
                                <input class="form-control" type="email" id="email" name="email" placeholder="Ingrese su email"
                                        value="<?= htmlspecialchars($datos['email'] ?? '') ?>" autofocus>
                                <output class="msg_error"><?= $error['email'] ?? '' ?></output>
                            </div>
                            <div class="d-flex flex-column ">
                                <label for="pass" class="text-start text-primary text-regular mb-3">Contraseña:</label>
                                <input class="form-control" type="password" id="pass" name="pass" placeholder="Ingrese su contraseña">
                                <output class="msg_error"><?= $error['pass'] ?? '' ?></output>
                            </div>
                
                            <p class="text-secondary text-regular">
                                ¿No tienes un usuario todavía? ¡Vamos a crear tu cuenta! >>
                                <a href="<?= Controller::path() ?>registro" class=" rg text-secondary x-medium text-regular">Registrarme</a>
                            </p>
                
                            <!-- Botón para enviar el formulario -->
                            <button type="submit" class="btnLog btn btn-primary mt-3 w-50 mx-auto" name="login">Ingresar</button>
                
                            <!-- Error general -->
                            <?php if (!empty($error['general'])): ?>
                                <div class="text-danger text-regular mt-2"><?= $error['general'] ?></div>
                            <?php endif; ?>
                        </form>
                        <a href="<?= Controller::path() ?>admin" class="btn btn-logout mt-2 border border-2 border-danger px-3 py-2">Ingreso Admin</a>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!empty($mostrarModal) && $user): ?>
            <div id="modalReactivar" class="modal" style="display: none;">
            <div class="modal-contenido">
                <h4 class="text-danger">Cuenta dada de baja</h4>
                <p class="text-regular">El usuario <strong><?= htmlspecialchars($user->email) ?></strong> fue dado de baja.</p>
                <p class="text-regular">¿Deseás reactivar la cuenta?</p>
                <form id="formReactivar" method="post" action="<?= $path ?>login/reactivarCuenta">
                <input type="hidden" name="id" value="<?= $user->id ?>">
                <input type="hidden" name="email" value="<?= htmlspecialchars($user->email) ?>">
                <input type="hidden" name="tipoUser" value="<?= $user->tipoUser ?>">
                <div class="modal-botones">
                    <button type="submit" class="btn btn-success">Sí, reactivar</button>
                    <button type="button" id="cancelarModal" class="btn btn-secondary">No</button>
                </div>
                </form>
            </div>
            </div>
        <?php endif; ?>

    </main>

    <?= $footer ?>

    <script src="/RosMed/public/js/main.js"></script>
    <script src="/RosMed/public/js/jquery.js"></script>
    <script>
        $(document).ready(function() {
            <?php if (!empty($mostrarModal)): ?>
                $('#modalReactivar').fadeIn();
            <?php endif; ?>

            $('#cancelarModal').click(function() {
                $('#modalReactivar').fadeOut();
            });
        });
    </script>

</body>
</html>
