<?php
require_once("ANIMALES.php");
/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of PROVEEDOR
 *
 * @author leand
 */
class PROVEEDOR extends ANIMALES{
    
    
    public function mostrarInicio(){
        echo '<center>
            <form>
            <b>Buscar por nombre: </b> <input type=text placeholder="Proveedor" name=txtbuscar >
            <button type="submit" name="bttbuscar" > <img src="../img/buscar.jpg" alt=""/>  <BR>BUSCAR PROVEEDOR</button>
            <br>
            <button type="submit" name="bttcrear" > <img src="../img/anadir.png" alt=""/>  <BR>CREAR PROVEEDOR</button>
            <button type="submit" name="bttresumen" > <img src="../img/cuadernorojo.png" alt=""/>  <BR>RESUMEN PROVEEDOR</button>
            </center>';
    }

    public function buscar($txtbuscar){
        echo '  <form> <center>
        <table border="1" class=table style="width:50%">
            <tr>
                <th>Código</th><th>Nombre</th><th>Acción</th>
            </tr>';

        $con=$this->consulta('select * from "PROVEEDOR" where idhac='.$_SESSION['idhac'].' and nompro ilike \''.addslashes($txtbuscar).'%\' order by estpro,nompro');

        while($a=$this->row($con)){
            echo '<tr>
                <td><h2>'.$a['codpro'].'</h2></td><td>'.$a['nompro'].'</td><td><button name=bttsel value='.$a['codpro'].'> <img src=../img/modif.jpg  > <br>Seleccionar</button></td>
                <td><button name=btteli onclick="javascript: return confirm(\'Esta seguro de Eliminar el Proveedor\');" value='.$a['codpro'].'><img src=../img/cancelar.jpg  > <br>Eliminar</button></td>
            </tr>';
        }

        echo '</table>
        </center></form>';
    }

    public function mostrarModificar($codpro){
        $con=$this->consulta('select * from "PROVEEDOR" where codpro='.$codpro);

        if($a=$this->row($con)){
            echo "<center><form><table BORDER=1><th colspan=2><center> Modificar los datos de Proveedor</center> <tr><th>Código<td>".$a['codpro']." <tr><th>Nombre<td><input type=text name=nompro value=\"".$a['nompro']."\">
                <tr><th colspan=2><center><button name=bttmod class=bttmod > <img src='../img/guardar.jpg'> <br>GUARDAR</button></center> </td></table>
                <input type=hidden name=codpro  value=".$a['codpro']." > </form></center>";
        } else {
            echo "<div class=errores >Error al seleccionar el proveedor de la BDD</div>";
        }
    }

    public function Modificar($datos){
        if($this->consulta('update "PROVEEDOR"  set   nompro=\''.$datos['nompro'].'\' where codpro='.$datos['codpro'])){
            echo "<div class=mesajeok >Cambios registrados</div>";
        } else {
            echo "<div class=errores >Error al modificar el proveedor de la BDD</div>";
        }
    }

    public function eliminar($codpro){
        if($this->consulta('delete from "PROVEEDOR"  where codpro='.$codpro)){
            echo "<div class=mesajeok >Datos Eliminados</div>";
        } else {
            echo "<div class=errores >Error al eliminar el proveedor de la BDD</div>";
        }
    }

    public function mostrarCrear(){
        echo "<center><form><table BORDER=1><th colspan=2><center>Crear un nuevo Proveedor</center> <tr><th>Nombre<td><input type=text name=nompro>
            <tr><th colspan=2><center><button name=bttnuevo class=bttnuevo > <img src='../img/guardar.jpg'> <br>GUARDAR</button></center> </td></table>
            </form></center>";
    }

    public function nuevo($param) {
        if($this->consulta('insert into "PROVEEDOR" (nompro) values (\''.$param['nompro'].'\');' )){
            echo "<div class=mesajeok >Nuevo dato registrado</div>";
        } else {
            echo "<div class=errores >Error al crear el nuevo dato BDD</div>";
        }
    }
    //put your code here
}
