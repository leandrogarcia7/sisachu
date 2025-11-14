<?php
require_once("GRUPO.php");
require_once("POTREROS.php");
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ESTANCIA
 *
 * @author LGT-5
 */
class ESTANCIA extends GRUPO{
    //put your code here
    private function listarEmpleadosOption() {
    $sql = 'SELECT idemp, nombrecompleto FROM "EMPLEADOS" WHERE estemp = 1 ORDER BY nombrecompleto';
    $res = $this->consulta($sql);
    $options = '';

    while ($r = $this->row($res)) {
        $options .= '<option value="' . $r['idemp'] . '">' . $r['nombrecompleto'] . '</option>';
    }

    return $options;
}

    public function mostrarInicio() {
    echo '<center><h2>🟢 Gestión de Estancia</h2>
    <form method="POST">
    <table border="1" style="width:80%; text-align:left; padding:10px;">
        <tr>
            <th colspan="2">📋 Nueva Estancia</th>
        </tr>
        <tr>
            <td>Grupo:</td>
            <td>
                <select name="idgru" required>
                    <option value="0">Seleccione un grupo</option>
                    ' . $this->listarGruposOption() . '
                </select>
            </td>
        </tr>
        <tr>
            <td>Potrero:</td>
            <td>
                <select name="idsub" required>
                    <option value="0">Seleccione un potrero</option>
                    ' . $this->listarPotrerosOption() . '
                </select>
            </td>
        </tr>
        <tr>
            <td>Responsable:</td>
            <td>
                <select name="responsable" required>
                    <option value="0">Seleccione responsable</option>
                    ' . $this->listarEmpleadosOption() . '
                </select>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <button type="submit" name="bttcrear"><img src="../img/buscar.jpg"><br>CREAR ESTANCIA</button>
            </td>
        </tr>
        <tr>
            <th colspan="2">📆 Filtrar por Fechas</th>
        </tr>
        <tr>
            <td>Inicio:</td>
            <td><input type="date" name="fini" value="' . date("Y-m") . '-01"></td>
        </tr>
        <tr>
            <td>Final:</td>
            <td><input type="date" name="ffin" value="' . date("Y-m-d") . '"></td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <button type="submit" name="bttlista"><img src="../img/notarojo.png"><br>LISTAR ESTANCIA</button>
                <button type="submit" name="bttimpani"><img src="../img/imprimir.jpg"><br>IMPRIMIR ESTANCIA</button>
            </td>
        </tr>
    </table>
    </form></center>';
}

    
  public function crearEstancia($idgru, $idsub, $responsable) {
    // Obtener nombre del potrero
    $sql = 'SELECT nompot FROM "POTREROS" WHERE idpot = :idpot AND idhac = :idhac';
    $stmt = $this->prepare($sql);
    $stmt->bindParam(':idpot', $idsub, PDO::PARAM_INT);
    $stmt->bindParam(':idhac', $_SESSION['idhac'], PDO::PARAM_INT);
    $stmt->execute();
    $potrero = $stmt->fetch();
    $nombrePotrero = $potrero ? $potrero['nompot'] : 'Desconocido';

    // Calcular fecha salida estimada
    $cantidadVacas = $this->listarCantidadAnimalesGrupo($idgru);
    $diasEstimados = ($cantidadVacas > 0) ? round(900 / $cantidadVacas) : 30;
    $fechaEstim = date('Y-m-d', strtotime("+$diasEstimados days"));

    echo '<form method="POST"><center>
        <table border="1">
            <tr><th colspan="2">Crear Estancia</th></tr>
            <tr><td>🐄 Grupo seleccionado:</td><td>' . $this->listarAnimalesGrupoNombres($idgru) . '</td></tr>
            <tr><td>🌾 Potrero seleccionado:</td><td>' . $nombrePotrero . '</td></tr>
                <tr><td>👤 Responsable:</td><td>' . $this->obtenerNombreResponsable($responsable) . '</td></tr>
<input type="hidden" name="responsable" value="' . $responsable . '">

            <tr><td>📅 Fecha ingreso:</td><td><input type="date" name="feciniest" value="' . date("Y-m-d") . '" required></td></tr>
            <tr><td>📅 Fecha salida planificada:</td><td><input type="date" name="fecfinest" value="' . $fechaEstim . '" required> 
                <br><small>Estimada según '.$cantidadVacas.' vacas</small></td></tr>
            <tr><td>📝 Observaciones:</td><td><input type="text" name="detest"></td></tr>
            <tr><td colspan="2" align="center">
                <input type="hidden" name="idgru" value="' . $idgru . '">
                <input type="hidden" name="idsub" value="' . $idsub . '">
                <button type="submit" name="bttcrearest"><img src="../img/guardar.jpg"><br>CREAR ESTANCIA</button>
            </td></tr>
        </table>
        </center></form>';
}
private function obtenerNombreResponsable($idemp) {
    $sql = 'SELECT nombrecompleto FROM "EMPLEADOS" WHERE idemp = :idemp';
    $stmt = $this->prepare($sql);
    $stmt->bindParam(':idemp', $idemp, PDO::PARAM_INT);
    $stmt->execute();
    $res = $stmt->fetch();
    return $res ? $res['nombrecompleto'] : 'Desconocido';
}

public function listarCantidadAnimalesGrupo($idgru) {
    $sql = 'SELECT COUNT(*) AS cantidad 
            FROM "ANIMAL_GRUPO" 
            WHERE idgru = :idgru';

    $stmt = $this->prepare($sql);
    $stmt->bindParam(':idgru', $idgru, PDO::PARAM_INT);
    $stmt->execute();
    $res = $stmt->fetch();
    return $res ? (int)$res['cantidad'] : 0;
}


    
    public function listarPotrerosOption(){
        $pConsulta=' SELECT *  FROM "POTREROS" where estpot=1 order by nompot';
          $con=$this->consulta($pConsulta);
          $op='';
          
          while ($r=$this->row($con)){
              $op.='<option value='.$r['idpot'].'> '.$r['nompot'].'</option> ';
          }
          
          
          return $op;
    }
    
