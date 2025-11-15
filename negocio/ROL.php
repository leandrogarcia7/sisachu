<?php
require_once("EMPLEADOS.php");
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ROL
 *
 * @author LGT-5
 */
class ROL extends EMPLEADOS {
    //put your code here
  public $meses = array('',
    'Enero', 'Febrero', 'Marzo', 'Abril', 
    'Mayo', 'Junio', 'Julio', 'Agosto', 
    'Septiembre', 'Octubre', 'Noviembre', 'Diciembre', 'Decimo Tercero','Decimo Cuarto','Vacaciones'
);
   public function mostrarInicio2(){
         echo '<center>
             <form>
             <b>Buscar por apellido: </b> <input type=text placeholder=GARCIA name=txtbuscar >
             <button type="submit" name="bttbuscar" > <img src="../img/buscar.jpg" alt=""/>  <BR>BUSCAR EMPLEADO</button>
          
             <button type="submit" name="bttresumen" > <img src="../img/cuadernorojo.png" alt=""/>  <BR>RESUMEN EMPLEADO</button>
           </form>  </center>';
         
     }   
     
     public function mostrarInicio(){
        // Obtiene el año actual
        $anioActual = date("Y");

        echo '<center>
            <form>
            <b>Buscar por apellido: </b> <input type="text" placeholder="GARCIA" name="txtbuscar">
            <button type="submit" name="bttbuscar"> <img src="../img/buscar.jpg" alt=""/>  <BR>BUSCAR EMPLEADO</button>

            <b>Mes: </b>
            <select name="mes">';
            // Bucle para mostrar todos los meses
            foreach($this->meses as $key => $mes) {
                echo '<option value="'.$key.'">'.$mes.'</option>';
            }
        echo '</select>

            <b>Año: </b>
            <select name="anio">';
            // Bucle para mostrar el año actual y 5 años anteriores
            for($i = 0; $i < 6; $i++) {
                echo '<option value="'.($anioActual - $i).'">'.($anioActual - $i).'</option>';
            }
        echo '</select>
            <button type="submit" name="bttresumen"> <img src="../img/cuadernorojo.png" alt=""/>  <BR>RESUMEN ROLES</button>
            <button type="submit" name="bttresumenanio"> <img src="../img/cuadernorojo.png" alt=""/>  <BR>RESUMEN AÑO</button>
        </form>  </center>';
    }
     
         public function buscar($txtbuscar){
         
          echo '   <center>
        <table border="1" class=table style="width:50%">
            <tr>
                <th>Id</th><th>Detalle</th><th>Acción</th>
            </tr>';
    

      //   $fotos= $ani->mostrarFotosAnimal($a['id'],300);
        
        
        
         $con=$this->consulta('select * from "EMPLEADOS" where apellido ilike \''.addslashes($txtbuscar).'%\' and idhac='.$_SESSION['idhac'].' order by apellido');
    

        while($a=$this->row($con)){
                    
        echo '<tr><form>
                <td><h2>'.$a['idemp'].'</h2></td><td>'.$a['apellido'].' '.$a['nombre'].'</td><td> Año: <input type="number" style="width: 70px;" name="anio" value="' . date("Y") .'" ><br>';
             echo "Mes:<select name='mes'>";
        for ($i = 1; $i <= 15; $i++) {
            echo "<option value='" . $i . "'>" .$this->meses[$i]. "</option>";
        }     
echo '<td><button name=bttcrear value='.$a['idemp'].'> <img src=../img/modif.jpg  > <br>Crear Rol</button></td>
                    <td><button name=bttlistar value='.$a['idemp'].'><img src=../img/cambiar.jpg  > <br>Listar Roles</button></td>
           </form> </tr>';
        
        
         
       
    }
    
    echo '</table>
        
    </center>';
    }
    
