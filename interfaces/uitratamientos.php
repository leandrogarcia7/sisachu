<?php
require '../negocio/USUARIO.php';
require '../negocio/TRATAMIENTOS.php';
$usu= new USUARIO();
$tratamiento= new TRATAMIENTOS();
   session_start();

function  listarAnimales($nombre){
   $ani= new ANIMALES();
   $animales= $ani->listarAnimales(1,$nombre);
 
    echo '  <form> <center>
        <table border="1" class=table style="width:50%">
            <tr>
                <th>Nombre</th><th>Arete</th><th>Acción</th>
            </tr>';
    
    foreach ($animales as $a){
      //   $fotos= $ani->mostrarFotosAnimal($a['id'],300);
        echo '<tr>
                <td><h2>'.$a['nombre'].'</h2></td><td>'.$a['arete'].'</td><td><button name=bttani value='.$a['id'].'> <img src=../img/modif.jpg  > <br>Seleccionar</button></td>
                    
            </tr>';
    }
    
    echo '</table>
        
    </center></form>';
}


?>

<html lang="es">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <body>
    <div class="banner">
        <img src="../img/animales.png" alt="Banner de Animales">
    </div>  
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
echo "<center>";
//mostrar las opciones de raza en botones animados
IF (isset($_REQUEST['bttani'])){    $tratamiento->mostrarTratamientosAnimal($_REQUEST['bttani']);echo "<tr><td>";}
IF (isset($_REQUEST['bttmodani'])){    $tratamiento->modificarAnimal($_REQUEST);  $tratamiento->mostrarTratamientosAnimal($_REQUEST['id']);echo "<tr><td>"; }
IF (isset($_REQUEST['bttcont'])){    $tratamiento->crearControl($_REQUEST);  $tratamiento->mostrarTratamientosAnimal($_REQUEST['idani']);echo "<tr><td>";}
IF (isset($_REQUEST['bttbusani'])){    listarAnimales($_REQUEST['nombre']);echo "<tr><td>";}
IF (isset($_REQUEST['bttlista'])){    $tratamiento->listarTratamientosFecha($_REQUEST['fini'],$_REQUEST['ffin']);echo "<tr><td>";}
$tratamiento->mostrarInicio();


//eiminar los datos
IF (isset($_REQUEST['btteli'])){    $tratamiento->Eliminar($_REQUEST['btteli']);}

echo "</center>";
?>
        </body>
</html>