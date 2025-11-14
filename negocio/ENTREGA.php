<?php
require_once("LECHE.php");
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ENTREGA
 *
 * @author LGT-5
 */
class ENTREGA extends LECHE {
    //put your code here
    
     public function mostrarInicio(){
         echo '<center>
             <form>
                <button type="submit" name="bttcrear" > <img src="../img/anadir.png" alt=""/>  <BR>REGISTRAR ENTREGA</button>
                <button type="submit" name="bttbuscar" > <img src="../img/buscar.jpg" alt=""/>  <BR>BUSCAR ENTREGA</button>
                <button type="submit" name="bttresumen" > <img src="../img/cuadernorojo.png" alt=""/>  <BR>RESUMEN ENTREGA</button>
             <br><br>
          
                Fecha: <input name=fec type=date value='.date("Y-m-d").'> Fecha fin: <input name=fecfin type=date value='.date("Y-m-d").'>
             </center>';
         
     }
         public function mostrarCrear(){
         
             //$CLIENT= new CLIENTE();
             $clientes= $this->clientesSelect();
             $empleados=$this->empleadosSelect();
             //solo por el primer ingreso
              echo "<center><form><table BORDER=1><th colspan=2><center>Registro de Entrega</center> 
                   <tr><th>Fecha entrega:</th><td><input type=date name=fecent  value=".date("Y-m-d")."> </td></tr>
                   <tr><th>Litros entregados:</th><td><input type=number name=totent style='width: 100;' value=0 required> Litros</td></tr>    
                   <tr><th>Cliente:</th><td><select name=codcli><option value=12>EL ORDEÑO</option></SELECT></td></tr>    
                   <tr><th>Empleado:</th><td><select name=idemp><option value=2> MOLINA VACA ARTURO ARNULFO</option></SELECT></td></tr>    
                   <tr><th>Alcohol:</th><td><input type=text style='width: 100;' name=alcent VALUE=Apto> </td></tr>
                    <tr><th>Densidad:</th><td><input type=text name=denent id=denent style='width: 100;' value=1.03 oninput='validateDecimal(this)'></td></tr>
                    <tr><th>Temperatura:</th><td><input type=text name=tement style='width: 100;' value=4 oninput='validateDecimal(this)'> °C</td></tr>
                    <tr><th>Hora:</th><td><input type=text name=horent style='width: 100;' value=".date("h:i:s")." > </td></tr>
                   <tr><th>Observaciones:</th><td><input type=text style='width: 100;' name=obsent > </td></tr>    
                  
                <tr><th colspan=2><center><button name=bttnuevo class=bttnuevo > <img src='../img/guardar.jpg'> <br>GUARDAR</button></center> </td></table>
                </form></center>";
             
             
             /*
               echo "<center><form><table BORDER=1><th colspan=2><center>Registro de Entrega</center> 
                   <tr><th>Fecha entrega:</th><td><input type=date name=fecent  value=".date("Y-m-d")."> </td></tr>
                   <tr><th>Litros entregados:</th><td><input type=number name=totent style='width: 100;' value=0 required> Litros</td></tr>    
                   <tr><th>Cliente:</th><td>".$clientes."</td></tr>    
                   <tr><th>Empleado:</th><td>".$empleados."</td></tr>    
                   <tr><th>Alcohol:</th><td><input type=text style='width: 100;' name=alcent > </td></tr>
                    <tr><th>Densidad:</th><td><input type=text name=denent id=denent style='width: 100;' value=0 oninput='validateDecimal(this)'></td></tr>
                    <tr><th>Temperatura:</th><td><input type=text name=tement style='width: 100;' value=0 oninput='validateDecimal(this)'> °C</td></tr>
                    <tr><th>Hora:</th><td><input type=text name=horent style='width: 100;' value=".date("h:i:s")." > </td></tr>
                   <tr><th>Observaciones:</th><td><input type=text style='width: 100;' name=obsent > </td></tr>    
                  
                <tr><th colspan=2><center><button name=bttnuevo class=bttnuevo > <img src='../img/guardar.jpg'> <br>GUARDAR</button></center> </td></table>
                </form></center>";
         */
     }
     