    public function CrearRol($idemp, $anio, $mes, $fecha = null) {
     
    $sql = "SELECT * FROM \"ROL\" WHERE idemp = $idemp AND mes = $mes AND anio = $anio";
    $rol2 = $this->consulta($sql);

    $sql = "INSERT INTO \"ROL\" (idemp, mes, anio) VALUES ($idemp, $mes, $anio)";
    if (!$this->consulta($sql)) {
        echo "Error ingreso de datos ".$sql;
    }

    // Obtener el nuevo rol creado
    $sql = "SELECT * FROM \"ROL\" WHERE idemp = $idemp AND mes = $mes AND anio = $anio";
    $rol2 = $this->consulta($sql); 
    $r = $this->row($rol2);

    $con = $this->consulta('select * from "EMPLEADOS" where idemp=' . $idemp);

    if ($a = $this->row($con)) {
        if (is_null($fecha)) {
            $fecha = date('Y-m-d');
        }

        $baseSql = "INSERT INTO \"ROL_DETALLE\" (idrol, descripcion, tipo, monto, fecha, idcat) VALUES";

        $sql = "INSERT INTO \"ROL_DETALLE\" (idrol, descripcion, tipo, monto, fecha,idcat) VALUES (".$r['id'].",'Sueldo Base','I',".$a['salario_base'].",'".$fecha."',15)";
        
        $fieldsToCheck = [
            'horassuple' => [1, 'Horas suplementarias'],
            'horasextras' => [2, 'Horas extras'],
            'dec3' => [3, 'Decimo Tercer Sueldo'],
            'dec4' => [4, 'Decimo Cuarto Sueldo'],
            'iessfond' => [5, 'Fondos de reserva'],
            'iesspat' => [6, 'Aporte Patronal al IESS'],
            'iessemp' => [7, 'Aporte del Empleado al IESS']
        ];

foreach ($fieldsToCheck as $field => $info) {
    if ($a[$field] != 0) {
        if ($field == 'iessemp') {
            $tipo = 'E';
        } else {
                if ($field == 'iesspat') {
            $tipo = 'A';
        } else {
            
            // Determinar el campo modificador (m*)
            $modifierField = 'm' . $field;
            $tipo = isset($a[$modifierField]) && $a[$modifierField] == 2 ? 'A' : 'I';
        }
        
        }
        $sql .= ";" . $baseSql . "('{$r['id']}','{$info[1]}','{$tipo}',{$a[$field]},'{$fecha}',{$info[0]})";
    }
}



        if (!$this->consulta($sql)) {
            echo "Error ingreso de datos " . $sql;
        }
    }

    return $rol2;   
}


public function modificarRol($datos) {
    // Comprobar que el idrol está presente antes de seguir adelante
    if (!isset($datos['idrol'])) {
        echo "Error: ID del rol no especificado.";
        return false;
    }

    $updateFields = []; // para almacenar fragmentos de la consulta SQL

    // Validar y agregar la fecha de rol si está presente
    if (isset($datos['fecrol']) && $this->validateDate($datos['fecrol'])) {
        $updateFields[] = "fecrol = '{$datos['fecrol']}'";
    }

    // Validar y agregar la fecha de pago si está presente
    if (isset($datos['fecpago']) && $this->validateDate($datos['fecpago'])) {
        $updateFields[] = "fecpago = '{$datos['fecpago']}'";
    }

    // Añadir obsrol si está presente
    if (isset($datos['obsrol'])) {
        $obsrolCleaned = pg_escape_string($datos['obsrol']);
        $updateFields[] = "obsrol = '{$obsrolCleaned}'";
    }

    if (empty($updateFields)) {
        echo "No hay campos válidos para actualizar.";
        return false;
    }

    // Construir la consulta SQL
    $sql = 'UPDATE "ROL" SET ' . implode(', ', $updateFields) . " WHERE id = '{$datos['idrol']}'";

    if ($stmt = $this->consulta($sql)) {
        return true;
    } else {
        echo "Error de modificación de datos: " . $sql;
        return false;
    }
}

// Función auxiliar para validar fechas
private function validateDate($date, $format = 'Y-m-d') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}


         public function mostrarRol($idrol){
               $sql = "SELECT * FROM \"ROL\",\"EMPLEADOS\" WHERE \"EMPLEADOS\".idemp=\"ROL\".idemp AND  \"ROL\".id= $idrol";
        $rol2 = $this->consulta($sql); 
  // echo $sql;     
     
 while($rol =$this->row($rol2)) {
 // Mostrar el formulario con los datos del rol
     $idrol=$rol['id'];

      
     echo "<center>";
echo "<form  method='post'>";
echo "<table border='1' style='width: 50%;'>";

echo "<tr><td colspan='2' style='text-align:center;'>";
echo "<label for='idemp'>ID Empleado:</label>";
echo "{$rol['nombre']} {$rol['apellido']}";
echo "</td></tr>";
echo "<input type=hidden name=idemp value={$rol['idemp']}>";
echo "<tr><td>";
echo "<label for='anio'>Año:</label>";
echo "{$rol['anio']}";
echo "<input type=hidden name=anio value={$rol['anio']}>";
echo "</td><td>";
echo "<label for='mes'>Mes:</label>";
echo " ".$this->meses[$i]. "";
echo "</td></tr>";
echo "<input type=hidden name=mes value={$rol['mes']}>";
echo "<tr><td>";
echo "<label for='fecrol'>Fecha Rol:</label>";
echo "<input type='date' id='fecrol' name='fecrol' value='{$rol['fecrol']}'>";
echo "</td><td>";
echo "<label for='tingresos'>Total Ingresos:</label>";
echo "{$rol['tingresos']}";
echo "</td></tr>";

echo "<tr><td>";
echo "<label for='fecpago'>Fecha Pago:</label>";
echo "<input type='date' id='fecpago' name='fecpago' value='{$rol['fecpago']}'>";
echo "</td><td>";
echo "<label for='tegresos'>Total Egresos:</label>";
echo "{$rol['tegresos']}";
echo "</td></tr>";

echo "<tr><td >";
echo "<label for='obsrol'>Observaciones Rol:</label>";
echo "<input type='text' id='obsrol' name='obsrol' maxlength='250' value='{$rol['obsrol']}'>";
echo "</td><td>";
echo "<label for='tahorro'>Total Ahorro:</label>";
echo "{$rol['tahorro']}";

echo "</td></tr>";

echo "<tr><td colspan='2'>";
echo "<label for='trecibir'>Total a Recibir:</label>";
echo "{$rol['trecibir']}";
echo "</td></tr>";

echo "<tr><td colspan='2' style='text-align:center;'>";
echo "<input type='submit' name='bttact' value=Modificar><input type='submit' name='pdfRol' value=Imprimir>";
echo "</td></tr>";

echo "</table><input type=hidden name=idrol value=".$idrol.">";
echo "</form>";
echo "</center><br><br>";

     
 }     
  $this->mostrarRolDetalle($idrol);
 
         }
    
    
    public function mostrarCrearRol($idemp,$anio,$mes){
    
        $sql = "SELECT * FROM \"ROL\" WHERE idemp = $idemp AND mes = $mes AND anio = $anio";
        $rol2 = $this->consulta($sql);
//echo $sql;
        // Si no existe, crearlo
        if (!$rol =$this->row($rol2)) {
          $rol2=  $this->CrearRol($idemp, $anio, $mes);
        }
        
       $sql = "SELECT * FROM \"ROL\",\"EMPLEADOS\" WHERE \"EMPLEADOS\".idemp=\"ROL\".idemp AND   \"EMPLEADOS\".idemp = $idemp AND mes = $mes AND anio = $anio";
        $rol2 = $this->consulta($sql); 
  // echo $sql;     
        $idrol=0;
 while($rol =$this->row($rol2)) {
 // Mostrar el formulario con los datos del rol
     $idrol=$rol['id'];

      
     echo "<center>";
echo "<form >";
echo "<table border='1' style='width: 50%;'>";

echo "<tr><td colspan='2' style='text-align:center;'>";
echo "<label for='idemp'>ID Empleado:</label>";
echo "{$rol['nombre']} {$rol['apellido']}";
echo "</td></tr>";
echo "<input type=hidden name=idemp value={$rol['idemp']}>";
echo "<tr><td>";
echo "<label for='anio'>Año:</label>";
echo "{$rol['anio']}";
echo "<input type=hidden name=anio value={$rol['anio']}>";
echo "</td><td>";
echo "<label for='mes'>Mes:</label>";
echo " ".$this->meses[$i]. "";
echo "</td></tr>";
echo "<input type=hidden name=mes value={$rol['mes']}>";
echo "<tr><td>";
echo "<label for='fecrol'>Fecha Rol:</label>";
echo "<input type='date' id='fecrol' name='fecrol' value='{$rol['fecrol']}'>";
echo "</td><td>";
echo "<label for='tingresos'>Total Ingresos:</label>";
echo "{$rol['tingresos']}";
echo "</td></tr>";

echo "<tr><td>";
echo "<label for='fecpago'>Fecha Pago:</label>";
echo "<input type='date' id='fecpago' name='fecpago' value='{$rol['fecpago']}'>";
echo "</td><td>";
echo "<label for='tegresos'>Total Egresos:</label>";
echo "{$rol['tegresos']}";
echo "</td></tr>";

echo "<tr><td >";
echo "<label for='obsrol'>Observaciones Rol:</label>";
echo "<input type='text' id='obsrol' name='obsrol' maxlength='250' value='{$rol['obsrol']}'>";
echo "</td><td>";
echo "<label for='tahorro'>Total Ahorro:</label>";
echo "{$rol['tahorro']}";

echo "</td></tr>";

echo "<tr><td colspan='2'>";
echo "<label for='trecibir'>Total a Recibir:</label>";
echo "{$rol['trecibir']}";
echo "</td></tr>";

echo "<tr><td colspan='2' style='text-align:center;'>";
echo "<input type='submit' name='bttact' value=Modificar><input type='submit' name='pdfRol' value=Imprimir>";
echo "</td></tr>";

echo "</table><input type=hidden name=idrol value=".$idrol.">";
echo "</form>";
echo "</center><br><br>";

     
 }     
  $this->mostrarRolDetalle($idrol);
 
 
    }     
    
    
    public function mostrarRolDetalle($idrol){
    $stmt = $this->consulta('SELECT * FROM "ROL_DETALLE" WHERE idrol = '.$idrol.' ;');

    // Display the details
    echo "<table border='1' class='table table-striped '>";
    echo "<tr><th>ID Detalle</th><th>ID Rol</th><th>Descripción</th><th>Tipo</th><th>Monto</th><th>Fecha</th><th>Categoría</th><th>Acción</th></tr>";

    // Fila para crear nuevo registro
    echo "<form><tr>";
    echo "<td colspan=2><button name='bttcrearDetalle'>Crear</button></td>";
  
    echo "<td><input type='text' name='descripcion_nuevo' placeholder='Descripción'></td>";
    echo "<td><select name='tipo_nuevo'>";
    echo "<option value='I'>Ingresos</option>";
    echo "<option value='E'>Egresos</option>";
    echo "<option value='A'>Ahorro</option>";
    echo "</select></td>";
    echo "<td><input type='text' name='monto_nuevo' placeholder='Monto'></td>";
    echo "<td><input type='date' name='fecha_nuevo' placeholder='Fecha'></td>";
    
    // Asumo que ya tienes un método para obtener todas las categorías de ROL_CATEGORIA
    $categorias = $this->getAllCategorias(); 
    echo "<td><select name='idcat_nuevo'>";
    foreach($categorias as $cat) {
        echo "<option value='".$cat['idcat']."'>".$cat['detcar']."</option>";
    }
    echo "</select></td>";
    echo "<td><input type=hidden name=idrol value=$idrol>  </td>";
    echo "</tr></form>";

    while($detalle = $this->row($stmt)) {
        echo "<form><tr>";
        echo "<td>" . $detalle['idroldet'] . "</td>";
        echo "<td>" . $detalle['idrol'] . "</td>";
        echo "<td>" . $detalle['descripcion'] . "</td>";
        echo "<td>" . $detalle['tipo'] . "</td>";
        echo "<td>" . $detalle['monto'] . "</td>";
        echo "<td>" . $detalle['fecha'] . "</td>";
        echo "<td>" . $detalle['idcat'] . "</td>"; // Si necesitas mostrar el nombre de la categoría, se debe realizar una consulta adicional.
        echo "<td><button name='bttEliminarDetalle' value='".$detalle['idroldet']."'>Eliminar</button></td>";
        echo "<input type=hidden name=idrol value=$idrol> ";
        echo "</tr></form>";
    }

    echo "</table>";  
}

