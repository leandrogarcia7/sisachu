<?php
require_once("MAQUINARIA.php");
/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of TRABAJO
 *
 * @author leand
 */
class TRABAJO extends MAQUINARIA{
    public $pdo; 
    public function __construct() {
        parent::__construct();         // llama al constructor de connex
        $this->pdo = $this->getPDO();  // inicializa la propiedad $pdo
    }
 
    
    public $tiptra = array(
    1 => 'Siembra',
    2 => 'Cosecha',
    3 => 'Mantenimiento de cultivos',
    4 => 'Fertilización',
    5 => 'Riego',
    6 => 'Alimentación del ganado',
    7 => 'Ordeño',
    8 => 'Vacunación',
    9 => 'Traslado de ganado',
    10 => 'Control veterinario',
    11 => 'Mantenimiento de maquinaria',
    12 => 'Construcción y reparación de infraestructuras',
    13 => 'Limpieza y ordenamiento de áreas',
    14 => 'Gestión de recursos',
    15 => 'Planificación y administración'
);
    
    public $idsub = array(
    1 => 'Puntual',
    2 => 'Semanal'
 
);
    
    
    public function mostrarCrear()
{
    // ==== Helpers locales ====
    $esc = fn($s) => htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');

    // Estados (reutiliza tu método)
    $estados = $this->obtenerEstados(); // ej: [0=>'Pendiente',1=>'OK',2=>'Falla']

    // Opciones para selects (traídas de BD)
    $optsMaterial   = $this->optionsFrom('SELECT id, detmat AS label FROM "MATERIAL" ORDER BY detmat', 'id', 'label');
    $optsMaquinaria = $this->optionsFrom('SELECT id, detmaq AS label FROM "MAQUINARIA" ORDER BY detmaq', 'id', 'label');
    $optsPotrero    = $this->optionsFrom('SELECT idpot AS id, nompot AS label FROM "POTREROS" ORDER BY nompot', 'id', 'label');
    $optsEmpleado   = $this->optionsFrom('SELECT idemp AS id, nombrecompleto AS label FROM "EMPLEADOS" ORDER BY nombrecompleto', 'id', 'label');

    echo '<center><h1>Agregar Nuevo Trabajo</h1>
<form method="POST" style="max-width:900px;text-align:left;display:inline-block;">
  <fieldset style="border:1px solid #ccc;padding:12px;border-radius:8px;margin-bottom:12px;">
    <legend><b>Datos del Trabajo</b></legend>

    <label for="fectra">Fecha del Trabajo:</label><br>
    <input type="date" id="fectra" name="fectra" required><br><br>

    <label for="dettra">Detalle del Trabajo:</label><br>
    <input type="text" id="dettra" name="dettra" required style="width:100%;max-width:800px;"><br><br>

    <label for="fecinitra">Fecha de Inicio:</label><br>
    <input type="date" id="fecinitra" name="fecinitra" required><br><br>

    <label for="fecfintra">Fecha de Fin:</label><br>
    <input type="date" id="fecfintra" name="fecfintra" required><br><br>

    <input type="hidden" id="idsub" name="idsub" value="0">

    <label for="tiptra">Tipo de Trabajo:</label><br>
    <select id="tiptra" name="tiptra" required>';

    foreach ($this->tiptra as $key => $value) {
        echo '<option value="'.$esc($key).'">'.$esc($value).'</option>';
    }

    echo '</select>
  </fieldset>';

    // ==== Bloque: MATERIAL (2 líneas) ====
    echo '<fieldset style="border:1px solid #ccc;padding:12px;border-radius:8px;margin-bottom:12px;">
      <legend><b>Material (hasta 2)</b></legend>';

    for ($i = 1; $i <= 2; $i++) {
        echo '<div style="display:grid;grid-template-columns:1fr 120px 120px 160px;gap:8px;align-items:center;margin-bottom:8px;">
          <div>
            <label>Material '.$i.' (opcional)</label>
            <select name="material['.$i.'][idmat]">
              <option value="">-- (sin material) --</option>';
                foreach ($optsMaterial as $opt) {
                    echo '<option value="'.$esc($opt["value"]).'">'.$esc($opt["label"]).'</option>';
                }
        echo '  </select>
          </div>
          <div>
            <label>Cantidad</label>
            <input type="number" step="0.01" min="0" name="material['.$i.'][cantidad]" placeholder="0">
          </div>
          <div>
            <label>Medida</label>
            <input type="text" name="material['.$i.'][medida]" placeholder="kg, m, und...">
          </div>
          <div>
            <label>Estado</label>
            <select name="material['.$i.'][estado]">
              <option value="">--</option>';
                foreach ($estados as $k => $lbl) {
                    echo '<option value="'.$esc($k).'">'.$esc($lbl).'</option>';
                }
        echo '  </select>
          </div>
        </div>';
    }
    echo '</fieldset>';

    // ==== Bloque: MAQUINARIA (2 líneas) ====
    echo '<fieldset style="border:1px solid #ccc;padding:12px;border-radius:8px;margin-bottom:12px;">
      <legend><b>Maquinaria (hasta 2)</b></legend>';

    for ($i = 1; $i <= 2; $i++) {
        echo '<div style="display:grid;grid-template-columns:1fr 120px 120px 160px;gap:8px;align-items:center;margin-bottom:8px;">
          <div>
            <label>Maquinaria '.$i.' (opcional)</label>
            <select name="maquinaria['.$i.'][idmaq]">
              <option value="">-- (sin maquinaria) --</option>';
                foreach ($optsMaquinaria as $opt) {
                    echo '<option value="'.$esc($opt["value"]).'">'.$esc($opt["label"]).'</option>';
                }
        echo '  </select>
          </div>
          <div>
            <label>Cantidad</label>
            <input type="number" step="0.01" min="0" name="maquinaria['.$i.'][cantidad]" placeholder="0">
          </div>
          <div>
            <label>Medida</label>
            <input type="text" name="maquinaria['.$i.'][medida]" placeholder="h, km, und...">
          </div>
          <div>
            <label>Estado</label>
            <select name="maquinaria['.$i.'][estado]">
              <option value="">--</option>';
                foreach ($estados as $k => $lbl) {
                    echo '<option value="'.$esc($k).'">'.$esc($lbl).'</option>';
                }
        echo '  </select>
          </div>
        </div>';
    }
    echo '</fieldset>';

    // ==== Bloque: POTRERO (2 líneas) ====
    echo '<fieldset style="border:1px solid #ccc;padding:12px;border-radius:8px;margin-bottom:12px;">
      <legend><b>Potrero (hasta 2)</b></legend>';

    for ($i = 1; $i <= 2; $i++) {
        echo '<div style="margin-bottom:8px;">
          <label>Potrero '.$i.' (opcional)</label>
          <select name="potrero['.$i.'][idpot]">
            <option value="">-- (sin potrero) --</option>';
            foreach ($optsPotrero as $opt) {
                echo '<option value="'.$esc($opt["value"]).'">'.$esc($opt["label"]).'</option>';
            }
    echo '  </select>
        </div>';
    }
    echo '</fieldset>';

    // ==== Bloque: EMPLEADO (2 líneas) ====
    echo '<fieldset style="border:1px solid #ccc;padding:12px;border-radius:8px;margin-bottom:12px;">
      <legend><b>Empleado (hasta 2)</b></legend>';

    for ($i = 1; $i <= 2; $i++) {
        echo '<div style="margin-bottom:8px;">
          <label>Empleado '.$i.' (opcional)</label>
          <select name="empleado['.$i.'][idemp]">
            <option value="">-- (sin empleado) --</option>';
            foreach ($optsEmpleado as $opt) {
                echo '<option value="'.$esc($opt["value"]).'">'.$esc($opt["label"]).'</option>';
            }
    echo '  </select>
        </div>';
    }
    echo '</fieldset>

  <div style="text-align:center;">
    <button type="submit" name="bttnuevo" style="border:none;background:#2e7d32;color:#fff;padding:10px 16px;border-radius:6px;cursor:pointer;">
      <img src="../img/guardar.jpg" alt="guardar" style="height:24px;vertical-align:middle;margin-right:6px;">
      <b>GUARDAR</b>
    </button>
  </div>
</form></center>';
}

/**
 * Construye arreglo de opciones para selects.
 * Devuelve: [ [value=>..., label=>...], ... ]
 */
private function optionsFrom($sql, $valueField, $labelField)
{
    $out = [];
    $res = $this->consulta($sql);
    while ($r = $this->row($res)) {
        $out[] = ['value' => $r[$valueField], 'label' => $r[$labelField]];
    }
    return $out;
}

