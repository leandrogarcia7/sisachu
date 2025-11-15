<?php
require '../negocio/LECHE.php';

class TANQUE extends LECHE {
public function modificarMedida($idMedida, $mm, $litros) {
    $sql = "UPDATE \"DETALLE_TANQUE\" 
            SET milimetros = $mm, litros = $litros 
            WHERE id = $idMedida";
    $this->consulta($sql);
}

    // Mostrar la interfaz para crear un tanque
 public function mostrarCrear() {
     
     
     
    echo "<center>
        <form>
            <table BORDER=1>
                <th colspan=2><center>Registro de Tanque</center></th>
                <tr>
                    <th>Nombre del Tanque:</th>
                    <td><input type='text' name='nombre' style='width: 100%;' required></td>
                </tr>
                <tr>
                    <th>Capacidad:</th>
                    <td><input type='number' step='0.01' name='capacidad' style='width: 100%;' required> Litros</td>
                </tr>
                <tr>
                    <th>Fabricante:</th>
                    <td><input type='text' name='fabricante' style='width: 100%;'></td>
                </tr>
                <tr>
                    <th>Mínimo:</th>
                    <td><input type='number' step='0.01' name='minimo' style='width: 100%;' required></td>
                </tr>
                <tr>
                    <th>Máximo:</th>
                    <td><input type='number' step='0.01' name='maximo' style='width: 100%;' required></td>
                </tr>
                <tr>
                    <th>Observaciones:</th>
                    <td><input type='text' name='observaciones' style='width: 100%;'></td>
                </tr>
                <tr>
                    <th colspan=2>
                        <center>
                            <button type='submit' name='bttnuevo' class='bttnuevo'>
                                <img src='../img/guardar.jpg'>
                                <br>GUARDAR
                            </button>
                        </center>
                    </th>
                </tr>
            </table>
        </form>
    </center>";
}



    // Guardar un nuevo tanque
    public function nuevo($data) {
        $sql = "INSERT INTO \"TANQUE\" (nombre, capacidad, fabricante, minimo, maximo, idhac) 
                VALUES ('{$data['nombre']}', {$data['capacidad']}, '{$data['fabricante']}', 
                        {$data['minimo']}, {$data['maximo']}, {$_SESSION['idhac']}) RETURNING id";
        $result = $this->consulta($sql);
        $row = pg_fetch_assoc($result);
        return $row['id'];
    }
    public function mostrarModificar($id) {
    $tanque = $this->buscarTanque($id); // Obtener los datos del tanque por ID

    echo "<center>
        <form method='POST'>
            <table BORDER=1>
                <th colspan=2><center>Modificar Tanque</center></th>
                <tr>
                    <th>Nombre del Tanque:</th>
                    <td><input type='text' name='nombre' style='width: 100%;' value='" . $tanque['nombre'] . "' required></td>
                </tr>
                <tr>
                    <th>Capacidad:</th>
                    <td><input type='number' step='0.01' name='capacidad' style='width: 100%;' value='" . $tanque['capacidad'] . "' required> Litros</td>
                </tr>
                <tr>
                    <th>Fabricante:</th>
                    <td><input type='text' name='fabricante' style='width: 100%;' value='" . $tanque['fabricante'] . "'></td>
                </tr>
                <tr>
                    <th>Mínimo:</th>
                    <td><input type='number' step='0.01' name='minimo' style='width: 100%;' value='" . $tanque['minimo'] . "' required></td>
                </tr>
                <tr>
                    <th>Máximo:</th>
                    <td><input type='number' step='0.01' name='maximo' style='width: 100%;' value='" . $tanque['maximo'] . "' required></td>
                </tr>
                <tr>
                    <th>Observaciones:</th>
                    <td><input type='text' name='observaciones' style='width: 100%;' value='" . $tanque['observaciones'] . "'></td>
                </tr>
                <tr>
                    <th colspan=2>
                        <center>
                            <input type='hidden' name='id' value='" . $tanque['id'] . "'>
                            <button type='submit' name='bttmod' class='bttmod'>
                                <img src='../img/guardar.jpg'>
                                <br>GUARDAR CAMBIOS
                            </button>
                        </center>
                    </th>
                </tr>
            </table>
        </form>
        <br>";

    // Mostrar la tabla de medidas del tanque
 echo "<form method='POST'>
        <table BORDER=1 style='width: 60%;'>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Milímetros</th>
                    <th>Litros</th>
                    <th>Opciones</th>
                </tr>
                <tr>
                    <td>Nuevo</td>
                    <td><input type='number' step='0.01' name='new_mm' style='width: 100%;' value='0' required></td>
                    <td><input type='number' step='0.01' name='new_litros' style='width: 100%;' value='0' required></td>
                    <td>
                        <button type='submit' name='bttcrearMedida' value='$id'>
                            <img src='../img/anadir.png'>
                            <br>Crear
                        </button>
                    </td>
                </tr>
            </thead>
            <tbody>";

// Obtener las medidas existentes para este tanque
$medidas = $this->obtenerMedidas($id); // Método que obtiene las medidas del tanque
foreach ($medidas as $medida) {
    echo "<tr>
            <form method='POST'>
                <td>{$medida['id']}</td>
                <td>
                    <input type='number' step='0.01' name='mod_mm_{$medida['id']}' style='width: 100%;' value='{$medida['milimetros']}' required>
                </td>
                <td>
                    <input type='number' step='0.01' name='mod_litros_{$medida['id']}' style='width: 100%;' value='{$medida['litros']}' required>
                </td>
                <td>
                    <input type='hidden' name='idMedida' value='{$medida['id']}'>
                    <input type='hidden' name='idTanque' value='$id'>
                    <button type='submit' name='bttmodMedida'>
                        <img src='../img/modif.jpg'>
                        <br>Modificar
                    </button>
                    <button type='submit' name='btteliMedida' value='{$medida['id']}' onclick=\"return confirm('¿Está seguro de eliminar esta medida?');\">
                        <img src='../img/cancelar.jpg'>
                        <br>Eliminar
                    </button>
                </td>
            </form>
        </tr>";
}

echo "</tbody>
        </table>
    </center>";

}
public function eliminarMedida($idMedida) {
    $sql = "DELETE FROM \"DETALLE_TANQUE\" WHERE id = $idMedida";
    $this->consulta($sql);
}

