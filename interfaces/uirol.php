<?php
require_once("../negocio/ROL.php");
require_once("../negocio/USUARIO.php");
require_once("../fpdf/fpdf.php");
$usu= new USUARIO();
  session_start();
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$clase=new ROL();
$pdf= new FPDF();
IF (isset($_REQUEST['pdfRol'])){ 
$clase->imprimirRol($_REQUEST['idrol'],$pdf);
}

require_once("../encabezado.php"); 




?>
<html lang="es" >
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
          <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
        <title>SISTEMA DE HACIENDAS</title>
    <script src="../js/script.js"></script>    
         </head>
        
    <body>
        <div class="banner">
        <img src="../img/rrhh.png" alt="Banner de Animales">
    </div>   
    <center><h2>ROLES</h2></center>
 
        
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
$clase->mostrarInicio();

$x=0;
//mostrar listado de  bttact
IF (isset($_REQUEST['bttbuscar'])){    $clase->buscar($_REQUEST['txtbuscar']);}
//bttact
IF (isset($_REQUEST['bttact'])){ $x++; $clase->modificarRol($_REQUEST);  $clase->mostrarCrearRol($_REQUEST['idemp'],$_REQUEST['anio'],$_REQUEST['mes']);}
//IF (isset($_REQUEST['bttlistar'])){    $clase->mostrarCrear($_REQUEST['txtbuscar']);}

IF (isset($_REQUEST['bttcrear']) and $x==0){    $clase->mostrarCrearRol($_REQUEST['bttcrear'],$_REQUEST['anio'],$_REQUEST['mes']);}


IF (isset($_REQUEST['bttEliminarDetalle'])){  $clase->eliminarDetalleRol($_REQUEST['bttEliminarDetalle']);  $clase->mostrarRol($_REQUEST['idrol']);}
//bttcrearDetalle

IF (isset($_REQUEST['bttcrearDetalle'])){  $clase->crearRolDetalle($_REQUEST);  $clase->mostrarRol($_REQUEST['idrol']);}

//bttresumen
IF (isset($_REQUEST['bttresumen'])){ $clase->mostrarRoles($_REQUEST['mes'],$_REQUEST['anio']);  ;}

//bttmosrol
IF (isset($_REQUEST['bttmosrol'])){  $clase->mostrarRol($_REQUEST['idrol']);}

IF (isset($_REQUEST['bttresumenanio'])){  $clase->mostrarReporteAnualRoles($_REQUEST['anio']);}

