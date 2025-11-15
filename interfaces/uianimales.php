<?php
require_once("../negocio/ANIMALES.php");
require_once("../negocio/USUARIO.php");
require_once("../negocio/DASHBOARD.php");
include_once("../PHP_XLSX/xlsxwriter.class.php");
   session_start();
 require('../fpdf/fpdf.php');


IF(isset($_REQUEST['bttaniexcel'])){
    $writer = new XLSXWriter();
$ANI = new ANIMALES();
$ANI->excelAnimales($_SESSION['idhac'],$writer);
 exit(0); 
}

if(isset($_REQUEST['bttimpani'])){
    $pdf = new FPDF('P','mm','A4');
    $ani = new ANIMALES();
$pdf->AddPage();
    $ani->mostrarImprimirPDF($pdf);
    $pdf->Output('d','ListadoAnimales.pdf');
}

require_once("../encabezado.php");   
   

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function buscarinicial(){
    
     $usu= new USUARIO();
     //echo "<center> <img src='../img/animales.png'> </center>";
   //echo '<center><table><tr><td>';
   //$usu->mostrarMenu(1, 1);
    
    echo ' <form><center>
        <table border =1>
            <tr><th>Buscar por nombre: </th><td><input type="text" placeholder="Lanita" name="nombre">
                <button type="submit" name="bttbusani"><IMG src=../img/buscar.jpg><br>BUSCAR</button></td></tr>
            <tr><th colspan="2">
            <center><br> <button  type="submit" name="bttlista" > <IMG src=../img/notarojo.png> <BR>LISTAR ANIMALES</button> 
               <button type="submit" value="Crear" name="bttcreani" ><IMG src=../img/anadir.png>  <br>CREAR ANIMAL</button>  <button type="submit" value="Imprimir" name="bttimpani" > <IMG src=../img/imprimir.jpg> <BR> IMPRIMIR</button>
               <button  type="submit" name="bttaniexcel" > <IMG src=../img/notarojo.png> <BR>LISTAR EXCEL</button> 
               </center></th></tr>';
        echo "</table></center></form>";
        
   
    //echo '</table>';   
  
    
}
function mostrarCrear(){
  $ani= new ANIMALES();
   $opraza= $ani->listarRazas(2);
   $oppro= $ani->listarProveedor(2);
   $opanimales=$ani->listarAnimales(3);
 
   echo '<center>
        <form>  <table border="1">
            <tr>
                <th colspan="2"><center><h2>Ingrese los datos del animal</h2></center></th></tr>
              <tr>
                <th>Nombre:</th><td><input type="text" placeholder="Lanita" name="nombre" style="width:100%;"></td> </tr>
             <tr>    <th>Arete:</th><td><input type="number" placeholder="001" name="arete"></td> </tr>
               <tr>    <th>Arete anterior:</th><td><input type="number" placeholder="001" name="aretea"></td> </tr>
              <tr>   <th>Fecha Nacimiento:</th><td><input type="date" value="'.date("Y-m-d").'" name="fecnac"></td> </tr>
                 <tr>    <th>Sexo:</th><td><select name="sexani"><option value=1>Hembra</option><option value=2>Macho</option>
                      </select> </td> </tr>  
            <tr>    <th>Especie:</th><td><select name="espani">
            <option value=1>Vacuno</option><option value=2>Equino</option>
            <option value=3>Ovino</option><option value=4>Canino</option>
                      </select> </td> </tr> 
                <tr>    <th>Peso al nacimiento:</th><td><input size="5" type="number" placeholder="001" name="pesonac"></td> </tr>
              <tr>    <th>Fecha llegada:</th><td><input type="date" value="'.date("Y-m-d").'" name="feclle"></td> </tr>
                 <tr>    <th>Peso a llegada:</th><td><input size="5" type="number" placeholder="001" name="pesolle"></td> </tr>
              <tr>    <th>Tipo llegada</th><td><select name="tiplle">
                          <option value="1">Nacimiento</option> <option value="2">Compra</option>
                          <option value="3">Arriendo</option> <option value="4">Partir</option>
                          <option value="5">Regalo</option>
                      </select> </td> </tr>
              <tr>    <th>Raza</th><td>'.$opraza.' 
                      <br>Otra: <input type="text" name="nueraza" > </td> </tr>   
                 <tr>    <th>Procedencia:</th><td>'.$oppro.' 
                      <br>Otro: <input type="text" name="nueprov" > </td>  </td> </tr>  
                 <tr>    <th>Madre:</th><td><select name="idmadre">'.$opanimales.' 
                      </select> </td> </tr>  
                 <tr>    <th>Padre:</th><td><select name="idpadre">'.$opanimales.'
                      </select> </td> </tr>  
            
<tr><th>Estado Hacienda:<td><select name="esthac">
                 <option value=0>'.$ani->esthac[0].'</option>
                 <option value=1>'.$ani->esthac[1].'</option>
                 <option value=2>'.$ani->esthac[2].'</option>
                 <option value=3>'.$ani->esthac[3].'</option>
                 <option value=4>'.$ani->esthac[4].'</option>
                     <option value=5>'.$ani->esthac[5].'</option>
                      </select>      
                      
<tr><th>Salud:<td><select name="estsal">
                 <option value=0>'.$ani->estsal[0].'</option>
                 <option value=1>'.$ani->estsal[1].'</option>
                 <option value=2>'.$ani->estsal[2].'</option>
                 <option value=3>'.$ani->estsal[3].'</option>
                 <option value=4>'.$ani->estsal[4].'</option>
                      </select>      
<tr><th>Estado productivo:<td><select name="estrep">
                 <option value=0>'.$ani->estrep[0].'</option>
                 <option value=1>'.$ani->estrep[1].'</option>
                 <option value=2>'.$ani->estrep[2].'</option>
                 <option value=3>'.$ani->estrep[3].'</option>
                 <option value=4>'.$ani->estrep[4].'</option>
                 <option value=5>'.$ani->estrep[5].'</option>
                 <option value=6>'.$ani->estrep[6].'</option>
                 <option value=7>'.$ani->estrep[7].'</option>
                      </select>      


             <tr>
                 <th colspan="2"><center><input type="submit" name="bttguaani" value="GUARDAR"></center></th></tr>
              </table></form>
    </center>';
}
function  listarAnimales($nombre){
   $ani= new ANIMALES();
   $animales= $ani->listarAnimales(1,$nombre);
 
    echo '  <form> <center>
        <table border="1" class=table style="width:50%">
            <tr>
                <th>Nombre</th><th>Arete</th><th>Especie</th><th>Acción</th>
            </tr>';
    
    foreach ($animales as $a){
      //   $fotos= $ani->mostrarFotosAnimal($a['id'],300);
        echo '<tr>
                <td><h2>'.$a['nombre'].'</h2></td><td>'.$a['arete'].'</td><td>'.$ani->espani[$a['espani']].'</td><td><button name=bttani value='.$a['id'].'> <img src=../img/modif.jpg  > <br>Seleccionar</button>
                    <button name=bttdashboard value='.$a['id'].'> <img src=../img/solicitardiplomas.jpg  > <br>Resumen</button></td>
                    <td><button name=btteani value='.$a['id'].' onclick="javascript: return confirm(\'Esta seguro de Eliminar el Animal y todos sus registros\');"><img src=../img/cancelar.jpg  > <br>Eliminar</button></td>
            </tr>';
    }
    
    echo '</table>
        
    </center></form>';
}

