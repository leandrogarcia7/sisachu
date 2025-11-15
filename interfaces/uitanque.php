<?php
require '../negocio/USUARIO.php';
require '../negocio/TANQUE.php';

$usu = new USUARIO();
session_start();
$tanque = new TANQUE();

?>

<html lang="es">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/script.js"></script>
</head>
<body>
    <div class="banner">
        <img src="../img/produccion.png" alt="Banner">
    </div>
    <h2 class="titulointerface">TANQUE</h2>
    
<?php
if (isset($_SESSION['idhac'])) {
    $usu->mostrarMenu(1, 1);
} else {
    header("Location: ../login.php");
    exit;
}

if (isset($_POST['bttcrearMedida'])) {
    $tanque->crearMedida($_POST['bttcrearMedida'], $_POST['new_mm'], $_POST['new_litros']);

}
if (isset($_POST['bttmodMedida'])) {
    $tanque->modificarMedida($_POST['idMedida'], $_POST["mod_mm_{$_POST['idMedida']}"], $_POST["mod_litros_{$_POST['idMedida']}"]);
}

if (isset($_POST['btteliMedida'])) {
    $tanque->eliminarMedida($_POST['btteliMedida']);
   
}

//btttablatanque
if (isset($_REQUEST['btttablatanque'])) {
   
    $tanque->mostraTablaTanque($_REQUEST['btttablatanque']);
   
}
if(isset($_REQUEST['bttmod'])) {
    $tanque->modificar($_REQUEST);
} 
if (isset($_REQUEST['btteli'])) {
    $tanque->eliminar($_REQUEST['btteli']);
} 
if (isset($_REQUEST['bttcrear'])) {
    $tanque->mostrarCrear();
} elseif (isset($_REQUEST['bttnuevo'])) {
    $id = $tanque->nuevo($_REQUEST);
    $tanque->mostrarModificar($id);
} elseif (isset($_REQUEST['bttsel'])) {
    $tanque->mostrarModificar($_REQUEST['bttsel']);
} else {
    $tanque->mostrarInicio();
}
?>
</body>
</html>
