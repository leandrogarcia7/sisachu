<?php
require_once("GRUPO.php");
/*
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
*/

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LECHE
 *
 * @author LGT-5
 */
class LECHE extends GRUPO{
    //put your code here
     public $tiepoleche= array('SIN DATOS','MAÑANA','TARDE','MA + TA','TA + MA','MA + TA + MA','TA + MA + TA','2 DÍAS' );
    
    public $optielec= "<option value=0>SIN DATOS</option>
                        <option value=1>MAÑANA</option>
                        <option value=2>TARDE</option>
                        <option value=3>MA + TA</option>
                        <option value=4>TA + MA</option>
                        <option value=5>MA + TA + MA</option> 
                        <option value=6>TA + MA + TA</option>
                        <option value=7>2 DÍAS</option> ";
     public $selectielec= "<select name=tielec>
                        <option value=0>SIN DATOS</option>
                        <option value=1>MAÑANA</option>
                        <option value=2>TARDE</option>
                        <option value=3>MA + TA</option>
                        <option value=4>TA + MA</option>
                        <option value=5>MA + TA + MA</option> 
                        <option value=6>TA + MA + TA</option>
                        <option value=7>2 DÍAS</option>
                   </select>";
     
     
     
      public function mostrarInicio(){
         echo '<center>
             <form>
                <button type="submit" name="bttcrear" > <img src="../img/anadir.png" alt=""/>  <BR>REGISTRAR LECHE</button>
                <button type="submit" name="bttbuscar" > <img src="../img/buscar.jpg" alt=""/>  <BR>BUSCAR REGISTRO</button>
                <button type="submit" name="bttresumen" > <img src="../img/cuadernorojo.png" alt=""/>  <BR>RESUMEN REGISTRO</button><input type=text name=precio size=4>
             <br><br>
             GRUPO: <SELECT name=idgru>'.$this->listarGruposOptionLeche().'</select>
             <BR><br>
                Fecha: <input name=feclecc type=date value='.date("Y-m-d").'> Fecha fin: <input name=feclecfin type=date value='.date("Y-m-d").'>
            <button type="submit" name="bttcreartabla" > <img src="../img/notarojo.png" alt=""/>  <BR>REGISTRAR LECHE</button></form> </center>';
         
     }
     public function mostrarCrear($feclecc) {
    $empleados = $this->empleadosSelect();
    $litrosTerneras = $_SESSION['litros_terneras'];
    $litrosMachos = $_SESSION['litros_machos'];
    
    echo '<div class="container">';
    echo '<h1 class="text-center mb-4">Registro de Leche</h1>';
    echo '<form method="POST" action="">';

    // Grupo
    echo '<div class="form-group">';
    echo '<label for="idgru" class="form-label">Grupo</label>';
    echo '<select name="idgru" id="idgru" class="form-control">' . $this->listarGruposOptionTotalLechera() . '</select>';
    echo '</div>';

    $cantidadRejo = $this->cantidadRejo($_SESSION['idhac']);
    // Obtener el último registro
   // $ultimoRegistro = $this->obtenerUltimoRegistro($_SESSION['idhac']);
    $ultimoRegistro = $this->obtenerUltimoRegistroFecha($_SESSION['idhac'],$feclecc);
    
    $ultimaMedida = isset($ultimoRegistro['medida_tanque']) ? $ultimoRegistro['medida_tanque'] : 0;
    $ultimaMedidaAnterior = isset($ultimoRegistro['medida_anterior_tanque']) ? $ultimoRegistro['medida_anterior_tanque'] : 0;
    $t1lec = isset($ultimoRegistro['n_terneras']) ? $ultimoRegistro['n_terneras'] : 0;
    $m1lec = isset($ultimoRegistro['n_terneros']) ? $ultimoRegistro['n_terneros'] : 0;
    $ultimaFecha = isset($ultimoRegistro['feclec']) ? $ultimoRegistro['feclec'] : 'SIN FECHA';
    $ultimoTipo = isset($ultimoRegistro['tielec']) ? $ultimoRegistro['tielec'] : 0;
    $ultimoTipoSiguiente = 1;
    if($ultimoTipo == 1) {
        $ultimoTipoSiguiente = 2;
    }

    $litrosanterior = $this->obtenerLitrosTanque($_SESSION['idhac'], $ultimaMedida);
    $t2lec = $t1lec * $litrosTerneras;
    $m2lec = $m1lec * $litrosMachos;
    $tleches = $t2lec + $m2lec;

    // Mostrar datos del último registro
    echo '<div class="alert alert-info">';
    echo '<strong>Último Registro ingresado:</strong><br>';
    echo 'Fecha: ' . $ultimaFecha . '<br>';
    echo 'Tipo: ' . $this->tiepoleche[$ultimoTipo];
    echo '<br>Llevar leche terneros: ' . $tleches;
    echo '</div>';

    // Fecha
    echo '<div class="form-group">';
    echo '<label for="feclec" class="form-label">Fecha</label>';
    echo '<input type="date" name="feclec" id="feclec" class="form-control" value="' . $feclecc . '">';
    echo '</div>';

    // CSS para separadores y colores
    echo '<style>';
    echo '.form-group { margin-bottom: 15px; padding: 10px; border-radius: 5px; }';
    echo '.section-group { border: 1px solid #007bff; padding: 15px; margin-bottom: 20px; border-radius: 5px; }';
    echo '.section-group-title { font-weight: bold; color: #007bff; margin-bottom: 10px; }';
    echo '.bttnuevo { background-color: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; }';
    echo '.bttnuevo:hover { background-color: #218838; }';
    echo '</style>';

    // Campo adicional: Medida del Tanque
    echo '<div class="section-group">';
    echo '<div class="section-group-title">Medida del Tanque</div>';
    echo '<div class="form-group">';
    echo '<label for="medida_anterior" class="form-label">Medida Anterior (mm)</label>';
    echo '<input type="text" id="medida_anterior" class="form-control" value="' . $ultimaMedida . 'mm = ' . $litrosanterior . ' litros" disabled>';
    echo '<input type="hidden" name="medida_anterior" class="form-control" value="' . $ultimaMedida . '">';
    echo '</div>';
    echo '<div class="form-group">';
    echo '<label for="medida_actual" class="form-label">Regla medida Actual (mm)</label>';
    echo '<input type="number" name="medida_actual" id="medida_actual" class="form-control" value="" autofocus onchange="calcularTanque();">';
    echo '</div>';
    echo '<div class="form-group">';
    echo '<label for="totelect" class="form-label">Litros (Calculado)</label>';
    echo '<input type="text" name="totelect" id="totelect" class="form-control" value="0" disabled>';
    echo '<label for="tielec" class="form-label">Tipo Entrega:</label>';
    echo '<br><select name="tielec" id="tielec" class="form-control">';
    echo '<option value="' . $ultimoTipoSiguiente . '">' . $this->tiepoleche[$ultimoTipoSiguiente] . '</option>';
    echo '<option value="1">MAÑANA</option>';
    echo '<option value="2">TARDE</option>';
    echo '</select>';
    echo '</div>';
    echo '</div>';

    $meses = 6;
    $edadterneras = $this->contarAnimalesPorEdadYSexo($meses, 1);
    $edadmachos = $this->contarAnimalesPorEdadYSexo($meses, 2);

    // Secciones del formulario
    $sections = [
        "Datos Generales" => [
            ["# Vacas", "nani", $cantidadRejo, false],
            ["# Terneras (" . $edadterneras . ") menores a " . $meses . " meses", "t1lec", $t1lec, false, false, "calcularTerneras();"],
            ["# Machos (" . $edadmachos . ") menores a " . $meses . " meses", "m1lec", $m1lec, false, false, "calcularMachos();"],
        ],
        "Producción de Leche" => [
            ["Leche Terneras (Litros)", "t2lec", $t2lec, false, false, "calcularTotales();"],
            ["Leche Machos (Litros)", "m2lec", $m2lec, false, false, "calcularTotales();"],
            ["Total Terneros (Litros)", "ttlec", ($t2lec + $m2lec), false, true], // Disabled
        ],
        "Consumo y Totales" => [
            ["Consumo (Litros)", "conlec", 0, false, false, "calcularTotales();"],
            ["Total entrega (Litros)", "totelec", "", false, false, "calcularTotales();"],
            ["Total Producción (Litros)", "totlec", "", false, true], // Disabled
        ],
    ];

    // Renderización por secciones
    foreach ($sections as $sectionTitle => $fields) {
        echo '<div class="section-group">';
        echo '<div class="section-group-title">' . $sectionTitle . '</div>';

        foreach ($fields as $field) {
            echo '<div class="form-group">';
            echo '<label for="' . $field[1] . '" class="form-label">' . $field[0] . '</label>';
            echo '<input type="number" name="' . $field[1] . '" id="' . $field[1] . '" class="form-control" value="' . $field[2] . '"'
                . (isset($field[3]) && $field[3] ? ' autofocus' : '') // Agrega autofocus si está configurado
                . (isset($field[4]) && $field[4] ? ' disabled' : '') // Agrega disabled si está configurado
                . (isset($field[5]) ? ' onchange="' . $field[5] . '"' : '') // Agrega onchange si está configurado
                . ' required>';
            echo '</div>';
        }
        echo '</div>';
    }

    // Empleado y Prueba
    echo '<div class="section-group">';
    echo '<div class="section-group-title">Información Adicional</div>';
    echo '<div class="form-group">';
    echo '<label for="idemp" class="form-label">Empleado</label>';
    echo $empleados;
    echo '</div>';
    echo '<div class="form-group">';
    echo '<label for="prulec" class="form-label">Prueba</label>';
    echo '<input type="text" name="prulec" id="prulec" class="form-control" maxlength="100" placeholder="Grasa, consistencia">';
    echo '</div>';
    echo '</div>';

    // Botón de guardar
    echo '<div class="text-center">';
    echo '<button name="bttnuevo" class="bttnuevo">';
    echo '<img src="../img/guardar.jpg" alt="Guardar"><br>GUARDAR';
    echo '</button>';
    echo '</div>';

    echo '</form>';
    echo '</div>';

    // JavaScript para cálculos
    echo '<script>';
    echo 'const litrosTerneras = ' . $litrosTerneras . ';';
    echo 'const litrosMachos = ' . $litrosMachos . ';';
    
    echo 'function calcularTerneras() {';
    echo '    const terneras = parseInt(document.getElementById("t1lec").value) || 0;';
    echo '    document.getElementById("t2lec").value = terneras * litrosTerneras;';
    echo '    calcularTotales();';
    echo '}';
    
    echo 'function calcularMachos() {';
    echo '    const machos = parseInt(document.getElementById("m1lec").value) || 0;';
    echo '    document.getElementById("m2lec").value = machos * litrosMachos;';
    echo '    calcularTotales();';
    echo '}';
    
    echo 'function calcularTotales() {';
    echo '    const t2lec = parseInt(document.getElementById("t2lec").value) || 0;';
    echo '    const m2lec = parseInt(document.getElementById("m2lec").value) || 0;';
    echo '    const conlec = parseInt(document.getElementById("conlec").value) || 0;';
    echo '    const totelec = parseInt(document.getElementById("totelec").value) || 0;';
    echo '    ';
    echo '    const ttlec = t2lec + m2lec;';
    echo '    document.getElementById("ttlec").value = ttlec;';
    echo '    ';
    echo '    const totlec = totelec + ttlec + conlec;';
    echo '    document.getElementById("totlec").value = totlec;';
    echo '}';

    // JavaScript para calcular litros del tanque
    echo 'function calcularTanque() {';
    echo '    const medidaActual = parseInt(document.getElementById("medida_actual").value) || 0;';
    echo '    const medidaAnterior = parseInt(' . $litrosanterior . ') || 0;';
    echo '    if (medidaActual > 0) {';
    echo '        fetch("obtener_litros_tanque.php", {';
    echo '            method: "POST",';
    echo '            headers: { "Content-Type": "application/x-www-form-urlencoded" },';
    echo '            body: `milimetros=${medidaActual}&idhac=' . $_SESSION['idhac'] . '`';
    echo '        })';
    echo '        .then(response => response.json())';
    echo '        .then(data => {';
    echo '            if (data.litros) {';
    echo '                let litrosCalculados;';
    echo '                if (medidaAnterior >= data.litros) {';
    echo '                    litrosCalculados = data.litros;';
    echo '                    document.getElementById("totelect").value = `${litrosCalculados} litros = ${data.litros} (litros tanque) - IGNORADO`;';
    echo '                } else {';
    echo '                    litrosCalculados = data.litros - medidaAnterior;';
    echo '                    document.getElementById("totelect").value = `${litrosCalculados} litros = ${data.litros} (litros tanque) - ${medidaAnterior} (litros Anterior)`;';
    echo '                }';
    echo '                document.getElementById("totelec").value = litrosCalculados;';
    echo '                calcularTotales();';
    echo '            } else {';
    echo '                document.getElementById("totelect").value = "No se pudo calcular los litros.";';
    echo '                document.getElementById("totelec").value = 0;';
    echo '            }';
    echo '        })';
    echo '        .catch(error => {';
    echo '            console.error("Error:", error);';
    echo '            document.getElementById("totelect").value = "Error al procesar la solicitud.";';
    echo '            document.getElementById("totelec").value = 0;';
    echo '        });';
    echo '    } else {';
    echo '        document.getElementById("totelect").value = "Ingrese una medida válida.";';
    echo '        document.getElementById("totelec").value = 0;';
    echo '    }';
    echo '}';
    
    echo '</script>';
}
        public function mostrarCrearAnt(){
         $empleados=$this->empleadosSelect();
               echo "<center><form><table BORDER=1><th colspan=2><center>Registro de Leche</center> 
                   <tr><th>Grupo</th><td><select name=idgru>".$this->listarGruposOptionTotal()."</select></td></tr>
                   <tr><th>Fecha</th><td><input type=date name=feclec  value=".date("Y-m-d")."> </td></tr>
                    <tr><th>Total entrega</th><td><input type=number style='width: 100;' name=totelec  id=totelec required onchange='sumar(t2lec,m2lec,ttlec);restar(totelec,ttlec,conlec,totlec);'> Litros</td></tr> 
                    <tr><th># Terneras</th><td><input type=number name=t1lec style='width: 100;' value=0 required></td></tr>    
                   <tr><th># Machos</th><td><input type=number name=m1lec style='width: 100;'  value=0 required></td></tr>    
                   <tr><th>Leche Terneras</th><td><input type=number style='width: 100;' name=t2lec  value=0 id=t2lec onchange='sumar(t2lec,m2lec,ttlec);restar(totelec,ttlec,conlec,totlec);' required> Litros</td></tr>    
                   <tr><th>Leche machos</th><td><input type=number style='width: 100;' name=m2lec  value=0 id=m2lec onchange='sumar(t2lec,m2lec,ttlec);restar(totelec,ttlec,conlec,totlec);' required> Litros</td></tr>
                   <tr><th>Total terneros</th><td><input type=number style='width: 100;' name=ttlec  value=0 id=ttlec onchange='sumar(t2lec,m2lec,ttlec);restar(totelec,ttlec,conlec,totlec);' required> Litros</td></tr> 
                   <tr><th>Consumo</th><td><input type=number style='width: 100;' id=conlec name=conlec value=0 onchange='sumar(t2lec,m2lec,ttlec);restar(totelec,ttlec,conlec,totlec);' required> Litros</td></tr> 
                    <tr><th>Total producción</th><td><input type=number name=totlec style='width: 100;'  required> Litros</td></tr>    
                 
                   <tr><th># Vacas</th><td><input type=number name=nani required></td></tr>    
                   <tr><th>Tipo</th><td><select name=tielec >".$this->optielec."</select></td></tr>    
                   <tr><th>Empleado</th><td>".$empleados."</td></tr>    
                   <tr><th>Prueba</th><td><input type=text name=prulec maxlenght=100 placeholder='Grasa, consistencia'></td></tr>    
                <tr><th colspan=2><center><button name=bttnuevo class=bttnuevo > <img src='../img/guardar.jpg'> <br>GUARDAR</button></center> </td></table>
                </form></center>";
         
                
     }
     public function nuevo($datos) {
    // Revisar los datos primero para que no se dupliquen y calcular los datos que se necesitan
    $ttlec = isset($datos['t2lec']) ? $datos['t2lec'] : 0;
    $ttlec += isset($datos['m2lec']) ? $datos['m2lec'] : 0;
    
    $totlec = $ttlec;
    $totlec += isset($datos['totelec']) ? $datos['totelec'] : 0;
    $totlec += isset($datos['conlec']) ? $datos['conlec'] : 0;

    // Validar otros campos y asignar 0 si no existen
    $idgru = isset($datos['idgru']) ? $datos['idgru'] : 0;
    $idemp = isset($datos['idemp']) ? $datos['idemp'] : 0;
    $prulec = isset($datos['prulec']) ? $datos['prulec'] : '';
    $nani = isset($datos['nani']) ? $datos['nani'] : 0;
    $tielec = isset($datos['tielec']) ? $datos['tielec'] : 0;
    $feclec = isset($datos['feclec']) ? $datos['feclec'] : date('Y-m-d');
    $t1lec = isset($datos['t1lec']) ? $datos['t1lec'] : 0;
    $m1lec = isset($datos['m1lec']) ? $datos['m1lec'] : 0;
    $t2lec = isset($datos['t2lec']) ? $datos['t2lec'] : 0;
    $m2lec = isset($datos['m2lec']) ? $datos['m2lec'] : 0;
    $totelec = isset($datos['totelec']) ? $datos['totelec'] : 0;
    $conlec = isset($datos['conlec']) ? $datos['conlec'] : 0;
    $medida_tanque = isset($datos['medida_actual']) ? $datos['medida_actual'] : 0;
    $medida_anterior_tanque = isset($datos['medida_anterior']) ? $datos['medida_anterior'] : 0;

    $sql = 'INSERT INTO "LECHE" (idgru, idemp, idusu, prulec, nani, tielec, feclec, 
        totlec, t1lec, m1lec, t2lec, m2lec, ttlec, totelec, conlec, medida_tanque, medida_anterior_tanque) 
        VALUES (' . $idgru . ',' . $idemp . ',' . $_SESSION['id'] . ', \'' . $prulec . '\',' . $nani . ',' . $tielec . ',\'' . $feclec . '\',
          ' . $totlec . ',' . $t1lec . ',' . $m1lec . ',' . $t2lec . ',' . $m2lec . ',' . $ttlec . ',' . $totelec . ',' . $conlec . ',' . $medida_tanque . ',' . $medida_anterior_tanque . ');';

