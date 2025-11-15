
<?php
require '../negocio/USUARIO.php';
require '../negocio/ESTANCIA.php';
$usu= new USUARIO();
   session_start();
$estancia = new ESTANCIA();
   
?>

<html lang="es">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/script.js"></script>  
<body>
   <div class="banner">
        <img src="../img/animales.png" alt="Banner de Animales">
    </div>
     <center><h2>ESTANCIA</h2></center>  
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

//echo "</td><td><center>";

if (isset($_POST['bttcrear'])) {
    $estancia->crearEstancia($_POST['idgru'], $_POST['idsub'], $_POST['responsable']);
}

IF (isset($_REQUEST['bttcrearest'])){    
    if (!isset($_REQUEST['responsable']) || $_REQUEST['responsable'] == 0) {
    echo "<div class='errores'>⚠️ Debes seleccionar un responsable para la estancia.</div>";
} else {
    $estancia->guardarEstancia(
        $_REQUEST['idgru'],
        $_REQUEST['idsub'],
        $_REQUEST['detest'],
        $_REQUEST['feciniest'],
        $_REQUEST['fecfinest'],
        $_REQUEST['responsable']
    );
}
    
}


IF (isset($_REQUEST['bttlista'])){ echo "<tr><td>";   $estancia->listarEstancia($_REQUEST['idgru'],$_REQUEST['idsub'],$_REQUEST['fini'],$_REQUEST['ffin']);}

if (isset($_POST['bttsel'])) {
    $estancia->mostrarEstancia($_POST['bttsel']);
}

if (isset($_POST['bttmodest'])) {
    $estancia->modificarEstancia(
        $_POST['idest'],
        $_POST['idgru'],
        $_POST['idsub'],
        $_POST['responsable'],
        $_POST['feciniest'],
        $_POST['fecfinest'],
        $_POST['fecsalest'],
        $_POST['detest'],
        $_POST['estest']
    );
}
$estancia->mostrarInicio();
?>
        </body>

</html>