    public function mostrarModificar2($id) {
    $tanque = $this->buscarTanque($id); // Obtener los datos del tanque por ID

    echo "<center>
        <form method='POST'>
            <table BORDER=1>
                <th colspan=2><center>Modificar Tanque</center></th>
                <tr>
                    <th>Nombre del Tanque:</th>
                    <td><input type='text' name='nombre' style='width: 100%;' value='" . $tanque['nombre'] . "' required></td>
                </tr>
                <tr>
                    <th>Capacidad:</th>
                    <td><input type='number' step='0.01' name='capacidad' style='width: 100%;' value='" . $tanque['capacidad'] . "' required> Litros</td>
                </tr>
                <tr>
                    <th>Fabricante:</th>
                    <td><input type='text' name='fabricante' style='width: 100%;' value='" . $tanque['fabricante'] . "'></td>
                </tr>
                <tr>
                    <th>Mínimo:</th>
                    <td><input type='number' step='0.01' name='minimo' style='width: 100%;' value='" . $tanque['minimo'] . "' required></td>
                </tr>
                <tr>
                    <th>Máximo:</th>
                    <td><input type='number' step='0.01' name='maximo' style='width: 100%;' value='" . $tanque['maximo'] . "' required></td>
                </tr>
                <tr>
                    <th>Observaciones:</th>
                    <td><input type='text' name='observaciones' style='width: 100%;' value='" . $tanque['observaciones'] . "'></td>
                </tr>
                <tr>
                    <th colspan=2>
                        <center>
                            <input type='hidden' name='id' value='" . $tanque['id'] . "'>
                            <button type='submit' name='bttmod' class='bttmod'>
                                <img src='../img/guardar.jpg'>
                                <br>GUARDAR CAMBIOS
                            </button>
                        </center>
                    </th>
                </tr>
            </table>
        </form>
        <br>";

    // Mostrar la tabla de medidas del tanque
    echo "<form method='POST'>
        <table BORDER=1 style='width: 60%;'>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Milímetros</th>
                    <th>Litros</th>
                    <th>Opciones</th>
                </tr>
                <tr>
                    <td>Nuevo</td>
                    <td><input type='number' step='0.01' name='mm' style='width: 100%;' value='0' required></td>
                    <td><input type='number' step='0.01' name='litros' style='width: 100%;' value='0' required></td>
                    <td>
                        <button type='submit' name='bttcrearMedida' value='$id'>
                            <img src='../img/anadir.png'>
                            <br>Crear
                        </button>
                    </td>
                </tr>
            </thead>
            <tbody>";
    
    // Obtener las medidas existentes para este tanque
    $medidas = $this->obtenerMedidas($id); // Método que debe implementar la consulta
    foreach ($medidas as $medida) {
        echo "<tr>
                <td>{$medida['id']}</td>
                <td>{$medida['milimetros']}</td>
                <td>{$medida['litros']}</td>
                <td>
                    <button type='submit' name='bttmodMedida' value='{$medida['id']}'>
                        <img src='../img/modif.jpg'>
                        <br>Modificar
                    </button>
                    <button type='submit' name='btteliMedida' value='{$medida['id']}' onclick=\"return confirm('¿Está seguro de eliminar esta medida?');\">
                        <img src='../img/cancelar.jpg'>
                        <br>Eliminar
                    </button>
                </td>
            </tr>";
    }

    echo "</tbody>
        </table>
    </form>
    </center>";
}

public function obtenerMedidas($tanqueId) {
    $sql = "SELECT * FROM \"DETALLE_TANQUE\" WHERE tanque_id = $tanqueId order by milimetros";
    $result = $this->consulta($sql);
    $medidas = [];
    while ($row = pg_fetch_assoc($result)) {
        $medidas[] = $row;
    }
    return $medidas;
}
public function crearMedida($tanqueId, $mm, $litros) {
    $sql = "INSERT INTO \"DETALLE_TANQUE\" (tanque_id, milimetros, litros) VALUES ($tanqueId, $mm, $litros)";
    $this->consulta($sql);
}