 public function mostrarCrear2() {
    echo '<center><h1>Agregar Nuevo Trabajo</h1>
<form method="POST">
  <label for="fectra">Fecha del Trabajo:</label>
  <input type="date" id="fectra" name="fectra" required><br><br>

  <label for="dettra">Detalle del Trabajo:</label>
  <input type="text" id="dettra" name="dettra" required><br><br>

  <label for="fecinitra">Fecha de Inicio:</label>
  <input type="date" id="fecinitra" name="fecinitra" required><br><br>

  <label for="fecfintra">Fecha de Fin:</label>
  <input type="date" id="fecfintra" name="fecfintra" required><br><br>

  
  <input type="hidden" id="idsub" name="idsub" value=0><br><br>

  <label for="tiptra">Tipo de Trabajo:</label>
  <select id="tiptra" name="tiptra" required>';
    foreach ($this->tiptra as $key => $value) {
        echo '<option value="'.$key.'">'.$value.'</option>';
    }
    echo '</select><br><br>

  <button type="submit" name="bttnuevo"> <img src="../img/guardar.jpg"> <br>GUARDAR</button>
</form></center>';
}
public function nuevo($datos)
{
    if (!isset($_SESSION['idhac'])) {
        echo "<div class='errores'>❌ Falta idhac en la sesión.</div>";
        return false;
    }

    // Normalizadores
    $trimS = fn($v) => trim((string)($v ?? ''));
    $intV  = fn($v) => (int)($v ?? 0);

    // Campos base del TRABAJO
    $fectra    = $trimS($datos['fectra']    ?? '');
    $dettra    = $trimS($datos['dettra']    ?? '');
    $fecfintra = $trimS($datos['fecfintra'] ?? '');
    $fecinitra = $trimS($datos['fecinitra'] ?? '');
    $idsub     = $intV($datos['idsub']      ?? 0);
    $tiptra    = $intV($datos['tiptra']     ?? 0);
    $idhac     = $intV($_SESSION['idhac']);

    if ($fectra === '' || $dettra === '' || $fecfintra === '' || $fecinitra === '') {
        echo "<div class='errores'>❌ Campos obligatorios vacíos.</div>";
        return false;
    }

    try {
        $this->pdo->beginTransaction();

        // Insert principal con RETURNING id
        $sql = 'INSERT INTO "TRABAJO"
                (fectra, dettra, fecfintra, fecinitra, idsub, idhac, tiptra)
                VALUES (:fectra,:dettra,:fecfintra,:fecinitra,:idsub,:idhac,:tiptra)
                RETURNING id;';
        $st = $this->pdo->prepare($sql);
        $st->execute([
            ':fectra'    => $fectra,
            ':dettra'    => $dettra,
            ':fecfintra' => $fecfintra,
            ':fecinitra' => $fecinitra,
            ':idsub'     => $idsub,
            ':idhac'     => $idhac,
            ':tiptra'    => $tiptra,
        ]);

        $idTrabajo = (int)$st->fetchColumn();
        if (!$idTrabajo) {
            throw new RuntimeException('No se obtuvo ID de TRABAJO.');
        }

        // Helper: procesa hasta 2 elementos del arreglo dado
        $procesarHastaDos = function(array $items, callable $fn) {
            $i = 0;
            foreach ($items as $row) {
                $i++;
                if ($i > 2) break;               // límite 2
                $fn($row, $i);
            }
        };

        // ===== Material (hasta 2) =====
        $material = is_array($datos['material'] ?? null) ? $datos['material'] : [];
        $procesarHastaDos($material, function($m) use ($idTrabajo) {
            $idRelacionado = isset($m['idmat']) && $m['idmat'] !== '' ? (int)$m['idmat'] : 0;
            if ($idRelacionado <= 0) return; // vacío

            $cantidad = ($m['cantidad'] ?? '') !== '' ? (float)$m['cantidad'] : 1;
            $medida   = trim((string)($m['medida'] ?? 'unidad'));
            $estado   = ($m['estado'] ?? '') !== '' ? (int)$m['estado'] : 1;

            $this->asociarATrabajo('MATERIAL', $idTrabajo, $idRelacionado, $cantidad, $medida, $estado);
        });

        // ===== Maquinaria (hasta 2) =====
        $maquinaria = is_array($datos['maquinaria'] ?? null) ? $datos['maquinaria'] : [];
        $procesarHastaDos($maquinaria, function($q) use ($idTrabajo) {
            $idRelacionado = isset($q['idmaq']) && $q['idmaq'] !== '' ? (int)$q['idmaq'] : 0;
            if ($idRelacionado <= 0) return;

            $cantidad = ($q['cantidad'] ?? '') !== '' ? (float)$q['cantidad'] : 1;
            $medida   = trim((string)($q['medida'] ?? 'unidad'));
            $estado   = ($q['estado'] ?? '') !== '' ? (int)$q['estado'] : 1;

            $this->asociarATrabajo('MAQUINARIA', $idTrabajo, $idRelacionado, $cantidad, $medida, $estado);
        });

        // ===== Potrero (hasta 2) =====
        $potrero = is_array($datos['potrero'] ?? null) ? $datos['potrero'] : [];
        $procesarHastaDos($potrero, function($p) use ($idTrabajo) {
            $idRelacionado = isset($p['idpot']) && $p['idpot'] !== '' ? (int)$p['idpot'] : 0;
            if ($idRelacionado <= 0) return;

            $this->asociarATrabajo('POTRERO', $idTrabajo, $idRelacionado);
        });

        // ===== Empleado (hasta 2) =====
        $empleado = is_array($datos['empleado'] ?? null) ? $datos['empleado'] : [];
        $procesarHastaDos($empleado, function($e) use ($idTrabajo) {
            $idRelacionado = isset($e['idemp']) && $e['idemp'] !== '' ? (int)$e['idemp'] : 0;
            if ($idRelacionado <= 0) return;

            $this->asociarATrabajo('EMPLEADO', $idTrabajo, $idRelacionado);
        });

        $this->pdo->commit();
        echo "<div class='ok'>✅ Trabajo creado exitosamente con ID: {$idTrabajo}</div>";
        return $idTrabajo;

    } catch (Throwable $ex) {
        if ($this->pdo->inTransaction()) {
            $this->pdo->rollBack();
        }
        echo "<div class='errores'>❌ Error al crear trabajo: ".$ex->getMessage()."</div>";
        return false;
    }
}


