<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Archivos Médicos</title>
    <link rel="stylesheet" href="/RosMed/public/css/main.css">
</head>
<body class="bg-light">

<main class="bg-light py-5">
    <div class="container">
        <h1 class="text-primary mb-4">Mis Archivos Médicos</h1>

        <?php if (!empty($error)): ?>
            <div class="text-danger text-regular"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="get" class="mb-4">
            <div class="input-group">
                <input type="text" name="busqueda" class="form-control text-regular" placeholder="Buscar por nombre..." value="<?= htmlspecialchars($_GET['busqueda'] ?? '') ?>">
                <button type="submit" class="btn btn-primary">Buscar</button>
            </div>
        </form>

        <form action="<?= $path ?>paciente/subirArchivo" method="post" enctype="multipart/form-data" class="mb-4">
            <div class="mb-3 input-file">
                <label for="archivo" class="input-file-label text-regular x-medium">Seleccionar archivo</label>
                <input type="file" name="archivo" id="archivo" accept=".pdf,.jpg,.jpeg,.png" required>
                <span id="archivo-nombre" class="text-regular x-medium">Ningún archivo seleccionado</span>
            </div>
            <h6 class="text-danger mb-4">Tener en cuenta que solo se aceptan archivo de formato .pdf, .png y .jpg</h6>
            <button type="submit" class="btn btn-success">Subir Archivo</button>
        </form>

        <?php if (!empty($archivos)): ?>
            <table class="responsive-table w-100">
                <thead class="table-light">
                    <tr class="bg-secondary">
                        <th class="text-center text-regular text-white x-medium p-3">Nombre Original</th>
                        <th class="text-center text-regular text-white x-medium p-3">Tipo</th>
                        <th class="text-center text-regular text-white x-medium p-3">Fecha de Subida</th>
                        <th class="text-center text-regular text-white x-medium p-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($archivos as $archivo): ?>
                        <tr class="">
                            <td class="text-center text-regular p-2"><?= htmlspecialchars($archivo['nombre_original']) ?></td>
                            <td class="text-center text-regular p-2"><?= htmlspecialchars($archivo['tipo']) ?></td>
                            <td class="text-center text-regular p-2"><?= htmlspecialchars($archivo['fecha_subida']) ?></td>
                            <td  class="text-center text-regular p-2">
                                <a href="<?= $path ?>paciente/descargarArchivo/<?= $archivo['id'] ?>" class="btn  btn-primary" target="_blank">Descargar</a>
                                <a href="#" class="btn btn-danger btn-eliminar" data-url="<?= $path ?>paciente/eliminarArchivo/<?= $archivo['id'] ?>">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No se encontraron archivos.</p>
        <?php endif; ?>

        <a href="/RosMed/paciente" class="btn btn-secondary mt-3">Volver</a>
    </div>

    <!-- Modal de Confirmación -->
    <div id="modalEliminar" class="modal">
    <div class="modal-contenido">
        <h4>Confirmar Eliminación</h4>
        <p>¿Estás seguro de que querés eliminar este archivo?</p>
        <div class="modal-botones">
        <form id="formEliminarArchivo" method="post" action="">
            <button type="submit" class="btn btn-danger">Sí, eliminar</button>
            <button type="button" class="btn btn-secondary" id="cancelarModal">Cancelar</button>
        </form>
        </div>
    </div>
    </div>

</main>

<script src="/RosMed/public/js/jquery.js"></script>
<script>
$(document).ready(function() {
  $('.btn-eliminar').click(function(e) {
    e.preventDefault();
    const url = $(this).data('url');
    $('#formEliminarArchivo').attr('action', url);
    $('#modalEliminar').fadeIn();
  });

  $('#cancelarModal').click(function() {
    $('#modalEliminar').fadeOut();
    $('#formEliminarArchivo').attr('action', '');
  });
});
</script>

<script>
$(document).ready(function(){
  $('#archivo').change(function(){
    const nombre = this.files.length > 0 ? this.files[0].name : 'Ningún archivo seleccionado';
    $('#archivo-nombre').text(nombre);
  });

  $('.custom-file-label').click(function() {
    $('#archivo').trigger('click');
  });
});
</script>



</body>
</html>