   public function guardarEstancia($idgru, $idsub, $detest, $feciniest, $fecfinest, $responsable) {
    $sql = 'INSERT INTO "ESTANCIA" (
                detest, idgru, idsub, feciniest, fecfinest, responsable, idusu, estest, idhac
            ) VALUES (
                :detest, :idgru, :idsub, :feciniest, :fecfinest, :responsable, :idusu, :estest, :idhac
            )';

    $stmt = $this->prepare($sql);
    $stmt->bindParam(':detest', $detest);
    $stmt->bindParam(':idgru', $idgru, PDO::PARAM_INT);
    $stmt->bindParam(':idsub', $idsub, PDO::PARAM_INT);
    $stmt->bindParam(':feciniest', $feciniest);
    $stmt->bindParam(':fecfinest', $fecfinest);
    $stmt->bindParam(':responsable', $responsable, PDO::PARAM_INT);
    $stmt->bindParam(':idusu', $_SESSION['idusuario'], PDO::PARAM_INT);
    $estadoInicial = 1; // Ocupado
    $stmt->bindParam(':estest', $estadoInicial, PDO::PARAM_INT);
    $stmt->bindParam(':idhac', $_SESSION['idhac'], PDO::PARAM_INT);

    try {
        $stmt->execute();
        echo "<div class='mesajeok'>✅ Estancia registrada correctamente.</div>";
    } catch (PDOException $e) {
        echo "<div class='errores'>❌ Error al crear la estancia: " . $e->getMessage() . "</div>";
    }
}


