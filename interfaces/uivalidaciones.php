<?php
require '../negocio/USUARIO.php';
require '../negocio/GRUPO.php';
$usu= new USUARIO();
   session_start();
 require_once("../encabezado.php");   

?>

<html lang="es">
    <head>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
 <script src="../js/script.js"></script> 
 <title>SISTEMA DE HACIENDAS</title>
 
    </head>
    <body>
   
<?php
if(isset($_SESSION['idhac'])){
     $usu-> mostrarMenu(1,1);
}else{
  header("Location: ../login.php");
exit;
}
$grupo = new GRUPO();
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

echo '<center><h2 class="titulointerface">REPRODUCCION</h2></center>';
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//echo "</td><td>";
//mostrar las opciones de raza en botones animados

$grupo->mostrarInicioValidacion();


IF (isset($_REQUEST['bttmostrar'])){    $grupo->mostrarValidarLeche($_REQUEST['selgru']);}





?>
        </body>
</html>