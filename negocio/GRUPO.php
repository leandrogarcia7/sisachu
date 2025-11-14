<?php
require_once("ANIMALES.php");
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GRUPO
 *
 * @author LGT-5
 */
class GRUPO extends ANIMALES{
    public $estgru= array('NO APLICA','TERNERA','FIERRO','VIENTRE','LECHE','SECA','TORO','TERNERO / TORETE'); //put your code here
     public $id,$detalle;
     
     public function mostrarGrupo($idgru){
          $con=$this->consulta('select * from "GRUPO" where id='.$idgru);
        
        $a=$this->row($con);
            return $a;
       
         
     }
     public function mostrarInicio(){
         echo '<center>
             <form>
             <b>Buscar por detalle: </b> <input type=text placeholder=PASTOREO1 name=txtbuscar >
             <button type="submit" name="bttbuscar" > <img src="../img/buscar.jpg" alt=""/>  <BR>BUSCAR GRUPO</button>
             <br>
             <button type="submit" name="bttcrear" > <img src="../img/anadir.png" alt=""/>  <BR>CREAR GRUPO</button>
             <button type="submit" name="bttresumen" > <img src="../img/cuadernorojo.png" alt=""/>  <BR>RESUMEN GRUPO</button>
             </center>';
         
     }
     
     public function buscarGrupo($txtbuscar){
         
          echo '  <form> <center>
        <table border="1" class=table style="width:50%">
            <tr>
                <th>Id</th><th>Detalle</th><th>Acción</th>
            </tr>';
    

      //   $fotos= $ani->mostrarFotosAnimal($a['id'],300);
        
        
        
         $con=$this->consulta('select * from "GRUPO" where detalle ilike \''.addslashes($txtbuscar).'%\' and idhac='.$_SESSION['idhac'].' order by detalle');
    

        while($a=$this->row($con)){
                    
        echo '<tr>
                <td><h2>'.$a['id'].'</h2></td><td>'.$a['detalle'].'</td><td><button name=bttsel value='.$a['id'].'> <img src=../img/modif.jpg  > <br>Seleccionar</button></td>
                    <td><button name=btteli onclick="javascript: return confirm(\'Esta seguro de Eliminar la Grupo\');" value='.$a['id'].'><img src=../img/cancelar.jpg  > <br>Eliminar</button></td>
            </tr>';
    }
    
    echo '</table>
        
    </center></form>';
    }   
         
    public function mostrarModificar($id){
        $con=$this->consulta('select * from "GRUPO" where id='.$id);
        
        if($a=$this->row($con)){
            echo "<center><form><table BORDER=1><th colspan=2><center> Modificar los datos de Grupo</center> <tr><th>Id<td>".$a['id']." <tr><th>Detalle<td><input type=text name=detalle value=\"".$a['detalle']."\">
              
<tr><th>Categoría</th>
            <td>
                <select name='estgru'>";
                
                // Definir las opciones disponibles
                $estgru = $this->estgru;
                 echo "<option value=".$a['estgru']." selected>".$this->estgru[$a['estgru']]."</option>";
                // Generar dinámicamente las opciones del select
           foreach ($estgru as $index => $categoria) {
    echo "<option value='$index'>$categoria</option>";
}

echo "

<tr><th colspan=2><center><button name=bttmod class=bttmod > <img src='../img/guardar.jpg'> <br>GUARDAR</button></center> </td></table>
               <input type=hidden name=id  value=".$a['id']." > </form></center>";
            
            //mostrar para agregar animales al grupo, listar todos los animales de una granja
            
        echo '<br><br><center><form>Selecciona el animal que deseas agregar al grupo: <br><select name=idani>  ';
         echo $this->listarAnimales(3);  
       echo '</select><input type=hidden name=idgru value='.$id.'><button name=bttasigru > <img src="../img/anadir.png"> <br>ASIGNAR</button> </center>';     
       
       ECHO '<br><br><center>Listado de animales asignados al grupo</center><br>';
       
     $con=  $this->listarAnimalesGrupo($id);
       echo "<center><table><th>Arete - Nombre";
         while($a=$this->row($con)){
             echo '<tr><td><form>'.$a['arete'].' - '.$a['nombre'].'<td> 
                 <button name=btteliasi onclick="javascript: return confirm(\'Esta seguro de Eliminar a '.$a['nombre'].' del Grupo\');" value='.$a['id'].'>
                     <img src=../img/cancelar.jpg  > <br>Eliminar</button> <input type=hidden name=idgru  value='.$id.' >   </form>';
         }
       echo "</table></center>";
        }else{
            echo "<div class=errores >Error al seleccionar  de la BDD</div>";
        }
        
    }
    