    if ($this->consulta($sql)) {
      //  echo "<div class='alert alert-info'><div class='mensajeok' style='color: green; font-weight: bold;'>";
       echo "<center><div class=mesajeok >Nuevo dato registrado</div>";
         echo "<div class='alert alert-info'>
    <p>Nuevo leche registrada exitosamente</p>
    <p>Fecha: {$feclec}</p>
    <p># Vacas: {$datos['nani']}</p>
    <p># Terneras: {$datos['t1lec']}</p>
    <p># Machos: {$datos['m1lec']}</p>
    <p>Leche terneros: {$ttlec}</p>
    <p>Registro: {$this->$tiepoleche[$tielec]}</p>
    <p>Total litros entrega: {$datos['totelec']}</p>
    <p>Medida de tanque: {$medida_tanque}</p>";
     if($tielec==2){
            //calcular el total día del campo ingresado si es en la tarde
            $pro=$this->mostrarProduccionDia($feclec,$_SESSION['idhac']);
            echo "<p>Total litros entrega: {$pro['total_entrega']}</p>";  
            echo "<p>Total litros producción: {$pro['total_produccion']}</p>"; 
        }
echo "</div></center>";

        
        
        
    } else {
        echo "<div class=errores >Error al crear el nuevo dato BDD <br>" . $sql . "</div>";
    }
}

