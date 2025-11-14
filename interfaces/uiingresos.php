<?php
//header('Content-Type: text/html; charset=ISO-8859-1');
/*
session_start();
$x1=0;
if(isset($_SESSION['codUsu']))
$x1=1;
else{
    header("location: /sisachu/login.php ");
}
 */
session_start();

  require '../negocio/USUARIO.php';
require_once("../negocio/INGRESOS.php");

function inicialCrear(){
	$bal= new BALANCE();
	//encabezado
	echo '<center><h2>Gestionar Ingresos</h2> <form id=crearing name=crearing>
<table border=1 class="table table-striped"> ';
        //<input type=button name=btttuttem value="MATERIA - TUTORES" onclick=mostrarCrearTemasTutores();>
        //fila para ingresar
        echo '<tr><th>Cuenta Haber:<td><input type=hidden id=coning name=coning style="width:50px;" value=1>';
        echo '<select id=codcueh name=codcueh >';
        $result=$bal->listarCuentas();
        while($reg=  pg_fetch_assoc($result)){
           if($reg['nivel1cue']==4 )
            if($reg['nivel3cue']!=0 ){
               echo '<option  value='.$reg['codcue'].'>'.$reg['nivel1cue'].' '.$reg['nivel2cue'].' '.$reg['nivel3cue'].' '.$reg['nivel4cue'].' '.$reg['nivel5cue'].' '.$reg['detcue'].'</option>';
           }else{
               echo '<option disabled="disabled"  value='.$reg['codcue'].'>---'.$reg['nivel1cue'].' '.$reg['nivel2cue'].' '.$reg['nivel3cue'].' '.$reg['nivel4cue'].' '.$reg['nivel5cue'].' '.$reg['detcue'].'</option>'; 
           }
         }
        echo '</select>';
      //listar cuentas
        echo '<tr><th>Categoria<td><select id=codcat name=codcat >';
        $result=$bal->listarCategorias();
        while($reg=  pg_fetch_assoc($result)){
            echo '<option  value='.$reg['codcat'].'>'.$reg['detcat'].'</option>';
        }
        echo '</select>';
        
              //lisatr clientes
        echo '<tr><th>Cliente<td><div id=dcli><select id=codcli name=codcli onchange=document.crearing.bttnueving.disabled=false;><option value=0>Seleccione</option>';
         $result=$bal->listarClientes();
        while($reg=  pg_fetch_assoc($result)){
            echo '<option  value='.$reg['codcli'].'>'.$reg['nomcli'].'</option>';
        }
        echo '</select><br>Nuevo:<br><input type=text id=nomcli><input type=button value=CREAR onclick=crearCliente(document.crearing.nomcli); > </div>';
        
        //fechas
        $feca=new DateTime(date('d-m-Y'));
        $hoy=$feca->format("Y-m-d");
	$feca->modify('+ 30 day');
        $unmes=$feca->format("Y-m-d");
      
        echo '<tr><th>Fechas<td>Registro:<input id=fecing name=fecing type=date style="width:150px;" value='.$hoy.'><br>
            Ingreso:<input id=fecent name=fecent type=date style="width:150px;" value='.$hoy.'>';
        
        
        echo '<tr><th>Monto<td><input type=text id=moning name=moning style="width:80px;" required><tr><td colspan=2><input type=submit id=bttnueving name=bttnueving value=CREAR disabled></table></form>';
}

