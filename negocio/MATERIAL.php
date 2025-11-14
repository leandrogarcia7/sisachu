<?php
require_once("POTREROS.php");

class MATERIAL extends POTREROS {

    public $tipmat= array('GENERAL','AGRARIO','VETERINARIO','LIMPIEZA','ALIMENTACION','ORDEÑO');
    
    
    
    
    
    public function mostrarInicio(){
       echo '<center>
            <form>
            <b>Buscar por detalle: </b> <input type="text" placeholder="Detalle del material" name="txtbuscar">
            <button type="submit" name="bttbuscar"> <img src="../img/buscar.jpg" alt=""/> <br>BUSCAR MATERIAL</button>
            <br>
            <button type="submit" name="bttcrear"> <img src="../img/anadir.png" alt=""/> <br>CREAR MATERIAL</button>
            </center>';
    }

    public function mostrarCrear() {
        echo '<center><h1>Agregar Material</h1>
<form method="POST">
  <label for="detmat">Detalle:</label>
  <input type="text" id="detmat" name="detmat" required><br><br>

  <label for="canmat">Cantidad:</label>
  <input type="number" id="canmat" name="canmat" required><br><br>

  <label for="medmat">Medida:</label>
  <input type="text" id="medmat" name="medmat" required><br><br>

  <label for="tipmat">Tipo:</label>
  <select id="tipmat" name="tipmat" required>';
        foreach ($this->tipmat as $key => $value) {
            echo '<option value="'.$key.'">'.$value.'</option>';
        }
  echo '</select><br><br>

  <button type="submit" name="bttnuevo"> <img src="../img/guardar.jpg"> <br>GUARDAR</button>
</form></center>';
    }

 public function nuevo($datos) {
    $consulta = "INSERT INTO \"MATERIAL\" (detmat, canmat, medmat, idhac,tipmat) VALUES ('";
    $consulta .= addslashes($datos['detmat']) . "', ";
    $consulta .= intval($datos['canmat']) . ", '";
    $consulta .= addslashes($datos['medmat']) . "', ";
     $consulta .= intval($_SESSION['idhac']) . ",";
    $consulta .= intval($datos['tipmat']) . ")";

    $resultado = $this->consulta($consulta);

    if ($resultado) {
        echo "<br>Material agregado con éxito";
    } else {
        echo "<br>Error al agregar material: " . $consulta;
    }
}


    public function buscar($txtbuscar) {
    echo '<center>
          <form>
          <table border="1" class="table" style="width:50%">
              <tr>
                  <th>Id</th>
                  <th>Detalle</th>
                  <th>Cantidad</th>
                  <th>Medida</th>
                  <th>Tipo</th>
                  <th>Acción</th>
              </tr>';

    $consulta = 'SELECT * FROM "MATERIAL" WHERE detmat ILIKE \'%' . $txtbuscar . '%\' and idhac='.$_SESSION['idhac'].' ORDER BY detmat';
    $resultados = $this->consulta($consulta);

    while ($a = $this->row($resultados)) {
        echo '<tr>
                  <td>'.$a['id'].'</td>
                  <td>'.$a['detmat'].'</td>
                  <td>'.$a['canmat'].'</td>
                  <td>'.$a['medmat'].'</td>
                  <td>'.$this->tipmat[$a['tipmat']].'</td>
                  <td>
                      <button name="bttsel" value="'.$a['id'].'"> <img src="../img/modif.jpg"> <br>Seleccionar</button>
                      <button name="btteli" onclick="return confirm(\'Esta seguro de eliminar el material?\');" value="'.$a['id'].'"><img src="../img/cancelar.jpg"> <br>Eliminar</button>
                  </td>
              </tr>';
    }

    echo '</table>
          </center></form>';
}


    public function mostrarModificar($id) {
    $consulta = 'SELECT * FROM "MATERIAL" WHERE id = '.$id.' and idhac='.$_SESSION['idhac'];
    $resultado = $this->consulta($consulta);

    if ($a = $this->row($resultado)) {
        echo '<center><form method="POST">
              <table border="1">
                  <tr><th>Id:</th><td>' . $a['id'] . '<input type="hidden" name="id" value="' . $a['id'] . '"></td></tr>
                  <tr><th>Detalle:</th><td><input type="text" name="detmat" value="' . $a['detmat'] . '"></td></tr>
                  <tr><th>Cantidad:</th><td><input type="number" name="canmat" value="' . $a['canmat'] . '"></td></tr>
                  <tr><th>Medida:</th><td><input type="text" name="medmat" value="' . $a['medmat'] . '"></td></tr>
                  <tr><th>Tipo:</th><td><select name="tipmat">';
        
        foreach ($this->tipmat as $key => $value) {
            $selected = $key == $a['tipmat'] ? 'selected' : '';
            echo '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';
        }

        echo '</select></td></tr>
              <tr><td colspan="2"><center><button type="submit" name="bttmod"> <img src="../img/guardar.jpg"> <br>Guardar</button></center></td></tr>
              </table></form></center>';
    } else {
        echo "<div class='errores'>Error al seleccionar de la BDD Material</div>";
    }
}


 public function modificarMaterial($datos) {
    $consulta = "UPDATE \"MATERIAL\" SET ";
    $consulta .= "detmat = '" . addslashes($datos['detmat']) . "', ";
    $consulta .= "canmat = " . intval($datos['canmat']) . ", ";
    $consulta .= "medmat = '" . addslashes($datos['medmat']) . "', ";
    $consulta .= "tipmat = " . intval($datos['tipmat']) . " ";
    $consulta .= "WHERE id = " . intval($datos['id']);

    $resultado = $this->consulta($consulta);

    if ($resultado) {
        echo "<br>Material modificado con éxito";
    } else {
        echo "<br>Error al modificar el material: " . $consulta;
    }
}


     public function eliminarMaterial($idMaterial) {
        return $this->consulta('DELETE FROM "MATERIAL" WHERE id = '.$idMaterial.' and idhac'.$_SESSION['idhac']);
    }

    // Otros métodos que puedan ser necesarios
}
?>