    public function listarAnimalesGrupo($id){
          $sql='select "ANIMAL_GRUPO".id,"ANIMALES".nombre,"ANIMALES".arete  from "ANIMAL_GRUPO","ANIMALES" where "ANIMAL_GRUPO".idani="ANIMALES".id and idgru='.$id.' order by nombre;';
       //  echo $sql;
         if($reg=$this->consulta($sql )){
             //echo "<div class=mesajeok >Nuevo dato registrado</div>";
         }else
         {
              echo "<div class=errores >Error al buscar en al BDD</div>";
         } 
       return  $reg;
    }
    
       public function listarAnimalesGrupoNombres($id){
          $sql='select "ANIMAL_GRUPO".id,"ANIMALES".nombre,"ANIMALES".arete  from "ANIMAL_GRUPO","ANIMALES" where "ANIMAL_GRUPO".idani="ANIMALES".id and idgru='.$id.' order by nombre;';
      $op='';
         if($reg=$this->consulta($sql )){
             //echo "<div class=mesajeok >Nuevo dato registrado</div>";
         }else
         {
              echo "<div class=errores >Error al buscar en al BDD</div>";
         } 
         $n=0;
         while($r=$this->row($reg)){
             $n++;
             $op.=''.$r['arete'].' - '.$r['nombre'].' ,';
         }
         $op.='Total ('.$n.')';
         
       return  $op;
    }
     public function Modificar($datos){
         if($this->consulta('update "GRUPO"  set   detalle=\''.$datos['detalle'].'\', estgru='.$datos['estgru'].' where id='.$datos['id'])){
             echo "<div class=mesajeok >Cambios registrados</div>";
         }else
         {
              echo "<div class=errores >Error al modificar la grupo de la BDD</div>";
         }             
         
         
     }
    
     public function eliminar($id){
           if($this->consulta('delete from "GRUPO"  where id='.$id)){
             echo "<div class=mesajeok >Datos Eliminados</div>";
         }else
         {
              echo "<div class=errores >Error al eliminar  de la BDD</div>";
         }             
         
         
     
     }
     
     public function mostrarCrear(){
         
               echo "<center><form><table BORDER=1><th colspan=2><center>Crear un nuevo Grupo</center> <tr><th>Detalle<td><input type=text name=detalle>
                <tr><th>Categoría</th>
            <td>
                <select name='estgru'>";
                
                // Definir las opciones disponibles
                    $estgru = $this->estgru;
               

                // Generar dinámicamente las opciones del select
              foreach ($estgru as $index => $categoria) {
    echo "<option value='$index'>$categoria</option>";
}

echo "

<tr><th colspan=2><center><button name=bttnuevo class=bttnuevo > <img src='../img/guardar.jpg'> <br>GUARDAR</button></center> </td></table>
                </form></center>";
         
     }
     