public function mostrarProduccionDia($feclec, $idhac) {
    $sql = "SELECT SUM(totlec) AS total_produccion, SUM(totelec) AS total_entrega 
            FROM \"LECHE\" 
            WHERE feclec = '$feclec' AND idgru IN (SELECT id FROM \"GRUPO\" WHERE idhac = $idhac)";
    
    $stmt = $this->consulta($sql);
    $result = pg_fetch_assoc($stmt);
    
      return array(
        'total_produccion' => isset($result['total_produccion']) ? $result['total_produccion'] : 0,
        'total_entrega' => isset($result['total_entrega']) ? $result['total_entrega'] : 0
    );
}


     public function nuevo2($datos){
         
         //revisar los datos primero para que no se duplique y calcular los datos que se necesitan
         $ttlec=$datos['t2lec']+$datos['m2lec'];
         $totlec=$ttlec+$datos['totelec']+$datos['conlec'];
         
         $sql='insert into "LECHE" (idgru, idemp, idusu, prulec, nani, tielec, feclec, 
            totlec, t1lec, m1lec, t2lec, m2lec, ttlec, totelec,conlec) 
            values ('.$datos['idgru'].','.$datos['idemp'].','.$_SESSION['id'].',  \''.$datos['prulec'].'\','.$datos['nani'].','.$datos['tielec'].',\''.$datos['feclec'].'\',
              '.$totlec.','.$datos['t1lec'].','.$datos['m1lec'].','.$datos['t2lec'].','.$datos['m2lec'].','.$ttlec.','.$datos['totelec'].','.$datos['conlec'].'  );';    
         if($this->consulta($sql )){
             echo "<div class=mesajeok >Nuevo dato registrado</div>";
         }else
         {
              echo "<div class=errores >Error al crear el nuevo dato BDD <br>".$sql."</div>";
         } 
         
     }
     public function buscarLeches($fec,$fecfin){
         
          echo '  <form> <center>
        <table border="1" class=table style="width:50%">
            <tr>
                <th>Id</th><th>Fecha registro</th><th>Toma</th>
                <th>Grupo</th><th>T. prod</th>
                <th># Terneras</th><th># Machos</th>
                <th>Total Terneros</th><th>Total entrega</th>
                <th># Vacas</th><th>Acción</th>
            </tr>';
    

      //   $fotos= $ani->mostrarFotosAnimal($a['id'],300);
        
        
        $sql='select * from "LECHE","GRUPO" where "GRUPO".id="LECHE".idgru and feclec>=\''.$fec.'\' and feclec<=\''.$fecfin.'\' and estlec=1 order by estlec desc, feclec';
         $con=$this->consulta($sql);
//echo $sql;

        while($a=$this->row($con)){
                      
        echo '<tr>
               <td>'.$a['idlec'].'</td> <td>'.$a['feclec'].'</td><td>'.$this->tiepoleche[$a['tielec']].'</td>
                    <td>'.$a['detalle'].'</td><td>'.$a['totlec'].'</td>
                    <td>'.$a['t1lec'].'</td><td>'.$a['m1lec'].'</td>
                    <td>'.$a['ttlec'].'</td><td>'.$a['totelec'].'</td>
                     <td>'.$a['nani'].'</td>    <td><button name=bttsel value='.$a['idlec'].'> <img src=../img/modif.jpg  > <br>Seleccionar</button></td>
                    <td><button name=btteli onclick="javascript: return confirm(\'Esta seguro de Eliminar la Leche\');" value='.$a['idlec'].'><img src=../img/cancelar.jpg  > <br>Eliminar</button></td>
            </tr>'; 
    }
    
    echo '</table>
        
    </center></form>';
    }  
    
    public function eliminar($id) {
        $sql='delete from "LECHE"  where idlec='.$id;
      //  echo $sql;
            if($this->consulta($sql)){
             echo "<div class=mesajeok >Datos Eliminados</div>";
         }else
         {
              echo "<div class=errores >Error al eliminar  de la BDD</div>";
         }             
         
         
        
        
    }
    
    /**
 * Obtiene la medida anterior del tanque basada en la lógica de hacienda, fecha y tipo
 * @param int $idhac ID de la hacienda
 * @param string $fecha Fecha del registro actual
 * @param int $tipo Tipo de ordeño (1=MAÑANA, 2=TARDE)
 * @param int $idlec_actual ID del registro actual (para excluirlo)
 * @return int Medida anterior en milímetros
 */