public function listarEstancia($idgru, $idsub, $fini, $ffin) {
    $sql = 'SELECT 
                e.id, 
                g.detalle AS grupo, 
                p.nompot AS potrero, 
                e.detest, 
                e.feciniest, 
                e.fecfinest, 
                e.fecsalest, 
                e.estest
            FROM "ESTANCIA" e
            JOIN "GRUPO" g ON g.id = e.idgru
            JOIN "POTREROS" p ON p.idpot = e.idsub
            WHERE e.feciniest BETWEEN :fini AND :ffin
              AND e.idhac = :idhac
            ORDER BY e.feciniest DESC';

    $stmt = $this->prepare($sql);
    $stmt->bindParam(':fini', $fini);
    $stmt->bindParam(':ffin', $ffin);
    $stmt->bindParam(':idhac', $_SESSION['idhac'], PDO::PARAM_INT);
    $stmt->execute();

    $estados = [
        0 => ['Registrado', '#f0f0f0'],
        1 => ['Ocupado', '#e0f7ff'],
        2 => ['Pendiente', '#fffac8'],
        3 => ['Terminado', '#d6ffd6']
    ];

    echo "<center><table border='1' class='table'>
            <tr>
                <th>Grupo</th>
                <th>Potrero</th>
                <th>Fecha Ingreso</th>
                <th>Fecha Planificada Salida</th>
                <th>Fecha Salida</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>";

    while ($r = $stmt->fetch()) {
        $estadoTexto = isset($estados[$r['estest']]) ? $estados[$r['estest']][0] : 'Desconocido';
        $color = isset($estados[$r['estest']]) ? $estados[$r['estest']][1] : '#ffffff';

        echo "<tr style='background-color:$color'>
                <td>{$r['grupo']}</td>
                <td>{$r['potrero']}</td>
                <td>{$r['feciniest']}</td>
                <td>" . ($r['fecfinest'] ?? '-') . "</td>
                <td>" . ($r['fecsalest'] ?? '-') . "</td>
                <td>$estadoTexto</td>
                <td>
                    <form method='POST' style='display:inline'>
                        <input type='hidden' name='idest' value='{$r['id']}'>
                        <button name='bttsel' value='{$r['id']}'><img src='../img/modif.jpg'><br>Seleccionar</button>
                        <button name='btteli' value='{$r['id']}' onclick=\"return confirm('¿Está seguro de eliminar esta estancia?');\"><img src='../img/cancelar.jpg'><br>Eliminar</button>
                    </form>
                </td>
              </tr>";
    }

    echo "</table></center>";
}


private function listarGruposOptionSeleccionado($seleccionado) {
    $sql = 'SELECT id, detalle FROM "GRUPO" ORDER BY detalle';
    $res = $this->consulta($sql);
    $html = '';
    while ($r = $this->row($res)) {
        $sel = ($r['id'] == $seleccionado) ? 'selected' : '';
        $html .= "<option value='{$r['id']}' $sel>{$r['detalle']}</option>";
    }
    return $html;
}

private function listarPotrerosOptionSeleccionado($seleccionado) {
    $sql = 'SELECT idpot, nompot FROM "POTREROS" WHERE idhac = ' . $_SESSION['idhac'];
    $res = $this->consulta($sql);
    $html = '';
    while ($r = $this->row($res)) {
        $sel = ($r['idpot'] == $seleccionado) ? 'selected' : '';
        $html .= "<option value='{$r['idpot']}' $sel>{$r['nompot']}</option>";
    }
    return $html;
}

