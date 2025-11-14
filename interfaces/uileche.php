<?php

require '../negocio/USUARIO.php';
require '../negocio/LECHE.php';
$usu= new USUARIO();
   session_start();
$leche= new LECHE();   


?>

<html lang="es">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/script.js"></script>
    <script>    
function sumar(obj1,obj2,obj3){
  
   x = obj1.value;  
   y = obj2.value;  
   suma=parseFloat(x)+parseFloat(y);  
   text= suma;  
     obj3.value = text;  
}
function restar(obj1,obj2,obj3,obj4){
  
   x = obj1.value;  
   y = obj2.value;  
   z = obj3.value;  
   suma=parseFloat(x)+parseFloat(y)+parseFloat(z);  
   text= suma;  
     obj4.value = text;  
}
</script>
<link href="../css/style.css" rel="stylesheet" type="text/css"/>


<link href="../css/style.css" rel="stylesheet" type="text/css"/>
    <body>
        <div class="banner">
        <img src="../img/produccion.png" alt="Banner de Animales">
    </div>   
        <h2 class="titulointerface">PRODUCCIÓN LECHE</h2>
        
        
        
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

//mostrar Crear Raza
$x=0;


//guardar los datos del nuevo
IF (isset($_REQUEST['bttnuevo'])){    $leche->nuevo($_REQUEST);}else{
    IF (isset($_REQUEST['bttcrear'])){    $leche->mostrarCrear($_REQUEST['feclecc']);}
}


//mostrar listado de razas
IF (isset($_REQUEST['bttbuscar'])){  $leche->mostrarInicio(); $x=1;  $leche->buscarLeches($_REQUEST['feclecc'],$_REQUEST['feclecfin']);}
//mostrar para modificar
IF (isset($_REQUEST['bttmod'])){    $leche->Modificar($_REQUEST);}

IF (isset($_REQUEST['bttsel'])){    $leche->mostrarModificar($_REQUEST['bttsel']);}
//guardar los cambios en el modificar

//eiminar los datos
IF (isset($_REQUEST['btteli'])){    $leche->Eliminar($_REQUEST['btteli']);}
//mostrar resumen por fecha de los grupo
if(isset($_REQUEST['bttresumen'])){ $leche->mostrarInicio(); $x=1;  $leche->resumenGruposHistorial($_REQUEST['feclecc'],$_REQUEST['feclecfin'],$_REQUEST['precio'],$_REQUEST['idgru']);  }
//



IF (isset($_REQUEST['bttcreartabla'])){  $leche->mostrarInicio(); $x=1;  $leche->mostrarIngresarLeche($_REQUEST['feclecc'],$_REQUEST['feclecfin'],$_REQUEST['idgru']);}

//IF (isset($_REQUEST['bttnuevo2'])){  $leche->mostrarInicio(); $x=1;  $leche->nuevo($_REQUEST); $leche->mostrarIngresarLeche($_REQUEST['feclec'],$_REQUEST['feclecfin'],$_REQUEST['idgru']); }

//IF (isset($_REQUEST['bttmod'])){    $leche->Modificar($_REQUEST);}

if($x==0)
$leche->mostrarInicio();


?>
        </body>
</html>