public function obtenerMedidaAnterior($idhac, $fecha, $tipo, $idlec_actual = 0) {
    $medidaAnterior = 0;
    
    // Si es MAÑANA (1), buscar el último registro TARDE (2) del día anterior o el mismo día
    if ($tipo == 1) {
        // Primero buscar TARDE del día anterior
        $fechaAnterior = date('Y-m-d', strtotime($fecha . ' -1 day'));
        
        $sql = "SELECT medida_tanque 
                FROM \"LECHE\" l
                JOIN \"GRUPO\" g ON l.idgru = g.id
                WHERE g.idhac = $idhac 
                AND l.feclec = '$fechaAnterior' 
                AND l.tielec = 2 
                AND l.medida_tanque IS NOT NULL
                ORDER BY l.idlec DESC 
                LIMIT 1";
    
        $result = $this->consulta($sql);
        if ($row = $this->row($result)) {
            $medidaAnterior = $row['medida_tanque'];
        } else {
            // Si no hay TARDE del día anterior, buscar MAÑANA del día anterior
            $sql = "SELECT medida_tanque 
                    FROM \"LECHE\" l
                    JOIN \"GRUPO\" g ON l.idgru = g.id
                    WHERE g.idhac = $idhac 
                    AND l.feclec = '$fechaAnterior' 
                    AND l.tielec = 1 
                    AND l.medida_tanque IS NOT NULL
                    ORDER BY l.idlec DESC 
                    LIMIT 1";
          
            $result = $this->consulta($sql);
            if ($row = $this->row($result)) {
                $medidaAnterior = $row['medida_tanque'];
            }
        }
    }
    // Si es TARDE (2), buscar MAÑANA del mismo día
    else if ($tipo == 2) {
        $sql = "SELECT medida_tanque 
                FROM \"LECHE\" l
                JOIN \"GRUPO\" g ON l.idgru = g.id
                WHERE g.idhac = $idhac 
                AND l.feclec = '$fecha' 
                AND l.tielec = 1 
                AND l.medida_tanque IS NOT NULL
                AND l.idlec != $idlec_actual
                ORDER BY l.idlec DESC 
                LIMIT 1";
        
        $result = $this->consulta($sql);
        if ($row = $this->row($result)) {
            $medidaAnterior = $row['medida_tanque'];
        } else {
            // Si no hay MAÑANA del mismo día, buscar el último registro del día anterior
            $fechaAnterior = date('Y-m-d', strtotime($fecha . ' -1 day'));
            
            $sql = "SELECT medida_tanque 
                    FROM \"LECHE\" l
                    JOIN \"GRUPO\" g ON l.idgru = g.id
                    WHERE g.idhac = $idhac 
                    AND l.feclec = '$fechaAnterior' 
                    AND l.medida_tanque IS NOT NULL
                    ORDER BY l.tielec DESC, l.idlec DESC 
                    LIMIT 1";
            
            $result = $this->consulta($sql);
            if ($row = $this->row($result)) {
                $medidaAnterior = $row['medida_tanque'];
            }
        }
    }
    
    // Si no se encontró ninguna medida anterior, buscar la última disponible
    if ($medidaAnterior == 0) {
        $sql = "SELECT medida_tanque 
                FROM \"LECHE\" l
                JOIN \"GRUPO\" g ON l.idgru = g.id
                WHERE g.idhac = $idhac 
                AND l.feclec < '$fecha' 
                AND l.medida_tanque IS NOT NULL
                AND l.idlec != $idlec_actual
                ORDER BY l.feclec DESC, l.tielec DESC, l.idlec DESC 
                LIMIT 1";
        
        $result = $this->consulta($sql);
        if ($row = $this->row($result)) {
            $medidaAnterior = $row['medida_tanque'];
        }
    }
    
    return $medidaAnterior;
}
    
   public function mostrarModificar($id) {
    $sql = 'SELECT l.*, g.detalle as grupo_detalle, e.apellido, e.nombre 
            FROM "LECHE" l
            LEFT JOIN "GRUPO" g ON g.id = l.idgru
            LEFT JOIN "EMPLEADOS" e ON e.idemp = l.idemp
            WHERE l.idlec = ' . $id;
    
    $con = $this->consulta($sql);
    
    if ($a = $this->row($con)) {
        $litrosTerneras = $_SESSION['litros_terneras'];
        $litrosMachos = $_SESSION['litros_machos'];
        
        echo '<div class="container">';
        echo '<h1 class="text-center mb-4">Modificar Registro de Leche</h1>';
        echo '<form method="POST" action="">';

        // Información del registro actual
        echo '<div class="alert alert-warning">';
        echo '<strong>Modificando registro del:</strong> ' . $a['feclec'] . '<br>';
        echo 'Grupo: ' . $a['grupo_detalle'] . '<br>';
        echo 'Empleado: ' . $a['apellido'] . ' ' . $a['nombre'];
        echo '</div>';

        // Grupo
        echo '<div class="form-group">';
        echo '<label for="idgru" class="form-label">Grupo</label>';
        echo '<select name="idgru" id="idgru" class="form-control">';
        echo '<option value="' . $a['idgru'] . '">' . $a['grupo_detalle'] . ' (Actual)</option>';
        echo $this->listarGruposOptionTotalLechera();
        echo '</select>';
        echo '</div>';

        // Fecha
        echo '<div class="form-group">';
        echo '<label for="feclec" class="form-label">Fecha</label>';
        echo '<input type="date" name="feclec" id="feclec" class="form-control" value="' . $a['feclec'] . '">';
        echo '</div>';

        // CSS para separadores y colores
        echo '<style>';
        echo '.form-group { margin-bottom: 15px; padding: 10px; border-radius: 5px; }';
        echo '.section-group { border: 1px solid #ffc107; padding: 15px; margin-bottom: 20px; border-radius: 5px; }';
        echo '.section-group-title { font-weight: bold; color: #ffc107; margin-bottom: 10px; }';
        echo '.bttmod { background-color: #ffc107; color: #212529; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; }';
        echo '.bttmod:hover { background-color: #e0a800; }';
        echo '</style>';

        // Medida del Tanque - Calcular medida anterior desde la BD
        echo '<div class="section-group">';
        echo '<div class="section-group-title">Medida del Tanque</div>';
        
        // Obtener la medida anterior basada en la lógica de hacienda, fecha y tipo
        $medidaAnterior = $this->obtenerMedidaAnterior($_SESSION['idhac'], $a['feclec'], $a['tielec'], $id);
        $litrosAnterior = $this->obtenerLitrosTanque($_SESSION['idhac'], $medidaAnterior);
        
        echo '<div class="form-group">';
        echo '<label for="medida_anterior" class="form-label">Medida Anterior (mm)</label>';
        echo '<input type="text" id="medida_anterior_display" class="form-control" value="' . $medidaAnterior . 'mm = ' . $litrosAnterior . ' litros" disabled>';
        echo '<input type="hidden" name="medida_anterior_tanque" value="' . $medidaAnterior . '">';
        echo '</div>';
        
        echo '<div class="form-group">';
        echo '<label for="medida_actual" class="form-label">Medida Actual (mm)</label>';
        $medidaActual = isset($a['medida_tanque']) ? $a['medida_tanque'] : 0;
        echo '<input type="number" name="medida_tanque" id="medida_actual" class="form-control" value="' . $medidaActual . '" onchange="calcularTanque();">';
        echo '</div>';
        
        echo '<div class="form-group">';
        echo '<label for="totelect" class="form-label">Litros Calculados del Tanque</label>';
        echo '<input type="text" name="totelect" id="totelect" class="form-control" value="Calculado automáticamente" disabled>';
        echo '</div>';
        
        echo '</div>';

        $meses = 6;
        $edadterneras = $this->contarAnimalesPorEdadYSexo($meses, 1);
        $edadmachos = $this->contarAnimalesPorEdadYSexo($meses, 2);

        // Secciones del formulario con datos existentes
        $sections = [
            "Datos Generales" => [
                ["# Vacas", "nani", $a['nani'], false],
                ["# Terneras (" . $edadterneras . ") menores a " . $meses . " meses", "t1lec", $a['t1lec'], false, false, "calcularTerneras();"],
                ["# Machos (" . $edadmachos . ") menores a " . $meses . " meses", "m1lec", $a['m1lec'], false, false, "calcularMachos();"],
            ],
            "Producción de Leche" => [
                ["Leche Terneras (Litros)", "t2lec", $a['t2lec'], false, false, "calcularTotales();"],
                ["Leche Machos (Litros)", "m2lec", $a['m2lec'], false, false, "calcularTotales();"],
                ["Total Terneros (Litros)", "ttlec", $a['ttlec'], false, true], // Disabled
            ],
            "Consumo y Totales" => [
                ["Consumo (Litros)", "conlec", $a['conlec'], false, false, "calcularTotales();"],
                ["Total entrega (Litros)", "totelec", $a['totelec'], false, false, "calcularTotales();"],
                ["Total Producción (Litros)", "totlec", $a['totlec'], false, true], // Disabled
            ],
        ];

        // Renderización por secciones
        foreach ($sections as $sectionTitle => $fields) {
            echo '<div class="section-group">';
            echo '<div class="section-group-title">' . $sectionTitle . '</div>';

            foreach ($fields as $field) {
                echo '<div class="form-group">';
                echo '<label for="' . $field[1] . '" class="form-label">' . $field[0] . '</label>';
                echo '<input type="number" name="' . $field[1] . '" id="' . $field[1] . '" class="form-control" value="' . $field[2] . '"'
                    . (isset($field[3]) && $field[3] ? ' autofocus' : '') // Agrega autofocus si está configurado
                    . (isset($field[4]) && $field[4] ? ' disabled' : '') // Agrega disabled si está configurado
                    . (isset($field[5]) ? ' onchange="' . $field[5] . '"' : '') // Agrega onchange si está configurado
                    . ' required>';
                echo '</div>';
            }
            echo '</div>';
        }

        // Información Adicional
        echo '<div class="section-group">';
        echo '<div class="section-group-title">Información Adicional</div>';
        
        // Tipo de ordeño
        echo '<div class="form-group">';
        echo '<label for="tielec" class="form-label">Tipo de Ordeño</label>';
        echo '<select name="tielec" id="tielec" class="form-control">';
        echo '<option value="' . $a['tielec'] . '">' . $this->tiepoleche[$a['tielec']] . ' (Actual)</option>';
        echo '<option value="1">MAÑANA</option>';
        echo '<option value="2">TARDE</option>';
        echo '</select>';
        echo '</div>';

        // Empleado
        echo '<div class="form-group">';
        echo '<label for="idemp" class="form-label">Empleado</label>';
        echo '<select name="idemp" id="idemp" class="form-control">';
        echo '<option value="' . $a['idemp'] . '">' . $a['apellido'] . ' ' . $a['nombre'] . ' (Actual)</option>';
        echo $this->empleadosoption();
        echo '</select>';
        echo '</div>';

        // Prueba
        echo '<div class="form-group">';
        echo '<label for="prulec" class="form-label">Prueba</label>';
        echo '<input type="text" name="prulec" id="prulec" class="form-control" maxlength="100" placeholder="Grasa, consistencia" value="' . htmlspecialchars($a['prulec']) . '">';
        echo '</div>';
        
        echo '</div>';

        // Campo oculto para el ID
        echo '<input type="hidden" name="idlec" value="' . $id . '">';

        // Botón de guardar
        echo '<div class="text-center">';
        echo '<button name="bttmod" class="bttmod">';
        echo '<img src="../img/guardar.jpg" alt="Guardar"><br>MODIFICAR';
        echo '</button>';
        echo '</div>';

        echo '</form>';
        echo '</div>';

        // JavaScript para cálculos
        echo '<script>';
        echo 'const litrosTerneras = ' . $litrosTerneras . ';';
        echo 'const litrosMachos = ' . $litrosMachos . ';';
        
        echo 'function calcularTerneras() {';
        echo '    const terneras = parseInt(document.getElementById("t1lec").value) || 0;';
        echo '    document.getElementById("t2lec").value = terneras * litrosTerneras;';
        echo '    calcularTotales();';
        echo '}';
        
        echo 'function calcularMachos() {';
        echo '    const machos = parseInt(document.getElementById("m1lec").value) || 0;';
        echo '    document.getElementById("m2lec").value = machos * litrosMachos;';
        echo '    calcularTotales();';
        echo '}';
        
        echo 'function calcularTotales() {';
        echo '    const t2lec = parseInt(document.getElementById("t2lec").value) || 0;';
        echo '    const m2lec = parseInt(document.getElementById("m2lec").value) || 0;';
        echo '    const conlec = parseInt(document.getElementById("conlec").value) || 0;';
        echo '    const totelec = parseInt(document.getElementById("totelec").value) || 0;';
        echo '    ';
        echo '    const ttlec = t2lec + m2lec;';
        echo '    document.getElementById("ttlec").value = ttlec;';
        echo '    ';
        echo '    const totlec = totelec + ttlec + conlec;';
        echo '    document.getElementById("totlec").value = totlec;';
        echo '}';

        // JavaScript para calcular litros del tanque
        echo 'function calcularTanque() {';
        echo '    const medidaActual = parseInt(document.getElementById("medida_actual").value) || 0;';
        echo '    const medidaAnterior = ' . $medidaAnterior . ';';
        echo '    const litrosAnterior = ' . $litrosAnterior . ';';
        echo '    if (medidaActual > 0) {';
        echo '        fetch("obtener_litros_tanque.php", {';
        echo '            method: "POST",';
        echo '            headers: { "Content-Type": "application/x-www-form-urlencoded" },';
        echo '            body: `milimetros=${medidaActual}&idhac=' . $_SESSION['idhac'] . '`';
        echo '        })';
        echo '        .then(response => response.json())';
        echo '        .then(data => {';
        echo '            if (data.litros) {';
        echo '                let litrosCalculados;';
        echo '                if (litrosAnterior >= data.litros) {';
        echo '                    litrosCalculados = data.litros;';
        echo '                    document.getElementById("totelect").value = `${litrosCalculados} litros = ${data.litros} (litros tanque) - IGNORADO`;';
        echo '                } else {';
        echo '                    litrosCalculados = data.litros - litrosAnterior;';
        echo '                    document.getElementById("totelect").value = `${litrosCalculados} litros = ${data.litros} (litros tanque) - ${litrosAnterior} (litros Anterior)`;';
        echo '                }';
        echo '                document.getElementById("totelec").value = litrosCalculados;';
        echo '                calcularTotales();';
        echo '            } else {';
        echo '                document.getElementById("totelect").value = "No se pudo calcular los litros.";';
        echo '                document.getElementById("totelec").value = 0;';
        echo '            }';
        echo '        })';
        echo '        .catch(error => {';
        echo '            console.error("Error:", error);';
        echo '            document.getElementById("totelect").value = "Error al procesar la solicitud.";';
        echo '            document.getElementById("totelec").value = 0;';
        echo '        });';
        echo '    }';
        echo '}';
        
        echo '</script>';

    } else {
        echo '<div class="alert alert-danger">Error al seleccionar el registro de la base de datos.<br>SQL: ' . $sql . '</div>';
    }
}
    
      public function mostrarModificarAnt($id){
          
          $sql='select * from "LECHE","GRUPO","EMPLEADOS" where "EMPLEADOS".idemp="LECHE".idemp and  "GRUPO".id="LECHE".idgru and idlec='.$id;
        $con=$this->consulta($sql);
        
        if($a=$this->row($con)){
            
              echo "<center><form><table BORDER=1><th colspan=2><center>Registro de Leche</center> 
                   <tr><th>Grupo</th><td><select name=idgru><option value=".$a['id'].">".$a['detalle']."(actual) </option> ".$this->listarGruposOptionTotal()."</select></td></tr>
                   <tr><th>Fecha</th><td><input type=date name=feclec  value=".$a['feclec']."> </td></tr>
                 <tr><th>Total entrega</th><td><input type=number value='".$a['totelec']."' style='width: 100;' name=totelec id=totelec  onchange='sumar(t2lec,m2lec,ttlec);restar(totelec,ttlec,conlec,totlec);' required> Litros</td></tr> 
                     <tr><th># Terneras</th><td><input type=number name=t1lec value='".$a['t1lec']."' style='width: 100;' value=0 required></td></tr>    
                   <tr><th># Machos</th><td><input type=number name=m1lec value='".$a['m1lec']."' style='width: 100;'  value=0 required></td></tr>    
                   <tr><th>Leche Terneras</th><td><input type=number value='".$a['t2lec']."' style='width: 100;' name=t2lec  value=0 id=t2lec onchange='sumar(t2lec,m2lec,ttlec);restar(totelec,ttlec,conlec,totlec);' required> Litros</td></tr>    
                   <tr><th>Leche machos</th><td><input type=number value='".$a['m2lec']."' style='width: 100;' name=m2lec  value=0 id=m2lec onchange='sumar(t2lec,m2lec,ttlec);restar(totelec,ttlec,conlec,totlec);' required> Litros</td></tr>
                   <tr><th>Total terneros</th><td><input type=number value='".$a['ttlec']."' style='width: 100;' name=ttlec  value=0 id=ttlec onchange='sumar(t2lec,m2lec,ttlec);restar(totelec,ttlec,conlec,totlec);' required> Litros</td></tr> 
                   <tr><th>Consumo</th><td><input type=number value='".$a['conlec']."' style='width: 100;' id=conlec name=conlec value=0 onchange='sumar(t2lec,m2lec,ttlec);restar(totelec,ttlec,conlec,totlec);' required> Litros</td></tr> 
                    <tr><th>Total producción</th><td><input type=number value='".$a['totlec']."' name=totlec style='width: 100;'  required> Litros</td></tr>    
                  <tr><th># Vacas</th><td><input type=number name=nani value='".$a['nani']."' required></td></tr>    
                   <tr><th>Tipo</th><td><select name=tielec > <option value=".$a['tielec'].">".$this->tiepoleche[$a['tielec']]."</option>
                   ".$this->optielec."
                   </select></td></tr>    
                   <tr><th>Empleado</th><td><select name=idemp><option value=".$a['idemp'].">".$a['apellido']." ".$a['nombre']."</option>".$this->empleadosoption()."   </select> </td></tr>    
                   <tr><th>Prueba</th><td><input type=text name=prulec value='".$a['prulec']."' maxlenght=100 placeholder='Grasa, consistencia'></td></tr>    
                <tr><th colspan=2><center><button name=bttmod class=bttmod > <img src='../img/guardar.jpg'> <br>GUARDAR</button></center> </td></table>
              <input type=hidden value='$id' name=idlec> </form></center>";
            
            
            
        }else{
            echo "<div class=errores >Error al seleccionar  de la BDD ".$sql."</div>";
        }
        
    }
    public function Modificar($datos) {
    // Cálculos automáticos
    $ttlec = $datos['t2lec'] + $datos['m2lec'];
    $totlec = $ttlec + $datos['totelec'] + $datos['conlec'];
    
    // Preparar campos básicos
    $campos = [
        'idgru = ' . $datos['idgru'],
        'idemp = ' . $datos['idemp'], 
        'idusu = ' . $_SESSION['id'],
        'prulec = \'' . addslashes($datos['prulec']) . '\'',
        'nani = ' . $datos['nani'],
        'tielec = ' . $datos['tielec'],
        'feclec = \'' . $datos['feclec'] . '\'',
        'totlec = ' . $totlec,
        't1lec = ' . $datos['t1lec'],
        'm1lec = ' . $datos['m1lec'],
        't2lec = ' . $datos['t2lec'],
        'm2lec = ' . $datos['m2lec'],
        'ttlec = ' . $ttlec,
        'totelec = ' . $datos['totelec'],
        'conlec = ' . $datos['conlec'],
        'estlec = 1'
    ];
    
    // Agregar campos del tanque si existen
    if (isset($datos['medida_tanque']) && !empty($datos['medida_tanque'])) {
        $campos[] = 'medida_tanque = ' . intval($datos['medida_tanque']);
    }
    
    if (isset($datos['medida_anterior_tanque']) && !empty($datos['medida_anterior_tanque'])) {
        $campos[] = 'medida_anterior_tanque = ' . intval($datos['medida_anterior_tanque']);
    } else {
        // Si no viene medida_anterior_tanque, calcularla
        $medidaAnterior = $this->obtenerMedidaAnterior($_SESSION['idhac'], $datos['feclec'], $datos['tielec'], $datos['idlec']);
        if ($medidaAnterior > 0) {
            $campos[] = 'medida_anterior_tanque = ' . $medidaAnterior;
        }
    }
    
    // Construir la consulta SQL
    $sql = 'UPDATE "LECHE" SET ' . implode(', ', $campos) . ' WHERE idlec = ' . $datos['idlec'] . ';';
    
    // Ejecutar la consulta
    if ($this->consulta($sql)) {
        echo "<div class='mesajeok'>Registro de leche modificado exitosamente</div>";
        
        // Log de la operación (opcional)
     //   $this->registrarLog('MODIFICAR_LECHE', 'ID: ' . $datos['idlec'] . ' - Fecha: ' . $datos['feclec'] . ' - Tipo: ' . $datos['tielec']);
        
        return true;
    } else {
        echo "<div class='errores'>Error al modificar el registro en la base de datos<br>SQL: " . $sql . "</div>";
        return false;
    }
}
        public function ModificarAnt($datos){
            
           // echo "No se puede modificar";
              $ttlec=$datos['t2lec']+$datos['m2lec'];
         $totlec=$ttlec+$datos['totelec']+$datos['conlec'];
         
         
            $sql='UPDATE "LECHE"
   SET  idgru='.$datos['idgru'].', idemp='.$datos['idemp'].', idusu='.$_SESSION['id'].', prulec=\''.$datos['prulec'].'\', nani='.$datos['nani'].', tielec='.$datos['tielec'].', 
       feclec=\''.$datos['feclec'].'\', totlec='.$totlec.', t1lec='.$datos['t1lec'].', m1lec='.$datos['m1lec'].', t2lec='.$datos['t2lec'].', m2lec='.$datos['m2lec'].', ttlec='.$ttlec.', 
       totelec='.$datos['totelec'].', estlec=1, conlec='.$datos['conlec'].'
 WHERE idlec='.$datos['idlec'].';
';
            
            
         if($this->consulta($sql)){
             echo "<div class=mesajeok >Cambios registrados</div>";
         }else
         {
              echo "<div class=errores >Error al modificar la raza de la BDD ".$sql."</div>";
         }             
         
         
     }
     
      public function resumenGrupos($fec,$fecfin,$precio){
          
      }
    
    public function resumenGruposHistorial($feclecc,$feclecfin,$precio,$idgru) {
    echo '<table border="1" class="table" style="width:70%">';
    echo '<tr><th>Grupo</th><th>Año</th><th>Mes</th><th>Total Producido</th><th>Total Entregado</th><th>Diferencia</th></tr>';

    $sql = "SELECT 
                g.detalle AS grupo, 
                EXTRACT(YEAR FROM l.feclec) AS anio, 
                EXTRACT(MONTH FROM l.feclec) AS mes, 
                SUM(l.totlec) AS total_producido, 
                SUM(l.totelec) AS total_entregado
            FROM 
                \"LECHE\" l
            INNER JOIN 
                \"GRUPO\" g ON g.id = l.idgru
            WHERE 
                EXTRACT(YEAR FROM l.feclec) >= 2019
                AND l.estlec = 1 and l.idgru=".$idgru."
                AND g.idhac = " . $_SESSION['idhac'] . "
            GROUP BY 
                g.detalle, EXTRACT(YEAR FROM l.feclec), EXTRACT(MONTH FROM l.feclec)
            ORDER BY 
                g.detalle, anio, mes";

    $con = $this->consulta($sql);

    $datos = [];
    while ($fila = $this->row($con)) {
        $datos[] = $fila;
    }

    $grupos = [];
    foreach ($datos as $fila) {
        $grupo = $fila['grupo'];
        $anio = $fila['anio'];
        $mes = $fila['mes'];
        $total_producido = $fila['total_producido'];
        $total_entregado = $fila['total_entregado'];
        $diferencia = $total_producido - $total_entregado;

        if (!isset($grupos[$grupo])) {
            $grupos[$grupo] = [];
        }

        if (!isset($grupos[$grupo][$anio])) {
            $grupos[$grupo][$anio] = ['total_producido' => 0, 'total_entregado' => 0, 'diferencia' => 0, 'meses' => []];
        }

        $grupos[$grupo][$anio]['total_producido'] += $total_producido;
        $grupos[$grupo][$anio]['total_entregado'] += $total_entregado;
        $grupos[$grupo][$anio]['diferencia'] += $diferencia;
        $grupos[$grupo][$anio]['meses'][$mes] = [
            'total_producido' => $total_producido,
            'total_entregado' => $total_entregado,
            'diferencia' => $diferencia,
        ];

        echo '<tr>';
        echo '<td>' . $grupo . '</td>';
        echo '<td>' . $anio . '</td>';
        echo '<td>' . $mes . '</td>';
        echo '<td>' . $total_producido . '</td>';
        echo '<td>' . $total_entregado . '</td>';
        echo '<td>' . $diferencia . '</td>';
        echo '</tr>';
    }

    foreach ($grupos as $grupo => $anios) {
        foreach ($anios as $anio => $datos_anuales) {
            echo '<tr style="font-weight:bold">';
            echo '<td>' . $grupo . '</td>';
            echo '<td>' . $anio . '</td>';
            echo '<td>Total</td>';
            echo '<td>' . $datos_anuales['total_producido'] . '</td>';
            echo '<td>' . $datos_anuales['total_entregado'] . '</td>';
            echo '<td>' . $datos_anuales['diferencia'] . '</td>';
            echo '</tr>';
        }
    }

    echo '</table>';
}

      
      
   public function mostrarIngresarLeche($fechaInicio, $fechaFin,$idgru) {
    echo '<center>
        ';
    /*
      echo '<table border="1" class="table" style="width:100%">
            <tr>
                <th>Fecha</th>
                <th>Toma</th>
                <th>Grupo</th>
                <th># Vacas</th>
                <th>Total entrega</th>
                <th># Terneras</th>
                <th># Machos</th>
                <th>Leche Terneras</th>
                <th>Leche Machos</th>
                <th>Total Terneros</th>
                <th>Consumo</th>
                <th>Total producción</th>
            
                <th>Tipo</th>
                <th>Empleado</th>
                <th>Prueba</th><th>Acción</th>
            </tr>';
     */
    
    
    
    
    echo '<table border="1" class="table" style="width:100%">
            <tr>
                <th>Fecha</th>
                <th>Toma</th>
                <th>Grupo</th>
                <th># Vacas</th>
              
                <th># Terneras</th>
                <th># Machos</th>
                <th>Leche Terneras</th>
                <th>Leche Machos</th>
                <th>Total Terneros</th>
                <th>Consumo</th>
                <th class="bg-secondary text-white">Total entrega</th>
                <th>Total producción</th>
            
                <th>Tipo</th>
                <th>Empleado</th>
                <th>Prueba</th><th>Acción</th>
            </tr>';

    $fechaInicio = new DateTime($fechaInicio);
    $fechaFin = new DateTime($fechaFin);
    $interval = new DateInterval('P1D');
$grupo=$this->mostrarGrupo($idgru);

$i=0;$ini=0;
    while ($fechaInicio <= $fechaFin and $i<100) {
        $fechaActual = $fechaInicio->format('Y-m-d');
        $filaManana = $this->existeRegistroEnFechaTipo($fechaActual, 1,$idgru);
        $filaTarde = $this->existeRegistroEnFechaTipo($fechaActual, 2,$idgru);
        $totaltanque=0;$totaldia=0;
        echo '<form method="POST">
            <input type=hidden name=idgru value='.$idgru.'>
             <input type=hidden name=feclec value='.$fechaActual.'>
            ';
                    $i++;
        if (!$filaManana) {

            // Mostrar campos para ingresar datos en la mañana
            echo '<tr>
                <td>' . $fechaActual . '</td>
                <td>MAÑANA</td>
                <td>
                    '.$grupo['detalle'].'
                </td>';
            
            echo ' <td><input type="number"  name="nani" id=nani'.$i.' style="width:100%;" value="0" required></td>
               
                  <td><input type="number" name="t1lec" id=t1lec'.$i.' onchange="sumar(t2lec'.$i.',m2lec'.$i.',ttlec'.$i.');restar(totelec'.$i.',ttlec'.$i.',conlec'.$i.',totlec'.$i.');" style="width:100%;" value="0" required></td>
                  <td><input type="number" name="m1lec" id=m1lec'.$i.' onchange="sumar(t2lec'.$i.',m2lec'.$i.',ttlec'.$i.');restar(totelec'.$i.',ttlec'.$i.',conlec'.$i.',totlec'.$i.');" style="width:100%;" value="0" required></td>
                  <td><input type="number" name="t2lec" id=t2lec'.$i.' onchange="sumar(t2lec'.$i.',m2lec'.$i.',ttlec'.$i.');restar(totelec'.$i.',ttlec'.$i.',conlec'.$i.',totlec'.$i.');" style="width:100%;" value="0" required></td>
                  <td><input type="number" name="m2lec" id=m2lec'.$i.' onchange="sumar(t2lec'.$i.',m2lec'.$i.',ttlec'.$i.');restar(totelec'.$i.',ttlec'.$i.',conlec'.$i.',totlec'.$i.');" style="width:100%;" value="0" required></td>
                  <td><input type="number" name="ttlec" id=ttlec'.$i.' onchange="sumar(t2lec'.$i.',m2lec'.$i.',ttlec'.$i.');restar(totelec'.$i.',ttlec'.$i.',conlec'.$i.',totlec'.$i.');" style="width:100%;" value="0" disabled ></td>
                  <td><input type="number" name="conlec" id=conlec'.$i.' onchange="sumar(t2lec'.$i.',m2lec'.$i.',ttlec'.$i.');restar(totelec'.$i.',ttlec'.$i.',conlec'.$i.',totlec'.$i.');" style="width:100%;" value="0" required></td>
                  <td class="bg-secondary text-white"><input type="number" name="totelec" style="width:100%;" required id=totelec'.$i.' onchange="sumar(t2lec'.$i.',m2lec'.$i.',ttlec'.$i.');restar(totelec'.$i.',ttlec'.$i.',conlec'.$i.',totlec'.$i.');" ></td>
                      <td><input type="number" name="totlec" id=totlec'.$i.'  style="width:100%;" disabled ></td>
                      

                 
                  <td>
                      <select name="tielec">
                      <option value=1>'.$this->tiepoleche[1].'</option>
                          ' . $this->optielec . '
                      </select>
                  </td>
                  <td><input type="number" name="idemp" value="0" style="width:100%;"></td>
                  <td><input type="text" name="prulec" maxlength="100" placeholder="Grasa, consistencia"></td>
                  <td> <button type="submit" name="bttnuevo" class="bttnuevo"> <img src="../img/guardar.jpg"> <br>CREAR</button>
                  ';
            
              if($ini==0){
                echo '<script>document.getElementById("nani'.$i.'").focus();</script>';
                //echo '<script>document.getElementById("totelec'.$i.'").focus();</script>';
                $ini++;
            }  
            $nt=0;$nm=0; $lt=0; $lm=0;
              $ttl= 0;
              $nv=0;
        } else {
              echo '<tr bgcolor=silver>
                <td>' . $fechaActual . '</td>
                <td>MAÑANA</td>
                <td>
                    '.$grupo['detalle'].'
                </td>';
              $nt=$filaManana['t1lec'];
              $nm=$filaManana['m1lec'];
              $lt=$filaManana['t2lec'];
              $lm=$filaManana['m2lec'];
              $ttl= $filaManana['ttlec'];
              $nv=$filaManana['nani'];
            // Mostrar campos con los valores de $filaManana
            echo '<td><input type="number" name="nani" value="' . $filaManana['nani'] . '" style="width:100%;" required></td>
              
                  <td><input type="number" name="t1lec" style="width:100%;" value="' . $filaManana['t1lec'] . '" required id=t1lec'.$i.' onchange="sumar(t2lec'.$i.',m2lec'.$i.',ttlec'.$i.');restar(totelec'.$i.',ttlec'.$i.',conlec'.$i.',totlec'.$i.');"></td>
                  <td><input type="number" name="m1lec" style="width:100%;" value="' . $filaManana['m1lec'] . '" required id=m1lec'.$i.' onchange="sumar(t2lec'.$i.',m2lec'.$i.',ttlec'.$i.');restar(totelec'.$i.',ttlec'.$i.',conlec'.$i.',totlec'.$i.');"></td>
                  <td><input type="number" name="t2lec" style="width:100%;" value="' . $filaManana['t2lec'] . '" required id=t2lec'.$i.' onchange="sumar(t2lec'.$i.',m2lec'.$i.',ttlec'.$i.');restar(totelec'.$i.',ttlec'.$i.',conlec'.$i.',totlec'.$i.');"></td>
                  <td><input type="number" name="m2lec" style="width:100%;" value="' . $filaManana['m2lec'] . '" required id=m2lec'.$i.' onchange="sumar(t2lec'.$i.',m2lec'.$i.',ttlec'.$i.');restar(totelec'.$i.',ttlec'.$i.',conlec'.$i.',totlec'.$i.');"></td>
                  <td><input type="number" name="ttlec" style="width:100%;" value="' . $filaManana['ttlec'] . '" required id=ttlec'.$i.' onchange="sumar(t2lec'.$i.',m2lec'.$i.',ttlec'.$i.');restar(totelec'.$i.',ttlec'.$i.',conlec'.$i.',totlec'.$i.');" disabled ></td>
                  <td><input type="number" name="conlec" style="width:100%;" value="' . $filaManana['conlec'] . '" required id=conlec'.$i.' onchange="sumar(t2lec'.$i.',m2lec'.$i.',ttlec'.$i.');restar(totelec'.$i.',ttlec'.$i.',conlec'.$i.',totlec'.$i.');"></td>
                  <td class="bg-secondary text-white"><input type="number" name="totelec" style="width:100%;" value="' . $filaManana['totelec'] . '" required id=totelec'.$i.' onchange="sumar(t2lec'.$i.',m2lec'.$i.',ttlec'.$i.');restar(totelec'.$i.',ttlec'.$i.',conlec'.$i.',totlec'.$i.');"></td>                 
                  <td><input type="number" name="totlec" style="width:100%;" value="' . $filaManana['totlec'] . '" id=totlec'.$i.' disabled ></td>
                  
                  <td>
                      <select name="tielec"><option value=' . $filaManana['tielec'] . '>'.$this->tiepoleche[$filaManana['tielec']].'</option>
                          ' . $this->optielec . '
                      </select>
                  </td>
                  <td><input type="number" name="idemp" value="' . $filaManana['idemp'] . '"></td>
                  <td><input type="text" name="prulec" maxlength="100" placeholder="Grasa, consistencia" value="' . $filaManana['prulec'] . '"></td>
                      <td> <input type=hidden name=idlec value='.$filaManana['idlec'].'>
                      <button type="submit" name="bttmod" class="bttnuevo"> <img src="../img/guardar.jpg"> <br>MODIFICAR</button>';
            $totaltanque=$filaManana['totelec']; $totaldia= $filaManana['totlec'];
        }
        echo '</tr></form>';

        // Repetir el mismo proceso para la toma TARDE
        echo '<form method="POST">
             <input type=hidden name=idgru value='.$idgru.'>
             <input type=hidden name=feclec value='.$fechaActual.'>
          ';  
                       $i++;
        if (!$filaTarde) {
            // Mostrar campos para ingresar datos en la tarde
            echo'<tr>
                <td>' . $fechaActual . '</td>
                <td>TARDE</td>
                <td>
                  '.$grupo['detalle'].'
                </td>';
            
        
            
            echo '<td><input type="number" name="nani" style="width:100%;" value="'.$nv.'" required></td>
                
                  <td><input type="number" name="t1lec" style="width:100%;" value="'.$nt.'" required id=t1lec'.$i.' onchange="sumar(t2lec'.$i.',m2lec'.$i.',ttlec'.$i.');restar(totelec'.$i.',ttlec'.$i.',conlec'.$i.',totlec'.$i.');"></td>
                  <td><input type="number" name="m1lec" style="width:100%;" value="'.$nm.'" required id=m1lec'.$i.' onchange="sumar(t2lec'.$i.',m2lec'.$i.',ttlec'.$i.');restar(totelec'.$i.',ttlec'.$i.',conlec'.$i.',totlec'.$i.');"></td>
                  <td><input type="number" name="t2lec" style="width:100%;" value="'.$lt.'" required id=t2lec'.$i.' onchange="sumar(t2lec'.$i.',m2lec'.$i.',ttlec'.$i.');restar(totelec'.$i.',ttlec'.$i.',conlec'.$i.',totlec'.$i.');"></td>
                  <td><input type="number" name="m2lec" style="width:100%;" value="'.$lm.'" required id=m2lec'.$i.' onchange="sumar(t2lec'.$i.',m2lec'.$i.',ttlec'.$i.');restar(totelec'.$i.',ttlec'.$i.',conlec'.$i.',totlec'.$i.');"></td>
                  <td><input type="number" name="ttlec" style="width:100%;" value="'.$ttl.'" required id=ttlec'.$i.' onchange="sumar(t2lec'.$i.',m2lec'.$i.',ttlec'.$i.');restar(totelec'.$i.',ttlec'.$i.',conlec'.$i.',totlec'.$i.');" disabled ></td>
                  <td><input type="number" name="conlec" style="width:100%;" value="0" required id=conlec'.$i.' onchange="sumar(t2lec'.$i.',m2lec'.$i.',ttlec'.$i.');restar(totelec'.$i.',ttlec'.$i.',conlec'.$i.',totlec'.$i.');"></td>
                  <td class="bg-secondary text-white"><input type="number" name="totelec" style="width:100%;"  required id=totelec'.$i.' onchange="sumar(t2lec'.$i.',m2lec'.$i.',ttlec'.$i.');restar(totelec'.$i.',ttlec'.$i.',conlec'.$i.',totlec'.$i.');"></td>
                  <td><input type="number" name="totlec" style="width:100%;" id=totlec'.$i.' disabled ></td>
                  
                  <td>
                      <select name="tielec"><option value=2>'.$this->tiepoleche[2].'</option>
                          ' . $this->optielec . '
                      </select>
                  </td>
                  <td><input type="number" name="idemp" value="0"></td>
                  <td><input type="text" name="prulec" maxlength="100" placeholder="Grasa, consistencia"></td><td> 
                  <button type="submit" name="bttnuevo" class="bttnuevo"> <img src="../img/guardar.jpg"> <br>CREAR</button>';
              if($nt!=0){
                echo '<script>document.getElementById("totelec'.$i.'").focus();</script>';
                 $ini++;
            }  
        } else {
             echo'<tr bgcolor=silver>
                <td>' . $fechaActual . '</td>
                <td>TARDE</td>
                <td>
                  '.$grupo['detalle'].'
                </td>';
            // Mostrar campos con los valores de $filaTarde
              $totaltanque=$totaltanque+$filaTarde['totelec']; $totaldia= $totaldia+$filaTarde['totlec'];
             
            echo '<td><input type="number" name="nani" value="' . $filaTarde['nani'] . '" style="width:100%;" required></td>
                
                  <td><input type="number" name="t1lec" style="width:100%;" value="' . $filaTarde['t1lec'] . '" required id=t1lec'.$i.' onchange="sumar(t2lec'.$i.',m2lec'.$i.',ttlec'.$i.');restar(totelec'.$i.',ttlec'.$i.',conlec'.$i.',totlec'.$i.');"></td>
                  <td><input type="number" name="m1lec" style="width:100%;" value="' . $filaTarde['m1lec'] . '" required id=m1lec'.$i.' onchange="sumar(t2lec'.$i.',m2lec'.$i.',ttlec'.$i.');restar(totelec'.$i.',ttlec'.$i.',conlec'.$i.',totlec'.$i.');"></td>
                  <td><input type="number" name="t2lec" style="width:100%;" value="' . $filaTarde['t2lec'] . '" required id=t2lec'.$i.' onchange="sumar(t2lec'.$i.',m2lec'.$i.',ttlec'.$i.');restar(totelec'.$i.',ttlec'.$i.',conlec'.$i.',totlec'.$i.');"></td>
                  <td><input type="number" name="m2lec" style="width:100%;" value="' . $filaTarde['m2lec'] . '" required id=m2lec'.$i.' onchange="sumar(t2lec'.$i.',m2lec'.$i.',ttlec'.$i.');restar(totelec'.$i.',ttlec'.$i.',conlec'.$i.',totlec'.$i.');"></td>
                  <td><input type="number" name="ttlec" style="width:100%;" value="' . $filaTarde['ttlec'] . '" required id=ttlec'.$i.' onchange="sumar(t2lec'.$i.',m2lec'.$i.',ttlec'.$i.');restar(totelec'.$i.',ttlec'.$i.',conlec'.$i.',totlec'.$i.');" disabled ></td>
                  <td><input type="number" name="conlec" style="width:100%;" value="' . $filaTarde['conlec'] . '" required id=conlec'.$i.' onchange="sumar(t2lec'.$i.',m2lec'.$i.',ttlec'.$i.');restar(totelec'.$i.',ttlec'.$i.',conlec'.$i.',totlec'.$i.');"></td>
                  <td class="bg-secondary text-white"><input type="number" name="totelec" style="width:100%;" value="' . $filaTarde['totelec'] . '" required  id=totelec'.$i.' onchange="sumar(t2lec'.$i.',m2lec'.$i.',ttlec'.$i.');restar(totelec'.$i.',ttlec'.$i.',conlec'.$i.',totlec'.$i.');"></td>
                  <td><input type="number" name="totlec" style="width:100%;" value="' . $filaTarde['totlec'] . '"  id=totlec'.$i.' disabled ></td>
                  
                  <td>
                      <select name="tielec"><option value=' . $filaTarde['tielec'] . '>'.$this->tiepoleche[$filaTarde['tielec']].'</option>
                          ' . $this->optielec . '
                      </select>
                  </td>
                  <td><input type="number" name="idemp" value="' . $filaTarde['idemp'] . '"></td>
                      <input type=hidden name=idlec value='.$filaTarde['idlec'].'>
                  <td><input type="text" name="prulec" maxlength="100" placeholder="Grasa, consistencia" value="' . $filaTarde['prulec'] . '">
                     <br> Total día: '.$totaldia.' Total tanque:'.$totaltanque.'  
                      </td><td> 
                      <button type="submit" name="bttmod" class="bttmod2"> <img src="../img/guardar.jpg"> <br>MODIFICAR</button>';
        }
        echo '</tr></form>';

        $fechaInicio->add($interval);
    }

    echo '</table>
        
    </center>';
}

