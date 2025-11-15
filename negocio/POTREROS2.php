<?php
require_once("BALANCE.php");
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of POTREROS
 *
 * @author LGT-5
 */
class POTREROS extends BALANCE {
    //put your code here
    
    public function inicial(){
        echo '<center><h2>Gestionar Trabajos</h2>
            <form><br>
            <button name=bttcrear>CREAR</button>
            <button name=bttbus>BUSCAR</button>
            <button name=bttrepo>REPORTE</button><br>
            </FORM>
            ';
    }
    
      public function incialCrear(){
          
          echo ' <form method="post">
  <label for="nombrePotrero">Nombre del Potrero:</label>
  <input type="text" id="nombrePotrero" name="nombrePotrero" required>

  <label for="tamanoPotrero">Tamaño del Potrero (en hectáreas):</label>
  <input type="number" id="tamanoPotrero" name="tamanoPotrero" step="0.01" required>

  <label for="estadoPotrero">Estado del Potrero:</label>
  <select id="estadoPotrero" name="estadoPotrero">
    <option value="1">Activo</option>
    <option value="0">Inactivo</option>
  </select>

  <label for="empresaPotrero">Empresa Dueña del Potrero:</label>
  <input type="number" id="empresaPotrero" name="empresaPotrero" required>

  <input type="submit" value="Crear Potrero">
</form>';
          
      }
    public function incialCrearTrabajo(){
        
       $result= $this->listarSubCategorias(1);
       $optionspotrero='';
       while ($r= pg_fetch_assoc($result)) {
           $optionspotrero.=' <option value='.$r['codsub'].'>'.$r['detsub'].'</option> ';
       }
       
       
          echo '<center><h2>Crear Trabajos</h2>
               <form>
               <table>
               <tr><th>Potrero:</th><td><select name=idsub>
               <option>Seleccione un potrero</option>
               '.$optionspotrero.'
                    </select></td></tr>
               <tr><th>Detalle</th><td><input type=text name=dettra></td></tr>
               <tr><th>Fecha Inicio</th><td><input type=date name=fecinitra value="'.date("Y-m-d").'"></td></tr>
               <tr><th>Fecha Fin</th><td><input type=date name=fecfintra value="'.date("Y-m-d").'"></td></tr>
               <tr><th>Tipo</th><td><select name=tiptra>
                            <option value=1>ABONO</option>
                            <option value=2>SIEMBRA</option>
                            <option value=3>RESIEMBRA</option>
                    </select></td></tr>
               <tr><td colspan=2> <button name=bttcreartrabajo>CREAR TRABAJO</button></tr>
</table>
            </FORM>

         
            ';
    }
    public function crearTrabajo($param) {
        
        //validar que ya no exista un mismo trabajo en el mismo dia
             $pConsulta='select * from  "TRABAJO" where fecinitra=\''. $param['fecinitra'].'\' and idsub='. $param['idsub'].' ;';
      $i2=  $this->consulta($pConsulta);
      if($a2=pg_fetch_assoc($i2)){
           return $a['id'] ;
      }else{
              $pConsulta='INSERT INTO "TRABAJO"(
            fectra, dettra, fecfintra, fecinitra, idsub,tiptra)
    VALUES (\''. date("Y-m-d").'\', \''. $param['dettra'].'\', \''. $param['fecfintra'].'\', \''. $param['fecinitra'].'\', '. $param['idsub'].', '. $param['tiptra'].') RETURNING id;';
    //  echo $pConsulta;
              $i=  $this->consulta($pConsulta);
      $a=pg_fetch_assoc($i);
       return $a['id'] ;
      }
        
        
        
        
    
        
    }
    
