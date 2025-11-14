<?php
require '../negocio/USUARIO.php';
require '../negocio/ENTREGA.php';
$usu= new USUARIO();
   session_start();
$entrega= new ENTREGA();   
 require_once("../encabezado.php");
?>

<html lang="es">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/script.js"></script>

<script>
function calcularMultiplicacion() {
    // Obtener el valor del campo de texto
    const multiplicador = parseFloat(document.getElementById('multiplicador').value);

    // Validar que el valor sea numérico y positivo
    if (isNaN(multiplicador) || multiplicador <= 0) {
        document.getElementById('resultado_campo').value = '';
        document.getElementById('resultado').innerText = 'Por favor, ingrese un valor válido en dólares.';
        return;
    }

    // Calcular el resultado en dólares
    const resultado = totalLitros * multiplicador;

    // Mostrar el resultado en el campo de texto y debajo del botón
    document.getElementById('resultado_campo').value = `$${resultado.toFixed(2)}`;
    document.getElementById('resultado').innerText = `El valor total es: $${resultado.toFixed(2)}`;
}
</script>

<link href="../css/style.css" rel="stylesheet" type="text/css"/>
    <body>
        <div class="banner">
        <img src="../img/produccion.png" alt="Banner de Animales">
    </div>   
        <h2 class="titulointerface">ENTREGA LECHE</h2>

<?php

//echo "<table style='width:90%'><td style='width:40%'>";
if(isset($_SESSION['idhac'])){
     $usu-> mostrarMenu(1,1);
}else{
  header("Location: ../login.php");
exit;
}


//echo "</td><td>";
//mostrar las opciones de raza en botones animados

//mostrar Crear Raza
$x=0;

IF (isset($_REQUEST['bttcrear'])){    $entrega->mostrarCrear();}
//guardar los datos del nuevo
IF (isset($_REQUEST['bttnuevo'])){    $id=$entrega->nuevo($_REQUEST);  $entrega->mostrarModificar($id);}
//mostrar listado de razas
IF (isset($_REQUEST['bttbuscar'])){  $entrega->mostrarInicio(); $x=1;  $entrega->buscar($_REQUEST['fec'],$_REQUEST['fecfin']);}
//mostrar para modificar
IF (isset($_REQUEST['bttmod'])){    $entrega->ModificarEntrega($_REQUEST, $_FILES); }

IF (isset($_REQUEST['bttsel'])){    $entrega->mostrarModificar($_REQUEST['bttsel']);}


//guardar los cambios en el modificar
//IF (isset($_REQUEST['bttmod'])){    $entrega->Modificar($_REQUEST);}
//eiminar los datos
IF (isset($_REQUEST['btteli'])){    $entrega->Eliminar($_REQUEST['btteli']);}

if(isset($_REQUEST['bttagrlec'])){  $entrega->agregarLecheEntrega($_REQUEST);  $entrega->mostrarModificar($_REQUEST['ident']);  }
if(isset($_REQUEST['bttelilec'])){  $entrega->eliminarLecheEntrega($_REQUEST['identlec']);  $entrega->mostrarModificar($_REQUEST['ident']);  }
//bttelilec
if(isset($_REQUEST['bttresumen'])){   $entrega->mostrarResumeEntregas($_REQUEST['fec'],$_REQUEST['fecfin'],$_SESSION['idhac']); $entrega->mostrarInicio(); $x=1; }

if($x==0)
$entrega->mostrarInicio();



?>
        </body>
</html>