private function existeRegistroEnFechaTipo($fecha, $tipo,$idgru) {
    $sql = 'SELECT * FROM "LECHE" ,"USUARIOS" WHERE "USUARIOS".id=idusu and "LECHE".idgru='.$idgru.'   and feclec = \'' . $fecha . '\' AND tielec = ' . $tipo . '  and idhac='.$_SESSION['idhac'];
   //echo $sql; 
   $con = $this->consulta($sql);
    $count = $this->row($con);

    return $count;
}
public function obtenerLitrosTanque($idhac, $milimetros) {
    // Consultar el valor de litros para un milimetraje específico basado en el idhac
    $sql = "SELECT dt.litros FROM \"DETALLE_TANQUE\" dt 
            INNER JOIN \"TANQUE\" t ON t.id = dt.tanque_id 
            WHERE t.idhac = $idhac AND dt.milimetros = $milimetros LIMIT 1";
    $resultado = $this->consulta($sql);

    if ($fila = pg_fetch_assoc($resultado)) {
        return $fila['litros'];
    } else {
        return null; // Devuelve null si no se encuentra la medida
    }
} 

public function regleche() {
    $empleados = $this->empleadosSelect();
    $litrosTerneras = $_SESSION['litros_terneras'];
    $litrosMachos = $_SESSION['litros_machos'];
    
    echo '<div class="container">';
    echo '<h1 class="text-center mb-4">Registro de Leche</h1>';
    echo '<form method="POST" action="">';

    // Grupo
    echo '<div class="form-group">';
    echo '<label for="idgru" class="form-label">Grupo</label>';
    echo '<select name="idgru" id="idgru" class="form-control">' . $this->listarGruposOptionTotalLechera() . '</select>';
    echo '</div>';

    $cantidadRejo = $this->cantidadRejo($_SESSION['idhac']);
    // Obtener el último registro
$ultimoRegistro = $this->obtenerUltimoRegistro($_SESSION['idhac']);
$ultimaMedida = isset($ultimoRegistro['medida_tanque']) ? $ultimoRegistro['medida_tanque'] : 0;
$ultimaMedidaAnterior = isset($ultimoRegistro['medida_anterior_tanque']) ? $ultimoRegistro['medida_anterior_tanque'] : 0;
$t1lec = isset($ultimoRegistro['n_terneras']) ? $ultimoRegistro['n_terneras'] : 0;
$m1lec = isset($ultimoRegistro['n_terneros']) ? $ultimoRegistro['n_terneros'] : 0;
$ultimaFecha = isset($ultimoRegistro['feclec']) ? $ultimoRegistro['feclec'] : 'SIN FECHA';
$ultimoTipo = isset($ultimoRegistro['tielec']) ? $ultimoRegistro['tielec'] : 0; // Índice del tipo
 $ultimoTipoSiguiente=1;
    if($ultimoTipo==1)
    $ultimoTipoSiguiente=2;
    
$litrosanterior= $this->obtenerLitrosTanque($_SESSION['idhac'], $ultimaMedida);
$t2lec=$t1lec*$litrosTerneras;
$m2lec=$m1lec*$litrosMachos;
$tleches=$t2lec+$m2lec;
// Mostrar datos del último registro
echo '<div class="alert alert-info">';
echo '<strong>Último Registro ingresado:</strong><br>';
echo 'Fecha: ' . $ultimaFecha . '<br>';
echo 'Tipo: ' . $this->tiepoleche[$ultimoTipo];

echo '<br>Llevar leche terneros: '.$tleches;

echo '</div>';

    // Fecha
    echo '<div class="form-group">';
    echo '<label for="feclec" class="form-label">Fecha</label>';
    echo '<input type="date" name="feclec" id="feclec" class="form-control" value="' . date("Y-m-d") . '">';
    echo '</div>';

    // CSS para separadores y colores
    echo '<style>';
    echo '.form-group { margin-bottom: 15px; padding: 10px; border-radius: 5px; }';
    echo '.section-group { border: 1px solid #007bff; padding: 15px; margin-bottom: 20px; border-radius: 5px; }';
    echo '.section-group-title { font-weight: bold; color: #007bff; margin-bottom: 10px; }';
    echo '</style>';

    
    // Campo adicional: Medida del Tanque
    echo '<div class="section-group">';
    echo '<div class="section-group-title">Medida del Tanque</div>';
    echo '<div class="form-group">';
    echo '<label for="medida_anterior" class="form-label">Medida Anterior (mm)</label>';
    echo '<input type="text"  id="medida_anterior" class="form-control" value="'.$ultimaMedida.'mm = '.$litrosanterior.' litros" disabled>';
    echo '<input type="hidden" name="medida_anterior" class="form-control" value="'.$ultimaMedida.'" disabled>';
    echo '</div>';
    echo '<div class="form-group">';
    echo '<label for="medida_actual" class="form-label">Regla medida Actual (mm) </label>';
    echo '<input type="number" name="medida_actual" id="medida_actual" class="form-control" value="" autofocus onchange="sumar(t2lec,m2lec,ttlec);restar(totelec,ttlec,conlec,totlec);">';
    echo '</div>';
    echo '<div class="form-group">';
    echo '<label for="totelect" class="form-label">Litros (Calculado)</label>';
    echo '<input type="text" name="totelect" id="totelect" class="form-control" value="0" disabled>';
     echo '<label for="Toma" class="form-label">Tipo Entrega:</label>';
         echo '<br><select name=tielec class="form-control">
             <option value='.$ultimoTipoSiguiente.'>'.$this->tiepoleche[$ultimoTipoSiguiente].'</option>
                        <option value=1>MAÑANA</option>
                        <option value=2>TARDE</option>
                        </select>';
    echo '</div>';
    echo '</div>';
   
    $meses=6;
    $edadterneras=$this->contarAnimalesPorEdadYSexo($meses, 1);
    $edadmachos=$this->contarAnimalesPorEdadYSexo($meses, 2);;
    // Secciones del formulario
    $sections = [
        "Datos Generales" => [
            ["# Vacas", "nani", $cantidadRejo, false],
            ["# Terneras (".$edadterneras.") menores a ".$meses." meses", "t1lec", $t1lec, false, false, "sumar(t2lec,m2lec,ttlec);restar(totelec,ttlec,conlec,totlec);"],
            ["# Machos (".$edadmachos.") menores a ".$meses." meses", "m1lec", $m1lec, false, false, "sumar(t2lec,m2lec,ttlec);restar(totelec,ttlec,conlec,totlec);"],
        ],
        "Producción de Leche" => [
            ["Leche Terneras (Litros)", "t2lec", $t2lec, false, false, "sumar(t2lec,m2lec,ttlec);restar(totelec,ttlec,conlec,totlec);"],
            ["Leche Machos (Litros)", "m2lec", $m2lec, false, false, "sumar(t2lec,m2lec,ttlec);restar(totelec,ttlec,conlec,totlec);"],
            ["Total Terneros (Litros)", "ttlec", ($t2lec+$m2lec), false, true], // Disabled
        ],
        "Consumo y Totales" => [
            ["Consumo (Litros)", "conlec", 0, false, false, "restar(totelec,ttlec,conlec,totlec);"],
            ["Total entrega (Litros)", "totelec", "", false, false, "sumar(t2lec,m2lec,ttlec);restar(totelec,ttlec,conlec,totlec);"],
            ["Total Producción (Litros)", "totlec", "", false, true], // Disabled
        ],
    ];
   
    // Renderización por secciones
    foreach ($sections as $sectionTitle => $fields) {
        echo '<div class="section-group">';
        echo '<div class="section-group-title">' . $sectionTitle . '</div>';
        
        
        foreach ($fields as $field) {
            echo '<div class="form-group">';
            echo '<label for="' . $field[1] . '" class="form-label">' . $field[0] . '</label>';
            echo '<input type="number" name="' . $field[1] . '" id="' . $field[1] . '" class="form-control" value="' . $field[2] . '" '
                . (isset($field[3]) && $field[3] ? 'autofocus' : '') // Agrega autofocus si está configurado
                . (isset($field[4]) && $field[4] ? ' disabled' : '') // Agrega disabled si está configurado
                . (isset($field[5]) ? ' onchange="' . $field[5] . '"' : '') // Agrega onchange si está configurado
                . '>';
            echo '</div>';
        }
        echo '</div>';
    }

    // Botón de guardar
    echo '<div class="text-center">';
    echo '<button name="bttnuevoregleche" class="btn btn-success btn-guardar">';
    echo '<img src="../img/guardar.jpg" alt="Guardar"><br>GUARDAR';
    echo '</button>';
    echo '</div>';

    echo '</form>';
    echo '</div>';
    
    // JavaScript para calcular litros al modificar la medida actual
echo '<script>';
echo 'document.getElementById("medida_actual").addEventListener("input", function() {';
echo '    const medidaActual = parseInt(this.value) || 0;';
echo '    const medidaAnterior = parseInt('.$litrosanterior.') || 0;';
echo '    if (medidaActual > 0) {';
echo '        fetch("obtener_litros_tanque.php", {';
echo '            method: "POST",';
echo '            headers: { "Content-Type": "application/x-www-form-urlencoded" },';
echo '            body: `milimetros=${medidaActual}&idhac=' . $_SESSION['idhac'] . '`';
echo '        })';
echo '        .then(response => response.json())';
echo '        .then(data => {'; 
echo '            if (data.litros) {'; 
echo '                let litrosCalculados;';
echo '                if (medidaAnterior >= data.litros) {'; // Si la medida anterior es mayor o igual
echo '                    litrosCalculados = data.litros;';
//echo '                    document.getElementById("totelect").value = `${data.litros} (litros tanque) - IGNORADO = ${litrosCalculados} litros`;';
echo '                    document.getElementById("totelect").value = `${litrosCalculados} litros = ${data.litros} (litros tanque) - IGNORADO `;';

echo '                } else {'; // Si la medida anterior es menor
echo '                    litrosCalculados = data.litros - medidaAnterior;';
echo '                    document.getElementById("totelect").value = `${litrosCalculados} litros = ${data.litros} (litros tanque) - ${medidaAnterior} (litros Anterior)`;';
echo '                }';
echo '                document.getElementById("totelec").value = litrosCalculados;';
echo '            } else {'; 
echo '                document.getElementById("totelect").value = "No se pudo calcular los litros.";';
echo '                document.getElementById("totelec").value = 0;';
echo '            }';
echo '        })';
echo '        .catch(error => {';
echo '            console.error("Error:", error);';
echo '            document.getElementById("totelect").value = "Error al procesar la solicitud.";';
echo '            document.getElementById("totelec").value = 0;';
echo '        });';
echo '    } else {'; 
echo '        document.getElementById("totelect").value = "Ingrese una medida válida.";';
echo '        document.getElementById("totelec").value = 0;';
echo '    }';
echo '});';
echo '</script>';

    
       // JavaScript
    echo '<br><script>';
    echo 'const litrosTerneras = ' . $litrosTerneras . ';';
    echo 'const litrosMachos = ' . $litrosMachos . ';';
    echo 'document.getElementById("t1lec").addEventListener("input", function() {';
    echo '    const terneras = parseInt(this.value) || 0;';
    echo '    document.getElementById("t2lec").value = terneras * litrosTerneras;';
    echo '});';
    echo 'document.getElementById("m1lec").addEventListener("input", function() {';
    echo '    const machos = parseInt(this.value) || 0;';
    echo '    document.getElementById("m2lec").value = machos * litrosMachos;';
    echo '});';
    echo '</script><br>';
}