public function getAllCategorias() {
    $stmt = $this->consulta('SELECT * FROM "ROL_CATEGORIA";');
    $categorias = [];
    while($cat = $this->row($stmt)) {
        $categorias[] = $cat;
    }
    return $categorias;
}

    public function eliminarDetalleRol($idrolder) {
    // Preparar el idrolder para evitar inyecciones SQL
    $idrolderCleaned = pg_escape_string($idrolder);

    // Construir la consulta SQL para eliminar el detalle del rol
    $sql = 'DELETE FROM "ROL_DETALLE" WHERE idroldet = \'' . $idrolderCleaned . '\'';

    // Ejecutar la consulta SQL
    if ($stmt = $this->consulta($sql)) {
        return true;
    } else {
        echo "Error al eliminar el detalle del rol: " . $sql;
        return false;
    }
}

 public function crearRolDetalle($datos) {
    // Desinfecta los datos para evitar inyecciones SQL
    $descripcion = pg_escape_string($datos['descripcion_nuevo']);
    $tipo = pg_escape_string($datos['tipo_nuevo']);
    $monto = floatval($datos['monto_nuevo']);  // Asegúrate de manejar correctamente los valores decimales
    $fecha = pg_escape_string($datos['fecha_nuevo']);
    $idcat = intval($datos['idcat_nuevo']);
    $idrol = intval($datos['idrol']);

    // Construir la consulta SQL para insertar el detalle del rol
    $sql = 'INSERT INTO "ROL_DETALLE" (idrol, descripcion, tipo, monto, fecha, idcat) VALUES (' . 
           "'$idrol', '$descripcion', '$tipo', '$monto', '$fecha', '$idcat')";

    // Ejecutar la consulta SQL
    if ($stmt = $this->consulta($sql)) {
        return true;
    } else {
        echo "Error al crear el detalle del rol: " . $sql;
        return false;
    }
}
public function imprimirRol2($idrol, $pdf) {
    // Obtiene los datos
    $empleado = $this->obtenerEmpleadoPorIdRol($idrol);
    $rolDetalles = $this->obtenerRolDetalles($idrol);
    $datosRol = $this->obtenerDatosRol($idrol);

    // Comienza a construir el PDF
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(190, 10, 'ROL DE PAGOS', 0, 1, 'C');

    // Información del empleado
    $pdf->SetFont('Arial', '', 12);
    $pdf->Ln(10);
    $pdf->Cell(50, 8, 'NOMBRE:', 0, 0);
    $pdf->Cell(140, 8, $empleado['nombre'] . " " . $empleado['apellido'], 0, 1);
    $pdf->Cell(50, 8, 'C.I. No:', 0, 0);
    $pdf->Cell(140, 8, $empleado['cedula_identidad'], 0, 1);
    // Puedes agregar las fechas aquí

    // Categorización de INGRESOS y EGRESOS/AHORRO
    $ingresos = [];
    $egresosYahorros = [];

    foreach ($rolDetalles as $detalle) {
        if ($detalle['tipo'] == 'I') {
            $ingresos[] = $detalle;
        } else {
            $egresosYahorros[] = $detalle;
        }
    }

    $pdf->Ln(10);
    $pdf->Cell(95, 8, 'INGRESOS', 0, 0, 'C');
    $pdf->Cell(95, 8, 'EGRESOS', 0, 1, 'C');

    // Poner los detalles en el PDF
    $lineHeight = 8;
    $maxRows = max(count($ingresos), count($egresosYahorros));

    for ($i = 0; $i < $maxRows; $i++) {
        if (isset($ingresos[$i])) {
            $pdf->Cell(45, $lineHeight, $ingresos[$i]['descripcion'] . ":", 0, 0);
            $pdf->Cell(50, $lineHeight, number_format($ingresos[$i]['monto'], 2), 0, 0, 'R');
        } else {
            $pdf->Cell(95, $lineHeight, '', 0, 0);
        }

        if (isset($egresosYahorros[$i])) {
            $pdf->Cell(45, $lineHeight, $egresosYahorros[$i]['descripcion'] . ":", 0, 0);
            $pdf->Cell(50, $lineHeight, number_format($egresosYahorros[$i]['monto'], 2), 0, 1, 'R');
        } else {
            $pdf->Cell(95, $lineHeight, '', 0, 1);
        }
    }

    // Aquí puedes agregar los totales y cualquier otro detalle

    $pdf->Output();
}

