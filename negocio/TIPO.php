<?php




require_once("BASE.php");
class TIPO extends connex {
    public $id, $detti, $codcue;

 public function mostrarInicio(){
        echo '<center><form>
            <!-- Formulario para Tipo_ingreso -->
            <b>Buscar por detalle de Ingreso: </b> 
            <input type="text" placeholder="INGRESO1" name="txtbuscarIngreso">
            <button type="submit" name="bttbuscarIngreso"> 
                <img src="../img/buscar.jpg" alt=""/> <br> BUSCAR INGRESO
            </button>
            <button type="submit" name="bttcrearIngreso"> 
                <img src="../img/anadir.png" alt=""/> <br> CREAR INGRESO
            </button>
            <button type="submit" name="bttresumenIngreso"> 
                <img src="../img/cuadernorojo.png" alt=""/> <br> RESUMEN INGRESO
            </button></form>
            
            <br><br> <!-- Espacio entre formularios -->

            <!-- Formulario para Tipo_Egreso --><form>
            <b>Buscar por detalle de Egreso: </b> 
            <input type="text" placeholder="EGRESO1" name="txtbuscarEgreso">
            <button type="submit" name="bttbuscarEgreso"> 
                <img src="../img/buscar.jpg" alt=""/> <br> BUSCAR EGRESO
            </button>
            <button type="submit" name="bttcrearEgreso"> 
                <img src="../img/anadir.png" alt=""/> <br> CREAR EGRESO
            </button>
            <button type="submit" name="bttresumenEgreso"> 
                <img src="../img/cuadernorojo.png" alt=""/> <br> RESUMEN EGRESO
            </button></form>
        </center>';
    }

   public function buscarTipoIngreso($txtbuscar){
         
    echo '<form> <center>
    <table border="1" class=table style="width:50%">
        <tr>
            <th>Id</th><th>Detalle</th><th>Acción</th><th>Eliminar</th>
        </tr>';
    
    $con = $this->consulta('select * from "TIPO_INGRESO" where detti ilike \''.addslashes($txtbuscar).'%\' order by detti');
    
    while($a = $this->row($con)){
                
        echo '<tr>
                <td><h2>'.$a['id'].'</h2></td>
                <td>'.$a['detti'].'</td>
                <td><button name=bttselti value='.$a['id'].'> 
                    <img src="../img/modif.jpg" alt="Modificar"> 
                    <br>Seleccionar
                </button></td>
                <td><button name=btteliti onclick="javascript: return confirm(\'¿Está seguro de eliminar el tipo de ingreso?\');" value='.$a['id'].'> 
                    <img src="../img/cancelar.jpg" alt="Eliminar"> 
                    <br>Eliminar
                </button></td>
            </tr>';
    }
    
    echo '</table>
    </center></form>';
}
  public function obtenerTiposIngresoDesdeClaseTipo() {
        $tiposIngreso = array();

        // Realiza la consulta para obtener los tipos de ingreso
        $con = $this->consulta('SELECT id, detti FROM "TIPO_INGRESO" where idhac='.$_SESSION['idhac']);

        // Recorre los resultados y almacena los tipos de ingreso en un arreglo
        while ($row = $this->row($con)) {
            $tiposIngreso[] = array(
                'id' => $row['id'],
                'detalle' => $row['detti']
            );
        }

        return $tiposIngreso;
    }
    
     public function obtenerTiposEgresoDesdeClaseTipo() {
        $tiposIngreso = array();

        // Realiza la consulta para obtener los tipos de ingreso
        $con = $this->consulta('SELECT id, dette FROM "TIPO_EGRESO" where idhac='.$_SESSION['idhac']);

        // Recorre los resultados y almacena los tipos de ingreso en un arreglo
        while ($row = $this->row($con)) {
            $tiposIngreso[] = array(
                'id' => $row['id'],
                'dette' => $row['dette']
            );
        }

        return $tiposIngreso;
    }
public function buscarTipoEgreso($txtbuscar){
         
    echo '<form> <center>
    <table border="1" class=table style="width:50%">
        <tr>
            <th>Id</th><th>Detalle</th><th>Acción</th><th>Eliminar</th>
        </tr>';
    
    $con = $this->consulta('select * from "TIPO_EGRESO" where dette ilike \''.addslashes($txtbuscar).'%\' order by dette');
    
    while($a = $this->row($con)){
                
        echo '<tr>
                <td><h2>'.$a['id'].'</h2></td>
                <td>'.$a['dette'].'</td>
                <td><button name=bttselte value='.$a['id'].'> 
                    <img src="../img/modif.jpg" alt="Modificar"> 
                    <br>Seleccionar
                </button></td>
                <td><button name=bttelite onclick="javascript: return confirm(\'¿Está seguro de eliminar el tipo de egreso?\');" value='.$a['id'].'> 
                    <img src="../img/cancelar.jpg" alt="Eliminar"> 
                    <br>Eliminar
                </button></td>
            </tr>';
    }
    
    echo '</table>
    </center></form>';
}

