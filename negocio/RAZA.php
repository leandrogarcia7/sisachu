<?php
require_once("BASE.php");
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RAZA
 *
 * @author Leandro
 */
class RAZA extends connex {
    //put your code here
     public $id,$detalle;
     
     
     public function mostrarInicio(){
         echo '<center>
             <form>
             <b>Buscar por detalle: </b> <input type=text placeholder=HOLSTEIN name=txtbuscar >
             <button type="submit" name="bttbuscar" > <img src="../img/buscar.jpg" alt=""/>  <BR>BUSCAR RAZA</button>
             <br>
             <button type="submit" name="bttcrear" > <img src="../img/anadir.png" alt=""/>  <BR>CREAR RAZA</button>
             <button type="submit" name="bttresumen" > <img src="../img/cuadernorojo.png" alt=""/>  <BR>RESUMEN RAZA</button>
             </center>';
         
     }
     
     public function buscar($txtbuscar){
         
          echo '  <form> <center>
        <table border="1" class=table style="width:50%">
            <tr>
                <th>Id</th><th>Detalle</th><th>Acción</th>
            </tr>';
    

      //   $fotos= $ani->mostrarFotosAnimal($a['id'],300);
        
        
        
         $con=$this->consulta('select * from "RAZA" where detalle ilike \''.addslashes($txtbuscar).'%\' order by estraza,detalle');
    

        while($a=$this->row($con)){
                    
        echo '<tr>
                <td><h2>'.$a['id'].'</h2></td><td>'.$a['detalle'].'</td><td><button name=bttsel value='.$a['id'].'> <img src=../img/modif.jpg  > <br>Seleccionar</button></td>
                    <td><button name=btteli onclick="javascript: return confirm(\'Esta seguro de Eliminar la Raza\');" value='.$a['id'].'><img src=../img/cancelar.jpg  > <br>Eliminar</button></td>
            </tr>';
    }
    
    echo '</table>
        
    </center></form>';
    }   
         
    public function mostrarModificar($id){
        $con=$this->consulta('select * from "RAZA" where id='.$id);
        
        if($a=$this->row($con)){
            echo "<center><form><table BORDER=1><th colspan=2><center> Modificar los datos de Raza</center> <tr><th>Id<td>".$a['id']." <tr><th>Detalle<td><input type=text name=detalle value=\"".$a['detalle']."\">
                <tr><th colspan=2><center><button name=bttmod class=bttmod > <img src='../img/guardar.jpg'> <br>GUARDAR</button></center> </td></table>
               <input type=hidden name=id  value=".$a['id']." > </form></center>";
            
            
            
        }else{
            echo "<div class=errores >Error al seleccionar la raza de la BDD</div>";
        }
        
    }
    
     public function Modificar($datos){
         if($this->consulta('update "RAZA"  set   detalle=\''.$datos['detalle'].'\' where id='.$datos['id'])){
             echo "<div class=mesajeok >Cambios registrados</div>";
         }else
         {
              echo "<div class=errores >Error al modificar la raza de la BDD</div>";
         }             
         
         
     }
    
     public function eliminar($id){
           if($this->consulta('delete from "RAZA"  where id='.$id)){
             echo "<div class=mesajeok >Datos Eliminados</div>";
         }else
         {
              echo "<div class=errores >Error al eliminar la raza de la BDD</div>";
         }             
         
         
     
     }
     
     public function mostrarCrear(){
         
               echo "<center><form><table BORDER=1><th colspan=2><center>Crear una nueva Raza</center> <tr><th>Detalle<td><input type=text name=detalle>
                <tr><th colspan=2><center><button name=bttnuevo class=bttnuevo > <img src='../img/guardar.jpg'> <br>GUARDAR</button></center> </td></table>
                </form></center>";
         
     }
     
     public function nuevo($param) {
             if($this->consulta('insert into "RAZA" (detalle) values (\''.$param['detalle'].'\');' )){
             echo "<div class=mesajeok >Nuevo dato registrado</div>";
         }else
         {
              echo "<div class=errores >Error al crear el nuevo dato BDD</div>";
         }  
         
         
     }
}