    // Inserta un nuevo trabajo en la base de datos
  public function nuevo2($datos) {
    // Asegurarse de que los datos están correctamente escapados para prevenir inyecciones SQL
    $fectra = addslashes($datos['fectra']);
    $dettra = addslashes($datos['dettra']);
    $fecfintra = addslashes($datos['fecfintra']);
    $fecinitra = addslashes($datos['fecinitra']);
    $idsub = intval($datos['idsub']);
    $tiptra = intval($datos['tiptra']);

    // Construir la consulta SQL
    $consulta = "INSERT INTO \"TRABAJO\" (fectra, dettra, fecfintra, fecinitra, idsub,idhac, tiptra) VALUES ('";
    $consulta .= $fectra . "', '";
    $consulta .= $dettra . "', '";
    $consulta .= $fecfintra . "', '";
    $consulta .= $fecinitra . "', ";
    $consulta .= $idsub . ", ";
        $consulta .= $_SESSION['idhac'] . ", ";
    $consulta .= $tiptra . ")  RETURNING id";

    // Ejecutar la consulta
    $result = $this->consulta($consulta);
    if ($row = pg_fetch_assoc($result)) {
        echo "Trabajo creado exitosamente con ID: " . $row['id'];
        return $row['id'];
    } else {
        echo "Error al ingresar a la BDD.".$consulta;
        return false;
    }
}

public function mostrarInicio() {
    echo '<center>
        <form method="GET">
        <b>Buscar por detalle del trabajo: </b> 
        <input type="text" placeholder="Detalle del trabajo" name="txtbuscar">
        <button type="submit" name="bttbuscar"> <img src="../img/buscar.jpg" alt=""/> <br>BUSCAR TRABAJO</button>
    
        <br>
   
        <button type="submit" name="bttcrear"> <img src="../img/anadir.png" alt=""/> <br>CREAR TRABAJO</button>
        </form>
        </center>';
}

public function buscar($txtbuscar) {
    $estados = $this->obtenerEstadosTrabajo(); // Usamos la función de estados

    echo '<center>
          <form>
          <table border="1" class="table" style="width:80%">
              <tr style="background-color:#ddd">
                  <th>Id</th>
                  <th>Detalle</th>
                  <th>Tipo</th>
                  <th>Fecha</th>
                  <th>Estado</th>
                  <th>Acción</th>
              </tr>';

    $consulta = 'SELECT * FROM "TRABAJO" 
                 WHERE dettra ILIKE \'%' . $txtbuscar . '%\' 
                   AND idhac=' . $_SESSION['idhac'] . ' 
                 ORDER BY idsub ASC, fectra  
                 LIMIT 50';

    $resultados = $this->consulta($consulta);

    while ($a = $this->row($resultados)) {
        // Color por estado
        switch ($a['idsub']) {
            case 0: $color = '#f0f0f0'; break; // Creado
            case 1: $color = '#e0f0ff'; break; // Asignado
            case 2: $color = '#fff8dc'; break; // Pendiente
            case 3: $color = '#e6ffe6'; break; // Terminado
            default: $color = '#ffffff'; break;
        }

        echo '<tr style="background-color:' . $color . '">
                  <td>' . $a['id'] . '</td>
                  <td>' . $a['dettra'] . '</td>
                  <td>' . (isset($this->tiptra[$a['tiptra']]) ? $this->tiptra[$a['tiptra']] : 'Desconocido') . '</td>
                  <td>' . $a['fectra'] . '</td>
                  <td>' . (isset($estados[$a['idsub']]) ? $estados[$a['idsub']] : 'N/A') . '</td>
                  <td>
                      <button name="bttsel" value="' . $a['id'] . '"> <img src="../img/modif.jpg"> <br>Seleccionar</button>
                      <button name="btteli" onclick="return confirm(\'¿Está seguro de eliminar el trabajo?\');" value="' . $a['id'] . '"><img src="../img/cancelar.jpg"> <br>Eliminar</button>
                  </td>
              </tr>';
    }

    echo '</table>
          </center></form>';
}
public function eliminarTrabajo($id) {
    $sql = 'DELETE FROM "TRABAJO" WHERE id = :id AND idhac = :idhac';

    $stmt = $this->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':idhac', $_SESSION['idhac'], PDO::PARAM_INT);

