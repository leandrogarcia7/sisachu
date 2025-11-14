<?php
require_once("EGRESO.php");
/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of CUENTA
 *
 * @author leand
 */
class CUENTA extends EGRESO {
    //put your code here
public function mostrarInicio() {
    $fechaInicio = date('Y-m-d', strtotime('-1 month'));
    $fechaFin = date('Y-m-d');

    echo '<center>
        <form method="get">

            <button type="submit" name="bttcrearCuenta"> 
                <img src="../img/anadir.png" alt=""/> <br> CREAR CUENTA
            </button>
            <br><br>

            <b>Buscar cuenta por código o nombre: </b>
            <input type="text" name="busquedaCuenta" placeholder="Ej: 1101 o Caja General" style="width: 250px;">
            <button type="submit" name="bttbuscarCuenta"> 
                <img src="../img/buscar.jpg" alt=""/> <br> BUSCAR
            </button>

          
            <button type="submit" name="bttresumenCuenta"> 
                <img src="../img/cuadernorojo.png" alt=""/> <br> RESUMEN DE CUENTAS
            </button>
        </form>
    </center>';
}


public function crearCuenta($datos) {
    $sql = 'INSERT INTO public."CUENTA" (
                codcue, detcue,
                nivel1cue, nivel2cue, nivel3cue, nivel4cue, nivel5cue,
                codcuedebe
            ) VALUES (
                ' . intval($datos['codcue']) . ',
                ' . $this->texto($datos['detcue']) . ',
                ' . (isset($datos['nivel1cue']) ? 1 : 0) . ',
                ' . (isset($datos['nivel2cue']) ? 1 : 0) . ',
                ' . (isset($datos['nivel3cue']) ? 1 : 0) . ',
                ' . (isset($datos['nivel4cue']) ? 1 : 0) . ',
                ' . (isset($datos['nivel5cue']) ? 1 : 0) . ',
                ' . ($datos['codcuedebe'] ?: 'NULL') . '
            );';

    if ($this->consulta($sql)) {
        echo "<div style='background-color:#d4edda;color:#155724;padding:20px;text-align:center;font-weight:bold;border-radius:10px;margin:20px auto;max-width:600px;'>✅ Cuenta creada correctamente.</div>";
    } else {
        echo "<div class='errores'>Error al crear la cuenta.</div>";
    }
}

    public function buscarCuenta($criterio) {
    $criterio = pg_escape_string($criterio);
    $sql = 'SELECT * FROM public."CUENTA"
            WHERE CAST(codcue AS TEXT) ILIKE \'%' . $criterio . '%\'
               OR detcue ILIKE \'%' . $criterio . '%\'
            ORDER BY codcue ASC;';
    
    $res = $this->consulta($sql);
    echo "<center><h3>Resultados de búsqueda para: <i>$criterio</i></h3></center>";
    $this->mostrarResultadoCuenta($res);
}

public function mostrarResultadoCuenta($res) {
    echo "<table border='1' align='center' width='80%'>
            <tr>
                <th>Código</th>
                <th>Detalle</th>
                <th>Nivel</th>
                <th>Cuenta Debe</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>";

    while ($reg = $this->fila($res)) {
        $niveles = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($reg["nivel{$i}cue"]) {
                $niveles .= "Nivel $i ";
            }
        }

        echo "<tr>
                <td>{$reg['codcue']}</td>
                <td>{$reg['detcue']}</td>
                <td>$niveles</td>
                <td>" . ($reg['codcuedebe'] ?: '-') . "</td>
                <td>{$reg['feccre']}</td>
                <td>
                    <form method='post'>
                        <button name='bttselCuenta' value='{$reg['codcue']}'>✏️</button>
                        <button name='btteliCuenta' value='{$reg['codcue']}' onclick=\"return confirm('¿Eliminar esta cuenta?');\">🗑️</button>
                    </form>
                </td>
              </tr>";
    }

    echo "</table>";
}