 public function mostrarCrearEgreso(){
        echo "<center>
                <form>
                    <table BORDER=1>
                        <th colspan=2><center>Crear un nuevo Tipo de Egreso</center></th>
                        <tr><th>Detalle</th><td><input type='text' name='detalle'></td></tr>
                        <tr><th colspan=2>
                            <center><button name='bttnuevoEgreso' class='bttnuevo'> 
                                <img src='../img/guardar.jpg' alt='Guardar'> 
                                <br>GUARDAR
                            </button></center> 
                        </th></tr>
                    </table>
                </form>
              </center>";
    }

 public function mostrarCrearIngreso(){
        echo "<center>
                <form>
                    <table BORDER=1>
                        <th colspan=2><center>Crear un nuevo Tipo de Ingreso</center></th>
                        <tr><th>Detalle</th><td><input type='text' name='detalle'></td></tr>
                        <tr><th colspan=2>
                            <center><button name='bttnuevoIngreso' class='bttnuevo'> 
                                <img src='../img/guardar.jpg' alt='Guardar'> 
                                <br>GUARDAR
                            </button></center> 
                        </th></tr>
                    </table>
                </form>
              </center>";
    }

public function crearTipoIngreso($datos) {
    $sql = 'insert into "TIPO_INGRESO" (detti,idhac) values (\'' . $datos['detalle'] . '\','.$_SESSION['idhac'].');';
    if ($res1 = $this->consulta($sql)) {
        echo "<div class=mesajeok>Nuevo tipo de ingreso registrado</div>";
    } else {
        echo "<div class=errores>Error al crear el nuevo tipo de ingreso en BDD " . $sql . " - " . pg_result_error($res1) . "</div>";
    } 
}


public function crearTipoEgreso($datos) {
    $sql = 'insert into "TIPO_EGRESO" (dette,idhac) values (\'' . $datos['detalle'] . '\','.$_SESSION['idhac'].');';
    if ($res1 = $this->consulta($sql)) {
        echo "<div class=mesajeok>Nuevo tipo de egreso registrado</div>";
    } else {
        echo "<div class=errores>Error al crear el nuevo tipo de egreso en BDD " . $sql . " - " . pg_result_error($res1) . "</div>";
    } 
}

public function mostrarTipoEgreso($id) {
    $con = $this->consulta('select * from "TIPO_EGRESO" where id=' . $id.' and idhac='.$_SESSION['idhac']);

    if ($a = $this->row($con)) {
        echo "<center><form><table BORDER=1><th colspan=2><center>Modificar Tipo de Egreso</center>";
        echo "<tr><th>ID<td>".$a['id'];
        echo "<tr><th>Detalle<td><input type=text name=dette value=\"" . $a['dette'] . "\">";
         echo "<tr><th colspan=2><center><button name=bttmodte class=bttmod > <img src='../img/guardar.jpg'> <br>GUARDAR</button></center> </td></table>";
        echo "<input type=hidden name=id value=" . $a['id'] . "> </form></center>";
    } else {
        echo "<div class=errores >Error al seleccionar el tipo de egreso de la BDD</div>";
    }
}

public function mostrarTipoIngreso($id) {
    $con = $this->consulta('select * from "TIPO_INGRESO" where id=' . $id.' and idhac='.$_SESSION['idhac']);

    if ($a = $this->row($con)) {
        echo "<center><form><table BORDER=1><th colspan=2><center>Modificar Tipo de Ingreso</center>";
        echo "<tr><th>ID<td>".$a['id'];
        echo "<tr><th>Detalle<td><input type=text name=detti value=\"" . $a['detti'] . "\">";
        echo "<tr><th colspan=2><center><button name=bttmodti class=bttmod > <img src='../img/guardar.jpg'> <br>GUARDAR</button></center> </td></table>";
        echo "<input type=hidden name=id value=" . $a['id'] . "> </form></center>";
    } else {
        echo "<div class=errores >Error al seleccionar el tipo de ingreso de la BDD</div>";
    }
}

public function modificarTipoIngreso($datos) {
    if ($this->consulta('update "TIPO_INGRESO" set detti=\'' . $datos['detti'] . '\' where id=' . $datos['id'])) {
        echo "<div class=mesajeok >Cambios en Tipo de Ingreso registrados</div>";
    } else {
        echo "<div class=errores >Error al modificar el tipo de ingreso en la BDD</div>";
    }
}

public function modificarTipoEgreso($datos) {
    if ($this->consulta('update "TIPO_EGRESO" set dette=\'' . $datos['dette'] . '\' where id=' . $datos['id'])) {
        echo "<div class=mesajeok >Cambios en Tipo de Egreso registrados</div>";
    } else {
        echo "<div class=errores >Error al modificar el tipo de egreso en la BDD</div>";
    }
}

public function eliminarTipoIngreso($id) {
    if ($this->consulta('delete from "TIPO_INGRESO" where id=' . $id)) {
        echo "<div class=mesajeok >Tipo de Ingreso Eliminado</div>";
    } else {
        echo "<div class=errores >Error al eliminar el tipo de ingreso de la BDD</div>";
    }
}

public function eliminarTipoEgreso($id) {
    if ($this->consulta('delete from "TIPO_EGRESO" where id=' . $id)) {
        echo "<div class=mesajeok >Tipo de Egreso Eliminado</div>";
    } else {
        echo "<div class=errores >Error al eliminar el tipo de egreso de la BDD</div>";
    }
}





}
