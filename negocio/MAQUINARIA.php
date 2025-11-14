<?php
require_once("MATERIAL.php");

class MAQUINARIA extends MATERIAL {

    public $estmaq= array('FUERA DE SERVICIO','DISPONIBLE','EN REPARACION');
    
    
    
    
    
    public function mostrarInicio() {
        echo '<center>
            <form>
            <b>Buscar por detalle: </b> <input type="text" placeholder="Detalle de la maquinaria" name="txtbuscar">
            <button type="submit" name="bttbuscar"> <img src="../img/buscar.jpg" alt=""/> <br>BUSCAR MAQUINARIA</button>
            <br>
            <button type="submit" name="bttcrear"> <img src="../img/anadir.png" alt=""/> <br>CREAR MAQUINARIA</button>
            </center>';
    }

  public function mostrarCrear() {
        echo '<center><h1>Agregar Maquinaria</h1>
<form method="POST">
  <label for="detmaq">Detalle:</label>
  <input type="text" id="detmaq" name="detmaq" required><br><br>
  
  <label for="estmaq">Estado:</label>
  <select id="estmaq" name="estmaq" required>';
        // Aquí debes agregar las opciones del estado de la maquinaria
        
         foreach ($this->estmaq as $key => $value) {
             echo '<option value="'.$key.'">'.$value.'</option>';
         }
  echo '</select><br><br>

  <button type="submit" name="bttnuevo"> <img src="../img/guardar.jpg"> <br>GUARDAR</button>
</form></center>';
    }

   public function nuevo($datos) {
        $consulta = "INSERT INTO \"MAQUINARIA\" (detmaq, estmaq,idhac) VALUES ('";
        $consulta .= addslashes($datos['detmaq']) . "', ";
        $consulta .= addslashes($datos['estmaq']) . "', ";
        $consulta .= intval($_SESSION['idhac']) . ")";

        $resultado = $this->consulta($consulta);

        if ($resultado) {
            echo "<br>Maquinaria agregada con éxito";
        } else {
            echo "<br>Error al agregar maquinaria: " . $consulta;
        }
    }


   public function buscar($txtbuscar) {
    echo '<center>
          <form>
          <table border="1" class="table" style="width:50%">
              <tr>
                  <th>Id</th>
                  <th>Detalle</th>
                  <th>Estado</th>
                  <th>Acción</th>
              </tr>';

    $consulta = 'SELECT * FROM "MAQUINARIA" WHERE detmaq ILIKE \'%' . $txtbuscar . '%\' and idhac='.$_SESSION['idhac'].'  ORDER BY detmaq';
    $resultados = $this->consulta($consulta);

    while ($a = $this->row($resultados)) {
        echo '<tr>
                  <td>'.$a['id'].'</td>
                  <td>'.$a['detmaq'].'</td>
                  <td>'. (isset($this->estmaq[$a['estmaq']]) ? $this->estmaq[$a['estmaq']] : 'Desconocido') .'</td>
                  <td>
                      <button name="bttsel" value="'.$a['id'].'"> <img src="../img/modif.jpg"> <br>Seleccionar</button>
                      <button name="btteli" onclick="return confirm(\'¿Está seguro de eliminar la maquinaria?\');" value="'.$a['id'].'"><img src="../img/cancelar.jpg"> <br>Eliminar</button>
                  </td>
              </tr>';
    }

    echo '</table>
          </center></form>';
}



   public function mostrarModificar($id) {
    $consulta = 'SELECT * FROM "MAQUINARIA" WHERE id = '.$id.' and idhac='.$_SESSION['idhac'].' ;';
    $resultado = $this->consulta($consulta);

    if ($a = $this->row($resultado)) {
        echo '<center><form method="POST">
              <table border="1">
                  <tr><th>Id:</th><td>' . $a['id'] . '<input type="hidden" name="id" value="' . $a['id'] . '"></td></tr>
                  <tr><th>Detalle:</th><td><input type="text" name="detmaq" value="' . $a['detmaq'] . '"></td></tr>
                  <tr><th>Estado:</th><td><select name="estmaq">';
        
        // Asumiendo que tienes un array de estados de maquinaria
        foreach ($this->estmaq as $key => $value) {
            $selected = $key == $a['estmaq'] ? 'selected' : '';
            echo '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';
        }

        echo '</select></td></tr>
              <tr><td colspan="2"><center><button type="submit" name="bttmod"> <img src="../img/guardar.jpg"> <br>Guardar</button></center></td></tr>
              </table></form></center>';
    } else {
        echo "<div class='errores'>Error al seleccionar de la BDD Maquinaria</div>";
    }
}



public function modificarMaquinaria($datos) {
    $consulta = "UPDATE \"MAQUINARIA\" SET ";
    $consulta .= "detmaq = '" . addslashes($datos['detmaq']) . "', ";
    $consulta .= "estmaq = " . intval($datos['estmaq']) . " ";
    $consulta .= "WHERE id = " . intval($datos['id']);

    $resultado = $this->consulta($consulta);

    if ($resultado) {
        echo "<br>Maquinaria modificada con éxito";
    } else {
        echo "<br>Error al modificar la maquinaria: " . $consulta;
    }
}



     public function eliminarMaquinaria($id) {
        return $this->consulta('DELETE FROM "MAQUINARIA" WHERE id = '.$id);
    }

    // Otros métodos que puedan ser necesarios
}
?>
