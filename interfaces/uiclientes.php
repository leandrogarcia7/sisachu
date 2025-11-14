<?php
require '../negocio/CLIENTE.php';
$usu= new USUARIO();
   session_start();



$obj=new CLIENTE();
require_once("../encabezado.php"); 
?>

<html lang="es">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
 <script src="../js/script.js"></script>   <body>
   <div class="banner">
        <img src="../img/rrhh.png" alt="Banner de Animales">
    </div>  
<?php

//echo "<table style='width:90%'><td style='width:40%'>";
if(isset($_SESSION['idhac'])){
     $usu-> mostrarMenu(1,1);
}else{
  header("Location: ../login.php");
exit;
}


//echo "</td><td>";
//mostrar las opciones de raza en botones animados
//$empleados->mostrarInicio();
//mostrar Crear Raza

//btteliasi
//IF (isset($_REQUEST['btteliasi'])){    $grupo->eliminarasignarAnimalGrupo($_REQUEST['btteliasi']);$grupo->mostrarModificar($_REQUEST['idgru']);}


//IF (isset($_REQUEST['bttasigru'])){    $grupo->asignarAnimalGrupo($_REQUEST['idgru'],$_REQUEST['idani']);$grupo->mostrarModificar($_REQUEST['idgru']);}

IF (isset($_REQUEST['bttcrear'])){    $obj->mostrarCrear();}
//guardar los datos del nuevo
IF (isset($_REQUEST['bttnuevo'])){    $obj->nuevo($_REQUEST);}
//mostrar listado de razas
IF (isset($_REQUEST['bttbuscar'])){    $obj->buscar($_REQUEST['txtbuscar']);}
//mostrar para modificar
IF (isset($_REQUEST['bttsel'])){    $obj->mostrarModificar($_REQUEST['bttsel']);}
//guardar los cambios en el modificar
IF (isset($_REQUEST['bttmod'])){    $obj->modificarCliente($_REQUEST);}
//eiminar los datos
IF (isset($_REQUEST['btteli'])){    $obj->eliminarCliente($_REQUEST['btteli']);}

$obj->mostrarInicio();
?>
        </body>
</html>