    try {
        $stmt->execute();
        echo "<div class='ok'>🗑️ Trabajo eliminado correctamente.</div>";
    } catch (PDOException $e) {
        echo "<div class='errores'>❌ Error al eliminar trabajo: " . $e->getMessage() . "</div>";
    }
}


private function obtenerEstadosTrabajo() {
    return [
        0 => 'Creado',
        1 => 'Asignado',
        2 => 'Pendiente',
        3 => 'Terminado'
    ];
}
        public function mostrarModificar2($id) {
    // Consulta principal (con parámetros para evitar inyección SQL)
    $sqlTrabajo = 'SELECT * FROM "TRABAJO" WHERE id = :id AND idhac = :idhac';
    $stmtTrabajo = $this->prepare($sqlTrabajo);
    $stmtTrabajo->bindParam(':id', $id, PDO::PARAM_INT);
    $stmtTrabajo->bindParam(':idhac', $_SESSION['idhac'], PDO::PARAM_INT);
    $stmtTrabajo->execute();
    $trabajo = $stmtTrabajo->fetch(PDO::FETCH_ASSOC);

    if (!$trabajo) {
        echo "<div class='errores'>Error al seleccionar el trabajo.</div>";
        return;
    }

    // Mostrar formulario de edición del trabajo
    echo '<center>
    <h3>Modificar Trabajo</h3>
    <form method="POST">
      <table border="1" cellpadding="4" cellspacing="0">
        <tr>
          <th>ID</th>
          <td>' . htmlspecialchars($trabajo['id']) . '
            <input type="hidden" name="id" value="' . htmlspecialchars($trabajo['id']) . '">
          </td>
        </tr>
        <tr>
          <th>Fecha del Trabajo</th>
          <td><input type="date" name="fectra" value="' . htmlspecialchars($trabajo['fectra']) . '"></td>
        </tr>
        <tr>
          <th>Detalle del Trabajo</th>
          <td><input type="text" name="dettra" value="' . htmlspecialchars($trabajo['dettra']) . '"></td>
        </tr>
        <tr>
          <th>Fecha de Inicio</th>
          <td><input type="date" name="fecinitra" value="' . htmlspecialchars($trabajo['fecinitra']) . '"></td>
        </tr>
        <tr>
          <th>Fecha de Fin</th>
          <td><input type="date" name="fecfintra" value="' . htmlspecialchars($trabajo['fecfintra']) . '"></td>
        </tr>
        <tr>
          <th>Estado del Trabajo</th>
          <td>
            <select name="idsub">';
              $estados = $this->obtenerEstadosTrabajo();
              foreach ($estados as $k => $v) {
                  $selected = ($trabajo['idsub'] == $k) ? 'selected' : '';
                  echo '<option value="' . htmlspecialchars($k) . '" ' . $selected . '>' . htmlspecialchars($v) . '</option>';
              }
    echo     '</select>
          </td>
        </tr>
        <tr>
          <th>Tipo de Trabajo</th>
          <td>
            <select name="tiptra">';
              foreach ($this->tiptra as $k => $v) {
                  $selected = ($trabajo['tiptra'] == $k) ? 'selected' : '';
                  echo '<option value="' . htmlspecialchars($k) . '" ' . $selected . '>' . htmlspecialchars($v) . '</option>';
              }
    echo     '</select>
          </td>
        </tr>
        <tr>
          <td colspan="2" style="text-align:center;">
            <button type="submit" name="bttmod">
              <img src="../img/guardar.jpg" alt="Guardar"><br>Guardar
            </button>
          </td>
        </tr>
      </table>
    </form>
    </center>
    <br>';

    // Tipos asociados y sus consultas
    $tiposAsociados = [
        'MATERIAL'   => [
            'sql'   => 'SELECT tm.id, m.detmat AS detalle, tm.cantramat AS cantidad, tm.medtramat AS medida, tm.esttramat AS estado
                        FROM "TRABAJO_MATERIAL" tm
                        JOIN "MATERIAL" m ON m.id = tm.idmat
                        WHERE tm.idtra = :idtra',
            'campos' => ['detalle', 'cantidad', 'medida', 'estado']
        ],
        'MAQUINARIA' => [
            'sql'   => 'SELECT tm.id, maq.detmaq AS detalle, tm.cantramaq AS cantidad, tm.medtramaq AS medida, tm.esttramaq AS estado
                        FROM "TRABAJO_MAQUINARIA" tm
                        JOIN "MAQUINARIA" maq ON maq.id = tm.idmaq
                        WHERE tm.idtra = :idtra',
            'campos' => ['detalle', 'cantidad', 'medida', 'estado']
        ],
        'POTRERO'    => [
            'sql'   => 'SELECT tp.id, p.nompot AS detalle
                        FROM "TRABAJO_POTRERO" tp
                        JOIN "POTREROS" p ON p.idpot = tp.idpot
                        WHERE tp.idtra = :idtra',
            'campos' => ['detalle']
        ],
        'EMPLEADO'   => [
            'sql'   => 'SELECT te.id, e.nombrecompleto AS detalle
                        FROM "TRABAJO_EMPLEADO" te
                        JOIN "EMPLEADOS" e ON e.idemp = te.idemp
                        WHERE te.idtra = :idtra',
            'campos' => ['detalle']
        ],
    ];

    // Obtener los estados para los select de Material y Maquinaria
    $estadosGenerales = $this->obtenerEstados();

    // Recorrer cada tipo asociado y mostrar su tabla
    foreach ($tiposAsociados as $tipo => $config) {
        $stmt = $this->prepare($config['sql']);
        $stmt->bindParam(':idtra', $id, PDO::PARAM_INT);
        $stmt->execute();
        $asociados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo '<center><h4>' . htmlspecialchars($tipo) . ' Asociado</h4>';
        echo '<table border="1" cellpadding="3" cellspacing="0">
              <tr>';

        // Encabezados comunes
        echo '<th>Detalle</th>';
        if (in_array('cantidad', $config['campos'])) {
            echo '<th>Cantidad</th><th>Medida</th><th>Estado</th>';
        }
        echo '<th>Acciones</th></tr>';

        // Filas de datos
        foreach ($asociados as $fila) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($fila['detalle']) . '</td>';

            if (isset($fila['cantidad'])) {
                // Materiales/Maquinaria
                echo '<td>' . htmlspecialchars($fila['cantidad']) . '</td>';
                echo '<td>' . htmlspecialchars($fila['medida']) . '</td>';
                echo '<td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="idTrabajo" value="' . htmlspecialchars($id) . '">
                            <input type="hidden" name="idAsociado" value="' . htmlspecialchars($fila['id']) . '">
                            <input type="hidden" name="tipoAsociado" value="' . htmlspecialchars($tipo) . '">
                            <select name="estado">';
                                foreach ($estadosGenerales as $k => $label) {
                                    $sel = ($fila['estado'] == $k) ? "selected" : "";
                                    echo '<option value="' . htmlspecialchars($k) . '" ' . $sel . '>' . htmlspecialchars($label) . '</option>';
                                }
                echo         '</select>
                        </form>
                      </td>';
            } else {
                // Potrero o Empleado (sin cantidad/medida/estado)
                echo '<td colspan="3"></td>';
            }

            // Botones de acción (modificar/eliminar)
            echo '<td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="idTrabajo" value="' . htmlspecialchars($id) . '">
                        <input type="hidden" name="idAsociado" value="' . htmlspecialchars($fila['id']) . '">
                        <input type="hidden" name="tipoAsociado" value="' . htmlspecialchars($tipo) . '">
                        <button type="submit" name="modificarAsociado" title="Modificar">✏️</button>
                        <button type="submit" name="eliminarAsociado" title="Eliminar"
                            onclick="return confirm(\'¿Eliminar este registro?\')">🗑️</button>
                    </form>
                  </td>';
            echo '</tr>';
        }

        echo '</table></center><br>';
    }
}

