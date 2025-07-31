<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil</title>
    <link rel="stylesheet" href="/RosMed/public/css/main.css">
</head>
<body class="bg-light">
    <nav class="bg-primary">
        <div class="container">
            <div class="row align-items-center py-4 pt-5">
                <p class="text-rm-nav x-medium text-white mb-0 pe-4">RosMed</p>
                <a href="<?= $path ?>">
                    <img src="<?= $path ?>img/logoRosMed.png" class="logo-nav pe-4">
                </a>
                <p class="text-regular x-medium text-white mb-0">Tecnología que acompaña tu salud</p>
            </div>
        </div>
    </nav>

    <main class="section bg-light min-55">
        <div class="container">
            <h1 class="text-primary mb-4">Bienvenido, <?= htmlspecialchars($_SESSION['nombre']) ?></h1>

            <div class="mb-4">
                <h4 class="text-secondary-two mb-3">Datos personales</h4>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="<?= $path ?>paciente/editar/nombre" class="btn btn btn-secondary me-3">Editar</a>
                        <span class="x-medium">Nombre:</span> <?= htmlspecialchars($usuario->nombre) ?>
                    </li>
                    <li class="mb-2">
                        <a href="<?= $path ?>paciente/editar/apellido" class="btn btn btn-secondary me-3">Editar</a>
                        <span class="x-medium">Apellido:</span> <?= htmlspecialchars($usuario->apellido) ?>
                    </li>
                    <li class="mb-2">
                        <a href="<?= $path ?>paciente/editar/dni" class="btn btn btn-secondary me-3">Editar</a>
                        <span class="x-medium">DNI:</span> <?= htmlspecialchars($usuario->dni) ?>
                    </li>
                    <li class="mb-2">
                        <a href="<?= $path ?>paciente/editar/fechaNac" class="btn btn btn-secondary me-3">Editar</a>
                        <span class="x-medium">Fecha de Nacimiento:</span> <?= htmlspecialchars($usuario->fechaNac) ?>
                    </li>
                    <li class="mb-2">
                        <a href="<?= $path ?>paciente/editar/telefono" class="btn btn btn-secondary me-3">Editar</a>
                        <span class="x-medium">Teléfono:</span> <?= htmlspecialchars($usuario->telefono) ?>
                    </li>
                    <li class="mb-2">
                        <a href="<?= $path ?>paciente/editar/email" class="btn btn btn-secondary me-3">Editar</a>
                        <span class="x-medium">Email:</span> <?= htmlspecialchars($usuario->email) ?>
                    </li>
                    <li class="mb-2">
                        <a href="<?= $path ?>paciente/editar/pass" class="btn btn btn-secondary me-3">Editar</a>
                        <span class="x-medium">Contraseña:</span> *****
                    </li>
                </ul>
            </div>

            <div class="row">
                <div class="col-12 col-lg-3">
                    <a href="<?= $path ?>paciente/archivos" class="btn btn-success">Mis archivos médicos</a>
                </div>
                <div class="col-12 col-lg-2">
                    <a href="<?= $path ?>/paciente/verMedicos" class="btn btn-primary">Mis médicos</a>
                </div>
                <div class="col-12 col-lg-3">
                    <a href="#" class="btn btn-danger btn-eliminar-perfil">Eliminar Perfil</a>
                </div>

                <div id="modalEliminarPerfil" class="modal">
                    <div class="modal-contenido">
                        <h4 class="text-danger x-medium">Confirmar Eliminación</h4>
                        <p class="text-regular text-danger">¿Estás segura de que querés eliminar tu perfil?</p>
                        <div class="modal-botones">
                        <form id="formEliminarPerfil" method="post" action="<?= $path ?><?= $_SESSION['tipoUser'] ?>/eliminarPerfil">
                            <button type="submit" class="btn btn-danger">Sí, eliminar</button>
                            <button type="button" class="btn btn-secondary" id="cancelarModalPerfil">Cancelar</button>
                        </form>
                        </div>
                    </div>
                </div>

            </div>

            <a href="/RosMed/logout/logout" class="btn btn-logout border border-danger text-danger mt-4">Cerrar sesión</a>
        </div>
    </main>

    <script src="/RosMed/public/js/jquery.js"></script>
    <script>
        $(document).ready(function() {
            $('.btn-eliminar-perfil').click(function(e) {
                e.preventDefault();
                $('#modalEliminarPerfil').fadeIn();
            });

            $('#cancelarModalPerfil').click(function() {
                $('#modalEliminarPerfil').fadeOut();
            });
        });
    </script>



</body>
</html>