function mostrarAnimal($id){
    $ani= new ANIMALES();
    $selanimal= $ani->mostrarAnimal($id);
   $opraza= $ani->listarRazas(2,$selanimal['idraza']);
   $oppro= $ani->listarProveedor(2,$selanimal['idprov']);
   $opanimalesm=$ani->listarAnimales(3,'',$selanimal['idmadre']);
   $opanimalesp=$ani->listarAnimales(3,'',$selanimal['idpadre']);
   $sexM='';$sexH='';
   if($selanimal['sexani']==1)$sexH=' selected=selected';
   if($selanimal['sexani']==2)$sexM=' selected=selected';
   $esp1='';$esp2='';$esp3='';$esp4='';
   if($selanimal['espani']==1)$esp1=' selected=selected';
   if($selanimal['espani']==2)$esp2=' selected=selected';
   if($selanimal['espani']==3)$esp3=' selected=selected';
   if($selanimal['espani']==4)$esp4=' selected=selected';
   $tiplle1='';$tiplle2='';$tiplle3='';$tiplle4='';$tiplle5='';
      if($selanimal['tiplle']==1)$tiplle1=' selected=selected'; 
      if($selanimal['tiplle']==2)$tiplle2=' selected=selected'; 
      if($selanimal['tiplle']==3)$tiplle3=' selected=selected'; 
      if($selanimal['tiplle']==4)$tiplle4=' selected=selected'; 
      if($selanimal['tiplle']==5)$tiplle5=' selected=selected'; 
   
   //mostrar Fotos de animal size   
       $fotos= $ani->mostrarFotosAnimal($id,400);
      
    echo '<center>
        <form >  <table border="1">
            <tr>
                <th colspan="2"><center><h2>Ingrese los datos del animal</h2></center></th></tr>
              <tr>
                <th>Nombre:</th><td><input type="text" value="'.$selanimal['nombre'].'" name="nombre" style="width:100%;"></td> </tr>
             <tr>    <th>Arete:</th><td><input type="number" value='.$selanimal['arete'].' name="arete"></td> </tr>
                     <tr>    <th>Arete anterior:</th><td><input type="number" value='.$selanimal['aretea'].' name="aretea"></td> </tr>
              <tr>   <th>Fecha Nacimiento:</th><td><input type="date" value="'.$selanimal['fecnac'].'" name="fecnac"></td> </tr>
                 <tr>    <th>Sexo:</th><td><select name="sexani"><option value=1 '.$sexH.'>Hembra</option><option value=2 '.$sexM.'>Macho</option>
                      </select> </td> </tr>  
            <tr>    <th>Especie:</th><td><select name="espani">
            <option value=1 '.$esp1.'>Vacuno</option><option value=2 '.$esp2.'>Equino</option>
            <option value=3 '.$esp3.'>Ovino</option><option value=4 '.$esp4.'>Canino</option>
                      </select> </td> </tr> 
                <tr>    <th>Peso al nacimiento:</th><td><input size="5" type="number" value='.$selanimal['pesonac'].' name="pesonac"></td> </tr>
              <tr>    <th>Fecha llegada:</th><td><input type="date" value="'.$selanimal['feclle'].'" name="feclle"></td> </tr>
                 <tr>    <th>Peso a llegada:</th><td><input size="5" type="number"  value='.$selanimal['pesolle'].' name="pesolle"></td> </tr>
              <tr>    <th>Tipo llegada</th><td><select name="tiplle">
                          <option value="1" '.$tiplle1.'>Nacimiento</option> <option value="2" '.$tiplle2.'>Compra</option>
                          <option value="3" '.$tiplle3.'>Arriendo</option> <option value="4" '.$tiplle4.'>Partir</option>
                          <option value="5" '.$tiplle5.'>Regalo</option>
                      </select> </td> </tr>
              <tr>    <th>Raza</th><td>'.$opraza.' 
                      <br>Otra: <input type="text" name="nueraza" > </td> </tr>   
                 <tr>    <th>Procedencia:</th><td>'.$oppro.' 
                      <br>Otro: <input type="text" name="nueprov" > </td>  </td> </tr>  
                 <tr>    <th>Madre:</th><td><select name="idmadre">'.$opanimalesm.' 
                      </select> </td> </tr>  
                 <tr>    <th>Padre:</th><td><select name="idpadre">'.$opanimalesp.'
                      </select> </td> </tr>  
                    <tr><th>Estado Hacienda:<td><select name="esthac">
                 <option value='.$selanimal['esthac'].'>'.$ani->esthac[$selanimal['esthac']].'</option>   
                 <option value=0>'.$ani->esthac[0].'</option>
                 <option value=1>'.$ani->esthac[1].'</option>
                 <option value=2>'.$ani->esthac[2].'</option>
                 <option value=3>'.$ani->esthac[3].'</option>
                 <option value=4>'.$ani->esthac[4].'</option>
                 <option value=5>'.$ani->esthac[5].'</option>
                      </select>      
                      
<tr><th>Salud:<td><select name="estsal">
<option value='.$selanimal['estsal'].'>'.$ani->estsal[$selanimal['estsal']].'</option> 
                 <option value=0>'.$ani->estsal[0].'</option>
                 <option value=1>'.$ani->estsal[1].'</option>
                 <option value=2>'.$ani->estsal[2].'</option>
                 <option value=3>'.$ani->estsal[3].'</option>
                 <option value=4>'.$ani->estsal[4].'</option>
                      </select>      
<tr><th>Estado productivo:<td><select name="estrep">
<option value='.$selanimal['estrep'].'>'.$ani->estrep[$selanimal['estrep']].'</option> 
                 <option value=0>'.$ani->estrep[0].'</option>
                 <option value=1>'.$ani->estrep[1].'</option>
                 <option value=2>'.$ani->estrep[2].'</option>
                 <option value=3>'.$ani->estrep[3].'</option>
                 <option value=4>'.$ani->estrep[4].'</option>
                    <option value=5>'.$ani->estrep[5].'</option>
                    <option value=6>'.$ani->estrep[6].'</option>
                    <option value=7>'.$ani->estrep[7].'</option>
                      </select>    
             <tr>
                 <th colspan="2"><center><input type=hidden name=id value='.$selanimal['id'].'>
                 <input type="submit" name="bttmodani" value="GUARDAR"></center></th></tr>
              </table></form>
              ';
    $controles=$ani->listarControles($id);
    
     echo '<center><form><table border=1>
        <tr><th colspan=2>Controles animales Hacienda</th>
        <tr><td>Tipo Control:<td><select name=tipcon onchange="mostrarDiv(this)">
        <option value=0>seleccione una opcion</option>
        <option value=1>Control rutinario o preventivo</option>
        <option value=2>Por enfermedad o emergencias</option>
        <option value=3>Control reproductivo</option>
        <option value=4>Control de gestación</option>
        </select>
        <tr><td>Fecha: <td><input type=date name=feccon value='.date('Y-m-d').'>
        <tr><td>Detalle:<td>
                <div id=tipo1 style="display:none;">
                    Desparasitación:<br><textarea name=descon style=width:100%;></textarea><br>
                    Vitaminas:<br><textarea name=vitcon style=width:100%;></textarea><br>
                    Reconstituyente a base de minerales:<br><textarea name=reccon style=width:100%;></textarea><br>
                </div>
                <div id=tipo2 style="display:none;">
                    Tomar signos:<br><textarea name=sigcon style=width:100%;></textarea><br>
                    Diagnostico:<br><textarea name=diacon style=width:100%;></textarea><br>
                    Medicación:<br><textarea name=medcon style=width:100%;></textarea><br>
                    Tratamiento:<br><textarea name=tracon style=width:100%;></textarea><br>
                </div>
                <div id=tipo3 style="display:none;">
                    Preñada:<br><textarea name=precon style=width:100%;></textarea><br>
                    Revisión de ovarios:<br><textarea name=revcon style=width:100%;></textarea><br>
                </div>
                <div id=tipo4 style="display:none;">
                    Signos vitales:<br><textarea name=svicon style=width:100%;></textarea><br>
                    Signos vitales feto:<br><textarea name=fetcon style=width:100%;></textarea><br>
                    Diagnostico:<br><textarea name=dia2con style=width:100%;></textarea><br>
                    Vitaminas y desparasitantes<br><textarea name=vit2con style=width:100%;></textarea>
</div>

<tr><td colspan=2><input type=submit value=Registrar name=bttcont>
</table> </form><table border=1>
<tr><th colspan=2>Controles anteriores
'.$controles.'
        </table><input type=hidden name=idani value='.$id.'></form></center>
        ';
     
     
     
     
echo '<table border=1>
<tr><th>FOTOS</TH></TR>
<tr><tD>'.$fotos.'</TD></TR>
    <tr><tD><form enctype="multipart/form-data" method="POST">
    <!-- MAX_FILE_SIZE debe preceder al campo de entrada del fichero -->
    <!-- El nombre del elemento de entrada determina el nombre en el array $_FILES -->
    Seleccionar foto: <input name="fotoa" type="file" />
    <input type="submit" value="Agregar" name="bttfoto"/>
    <input type=hidden name=id value='.$selanimal['id'].'>
</form></TD></TR>
</table>
    </center>';
    //registrar visitas
   
    
    
    //mostrar visitas
    
    
}
function subirFoto($datos,$f){
    
$dir_subida = '/fotos/';
$dir_subida = '../fotos/';
$fichero_subido = $dir_subida.''.$datos['id'].'_'. basename($f['fotoa']['name']);
echo $f['fotoa']['tmp_name'];
//$fichero_subido = $dir_subida.''.$datos['id'].'_'. basename($f['fotoa']['name']);

if (move_uploaded_file($f['fotoa']['tmp_name'], $fichero_subido)) {
   // echo "El fichero es válido y se subió con éxito.\n";
     $ani= new ANIMALES();
    if(!$ani->agregarFotoAnimal($datos['id'],$f['fotoa']['name'])){
        echo "Error al ingresar a la BD";
    }ELSE{
        Echo "*** Foto agregada *** <br>";
    }
} else {
    echo "¡Posible ataque de subida de ficheros!\n";
}

}
?>
<html lang="es" >
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
          <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
        <title>SISTEMA DE HACIENDAS</title>
        <script type="text/javascript">
