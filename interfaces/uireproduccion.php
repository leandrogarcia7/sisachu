<?php
require_once("../negocio/ANIMALES.php");
require_once("../negocio/USUARIO.php");
include_once("../PHP_XLSX/xlsxwriter.class.php");
require_once("../negocio/REPRODUCCION.php");
 session_start();
 require('../fpdf/fpdf.php');
 require_once("../encabezado.php");   
$obj = new REPRODUCCION(); 
/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */
?>
<html lang="es" >
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
          <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/script.js"></script>
   <title>SISTEMA DE HACIENDAS</title>
    </HEAD>
    <body>
    <div class="banner">
        <img src="../img/animales.png" alt="Banner de Animales">
    </div>
        
        
        <?php
$usu= new USUARIO();
//echo "<table style='width:90%'><td style='width:40%'>";
if(isset($_SESSION['idhac'])){
     $usu-> mostrarMenu(1,1);
}else{
  header("Location: ../login.php");
exit;
}

echo '<center><h2 class="titulointerface">REPRODUCCION</h2></center>';
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//echo "</td><td>";
//mostrar las opciones de raza en botones animados
$obj->mostrarInicio();
//mostrar las opciones de raza en botones animados

//echo "<center><table style='width:90%'>";

IF (isset($_REQUEST['bttani'])){    $obj->mostrarCrear($_REQUEST['bttani']); /*echo "<tr><td>"; */}

//IF (isset($_REQUEST['bttmodani'])){    $control->modificarAnimal($_REQUEST);  $control->mostrarControlesAnimal($_REQUEST['id']);echo "<tr><td>"; }
IF (isset($_REQUEST['bttcrear'])){    $obj->crearReproduccion($_REQUEST);  $obj->mostrarCrear($_REQUEST['idmadre']);/*echo "<tr><td>"; */}
IF (isset($_REQUEST['bttbusani'])){    $obj->listarAnimalesReproduccion($_REQUEST['nombre']);/*echo "<tr><td>"; */}
IF (isset($_REQUEST['bttlista'])){    $obj->listarReproduccionesFecha($_REQUEST['fini'],$_REQUEST['ffin']);/*echo "<tr><td>"; */}

//bttmodrep
IF (isset($_REQUEST['bttmodrep'])){    $obj->modificarReproduccionTabla($_REQUEST);  $obj->mostrarCrear($_REQUEST['idani']);/*echo "<tr><td>"; */}
IF (isset($_REQUEST['btterepani'])){    $obj->eliminarReproduccionTabla($_REQUEST['idrep']);  $obj->mostrarCrear($_REQUEST['idani']);/*echo "<tr><td>"; */}


if(isset(($_REQUEST['btttabani']))){
    $fecha_timestamp = strtotime($_REQUEST['fini']);
    $an = date("Y", $fecha_timestamp);
 
    $obj->mostraTablaReproduccion($an,date("M"));}
//btterepani
//$obj->mostrarInicio();
if(isset(($_REQUEST['bttlista']))){ $obj->mostraListaReproduccion(date("Y"),date("M")); }
?>
 </center>  </body>
</html>