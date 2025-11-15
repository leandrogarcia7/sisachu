<?php
require_once("USUARIO.php");
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ANIMALES
 *
 * @author Leandro
 */
class ANIMALES extends USUARIO{
    
  public $id, $arete,$pesonac,$pesolle;
  public $nombre, $fecnac, $feclle, $idraza, $idprov, $idvacunas,$idpadre, $idmadre, $estani;
  public $tipo= array('','Control rutinario o preventivo','Por enfermedad o emergencias','Control reproductivo','Control de gestación' );
 public $esthac= array('POR REGISTRAR','NORMAL','VENDIDO','MUERTO','PERDIDO', 'FUERA DE PREDIO' );
 // public $esthac= array('','TORO','VACA','VIENTRE','PERDIDO MUERTO' );
 public $estsal= array('NO APLICA','SALUDABLE','ENFERMO','ENFERMO EN TRATAMIENTO','EN TRATAMIENTO' );
 public $estrep= array('NO APLICA','TERNERA','FIERRO','VIENTRE','LECHE','SECA','TORO','TERNERO / TORETE');

     public $tiplle =array('','Nacimiento','Compra','Arriendo','Partir','Regalo' );
  public $espani =array('General','Vacuno','Equino','Ovino','Canino','' );
  public $sexani= array('','Hembra','Macho');
  