     public function nuevo($param) {
         $sql='insert into "GRUPO" (detalle,idhac,estgru) values (\''.$param['detalle'].'\','.$_SESSION['idhac'].','.$param['estgru'].');';
             if($res1=$this->consulta($sql)){
             echo "<div class=mesajeok >Nuevo dato registrado</div>";
         }else
         {
              echo "<div class=errores >Error al crear el nuevo dato BDD ".$sql." - ".pg_result_error($res1)."</div>";
         }  
         
         
     }
    
     
     public function eliminarasignarAnimalGrupo($id){
               if($this->consulta('delete from "ANIMAL_GRUPO"  where id='.$id)){
             echo "<div class=mesajeok >Datos Eliminados</div>";
         }else
         {
              echo "<div class=errores >Error al eliminar  de la BDD</div>";
         }  
         
     }
     public function listarGrupos(){
          $con=$this->consulta('select * from "GRUPO" where  idhac='.$_SESSION['idhac'].' ');
          return $con;
     }
       public function listarGruposOption(){
          $con=$this->consulta('select * from "GRUPO" where  idhac='.$_SESSION['idhac'].' order by id desc; ');
          $op='';
          
          while ($r=$this->row($con)){
              $op.='<option value='.$r['id'].'> '.$r['detalle'].'</option> ';
          }
          
          
          return $op;
     }
     public function listarGruposOptionLeche(){
          $con=$this->consulta('select * from "GRUPO" where estgru=4 and idhac='.$_SESSION['idhac'].' order by id desc; ');
          $op='';
          
          while ($r=$this->row($con)){
              $op.='<option value='.$r['id'].'> '.$r['detalle'].'</option> ';
          }
          
          
          return $op;
     }
         public function listarGruposOptionTotalLechera(){
          $con=$this->consulta('select "GRUPO".id,detalle,count("ANIMAL_GRUPO".id) as total from "GRUPO","ANIMAL_GRUPO" where "ANIMAL_GRUPO".idgru="GRUPO".id and idhac='.$_SESSION['idhac'].' and estgru=4 group by "GRUPO".id,detalle order by "GRUPO".id desc');
          $op='';
          
          while ($r=$this->row($con)){
              $op.='<option value='.$r['id'].'> <h2> '.$r['detalle'].' ('.$r['total'].') </h2></option> ';
          }
           return $op;
     }  
     
      public function listarGruposOptionTotal(){
          $con=$this->consulta('select "GRUPO".id,detalle,count("ANIMAL_GRUPO".id) as total from "GRUPO","ANIMAL_GRUPO" where "ANIMAL_GRUPO".idgru="GRUPO".id and idhac='.$_SESSION['idhac'].' group by "GRUPO".id,detalle order by "GRUPO".id desc');
          $op='';
          
          while ($r=$this->row($con)){
              $op.='<option value='.$r['id'].'> <h2> '.$r['detalle'].' ('.$r['total'].') </h2></option> ';
          }
          
          
          return $op;
     }  
        public function listarGruposOptionTotalLechero(){
         
            $sql='select "GRUPO".id,detalle,count("ANIMAL_GRUPO".id) as total from "GRUPO","ANIMAL_GRUPO" where "ANIMAL_GRUPO".idgru="GRUPO".id and idhac='.$_SESSION['idhac'].' and estgru=4 group by "GRUPO".id,detalle order by "GRUPO".id desc';
            $con=$this->consulta($sql);
          $op='';
          
          while ($r=$this->row($con)){
              $op.='<option value='.$r['id'].'> <h2> '.$r['detalle'].' ('.$r['total'].') </h2></option> ';
          }
          
          
          return $op;
     }  
     
     
     public function cantidadRejo($idhac) {
    // Consulta para buscar grupos con "Rejo" en su nombre
    $sql = '
        SELECT COUNT("ANIMAL_GRUPO".id) AS total
        FROM "GRUPO", "ANIMAL_GRUPO"
        WHERE "ANIMAL_GRUPO".idgru = "GRUPO".id
        AND "GRUPO".detalle ILIKE \'%Rejo%\'
        AND "GRUPO".idhac = ' . $idhac . ' and estgru=4
        GROUP BY "GRUPO".id';

    // Ejecutar la consulta
    $con = $this->consulta($sql);

    // Inicializar total
    $total = 0;

    // Iterar sobre los resultados y sumar los totales
    while ($r = $this->row($con)) {
        $total += $r['total'];
    }

    // Retornar el total de animales en grupos que contienen "Rejo"
    return $total;
}

 
     
}