public function mostrarModificar($id) {
    $consulta = 'SELECT * FROM "TRABAJO" WHERE id = ' . $id . ' AND idhac=' . $_SESSION['idhac'] . ';';
    $resultado = $this->consulta($consulta);

    if ($a = $this->row($resultado)) {
        echo '<center><form method="POST">
              <table border="1">
                  <tr><th>Id:</th><td>' . $a['id'] . '<input type="hidden" name="id" value="' . $a['id'] . '"></td></tr>
                  <tr><th>Fecha del Trabajo:</th><td><input type="date" name="fectra" value="' . $a['fectra'] . '"></td></tr>
                  <tr><th>Detalle del Trabajo:</th><td><input type="text" name="dettra" value="' . $a['dettra'] . '"></td></tr>
                  <tr><th>Fecha de Inicio:</th><td><input type="date" name="fecinitra" value="' . $a['fecinitra'] . '"></td></tr>
                  <tr><th>Fecha de Fin:</th><td><input type="date" name="fecfintra" value="' . $a['fecfintra'] . '"></td></tr>
                  <tr><th>Estado del Trabajo:</th><td><select name="idsub">';
$estados = $this->obtenerEstadosTrabajo();
foreach ($estados as $k => $v) {
    $sel = ($a['idsub'] == $k) ? 'selected' : '';
    echo '<option value="' . $k . '" ' . $sel . '>' . $v . '</option>';
}
echo '</select></td></tr>
<tr><th>Tipo de Trabajo:</th><td><select name="tiptra">';

        foreach ($this->tiptra as $key => $value) {
            $selected = $key == $a['tiptra'] ? 'selected' : '';
            echo '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';
        }

        echo '</select></td></tr>
              <tr><td colspan="2"><center><button type="submit" name="bttmod"> <img src="../img/guardar.jpg"> <br>Guardar</button></center></td></tr>
              </table></form></center>';
// Mostrar los asociados
        foreach (['MATERIAL', 'MAQUINARIA', 'POTRERO', 'EMPLEADO'] as $tipo) {
            $this->mostrarAsociados($tipo, $id);
        }
        // Mostrar formularios para asociar recursos
        foreach (['MATERIAL', 'MAQUINARIA', 'POTRERO', 'EMPLEADO'] as $tipo) {
            $this->mostrarFormularioAsociar($tipo, $id);
        }

        

    } else {
        echo "<div class='errores'>Error al seleccionar de la BDD Trabajo</div>";
    }
}