  public function crearAnimal($datos){
      //validar que no sea raza nueva
      if(isset($datos['nueraza']))
      if($datos['nueraza']!=''){
          $query='select * from "RAZA" where detalle=\''.addslashes ($datos['nueraza']).'\';'; 
      $pConsulta=$this->consulta($query);
      if($reg=$this->row($pConsulta)){
          $datos['idraza']=$reg['id'];
      }else{
      $query='INSERT INTO "RAZA"(detalle)VALUES (\''.addslashes ($datos['nueraza']).'\');'; 
      $pConsulta=$this->consulta($query);
      $query='select * from "RAZA" where detalle=\''.addslashes ($datos['nueraza']).'\';'; 
      $pConsulta=$this->consulta($query);
       if($reg=$this->row($pConsulta)){
          $datos['idraza']=$reg['id'];
      }else{
           $datos['idraza']=1;
      }
      }   
      } 
       //validar que no sea proveedor nuevo
      if(isset($datos['nueprov']))
        if($datos['nueprov']!=''){
          $query='select * from "PROVEEDOR" where nompro=\''.addslashes ($datos['nueprov']).'\';'; 
      $pConsulta=$this->consulta($query);
      if($reg=$this->row($pConsulta)){
          $datos['idprov']=$reg['codpro'];
      }else{
      $query='INSERT INTO "PROVEEDOR"(nompro)VALUES (\''.addslashes ($datos['nueprov']).'\');'; 
      $pConsulta=$this->consulta($query);
      $query='select * from "PROVEEDOR" where nompro=\''.addslashes ($datos['nueprov']).'\';'; 
      $pConsulta=$this->consulta($query);
       if($reg=$this->row($pConsulta)){
          $datos['idprov']=$reg['codpro'];
      }else{
           $datos['idprov']=1;
      }
      }   
      } 
     if(!isset($datos['aretea']) || $datos['aretea'] == ''){
    $datos['aretea'] = 0;
}
      if($datos['esthac']=='')$datos['esthac']=0; 
      if($datos['estsal']=='')$datos['estsal']=0; 
      if($datos['estrep']=='')$datos['estrep']=0; 
      if($datos['arete']=='')$datos['arete']=0;      
      if (!isset($datos['tiplle']) || $datos['tiplle'] == '') {
    $datos['tiplle'] = 0;
}
      
      if($datos['espani']=='')$datos['espani']=0; 
      
     if (!isset($datos['pesonac']) || $datos['pesonac'] == '') {
    $datos['pesonac'] = 0;
}
if (!isset($datos['pesolle']) || $datos['pesolle'] == '') {
    $datos['pesolle'] = 0;
}
      if($datos['fecnac']=='')$datos['fecnac']='01-01-1900';   if($datos['feclle']=='')$datos['feclle']='01-01-1900'; 
      
      $query='INSERT INTO "ANIMALES"(arete, nombre, fecnac, feclle, tiplle, idraza, idprov, idpadre,idmadre, estani, pesonac, pesolle,sexani,espani,aretea,esthac,estsal,estrep,idhac)
    VALUES ('.$datos['arete'].', \''.addslashes($datos['nombre']).'\',\''.$datos['fecnac'].'\', \''.$datos['feclle'].'\','.$datos['tiplle'].', '.$datos['idraza'].','.$datos['idprov'].', '.$datos['idpadre'].', 
            '.$datos['idmadre'].', 1, '.$datos['pesonac'].', '.$datos['pesolle'].','.$datos['sexani'].','.$datos['espani'].',\''.$datos['aretea'].'\','.$datos['esthac'].','.$datos['estsal'].','.$datos['estrep'].','.$_SESSION['idhac'].');'; 
      if($con=$this->consulta($query)){
              $query='select * from "ANIMALES" order by id desc;'; 
      $pConsulta=$this->consulta($query);
      if($reg=$this->row($pConsulta)){
          return $reg['id'];
      }
          
          
          return true;
       }else{
           echo "Error ingresar a la BDD ";
           return false;
       }
       
      
  }
    public function listarRazas($op,$id=0){
        
        $con=$this->consulta('select * from "RAZA" order by id');
     $dato='<select name=idraza>';
        while($reg=$this->row($con)){
            if($reg['id']==$id)
             $dato.='<option value='.$reg['id'].' selected=selected>'.$reg['detalle'].'</option>';
            else
                $dato.='<option value='.$reg['id'].'>'.$reg['detalle'].'</option>';
             $r[]=$reg;
        }
        $dato.='</select>';
        if($op==1) return $r; else return $dato;
    }
     public function listarProveedor($op,$id=0){
        
        $con=$this->consulta('select * from "PROVEEDOR" order by codpro');
     $dato='<select name=idprov>';
        while($reg=$this->row($con)){
             if($reg['codpro']==$id)
             $dato.='<option value='.$reg['codpro'].' selected=selected>'.$reg['nompro'].'</option>';
             else
            $dato.='<option value='.$reg['codpro'].'>'.$reg['nompro'].'</option>';
             $r[]=$reg;
        }
        $dato.='</select>';
        if($op==1) return $r; else return $dato;
    }  
         public function listarAnimales($op,$nombre='',$id=0){
      //  echo 'select * from "ANIMALES" where nombre ilike \''.addslashes($nombre).'%\' and idhac='.$_SESSION['idhac'].' order by esthac,nombre';
        $con=$this->consulta('select * from "ANIMALES" where nombre ilike \''.addslashes($nombre).'%\' and idhac='.$_SESSION['idhac'].' order by esthac,espani,nombre');
        $dato='';
    if($op==2) $dato='<select name=id >';
        while($reg=$this->row($con)){
             if($reg['id']==$id)
             $dato.='<option value='.$reg['id'].' selected=selected>'.$reg['nombre'].' - '.$reg['arete'].' - '.$this->esthac[$reg['esthac']].' - '.$this->espani[$reg['espani']].' </option>';
             else 
              $dato.='<option value='.$reg['id'].'>'.$reg['nombre'].' - '.$reg['arete'].'  - '.$this->esthac[$reg['esthac']].'  - '.$this->espani[$reg['espani']].' </option>';    
             $r[]=$reg;
        }
         if($op==2)  $dato.='</select>';
        if($op==1) return $r; else return $dato;
    }  
         public function listarAnimalesMadres($op,$nombre='',$id=0){
      //  echo 'select * from "ANIMALES" where nombre ilike \''.addslashes($nombre).'%\' and idhac='.$_SESSION['idhac'].' order by esthac,nombre';
        $con=$this->consulta('select * from "ANIMALES" where nombre ilike \''.addslashes($nombre).'%\' and idhac='.$_SESSION['idhac'].' and sexani=1 order by esthac,espani,nombre');
        $dato='';
    if($op==2) $dato='<select name=id >';
        while($reg=$this->row($con)){
             if($reg['id']==$id)
             $dato.='<option value='.$reg['id'].' selected=selected>'.$reg['nombre'].' - '.$reg['arete'].' - '.$this->esthac[$reg['esthac']].' - '.$this->espani[$reg['espani']].' </option>';
             else 
              $dato.='<option value='.$reg['id'].'>'.$reg['nombre'].' - '.$reg['arete'].'  - '.$this->esthac[$reg['esthac']].'  - '.$this->espani[$reg['espani']].' </option>';    
             $r[]=$reg;
        }
         if($op==2)  $dato.='</select>';
        if($op==1) return $r; else return $dato;
    }  
            public function listarAnimalesPadres($op,$nombre='',$id=0){
      //  echo 'select * from "ANIMALES" where nombre ilike \''.addslashes($nombre).'%\' and idhac='.$_SESSION['idhac'].' order by esthac,nombre';
        $con=$this->consulta('select * from "ANIMALES" where nombre ilike \''.addslashes($nombre).'%\' and idhac='.$_SESSION['idhac'].' and sexani=2 order by esthac,espani,nombre');
        $dato='';
    if($op==2) $dato='<select name=id >';
        while($reg=$this->row($con)){
             if($reg['id']==$id)
             $dato.='<option value='.$reg['id'].' selected=selected>'.$reg['nombre'].' - '.$reg['arete'].' - '.$this->esthac[$reg['esthac']].' - '.$this->espani[$reg['espani']].' </option>';
             else 
              $dato.='<option value='.$reg['id'].'>'.$reg['nombre'].' - '.$reg['arete'].'  - '.$this->esthac[$reg['esthac']].'  - '.$this->espani[$reg['espani']].' </option>';    
             $r[]=$reg;
        }
         if($op==2)  $dato.='</select>';
        if($op==1) return $r; else return $dato;
    }  
    //put your code here
    public function mostrarAnimal($id){
          $con=$this->consulta('select * from "ANIMALES" where id='.$id);
        if($reg=$this->row($con)){
            return $reg;
        }else{
            return FALSE;
        }
       
    }
       public function mostrarFotoAnimal($id,$msize){
           $con=$this->consulta('select * from "FOTO" where codid='.$id.' order by codfot desc');
           $fot='';
        if($reg=$this->row($con)){
           $fot.='<img src="../fotos/'.$reg['nomfoto'].'" width='.$msize.'>';
        }
         return $fot;
    }
       public function mostrarFotoAnimalURL($id,$msize){
           $con=$this->consulta('select * from "FOTO" where codid='.$id.' order by codfot desc');
           $fot='';
        if($reg=$this->row($con)){
           $fot.='http://hacienda.ikarana.com/fotos/'.rawurlencode($reg['nomfoto']).'';
        }
         return $fot;
    }
    public function mostrarFotosAnimal($id,$msize){
           $con=$this->consulta('select * from "FOTO" where codid='.$id);
           $fot='';
        while($reg=$this->row($con)){
           $fot.='<form method=POST><img src="../fotos/'.$reg['nomfoto'].'" width='.$msize.'><br><input type=hidden name=codfot value='.$reg['codfot'].'><input type=submit value=Eliminar name=bttelifoto></form><br>';
        }
         return $fot;
    }
    public function agregarFotoAnimal($id,$name){
      
   $query='INSERT INTO "FOTO"(
             tipofoto, codid, fecfoto, codusu, nomfoto)
    VALUES (1, '.$id.', \''.date('Y-m-d h:i:s').'\', 1, \''.$id.'_'.$name.'\');';
           $con=$this->consulta($query);
      return $con;  
    }
    
    public function modificarAnimalTabla($datos){
       
      if($datos['esthac']=='')$datos['esthac']=0; 
      if($datos['estsal']=='')$datos['estsal']=0; 
      if($datos['estrep']=='')$datos['estrep']=0; 
      
   
      if($datos['arete']=='')$datos['arete']=0;      if($datos['espani']=='')$datos['espani']=0; 
      if($datos['pesonac']=='')$datos['pesonac']=0;       if($datos['pesolle']=='')$datos['pesolle']=0;
      if($datos['fecnac']=='')$datos['fecnac']='01-01-1900';   if($datos['feclle']=='')$datos['feclle']='01-01-1900'; 
      if($datos['fecmue']=='')$datos['fecmue']='01-01-1900'; 
      $query='UPDATE "ANIMALES"
   SET arete='.$datos['arete'].', nombre=\''.$datos['nombre'].'\', fecnac=\''.$datos['fecnac'].'\',  idraza='.$datos['idraza'].', 
       idprov='.$datos['idprov'].', idpadre='.$datos['idpadre'].', idmadre='.$datos['idmadre'].',  fecmue=\''.$datos['fecmue'].'\',
       sexani='.$datos['sexani'].', espani='.$datos['espani'].',  esthac='.$datos['esthac'].', estsal='.$datos['estsal'].', estrep='.$datos['estrep'].'
 WHERE id='.$datos['id'].' ;';
           if($con=$this->consulta($query)){
               Echo "***Animal Modificado***";
           }else{
               Echo "Error en el ingreso de datos :<br>".$query;
           }
           
      return $con; 
        
       
        
    }
    
    
    public function modificarAnimal($datos){
         if($datos['nueraza']!=''){
          $query='select * from "RAZA" where detalle=\''.addslashes ($datos['nueraza']).'\';'; 
      $pConsulta=$this->consulta($query);
      if($reg=$this->row($pConsulta)){
          $datos['idraza']=$reg['id'];
      }else{
      $query='INSERT INTO "RAZA"(detalle)VALUES (\''.addslashes ($datos['nueraza']).'\');'; 
      $pConsulta=$this->consulta($query);
      $query='select * from "RAZA" where detalle=\''.addslashes ($datos['nueraza']).'\';'; 
      $pConsulta=$this->consulta($query);
       if($reg=$this->row($pConsulta)){
          $datos['idraza']=$reg['id'];
      }else{
           $datos['idraza']=1;
      }
      }   
      } 
       //validar que no sea proveedor nuevo
        if($datos['nueprov']!=''){
          $query='select * from "PROVEEDOR" where nompro=\''.addslashes ($datos['nueprov']).'\';'; 
      $pConsulta=$this->consulta($query);
      if($reg=$this->row($pConsulta)){
          $datos['idprov']=$reg['codpro'];
      }else{
      $query='INSERT INTO "PROVEEDOR"(nompro)VALUES (\''.addslashes ($datos['nueprov']).'\');'; 
      $pConsulta=$this->consulta($query);
      $query='select * from "PROVEEDOR" where nompro=\''.addslashes ($datos['nueprov']).'\';'; 
      $pConsulta=$this->consulta($query);
       if($reg=$this->row($pConsulta)){
          $datos['idprov']=$reg['codpro'];
      }else{
           $datos['idprov']=1;
      }
      }   
      } 
      
          if($datos['esthac']=='')$datos['esthac']=0; 
      if($datos['estsal']=='')$datos['estsal']=0; 
      if($datos['estrep']=='')$datos['estrep']=0; 
      
      if($datos['aretea']=='')$datos['aretea']='';  
      if($datos['arete']=='')$datos['arete']=0;       if($datos['tiplle']=='')$datos['tiplle']=0;  if($datos['espani']=='')$datos['espani']=0; 
      if($datos['pesonac']=='')$datos['pesonac']=0;       if($datos['pesolle']=='')$datos['pesolle']=0;
      if($datos['fecnac']=='')$datos['fecnac']='01-01-1900';   if($datos['feclle']=='')$datos['feclle']='01-01-1900'; 
       $query='UPDATE "ANIMALES"
   SET arete='.$datos['arete'].', nombre=\''.$datos['nombre'].'\', fecnac=\''.$datos['fecnac'].'\', feclle=\''.$datos['feclle'].'\', tiplle='.$datos['tiplle'].', idraza='.$datos['idraza'].', 
       idprov='.$datos['idprov'].', idpadre='.$datos['idpadre'].', idmadre='.$datos['idmadre'].',  pesonac='.$datos['pesonac'].', pesolle='.$datos['pesolle'].', 
       sexani='.$datos['sexani'].', espani='.$datos['espani'].', aretea=\''.$datos['aretea'].'\', esthac='.$datos['esthac'].', estsal='.$datos['estsal'].', estrep='.$datos['estrep'].'
 WHERE id='.$datos['id'].' ;';
           if($con=$this->consulta($query)){
               
           }else{
               Echo "Error en el ingreso de datos :<br>".$query;
           }
           
      return $con; 
        
        
    }
     public function crearControl($d){
        $query='INSERT INTO "CONTROLES"(
           idani, tipcon, descon, vitcon, 
           reccon, sigcon, diacon, medcon, 
            tracon, precon, revcon, svicon, 
            fetcon, dia2con, vit2con, idusu, 
            fecing, feccon)
    VALUES ('.$d['idani'].' ,'.$d['tipcon'].' , \''.$d['descon'].'\', \''.$d['vitcon'].'\', 
        \''.$d['reccon'].'\', \''.$d['sigcon'].'\', \''.$d['diacon'].'\',\''.$d['medcon'].'\',
            \''.$d['tracon'].'\',  \''.$d['precon'].'\',  \''.$d['revcon'].'\',  \''.$d['svicon'].'\', 
                \''.$d['fetcon'].'\',  \''.$d['dia2con'].'\',  \''.$d['vit2con'].'\',1,\''.date('Y-m-d h:i:s').'\'  ,\''.$d['feccon'].'\');';
          $con=$this->consulta($query);
        return $con;
    }
    public function listarControles($id){
         $query='select * from  "CONTROLES" where idani='.$id.' order by feccon desc ;';
          $con=$this->consulta($query);
          $controles='';
         
          
          while($r=  pg_fetch_assoc($con)){
              $controles.='<tr><th>Tipo:<th>'.$this->tipo[$r['tipcon']];
              $controles.='<tr><td>Fecha:<td>'.$r['feccon'];
              $controles.='<tr><td>Detalle:<td>';
              switch ($r['tipcon']){
                  case 1:{  $controles.='Desparasitación:'.$r['descon'].'<br>Vitaminas:'.$r['vitcon'].'<br>Reconstituyente a base de minerales:'.$r['reccon']; break;}
                  case 2:{  $controles.='Tomar signos:'.$r['sigcon'].'<br>Diagnostico:'.$r['diacon'].'<br>Medicación:'.$r['medcon'].'<br>Tratamiento:'.$r['tracon']; break;}
                  case 3:{  $controles.='Preñada:'.$r['precon'].'<br>Revisión de ovarios:'.$r['revcon']; break;}
                  case 4:{  $controles.='Signos vitales:'.$r['svicon'].'<br>Signos vitales feto:'.$r['fetcon'].'<br>Diagnostico:'.$r['dia2con'].'<br>Vitaminas y desparasitantes:'.$r['vit2con']; break;}
          }
         $controles.= '</TD><tr><TD colspan=2><center><form><input type=hidden name=idani value='.$r['idani'].'> <button name=bttecon value='.$r['id'].' onclick="javascript: return confirm(\'Esta seguro de Eliminar el Control y todos sus registros\');"><img src=../img/cancelar.jpg  > <br>Eliminar</button></center> </form></TD>';
          
          
                  }
        return $controles;
    }
    
    
    public function eliminarControl($id){
        $query='delete from  "CONTROLES" where id='.$id.';';
          $con=$this->consulta($query);
          RETURN $con;
        
    }
    
    public function cuadroResumen(){
        $resumen='<CENTER><form><TABLE border=1 class=table><tr>';
        
        $resumen.='<th>Por raza<th>Por sexo<th>Por especie<th>Por estado productivo<th>Por Estado Hacienda<tr><td>';
        $query='SELECT   count("ANIMALES".id),"RAZA".detalle,"RAZA".id ,esthac FROM "ANIMALES", "RAZA" WHERE espani=1 AND "RAZA".id = "ANIMALES".idraza and idhac='.$_SESSION['idhac'].' and esthac=1 group by "RAZA".detalle,"RAZA".id,esthac;';
        $con=$this->consulta($query);
        while($r=  pg_fetch_assoc($con)){
             $resumen.='<button style="width:100%;" type=submit name=bttrazacat value='.$r['id'].'> '.$r['detalle'].':'.$r['count'].'</button><br>';
          }
          
          $resumen.='<td>'; $s[1]='Hembras';$s[2]='Machos';$s[0]='No ingresado';
        $query='SELECT   count("ANIMALES".id),sexani,esthac FROM "ANIMALES" where esthac=1 AND espani=1 and idhac='.$_SESSION['idhac'].'  and esthac=1 group by sexani,esthac;';
        $con=$this->consulta($query);
        while($r=  pg_fetch_assoc($con)){
             $resumen.='<button style="width:100%;" type=submit name=bttsexcat value='.$r['sexani'].'>'.$s[$r['sexani']].':'.$r['count'].'</button><br>';
          } 
          
          
           
            $resumen.='<td>'; $s[1]='Vacuno';$s[2]='Equino';$s[3]='Ovino';$s[4]='Canino';$s[0]='No ingresado';
        $query='SELECT   count("ANIMALES".id),espani FROM "ANIMALES" where esthac=1 and idhac='.$_SESSION['idhac'].'  and esthac=1 group by espani,esthac;';
        $con=$this->consulta($query);
        while($r=  pg_fetch_assoc($con)){
             $resumen.='<button style="width:100%;" type=submit name=bttespcat value='.$r['espani'].'>'.$this->espani[$r['espani']].':'.$r['count'].'</button><br>';
          } 
          
              $resumen.='<td>'; //$s[1]='Vacuno';$s[2]='Equino';$s[3]='Ovino';$s[4]='Canino';$s[0]='No ingresado';
        $query='SELECT   count("ANIMALES".id),estrep,esthac FROM "ANIMALES" where idhac='.$_SESSION['idhac'].'  and  esthac=1 group by estrep,esthac order by estrep,esthac;';
        $con=$this->consulta($query);
        while($r=  pg_fetch_assoc($con)){
             $resumen.='<button style="width:100%;" type=submit name=bttrepcat value='.$r['estrep'].'>'.$this->estrep[$r['estrep']].':'.$r['count'].'</button><br>';
          } 
          
                 $resumen.='<td>';// $s[1]='Vacuno';$s[2]='Equino';$s[3]='Ovino';$s[4]='Canino';$s[0]='No ingresado';
        $query='SELECT   count("ANIMALES".id),esthac FROM "ANIMALES" where  idhac='.$_SESSION['idhac'].' group by esthac;';
        $con=$this->consulta($query);
        while($r=  pg_fetch_assoc($con)){
             $resumen.='<button style="width:100%;" type=submit name=bttestcat value='.$r['esthac'].'>'.$this->esthac[$r['esthac']].':'.$r['count'].'</button><br>';
          } 
          
          $resumen.='</table></form></center>';
          return $resumen; 
    }
    public function eliminarAnimal($id){
         $query='delete from  "ANIMALES" where id='.$id.';';
          $con=$this->consulta($query);
          RETURN $con;
    }
    public function eliminarFoto($codfot){
            $query='delete from  "FOTO" where codfot='.$codfot.';';
          $con=$this->consulta($query);
          RETURN $con;
    }
    
   
      public function mostrarImprimirPDF($pdf){
        $query='select * from "ANIMALES" where esthac=1 and idhac='.$_SESSION['idhac'].' order by arete';
       $idhac=$_SESSION['idhac'] ;
        $query='select "ANIMALES".id as codani, "ANIMALES".arete,"ANIMALES".nombre,"ANIMALES".espani,"ANIMALES".fecnac,"ANIMALES".feclle,"ANIMALES".tiplle,"RAZA".detalle,nompro,padre.nombre as nompadre,
         madre.nombre as nommadre, "ANIMALES".esthac, "ANIMALES".sexani,"ANIMALES".estrep from "ANIMALES","RAZA","PROVEEDOR","ANIMALES" as padre, "ANIMALES" as madre 
           where "ANIMALES".idpadre=padre.id and "ANIMALES".idmadre=madre.id and "ANIMALES".idprov="PROVEEDOR".codpro and 
           "RAZA".id="ANIMALES".idraza and "ANIMALES".idhac='.$idhac.' order by esthac,espani,nombre';
        
        $con=$this->consulta($query);
        
         /*
     * $pdf->SetFont('Arial','B',16);
$pdf->Cell(40,10,'¡Hola, Mundo!');
     */
        
        
       $n=0;
        while ($r= pg_fetch_assoc($con)){
                 
            $n++;
          
           $animal[$n][0]=$r['arete'];
           $animal[$n][1]=$r['nombre'];
           $animal[$n][2]=$r['fecnac'];
           $animal[$n][3]=$r['nompadre'];
           $animal[$n][4]=$r['nommadre'];
           $animal[$n][5]=$r['espani'];
           $animal[$n][6]=$r['esthac'];
           $animal[$n][7]=$r['codani'];
         //   echo "<td><center>".$url."<br>".$r['arete']."<br>".$r['nombre']."<br>".$r['fecnac']."<br>Papá:".$r['nompadre']."<br>Mamá:".$r['nommadre']."</center>";
        }
        
           $animal[$n+1][7]=''; $animal[$n+1][0]='';           $animal[$n+1][1]='';           $animal[$n+1][2]='';           $animal[$n+1][3]='';           $animal[$n+1][4]='';           $animal[$n+1][5]=''; $animal[$n+1][6]='';
           $animal[$n+2][7]='';    $animal[$n+2][0]='';           $animal[$n+2][1]='';           $animal[$n+2][2]='';           $animal[$n+2][3]='';           $animal[$n+2][4]='';           $animal[$n+2][5]=''; $animal[$n+2][6]='';
           $animal[$n+3][7]='';   $animal[$n+3][0]='';           $animal[$n+3][1]='';           $animal[$n+3][2]='';           $animal[$n+3][3]='';           $animal[$n+3][4]='';           $animal[$n+3][5]='';           $animal[$n+3][6]='';
        
           
        $anc=65;
        $salto=9;$salto1=22;$salto2=12;
        for ($i=1;$i<$n;$i=$i+9){
            
            //repetir 3 veces para hacer las fotos
            $c=0;$a=1;$b=2;
            for($j=1;$j<4;$j++){
            
            $pdf->SetFont('Arial','B',40);
            $pdf->Cell($anc,$salto1,$animal[$i+$c][0],'LT',0,'C');
            $pdf->Cell($anc,$salto1,$animal[$i+$a][0],'LT',0,'C');
            $pdf->Cell($anc,$salto1,$animal[$i+$b][0],'LTR',1,'C');
             
            $pdf->SetFont('Arial','B',22);
            $pdf->Cell($anc,$salto2,$animal[$i+$c][1],'L',0,'C');
            $pdf->Cell($anc,$salto2,$animal[$i+$a][1],'L',0,'C');
            $pdf->Cell($anc,$salto2,$animal[$i+$b][1],'LR',1,'C');
            
               $pdf->SetFont('Arial','B',22);
            $pdf->Cell($anc,$salto2,$animal[$i+$c][2],'L',0,'C');
            $pdf->Cell($anc,$salto2,$animal[$i+$a][2],'L',0,'C');
            $pdf->Cell($anc,$salto2,$animal[$i+$b][2],'LR',1,'C');
            //espani
             $pdf->SetFont('Arial','B',12);
            $pdf->Cell($anc,$salto1,$this->espani[$animal[$i+$c][5]].' - '.$this->esthac[$animal[$i+$c][6]],'L',0,'C');
            $pdf->Cell($anc,$salto1,$this->espani[$animal[$i+$a][5]].' - '.$this->esthac[$animal[$i+$a][6]],'L',0,'C');
            $pdf->Cell($anc,$salto1,$this->espani[$animal[$i+$b][5]].' - '.$this->esthac[$animal[$i+$b][6]],'LR',1,'C');
            
            $pdf->SetFont('Arial','B',14);
            $pdf->Cell($anc,$salto,'Padre: '.$animal[$i+$c][3],'L',0,'C');
            $pdf->Cell($anc,$salto,'Padre: '.$animal[$i+$a][3],'L',0,'C');
            $pdf->Cell($anc,$salto,'Padre: '.$animal[$i+$b][3],'LR',1,'C');
            
             $pdf->SetFont('Arial','B',14);
            $pdf->Cell($anc,$salto,'Madre: '.$animal[$i+$c][4],'LB',0,'C');
            $pdf->Cell($anc,$salto,'Madre: '.$animal[$i+$a][4],'LB',0,'C');
            $pdf->Cell($anc,$salto,'Madre: '.$animal[$i+$b][4],'LRB',1,'C');
            
            //--------------segunda fila
            $c=$c+3;
            $a=$a+3;
            $b=$b+3;
            }
            //Generar las fotos
              $c=0;$a=1;$b=2;$alto=86;$al=0;
            for($j=1;$j<4;$j++){
                  $url1= $this->mostrarFotoAnimalURL($animal[$i+$c][7], 400);
                  $url2= $this->mostrarFotoAnimalURL($animal[$i+$a][7], 400);
                  $url3= $this->mostrarFotoAnimalURL($animal[$i+$b][7], 400);
                  
                  //poner en el cuadro las imagenes
             //   $pdf->SetFont('Arial','B',40);
                  
                 $pdf->Cell($anc,$alto,'',1,0,'C');
                 if($url1!='')
                 $pdf->Image(($url1),10,10+($al*86),$anc,$alto);
                
              
                 $pdf->Cell($anc,$alto,'',1,0,'C');
                 if($url2!='')
                 $pdf->Image(($url2),10+$anc,10+($al*86),$anc,$alto);
                
                 
                 $pdf->Cell($anc,$alto,'',1,1,'C');
                 if($url3!='')
                 $pdf->Image(($url3),10+($anc*2),10+($al*86),$anc,$alto);
                 
                 
                           
                 
              //   $pdf->Cell($anc,$alto,($url3),1,1,'C');
             //    $pdf->Image($url3,$anc,$alto);
                  
            $c=$c+3;
            $a=$a+3;
            $b=$b+3;   
            $al=$al+1;
            }
            
          
        }        
      
        
        
        
        
       
        
    }
    
    
    public function mostrarImprimir($tipo){
        $query='select * from "ANIMALES" where esthac=1 and idhac='.$_SESSION['idhac'].' order by arete';
       $idhac=$_SESSION['idhac'] ;
        $query='select "ANIMALES".id as codani, "ANIMALES".arete,"ANIMALES".nombre,"ANIMALES".espani,"ANIMALES".fecnac,"ANIMALES".feclle,"ANIMALES".tiplle,"RAZA".detalle,nompro,padre.nombre as nompadre,
         madre.nombre as nommadre, "ANIMALES".esthac, "ANIMALES".sexani,"ANIMALES".estrep from "ANIMALES","RAZA","PROVEEDOR","ANIMALES" as padre, "ANIMALES" as madre 
           where "ANIMALES".idpadre=padre.id and "ANIMALES".idmadre=madre.id and "ANIMALES".idprov="PROVEEDOR".codpro and 
           "RAZA".id="ANIMALES".idraza and "ANIMALES".idhac='.$idhac.' order by esthac,nombre';
        
        $con=$this->consulta($query);
        $ta="<table border=1 style=\"font-size:50px;width:100%;\"  >";
        echo $ta;
        $n=0;
        while ($r= pg_fetch_assoc($con)){
            //ver la ultima foto
            
          $url= $this->mostrarFotoAnimal($r['codani'], 400);
          $url='';      
            $n++;
            if($n==4){echo "<tr>";}
            if($n==7){echo "</table><br>".$ta."<tr>";$n=1;}
            
            echo "<td><center>".$url."<br>".$r['arete']."<br>".$r['nombre']."<br>".$r['fecnac']."<br>Papá:".$r['nompadre']."<br>Mamá:".$r['nommadre']."</center>";
            
        }
        
       echo "</table></p><br>"; 
        
    }
    public function excelAnimales($idhac,$writer){
    $delimiter = ";";
    $filename = "animales" . date('Y-m-d') . ".csv";
    
    //create a file pointer
    $f = fopen('php://memory', 'w');
    
    //set column headers
    $fields = array('');
    fputcsv($f, $fields, $delimiter);
    
     $fields = array('N','Arete','Nombre','Fec Nacimiento','Fec Llegada','Especie','Tipo Llegada','Raza','Proveedor','Padre'
        ,'Madre','Estado Hacienda','Sexo','Estado Reproductivo');
    fputcsv($f, $fields, $delimiter);
    
     $con=$this->consulta('select "ANIMALES".arete,"ANIMALES".nombre,"ANIMALES".espani,"ANIMALES".fecnac,"ANIMALES".feclle,"ANIMALES".tiplle,"RAZA".detalle,nompro,padre.nombre as nompadre,
         madre.nombre as nommadre, "ANIMALES".esthac, "ANIMALES".sexani,"ANIMALES".estrep from "ANIMALES","RAZA","PROVEEDOR","ANIMALES" as padre, "ANIMALES" as madre 
           where "ANIMALES".idpadre=padre.id and "ANIMALES".idmadre=madre.id and "ANIMALES".idprov="PROVEEDOR".codpro and 
           "RAZA".id="ANIMALES".idraza and "ANIMALES".idhac='.$idhac.' order by esthac,nombre');
      $a=0;
    //output each row of the data, format line as csv and write to file pointer
 while($r=$this->row($con)){
     $a++;
        $status = ($row['status'] == '1')?'Active':'Inactive';
        $lineData = array($a,$r['arete'],utf8_encode($r['nombre']),$r['fecnac'],$r['feclle'],$this->espani[$r['espani']] ,$this->tiplle[$r['tiplle']],$r['detalle'],utf8_encode($r['nompro']),($r['nompadre'])        ,$r['nommadre'],$this->esthac[$r['esthac']],($r['sexani']),$this->estrep[$r['estrep']]);
        fputcsv($f, $lineData, $delimiter);
    }
    
    //move back to beginning of file
    fseek($f, 0);
    
    //set headers to download file rather than displayed
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '";');
    
    //output all remaining data on a file pointer
    fpassthru($f);

exit;
        /*
        
        $writer->setAuthor('Msc. Leandro García');

$format = array('font'=>'Arial','font-size'=>10,'font-style'=>'bold,italic', 'halign'=>'center');
    $writer->writeSheetRow('Sheet1', array('N','Arete','Nombre','Fec Nacimiento','Fec Llegada','Tipo Llegada','Raza','Proveedor','Padre'
        ,'Madre','Estado Hacienda','Sexo','Estado Reproductivo'),$format );

       $con=$this->consulta('select "ANIMALES".arete,"ANIMALES".nombre,"ANIMALES".fecnac,"ANIMALES".feclle,"ANIMALES".tiplle,"RAZA".detalle,nompro,padre.nombre as nompadre,
         madre.nombre as nommadre, "ANIMALES".esthac, "ANIMALES".sexani,"ANIMALES".estrep from "ANIMALES","RAZA","PROVEEDOR","ANIMALES" as padre, "ANIMALES" as madre 
           where "ANIMALES".idpadre=padre.id and "ANIMALES".idmadre=madre.id and "ANIMALES".idprov="PROVEEDOR".codpro and 
           "RAZA".id="ANIMALES".idraza and "ANIMALES".idhac='.$idhac.' order by esthac,nombre');
      $a=0;
        while($r=$this->row($con)){
       $a++;
            $writer->writeSheetRow('Sheet1', array($a,$r['arete'],utf8_encode($r['nombre']),$r['fecnac'],$r['feclle'],$this->tiplle[$r['tiplle']],$r['detalle'],utf8_encode($r['nompro']),($r['nompadre'])        ,$r['nommadre'],$this->esthac[$r['esthac']],($r['sexani']),$this->estrep[$r['estrep']]),$format );
        
        }
    $writer->writeToStdOut();
    */
    }
    public function empleadosSelect(){
         $clientes = $this->consulta('SELECT * FROM public."EMPLEADOS" where estemp=1 and idhac='.$_SESSION['idhac'].' order by apellido,nombre');
        $html = '<select name="idemp">';
        while ($cliente =pg_fetch_assoc( $clientes)) {
            $html .= '<option value="' . $cliente['idemp'] . '"> ' . $cliente['apellido'] . ' ' . $cliente['nombre'] . '</option>';
        }
        $html .= '</select>';
        return $html;
    }
    
    
      public function empleadosoption() {
        $clientes = $this->consulta('SELECT * FROM public."EMPLEADOS" where estemp=1 and idhac='.$_SESSION['idhac'].' order by apellido,nombre');
        $html = '';
        while ($cliente =pg_fetch_assoc( $clientes)) {
            $html .= '<option value="' . $cliente['idemp'] . '">' . $cliente['apellido'] . ' ' . $cliente['nombre'] . '</option>';
        }
        $html .= '';
        return $html;
    }  
    
    public function clientesSelect() {
        $clientes = $this->consulta('SELECT codcli, nomcli, telcli, celcli, estcli FROM public."CLIENTE" where estcli=1 and idhac='.$_SESSION['idhac'].'');
        $html = '<select name="codcli">';
        while ($cliente =pg_fetch_assoc( $clientes)) {
            $html .= '<option value="' . $cliente['codcli'] . '">' . $cliente['nomcli'] . '</option>';
        }
        $html .= '</select>';
        return $html;
    }
    
   public function clientesoption() {
        $clientes = $this->consulta('SELECT codcli, nomcli, telcli, celcli, estcli FROM public."CLIENTE" where estcli=1 and idhac='.$_SESSION['idhac'].'');
        $html = '';
        while ($cliente =pg_fetch_assoc( $clientes)) {
            $html .= '<option value="' . $cliente['codcli'] . '">' . $cliente['nomcli'] . '</option>';
        }
        $html .= '';
        return $html;
    }  
    
    public function tablaModificarSexo($idsex){
        
        echo '<table border=1 > <tr><th>Nombre<th>Arete<th>Fecha Nacimiento<th>Sexo<th>Especie<th>Raza<th>Procedencia<th>Madre<th>Padre<th>Estado Hacienda<th>Salud<th>Estado productivo<th>Fecha Muerto<th>Acción';
            $con=$this->consulta('select * from "ANIMALES" where sexani='.$idsex.' and idhac='.$_SESSION['idhac'].' order by esthac,nombre');
        while($selanimal=$this->row($con)){
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
  
            
        
      echo '<form><tr> <td><input type="text" value="'.$selanimal['nombre'].'" name="nombre" size=15 ></td> 
              <td><input type="number" value='.$selanimal['arete'].'  size=5 name="arete" style="width:100%;"></td>
                   
               <td><input type="date" value="'.$selanimal['fecnac'].'" name="fecnac"></td> 
                  <td><select name="sexani"><option value=1 '.$sexH.'>Hembra</option><option value=2 '.$sexM.'>Macho</option>
                      </select> </td> 
              <td><select name="espani">
            <option value=1 '.$esp1.'>Vacuno</option><option value=2 '.$esp2.'>Equino</option>
            <option value=3 '.$esp3.'>Ovino</option><option value=4 '.$esp4.'>Canino</option>
                      </select> </td> 
                  
              <td>'.$opraza.' 
                      
                <td>'.$oppro.' 
                  </td> 
               <td><select name="idmadre" style="width:150px;">'.$opanimalesm.' 
                      </select> </td> <td><select name="idpadre" style="width:150px;">'.$opanimalesp.'
                      </select> </td> 
             <td>       <select name="esthac" style="width:100px;">
                 <option value='.$selanimal['esthac'].'>'.$this->esthac[$selanimal['esthac']].'</option>   
                 <option value=0>'.$this->esthac[0].'</option>
                 <option value=1>'.$this->esthac[1].'</option>
                 <option value=2>'.$this->esthac[2].'</option>
                 <option value=3>'.$this->esthac[3].'</option>
                 <option value=4>'.$this->esthac[4].'</option>
                 <option value=5>'.$this->esthac[5].'</option>
                      </select>      
                      
<td><select name="estsal" style="width:100px;">
<option value='.$selanimal['estsal'].'>'.$this->estsal[$selanimal['estsal']].'</option> 
                 <option value=0>'.$this->estsal[0].'</option>
                 <option value=1>'.$this->estsal[1].'</option>
                 <option value=2>'.$this->estsal[2].'</option>
                 <option value=3>'.$this->estsal[3].'</option>
                 <option value=4>'.$this->estsal[4].'</option>
                      </select>      
<td><select name="estrep" style="width:100px;">
<option value='.$selanimal['estrep'].'>'.$this->estrep[$selanimal['estrep']].'</option> 
                 <option value=0>'.$this->estrep[0].'</option>
                 <option value=1>'.$this->estrep[1].'</option>
                 <option value=2>'.$this->estrep[2].'</option>
                 <option value=3>'.$this->estrep[3].'</option>
                 <option value=4>'.$this->estrep[4].'</option>
                    <option value=5>'.$this->estrep[5].'</option>
                    <option value=6>'.$this->estrep[6].'</option>
                    <option value=7>'.$this->estrep[7].'</option>
                      </select>    
               <td>   <input type="date" value="'.$selanimal['fecmue'].'" name="fecmue"> 
                 <td><button name=bttmodhor value='.$selanimal['id'].'> Modificar</buton><center><input type=hidden name=id value='.$selanimal['id'].'>
                     <input type=hidden name=bttsexcat value='.$idsex.'>   '
              . '</tr></form>';
            
            
            
            
        }
        
        echo '</table>';
    }
    
    public function tablaModificarEstadoHacienda($esthac){
        
        
        
        echo '<table border=1 class="table table-striped"> <tr><th>Nombre<th>Arete<th>Fecha Nacimiento<th>Sexo/Especie<th>Raza/Procedencia<th>Madre/Padre<th>Estado Hacienda<th>Salud<th>Estado productivo<th>Fecha Muerto<th>Acción';
            $con=$this->consulta('select * from "ANIMALES" where esthac='.$esthac.' and idhac='.$_SESSION['idhac'].' order by esthac,nombre');
        while($selanimal=$this->row($con)){
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
  
            
        
      echo '<form><tr> <td><input type="text" value="'.$selanimal['nombre'].'" name="nombre" size=15 ></td> 
              <td><input type="number" value='.$selanimal['arete'].'  size=5 name="arete" style="width:100%;"></td>
                   
               <td><input type="date" value="'.$selanimal['fecnac'].'" name="fecnac"></td> 
                  <td><select name="sexani"><option value=1 '.$sexH.'>Hembra</option><option value=2 '.$sexM.'>Macho</option>
                      </select> 
              <br> Especie<select name="espani">
            <option value=1 '.$esp1.'>Vacuno</option><option value=2 '.$esp2.'>Equino</option>
            <option value=3 '.$esp3.'>Ovino</option><option value=4 '.$esp4.'>Canino</option>
                      </select> </td> 
                  
              <td>'.$opraza.' 
                      
                <br>Proc: '.$oppro.' 
                  </td> 
               <td><select name="idmadre" style="width:100px;">'.$opanimalesm.' 
                      </select> <br> Padre:<select name="idpadre" style="width:100px;">'.$opanimalesp.'
                      </select> </td> 
             <td>       <select name="esthac" style="width:100px;">
                 <option value='.$selanimal['esthac'].'>'.$this->esthac[$selanimal['esthac']].'</option>   
                 <option value=0>'.$this->esthac[0].'</option>
                 <option value=1>'.$this->esthac[1].'</option>
                 <option value=2>'.$this->esthac[2].'</option>
                 <option value=3>'.$this->esthac[3].'</option>
                 <option value=4>'.$this->esthac[4].'</option>
                 <option value=5>'.$this->esthac[5].'</option>
                      </select>      
                      
<td><select name="estsal" style="width:100px;">
<option value='.$selanimal['estsal'].'>'.$this->estsal[$selanimal['estsal']].'</option> 
                 <option value=0>'.$this->estsal[0].'</option>
                 <option value=1>'.$this->estsal[1].'</option>
                 <option value=2>'.$this->estsal[2].'</option>
                 <option value=3>'.$this->estsal[3].'</option>
                 <option value=4>'.$this->estsal[4].'</option>
                      </select>      
<td><select name="estrep" style="width:100px;">
<option value='.$selanimal['estrep'].'>'.$this->estrep[$selanimal['estrep']].'</option> 
                 <option value=0>'.$this->estrep[0].'</option>
                 <option value=1>'.$this->estrep[1].'</option>
                 <option value=2>'.$this->estrep[2].'</option>
                 <option value=3>'.$this->estrep[3].'</option>
                 <option value=4>'.$this->estrep[4].'</option>
                    <option value=5>'.$this->estrep[5].'</option>
                    <option value=6>'.$this->estrep[6].'</option>
                    <option value=7>'.$this->estrep[7].'</option>
                      </select>    
      <td>   <input type="date" value="'.$selanimal['fecmue'].'" name="fecmue">   
                 <td><button name=bttmodhor value='.$selanimal['id'].'> Modificar</buton><center><input type=hidden name=id value='.$selanimal['id'].'>
                     <input type=hidden name=bttestcat value='.$esthac.'>   '
              . '</tr></form>';
            
            
            
            
        }
        
        echo '</table>';
    }
    public function tablaModificarReproductivo($estrep){
        
        
        echo '<table border=1 class="table table-striped"> <tr><th>Nombre<th>Arete<th>Fecha Nacimiento<th>Sexo<th>Especie<th>Raza<th>Procedencia<th>Madre<th>Padre<th>Estado Hacienda<th>Salud<th>Estado productivo<th>Fecha Muerto<th>Acción';
            $con=$this->consulta('select * from "ANIMALES" where estrep='.$estrep.' and idhac='.$_SESSION['idhac'].' order by esthac,nombre');
        while($selanimal=$this->row($con)){
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
 
            
        
      echo '<form><tr> <td><input type="text" value="'.$selanimal['nombre'].'" name="nombre" size=15 ></td> 
              <td><input type="number" value='.$selanimal['arete'].'  size=5 name="arete" style="width:100%;"></td>
                   
               <td><input type="date" value="'.$selanimal['fecnac'].'" name="fecnac"></td> 
                  <td><select name="sexani"><option value=1 '.$sexH.'>Hembra</option><option value=2 '.$sexM.'>Macho</option>
                      </select> </td> 
              <td><select name="espani">
            <option value=1 '.$esp1.'>Vacuno</option><option value=2 '.$esp2.'>Equino</option>
            <option value=3 '.$esp3.'>Ovino</option><option value=4 '.$esp4.'>Canino</option>
                      </select> </td> 
                  
              <td>'.$opraza.' 
                      
                <td>'.$oppro.' 
                  </td> 
               <td><select name="idmadre" style="width:150px;">'.$opanimalesm.' 
                      </select> </td> <td><select name="idpadre" style="width:150px;">'.$opanimalesp.'
                      </select> </td> 
             <td>       <select name="esthac" style="width:100px;">
                 <option value='.$selanimal['esthac'].'>'.$this->esthac[$selanimal['esthac']].'</option>   
                 <option value=0>'.$this->esthac[0].'</option>
                 <option value=1>'.$this->esthac[1].'</option>
                 <option value=2>'.$this->esthac[2].'</option>
                 <option value=3>'.$this->esthac[3].'</option>
                 <option value=4>'.$this->esthac[4].'</option>
                 <option value=5>'.$this->esthac[5].'</option>
                      </select>      
                      
<td><select name="estsal" style="width:100px;">
<option value='.$selanimal['estsal'].'>'.$this->estsal[$selanimal['estsal']].'</option> 
                 <option value=0>'.$this->estsal[0].'</option>
                 <option value=1>'.$this->estsal[1].'</option>
                 <option value=2>'.$this->estsal[2].'</option>
                 <option value=3>'.$this->estsal[3].'</option>
                 <option value=4>'.$this->estsal[4].'</option>
                      </select>      
<td><select name="estrep" style="width:100px;">
<option value='.$selanimal['estrep'].'>'.$this->estrep[$selanimal['estrep']].'</option> 
                 <option value=0>'.$this->estrep[0].'</option>
                 <option value=1>'.$this->estrep[1].'</option>
                 <option value=2>'.$this->estrep[2].'</option>
                 <option value=3>'.$this->estrep[3].'</option>
                 <option value=4>'.$this->estrep[4].'</option>
                    <option value=5>'.$this->estrep[5].'</option>
                    <option value=6>'.$this->estrep[6].'</option>
                    <option value=7>'.$this->estrep[7].'</option>
                      </select>    
               <td>   <input type="date" value="'.$selanimal['fecmue'].'" name="fecmue"> 
                 <td><button name=bttmodhor value='.$selanimal['id'].'> Modificar</buton><center><input type=hidden name=id value='.$selanimal['id'].'>
                     <input type=hidden name=bttrepcat value='.$estrep.'>   '
              . '</tr></form>';
            
            
            
            
        }
        
        echo '</table>';
    }
    
    public function tablaModificarEspecie($espani){
        
        echo '<table border=1 class="table table-striped"> <tr><th>Nombre<th>Arete<th>Fecha Nacimiento<th>Sexo<th>Especie<th>Raza<th>Procedencia<th>Madre<th>Padre<th>Estado Hacienda<th>Salud<th>Estado productivo<th>Fecha Muerto<th>Acción';
            $con=$this->consulta('select * from "ANIMALES" where espani='.$espani.' and idhac='.$_SESSION['idhac'].' order by esthac,nombre');
        while($selanimal=$this->row($con)){
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
 
            
        
      echo '<form><tr> <td><input type="text" value="'.$selanimal['nombre'].'" name="nombre" size=15 ></td> 
              <td><input type="number" value='.$selanimal['arete'].'  size=5 name="arete" style="width:100%;"></td>
                   
               <td><input type="date" value="'.$selanimal['fecnac'].'" name="fecnac"></td> 
                  <td><select name="sexani"><option value=1 '.$sexH.'>Hembra</option><option value=2 '.$sexM.'>Macho</option>
                      </select> </td> 
              <td><select name="espani">
            <option value=1 '.$esp1.'>Vacuno</option><option value=2 '.$esp2.'>Equino</option>
            <option value=3 '.$esp3.'>Ovino</option><option value=4 '.$esp4.'>Canino</option>
                      </select> </td> 
                  
              <td>'.$opraza.' 
                      
                <td>'.$oppro.' 
                  </td> 
               <td><select name="idmadre" style="width:150px;">'.$opanimalesm.' 
                      </select> </td> <td><select name="idpadre" style="width:150px;">'.$opanimalesp.'
                      </select> </td> 
             <td>       <select name="esthac" style="width:100px;">
                 <option value='.$selanimal['esthac'].'>'.$this->esthac[$selanimal['esthac']].'</option>   
                 <option value=0>'.$this->esthac[0].'</option>
                 <option value=1>'.$this->esthac[1].'</option>
                 <option value=2>'.$this->esthac[2].'</option>
                 <option value=3>'.$this->esthac[3].'</option>
                 <option value=4>'.$this->esthac[4].'</option>
                 <option value=5>'.$this->esthac[5].'</option>
                      </select>      
                      
<td><select name="estsal" style="width:100px;">
<option value='.$selanimal['estsal'].'>'.$this->estsal[$selanimal['estsal']].'</option> 
                 <option value=0>'.$this->estsal[0].'</option>
                 <option value=1>'.$this->estsal[1].'</option>
                 <option value=2>'.$this->estsal[2].'</option>
                 <option value=3>'.$this->estsal[3].'</option>
                 <option value=4>'.$this->estsal[4].'</option>
                      </select>      
<td><select name="estrep" style="width:100px;">
<option value='.$selanimal['estrep'].'>'.$this->estrep[$selanimal['estrep']].'</option> 
                 <option value=0>'.$this->estrep[0].'</option>
                 <option value=1>'.$this->estrep[1].'</option>
                 <option value=2>'.$this->estrep[2].'</option>
                 <option value=3>'.$this->estrep[3].'</option>
                 <option value=4>'.$this->estrep[4].'</option>
                    <option value=5>'.$this->estrep[5].'</option>
                    <option value=6>'.$this->estrep[6].'</option>
                    <option value=7>'.$this->estrep[7].'</option>
                      </select>    
               <td>   <input type="date" value="'.$selanimal['fecmue'].'" name="fecmue"> 
                 <td><button name=bttmodhor value='.$selanimal['id'].'> Modificar</buton><center><input type=hidden name=id value='.$selanimal['id'].'>
                     <input type=hidden name=bttespcat value='.$espani.'>   '
              . '</tr></form>';
            
            
            
            
        }
        
        echo '</table>';
    }
    public function tablaModificarRaza($idraza){
        echo '<table border=1 class="table table-striped"> <tr><th>Nombre<th>Arete<th>Fecha Nacimiento<th>Sexo<th>Especie<th>Raza<th>Procedencia<th>Madre<th>Padre<th>Estado Hacienda<th>Salud<th>Estado productivo<th>Fecha Muerto<th>Acción';
            $con=$this->consulta('select * from "ANIMALES" where idraza='.$idraza.' and idhac='.$_SESSION['idhac'].' order by esthac,nombre');
        while($selanimal=$this->row($con)){
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

            
        
      echo '<form><tr> <td><input type="text" value="'.$selanimal['nombre'].'" name="nombre" size=15 ></td> 
              <td><input type="number" value='.$selanimal['arete'].'  size=5 name="arete" style="width:100%;"></td>
                   
               <td><input type="date" value="'.$selanimal['fecnac'].'" name="fecnac"></td> 
                  <td><select name="sexani"><option value=1 '.$sexH.'>Hembra</option><option value=2 '.$sexM.'>Macho</option>
                      </select> </td> 
              <td><select name="espani">
            <option value=1 '.$esp1.'>Vacuno</option><option value=2 '.$esp2.'>Equino</option>
            <option value=3 '.$esp3.'>Ovino</option><option value=4 '.$esp4.'>Canino</option>
                      </select> </td> 
                  
              <td>'.$opraza.' 
                      
                <td>'.$oppro.' 
                  </td> 
               <td><select name="idmadre" style="width:150px;">'.$opanimalesm.' 
                      </select> </td> <td><select name="idpadre" style="width:150px;">'.$opanimalesp.'
                      </select> </td> 
             <td>       <select name="esthac" style="width:100px;">
                 <option value='.$selanimal['esthac'].'>'.$this->esthac[$selanimal['esthac']].'</option>   
                 <option value=0>'.$this->esthac[0].'</option>
                 <option value=1>'.$this->esthac[1].'</option>
                 <option value=2>'.$this->esthac[2].'</option>
                 <option value=3>'.$this->esthac[3].'</option>
                 <option value=4>'.$this->esthac[4].'</option>
                 <option value=5>'.$this->esthac[5].'</option>
                      </select>      
                      
<td><select name="estsal" style="width:100px;">
<option value='.$selanimal['estsal'].'>'.$this->estsal[$selanimal['estsal']].'</option> 
                 <option value=0>'.$this->estsal[0].'</option>
                 <option value=1>'.$this->estsal[1].'</option>
                 <option value=2>'.$this->estsal[2].'</option>
                 <option value=3>'.$this->estsal[3].'</option>
                 <option value=4>'.$this->estsal[4].'</option>
                      </select>      
<td><select name="estrep" style="width:100px;">
<option value='.$selanimal['estrep'].'>'.$this->estrep[$selanimal['estrep']].'</option> 
                 <option value=0>'.$this->estrep[0].'</option>
                 <option value=1>'.$this->estrep[1].'</option>
                 <option value=2>'.$this->estrep[2].'</option>
                 <option value=3>'.$this->estrep[3].'</option>
                 <option value=4>'.$this->estrep[4].'</option>
                    <option value=5>'.$this->estrep[5].'</option>
                    <option value=6>'.$this->estrep[6].'</option>
                    <option value=7>'.$this->estrep[7].'</option>
                      </select>    
               <td>   <input type="date" value="'.$selanimal['fecmue'].'" name="fecmue"> 
                 <td><button name=bttmodhor value='.$selanimal['id'].'> Modificar</buton><center><input type=hidden name=id value='.$selanimal['id'].'>
                     <input type=hidden name=bttrazacat value='.$idraza.'>   '
              . '</tr></form>';
        }
        
        echo '</table>';
    }
    
    
    
  public function mostraListaReproduccionAtender($anio, $mes) {
      

    // Calcular las fechas de inicio y fin del rango (20 días antes y después de hoy)
    $fechaInicio = date('Y-m-d', strtotime('-40 days'));
    $fechaFin = date('Y-m-d', strtotime('+40 days'));

    // Consulta SQL para obtener los animales que cumplen con los criterios de procesos activos
    $sqlProcesos = "SELECT
                a.id, a.nombre AS nombre_animal, a.arete, a.esthac, a.espani, a.fecnac,
                r.fecpro, r.fecres, r.fecrev,
                r.tiprep AS tipo_rep,
                r.tipres AS tipo_res
            FROM \"ANIMALES\" a
            LEFT JOIN \"REPRODUCCION\" r ON a.id = r.idmadre
            WHERE
                a.sexani = 1 AND
                a.esthac = 1 AND
                a.espani = 1 AND
                a.idhac = {$_SESSION['idhac']} AND (
                    (r.fecpro BETWEEN '$fechaInicio' AND '$fechaFin') OR
                    (r.fecres BETWEEN '$fechaInicio' AND '$fechaFin') OR
                    (r.fecrev BETWEEN '$fechaInicio' AND '$fechaFin')
                )
            ORDER BY a.nombre, estrep";

    // Consulta SQL para obtener los animales sin procesos activos y mayores a 13 meses
    $fechaMinimaNacimiento = date('Y-m-d', strtotime('-13 months'));
    $sqlSinProcesos = "SELECT
                a.id, a.nombre AS nombre_animal, a.arete, a.esthac, a.espani, a.fecnac
            FROM \"ANIMALES\" a
            WHERE
                a.sexani = 1 AND
                a.esthac = 1 AND
                a.espani = 1 AND
                a.idhac = {$_SESSION['idhac']} AND NOT EXISTS (
                    SELECT 1 FROM \"REPRODUCCION\" r WHERE r.idmadre = a.id
                ) AND a.fecnac <= '$fechaMinimaNacimiento'
            ORDER BY a.nombre";

    // Ejecutar ambas consultas
    $stmtProcesos = $this->consulta($sqlProcesos);
    $resultadosProcesos = pg_fetch_all($stmtProcesos);

    $stmtSinProcesos = $this->consulta($sqlSinProcesos);
    $resultadosSinProcesos = pg_fetch_all($stmtSinProcesos);

    // Inicializar el listado de animales a mostrar en la tabla
    $tabla = [];

    // Procesar los resultados con procesos activos
    foreach ($resultadosProcesos as $fila) {
        $id = $fila['id'];
        $nombre_animal = $fila['nombre_animal'];
        $arete = $fila['arete'];
        $esthac = $this->esthac[$fila['esthac']];
        $espani = $this->espani[$fila['espani']];
        $fecnac = $fila['fecnac'];
        $tipo_rep = $fila['tipo_rep'];
        $tipo_res = $fila['tipo_res'];
        $fecpro = $fila['fecpro'];
        $fecres = $fila['fecres'];
        $fecrev = $fila['fecrev'];

        // Calcular la edad en meses
        $edad_meses = round((strtotime(date('Y-m-d')) - strtotime($fecnac)) / (30 * 24 * 60 * 60));

        // Descripción del proceso
        $descripcion = $this->tiprep[$tipo_rep] . ' - ' . $this->tipres[$tipo_res];
        $fechas = "Proceso: " . ($fecpro ?? 'N/A') . ", Parto: " . ($fecres ?? 'N/A') . ", Revisión: " . ($fecrev ?? 'N/A');

        $tabla[] = [
            'id' => $id,
            'nombre' => $nombre_animal,
            'arete' => $arete,
            'esthac' => $esthac,
            'espani' => $espani,
            'edad_meses' => $edad_meses,
            'descripcion' => $descripcion,
            'fechas' => $fechas,
            'resaltado' => false
        ];
    }

    // Procesar los resultados sin procesos activos
    foreach ($resultadosSinProcesos as $fila) {
        $id = $fila['id'];
        $nombre_animal = $fila['nombre_animal'];
        $arete = $fila['arete'];
        $esthac = $this->esthac[$fila['esthac']];
        $espani = $this->espani[$fila['espani']];
        $fecnac = $fila['fecnac'];

        // Calcular la edad en meses
        $edad_meses = round((strtotime(date('Y-m-d')) - strtotime($fecnac)) / (30 * 24 * 60 * 60));

        // Buscar el último registro en REPRODUCCION
        $sqlUltimoRegistro = "SELECT fecpro, fecres, fecrev FROM \"REPRODUCCION\" WHERE idmadre = $id ORDER BY fecrev DESC, fecres DESC, fecpro DESC LIMIT 1";
        $ultimoRegistro = $this->consulta($sqlUltimoRegistro);
        $filaUltimoRegistro = pg_fetch_assoc($ultimoRegistro);

        $descripcion = 'Sin proceso activo';
        $fechas = 'N/A';
        $resaltado = false;

        if ($filaUltimoRegistro) {
            $fecpro = $filaUltimoRegistro['fecpro'] ?? 'N/A';
            $fecres = $filaUltimoRegistro['fecres'] ?? 'N/A';
            $fecrev = $filaUltimoRegistro['fecrev'] ?? 'N/A';

            $fechas = "Último Proceso: $fecpro, Último Parto: $fecres, Última Revisión: $fecrev";
            $resaltado = true; // Marcar para resaltar
        }

        $tabla[] = [
            'id' => $id,
            'nombre' => $nombre_animal,
            'arete' => $arete,
            'esthac' => $esthac,
            'espani' => $espani,
            'edad_meses' => $edad_meses,
            'descripcion' => $descripcion,
            'fechas' => $fechas,
            'resaltado' => $resaltado
        ];
    }

    // Mostrar el formulario y la tabla
    echo "<form method='post'>";
    if (empty($tabla)) {
        echo "No hay animales con procesos de reproducción que finalicen entre el rango de 20 días antes y 20 días después de la fecha actual, ni animales en espera.";
    } else {
        echo "<center><table border=\"1\" class=\"table table-bordered table-striped\">";
        echo "<tr>
                <th>Nombre</th>
                <th>Arete</th>
                <th>Estado Hacienda</th>
                <th>Estado Animal</th>
                <th>Edad (meses)</th>
                <th>Reproducción</th>
                <th>Fechas</th>
               
              </tr>";
        foreach ($tabla as $a) {
            $rowStyle = $a['resaltado'] ? 'style="color: red;"' : '';
            echo "<tr $rowStyle>
                    <td><h2>{$a['nombre']}</h2></td>
                    <td>{$a['arete']}</td>
                    <td>{$a['esthac']}</td>
                    <td>{$a['espani']}</td>
                    <td>{$a['edad_meses']}</td>
                    <td>{$a['descripcion']}</td>
                    <td>{$a['fechas']}</td>
                  
                  </tr>";
        }
        echo "</table></center>";
    }
    echo "</form>";
}



    public function mostraListaReproduccion($anio, $mes) {
    // Obtener la fecha mínima de nacimiento para 13 meses de antigüedad
    $fechaMinimaNacimiento = date('Y-m-d', strtotime("-13 months", strtotime("$anio-$mes-01")));
    
    // Consulta SQL para obtener los animales que cumplen los criterios básicos
    $sql = "SELECT
                a.id, a.nombre AS nombre_animal, a.arete, a.esthac, a.espani, a.fecnac,
                r.fecpro, r.fecres, r.fecrev,
                r.tiprep AS tipo_rep,
                r.tipres AS tipo_res
            FROM \"ANIMALES\" a
            LEFT JOIN \"REPRODUCCION\" r ON a.id = r.idmadre
            WHERE
                a.sexani = 1 AND
                a.esthac = 1 AND
                a.espani = 1 AND
                a.fecnac <= '$fechaMinimaNacimiento' AND
                a.idhac = {$_SESSION['idhac']}
            ORDER BY a.nombre, estrep";
    
    // Ejecutar la consulta
    $stmt = $this->consulta($sql);
    $resultados = pg_fetch_all($stmt);

    // Inicializar el listado de animales a mostrar en la tabla
    $tabla = [];

    // Procesar los resultados
    foreach ($resultados as $fila) {
        $id = $fila['id'];
        $nombre_animal = $fila['nombre_animal'];
        $arete = $fila['arete'];
        $esthac = $this->esthac[$fila['esthac']];
        $espani = $this->espani[$fila['espani']];
        $fecnac = $fila['fecnac'];
        $tipo_rep = $fila['tipo_rep'];
        $tipo_res = $fila['tipo_res'];
        $fecpro = $fila['fecpro'];
        $fecres = $fila['fecres'];
        $fecrev = $fila['fecrev'];

        // Calcular la edad en meses
        $edad_meses = round((strtotime("$anio-$mes-01") - strtotime($fecnac)) / (30 * 24 * 60 * 60));

        // Verificar las fechas si existen
        $mostrar = false;
        if (is_null($fecpro) && is_null($fecres) && is_null($fecrev)) {
            $mostrar = true; // Si no tiene fechas, debe estar en el listado
        } else {
            $fechaComparacion = "$anio-$mes-01";
            if ((!is_null($fecpro) && date('Y-m', strtotime($fecpro)) == date('Y-m', strtotime($fechaComparacion))) ||
                (!is_null($fecres) && date('Y-m', strtotime($fecres)) == date('Y-m', strtotime($fechaComparacion))) ||
                (!is_null($fecrev) && date('Y-m', strtotime($fecrev)) == date('Y-m', strtotime($fechaComparacion)))) {
                $mostrar = true;
            }
        }

        if ($mostrar) {
            $descripcion = $this->tiprep[$tipo_rep] . ' - ' . $this->tipres[$tipo_res];
            $fechas = "Proceso: " . ($fecpro ?? 'N/A') . ", Parto: " . ($fecres ?? 'N/A') . ", Revisión: " . ($fecrev ?? 'N/A');
            $tabla[] = [
                'id' => $id,
                'nombre' => $nombre_animal,
                'arete' => $arete,
                'esthac' => $esthac,
                'espani' => $espani,
                'edad_meses' => $edad_meses,
                'descripcion' => $descripcion,
                'fechas' => $fechas
            ];
        }
    }

    // Mostrar el formulario y la tabla
    echo "<form method='post'>";
    if (empty($tabla)) {
        echo "No hay animales que necesiten atención en el mes $mes del año $anio.";
    } else {
        echo "<center><table border=\"1\" class=\"table table-bordered table-striped\">";
        echo "<tr>
                <th>Nombre</th>
                <th>Arete</th>
                <th>Estado Hacienda</th>
                <th>Estado Animal</th>
                <th>Edad (meses)</th>
                <th>Reproducción</th>
                <th>Fechas</th>
                <th>Acción</th>
              </tr>";
        foreach ($tabla as $a) {
            echo "<tr>
                    <td><h2>{$a['nombre']}</h2></td>
                    <td>{$a['arete']}</td>
                    <td>{$a['esthac']}</td>
                    <td>{$a['espani']}</td>
                    <td>{$a['edad_meses']}</td>
                    <td>{$a['descripcion']}</td>
                    <td>{$a['fechas']}</td>
                    <td><button name='bttani' value='{$a['id']}'> <img src='../img/modif.jpg'> <br>Seleccionar</button></td>
                  </tr>";
        }
        echo "</table></center>";
    }
    echo "</form>";
}
   PUBLIC function subirFoto($datos,$f){
    
$dir_subida = '/fotos/';
$dir_subida = '../fotos/';
$fichero_subido = $dir_subida.''.$datos['id'].'_'. basename($f['fotoa']['name']);
echo $f['fotoa']['tmp_name'];
//$fichero_subido = $dir_subida.''.$datos['id'].'_'. basename($f['fotoa']['name']);

if (move_uploaded_file($f['fotoa']['tmp_name'], $fichero_subido)) {
   // echo "El fichero es válido y se subió con éxito.\n";
    
    if(!$this->agregarFotoAnimal($datos['id'],$f['fotoa']['name'])){
        echo "Error al ingresar a la BD";
    }ELSE{
        Echo "*** Foto agregada *** <br>";
    }
} else {
    echo "¡Posible ataque de subida de ficheros!\n";
}

}

 public function asignarAnimalGrupo($idgru,$idani){
         $sql='insert into "ANIMAL_GRUPO" (idani,idgru) values ('.$idani.','.$idgru.');';
          if($this->consulta($sql )){
             echo "<div class=mesajeok >Nuevo dato registrado</div>";
         }else
         {
              echo "<div class=errores >Error al crear el nuevo dato BDD</div>";
         }  
     }
}
