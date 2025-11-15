
<?php
require_once '../negocio/TRABAJO.php';
$usu = new USUARIO();
session_start();

$obj = new TRABAJO();
require_once("../encabezado.php"); 
?>

<html lang="es">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../js/script.js"></script>
  <body>
    <div class="banner">
      <img src="../img/rrhh.png" alt="Banner de Potreros">
    </div>  

    <?php
    if(isset($_SESSION['idhac'])){
        $usu->mostrarMenu(1,1);
    } else {
        header("Location: ../login.php");
        exit;
    }

    if (isset($_POST['modificarAsociado'])) {
    $obj->modificarAsociado($_POST['tipoAsociado'], $_POST['idAsociado'], $_POST['estado']);
}

if (isset($_POST['eliminarAsociado'])) {
    $obj->eliminarAsociado($_POST['tipoAsociado'], $_POST['idAsociado']);
}

    if (isset($_POST['asociarMAQUINARIA'])) {
    $obj->asociarATrabajo('MAQUINARIA', $_POST['idTrabajo'], $_POST['idMAQUINARIA'], $_POST['cantidad'], $_POST['medida'], $_POST['estado']);
}
if (isset($_POST['asociarMATERIAL'])) {
    $obj->asociarATrabajo('MATERIAL', $_POST['idTrabajo'], $_POST['idMATERIAL'], $_POST['cantidad'], $_POST['medida'], $_POST['estado']);
}
if (isset($_POST['asociarPOTRERO'])) {
    $obj->asociarATrabajo('POTRERO', $_POST['idTrabajo'], $_POST['idPOTRERO']);
}
if (isset($_POST['asociarEMPLEADO'])) {
    $obj->asociarATrabajo('EMPLEADO', $_POST['idTrabajo'], $_POST['idEMPLEADO']);
}
    
    // Acciones de la interfaz
    if (isset($_REQUEST['bttcrear'])){ 
        if(isset($_REQUEST['idTrabajo'])) {
        $obj->mostrarModificar($_REQUEST['idTrabajo']);
    } else{
            $obj->mostrarCrear();}
    }  
    if (isset($_REQUEST['bttnuevo'])){ $obj->mostrarModificar($obj->nuevo($_REQUEST));}
    if (isset($_REQUEST['bttbuscar'])){ $obj->buscar($_REQUEST['txtbuscar']);}
      if (isset($_REQUEST['bttmod'])){ $obj->modificarTrabajo($_REQUEST);}
    if (isset($_REQUEST['bttsel'])){ $obj->mostrarModificar($_REQUEST['bttsel']);}
  
    if (isset($_REQUEST['btteli'])){ $obj->eliminarTrabajo($_REQUEST['btteli']);}
    
    
    
    



    $obj->mostrarInicio();
/*
$potreros=new POTREROS();
$potreros->inicial();

if(isset($_REQUEST['bttcrear'])){
    $potreros->incialCrear();
}

if (isset($_REQUEST['bttcreartrabajo'])){
    $id=$potreros->crearTrabajo($_REQUEST);
    $potreros->mostrarAgregarTrabajo($id);
}



if(isset($_REQUEST['btttramatc'])){
    $potreros->agregarTrabajoMaterial($_REQUEST);
    
    $potreros->mostrarAgregarTrabajo($_REQUEST['idtra']);
    
}

if(isset($_REQUEST['btttramaqc'])){
    $potreros->agregarTrabajoMaquinaria($_REQUEST);
    
    $potreros->mostrarAgregarTrabajo($_REQUEST['idtra']);
    
}

*/



?>
        </body>

</html>