private function mostrarAsociados($tipo, $idTrabajo) {
    $estados = $this->obtenerEstados();

    switch ($tipo) {
        case 'MATERIAL':
            $sql = 'SELECT tm.id, m.detmat AS detalle, tm.cantramat AS cantidad, tm.medtramat AS medida, tm.esttramat AS estado
                    FROM "TRABAJO_MATERIAL" tm
                    JOIN "MATERIAL" m ON m.id = tm.idmat
                    WHERE tm.idtra = ' . $idTrabajo;
            break;

        case 'MAQUINARIA':
            $sql = 'SELECT tm.id, maq.detmaq AS detalle, tm.cantramaq AS cantidad, tm.medtramaq AS medida, tm.esttramaq AS estado
                    FROM "TRABAJO_MAQUINARIA" tm
                    JOIN "MAQUINARIA" maq ON maq.id = tm.idmaq
                    WHERE tm.idtra = ' . $idTrabajo;
            break;

        case 'POTRERO':
            $sql = 'SELECT tp.id, p.nompot AS detalle
                    FROM "TRABAJO_POTRERO" tp
                    JOIN "POTREROS" p ON p.idpot = tp.idpot
                    WHERE tp.idtra = ' . $idTrabajo;
            break;

        case 'EMPLEADO':
            $sql = 'SELECT te.id, e.nombrecompleto AS detalle
                    FROM "TRABAJO_EMPLEADO" te
                    JOIN "EMPLEADOS" e ON e.idemp = te.idemp
                    WHERE te.idtra = ' . $idTrabajo;
            break;

        default:
            return;
    }

    $res = $this->consulta($sql);

    echo "<center><h4>$tipo Asociado</h4><table border='1'>
            <tr><th>Detalle</th>";

    if (in_array($tipo, ['MATERIAL', 'MAQUINARIA'])) {
        echo "<th>Cantidad</th><th>Medida</th><th>Estado</th>";
    }

    echo "<th>Acciones</th></tr>";

    while ($r = $this->row($res)) {
        echo "<tr>
                <td>{$r['detalle']}</td>";

        if (isset($r['cantidad'])) {
            echo "<td>{$r['cantidad']}</td><td>{$r['medida']}</td><td>";

            // Campo de estado (solo si existe)
            echo "<form method='POST' style='display:inline'>
                    <input type='hidden' name='idTrabajo' value='$idTrabajo'>
                    <input type='hidden' name='idAsociado' value='{$r['id']}'>
                    <input type='hidden' name='tipoAsociado' value='$tipo'>
                    <select name='estado'>";
            foreach ($estados as $k => $label) {
                $sel = ($r['estado'] == $k) ? "selected" : "";
                echo "<option value='$k' $sel>$label</option>";
            }
            echo "</select>";
        } else {
            // Para POTRERO y EMPLEADO (no hay estado/cantidad/medida)
            echo "<td colspan='3'><form method='POST' style='display:inline'>
                    <input type='hidden' name='idTrabajo' value='$idTrabajo'>
                    <input type='hidden' name='idAsociado' value='{$r['id']}'>
                    <input type='hidden' name='tipoAsociado' value='$tipo'>";
        }

        echo "</td><td>
                <button type='submit' name='modificarAsociado'>✏️</button>
                <button type='submit' name='eliminarAsociado' onclick=\"return confirm('¿Eliminar este registro?')\">🗑️</button>
              </form>
              </td>
              </tr>";
    }

    echo "</table></center><br>";
}

