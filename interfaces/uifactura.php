<?php
require '../negocio/USUARIO.php';
require '../negocio/INGRESOS.php';
$usu= new USUARIO();
   session_start();
 require_once("../encabezado.php");   

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<HTML lang=es>
<head><meta http-equiv="Content-Type" content="text/html;">
<title>LGT Solutions</title>
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
 <script src="../js/script.js"></script> 
 <link href="../css/style.css" rel="stylesheet" type="text/css"/>
<script>
  // Función para recalcular todos los totales
  function recalcularTotales() {
    let totalLitros = 0;
    // Recorrer todos los checkboxes de entregas
    const checkboxes = document.querySelectorAll('.chkentrega');
    checkboxes.forEach(function(checkbox) {
      if (checkbox.checked) {
        // Buscar la fila y obtener el valor de litros
        const row = checkbox.closest('tr');
        const litrosText = row.querySelector('.litros').textContent;
        const litros = parseFloat(litrosText) || 0;
        totalLitros += litros;
      }
    });
    // Actualizar el campo total de litros
    document.getElementById('totalLitros').value = totalLitros.toFixed(2);

    // Obtener los valores actuales de los inputs
    const precioLeche = parseFloat(document.getElementById('precioLeche').value) || 0;
    const seguroPorLitro = parseFloat(document.getElementById('seguroPorLitro').value) || 0;
    const retencionPorLitro = parseFloat(document.getElementById('retencionPorLitro').value) || 0;
    const bonoComprasPorLitro = parseFloat(document.getElementById('bonoComprasPorLitro').value) || 0;
    const comision = parseFloat(document.getElementById('comision').value) || 0;  // Se obtiene el valor de comisión

    // Realizar los cálculos
    const subtotal = totalLitros * precioLeche;
    const seguro = totalLitros * seguroPorLitro;
    const retencion = totalLitros * retencionPorLitro;
    const bonoCompras = totalLitros * bonoComprasPorLitro;
    const totalPagar = subtotal - (seguro + retencion + bonoCompras + comision); // Se resta la comisión

    // Actualizar los campos de resultado
    document.getElementById('seguro').value = seguro.toFixed(2);
    document.getElementById('retencion').value = retencion.toFixed(2);
    document.getElementById('bonoCompras').value = bonoCompras.toFixed(2);
    document.getElementById('totalPagar').value = totalPagar.toFixed(2);
  }

  // Asignar eventos cuando el DOM esté cargado
  document.addEventListener('DOMContentLoaded', function() {
    // Incluir el input de comisión en los eventos de cambio
    const inputs = ['precioLeche', 'seguroPorLitro', 'retencionPorLitro', 'bonoComprasPorLitro', 'comision'];
    inputs.forEach(function(id) {
      document.getElementById(id).addEventListener('input', recalcularTotales);
    });
    // Evento para los checkboxes
    const checkboxes = document.querySelectorAll('.chkentrega');
    checkboxes.forEach(function(checkbox) {
      checkbox.addEventListener('change', recalcularTotales);
    });
  });





</script> 
</head>
<body> <div class="banner">
        <img src="../img/produccion.png" alt="Banner de Animales">
    </div>   
   <center><h2>GENERAR FACTURAS LECHE</h2></center>
<?php
$x=0;
$obj= new INGRESOS();

if(isset($_SESSION['idhac'])){
     $usu-> mostrarMenu(1,1);
}else{
  header("Location: ../login.php");
exit;
}

$obj->mostrarInicioFactura();

if(isset($_REQUEST['bttCrearFactura'])){
   $idfac= $obj->crearFacturaLeche($_POST);
 $obj->mostrarFacturaLeche($idfac);  
}else
if(isset($_REQUEST['bttcrearFacturaLeche'])){
     $obj->mostrarCrearFacturaLeche($_REQUEST['fechaInicioFactura'],$_REQUEST['fechaFinFactura']);
}

//bttbuscarFacturasPorFechas
if (isset($_REQUEST['bttbuscarFacturasPorFechas'])) {
    $obj->listarFacturarPorFechas($_REQUEST['fechaInicioFactura'],$_REQUEST['fechaFinFactura']);
}

//eliminarFacturaLeche
if (isset($_REQUEST['btteliFactura'])) {
    $obj->eliminarFacturaLeche($_REQUEST['btteliFactura']);
}
if (isset($_REQUEST['bttActualizarFactura'])) {
    $obj->modificarFacturaLeche($_POST);
}

if (isset($_REQUEST['bttselFactura'])) {
    $obj->mostrarFacturaLeche($_REQUEST['bttselFactura']);
}
