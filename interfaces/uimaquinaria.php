<?php
require_once '../negocio/MAQUINARIA.php';
$usu = new USUARIO();
session_start();

$obj = new MAQUINARIA();
require_once("../encabezado.php"); 
?>

<html lang="es">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../js/script.js"></script>
  <body>
    <div class="banner">
      <img src="../img/rrhh.png" alt="Banner de Potreros">
    </div>  

    <?php
    if(isset($_SESSION['idhac'])){
        $usu->mostrarMenu(1,1);
    } else {
        header("Location: ../login.php");
        exit;
    }

    // Acciones de la interfaz
    if (isset($_REQUEST['bttcrear'])){ $obj->mostrarCrear();}
    if (isset($_REQUEST['bttnuevo'])){ $obj->nuevo($_REQUEST);}
    if (isset($_REQUEST['bttbuscar'])){ $obj->buscar($_REQUEST['txtbuscar']);}
    if (isset($_REQUEST['bttsel'])){ $obj->mostrarModificar($_REQUEST['bttsel']);}
    if (isset($_REQUEST['bttmod'])){ $obj->modificarMaquinaria($_REQUEST);}
    if (isset($_REQUEST['btteli'])){ $obj->eliminarMaquinaria($_REQUEST['btteli']);}

    $obj->mostrarInicio();
    ?>
  </body>
</html>