public function modificarAsociado($tipo, $idAsociado, $estado) {
    switch ($tipo) {
        case 'MAQUINARIA':
            $sql = 'UPDATE "TRABAJO_MAQUINARIA" SET esttramaq = :estado WHERE id = :idAsociado';
            break;
        case 'MATERIAL':
            $sql = 'UPDATE "TRABAJO_MATERIAL" SET esttramat = :estado WHERE id = :idAsociado';
            break;
        // Otros tipos si en el futuro agregas más campos modificables
        default:
            echo "<div class='errores'>Tipo no soportado para modificar estado.</div>";
            return;
    }

    $stmt = $this->prepare($sql);
    $stmt->bindParam(':estado', $estado, PDO::PARAM_INT);
    $stmt->bindParam(':idAsociado', $idAsociado, PDO::PARAM_INT);

    try {
        $stmt->execute();
        echo "<div class='ok'>✅ Estado actualizado correctamente.</div>";
    } catch (PDOException $e) {
        echo "<div class='errores'>❌ Error al actualizar: " . $e->getMessage() . "</div>";
    }
}

private function obtenerEstados() {
    return [
        0 => 'Asignado',
        1 => 'Disponible',
        2 => 'No disponible',
        3 => 'Implementado',
    ];
}
public function eliminarAsociado($tipo, $idAsociado) {
    switch ($tipo) {
        case 'MAQUINARIA':
            $sql = 'DELETE FROM "TRABAJO_MAQUINARIA" WHERE id = :id';
            break;
        case 'MATERIAL':
            $sql = 'DELETE FROM "TRABAJO_MATERIAL" WHERE id = :id';
            break;
        case 'POTRERO':
            $sql = 'DELETE FROM "TRABAJO_POTRERO" WHERE id = :id';
            break;
        case 'EMPLEADO':
            $sql = 'DELETE FROM "TRABAJO_EMPLEADO" WHERE id = :id';
            break;
        default:
            echo "<div class='errores'>Tipo no soportado para eliminar.</div>";
            return;
    }

    $stmt = $this->prepare($sql);
    $stmt->bindParam(':id', $idAsociado, PDO::PARAM_INT);

    try {
        $stmt->execute();
        echo "<div class='ok'>🗑️ Asociación eliminada correctamente.</div>";
    } catch (PDOException $e) {
        echo "<div class='errores'>❌ Error al eliminar: " . $e->getMessage() . "</div>";
    }
}

