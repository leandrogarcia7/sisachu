<?php
error_reporting(E_ALL & ~E_NOTICE);  
 require_once("../negocio/CUENTA.php");
 // require_once("../negocio/PERFIL_USUARIO.php");
  require '../negocio/USUARIO.php';
 //require('fpdf/fpdf.php');

session_start();

require_once("../encabezado.php"); 
$usu = new USUARIO();
$obj = new CUENTA();

?>

<HTML lang=es>
<head>
    <meta http-equiv="Content-Type" content="text/html;">
    <title>LGT Solutions</title>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <link href="../css/style.css" rel="stylesheet" type="text/css"/>
    <script src="../js/script.js"></script>   
</head>

<body>
<div class="banner">
    <img src="../img/ingresos.png" alt="Banner de Cuentas">
</div>   

<center><h2>PLAN DE CUENTAS</h2></center>

<?php
if (isset($_SESSION['idhac'])) {
    $usu->mostrarMenu(1, 1);
} else {
    header("Location: ../login.php");
    exit;
}

// Mostrar tabla inicial de cuentas
$obj->mostrarInicio();

// Registrar nueva cuenta
if (isset($_POST['bttguardarCuenta'])) {
    $obj->crearCuenta($_POST);
}

// Mostrar formulario para crear cuenta
if (isset($_REQUEST['bttcrearCuenta'])) {
    $obj->mostrarCrearCuenta();
}

if (isset($_REQUEST['bttbuscarCuenta'])) {
    $obj->buscarCuenta($_REQUEST['busquedaCuenta']);
}
// Seleccionar cuenta para edición
if (isset($_REQUEST['bttselCuenta'])) {
    $obj->mostrarCuenta($_REQUEST['bttselCuenta']);
}

// Modificar cuenta
if (isset($_REQUEST['bttmodCuenta'])) {
    $obj->modificarCuenta($_REQUEST);
}

// Eliminar cuenta
if (isset($_REQUEST['btteliCuenta'])) {
    $obj->eliminarCuenta($_REQUEST['btteliCuenta']);
}

if (isset($_REQUEST['bttresumenCuenta'])) {
    $obj->mostrarResumenCuentas();
}
?>
</body>
</html>