    public function mostrarAgregarTrabajo($id){
        //listar para agregar materiales
           
     $pConsulta=' SELECT *  FROM "MATERIAL"  order by detmat';
     $resulmate= $this->consulta($pConsulta);
   $selmate='<select name=idmat>';
   while($rmater= pg_fetch_assoc($resulmate)){
       $selmate.='<option value='.$rmater['id'].'>'.$rmater['detmat'].'</option>';
       
   }
   $selmate.='</select>';    
        
        //listar para agregar maquinaria
        
          $pConsulta=' SELECT *  FROM "MAQUINARIA"  order by detmaq';
     $resulmaq= $this->consulta($pConsulta);
   $selmaq='<select name=idmaq>';
   while($rmater= pg_fetch_assoc($resulmaq)){
       $selmaq.='<option value='.$rmater['id'].'>'.$rmater['detmaq'].'</option>';
       
   }
   $selmaq.='</select>';     
   
   
   //MOSTRAR formulario para agregar materiales y maquinaria
   
   echo '<form><table> 
        <tr><th>Material<td>'.$selmate.'
        <tr><th>Cantidad<td><input type=text name=cantramat>
        <tr><th>Medida<td><input type=text name=medtramat>
        <tr><th colspan=2><input type=submit name=btttramatc value=AGREGAR>
      </table>  
      <input type=hidden name=idtra value='.$id.' >
</form>
        <BR><BR>
';
   
      echo '<form><table> 
        <tr><th>Maquinaria<td>'.$selmaq.'
        <tr><th>Cantidad<td><input type=text name=cantramaq>
        <tr><th>Medida<td><input type=text name=medtramaq>
        <tr><th colspan=2><input type=submit name=btttramaqc value=AGREGAR>
      </table>  
       <input type=hidden name=idtra value='.$id.' >
</form>
        
';
   
   
   
   //mostrar los materiales y maquinaria ya ingresados
   
   echo '<table class="table">
            <tr><th>Material<th>Cantidad<th>Medida
       ';
           $pConsulta=' SELECT *  FROM "TRABAJO_MATERIAL","MATERIAL" where "MATERIAL".id="TRABAJO_MATERIAL".idmat and idtra='.$id.'   order by detmat';
 //    echo $pConsulta;
           $resulmaq= $this->consulta($pConsulta);

   while($r= pg_fetch_assoc($resulmaq)){
      
       echo '<tr><td>'.$r['detmat'].'<td>'.$r['cantramat'].'<td> '.$r['medtramat'].'';
   }
   
   
   echo '<tr><th>Maquinaria<th>Cantidad<th>Medida';
   
        
                   $pConsulta=' SELECT *  FROM "TRABAJO_MAQUINARIA","MAQUINARIA" where "MAQUINARIA".id="TRABAJO_MAQUINARIA".idmaq and idtra='.$id.'   order by detmaq';
     $resulmaq= $this->consulta($pConsulta);

   while($r= pg_fetch_assoc($resulmaq)){
      
       echo '<tr><td>'.$r['detmaq'].'<td>'.$r['cantramaq'].'<td> '.$r['medtramaq'].'';
   }
        echo '</table>';
    }
    public function agregarTrabajoMaterial($datos){
        //insert ddatos materia
        
        $sql='INSERT INTO "TRABAJO_MATERIAL"(
            idmat, idtra, cantramat, medtramat, esttramat)
    VALUES ('.$datos['idmat'].','.$datos['idtra'].','.$datos['cantramat'].',\''.$datos['medtramat'].'\',1);
';
        
        if($this->consulta($sql)){
           echo '<h3>Ingresado correctamente</h3>'; 
        }else{
            echo '<h3>Error al ingresar</h3>'; 
        }
        return 0;
    }
    
        public function agregarTrabajoMaquinaria($datos){
        //insert ddatos materia
        
        $sql='INSERT INTO "TRABAJO_MAQUINARIA"(
            idmaq, idtra, cantramaq, medtramaq, esttramaq)
    VALUES ('.$datos['idmaq'].','.$datos['idtra'].','.$datos['cantramaq'].',\''.$datos['medtramaq'].'\',1);
';
        
        if($this->consulta($sql)){
           echo '<h3>Ingresado correctamente</h3>'; 
        }else{
            echo '<h3>Error al ingresar</h3>'; 
        }
        return 0;
    }
    public function datosPotrero($idsub){
        
          $pConsulta=' SELECT *  FROM "SUBCATEGORIA" where codcat=1 and codsub='.$idsub.' and estsub=1';
          $con=$this->consulta($pConsulta);
          $r= $this->row($con);
          return $r;
    }
    
     // Función para crear un nuevo potrero
    public function crearPotrero($nompot, $tampot, $estpot, $emppot) {
        


try {
  
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
      $sql = "INSERT INTO POTRERO (nompot, tampot, estpot, emppot)
    VALUES ('$nompot', $tampot, $estpot, $emppot)";
  
  // use exec() because no results are returned
$this->consulta($sql);
  echo "Potrero creado exitosamente.";
} catch(PDOException $e) {
  echo $sql . "<br>" . $e->getMessage();
}
      /*  
        
        $query = "INSERT INTO potrero (nombre_potrero, tamanio_hectareas, ubicacion, fecha_siembra, tipo_cultivo, rendimiento, estado) VALUES ('$nombre_potrero', $tamanio_hectareas, '$ubicacion', '$fecha_siembra', '$tipo_cultivo', $rendimiento, '$estado')";

        $result = pg_query($this->conn, $query);

        if (!$result) {
            return false;
        } else {
            return true;
        }
       * */
       
    }   
     public function modificarPotrero($id_potrero, $nombre_potrero, $tamanio_hectareas, $ubicacion, $fecha_siembra, $tipo_cultivo, $rendimiento, $estado) {
        $query = "UPDATE potrero SET nombre_potrero = '$nombre_potrero', tamanio_hectareas = $tamanio_hectareas, ubicacion = '$ubicacion', fecha_siembra = '$fecha_siembra', tipo_cultivo = '$tipo_cultivo', rendimiento = $rendimiento, estado = '$estado' WHERE id_potrero = $id_potrero";

        $result = pg_query($this->conn, $query);

        if (!$result) {
            return false;
        } else {
            return true;
        }
    }  
    
      public function eliminarPotrero($id_potrero) {
        $query = "DELETE FROM potrero WHERE id_potrero = $id_potrero";

        $result = pg_query($this->conn, $query);

        if (!$result) {
            return false;
        } else {
            return true;
        }
    }
    
    
    
    
    
}
