<?php
require '../negocio/USUARIO.php';
require '../negocio/DIARIO.php';
$usu= new USUARIO();
   session_start();
 require_once("../encabezado.php");   

?>

<html lang="es">
    <head>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
 <script src="../js/script.js"></script> 
 <link href="../css/style.css" rel="stylesheet" type="text/css"/>
 <title>SISTEMA DE HACIENDAS</title>
 
    </head>
    <body>
   <div class="banner">
        <img src="../img/produccion.png" alt="Banner de Animales">
    </div>   
<?php
if(isset($_SESSION['idhac'])){
     $usu-> mostrarMenu(1,1);
}else{
  header("Location: ../login.php");
exit;
}
$diario = new DIARIO();
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

echo '<center><h2 class="titulointerface">DIARIO DE LECHE</h2></center>';
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//echo "</td><td>";
//mostrar las opciones de raza en botones animados

$x=0;


IF (isset($_REQUEST['bttmostrar'])){    $id=$diario->crearDiario($_REQUEST);   $diario->mostrarDiarioLeche($id);  echo "<br></br>";  }

IF (isset($_REQUEST['bttguardartoma'])){  $id=$diario->crearDiarioAnimal($_REQUEST);   $diario->mostrarDiarioLeche($id); echo "<br></br>";   }
//

if($x==0)
$diario->mostrarInicioDiario();

?>
        </body>
</html><?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

