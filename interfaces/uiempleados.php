<?php
require_once("../negocio/EMPLEADOS.php");
require_once("../negocio/USUARIO.php");
$usu= new USUARIO();
  session_start();
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$empleados=new EMPLEADOS();
require_once("../encabezado.php"); 


?>
<html lang="es" >
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
          <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/script.js"></script>
        <title>SISTEMA DE HACIENDAS</title>
        
         </head>
        
    <body>
      <div class="banner">
        <img src="../img/rrhh.png" alt="Banner de Animales">
    </div>    
    <center><h2>EMPLEADOS</h2></center>
 
        
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
$empleados->mostrarInicio();
//mostrar Crear Raza

//btteliasi
//IF (isset($_REQUEST['btteliasi'])){    $grupo->eliminarasignarAnimalGrupo($_REQUEST['btteliasi']);$grupo->mostrarModificar($_REQUEST['idgru']);}


//IF (isset($_REQUEST['bttasigru'])){    $grupo->asignarAnimalGrupo($_REQUEST['idgru'],$_REQUEST['idani']);$grupo->mostrarModificar($_REQUEST['idgru']);}

IF (isset($_REQUEST['bttmod'])){    $empleados->Modificar($_REQUEST);}

IF (isset($_REQUEST['bttcrear'])){    $empleados->mostrarCrear();}
//guardar los datos del nuevo
IF (isset($_REQUEST['bttnuevo'])){    $empleados->nuevo($_REQUEST);}
//mostrar listado de razas
IF (isset($_REQUEST['bttbuscar'])){    $empleados->buscar($_REQUEST['txtbuscar']);}
//mostrar para modificar
IF (isset($_REQUEST['bttsel'])){    $empleados->mostrarModificar($_REQUEST['bttsel']);}
//guardar los cambios en el modificar

//eiminar los datos
IF (isset($_REQUEST['btteli'])){    $empleados->Eliminar($_REQUEST['btteli']);}
?>
     </body>
</html>