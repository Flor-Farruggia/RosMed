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
                <p class="text-regular x-medium text-white mb-0">Tecnología que acompaña tu salud y tu trabajo</p>
            </div>
        </div>
    </nav>

    <main class="bg-light py-5" style="min-height: 55vh;">
        <div class="container">
            <h1 class="text-primary mb-4">Bienvenido, <?= htmlspecialchars($_SESSION['nombre']) ?></h1>

            <div class="mb-4">
                <h4 class="text-secondary-two mb-3">Datos personales</h4>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="<?= $path ?>medico/editar/nombre" class="btn btn btn-secondary me-3">Editar</a>
                        <strong>Nombre:</strong> <?= htmlspecialchars($usuario->nombre) ?>
                    </li>
                    <li class="mb-2">
                        <a href="<?= $path ?>medico/editar/apellido" class="btn btn btn-secondary me-3">Editar</a>
                        <strong>Apellido:</strong> <?= htmlspecialchars($usuario->apellido) ?>
                    </li>
                    <li class="mb-2">
                        <a href="<?= $path ?>medico/editar/dni" class="btn btn btn-secondary me-3">Editar</a>
                        <strong>DNI:</strong> <?= htmlspecialchars($usuario->dni) ?>
                    </li>
                    <li class="mb-2">
                        <a href="<?= $path ?>medico/editar/fechaNac" class="btn btn btn-secondary me-3">Editar</a>
                        <strong>Fecha de Nacimiento:</strong> <?= htmlspecialchars($usuario->fechaNac) ?>
                    </li>
                    <li class="mb-2">
                        <a href="<?= $path ?>medico/editar/telefono" class="btn btn btn-secondary me-3">Editar</a>
                        <strong>Teléfono:</strong> <?= htmlspecialchars($usuario->telefono) ?>
                    </li>
                    <li class="mb-2">
                        <a href="<?= $path ?>medico/editar/email" class="btn btn btn-secondary me-3">Editar</a>
                        <strong>Email:</strong> <?= htmlspecialchars($usuario->email) ?>
                    </li>
                    <li class="mb-2">
                        <a href="<?= $path ?>medico/editar/pass" class="btn btn btn-secondary me-3">Editar</a>
                        <strong>Contraseña:</strong> *****
                    </li>
                    <li class="mb-2">
                        <a href="<?= $path ?>medico/editar/matricula" class="btn btn btn-secondary me-3">Editar</a>
                        <strong>Matrícula:</strong> 
                        <?php if (empty($matricula)): ?>
                            <span class="text-danger bold">[Incorpore su matrícula para habilitar todas sus funciones]</span>
                        <?php else: ?>
                            <?= htmlspecialchars($matricula) ?>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
            
            <div class="row">
                <div class="col-12 col-lg-2">
                    <?php if (empty($matricula)): ?>
                        <button class="btn btn-disable" disabled title="Deesabilitado sin matricula">Mis pacientes</button>
                    <?php else: ?>
                        <a href="<?= $path ?>medico/listaPacientes" class="btn btn-secondary">Mis pacientes</a>
                    <?php endif; ?>
                </div>
                <div class="col-12 col-lg-2">
                    <a href="<?= $path ?>medico/archivos" class="btn btn-primary">Mis archivos</a>
                </div>
                <div class="col-12 col-lg-2">
                    <a href="<?= $path ?>medico/verMedicos" class="btn btn-info">Mis médicos</a>
                </div>
                <div class="col-12 col-lg-2">
                    <a href="<?= $path ?>medico/eliminarPerfil" class="btn btn-danger btn-eliminar-perfil">Eliminar mi perfil</a>
                </div>
                    
            </div>
                
            <a href="/RosMed/logout/logout" class="btn btn-logout border border-danger text-danger mt-4">Cerrar sesión</a>
        </div>
    </main>

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