function listarIngresosFecha($fecini,$fecfin){
    $bal= new BALANCE();
    $pConsulta='select * from "INGRESOS","CATEGORIA","CLIENTE","CUENTA" where "CATEGORIA".codcat="INGRESOS".codcat and "CLIENTE".codcli="INGRESOS".codcli and "CUENTA".codcue="INGRESOS".codcueh and  fecing<=\''.$fecfin.'\' and fecing<=\''.$fecini.'\'';
 //   echo $pConsulta;
    
    $result=$bal->consulta($pConsulta);
    echo '<center><h2>Ingresos desde '.$fecini.' hasta '.$fecfin.' </h2> 
<table border=1 class="table table-striped"> 
<tr><th>N<th>Cliente<th>Monto<th>Fecha<th>Categoria<th>Cuenta<th>Abono<TH>Ingreso
';
    
    $n=0;
    while($reg=  pg_fetch_assoc($result)){
        $n++;
        $saldo=$reg['moning']-$reg['aboing'];
        echo '<tr><td>'.$n.'<td>'.$reg['nomcli'].'<td>Total:'.$reg['moning'].'<br>Abonos:'.$reg['aboing'].'<br>Saldo:'.($saldo).' <th>'.$reg['fecing'].'<th>'.$reg['detcat'].'<th>'.$reg['detcue'].'<th> 
        <form id=listaing name=listaing> 
        Monto:<input type=money name=montoaboing value='.$reg['moning'].' >  
            Fecha: <input type=date name=fecaboing value='.date("Y-m-d").'> 
            <input type=hidden name=coding value='.$reg['coding'].'>  
               <input type=hidden name=aboing value='.$reg['aboing'].' >   
               <input type=hidden name=codusu value=1>     
            <br>   ';
        //cuenta sesion '.$_SESSION['codUsu'].'>  
         echo 'Cuenta INGRESO:';
        echo '<select id=codcued name=codcued >';
        $result2=$bal->listarCuentas();
        while($reg2=  pg_fetch_assoc($result2)){
           if($reg2['nivel1cue']==1 )
            if($reg2['nivel3cue']!=0 ){
               echo '<option  value='.$reg2['codcue'].'>'.$reg2['nivel1cue'].' '.$reg2['nivel2cue'].' '.$reg2['nivel3cue'].' '.$reg2['nivel4cue'].' '.$reg2['nivel5cue'].' '.$reg2['detcue'].'</option>';
           }else{
               echo '<option disabled="disabled"  value='.$reg2['codcue'].'>---'.$reg2['nivel1cue'].' '.$reg2['nivel2cue'].' '.$reg2['nivel3cue'].' '.$reg2['nivel4cue'].' '.$reg2['nivel5cue'].' '.$reg2['detcue'].'</option>'; 
           }
            
           
        }
        echo '</select>';
        
        echo ' <input type=submit name=bttaboing value=ABONAR></form> <td><form><input type=submit name=bttmoding value=MODIFICAR> </form>   ';
        
    }
}


?>

<HTML lang=es>
<head><meta http-equiv="Content-Type" content="text/html;">
<title>LGT Solutions</title>
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
 <script src="../js/script.js"></script> 
 <link href="../css/style.css" rel="stylesheet" type="text/css"/>
<script>
    function crearCliente(nomcli){
       
	if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	xmlhttp.onreadystatechange=function()
	  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
	    {
		
			    document.getElementById("dcli").innerHTML=xmlhttp.responseText;
		  
	    }
	  }

	xmlhttp.open("GET","jqingresos.php?aja=1&nomcli="+nomcli.value,true);
	xmlhttp.send();	
}
    function listarSubcategoria(codcat){
	if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	xmlhttp.onreadystatechange=function()
	  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
	    {
		
			    document.getElementById("dsubc").innerHTML=xmlhttp.responseText;
		  
	    }
	  }

	xmlhttp.open("GET","jqingresos.php?codcat="+codcat.value,true);
	xmlhttp.send();	
}
function ponerMonto(codsub){
	if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	xmlhttp.onreadystatechange=function()
	  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
	    {
		
			    document.getElementById("moning").value=xmlhttp.responseText;
		  
	    }
	  }

	xmlhttp.open("GET","jqingresos.php?lcodsub="+codsub.value,true);
	xmlhttp.send();	
}


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
<body>
        <div class="banner">
        <img src="../img/ingresos.png" alt="Banner de Animales">
    </div>   
   <center><h2>INGRESOS</h2></center>
<?php
$x=0;

$usu= new USUARIO();
$obj= new INGRESOS();

if(isset($_SESSION['idhac'])){
     $usu-> mostrarMenu(1,1);
}else{
  header("Location: ../login.php");
exit;
}

$obj->mostrarInicio();


if (isset($_REQUEST['bttbuscarPorFechas'])) {
    $obj->buscarIngreso($_REQUEST['fechaInicio'],$_REQUEST['fechaFin']);
}



if (isset($_POST['eliminar_factura'])) {
    $obj->eliminarFacturaDeIngreso($_POST['idingreso'], $_POST['eliminar_factura']);
    // $obj->mostrarIngreso($_POST['idingreso']);
}


if (isset($_REQUEST['bttnuevoIngreso'])) {
    $obj->crearIngreso($_POST);
    $obj->buscarIngreso($_REQUEST['fecing'],$_REQUEST['fechaFin']);
    
}ELSE{
    
if (isset($_REQUEST['bttcrearIngreso'])) {
    $obj->mostrarCrearIngresos();
}
    
}
if (isset($_REQUEST['btteditarIngreso']) ) {
    $obj->modificarIngreso($_REQUEST);
}
if (isset($_REQUEST['bttseling']) ){
    $obj->mostrarIngreso($_REQUEST['bttseling']);
}

/*
if (isset($_POST['nuevas_facturas'])) {
    $obj->agregarFacturasAIngreso($_POST['idingreso'], $_POST['nuevas_facturas']);
  //   $obj->mostrarIngreso($_POST['idingreso']);
}
*/

if (isset($_REQUEST['btteliing'])) {
    $obj->eliminarIngreso($_REQUEST['btteliing']);
}