private function listarEmpleadosOptionSeleccionado($seleccionado) {
    $sql = 'SELECT idemp, nombrecompleto FROM "EMPLEADOS" WHERE estemp = 1';
    $res = $this->consulta($sql);
    $html = '';
    while ($r = $this->row($res)) {
        $sel = ($r['idemp'] == $seleccionado) ? 'selected' : '';
        $html .= "<option value='{$r['idemp']}' $sel>{$r['nombrecompleto']}</option>";
    }
    return $html;
}


    
    public function mostrarEstancia($idest) {
    $sql = 'SELECT * FROM "ESTANCIA" WHERE id = :id AND idhac = :idhac';
    $stmt = $this->prepare($sql);
    $stmt->bindParam(':id', $idest, PDO::PARAM_INT);
    $stmt->bindParam(':idhac', $_SESSION['idhac'], PDO::PARAM_INT);
    $stmt->execute();
    $r = $stmt->fetch();

    if (!$r) {
        echo "<div class='errores'>❌ No se encontró la estancia.</div>";
        return;
    }

    $estados = [
        0 => 'Registrado',
        1 => 'Ocupado',
        2 => 'Pendiente',
        3 => 'Terminado'
    ];

    echo '<center><form method="POST"><h3>✏️ Editar Estancia</h3>
    <table border="1" style="width:60%; text-align:left;">';

    echo '<tr><td>Grupo:</td><td>
            <select name="idgru">';
    echo $this->listarGruposOptionSeleccionado($r['idgru']);
    echo '</select></td></tr>';

    echo '<tr><td>Potrero:</td><td>
            <select name="idsub">';
    echo $this->listarPotrerosOptionSeleccionado($r['idsub']);
    echo '</select></td></tr>';

    echo '<tr><td>Responsable:</td><td>
            <select name="responsable">';
    echo $this->listarEmpleadosOptionSeleccionado($r['responsable']);
    echo '</select></td></tr>';

    echo '<tr><td>Fecha Ingreso:</td><td><input type="date" name="feciniest" value="' . $r['feciniest'] . '"></td></tr>';
    echo '<tr><td>Fecha Salida Planificada:</td><td><input type="date" name="fecfinest" value="' . $r['fecfinest'] . '"></td></tr>';
    echo '<tr><td>Fecha Salida Real:</td><td><input type="date" name="fecsalest" value="' . $r['fecsalest'] . '"></td></tr>';
    echo '<tr><td>Detalle:</td><td><input type="text" name="detest" value="' . $r['detest'] . '"></td></tr>';

    echo '<tr><td>Estado:</td><td>
            <select name="estest">';
    foreach ($estados as $k => $v) {
        $sel = ($r['estest'] == $k) ? 'selected' : '';
        echo "<option value='$k' $sel>$v</option>";
    }
    echo '</select></td></tr>';

    echo '<tr><td colspan="2" align="center">
            <input type="hidden" name="idest" value="' . $r['id'] . '">
            <button type="submit" name="bttmodest"><img src="../img/guardar.jpg"><br>GUARDAR CAMBIOS</button>
          </td></tr>
    </table></form></center>';
}
public function modificarEstancia($idest, $idgru, $idsub, $responsable, $feciniest, $fecfinest, $fecsalest, $detest, $estest) {
    $sql = 'UPDATE "ESTANCIA"
            SET idgru = :idgru,
                idsub = :idsub,
                responsable = :responsable,
                feciniest = :feciniest,
                fecfinest = :fecfinest,
                fecsalest = :fecsalest,
                detest = :detest,
                estest = :estest
            WHERE id = :idest AND idhac = :idhac';

    $stmt = $this->prepare($sql);
    $stmt->bindParam(':idgru', $idgru, PDO::PARAM_INT);
    $stmt->bindParam(':idsub', $idsub, PDO::PARAM_INT);
    $stmt->bindParam(':responsable', $responsable, PDO::PARAM_INT);
    $stmt->bindParam(':feciniest', $feciniest);
    $stmt->bindParam(':fecfinest', $fecfinest);
    $stmt->bindParam(':fecsalest', $fecsalest);
    $stmt->bindParam(':detest', $detest);
    $stmt->bindParam(':estest', $estest, PDO::PARAM_INT);
    $stmt->bindParam(':idest', $idest, PDO::PARAM_INT);
    $stmt->bindParam(':idhac', $_SESSION['idhac'], PDO::PARAM_INT);

    try {
        $stmt->execute();
        echo "<div class='ok'>✅ Estancia modificada correctamente.</div>";
    } catch (PDOException $e) {
        echo "<div class='errores'>❌ Error al modificar la estancia: " . $e->getMessage() . "</div>";
    }
}