public function mostrarCuenta($id) {
    $sql = 'SELECT * FROM public."CUENTA" WHERE codcue = ' . intval($id);
    $res = $this->consulta($sql);
    $cuenta = $this->fila($res);

    echo '<center><form method="post">
        <h3>Editar Cuenta</h3>
        <input type="hidden" name="codcue" value="' . $cuenta['codcue'] . '">
        <table border="1">
            <tr><th>Detalle</th><td><input type="text" name="detcue" value="' . $cuenta['detcue'] . '" required></td></tr>
            <tr><th>Nivel 1</th><td><input type="checkbox" name="nivel1cue" value="1" ' . ($cuenta['nivel1cue'] ? 'checked' : '') . '></td></tr>
            <tr><th>Nivel 2</th><td><input type="checkbox" name="nivel2cue" value="1" ' . ($cuenta['nivel2cue'] ? 'checked' : '') . '></td></tr>
            <tr><th>Nivel 3</th><td><input type="checkbox" name="nivel3cue" value="1" ' . ($cuenta['nivel3cue'] ? 'checked' : '') . '></td></tr>
            <tr><th>Nivel 4</th><td><input type="checkbox" name="nivel4cue" value="1" ' . ($cuenta['nivel4cue'] ? 'checked' : '') . '></td></tr>
            <tr><th>Nivel 5</th><td><input type="checkbox" name="nivel5cue" value="1" ' . ($cuenta['nivel5cue'] ? 'checked' : '') . '></td></tr>
            <tr><th>Código Debe</th><td><input type="number" name="codcuedebe" value="' . $cuenta['codcuedebe'] . '"></td></tr>
            <tr><td colspan="2"><center><button type="submit" name="bttmodCuenta">💾 Guardar Cambios</button></center></td></tr>
        </table>
        </form></center>';
}
public function modificarCuenta($datos) {
    $sql = 'UPDATE public."CUENTA" SET
                detcue = ' . $this->texto($datos['detcue']) . ',
                nivel1cue = ' . (isset($datos['nivel1cue']) ? 1 : 0) . ',
                nivel2cue = ' . (isset($datos['nivel2cue']) ? 1 : 0) . ',
                nivel3cue = ' . (isset($datos['nivel3cue']) ? 1 : 0) . ',
                nivel4cue = ' . (isset($datos['nivel4cue']) ? 1 : 0) . ',
                nivel5cue = ' . (isset($datos['nivel5cue']) ? 1 : 0) . ',
                codcuedebe = ' . ($datos['codcuedebe'] ?: 'NULL') . '
            WHERE codcue = ' . intval($datos['codcue']) . ';';

    if ($this->consulta($sql)) {
        echo "<div style='background-color:#d4edda;color:#155724;padding:20px;text-align:center;font-weight:bold;border-radius:10px;margin:20px auto;max-width:600px;'>✅ Cuenta actualizada correctamente.</div>";
    } else {
        echo "<div class='errores'>Error al modificar la cuenta.</div>";
    }
}
public function eliminarCuenta($id) {
    $sql = 'DELETE FROM public."CUENTA" WHERE codcue = ' . intval($id);
    if ($this->consulta($sql)) {
        echo "<div style='background-color:#d4edda;color:#155724;padding:20px;text-align:center;font-weight:bold;border-radius:10px;margin:20px auto;max-width:600px;'>🗑️ Cuenta eliminada correctamente.</div>";
    } else {
        echo "<div class='errores'>No se pudo eliminar la cuenta.</div>";
    }
}
public function mostrarResumenCuentas() {
    $sql = 'SELECT nivel1cue, COUNT(*) AS total FROM public."CUENTA" GROUP BY nivel1cue ORDER BY nivel1cue;';
    $res = $this->consulta($sql);

    echo "<center><h3>Resumen de cuentas por Nivel 1</h3></center>";
    echo "<table border='1' align='center'>
            <tr><th>Nivel 1</th><th>Total Cuentas</th></tr>";
    while ($reg = $this->fila($res)) {
        echo "<tr><td>{$reg['nivel1cue']}</td><td>{$reg['total']}</td></tr>";
    }
    echo "</table>";
}

}