    // Mostrar el formulario para modificar un tanque
  


    // Guardar los cambios de un tanque existente
    public function modificar($data) {
        $sql = "UPDATE \"TANQUE\" 
                SET nombre = '{$data['nombre']}', capacidad = {$data['capacidad']}, 
                    fabricante = '{$data['fabricante']}', minimo = {$data['minimo']}, 
                    maximo = {$data['maximo']}, idhac = {$_SESSION['idhac']}
                WHERE id = {$data['id']}";
        $this->consulta($sql);
    }

    // Buscar un tanque por ID
    public function buscarTanque($id) {
        $sql = "SELECT * FROM \"TANQUE\" WHERE id = $id";
        $result = $this->consulta($sql);
        return pg_fetch_assoc($result);
    }

    // Mostrar la lista de tanques
  public function mostrarInicio() {
    //session_start();
    if (!isset($_SESSION['idhac'])) {
        die('Error: ID de hacienda no definido en la sesión.');
    }

    $idhac = $_SESSION['idhac']; // ID de la hacienda desde la sesión
    $sql = "SELECT * FROM \"TANQUE\" WHERE idhac = $idhac";
    $result = $this->consulta($sql);

    echo '<center>
        <form>
            <button type="submit" name="bttcrear">
                <img src="../img/anadir.png" alt=""/>  
                <br>REGISTRAR TANQUE
            </button>
        </form>
        <br><br>
        <h3>Lista de Tanques Registrados</h3>  <form> 
        <center>
            <table border="1" class="table" style="width:50%">
                <tr>
                    <th>Nombre</th>
                    <th>Capacidad</th>
                    <th>Fabricante</th>
                    <th>Mínimo</th>
                    <th>Máximo</th>
                    <th>Acción</th>
                </tr>';
    
    // Iterar sobre los resultados y generar filas para cada tanque
    while ($tanque = pg_fetch_assoc($result)) {
        echo '<tr>
                <td>' . $tanque['nombre'] . '</td>
                <td>' . $tanque['capacidad'] . ' L</td>
                <td>' . $tanque['fabricante'] . '</td>
                <td>' . $tanque['minimo'] . ' L</td>
                <td>' . $tanque['maximo'] . ' L</td>
                 <td>
                    <button name="btttablatanque" value="' . $tanque['id'] . '">
                        <img src="../img/imprimir.jpg" alt="Tabla">
                        <br>Tabla
                    </button>
                </td>    
                <td>
                    <button name="bttsel" value="' . $tanque['id'] . '">
                        <img src="../img/modif.jpg" alt="Modificar">
                        <br>Seleccionar
                    </button>
                </td>
                <td>
                    <button name="btteli" value="' . $tanque['id'] . '" onclick="return confirm(\'¿Está seguro de eliminar este tanque?\');">
                        <img src="../img/cancelar.jpg" alt="Eliminar">
                        <br>Eliminar
                    </button>
                </td>
            </tr>';
    }
    
    echo '</table>
        </center>
    </form>';
}



    // Eliminar un tanque
    public function eliminar($id) {
        $sql = "DELETE FROM \"TANQUE\" WHERE id = $id";
        $this->consulta($sql);
    }
    
  public function mostraTablaTanque($tanque_id) {
    // Consultar los datos de DETALLE_TANQUE
    $sql = "SELECT milimetros, litros FROM \"DETALLE_TANQUE\" WHERE tanque_id = $tanque_id ORDER BY milimetros ASC";
    $resultado = $this->consulta($sql);

    if (!$resultado) {
        die("Error en la consulta.");
    }

    // Organizar los datos en un array asociativo
    $datos = [];
    while ($fila = pg_fetch_assoc($resultado)) {
        $datos[$fila['milimetros']] = $fila['litros'];
    }

    // Crear la tabla HTML con bordes claros para las celdas
    echo '<table border="1" style="border-collapse: collapse; width: 100%; text-align: center;">';
    echo '<thead>';
    echo '<tr><th>H (mm)</th>';

    // Crear los encabezados de las columnas (0 a 9)
    for ($col = 0; $col <= 9; $col++) {
        echo "<th>$col</th>";
    }

    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    // Generar filas desde H(mm) 0 hasta 1000
    for ($fila = 0; $fila <= 1000; $fila += 10) {
        echo "<tr>";
        echo "<td>$fila</td>";

        for ($col = 0; $col <= 9; $col++) {
            $mm = $fila + $col;

            if (isset($datos[$mm])) {
                echo "<td style='border: 1px solid black;'>{$datos[$mm]}</td>";
            } else {
                echo "<td style='border: 1px solid black;'></td>";
            }
        }

        echo "</tr>";
    }

    echo '</tbody>';
    echo '</table><br><br>';
}

 
}
?>
