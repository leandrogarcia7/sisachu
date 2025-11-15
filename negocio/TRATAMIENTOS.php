<?php
require_once("ANIMALES.php");
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TRATAMIENTOS
 *
 * @author LGT-5
 */
class TRATAMIENTOS extends ANIMALES {
    public function mostrarInicio(){
 echo '<td>  <form><center>
        <table border =1>
            <tr><th>Buscar por nombre: </th><tH><BR><input type="text" placeholder="Lanita" name="nombre">
                <button type="submit" name="bttbusani"><IMG src=../img/buscar.jpg><br>BUSCAR ANIMAL</button></td></tr>
<tr><td><br>          
<tr><th >Mostrar por fechas:<th>
            <center>Inicio:<input type=date name=fini value="'.date("Y-m").'-01"> Final:<input type=date name=ffin  value="'.date("Y-m-d").'" >
            <br>  <br> <button  type="submit" name="bttlista" > <IMG src=../img/notarojo.png> <BR>LISTAR CONTROLES</button> 
             <button type="submit" value="Imprimir" name="bttimpani" > <IMG src=../img/imprimir.jpg> <BR> IMPRIMIR CONTROLES</button></center></th></tr>
        
        </table></center></form></table>';
         
     }
     /*
      * Muestra al inicio para crear un nuevo control y abajo todos los controles del animal y las opciones para modificar los mismos
      * 
      */
     public function mostrarTratamientosAnimal($id){
         
          $selanimal= $this->mostrarAnimal($id);
   $opraza= $this->listarRazas(2,$selanimal['idraza']);
   $oppro= $this->listarProveedor(2,$selanimal['idprov']);
   $opanimalesm=$this->listarAnimales(3,'',$selanimal['idmadre']);
   $opanimalesp=$this->listarAnimales(3,'',$selanimal['idpadre']);
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
     //  $fotos= $this->mostrarFotosAnimal($id,400);
      
    echo '<table><tr><td><center>
        <form >  <table border="1">
            <tr>
                <th colspan="2"><center><h2>Datos del animal</h2></center></th></tr>
              <tr>
                <th>Nombre:</th><td><input type="text" value="'.$selanimal['nombre'].'" name="nombre" style="width:100%;"></td> </tr>
             <tr>    <th>Arete:</th><td><input type="number" value='.$selanimal['arete'].' name="arete"></td> </tr>
                     <tr>    <th>Arete anterior:</th><td><input type="number" value='.$selanimal['aretea'].' name="aretea"></td> </tr>
              <tr>   <th>Fecha Nacimiento:</th><td><input type="date" value="'.$selanimal['fecnac'].'" name="fecnac"></td> </tr>
                 <tr>    <th>Sexo:</th><td><select name="sexani"><option value=1 '.$sexH.'>Hembra</option><option value=2 '.$sexM.'>Macho</option>
                      </select> </td> </tr>  
            <tr>    <th>Especie:</th><td><select name="espani">
            <option value=1 '.$esp1.'>Vacuno</option><option value=2 '.$esp2.'>Equino</option>
            <option value= '.$esp3.'>Ovino</option><option value=4 '.$esp4.'>Canino</option>
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
                      </select>      
                      
<tr><th>Salud:<td><select name="estsal">
<option value='.$selanimal['estsal'].'>'.$ani->estsal[$selanimal['estsal']].'</option> 
                 <option value=0>'.$ani->estsal[0].'</option>
                 <option value=1>'.$ani->estsal[1].'</option>
                 <option value=2>'.$ani->estsal[2].'</option>
                 <option value=3>'.$ani->estsal[3].'</option>
                 <option value=4>'.$ani->estsal[4].'</option>
                      </select>      
<tr><th>Reproducción:<td><select name="estrep">
<option value='.$selanimal['estrep'].'>'.$ani->estrep[$selanimal['estrep']].'</option> 
                 <option value=0>'.$ani->estrep[0].'</option>
                 <option value=1>'.$ani->estrep[1].'</option>
                 <option value=2>'.$ani->estrep[2].'</option>
                 <option value=3>'.$ani->estrep[3].'</option>
                 <option value=4>'.$ani->estrep[4].'</option>
                    <option value=5>'.$ani->estrep[5].'</option>
                      </select>    
             <tr>
                 <th colspan="2"><center><input type=hidden name=id value='.$selanimal['id'].'>
                 <input type="submit" name="bttmodani" value="GUARDAR"></center></th></tr>
              </table></form>
              ';
           $controles=$this->listarControles($id);
    
     echo '<td><center><form><table border=1>
        <tr><th colspan=2>Tratamientos animales Hacienda</th>
        <tr><th>Tipo Control:<td><select name=tipcon onchange="mostrarDiv(this)">
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
<tr><th colspan=2>Controles anteriores
'.$controles.'
        </table><input type=hidden name=idani value='.$id.'></form></center></table>
        ';
         
         
     }
     /**
      * Mostar por fechas los controles realizados y los botones para seleccionar y para eliminar
      */
     public function listarTratamientosFecha($fini,$ffin){
          $query='select "CONTROLES".id, idani, tipcon,feccon,descon,vitcon,reccon,sigcon,diacon ,medcon ,tracon,precon,revcon,svicon,fetcon,dia2con,vit2con,nombre,arete
              from  "ANIMALES","CONTROLES" where "ANIMALES".id = "CONTROLES".idani and feccon<=\''.$ffin.'\' and  feccon>=\''.$fini.'\' order by idani desc ;';
       //   echo $query;
          $con=$this->consulta($query);
        
         
          echo '<CENTER><table class="table table-striped"><tr><th>Arete<TH>Animal<TH>Fecha<th>Tipo control<TH>Detalle<TH>Acción ';
          while($r=  pg_fetch_assoc($con)){
                $controles='';
                   switch ($r['tipcon']){
                  case 1:{  $controles.='<b>Desparasitación:</b> '.$r['descon'].'<br><b>Vitaminas:</b> '.$r['vitcon'].'<br><b>Reconstituyente a base de minerales:</b> '.$r['reccon']; break;}
                  case 2:{  $controles.='<b>Tomar signos:</b> '.$r['sigcon'].'<br><b>Diagnostico: </b>'.$r['diacon'].'<br><b>Medicación:</b> '.$r['medcon'].'<br><b>Tratamiento:</b> '.$r['tracon']; break;}
                  case 3:{  $controles.='<b>Preñada: </b>'.$r['precon'].'<br><b>Revisión de ovarios:</b> '.$r['revcon']; break;}
                  case 4:{  $controles.='<b>Signos vitales: </b>'.$r['svicon'].'<br><b>Signos vitales feto: </b>'.$r['fetcon'].'<br><b>Diagnostico:</b> '.$r['dia2con'].'<br><b>Vitaminas y desparasitantes:</b> '.$r['vit2con']; break;}
          }
          
              echo '<tr><td>'.$r['arete'].'<Td>'.$r['nombre'].'<td>'.$r['feccon'].'<Td>'.$this->tipo[$r['tipcon']].'<Td>'.$controles.'<Td>
                  <form>  <button type="submit" name="bttmodcontrol" > <img src="../img/cuadernorojo.png" alt=""/>  <BR>Modificar</button>  
                  <button name=bttecontrol value='.$r['id'].' onclick="javascript: return confirm(\'Esta seguro de Eliminar el Control de '.$r['nombre'].' y todos sus registros\');"><img src=../img/cancelar.jpg  > <br>Eliminar</button>
</form>  ';
              
         
          
                  }
         echo "</table></CENTER>";
         
     }
}
