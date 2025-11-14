<?php
require '../negocio/USUARIO.php';
require '../negocio/PROVEEDOR.php';
$usu= new USUARIO();
   session_start();
if(isset($_SESSION['idhac'])){
     $usu-> mostrarMenu(1,1);
}else{
  header("Location: ../login.php");
exit;
}

?>

<html lang="es">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<link href="../css/style.css" rel="stylesheet" type="text/css"/>
<script src="../js/script.js"></script>
<body>
    <div class="banner">
        <img src="../img/rrhh.png" alt="Banner de Animales">
    </div>   
       <h2 class="titulointerface">PROVEEDOR</h2>
<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$obj= new PROVEEDOR();
//echo "</td><td>";
//mostrar las opciones de raza en botones animados
$obj->mostrarInicio();
//mostrar Crear Raza
IF (isset($_REQUEST['bttcrear'])){    $obj->mostrarCrear();}
//guardar los datos del nuevo
IF (isset($_REQUEST['bttnuevo'])){    $obj->nuevo($_REQUEST);}
//mostrar listado de razas
IF (isset($_REQUEST['bttbuscar'])){    $obj->buscar($_REQUEST['txtbuscar']);}
//mostrar para modificar
IF (isset($_REQUEST['bttsel'])){    $obj->mostrarModificar($_REQUEST['bttsel']);}
//guardar los cambios en el modificar
IF (isset($_REQUEST['bttmod'])){    $obj->Modificar($_REQUEST);}
//eiminar los datos
IF (isset($_REQUEST['btteli'])){    $obj->Eliminar($_REQUEST['btteli']);}



?>
        </body>
</html>