        public function nuevo($datos){
            
            
               
          $datos['tement'] = str_replace(',', '.', $datos['tement']);
           $datos['denent'] = str_replace(',', '.', $datos['denent']); 
          $datos['alcent'] = str_replace(',', '.', $datos['alcent']); 
            
            
         $sql='INSERT INTO "ENTREGA"(fecent, totent, codcli, idemp,  totlit, obsent, 
            tieent, idusu,alcent,denent,tement,horent)
    VALUES (\''.$datos['fecent'].'\', '.$datos['totent'].','.$datos['codcli'].','.$datos['idemp'].',0,\''.$datos['obsent'].'\','.time().','.$_SESSION['id'].',\''.$datos['alcent'].'\','.$datos['denent'].','.$datos['tement'].',\''.$datos['horent'].'\' )  RETURNING ident;';    
         //echo $sql;
         if($reg=$this->consulta($sql )){
             $r=$this->row($reg);
             $id=$r['ident'];
             echo "<div class=mesajeok >Nuevo dato registrado id:  ".$r['ident']."</div>";
         }else
         {
              echo "<div class=errores >Error al crear el nuevo dato BDD <br>".$sql."</div>";
         } 
        return $id; 
     }
     
     
     public function mostrarModificar($id) {
        // Asegúrate de que la sesión esté iniciada para acceder a $_SESSION['idhac']
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $con = $this->consulta('SELECT * FROM "ENTREGA", "CLIENTE", "EMPLEADOS" 
                                WHERE "EMPLEADOS".idemp = "ENTREGA".idemp 
                                AND "CLIENTE".codcli = "ENTREGA".codcli 
                                AND ident = ' . $id);
        
        if ($a = $this->row($con)) {
            echo "<center><form method='post' enctype='multipart/form-data'><table BORDER=1><th colspan=2><center>Registro de Entrega</center> 
                   <tr><th>Fecha entrega:</th><td><input type=date name=fecent  value=" . $a['fecent'] . "> </td></tr>
                   <tr><th>Litros entregados:</th><td><input type=number name=totent style='width: 100;' value=" . $a['totent'] . " required> Litros</td></tr>    
                   <tr><th>Cliente:</th><td> <select name=codcli><option value=" . $a['codcli'] . ">" . $a['nomcli'] . "</option>" . $this->clientesoption() . "   </select> 
                    </td></tr>    
                   <tr><th>Empleado:</th><td><select name=idemp><option value=" . $a['idemp'] . ">" . $a['apellido'] . " " . $a['nombre'] . "</option>" . $this->empleadosoption() . "   </select> 
                       </td></tr>    
                   <tr><th>Alcohol:</th><td><input type=text style='width: 100;' name=alcent value='" . $a['alcent'] . "' > </td></tr>
                    <tr><th>Densidad:</th><td><input type=text name=denent style='width: 100;' value=" . $a['denent'] . " oninput='validateDecimal(this)'> </td></tr>
                    <tr><th>Temperatura:</th><td><input type=text name=tement style='width: 100;' value=" . $a['tement'] . " oninput='validateDecimal(this)'> °C</td></tr>
                    <tr><th>Hora:</th><td><input type=text style='width: 100;' name=horent value=" . $a['horent'] . " > </td></tr>
                   <tr><th>Observaciones:</th><td><input type=text style='width: 100;' name=obsent value='" . $a['obsent'] . "' > </td></tr>    
                <tr><td colspan=2>  
             
      ";
            // Mostrar imagen si existe
            if (!empty($a['imagen'])) {
                $rutaImagen = '../entregas/' . $a['imagen']; // Ajusta el path según tu estructura
                echo "<center><h3>Imagen de la Entrega Actual</h3>
                      <a href='$rutaImagen' target='_blank'>
                          <img src='$rutaImagen' alt='Imagen de Entrega' style='max-width: 300px; height: auto; border: 1px solid #ccc; padding: 5px;'>
                      </a>
                      <br>
                      <input type='checkbox' name='eliminar_imagen' value='1'> Eliminar imagen actual
                      </center><br>";
            } else {
                echo "<center><h3>No hay imagen cargada</h3></center>";
            }

            // Campo para subir nueva imagen
            echo "<center>
                    <div style='margin-bottom: 15px;'>
                        <label for='nueva_imagen'>Subir nueva imagen (opcional, máx 500KB):</label>
                        <input type='file' name='imagenEntrega' id='imagenEntrega' accept='image/*' class='form-control'>
                    </div>
                  </center>";

            echo "<tr><td><input type=hidden name=ident value=$id> <tr><th colspan=2><center><button name=bttmod class=bttmod > <img src='../img/guardar.jpg'> <br>GUARDAR</button></center> </td></table>   </form></center>";
            $entregados = $a['totent'];
            //mostrar la tabla de produccion registrada con ese id
            echo "<center><table border=1 class=table><tr><th colspan=5>AGREGADOS A LA ENTREGA</tr><tr><th>Fecha<th>Grupo<th>Tipo<th>Litros<th>Acción";
            //listar todas las leches que no estan organizadas
            $timestamp = strtotime($a['fecent']) - (6 * 24 * 60 * 60);

            // Convertir el timestamp de vuelta a una fecha en formato d-m-Y
            $fecent = $a['fecent'];
            $fecmay = date('d-m-Y', $timestamp);
            
            $pConsulta = 'SELECT * FROM "LECHE","GRUPO","ENTREGA_LECHE" 
                          WHERE "ENTREGA_LECHE".idlec = "LECHE".idlec 
                          AND "ENTREGA_LECHE".ident = ' . $id . ' 
                          AND "GRUPO".id = "LECHE".idgru 
                          ORDER BY feclec '; 
            
            $reg = $this->consulta($pConsulta);
            $litros = 0;
            while ($a = $this->row($reg)) {
                echo '<tr><td>' . $a['feclec'] . ' <td>' . $a['detalle'] . '<td> ' . $this->tiepoleche[$a['tielec']] . ' <td> ' . $a['totelec'] . '</td><td><form><input type=submit name=bttelilec value=ELIMINAR> <input type=hidden name=ident value=' . $id . '><input type=hidden name=identlec value=' . $a['identlec'] . '></form>
              ';
                $litros = $litros + $a['totelec'];
            }
            echo "<tr><th colspan=3>Total litros asignados: <th colspan=2>" . $litros . " 
                      <tr><th colspan=3>Total litros entregados: <th colspan=2>" . $entregados . " 
                      <tr><th colspan=3>Diferencia: <th colspan=2>" . ($entregados - $litros) . " 
                      </table></center><BR>";
            
            //mostrar tabla de leches que se pueden agregar   
            echo "<center><table border=1 class=table><tr><th colspan=5>PENDIENTES DE AGREGAR</tr><tr><th>Fecha<th>Grupo<th>Tipo<th>Litros<th>Acción";
            //listar todas las leches que no estan organizadas
            $pConsulta = 'SELECT * FROM "LECHE","GRUPO" 
                          WHERE "GRUPO".id = "LECHE".idgru 
                          AND idlec NOT IN (SELECT idlec FROM "ENTREGA_LECHE") 
                          AND idhac = ' . $_SESSION['idhac'] . ' 
                          AND feclec <= \'' . $fecent . '\' 
                          AND feclec > \'' . $fecmay . '\'  
                          ORDER BY feclec '; 
            
            $reg = $this->consulta($pConsulta);
            
            while ($a = $this->row($reg)) {
                echo '<tr><td>' . $a['feclec'] . ' <td>' . $a['detalle'] . '<td> ' . $this->tiepoleche[$a['tielec']] . ' <td> ' . $a['totelec'] . '</td><td><form><input type=submit name=bttagrlec value=AGREGAR> <input type=hidden name=ident value=' . $id . '><input type=hidden name=idlec value=' . $a['idlec'] . '></form>
              ';
            }
            echo "</table></center>";
        } else {
            echo "<div class=errores >Error al seleccionar  de la BDD Entrega</div>";
        }
    }
     
     
     
    public function mostrarModificarAnte($id) {
          $con=$this->consulta('select * from "ENTREGA","CLIENTE","EMPLEADOS" where "EMPLEADOS".idemp="ENTREGA".idemp and  "CLIENTE".codcli="ENTREGA".codcli and ident='.$id);
        
        if($a=$this->row($con)){
              echo "<center><form><table BORDER=1><th colspan=2><center>Registro de Entrega</center> 
                   <tr><th>Fecha entrega:</th><td><input type=date name=fecent  value=".$a['fecent']."> </td></tr>
                   <tr><th>Litros entregados:</th><td><input type=number name=totent style='width: 100;' value=".$a['totent']." required> Litros</td></tr>    
                   <tr><th>Cliente:</th><td> <select name=codcli><option value=".$a['codcli'].">".$a['nomcli']."</option>".$this->clientesoption()."   </select> 
                    </td></tr>    
                   <tr><th>Empleado:</th><td><select name=idemp><option value=".$a['idemp'].">".$a['apellido']." ".$a['nombre']."</option>".$this->empleadosoption()."   </select> 
                 
                       </td></tr>    
                   <tr><th>Alcohol:</th><td><input type=text style='width: 100;' name=alcent value='".$a['alcent']."' > </td></tr>
                    <tr><th>Densidad:</th><td><input type=text name=denent style='width: 100;' value=".$a['denent']." oninput='validateDecimal(this)'> </td></tr>
                    <tr><th>Temperatura:</th><td><input type=text name=tement style='width: 100;' value=".$a['tement']." oninput='validateDecimal(this)'> °C</td></tr>
                    <tr><th>Hora:</th><td><input type=text name=horent style='width: 100;' value=".$a['horent']." > </td></tr>
                   <tr><th>Observaciones:</th><td><input type=text style='width: 100;' name=obsent value='".$a['obsent']."' > </td></tr>    
                  
             <tr><th colspan=2><center><button name=bttmod class=bttmod > <img src='../img/guardar.jpg'> <br>GUARDAR</button></center> </td></table>
      ";
              // Mostrar imagen si existe
if (!empty($a['imagen'])) {
    $rutaImagen = '../entregas/' . $a['imagen']; // Ajusta el path según tu estructura
    echo "<center><h3>Imagen de la Entrega</h3>
          <a href='$rutaImagen' target='_blank'>
              <img src='$rutaImagen' alt='Imagen de Entrega' style='max-width: 300px; height: auto; border: 1px solid #ccc; padding: 5px;'>
          </a></center><br>";
}

echo "<input type=hidden name=ident value=$id>   </form></center>";
              $entregados=$a['totent'];
           //mostrar la tabla de produccion registrada con ese id
                 echo "<center><table border=1 class=table><tr><th colspan=5>AGREGADOS A LA ENTREGA</tr><tr><th>Fecha<th>Grupo<th>Tipo<th>Litros<th>Acción";
                  //listar todas las leches que no estan organizadas
                 $timestamp = strtotime($a['fecent']) - (6 * 24 * 60 * 60);

// Convertir el timestamp de vuelta a una fecha en formato d-m-Y
                 $fecent=$a['fecent'];
                 $fecmay=  date('d-m-Y', $timestamp);
        
             $pConsulta='select * from "LECHE","GRUPO","ENTREGA_LECHE" where "ENTREGA_LECHE".idlec="LECHE".idlec and  "ENTREGA_LECHE".ident='.$id.' and  "GRUPO".id="LECHE".idgru order by feclec '; 
             
            // echo $pConsulta;
        $reg=$this->consulta($pConsulta);
        $litros=0;
           while($a=$this->row($reg)){
             echo '<tr><td>'.$a['feclec'].' <td>'.$a['detalle'].'<td> '.$this->tiepoleche[$a['tielec']].' <td> '.$a['totelec'].'</td><td><form><input type=submit name=bttelilec value=ELIMINAR> <input type=hidden name=ident value='.$id.'><input type=hidden name=identlec value='.$a['identlec'].'></form>
              ';
            $litros=$litros+  $a['totelec'];
         }
                  echo "<tr><th colspan=3>Total litros asignados: <th colspan=2>".$litros." 
                      <tr><th colspan=3>Total litros entregados: <th colspan=2>".$entregados." 
                      <tr><th colspan=3>Diferencia: <th colspan=2>".($entregados-$litros)." 
                      </table></center><BR>";
              
              
           //mostrar tabla de leches que se pueden agregar   
              echo "<center><table border=1 class=table><tr><th colspan=5>PENDINTES DE AGREGAR</tr><tr><th>Fecha<th>Grupo<th>Tipo<th>Litros<th>Acción";
                  //listar todas las leches que no estan organizadas
             $pConsulta='select * from "LECHE","GRUPO" where "GRUPO".id="LECHE".idgru and  idlec not in (select idlec from "ENTREGA_LECHE") and idhac='.$_SESSION['idhac'].' AND feclec<=\''.$fecent.'\' and feclec>\''.$fecmay.'\'  order by feclec '; 
             //$pConsulta='select * from "LECHE","GRUPO" where "GRUPO".id="LECHE".idgru and  idlec not in (select idlec from "ENTREGA_LECHE") and idhac='.$_SESSION['idhac'].'  order by feclec '; 
             //echo $pConsulta;
             
             $reg=$this->consulta($pConsulta);
        
           while($a=$this->row($reg)){
             echo '<tr><td>'.$a['feclec'].' <td>'.$a['detalle'].'<td> '.$this->tiepoleche[$a['tielec']].' <td> '.$a['totelec'].'</td><td><form><input type=submit name=bttagrlec value=AGREGAR> <input type=hidden name=ident value='.$id.'><input type=hidden name=idlec value='.$a['idlec'].'></form>
              ';
         }
                  echo "</table></center>";
              
            
        }else{
            echo "<div class=errores >Error al seleccionar  de la BDD Entrega</div>";
        }
        
    }
    
    
    public function ModificarEntrega($datos, $files) {
    // Reemplazo de comas por puntos para valores decimales
    $datos['tement'] = str_replace(',', '.', $datos['tement']);
    $datos['denent'] = str_replace(',', '.', $datos['denent']);
    $datos['alcent'] = str_replace(',', '.', $datos['alcent']);

    $nombreImagen = null; // Inicializar a null por defecto

    // Validar y manejar la imagen si se ha subido una nueva
    if (isset($files['imagenEntrega']) && $files['imagenEntrega']['error'] === UPLOAD_ERR_OK) {
        $imagen = $files['imagenEntrega'];
        $nombreImagen = 'entrega_' . time() . '_' . basename($imagen['name']);
        $rutaImagen = __DIR__ . '/../entregas/' . $nombreImagen; // Asegúrate de que esta ruta sea correcta

        // Mover el archivo subido al directorio de destino
        if (!move_uploaded_file($imagen['tmp_name'], $rutaImagen)) {
            echo "<div class='errores'>Error al guardar la nueva imagen.</div>";
            return false; // Retornar false si falla la subida de la imagen
        }
    }

    // Construir la consulta SQL para actualizar la entrega
    // Incluir el campo 'imagen' en la actualización solo si se ha subido una nueva
    $sql = 'UPDATE "ENTREGA" SET 
        fecent = \'' . $datos['fecent'] . '\',
        totent = ' . $datos['totent'] . ',
        codcli = ' . $datos['codcli'] . ',
        idemp = ' . $datos['idemp'] . ',
        obsent = \'' . addslashes($datos['obsent']) . '\',
        alcent = \'' . addslashes($datos['alcent']) . '\',
        denent = ' . $datos['denent'] . ',
        tement = ' . $datos['tement'] . ',
        horent = \'' . $datos['horent'] . '\' ';

    if ($nombreImagen !== null) {
        $sql .= ', imagen = \'' . addslashes($nombreImagen) . '\' ';
    }

    $sql .= ' WHERE ident = ' . $datos['ident'] . ';';

    // Ejecutar la consulta
    if ($this->consulta($sql)) {
        echo "<div class='mesajeok'>Cambios registrados exitosamente.</div>";
        return true;
    } else {
        echo "<div class='errores'>Error al modificar la entrega en la BDD.<br>SQL: " . $sql . "</div>";
        return false;
    }
}



      public function ModificarAnte($datos){
            
           // echo "No se puede modificar";
            
          $datos['tement'] = str_replace(',', '.', $datos['tement']);
           $datos['denent'] = str_replace(',', '.', $datos['denent']); 
          $datos['alcent'] = str_replace(',', '.', $datos['alcent']); 
          
            $sql='UPDATE "ENTREGA"
   SET  fecent=\''.$datos['fecent'].'\',totent='.$datos['totent'].',codcli='.$datos['codcli'].' , idemp='.$datos['idemp'].', obsent=\''.$datos['obsent'].'\',alcent=\''.$datos['alcent'].'\' , denent='.$datos['denent'].', tement='.$datos['tement'].',horent=\''.$datos['horent'].'\' 

 WHERE ident='.$datos['ident'].';
';
            
            
         if($this->consulta($sql)){
             echo "<div class=mesajeok >Cambios registrados</div>";
         }else
         {
              echo "<div class=errores >Error al modificar la raza de la BDD ".$sql."</div>";
         }             
         
         
     }
       public function buscar($fec,$fecfin){
         
          echo '  <form> <center>
        <table border="1" class=table style="width:100%">
            <tr>
                <th>Id</th><th>Fecha-registro</th><th>Litros entregados</th>
                <th>Densidad</th><th>Temperatura</th>
                <th>Hora</th><th>Litros producción</th><th>Acción</th>
            </tr>';
    

      //   $fotos= $ani->mostrarFotosAnimal($a['id'],300);
        
        
        $sql='select * from "ENTREGA" where fecent>=\''.$fec.'\' and fecent<=\''.$fecfin.'\' and estent!=0 and idhac='.$_SESSION['idhac'].' order by fecent';
         $con=$this->consulta($sql);
//echo  $sql;

        while($a=$this->row($con)){
                      $medidas= $this->contarLitros($a['ident'],$a['totent']);
                     // $a['totent']-$medidas;
        echo '<tr>
               <td>'.$a['ident'].'</td> <td>'.$a['fecent'].'</td>
                    <td>'.$a['totent'].'</td><td>'.$a['denent'].'</td>
                    <td>'.$a['tement'].'</td><td>'.$a['horent'].'</td>
            <td>'.$medidas.'</td>
                        <td><button name=bttsel value='.$a['ident'].'> <img src=../img/modif.jpg  > <br>Seleccionar</button></td>
                    <td><button name=btteli onclick="javascript: return confirm(\'Esta seguro de Eliminar la Leche\');" value='.$a['ident'].'><img src=../img/cancelar.jpg  > <br>Eliminar</button></td>
            </tr>'; 
    }
    
    echo '</table>
        
    </center></form>';
    } 
    public function contarLitros($ident,$totent){
        
          $pConsulta='select * from "LECHE","GRUPO","ENTREGA_LECHE" where "ENTREGA_LECHE".idlec="LECHE".idlec and  "ENTREGA_LECHE".ident='.$ident.' and  "GRUPO".id="LECHE".idgru order by feclec,tielec '; 
             
            // echo $pConsulta;
        $reg=$this->consulta($pConsulta);
        $litros=0;$r='';
           while($a=$this->row($reg)){
              $litros+= $a['totelec'];
             $r.= ''.$a['feclec'].' / '.$a['detalle'].' / '.$this->tiepoleche[$a['tielec']].'  ('.$a['totelec'].')<br>';
            
         }
         $dif=$totent-$litros;
         if($dif==0){
         $r='Total registro ('.$litros.')<br>'.$r;
         }else{
             //$r='<b>Diferencia: '.$dif.'</b> <br>Total registro ('.$litros.')<br>'.$r;
             $r = '<div style="background-color: #ffcccc; color: #b30000; padding: 10px; border: 2px solid #b30000; border-radius: 8px; font-weight: bold; text-align: center; margin-bottom: 10px;">
        Diferencia: ' . $dif . '
      </div>
      <br>Total registro (' . $litros . ')<br>' . $r;

         }
         
        return $r;
    }
     public function eliminar($id) {
        $sql='delete from "ENTREGA"  where ident='.$id;
      //  echo $sql;
            if($this->consulta($sql)){
             echo "<div class=mesajeok >Datos Eliminados</div>";
         }else
         {
              echo "<div class=errores >Error al eliminar  de la BDD</div>";
         }             
         
         
        
        
    }
    
    public function agregarLecheEntrega($datos){
        
           $sql='INSERT INTO "ENTREGA_LECHE"(idlec,ident) VALUES ('.$datos['idlec'].','.$datos['ident'].')  RETURNING identlec;';    
         //echo $sql;
         if($reg=$this->consulta($sql )){
             $r=$this->row($reg);
             $id=$r['ident'];
             echo "<div class=mesajeok >Nuevo dato registrado id:  ".$r['identlec']."</div>";
         }else
         {
              echo "<div class=errores >Error al crear el nuevo dato BDD <br>".$sql."</div>";
         } 
        return $id; 
        
    }
    public function eliminarLecheEntrega($id){
           $sql='delete from "ENTREGA_LECHE"  where identlec='.$id;
      //  echo $sql;
            if($this->consulta($sql)){
             echo "<div class=mesajeok >Datos Eliminados</div>";
         }else
         {
              echo "<div class=errores >Error al eliminar  de la BDD</div>";
         } 
        
    }
    
    public function mostrarResumeEntregas($fec, $fecfin, $idhac){
        $resumen = $this->resumenEntregas($fec, $fecfin, $idhac);

if ($resumen) {
    echo '<div class="container mt-4">';
    echo '<div class="row justify-content-center">';
    echo '<div class="col-md-8">';
    echo '<table class="table table-bordered table-striped text-center">';
    echo '<thead class="thead-dark">';
    echo '<tr>';
    echo '<th>#</th>';
    echo '<th>Fecha</th>';
    echo '<th>Total Litros</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    $totalLitros = 0;
     while ($fila = $this->row($resumen)) {
     $totalLitros += isset($fila['total_litros']) ? $fila['total_litros'] : 0;

        echo '<tr>';
        echo '<td>' . (isset($fila['numero_entrega']) ? $fila['numero_entrega'] : 'Total') . '</td>';
        echo '<td>' . (isset($fila['fecha']) ? $fila['fecha'] : '-') . '</td>';
        echo '<td>' . $fila['total_litros'] . '</td>';
        echo '</tr>';
    }

     echo '</tbody>';
    echo '</table>';

    echo '</div>';
    echo '</div>';
    echo '<div class="row justify-content-center mt-3">';
    echo '<div class="col-md-4">';
    echo '<label for="multiplicador" class="form-label">Ingrese valor en dólares ($):</label>';
    echo '<input type="number" id="multiplicador" class="form-control" placeholder="Ingrese un valor" step="0.01">';
    echo '</div>';
    echo '<div class="col-md-4 d-flex align-items-end">';
   // echo '<button class="btn btn-primary w-100" onclick="calcularMultiplicacion()">Calcular</button>';
    echo '<button type="button" class="btn btn-primary w-100" onclick="calcularMultiplicacion()">Calcular</button>';

    echo '</div>';
    echo '</div>';
    echo '<div class="row justify-content-center mt-3">';
    echo '<div class="col-md-8">';
    echo '<label for="resultado_campo" class="form-label">Resultado en dólares ($):</label>';
    echo '<input type="text" id="resultado_campo" class="form-control" readonly>';
    echo '</div>';
    echo '</div>';
    echo '</div>';

    // Pasar el valor de total litros a JavaScript
    echo "<script>const totalLitros = $totalLitros;</script>";
} else {
    echo '<div class="alert alert-danger text-center mt-4">Error al obtener el resumen.</div>';
}

echo "<br><br>";
    }
    
  
public function resumenEntregas($fec, $fecfin, $idhac) {
    // Construir la consulta SQL para obtener el resumen
    $sql = '
        WITH resumen AS (
            SELECT 
                ROW_NUMBER() OVER (ORDER BY fecent) AS numero_entrega,
                fecent AS fecha,
                totent AS total_litros
            FROM 
                "ENTREGA"
            WHERE 
                fecent BETWEEN \'' . $fec . '\' AND \'' . $fecfin . '\'
                AND idhac = ' . $idhac . '
        )
        SELECT * FROM resumen
        UNION ALL
        SELECT 
            NULL AS numero_entrega, 
            NULL AS fecha, 
            SUM(totent) AS total_litros
        FROM 
            "ENTREGA"
        WHERE 
            fecent BETWEEN \'' . $fec . '\' AND \'' . $fecfin . '\'
            AND idhac = ' . $idhac . ';
    ';
//echo $sql;
    // Ejecutar la consulta
    if ($con=$this->consulta($sql)) {
        // Obtener los resultados en formato array
        return $con;
    } else {
        // Manejo de error si la consulta falla
        return false;
    }
}



public function mostrarIngresarEntregaLeche() {
    // Iniciar sesión para obtener la hacienda
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Obtener el último registro de ENTREGAS para tomar sus valores como default
    $consultaUltimo = 'SELECT "ENTREGA".*, "CLIENTE".nomcli 
                       FROM "ENTREGA"
                       JOIN "CLIENTE" ON "ENTREGA".codcli = "CLIENTE".codcli
                       ORDER BY fecent DESC LIMIT 1';
    
    $ultimoRegistro = $this->consulta($consultaUltimo);
    $default = $this->row($ultimoRegistro);

    // Sumar la cantidad total de litros de leches no asignadas
    $fechaLimite = date('Y-m-d', strtotime('-5 days'));
 
    $pConsulta = 'SELECT * FROM "LECHE","GRUPO" 
                   WHERE "GRUPO".id="LECHE".idgru 
                   AND idlec NOT IN (SELECT idlec FROM "ENTREGA_LECHE") 
                   AND idhac=' . $_SESSION['idhac'] . ' 
                   AND feclec >= \'' . $fechaLimite . '\'
                   ORDER BY feclec ,tielec ';
$tablalitros='';
    $reg = $this->consulta($pConsulta);
$totall=0;$chec='';$menor=0;$b=0;
    while ($a = $this->row($reg)) {
        if($menor<$a['medida_tanque'] and $b==0){
           $chec='checked'; 
           $menor=$a['medida_tanque'];
           $totall=$totall+$a['totelec'];
        }else{
            $chec=''; 
            $b=1;
           $menor=$a['medida_tanque']; 
        }
        
        $tablalitros.= '<tr>
                <td>
                    <input type="checkbox" class="form-check-input" name="leches[]" value="' . $a['idlec'] . '" '.$chec.'>
                </td>
                <td>' . $a['feclec'] . '</td>
                <td>' . $a['detalle'] . '</td>
                <td>' . $this->tiepoleche[$a['tielec']] . '</td>
                <td>' . $a['totelec'] . ' /
                 ' . $a['medida_tanque']. '</td>    
              </tr>';
    }
    
    
   // $litrosNoAsignados = $this->row($resultadoLitros)['total_no_asignado'] ?? 0;

    echo '<div class="container mt-4">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow-lg">
                        <div class="card-header bg-primary text-white text-center">
                            <h3>Registro de Nueva Entrega de Leche</h3>
                        </div>
                        <div class="card-body">
                            <form method="post" enctype="multipart/form-data">

                                <div class="mb-3">
                                    <label class="form-label"><b>Fecha de Entrega:</b></label>
                                    <input type="date" class="form-control" name="fecent" value="' . date('Y-m-d') . '" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label"><b>Litros Entregados:</b></label>
                                    <input type="number" class="form-control" name="totent" value="' . $totall . '" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label"><b>Cliente:</b></label>
                                    <select class="form-select" name="codcli">
                                        <option value="' . $default['codcli'] . '">' . $default['nomcli'] . '</option>'
                                        . $this->clientesoption() . '
                                    </select>
                                </div>

                              

                                <div class="mb-3">
                                    <label class="form-label"><b>Alcohol:</b></label>
                                    <input type="text" class="form-control" name="alcent" value="' . $default['alcent'] . '">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label"><b>Densidad:</b></label>
                                    <input type="text" class="form-control" name="denent" value="' . $default['denent'] . '" oninput="validateDecimal(this)">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label"><b>Temperatura (°C):</b></label>
                                    <input type="text" class="form-control" name="tement" value="' . $default['tement'] . '" oninput="validateDecimal(this)">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label"><b>Hora:</b></label>
                                    <input type="text" class="form-control" name="horent" value="' . date('h:i:s') . '">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label"><b>Observaciones:</b></label>
                                    <textarea class="form-control" name="obsent" rows="2">' . $default['obsent'] . '</textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label"><b>Subir Imagen (máx 500KB):</b></label>
                                    <input type="file" class="form-control" name="imagenEntrega" accept="image/*" required>
                                </div>

                                <!-- Tabla de Leches Disponibles para Asignar -->
                                <div class="container mt-4">
                                    <h4 class="text-center text-warning">Leches Disponibles para Asignar</h4>
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead class="table-dark text-center">
                                            <tr>
                                                <th>Sel</th>
                                                <th>Fecha</th>
                                                <th>Grupo</th>
                                                <th>Tipo</th>
                                                
                                                <th>Litros / (mm)</th>
                                            </tr>
                                        </thead>
                                        <tbody>';

    
    echo $tablalitros;
    echo '</tbody></table>

        <div class="d-grid">
            <button type="submit" name="bttcrearEntrega" class="btn btn-success">
                <i class="fas fa-save"></i> GUARDAR ENTREGA Y LECHES SELECCIONADAS
            </button>
        </div>
        </form>
        </div></div></div></div>';
}

public function guardarEntregaConLeches($datos) {
    // Validar y manejar la imagen
    if (isset($_FILES['imagenEntrega']) && $_FILES['imagenEntrega']['error'] === UPLOAD_ERR_OK) {
        $imagen = $_FILES['imagenEntrega'];
        $nombreImagen = 'entrega_' . time() . '_' . basename($imagen['name']);
        $rutaImagen = __DIR__ . '/../entregas/' . $nombreImagen;


        if (!move_uploaded_file($imagen['tmp_name'], $rutaImagen)) {
            echo "<div class='errores'>Error al guardar la imagen.</div>";
            return;
        }
    } else {
        $nombreImagen = null;
    }
if($datos['idemp']=='')$datos['idemp']=0;
    // Reemplazo de decimales
    $datos['tement'] = str_replace(',', '.', $datos['tement']);
    $datos['denent'] = str_replace(',', '.', $datos['denent']);
    $datos['alcent'] = str_replace(',', '.', $datos['alcent']);

    // Insertar nueva entrega
    $sql = 'INSERT INTO "ENTREGA"(fecent, totent, codcli, idemp, totlit, obsent, 
            tieent, idusu, alcent, denent, tement, horent, imagen)
            VALUES (
                \'' . $datos['fecent'] . '\',
                ' . $datos['totent'] . ',
                ' . $datos['codcli'] . ',
                ' . $datos['idemp'] . ',
                0,
                \'' . $datos['obsent'] . '\',
                ' . time() . ',
                ' . $_SESSION['id'] . ',
                \'' . $datos['alcent'] . '\',
                ' . $datos['denent'] . ',
                ' . $datos['tement'] . ',
                \'' . $datos['horent'] . '\',
                \'' . $nombreImagen . '\'
            ) RETURNING ident;';

    if ($reg = $this->consulta($sql)) {
        $r = $this->row($reg);
        $idEntrega = $r['ident'];
        echo "<div class='alert alert-info'>Nueva entrega registrada  " . $datos['totent'] . " litros</div>";

        // Asignar las leches seleccionadas
        if (!empty($datos['leches'])) {
            foreach ($datos['leches'] as $idLeche) {
                $sqlLeche = 'INSERT INTO "ENTREGA_LECHE"(idlec, ident) 
                              VALUES (' . $idLeche . ',' . $idEntrega . ') RETURNING identlec;';

                if ($regLeche = $this->consulta($sqlLeche)) {
                    $rLeche = $this->row($regLeche);
                   // echo "<div class='mesajeok'><div class='alert alert-info'>Leche asignada con ID: " . $rLeche['identlec'] . "</div></div>";
                } else {
                    echo "<div class='errores'>Error al asignar leche con ID: " . $idLeche . "</div>";
                }
            }
        } else {
            echo "<div class='errores'>No se seleccionó ninguna leche para asignar.</div>";
        }
    } else {
        echo "<div class='errores'>Error al crear la nueva entrega en la base de datos.</div>";
    }

    return $idEntrega ?? null;
}

}
