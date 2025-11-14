<?php
require '../negocio/USUARIO.php';
require '../negocio/TIPO.php';  // Suponiendo que TIPO_INGRESO y TIPO_EGRESO están en esta clase.
$usu = new USUARIO();
$tipo = new TIPO();

session_start();

?>

<html lang="es">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <link href="../css/style.css" rel="stylesheet" type="text/css"/>
    <script src="../js/script.js"></script>   
    <body>
        <h2 class="titulointerface">Gestión de Tipos: Ingresos y Egresos</h2>
        
        <?php
        if(isset($_SESSION['idhac'])){
            $usu->mostrarMenu(1,1);
        } else {
            header("Location: ../login.php");
            exit;
        }

    
$obj = new TIPO();

// Mostrar las opciones iniciales
$obj->mostrarInicio();

// Para Tipo de Ingreso
if (isset($_REQUEST['bttbuscarIngreso'])) {
    $obj->buscarTipoIngreso($_REQUEST['txtbuscarIngreso']);
}

if (isset($_REQUEST['bttcrearIngreso'])) {
    $obj->mostrarCrearIngreso();
}

if (isset($_REQUEST['bttnuevoIngreso'])) {
    $obj->crearTipoIngreso($_REQUEST);
}

if (isset($_REQUEST['bttselti']) ){
    $obj->mostrarTipoIngreso($_REQUEST['bttselti']);
}

if (isset($_REQUEST['bttmodti']) ) {
    $obj->modificarTipoIngreso($_REQUEST);
}

if (isset($_REQUEST['btteliti'])) {
    $obj->eliminarTipoIngreso($_REQUEST['btteliti']);
}

// Para Tipo de Egreso
if (isset($_REQUEST['bttbuscarEgreso'])) {
    $obj->buscarTipoEgreso($_REQUEST['txtbuscarEgreso']);
}

if (isset($_REQUEST['bttcrearEgreso'])) {
    $obj->mostrarCrearEgreso();
}

if (isset($_REQUEST['bttnuevoEgreso'])) {
    $obj->crearTipoEgreso($_REQUEST);
}

if (isset($_REQUEST['bttselte'])) {
    $obj->mostrarTipoEgreso($_REQUEST['bttselte']);
}

if (isset($_REQUEST['bttmodte'])) {
    $obj->modificarTipoEgreso($_REQUEST);
}

if (isset($_REQUEST['bttelite'])) {
    $obj->eliminarTipoEgreso($_REQUEST['bttelite']);
}

  
        
        
        

        ?>
    </body>
</html>