private function mostrarFormularioAsociar($tipo, $idTrabajo) {
    $detalles = $this->obtenerDetalles($tipo);
    $estados = $this->obtenerEstados(); // obtener lista de estados

    echo "<center><form method='POST'>
            <input type='hidden' name='idTrabajo' value='$idTrabajo'>
            <table border='1'>
                <tr><th colspan='2'>Asociar $tipo</th></tr>
                <tr><td>$tipo:</td><td>
                    <select name='id$tipo'>";
    foreach ($detalles as $detalle) {
        echo "<option value='" . $detalle['id'] . "'>" . $detalle['detalle'] . "</option>";
    }
    echo "        </select>
                </td></tr>";

    // Campos adicionales según el tipo
    if (in_array($tipo, ['MAQUINARIA', 'MATERIAL'])) {
        echo "<tr><td>Cantidad:</td><td><input type='number' step='0.01' name='cantidad' value='1'></td></tr>
              <tr><td>Medida:</td><td><input type='text' name='medida' value='qq'></td></tr>
              <tr><td>Estado:</td><td>
                <select name='estado'>";
        foreach ($estados as $key => $label) {
            echo "<option value='$key'>$label</option>";
        }
        echo "</select></td></tr>";
    }

    echo "<tr><td colspan='2'><center>
                <button type='submit' name='asociar$tipo'>
                    Asociar <br><img src='../img/$tipo.png' style='height: 50px; width: auto;'>
                </button>
          </center></td></tr>
          </table>
          </form></center><br>";
}


public function asociarATrabajo($tipo, $idTrabajo, $idRelacionado, $cantidad = 1, $medida = 'unidad', $estado = 1) {
    switch ($tipo) {
        case 'MAQUINARIA':
            $consulta = 'INSERT INTO "TRABAJO_MAQUINARIA" (idmaq, idtra, cantramaq, medtramaq, esttramaq) 
                         VALUES (:idRelacionado, :idTrabajo, :cantidad, :medida, :estado);';
            break;

        case 'MATERIAL':
            $consulta = 'INSERT INTO "TRABAJO_MATERIAL" (idmat, idtra, cantramat, medtramat, esttramat) 
                         VALUES (:idRelacionado, :idTrabajo, :cantidad, :medida, :estado);';
            break;

        case 'POTRERO':
            $consulta = 'INSERT INTO "TRABAJO_POTRERO" (idtra, idpot) 
                         VALUES (:idTrabajo, :idRelacionado);';
            break;

        case 'EMPLEADO':
            $consulta = 'INSERT INTO "TRABAJO_EMPLEADO" (idtra, idemp) 
                         VALUES (:idTrabajo, :idRelacionado);';
            break;
    }

    $stmt = $this->pdo->prepare($consulta);
    $stmt->bindParam(':idTrabajo', $idTrabajo, PDO::PARAM_INT);
    $stmt->bindParam(':idRelacionado', $idRelacionado, PDO::PARAM_INT);

    if (in_array($tipo, ['MAQUINARIA', 'MATERIAL'])) {
        $stmt->bindParam(':cantidad', $cantidad);
        $stmt->bindParam(':medida', $medida);
        $stmt->bindParam(':estado', $estado);
    }

    try {
        $stmt->execute();
        echo "<div class='ok'>✅ $tipo asociado correctamente al trabajo.</div>";
    } catch (PDOException $e) {
        echo "<div class='errores'>❌ Error al asociar $tipo: " . $e->getMessage() . "</div>";
    }
}


private function obtenerDetalles($tipo) {
    // Consulta SQL varía según el tipo
    switch ($tipo) {
        case 'MAQUINARIA':
            $consulta = 'SELECT id, detmaq AS detalle FROM "MAQUINARIA" WHERE idhac='.$_SESSION['idhac'].';';
            break;
        case 'MATERIAL':
            $consulta = 'SELECT id, detmat AS detalle FROM "MATERIAL" WHERE idhac='.$_SESSION['idhac'].';';
            break;
        case 'EMPLEADO':
            $consulta = 'SELECT idemp AS id, nombrecompleto AS detalle FROM "EMPLEADOS" WHERE idhac='.$_SESSION['idhac'].';';
            break;
        case 'POTRERO':
            $consulta = 'SELECT idpot AS id, nompot AS detalle FROM "POTREROS" WHERE idhac='.$_SESSION['idhac'].';';
            break;
        // Agregar caso para 'POTRERO' si tienes una estructura similar
    }

    $resultados = $this->consulta($consulta);
    return $this->fetchAll($resultados); // Asume una función que convierte los resultados en un array
}


public function modificarTrabajo($datos) {
    // Asegurarse de que los datos están correctamente escapados para prevenir inyecciones SQL
    $fectra = addslashes($datos['fectra']);
    $dettra = addslashes($datos['dettra']);
    $fecfintra = addslashes($datos['fecfintra']);
    $fecinitra = addslashes($datos['fecinitra']);
    $idsub = intval($datos['idsub']);
    $tiptra = intval($datos['tiptra']);
    $id = intval($datos['id']);

    // Construir la consulta SQL en una sola cadena
    $consulta = "UPDATE \"TRABAJO\" SET "
              . "fectra = '$fectra', "
              . "dettra = '$dettra', "
              . "fecfintra = '$fecfintra', "
              . "fecinitra = '$fecinitra', "
              . "idsub = $idsub, "
              . "tiptra = $tiptra "
              . "WHERE id = $id";

    // Ejecutar la consulta
    $resultado = $this->consulta($consulta);

    // Verificar el resultado
    if ($resultado) {
        echo "<br>Trabajo modificado con éxito";
    } else {
        echo "<br>Error al modificar el trabajo: " . $consulta;
    }
}

    // Otros métodos que puedan ser necesarios para la clase TRABAJO
}