function mostrarDiv(obj){
    if(obj.value==1){
        document.getElementById('tipo1').style.display = 'block';
        document.getElementById('tipo2').style.display = 'none';
        document.getElementById('tipo3').style.display = 'none';
        document.getElementById('tipo4').style.display = 'none';
    }
 if(obj.value==2){
        document.getElementById('tipo1').style.display = 'none';
        document.getElementById('tipo2').style.display = 'block';
        document.getElementById('tipo3').style.display = 'none';
        document.getElementById('tipo4').style.display = 'none';
    }
     if(obj.value==3){
        document.getElementById('tipo1').style.display = 'none';
        document.getElementById('tipo2').style.display = 'none';
        document.getElementById('tipo3').style.display = 'block';
        document.getElementById('tipo4').style.display = 'none';
    }
     if(obj.value==4){
        document.getElementById('tipo1').style.display = 'none';
        document.getElementById('tipo2').style.display = 'none';
        document.getElementById('tipo3').style.display = 'none';
        document.getElementById('tipo4').style.display = 'block';
    }

}
function menu(obj){
    var menuDiv = document.getElementById('menu');
    if(menuDiv.style.display == 'block') {
        menuDiv.style.display = 'none';
    } else {
        menuDiv.style.display = 'block';
    }
}



</script>
    </head>
        
    <body>
          <div class="banner">
        <img src="../img/animales.png" alt="Banner de Animales">
    </div>
    <center><h2>ANIMALES</h2></center>
 
        
