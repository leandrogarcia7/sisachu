<?php
require __DIR__ . '/../negocio/USUARIO.php';

$usu = new USUARIO();

session_start();

if (isset($_REQUEST['bttlogin'])) {
    // El encabezado no se carga cuando se procesa el login.
} else {
    $encabezado = __DIR__ . '/../encabezado.php';
    if (is_file($encabezado)) {
        require_once $encabezado;
    }
}
?>

<html lang="es">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function menu(obj){
    var menuDiv = document.getElementById('menu');
    if(menuDiv.style.display == 'block') {
        menuDiv.style.display = 'none';
    } else {
        menuDiv.style.display = 'block';
    }
}</script>

    <body>
   
<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
 echo "<center><img src=../img/banner.png style='width:100%'></center>";
if(isset($_REQUEST['bttlogin'])){

    if($usu->loginUsuario($_REQUEST)){
    if(isset($_SESSION['idhac'])){
     $usu-> mostrarMenu(1,$_SESSION['tipusu']);
}
        echo "Ingreso Correcto";
   
    }else{
        echo "Usuario Incorrecto";
 
        
    }

}else{
              if(isset($_SESSION['nomusu']))
{
	   $usu-> mostrarMenu(1,$_SESSION['tipusu']);
}
}
echo "<script>menu();</script> ";





?>
        </body>
</html>