public function mostrarEstanciasActual($idhac) {
    $fechaInicio = date('Y-m-d', strtotime('-2 months'));
    $hoy = date('Y-m-d');

    $sql = 'SELECT 
                e.id, 
                g.id AS idgru,
                g.detalle AS grupo, 
                p.idpot,
                p.nompot AS potrero, 
                e.feciniest, 
                e.fecfinest, 
                e.fecsalest, 
                e.estest
            FROM "ESTANCIA" e
            JOIN "GRUPO" g ON g.id = e.idgru
            JOIN "POTREROS" p ON p.idpot = e.idsub
            WHERE e.feciniest >= :fechaInicio
              AND e.idhac = :idhac
            ORDER BY (e.fecsalest IS NOT NULL), e.estest ASC, e.feciniest DESC';

    $stmt = $this->prepare($sql);
    $stmt->bindParam(':fechaInicio', $fechaInicio);
    $stmt->bindParam(':idhac', $idhac, PDO::PARAM_INT);
    $stmt->execute();

    $estados = [
        0 => ['Registrado', '#f0f0f0'],
        1 => ['Ocupado', '#e0f7ff'],
        2 => ['Pendiente', '#fffac8'],
        3 => ['Terminado', '#d6ffd6']
    ];

    echo '<div class="container mt-4">
            <div class="text-center mb-3">
                <button class="btn btn-primary" type="button" onclick="document.getElementById(\'formCrear\').classList.toggle(\'d-none\')">
                    ➕ Registrar Nueva Estancia
                </button>
            </div>

            <div id="formCrear" class="card shadow-lg mb-4 d-none">
                <div class="card-header bg-success text-white text-center">
                    <h5>Crear Estancia</h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="row g-3">
                            <div class="col-md-5">
                                <select name="idgru" class="form-select" required>
                                    <option value="">-- Grupo --</option>
                                    ' . $this->listarGruposOption() . '
                                </select>
                            </div>
                            <div class="col-md-5">
                                <select name="idsub" class="form-select" required>
                                    <option value="">-- Potrero --</option>
                                    ' . $this->listarPotrerosOption() . '
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="feciniest" class="form-control" value="' . date('Y-m-d') . '" required>
                            </div>
                        </div>
                        <input type="hidden" name="responsable" value="NULL">
                        <div class="text-center mt-3">
                            <button type="submit" name="bttcrearest" class="btn btn-success">💾 Guardar Estancia</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card shadow-lg">
                <div class="card-header bg-info text-white text-center">
                    <h3>Estancias de los Últimos 2 Meses</h3>
                </div>
                <div class="card-body">';

    while ($r = $stmt->fetch()) {
        $estadoTexto = isset($estados[$r['estest']]) ? $estados[$r['estest']][0] : 'Desconocido';
        $color = isset($estados[$r['estest']]) ? $estados[$r['estest']][1] : '#ffffff';

        echo '<div class="card mb-3" style="background-color:' . $color . '">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h5>🐄 Grupo: ' . $r['grupo'] . '</h5>
                            <p>🌾 Potrero: ' . $r['potrero'] . '</p>
                            <p>📅 Ingreso: ' . $r['feciniest'] . ' / Planificada: ' . ($r['fecfinest'] ?? '-') . '</p>
                            <p>🚪 Salida: ' . ($r['fecsalest'] ?? '-') . '</p>
                        </div>
                        <div class="col-md-4 text-center">
                            <p class="fw-bold">Estado: ' . $estadoTexto . '</p>';

        if ($r['fecsalest'] === null) {
            echo '<form method="POST">
                    <input type="hidden" name="idest" value="' . $r['id'] . '">
                    <button type="submit" name="bttsalidaEstancia" class="btn btn-danger">🚪 Registrar Salida</button>
                  </form>';
        }

        echo '      </div>
                    </div>
                </div>
              </div>';
    }

    echo '   </div>
            </div>
        </div>';
}