public function contarAnimalesPorEdadYSexo($meses, $sexani) {
    // Calcular la fecha límite con base en la cantidad de meses
    $fechaLimite = date('Y-m-d', strtotime("-{$meses} months"));

    // Consulta SQL para contar los animales que cumplen los criterios
    $sql = "
        SELECT COUNT(*) AS cantidad
        FROM \"ANIMALES\"
        WHERE esthac = 1 
          AND espani = 1 
          AND sexani = $sexani 
          AND fecnac >= '$fechaLimite';
    ";

    // Ejecutar la consulta
    $resultado = $this->consulta($sql);

    // Obtener el resultado
    if ($fila = pg_fetch_assoc($resultado)) {
        return (int)$fila['cantidad'];
    } else {
        return 0; // Si no hay resultados, devolver 0
    }
}
public function obtenerUltimoRegistroFecha($idhac,$feclec) {
    $sql = "
        SELECT l.medida_tanque, l.medida_anterior_tanque, l.t1lec AS n_terneras, l.m1lec AS n_terneros, 
               l.feclec, l.tielec, l.tielec 
        FROM \"LECHE\" l
        INNER JOIN \"GRUPO\" g ON l.idgru = g.id
        WHERE g.idhac = $idhac and feclec<='".$feclec."'
        ORDER BY l.feclec DESC, l.idlec DESC
        LIMIT 1
    ";

    $resultado = $this->consulta($sql);

    if ($fila = pg_fetch_assoc($resultado)) {
        return $fila; // Devuelve los datos como un array asociativo
    } else {
        return null; // No hay registros
    }
}
public function obtenerUltimoRegistro($idhac) {
    $sql = "
        SELECT l.medida_tanque, l.medida_anterior_tanque, l.t1lec AS n_terneras, l.m1lec AS n_terneros, 
               l.feclec, l.tielec, l.tielec 
        FROM \"LECHE\" l
        INNER JOIN \"GRUPO\" g ON l.idgru = g.id
        WHERE g.idhac = $idhac
        ORDER BY l.feclec DESC, l.idlec DESC
        LIMIT 1
    ";

    $resultado = $this->consulta($sql);

    if ($fila = pg_fetch_assoc($resultado)) {
        return $fila; // Devuelve los datos como un array asociativo
    } else {
        return null; // No hay registros
    }
}

}