public function imprimirRol($idrol, $pdf) {
    // Obtiene los datos
    $empleado = $this->obtenerEmpleadoPorIdRol($idrol);
    $rolDetalles = $this->obtenerRolDetalles($idrol);
    $datosRol = $this->obtenerDatosRol($idrol);

    // Comienza a construir el PDF
    $pdf->AddPage();
    
   for ($fg = 0; $fg < 2; $fg++) {
    
    
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(190, 10, 'ROL DE PAGOS', 0, 1, 'C');

    // Información del empleado
    $pdf->SetFont('Arial', '', 12);
   // $pdf->Ln(5);
    $pdf->Cell(50, 5, 'NOMBRE:', 0, 0);
    $pdf->Cell(140, 5, $empleado['nombre'] . " " . $empleado['apellido'], 0, 1);
    $pdf->Cell(50, 5, 'C.I. No:', 0, 0);
    $pdf->Cell(140, 5, $empleado['cedula_identidad'], 0, 1);
    
    $fecpago = $datosRol['fecpago'];
// Obtener el nombre del mes basado en el número

//$pdf->Ln(5);
$pdf->Cell(95, 5, 'FECHA: ' . $fecpago, 0, 0);
$pdf->Cell(95, 5, 'MES: ' .$this->meses[$datosRol['mes']]. ' - ' . $datosRol['anio'], 0, 1, 'R');

    // Categorización de INGRESOS y EGRESOS/AHORRO
    $ingresos = [];
    $egresos = [];
    $ahorros = [];

    foreach ($rolDetalles as $detalle) {
        if ($detalle['tipo'] == 'I') {
            $ingresos[] = $detalle;
        } elseif ($detalle['tipo'] == 'E') {
            $egresos[] = $detalle;
        } else {
            $ahorros[] = $detalle;
        }
    }
 $pdf->SetFont('Arial', 'B', 12);
    $pdf->Ln(4);
    $pdf->Cell(95, 8, 'INGRESOS', 0, 0, 'C');
    $pdf->Cell(95, 8, 'EGRESOS', 0, 1, 'C');
 $pdf->SetFont('Arial', '', 12);
    // Poner los detalles en el PDF
    $lineHeight = 5;
    $maxRows = max(count($ingresos), count($egresos), count($ahorros));

 
$i_ingresos = 0;
$i_egresos = 0;
$i_ahorros = 0;

while ($i_ingresos < count($ingresos) || $i_egresos < count($egresos) || $i_ahorros < count($ahorros)) {
    // Columna Izquierda (Ingresos o Ahorros)
    if ($i_ingresos < count($ingresos)) {
        $pdf->Cell(75, $lineHeight, $ingresos[$i_ingresos]['descripcion'] . ":", 0, 0);
        $pdf->Cell(20, $lineHeight, "$ ".number_format($ingresos[$i_ingresos]['monto'], 2), 1, 0, 'R');
        $i_ingresos++;
    } else if ($i_ahorros < count($ahorros)) {
        $pdf->Cell(75, $lineHeight, $ahorros[$i_ahorros]['descripcion'] . "(A):", 0, 0);
        $pdf->Cell(20, $lineHeight, "$ ".number_format($ahorros[$i_ahorros]['monto'], 2), 1, 0, 'R');
        $i_ahorros++;
    } else {
        $pdf->Cell(95, $lineHeight, '', 0, 0);
    }

    // Columna Derecha (Egresos)
    if ($i_egresos < count($egresos)) {
        $pdf->Cell(75, $lineHeight, $egresos[$i_egresos]['descripcion'] . ":", 0, 0);
        $pdf->Cell(20, $lineHeight, "$ ".number_format($egresos[$i_egresos]['monto'], 2), 1, 1, 'R');
        $i_egresos++;
    } else {
        $pdf->Cell(95, $lineHeight, '', 0, 1);
    }
}


     $pdf->SetFont('Arial', 'B', 12);
    // Aquí puedes agregar los totales y cualquier otro detalle
    $pdf->Ln(5);
    $pdf->Cell(47.5, 5, 'TOTAL PARCIAL:', 0, 0, 'R');
    $pdf->Cell(47.5, 5, '$ ' .number_format(($datosRol['tingresos']+$datosRol['tahorro']), 2), 1, 1, 'R');

    $pdf->Cell(47.5, 5, 'TOTAL INGRESOS:', 0, 0, 'R');
    $pdf->Cell(47.5, 5, '$ ' . number_format($datosRol['tingresos'], 2), 1, 0, 'R');
    $pdf->Cell(47.5, 5, 'TOTAL EGRESOS:', 0, 0, 'R');
    $pdf->Cell(47.5, 5, '$ ' . number_format($datosRol['tegresos'], 2), 1, 1, 'R');

   // $pdf->Ln(5);
    $pdf->Cell(95,5, 'LIQUIDO A RECIBIR:', 0, 0, 'R');
    $pdf->Cell(95, 5, '$ ' . number_format($datosRol['trecibir'], 2), 1, 1, 'R');
    
    $pdf->Ln(18);
    $pdf->Cell(190, 5, 'RECIBIDO POR', 0, 1, 'C');
    $pdf->Cell(190, 5, 'CI: ' . $empleado['cedula_identidad'], 0, 1, 'C');
   
    $pdf->SetXY(0, 148);
    $pdf->Ln(2);
    
}
  
    
    $pdf->Output('D','Rol'.$empleado['cedula_identidad'].'-'.$datosRol['anio'].'-'.$datosRol['mes'].'.pdf');
}



