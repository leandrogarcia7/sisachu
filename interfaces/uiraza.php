<?php
require '../negocio/USUARIO.php';
require '../negocio/RAZA.php';
$usu= new USUARIO();
   session_start();
$raza = new RAZA();
require_once("../encabezado.php");   
?>

<html lang="es">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<link href="../css/style.css" rel="stylesheet" type="text/css"/>
<script src="../js/script.js"></script>
    <body>
        <div class="banner">
        <img src="../img/animales.png" alt="Banner de Animales">
    </div>
        <h2 class="titulointerface">RAZA</h2>
        
        
        
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
$raza->mostrarInicio();
//mostrar Crear Raza
IF (isset($_REQUEST['bttcrear'])){    $raza->mostrarCrear();}
//guardar los datos del nuevo
IF (isset($_REQUEST['bttnuevo'])){    $raza->nuevo($_REQUEST);}
//mostrar listado de razas
IF (isset($_REQUEST['bttbuscar'])){    $raza->buscar($_REQUEST['txtbuscar']);}
//mostrar para modificar
IF (isset($_REQUEST['bttsel'])){    $raza->mostrarModificar($_REQUEST['bttsel']);}
//guardar los cambios en el modificar
IF (isset($_REQUEST['bttmod'])){    $raza->Modificar($_REQUEST);}
//eiminar los datos
IF (isset($_REQUEST['btteli'])){    $raza->Eliminar($_REQUEST['btteli']);}
?>
        </body>
</html>