<?php

 $usu= new USUARIO();
     //echo "<center> <img src='../img/animales.png'> </center>";
   //echo '<center><table><tr><td>';
   $usu->mostrarMenu(1, 1);



$ani=new ANIMALES();
$m=0;
buscarinicial();
$x=0;

if(isset($_REQUEST['bttelifoto'])){
    $ani->eliminarFoto($_REQUEST['codfot']);
    $x++;
}
//
if(isset($_REQUEST['bttmodhor'])){
    $ani->modificarAnimalTabla($_REQUEST);$x++;
}


if(isset($_REQUEST['bttfoto'])){
    $ani->subirFoto($_REQUEST,$_FILES);$x++;
     //mostrarAnimal($_REQUEST['id']);
    $m=1;
}

if(isset($_REQUEST['bttguaani']) and $m==0){
   $id=$ani->crearAnimal($_REQUEST);$x++;
    mostrarAnimal($id);
}
if(isset($_REQUEST['bttcreani'])){
    mostrarCrear();$x++;
}

if(isset($_REQUEST['bttbusani'])){
    listarAnimales($_REQUEST['nombre']);$x++;
}

if(isset($_REQUEST['bttlista'])){
    listarAnimales('');$x++;
}
if(isset($_REQUEST['bttani'])){
    mostrarAnimal($_REQUEST['bttani']);$x++;
}
if (isset($_REQUEST['bttmodani'])){
    $ani->modificarAnimal($_REQUEST);
    $x++;
     mostrarAnimal($_REQUEST['id']);
}
if(isset($_REQUEST['bttcont'])){
    $ani->crearControl($_REQUEST);$x++;
     mostrarAnimal($_REQUEST['idani']);
}
if(isset($_REQUEST['btteani'])){$x++;
    if($ani->eliminarAnimal($_REQUEST['btteani'])){
        echo "<b>Animal eliminado</b>";
    }else{
        echo "<b>El animal tiene controles confirme eliminar</b>";
        mostrarAnimal($_REQUEST['idani']);
    }
     
}
if(isset($_REQUEST['bttecon'])){$x++;
    $ani->eliminarControl($_REQUEST['bttecon']);
    mostrarAnimal($_REQUEST['idani']); 
}


if(isset($_REQUEST['bttrazacat'])){$x++;
    $ani->tablaModificarRaza($_REQUEST['bttrazacat']);
}

if(isset($_REQUEST['bttsexcat'])){$x++;
    $ani->tablaModificarSexo($_REQUEST['bttsexcat']);
}
if(isset($_REQUEST['bttespcat'])){$x++;
    $ani->tablaModificarEspecie($_REQUEST['bttespcat']);
}


if(isset($_REQUEST['bttrepcat'])){$x++;
    $ani->tablaModificarReproductivo($_REQUEST['bttrepcat']);
}
if(isset($_REQUEST['bttestcat'])){$x++;
    $ani->tablaModificarEstadoHacienda($_REQUEST['bttestcat']);
}
if(isset($_REQUEST['bttdashboard'])){
    $dashboard = new DASHBOARD();
    $dashboard->mostrarDashboard($_REQUEST['bttdashboard']);
}

 $ani= new ANIMALES();
 if($x==0)  echo $ani->cuadroResumen(); 
//bttestcat
//'.$selanimal['id'].'
//buscarinicial();
?>
</body>
</html>