private function obtenerEmpleadoPorIdRol($idrol) {
        // Asumiendo que tienes un método para conectarte a la DB
      

        $sql = "SELECT 
                    E.nombre, E.apellido, E.cedula_identidad
                FROM \"ROL\" R 
                JOIN \"EMPLEADOS\" E ON R.idemp = E.idemp
                WHERE R.id = $idrol";
        
        $result = $this->consulta($sql);
        return pg_fetch_assoc($result);
    }

    private function obtenerRolDetalles($idrol) {
       

        $sql = "SELECT 
                    RD.descripcion, RD.tipo, RD.monto
                FROM \"ROL_DETALLE\" RD
                WHERE RD.idrol = $idrol";

        $result =  $this->consulta($sql);
        return pg_fetch_all($result);
    }

    private function obtenerDatosRol($idrol) {
       
        $sql = "SELECT * FROM \"ROL\" WHERE id = $idrol";

        $result =  $this->consulta($sql);
        return pg_fetch_assoc($result);
    }
 public function mostrarRoles($mes, $anio) {
        // Construimos la consulta SQL
        $sql = "SELECT id,e.cedula_identidad, e.nombrecompleto, r.mes, r.anio, r.tingresos, r.tegresos, r.tahorro, r.trecibir, r.fecpago 
                FROM \"ROL\" r 
                JOIN \"EMPLEADOS\" e ON r.idemp = e.idemp 
                WHERE r.mes = $mes AND r.anio = $anio";

        // Ejecutamos la consulta
        $result = $this->consulta($sql);

        // Mostramos el inicio de la tabla
       //  border='1' class="table table-striped "
        echo '<center><table border="1" class="table table-striped ">
                <thead>
                    <tr>
                        <th>Cédula</th>
                        <th>Nombres Completos</th>
                        <th>Mes</th>
                        <th>Año</th>
                        <th>Total Ingresos</th>
                        <th>Total Egresos</th>
                        <th>Total Ahorro</th>
                        <th>Total a Recibir</th>
                        <th>Fecha de Pago</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>';

        // Llenamos la tabla con los datos obtenidos
        while($row = $this->row($result)) {
            echo '<form><tr>
                    <td>' . $row['cedula_identidad'] . '</td>
                    <td>' . $row['nombrecompleto'] . '</td>
                    <td>' . $this->meses[$row['mes']] . '</td>
                    <td>' . $row['anio'] . '</td>
                    <td>' . number_format($row['tingresos'], 2) . '</td>
                    <td>' . number_format($row['tegresos'], 2) . '</td>
                    <td>' . number_format($row['tahorro'], 2) . '</td>
                    <td>' . number_format($row['trecibir'], 2) . '</td>
                    <td>' . $row['fecpago'] . '</td>
                    <td>
                        <button type="submit" name=bttmosrol>Seleccionar</button>
                        <button type="submit" name=pdfRol>PDF</button>
                        <input type=hidden name=idrol value='.$row['id'].'>
                    </td>
                </tr></form>';
        }

        // Cerramos la tabla
        echo '</tbody></table></center>';
    }
  
    public function mostrarReporteAnualRoles($anio) {
       // Consulta para obtener los detalles por empleado y mes
        $detalleSql = "SELECT r.id, e.nombrecompleto, r.mes, 
                              r.tingresos, r.tegresos, r.tahorro, r.trecibir
                       FROM \"ROL\" r 
                       JOIN \"EMPLEADOS\" e ON r.idemp = e.idemp 
                       WHERE r.anio = $anio
                       ORDER BY e.nombrecompleto, r.mes";

        // Consulta para obtener los totales anuales
        $totalesSql = "SELECT SUM(r.tingresos) as total_ingresos, 
                              SUM(r.tegresos) as total_egresos, 
                              SUM(r.tahorro) as total_ahorro, 
                              SUM(r.trecibir) as total_recibir
                       FROM \"ROL\" r 
                       WHERE r.anio = $anio";

        // Ejecutamos las consultas
        $detalleResult = $this->consulta($detalleSql);
        $totalesResult = $this->consulta($totalesSql);
        $totales = $this->row($totalesResult);

        // Mostramos el inicio de la tabla
        echo '<center><table border="1" class="table table-striped ">
                <thead>
                    <tr>
                        <th>Nombre Completo</th>
                        <th>Mes</th>
                        <th>Total Ingresos</th>
                        <th>Total Egresos</th>
                        <th>Total Ahorro</th>
                        <th>Total a Recibir</th>
                    </tr>
                </thead>
                <tbody>';

        // Llenamos la tabla con los datos obtenidos
        while($row = $this->row($detalleResult)) {
            echo '<tr>
                    <td>' . $row['nombrecompleto'] . '</td>
                    <td>' . $this->meses[$row['mes']] . '</td>
                    <td>' . number_format($row['tingresos'], 2) . '</td>
                    <td>' . number_format($row['tegresos'], 2) . '</td>
                    <td>' . number_format($row['tahorro'], 2) . '</td>
                    <td>' . number_format($row['trecibir'], 2) . '</td>
                </tr>';
        }

        // Mostramos los totales anuales
        echo '<tr>
                <th colspan="2">Total Anual</th>
                <th>' . number_format($totales['total_ingresos'], 2) . '</th>
                <th>' . number_format($totales['total_egresos'], 2) . '</th>
                <th>' . number_format($totales['total_ahorro'], 2) . '</th>
                <th>' . number_format($totales['total_recibir'], 2) . '</th>
              </tr>';

        // Cerramos la tabla
        echo '</tbody></table></center>';
    }
    
}
