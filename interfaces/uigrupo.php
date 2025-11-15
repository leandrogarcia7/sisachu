<?php
require '../negocio/USUARIO.php';
require '../negocio/GRUPO.php';
<<<<<<< HEAD
$usu= new USUARIO();
   session_start();
$grupo = new GRUPO();

?>

<html lang="es">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<link href="../css/style.css" rel="stylesheet" type="text/css"/>
 <script src="../js/script.js"></script>   <body>
    <div class="banner">
        <img src="../img/animales.png" alt="Banner de Animales">
    </div>
     <h2 class="titulointerface">GRUPO</h2>
        
        
        
<?php

//echo "<table style='width:90%'><td style='width:40%'>";
if(isset($_SESSION['idhac'])){
     $usu-> mostrarMenu(1,1);
}else{
  header("Location: ../login.php");
exit;
}
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//echo "</td><td>";
//mostrar las opciones de raza en botones animados
$grupo->mostrarInicio();
//mostrar Crear Raza

//btteliasi
IF (isset($_REQUEST['btteliasi'])){    $grupo->eliminarasignarAnimalGrupo($_REQUEST['btteliasi']);$grupo->mostrarModificar($_REQUEST['idgru']);}


IF (isset($_REQUEST['bttasigru'])){    $grupo->asignarAnimalGrupo($_REQUEST['idgru'],$_REQUEST['idani']);$grupo->mostrarModificar($_REQUEST['idgru']);}

IF (isset($_REQUEST['bttcrear'])){    $grupo->mostrarCrear();}
//guardar los datos del nuevo
IF (isset($_REQUEST['bttnuevo'])){    $grupo->nuevo($_REQUEST);}
//mostrar listado de razas
IF (isset($_REQUEST['bttbuscar'])){    $grupo->buscarGrupo($_REQUEST['txtbuscar']);}
//mostrar para modificar
IF (isset($_REQUEST['bttsel'])){    $grupo->mostrarModificar($_REQUEST['bttsel']);}
//guardar los cambios en el modificar
IF (isset($_REQUEST['bttmod'])){    $grupo->Modificar($_REQUEST);}
//eiminar los datos
IF (isset($_REQUEST['btteli'])){    $grupo->Eliminar($_REQUEST['btteli']);}
?>
        </body>
</html>
=======

$usu = new USUARIO();
$grupo = new GRUPO();

session_start();

if (!isset($_SESSION['idhac'])) {
    header("Location: ../login.php");
    exit;
}

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
<div class="container-fluid py-4">
    <div class="row justify-content-center mb-4">
        <div class="col-12 col-lg-10">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center text-md-start">
                    <div class="banner mb-3">
                        <img class="img-fluid rounded-3" src="../img/animales.png" alt="Banner de Animales">
                    </div>
                    <h1 class="titulointerface h3 mb-0">Gestión de grupos</h1>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-4 justify-content-center">
        <div class="col-12 col-lg-3">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-body p-3">
                    <?php
                    if (isset($_SESSION['idhac'])) {
                        $usu->mostrarMenu(1, 1);
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-7">
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
        </div>
    </div>
</div>
</body>
</html>
>>>>>>> 874e0ab04d19a92fa5cb3e836e0d41d3e0266fbe
