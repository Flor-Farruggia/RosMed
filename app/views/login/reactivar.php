<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cuenta dada de baja</title>
  <link rel="stylesheet" href="/RosMed/public/css/main.css">
</head>
<body class="bg-light">

<main class="container py-5">
  <h2 class="text-center text-danger mb-4">Usuario dado de baja</h2>

  <p class="text-center">El usuario <strong><?= htmlspecialchars($user->email) ?></strong> fue dado de baja.</p>
  <p class="text-center">¿Deseás reactivar la cuenta?</p>

  <div class="text-center mt-4">
    <form method="post" action="<?= $path ?>login/reactivarCuenta">
      <input type="hidden" name="id" value="<?= $user->id ?>">
      <input type="hidden" name="email" value="<?= htmlspecialchars($user->email) ?>">
      <input type="hidden" name="tipoUser" value="<?= $user->tipoUser ?>">
      <button type="submit" class="btn btn-success">Sí, reactivar</button>
      <a href="<?= $path ?>login" class="btn btn-secondary">No, volver</a>
    </form>
  </div>
</main>

</body>
</html>
