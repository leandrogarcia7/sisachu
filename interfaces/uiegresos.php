<?php
require '../negocio/USUARIO.php';
require_once("../negocio/EGRESO.php"); // Suponiendo que TIPO_INGRESO y TIPO_EGRESO están en esta clase.
$usu = new USUARIO();
session_start();
require_once("../encabezado.php"); 
?>

<HTML lang=es>
<head><meta http-equiv="Content-Type" content="text/html;">
<title>LGT Solutions</title>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <link href="../css/style.css" rel="stylesheet" type="text/css"/>
    <script src="../js/script.js"></script>   

</head>
<body><div class="banner">
        <img src="../img/ingresos.png" alt="Banner de Animales">
    </div>   
   <center><h2>EGRESOS</h2></center>
<?php
$x=0;

$usu= new USUARIO();
$obj= new EGRESO();

if(isset($_SESSION['idhac'])){
     $usu-> mostrarMenu(1,1);
}else{
  header("Location: ../login.php");
exit;
}

$obj->mostrarInicio();


if (isset($_REQUEST['bttbuscarPorFechas'])) {
    $obj->buscarEgreso($_REQUEST['fechaInicio'],$_REQUEST['fechaFin']);
}
if (isset($_POST['bttguardarEgreso'])) {
    $obj->crearEgreso($_POST);
}
if (isset($_REQUEST['bttcrearEgreso'])) {
    $obj->mostrarCrearEgresos();
}
//bttselEgreso
if (isset($_REQUEST['bttselEgreso'])) {
    $obj->mostrarEgreso($_REQUEST['bttselEgreso']);
}

if (isset($_REQUEST['bttmodegreso']) ) {
    $obj->modificarEgreso($_REQUEST);
}



if (isset($_REQUEST['btteliEgreso'])) {
    $obj->eliminarEgreso($_REQUEST['btteliEgreso']);
}


