<?php
require '../negocio/USUARIO.php';
require '../negocio/GRUPO.php';

$usu = new USUARIO();
$usu= new USUARIO();
   session_start();
$grupo = new GRUPO();

session_start();

if (!isset($_SESSION['idhac'])) {
    header("Location: ../login.php");
    exit;
}

require_once("../encabezado.php");

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestión de grupos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet" type="text/css"/>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/script.js"></script>
</head>
<body class="bg-light">
<main class="container-fluid py-4">
    <div class="row justify-content-center mb-4">
        <div class="col-12 col-lg-10">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center text-md-start">
                    <figure class="banner mb-3">
                        <img class="img-fluid rounded-3" src="../img/animales.png" alt="Banner de Animales">
                    </figure>
                    <h1 class="titulointerface h3 mb-0">Gestión de grupos</h1>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-4 justify-content-center">
        <aside class="col-12 col-lg-3">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-body p-3">
                    <?php
                    if (isset($_SESSION['idhac'])) {
                        $usu->mostrarMenu(1, 1);
                    }
                    ?>
                </div>
            </div>
        </aside>
        <section class="col-12 col-lg-7 col-xl-6">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <?php
                    $grupo->mostrarInicio();

                    if (isset($_REQUEST['btteliasi'])) {
                        $grupo->eliminarasignarAnimalGrupo($_REQUEST['btteliasi']);
                        $grupo->mostrarModificar($_REQUEST['idgru']);
                    }

                    if (isset($_REQUEST['bttasigru'])) {
                        $grupo->asignarAnimalGrupo($_REQUEST['idgru'], $_REQUEST['idani']);
                        $grupo->mostrarModificar($_REQUEST['idgru']);
                    }

                    if (isset($_REQUEST['bttcrear'])) {
                        $grupo->mostrarCrear();
                    }

                    if (isset($_REQUEST['bttnuevo'])) {
                        $grupo->nuevo($_REQUEST);
                    }

                    if (isset($_REQUEST['bttbuscar'])) {
                        $grupo->buscarGrupo($_REQUEST['txtbuscar']);
                    }

                    if (isset($_REQUEST['bttsel'])) {
                        $grupo->mostrarModificar($_REQUEST['bttsel']);
                    }

                    if (isset($_REQUEST['bttmod'])) {
                        $grupo->Modificar($_REQUEST);
                    }

                    if (isset($_REQUEST['btteli'])) {
                        $grupo->Eliminar($_REQUEST['btteli']);
                    }
                    ?>
                </div>
            </div>
        </section>
    </div>
</main>
</body>
</html>