public function guardarEstanciaMobile($idgru, $idsub, $feciniest) {
    $idhac = $_SESSION['idhac'];
    $idusu = $_SESSION['idusuario'];

    // 1. Verificar si el grupo ya está en una estancia sin terminar
    $sqlVerificar = 'SELECT id FROM "ESTANCIA"
                    WHERE idgru = :idgru AND fecsalest IS NULL AND idhac = :idhac AND estest < 3';
    $stmtVerif = $this->prepare($sqlVerificar);
    $stmtVerif->bindParam(':idgru', $idgru, PDO::PARAM_INT);
    $stmtVerif->bindParam(':idhac', $idhac, PDO::PARAM_INT);
    $stmtVerif->execute();

    if ($row = $stmtVerif->fetch()) {
        $idestAnterior = $row['id'];
        $sqlUpdate = 'UPDATE "ESTANCIA" SET fecsalest = :feciniest, estest = 3 WHERE id = :idest AND idhac = :idhac';
        $stmtUp = $this->prepare($sqlUpdate);
        $stmtUp->bindParam(':feciniest', $feciniest);
        $stmtUp->bindParam(':idest', $idestAnterior, PDO::PARAM_INT);
        $stmtUp->bindParam(':idhac', $idhac, PDO::PARAM_INT);
        $stmtUp->execute();
    }

    // 2. Calcular fecha estimada de salida
    $sqlContar = 'SELECT COUNT(*) AS vacas FROM "ANIMAL_GRUPO" WHERE idgru = :idgru';
    $stmtContar = $this->prepare($sqlContar);
    $stmtContar->bindParam(':idgru', $idgru, PDO::PARAM_INT);
    $stmtContar->execute();
    $rowContar = $stmtContar->fetch();
    $vacas = $rowContar ? (int)$rowContar['vacas'] : 0;
    $dias = ($vacas > 0) ? round(900 / $vacas) : 30;
    $fecfinest = date('Y-m-d', strtotime($feciniest . "+$dias days"));

    // 3. Insertar nueva estancia
    $sqlInsert = 'INSERT INTO "ESTANCIA" (
                    detest, idgru, idsub, feciniest, fecfinest, responsable, idusu, estest, idhac
                  ) VALUES (
                    NULL, :idgru, :idsub, :feciniest, :fecfinest, NULL, :idusu, 1, :idhac)';

    $stmtInsert = $this->prepare($sqlInsert);
    $stmtInsert->bindParam(':idgru', $idgru, PDO::PARAM_INT);
    $stmtInsert->bindParam(':idsub', $idsub, PDO::PARAM_INT);
    $stmtInsert->bindParam(':feciniest', $feciniest);
    $stmtInsert->bindParam(':fecfinest', $fecfinest);
    $stmtInsert->bindParam(':idusu', $idusu, PDO::PARAM_INT);
    $stmtInsert->bindParam(':idhac', $idhac, PDO::PARAM_INT);

    try {
        $stmtInsert->execute();
        echo "<div class='ok'>✅ Estancia registrada correctamente.</div>";
    } catch (PDOException $e) {
        echo "<div class='errores'>❌ Error al guardar la estancia: " . $e->getMessage() . "</div>";
    }
}

public function registrarSalidaEstancia($idest) {
    $idhac = $_SESSION['idhac'];
    $fechaSalida = date('Y-m-d');

    $sql = 'UPDATE "ESTANCIA"
            SET fecsalest = :fecsalest, estest = 3
            WHERE id = :idest AND idhac = :idhac';

    $stmt = $this->prepare($sql);
    $stmt->bindParam(':fecsalest', $fechaSalida);
    $stmt->bindParam(':idest', $idest, PDO::PARAM_INT);
    $stmt->bindParam(':idhac', $idhac, PDO::PARAM_INT);

    try {
        $stmt->execute();
        echo "<div class='ok'>✅ Salida registrada correctamente.</div>";
    } catch (PDOException $e) {
        echo "<div class='errores'>❌ Error al registrar salida: " . $e->getMessage() . "</div>";
    }
}
         }
