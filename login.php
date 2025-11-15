<?php
// Punto de entrada principal para la autenticación.
// Si la interfaz de menú existe, la reutilizamos para evitar duplicar lógica.
$menuPath = __DIR__ . '/interfaces/menu.php';
if (is_file($menuPath)) {
    require $menuPath;
    return;
}

// Fallback sencillo en caso de que la interfaz no esté disponible.
session_start();
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Inicio de sesión</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
  </head>
  <body class="bg-light">
    <div class="container py-5">
      <div class="row justify-content-center">
        <div class="col-md-6">
          <div class="card shadow-sm">
            <div class="card-body text-center">
              <h1 class="h4 mb-3">Inicio de sesión</h1>
              <p class="text-muted mb-4">
                No se encontró la vista de menú. Contacte al administrador para restaurar el archivo
                <code>interfaces/menu.php</code>.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
