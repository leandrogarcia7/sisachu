<?php
require_once("USUARIO.php");

/**
 * Clase HACIENDA - Gestión de haciendas y reportes
 * @author Leandro García
 */
class HACIENDA extends USUARIO {
    /**
     * Mostrar el formulario de configuración de la hacienda.
     *
     * Este formulario permite editar la información general de la hacienda
     * (como razón social o dirección matriz) y los parámetros necesarios
     * para la facturación electrónica, así como cargar el certificado
     * digital (.p12) y su clave.  Utiliza los valores almacenados en la
     * base de datos para pre‑llenar los campos, de modo que el usuario
     * pueda modificarlos según sea necesario.
     */
    public function mostrarFormularioHacienda() {
        // Obtener el ID de hacienda desde la sesión
        $idhac = isset($_SESSION['idhac']) ? $_SESSION['idhac'] : 0;
        $hacienda = $this->obtenerHacienda($idhac);

        // Asignar valores actuales o por defecto
        $ambiente        = isset($hacienda['ambiente']) ? $hacienda['ambiente'] : '1';
        $tipoEmision     = isset($hacienda['tipo_emision']) ? $hacienda['tipo_emision'] : '1';
        $razonSocial     = isset($hacienda['razon_social']) ? $hacienda['razon_social'] : '';
        $nombreComercial = isset($hacienda['nombre_comercial']) ? $hacienda['nombre_comercial'] : '';
        $ruc             = isset($hacienda['ruc']) ? $hacienda['ruc'] : '';
        $codDoc          = isset($hacienda['cod_doc']) ? $hacienda['cod_doc'] : '01';
        $estab           = isset($hacienda['estab']) ? $hacienda['estab'] : '';
        $ptoEmi          = isset($hacienda['pto_emi']) ? $hacienda['pto_emi'] : '';
        $secuencial      = isset($hacienda['secuencial']) ? $hacienda['secuencial'] : '';
        $dirMatriz       = isset($hacienda['dir_matriz']) ? $hacienda['dir_matriz'] : '';
        $obligadoCont    = isset($hacienda['obligado_contabilidad']) ? $hacienda['obligado_contabilidad'] : 'NO';
        $certActual      = isset($hacienda['certificado_path']) ? $hacienda['certificado_path'] : '';
        $claveCert       = isset($hacienda['certificado_clave']) ? $hacienda['certificado_clave'] : '';

        // Inicio del formulario
        echo '<form method="post" enctype="multipart/form-data">';
        echo '<div class="row">';

        // Ambiente
        echo '<div class="col-md-4 mb-3">';
        echo '<label for="ambiente" class="form-label">Ambiente</label>';
        echo '<select name="ambiente" id="ambiente" class="form-control" required>';
        echo '<option value="1"'.($ambiente == '1' ? ' selected' : '').'>Pruebas</option>';
        echo '<option value="2"'.($ambiente == '2' ? ' selected' : '').'>Producción</option>';
        echo '</select>';
        echo '</div>';

        // Tipo de emisión
        echo '<div class="col-md-4 mb-3">';
        echo '<label for="tipo_emision" class="form-label">Tipo de Emisión</label>';
        echo '<select name="tipo_emision" id="tipo_emision" class="form-control" required>';
        echo '<option value="1"'.($tipoEmision == '1' ? ' selected' : '').'>Normal</option>';
        echo '<option value="2"'.($tipoEmision == '2' ? ' selected' : '').'>Contingencia</option>';
        echo '</select>';
        echo '</div>';

        // Código de documento
        echo '<div class="col-md-4 mb-3">';
        echo '<label for="cod_doc" class="form-label">Código de Documento</label>';
        echo '<input type="text" name="cod_doc" id="cod_doc" class="form-control" value="'.htmlspecialchars($codDoc).'" maxlength="2" required />';
        echo '</div>';

        // Razón social
        echo '<div class="col-md-6 mb-3">';
        echo '<label for="razon_social" class="form-label">Razón Social</label>';
        echo '<input type="text" name="razon_social" id="razon_social" class="form-control" value="'.htmlspecialchars($razonSocial).'" required />';
        echo '</div>';

        // Nombre comercial
        echo '<div class="col-md-6 mb-3">';
        echo '<label for="nombre_comercial" class="form-label">Nombre Comercial</label>';
        echo '<input type="text" name="nombre_comercial" id="nombre_comercial" class="form-control" value="'.htmlspecialchars($nombreComercial).'" required />';
        echo '</div>';

        // RUC
        echo '<div class="col-md-4 mb-3">';
        echo '<label for="ruc" class="form-label">RUC</label>';
        echo '<input type="text" name="ruc" id="ruc" class="form-control" value="'.htmlspecialchars($ruc).'" maxlength="13" required />';
        echo '</div>';

        // Establecimiento
        echo '<div class="col-md-4 mb-3">';
        echo '<label for="estab" class="form-label">Establecimiento</label>';
        echo '<input type="text" name="estab" id="estab" class="form-control" value="'.htmlspecialchars($estab).'" maxlength="3" required />';
        echo '</div>';

        // Punto de emisión
        echo '<div class="col-md-4 mb-3">';
        echo '<label for="pto_emi" class="form-label">Punto de Emisión</label>';
        echo '<input type="text" name="pto_emi" id="pto_emi" class="form-control" value="'.htmlspecialchars($ptoEmi).'" maxlength="3" required />';
        echo '</div>';

        // Secuencial
        echo '<div class="col-md-4 mb-3">';
        echo '<label for="secuencial" class="form-label">Secuencial</label>';
        echo '<input type="text" name="secuencial" id="secuencial" class="form-control" value="'.htmlspecialchars($secuencial).'" maxlength="9" required />';
        echo '</div>';

        // Dirección matriz
        echo '<div class="col-md-8 mb-3">';
        echo '<label for="dir_matriz" class="form-label">Dirección Matriz</label>';
        echo '<input type="text" name="dir_matriz" id="dir_matriz" class="form-control" value="'.htmlspecialchars($dirMatriz).'" required />';
        echo '</div>';

        // Obligado a llevar contabilidad
        echo '<div class="col-md-4 mb-3">';
        echo '<label for="obligado_contabilidad" class="form-label">Obligado a llevar contabilidad</label>';
        echo '<select name="obligado_contabilidad" id="obligado_contabilidad" class="form-control" required>';
        echo '<option value="SI"'.($obligadoCont == 'SI' ? ' selected' : '').'>SI</option>';
        echo '<option value="NO"'.($obligadoCont == 'NO' ? ' selected' : '').'>NO</option>';
        echo '</select>';
        echo '</div>';

        // Certificado digital (.p12)
        echo '<div class="col-md-8 mb-3">';
        echo '<label class="form-label">Certificado Digital (.p12)</label>';
        if(!empty($certActual)) {
            echo '<p class="form-text">Actualmente: <a href="../'.htmlspecialchars($certActual).'" target="_blank">'.htmlspecialchars(basename($certActual)).'</a></p>';
        }
        echo '<input type="file" name="certificado_p12" class="form-control" accept=".p12" />';
        echo '</div>';

        // Clave del certificado
        echo '<div class="col-md-4 mb-3">';
        echo '<label for="certificado_clave" class="form-label">Clave del Certificado</label>';
        echo '<input type="password" name="certificado_clave" id="certificado_clave" class="form-control" value="'.htmlspecialchars().'" />';
        echo '</div>';

        echo '</div>';
        echo '<button type="submit" name="bttActualizarHacienda" class="btn btn-primary">Guardar Cambios</button>';
        echo '</form>';
    }

    /**
     * Actualiza la información de la hacienda según los datos recibidos del formulario.
     * Maneja la carga del certificado digital y construye la sentencia SQL
     * dinámicamente para evitar errores de sintaxis (p. ej. comas sobrantes).
     *
     * @param array $data Datos enviados por el usuario (generalmente $_POST)
     */
    public function actualizarHacienda($data) {
    // Verificar sesión y obtener identificador de hacienda
    $idhac = isset($_SESSION['idhac']) ? $_SESSION['idhac'] : 0;
    if ($idhac == 0) {
        throw new Exception('No se ha definido la hacienda a actualizar');
    }

    // Sanitizar las entradas
    $ambiente        = isset($data['ambiente']) ? addslashes(trim($data['ambiente'])) : '1';
    $tipoEmision     = isset($data['tipo_emision']) ? addslashes(trim($data['tipo_emision'])) : '1';
    $razonSocial     = isset($data['razon_social']) ? addslashes(trim($data['razon_social'])) : '';
    $nombreComercial = isset($data['nombre_comercial']) ? addslashes(trim($data['nombre_comercial'])) : '';
    $ruc             = isset($data['ruc']) ? addslashes(trim($data['ruc'])) : '';
    $codDoc          = isset($data['cod_doc']) ? addslashes(trim($data['cod_doc'])) : '';
    $estab           = isset($data['estab']) ? addslashes(trim($data['estab'])) : '';
    $ptoEmi          = isset($data['pto_emi']) ? addslashes(trim($data['pto_emi'])) : '';
    $secuencial      = isset($data['secuencial']) ? addslashes(trim($data['secuencial'])) : '';
    $dirMatriz       = isset($data['dir_matriz']) ? addslashes(trim($data['dir_matriz'])) : '';
    $obligadoCont    = isset($data['obligado_contabilidad']) ? addslashes(trim($data['obligado_contabilidad'])) : 'NO';
    $claveCertificado= isset($data['certificado_clave']) ? trim($data['certificado_clave']) : '';

    // Obtener datos actuales
    $actual = $this->obtenerHacienda($idhac);
    $certificadoPath = isset($actual['certificado_path']) ? $actual['certificado_path'] : '';
    $claveExistente  = isset($actual['certificado_clave']) ? $actual['certificado_clave'] : '';

    // Manejo de subida de archivo
    if (isset($_FILES['certificado_p12']) && isset($_FILES['certificado_p12']['tmp_name']) && $_FILES['certificado_p12']['error'] === UPLOAD_ERR_OK) {
        $tmpName  = $_FILES['certificado_p12']['tmp_name'];
        $fileName = basename($_FILES['certificado_p12']['name']);
        // Crear directorio de certificados si no existe
        $dirDestino = dirname(__DIR__) . '/certificados';
        if (!file_exists($dirDestino)) {
            mkdir($dirDestino, 0777, true);
        }
        // Generar un nombre único
        $nuevoNombre = $idhac . '_' . date('YmdHis') . '_' . preg_replace('/[^A-Za-z0-9_\.]/', '', $fileName);
        $rutaDestino = $dirDestino . '/' . $nuevoNombre;
        if (move_uploaded_file($tmpName, $rutaDestino)) {
            $certificadoPath = 'certificados/' . $nuevoNombre;
        }
    }

    // Construir lista de actualizaciones dinámicamente
    $updates = array();
    $updates[] = "ambiente = '" . $ambiente . "'";
    $updates[] = "tipo_emision = '" . $tipoEmision . "'";
    $updates[] = "razon_social = '" . $razonSocial . "'";
    $updates[] = "nombre_comercial = '" . $nombreComercial . "'";
    $updates[] = "ruc = '" . $ruc . "'";
    $updates[] = "cod_doc = '" . $codDoc . "'";
    $updates[] = "estab = '" . $estab . "'";
    $updates[] = "pto_emi = '" . $ptoEmi . "'";
    $updates[] = "secuencial = '" . $secuencial . "'";
    $updates[] = "dir_matriz = '" . $dirMatriz . "'";
    $updates[] = "obligado_contabilidad = '" . $obligadoCont . "'";

    // Actualizar certificado solo si se sube uno nuevo
    if (!empty($certificadoPath)) {
        $updates[] = "certificado_path = '" . $certificadoPath . "'";
    }

    // Actualizar clave solo si se envía una nueva
    if ($claveCertificado !== '') {
        $updates[] = "certificado_clave = '" . addslashes($claveCertificado) . "'";
    } else {
        // Mantener la clave existente
        if (!empty($claveExistente)) {
            $updates[] = "certificado_clave = '" . addslashes($claveExistente) . "'";
        }
    }

    // Ejecutar UPDATE
    $query = 'UPDATE "HACIENDA" SET ' . implode(', ', $updates) . ' WHERE id = ' . $idhac;
    // echo $query; // útil para depurar
    $this->consulta($query);
}

    
    /**
     * Obtener información básica de la hacienda
     */
    public function obtenerHacienda($id) {
        $query = 'SELECT * FROM "HACIENDA" WHERE id = '.$id;
        $con = $this->consulta($query);
        if ($reg = $this->row($con)) {
            return $reg;
        }
        return false;
    }
    
    /**
     * Mostrar dashboard principal de la hacienda
     */
    public function mostrarDashboard() {
        $idhac = $_SESSION['idhac'];
        $hacienda = $this->obtenerHacienda($idhac);
        
        echo '<div class="container-fluid">';
        echo '<div class="row mb-4">';
        echo '<div class="col-12">';
        echo '<div class="card bg-primary text-white">';
        echo '<div class="card-body">';
        echo '<h2><i class="fas fa-home"></i> '.($hacienda ? $hacienda['nomhac'] : 'HACIENDA').'</h2>';
        echo '<p>Panel de Control - '.date('d/m/Y').'</p>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        
        // Mostrar estadísticas
        $this->mostrarEstadisticas();
        
        // Mostrar alertas
       // $this->mostrarAlertas();
        
        // Mostrar actividad reciente
        echo '<div class="row mt-4">';
        echo '<div class="col-md-6">';
        echo '<div class="card">';
        echo '<div class="card-header">';
        echo '<h5><i class="fas fa-clock"></i> Actividad Reciente</h5>';
        echo '</div>';
        echo '<div class="card-body">';
        echo $this->obtenerActividadReciente();
        echo '</div>';
        echo '</div>';
        echo '</div>';
        
        echo '<div class="col-md-6">';
        echo '<div class="card">';
        echo '<div class="card-header">';
        echo '<h5><i class="fas fa-tasks"></i> Tareas Pendientes</h5>';
        echo '</div>';
        echo '<div class="card-body">';
        echo $this->obtenerTareasPendientes();
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        
        echo '</div>';
    }
    
    /**
     * Mostrar estadísticas principales
     */
    public function mostrarEstadisticas() {
        $stats = $this->obtenerEstadisticas();
        
        echo '<div class="row">';
        
        // Total de animales
        echo '<div class="col-md-3">';
        echo '<div class="card bg-info text-white">';
        echo '<div class="card-body text-center">';
        echo '<h3>'.$stats['total_animales'].'</h3>';
        echo '<p><i class="fas fa-cow"></i> Total Animales</p>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        
        // Producción de leche hoy
        echo '<div class="col-md-3">';
        echo '<div class="card bg-success text-white">';
        echo '<div class="card-body text-center">';
        echo '<h3>'.$stats['leche_hoy'].' L</h3>';
        echo '<p><i class="fas fa-tint"></i> Leche Hoy</p>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        
        // Entregas hoy
        echo '<div class="col-md-3">';
        echo '<div class="card bg-warning text-white">';
        echo '<div class="card-body text-center">';
        echo '<h3>'.$stats['entregas_hoy'].'</h3>';
        echo '<p><i class="fas fa-truck"></i> Entregas Hoy</p>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        
        // Reproducciones activas
        echo '<div class="col-md-3">';
        echo '<div class="card bg-danger text-white">';
        echo '<div class="card-body text-center">';
        echo '<h3>'.$stats['reproducciones_pendientes'].'</h3>';
        echo '<p><i class="fas fa-heart"></i> Reproducciones</p>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        
        echo '</div>';
    }
    
    /**
     * Obtener estadísticas de la hacienda
     */
    public function obtenerEstadisticas() {
        $idhac = $_SESSION['idhac'];
        $stats = array();
        $fechaHoy = date('Y-m-d');
        // Total de animales
        $query = 'SELECT COUNT(*) as total FROM "ANIMALES" WHERE idhac = '.$idhac.' AND esthac = 1';
        $con = $this->consulta($query);
        $reg = $this->row($con);
        $stats['total_animales'] = $reg['total'];
        
        // Leche de hoy
        $query = 'SELECT COALESCE(SUM(l.totlec), 0) as total 
                  FROM "LECHE" l
                  INNER JOIN "GRUPO" g ON l.idgru = g.id
                  WHERE g.idhac = '.$idhac.' AND l.feclec = \'' . $fechaHoy . '\'';
        $con = $this->consulta($query);
        $reg = $this->row($con);
        $stats['leche_hoy'] = $reg['total'];
        
        // Entregas de hoy
        $query = 'SELECT COUNT(*) as total FROM "ENTREGA" WHERE idhac = '.$idhac.' AND fecent = \'' . $fechaHoy . '\'';
        $con = $this->consulta($query);
        $reg = $this->row($con);
        $stats['entregas_hoy'] = $reg['total'];
        
        // Reproducciones pendientes
        $query = 'SELECT COUNT(*) as total 
                  FROM "REPRODUCCION" r
                  INNER JOIN "ANIMALES" a ON r.idmadre = a.id
                  WHERE a.idhac = '.$idhac.' AND r.tipres = 0';
        $con = $this->consulta($query);
        $reg = $this->row($con);
        $stats['reproducciones_pendientes'] = $reg['total'];
        
        return $stats;
    }
    
    /**
     * Generar reporte mensual
     */
    public function generarReporteMensual($mes, $anio) {
        $reporte = array();
        $idhac = $_SESSION['idhac'];
        
        // Animales
        $query = 'SELECT esthac, COUNT(*) as total FROM "ANIMALES" WHERE idhac = '.$idhac.' GROUP BY esthac';
        $con = $this->consulta($query);
        while($reg = $this->row($con)) {
            $reporte['animales'][$reg['esthac']] = $reg['total'];
        }
        
        // Leche del mes
        $query = 'SELECT 
                    COALESCE(SUM(totlec), 0) as total_leche,
                    COUNT(*) as total_ordenos,
                    COALESCE(AVG(totlec), 0) as promedio_diario
                  FROM "LECHE" l
                  INNER JOIN "GRUPO" g ON l.idgru = g.id
                  WHERE g.idhac = '.$idhac.'
                  AND EXTRACT(MONTH FROM feclec) = '.$mes.'
                  AND EXTRACT(YEAR FROM feclec) = '.$anio;
        $con = $this->consulta($query);
        $reporte['leche'] = $this->row($con);
        
        // Entregas del mes
        $query = 'SELECT 
                    COALESCE(SUM(totlit), 0) as total_litros,
                    COALESCE(SUM(totent), 0) as total_valor,
                    COUNT(*) as total_entregas
                  FROM "ENTREGA"
                  WHERE idhac = '.$idhac.'
                  AND EXTRACT(MONTH FROM fecent) = '.$mes.'
                  AND EXTRACT(YEAR FROM fecent) = '.$anio;
        $con = $this->consulta($query);
        $reporte['entregas'] = $this->row($con);
        
        // Ingresos del mes
        $query = 'SELECT 
                    ti.detti as tipo,
                    COALESCE(SUM(i.montoing), 0) as total
                  FROM "INGRESO" i
                  INNER JOIN "TIPO_INGRESO" ti ON i.idtipi = ti.id
                  WHERE ti.idhac = '.$idhac.'
                  AND EXTRACT(MONTH FROM i.fecing) = '.$mes.'
                  AND EXTRACT(YEAR FROM i.fecing) = '.$anio.'
                  GROUP BY ti.detti';
        $con = $this->consulta($query);
        $reporte['ingresos'] = array();
        while($reg = $this->row($con)) {
            $reporte['ingresos'][] = $reg;
        }
        
        // Egresos del mes
        $query = 'SELECT 
                    te.dette as tipo,
                    COALESCE(SUM(e.montoegr), 0) as total
                  FROM "EGRESO" e
                  INNER JOIN "TIPO_EGRESO" te ON e.idtipe = te.id
                  WHERE te.idhac = '.$idhac.'
                  AND EXTRACT(MONTH FROM e.fecegr) = '.$mes.'
                  AND EXTRACT(YEAR FROM e.fecegr) = '.$anio.'
                  GROUP BY te.dette';
        $con = $this->consulta($query);
        $reporte['egresos'] = array();
        while($reg = $this->row($con)) {
            $reporte['egresos'][] = $reg;
        }
        
        // Reproducciones del mes
        $query = 'SELECT 
                    r.tipres,
                    COUNT(*) as total
                  FROM "REPRODUCCION" r
                  INNER JOIN "ANIMALES" a ON r.idmadre = a.id
                  WHERE a.idhac = '.$idhac.'
                  AND (EXTRACT(MONTH FROM r.fecpro) = '.$mes.' OR EXTRACT(MONTH FROM r.fecres) = '.$mes.')
                  AND (EXTRACT(YEAR FROM r.fecpro) = '.$anio.' OR EXTRACT(YEAR FROM r.fecres) = '.$anio.')
                  GROUP BY r.tipres';
        $con = $this->consulta($query);
        $reporte['reproducciones'] = array();
        while($reg = $this->row($con)) {
            $reporte['reproducciones'][$reg['tipres']] = $reg['total'];
        }
        
        return $reporte;
    }
    
    /**
     * Mostrar reporte mensual en formato HTML
     */
    public function mostrarReporteMensual($reporte, $mes, $anio) {
        $meses = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                      'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
        $esthac = array('POR REGISTRAR','NORMAL','VENDIDO','MUERTO','PERDIDO', 'FUERA DE PREDIO');
        $tipres = array('Por revisar','Vacia','Aborto','Parto','Confirmado');
        
        echo '<div class="container-fluid mt-4">';
        echo '<div class="card">';
        echo '<div class="card-header bg-primary text-white">';
        echo '<h4><i class="fas fa-chart-line"></i> Reporte Mensual - '.$meses[$mes].' '.$anio.'</h4>';
        echo '</div>';
        echo '<div class="card-body">';
        
        // Resumen de Animales
        echo '<div class="row mb-4">';
        echo '<div class="col-md-6">';
        echo '<h5><i class="fas fa-cow"></i> Resumen de Animales</h5>';
        if(isset($reporte['animales']) && !empty($reporte['animales'])) {
            echo '<div class="table-responsive">';
            echo '<table class="table table-striped">';
            echo '<thead><tr><th>Estado</th><th>Cantidad</th></tr></thead>';
            echo '<tbody>';
            $total_animales = 0;
            foreach($reporte['animales'] as $estado => $cantidad) {
                echo '<tr><td>'.$esthac[$estado].'</td><td><span class="badge badge-primary">'.$cantidad.'</span></td></tr>';
                $total_animales += $cantidad;
            }
            echo '<tr class="table-info"><td><strong>Total</strong></td><td><strong>'.$total_animales.'</strong></td></tr>';
            echo '</tbody></table>';
            echo '</div>';
        } else {
            echo '<p class="text-muted">No hay datos de animales para este período</p>';
        }
        echo '</div>';
        
        // Producción de Leche
        echo '<div class="col-md-6">';
        echo '<h5><i class="fas fa-tint"></i> Producción de Leche</h5>';
        if(isset($reporte['leche']) && $reporte['leche']['total_leche'] > 0) {
            echo '<div class="row">';
            echo '<div class="col-md-4"><div class="card bg-info text-white text-center"><div class="card-body"><h4>'.number_format($reporte['leche']['total_leche']).'</h4><small>Total Litros</small></div></div></div>';
            echo '<div class="col-md-4"><div class="card bg-success text-white text-center"><div class="card-body"><h4>'.$reporte['leche']['total_ordenos'].'</h4><small>Ordeños</small></div></div></div>';
            echo '<div class="col-md-4"><div class="card bg-warning text-white text-center"><div class="card-body"><h4>'.number_format($reporte['leche']['promedio_diario'], 1).'</h4><small>Promedio Diario</small></div></div></div>';
            echo '</div>';
        } else {
            echo '<p class="text-muted">No hay registros de producción de leche para este período</p>';
        }
        echo '</div>';
        echo '</div>';
        
        // Resto del reporte...
        echo '<div class="row mt-4">';
        echo '<div class="col-md-12 text-center">';
        echo '<button class="btn btn-success" onclick="window.print()"><i class="fas fa-print"></i> Imprimir Reporte</button>';
        echo '</div>';
        echo '</div>';
        
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
    
    /**
     * Mostrar alertas de la hacienda
     */
    public function mostrarAlertas() {
        $alertas = array();
        $idhac = $_SESSION['idhac'];
        
        // Reproducciones por revisar
        $query = 'SELECT COUNT(*) as total 
                  FROM "REPRODUCCION" r
                  INNER JOIN "ANIMALES" a ON r.idmadre = a.id
                  WHERE a.idhac = '.$idhac.'
                  AND r.tipres = 0
                  AND r.fecrev <= CURRENT_DATE';
        $con = $this->consulta($query);
        $reg = $this->row($con);
        if($reg['total'] > 0) {
            $alertas[] = array(
                'tipo' => 'info',
                'mensaje' => 'Hay '.$reg['total'].' reproducciones pendientes de revisión',
                'icono' => 'fa-heart'
            );
        }
        
        // Partos próximos (próximos 15 días)
        $query = 'SELECT COUNT(*) as total 
                  FROM "REPRODUCCION" r
                  INNER JOIN "ANIMALES" a ON r.idmadre = a.id
                  WHERE a.idhac = '.$idhac.'
                  AND r.tipres = 4
                  AND r.fecres BETWEEN CURRENT_DATE AND (CURRENT_DATE + INTERVAL \'15 days\')';
        $con = $this->consulta($query);
        $reg = $this->row($con);
        if($reg['total'] > 0) {
            $alertas[] = array(
                'tipo' => 'success',
                'mensaje' => 'Hay '.$reg['total'].' partos esperados en los próximos 15 días',
                'icono' => 'fa-baby'
            );
        }
        
        // Mostrar alertas
        if(!empty($alertas)) {
            echo '<div class="alertas-hacienda mb-4">';
            foreach($alertas as $alerta) {
                echo '<div class="alert alert-'.$alerta['tipo'].' alert-dismissible fade show" role="alert">
                        <i class="fa '.$alerta['icono'].'"></i> '.$alerta['mensaje'].'
                        <button type="button" class="close" data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                      </div>';
            }
            echo '</div>';
        }
    }
    
    /**
     * Obtener actividad reciente
     */
    public function obtenerActividadReciente() {
        $idhac = $_SESSION['idhac'];
        $actividad = '';
        
        // Últimos registros de leche
        $query = 'SELECT l.feclec, l.totlec, g.detalle as grupo
                  FROM "LECHE" l
                  INNER JOIN "GRUPO" g ON l.idgru = g.id
                  WHERE g.idhac = '.$idhac.'
                  ORDER BY l.feclec DESC, l.idlec DESC
                  LIMIT 5';
        $con = $this->consulta($query);
        
        $actividad .= '<ul class="list-unstyled">';
        while($reg = $this->row($con)) {
            $actividad .= '<li><i class="fas fa-tint text-info"></i> Producción: '.$reg['totlec'].' litros - '.$reg['grupo'].' - '.$reg['feclec'].'</li>';
        }
        
        // Últimas entregas
        $query = 'SELECT e.fecent, e.totent, c.nomcli
                  FROM "ENTREGA" e
                  INNER JOIN "CLIENTE" c ON e.codcli = c.codcli
                  WHERE e.idhac = '.$idhac.'
                  ORDER BY e.fecent DESC, e.ident DESC
                  LIMIT 3';
        $con = $this->consulta($query);
        
        while($reg = $this->row($con)) {
            $actividad .= '<li><i class="fas fa-truck text-success"></i> Entrega: '.$reg['totent'].' litros a '.$reg['nomcli'].' - '.$reg['fecent'].'</li>';
        }
        $actividad .= '</ul>';
        
        return $actividad;
    }
    
    /**
     * Obtener tareas pendientes
     */
    public function obtenerTareasPendientes() {
        $idhac = $_SESSION['idhac'];
        $tareas = '';
        
        // Reproducciones pendientes
        $query = 'SELECT COUNT(*) as total 
                  FROM "REPRODUCCION" r
                  INNER JOIN "ANIMALES" a ON r.idmadre = a.id
                  WHERE a.idhac = '.$idhac.'
                  AND r.tipres = 0';
        $con = $this->consulta($query);
        $reg = $this->row($con);
        
        $tareas .= '<ul class="list-unstyled">';
        if($reg['total'] > 0) {
            $tareas .= '<li><i class="fas fa-heart text-warning"></i> '.$reg['total'].' reproducciones por revisar</li>';
        }
        
        // Próximos partos
        $query = 'SELECT COUNT(*) as total 
                  FROM "REPRODUCCION" r
                  INNER JOIN "ANIMALES" a ON r.idmadre = a.id
                  WHERE a.idhac = '.$idhac.'
                  AND r.tipres = 4
                  AND r.fecres BETWEEN CURRENT_DATE AND (CURRENT_DATE + INTERVAL \'30 days\')';
        $con = $this->consulta($query);
        $reg = $this->row($con);
        
        if($reg['total'] > 0) {
            $tareas .= '<li><i class="fas fa-baby text-info"></i> '.$reg['total'].' partos esperados próximamente</li>';
        }
        
        $tareas .= '</ul>';
        
        return $tareas;
    }
    
    /**
     * Exportar datos a CSV
     */
    public function exportarDatos($tipo, $fecha_inicio = '', $fecha_fin = '') {
        $filename = $tipo . "_" . date('Y-m-d') . ".csv";
        $delimiter = ";";
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '";');
        
        $f = fopen('php://output', 'w');
        
        switch($tipo) {
            case 'animales':
                $this->exportarAnimales($f, $delimiter);
                break;
            case 'leche':
                $this->exportarLeche($f, $delimiter, $fecha_inicio, $fecha_fin);
                break;
            case 'entregas':
                $this->exportarEntregas($f, $delimiter, $fecha_inicio, $fecha_fin);
                break;
            case 'financiero':
                $this->exportarFinanciero($f, $delimiter, $fecha_inicio, $fecha_fin);
                break;
        }
        
        fclose($f);
        exit;
    }
    
    private function exportarAnimales($f, $delimiter) {
        fputcsv($f, array('ID', 'Nombre', 'Arete', 'Especie', 'Raza', 'Estado', 'Fecha Nacimiento'), $delimiter);
        
        $query = 'SELECT a.id, a.nombre, a.arete, a.espani, r.detalle as raza, a.esthac, a.fecnac
                  FROM "ANIMALES" a
                  LEFT JOIN "RAZA" r ON a.idraza = r.id
                  WHERE a.idhac = '.$_SESSION['idhac'].'
                  ORDER BY a.nombre';
        
        $con = $this->consulta($query);
        $espani = array('General','Vacuno','Equino','Ovino','Canino');
        $esthac = array('POR REGISTRAR','NORMAL','VENDIDO','MUERTO','PERDIDO', 'FUERA DE PREDIO');
        
        while($reg = $this->row($con)) {
            fputcsv($f, array(
                $reg['id'],
                $reg['nombre'],
                $reg['arete'],
                $espani[$reg['espani']],
                $reg['raza'],
                $esthac[$reg['esthac']],
                $reg['fecnac']
            ), $delimiter);
        }
    }
    
    private function exportarLeche($f, $delimiter, $fecha_inicio, $fecha_fin) {
        fputcsv($f, array('Fecha', 'Grupo', 'Total Litros', 'Ordeños', 'Empleado'), $delimiter);
        
        $where = '';
        if($fecha_inicio && $fecha_fin) {
            $where = 'AND l.feclec BETWEEN \''.$fecha_inicio.'\' AND \''.$fecha_fin.'\'';
        }
        
        $query = 'SELECT l.feclec, g.detalle as grupo, l.totlec, l.tielec, e.nombrecompleto
                  FROM "LECHE" l
                  INNER JOIN "GRUPO" g ON l.idgru = g.id
                  LEFT JOIN "EMPLEADOS" e ON l.idemp = e.idemp
                  WHERE g.idhac = '.$_SESSION['idhac'].'
                  '.$where.'
                  ORDER BY l.feclec DESC';
        
        $con = $this->consulta($query);
        while($reg = $this->row($con)) {
            fputcsv($f, array(
                $reg['feclec'],
                $reg['grupo'],
                $reg['totlec'],
                $reg['tielec'],
                $reg['nombrecompleto']
            ), $delimiter);
        }
    }
    
    private function exportarEntregas($f, $delimiter, $fecha_inicio, $fecha_fin) {
        fputcsv($f, array('Fecha', 'Cliente', 'Litros', 'Valor Total', 'Estado'), $delimiter);
        
        $where = '';
        if($fecha_inicio && $fecha_fin) {
            $where = 'AND e.fecent BETWEEN \''.$fecha_inicio.'\' AND \''.$fecha_fin.'\'';
        }
        
        $query = 'SELECT e.fecent, c.nomcli, e.totlit, e.totent, e.estent
                  FROM "ENTREGA" e
                  INNER JOIN "CLIENTE" c ON e.codcli = c.codcli
                  WHERE e.idhac = '.$_SESSION['idhac'].'
                  '.$where.'
                  ORDER BY e.fecent DESC';
        
        $con = $this->consulta($query);
        while($reg = $this->row($con)) {
            fputcsv($f, array(
                $reg['fecent'],
                $reg['nomcli'],
                $reg['totlit'],
                $reg['totent'],
                $reg['estent'] == 1 ? 'Activo' : 'Inactivo'
            ), $delimiter);
        }
    }
    
    private function exportarFinanciero($f, $delimiter, $fecha_inicio, $fecha_fin) {
        fputcsv($f, array('Fecha', 'Tipo', 'Descripción', 'Monto', 'Categoría'), $delimiter);
        
        $where = '';
        if($fecha_inicio && $fecha_fin) {
            $where = 'AND i.fecing BETWEEN \''.$fecha_inicio.'\' AND \''.$fecha_fin.'\'';
        }
        
        // Ingresos
        $query = 'SELECT i.fecing, \'INGRESO\' as categoria, ti.detti, i.deting, i.montoing
                  FROM "INGRESO" i
                  INNER JOIN "TIPO_INGRESO" ti ON i.idtipi = ti.id
                  WHERE ti.idhac = '.$_SESSION['idhac'].'
                  '.$where;
        
        $con = $this->consulta($query);
        while($reg = $this->row($con)) {
            fputcsv($f, array(
                $reg['fecing'],
                $reg['detti'],
                $reg['deting'],
                $reg['montoing'],
                $reg['categoria']
            ), $delimiter);
        }
        
        // Egresos
        $where = '';
        if($fecha_inicio && $fecha_fin) {
            $where = 'AND e.fecegr BETWEEN \''.$fecha_inicio.'\' AND \''.$fecha_fin.'\'';
        }
        
        $query = 'SELECT e.fecegr, \'EGRESO\' as categoria, te.dette, e.detegr, e.montoegr
                  FROM "EGRESO" e
                  INNER JOIN "TIPO_EGRESO" te ON e.idtipe = te.id
                  WHERE te.idhac = '.$_SESSION['idhac'].'
                  '.$where;
        
        $con = $this->consulta($query);
        while($reg = $this->row($con)) {
            fputcsv($f, array(
                $reg['fecegr'],
                $reg['dette'],
                $reg['detegr'],
                $reg['montoegr'],
                $reg['categoria']
            ), $delimiter);
        }
    }
    /**
 * Obtener estadísticas rápidas para la interfaz.
 *
 * Calcula el número de animales activos, los litros de leche producidos hoy,
 * las entregas realizadas hoy y el número de usuarios activos. El parámetro
 * $idhac permite especificar la hacienda; si es nulo se toma el id de la sesión.
 *
 * @param int|null $idhac Identificador de la hacienda
 * @return array
 */
public function obtenerEstadisticasRapidas($idhac = null) {
    // Tomar el id de la sesión si no se pasa como argumento
    if ($idhac === null) {
        $idhac = isset($_SESSION['idhac']) ? $_SESSION['idhac'] : 0;
    }

    $stats = array();

    // Animales activos (estado = 1)
    $query = 'SELECT COUNT(*) as total FROM "ANIMALES" WHERE idhac = ' . $idhac . ' AND esthac = 1';
    $con   = $this->consulta($query);
    $reg   = $this->row($con);
    $stats['animales_activos'] = isset($reg['total']) ? $reg['total'] : 0;
$fechaHoy = date('Y-m-d');
    // Producción de leche de hoy
  $query = 'SELECT COALESCE(SUM(l.totlec), 0) AS total
          FROM "LECHE" l
          INNER JOIN "GRUPO" g ON l.idgru = g.id
          WHERE g.idhac = ' . $idhac . ' AND l.feclec = \'' . $fechaHoy . '\'';
    $con = $this->consulta($query);
    $reg = $this->row($con);
    $stats['leche_hoy'] = isset($reg['total']) ? $reg['total'] : 0;

    // Entregas de hoy
    $query = 'SELECT COUNT(*) as total FROM "ENTREGA"
              WHERE idhac = ' . $idhac . ' AND fecent = \'' . $fechaHoy . '\'';
    $con = $this->consulta($query);
    $reg = $this->row($con);
    $stats['entregas_hoy'] = isset($reg['total']) ? $reg['total'] : 0;

    // Usuarios activos: valor por defecto (actualizar según la tabla real de usuarios)
    $stats['usuarios_activos'] = 0;

    return $stats;
}
/**
 * Modifica la información de la hacienda a partir de los datos del formulario.
 * También gestiona la carga del archivo de firma (.p12) y lo guarda en la carpeta 'archivos'.
 *
 * @param array $datos Datos provenientes del formulario ($_POST)
 * @throws Exception si no existe un id de hacienda válido
 */
public function modificarHacienda($datos) {
    // Verificar que existe el id de la hacienda en la sesión
    $idhac = isset($_SESSION['idhac']) ? $_SESSION['idhac'] : 0;
    if ($idhac == 0) {
        throw new Exception('No se encontró la hacienda a modificar.');
    }

    // Sanitizar entradas recibidas
    $ambiente        = isset($datos['ambiente'])        ? addslashes(trim($datos['ambiente']))        : '1';
    $tipoEmision     = isset($datos['tipo_emision'])     ? addslashes(trim($datos['tipo_emision']))     : '1';
    $razonSocial     = isset($datos['razon_social'])     ? addslashes(trim($datos['razon_social']))     : '';
    $nombreComercial = isset($datos['nombre_comercial']) ? addslashes(trim($datos['nombre_comercial'])) : '';
    $ruc             = isset($datos['ruc'])             ? addslashes(trim($datos['ruc']))             : '';
    $codDoc          = isset($datos['cod_doc'])          ? addslashes(trim($datos['cod_doc']))          : '';
    $estab           = isset($datos['estab'])           ? addslashes(trim($datos['estab']))           : '';
    $ptoEmi          = isset($datos['pto_emi'])          ? addslashes(trim($datos['pto_emi']))          : '';
    $secuencial      = isset($datos['secuencial'])      ? addslashes(trim($datos['secuencial']))      : '';
    $dirMatriz       = isset($datos['dir_matriz'])       ? addslashes(trim($datos['dir_matriz']))       : '';
    $obligadoCont    = isset($datos['obligado_contabilidad']) ? addslashes(trim($datos['obligado_contabilidad'])) : 'NO';
    $claveCertificado= isset($datos['certificado_clave']) ? addslashes(trim($datos['certificado_clave'])) : '';

    // Obtener los valores actuales para preservar el certificado si no se sube uno nuevo
    $registroActual = $this->obtenerHacienda($idhac);
    $certificadoPath = isset($registroActual['certificado_path']) ? $registroActual['certificado_path'] : '';

    // Manejo de la subida de archivo de firma (.p12)
    if (isset($_FILES['certificado_p12']) &&
        isset($_FILES['certificado_p12']['tmp_name']) &&
        $_FILES['certificado_p12']['error'] === UPLOAD_ERR_OK) {

        $tmpName  = $_FILES['certificado_p12']['tmp_name'];
        $fileName = basename($_FILES['certificado_p12']['name']);
        // Directorio para guardar los certificados: 'archivos'
        $dirDestino = dirname(__DIR__) . '/archivos';
        if (!file_exists($dirDestino)) {
            mkdir($dirDestino, 0777, true);
        }
        // Generar un nombre único para evitar colisiones
        $nuevoNombre = $idhac . '_' . date('YmdHis') . '_' . preg_replace('/[^A-Za-z0-9_\\.]/', '', $fileName);
        $rutaDestino = $dirDestino . '/' . $nuevoNombre;

        // Mover el archivo subido al destino
        if (move_uploaded_file($tmpName, $rutaDestino)) {
            // Guardar la ruta relativa al archivo para almacenarla en la base
            $certificadoPath = 'archivos/' . $nuevoNombre;
        }
    }

    // Construir las asignaciones para el UPDATE
    $updates = array();
    $updates[] = "ambiente = '{$ambiente}'";
    $updates[] = "tipo_emision = '{$tipoEmision}'";
    $updates[] = "razon_social = '{$razonSocial}'";
    $updates[] = "nombre_comercial = '{$nombreComercial}'";
    $updates[] = "ruc = '{$ruc}'";
    $updates[] = "cod_doc = '{$codDoc}'";
    $updates[] = "estab = '{$estab}'";
    $updates[] = "pto_emi = '{$ptoEmi}'";
    $updates[] = "secuencial = '{$secuencial}'";
    $updates[] = "dir_matriz = '{$dirMatriz}'";
    $updates[] = "obligado_contabilidad = '{$obligadoCont}'";
    // Guardar ruta y clave del certificado (o NULL si no se especifica)
    $updates[] = "certificado_path = " . (empty($certificadoPath) ? "NULL" : "'{$certificadoPath}'");
    $updates[] = "certificado_clave = " . (empty($claveCertificado) ? "NULL" : "'{$claveCertificado}'");

    // Sentencia de actualización
    $query = 'UPDATE "HACIENDA" SET ' . implode(', ', $updates) . ' WHERE id = ' . $idhac;

    // Ejecutar el UPDATE
    $this->consulta($query);
}


public function mostrarUsuarios() {
    $idhac = isset($_SESSION['idhac']) ? intval($_SESSION['idhac']) : 0;
    if ($idhac <= 0) {
        throw new Exception('No se ha definido la hacienda.');
    }
    // Obtener usuarios de la hacienda
    $query = 'SELECT id, nomusu, username, emailusu, estusu, tipusu FROM "USUARIOS" '
           . 'WHERE idhac = ' . $idhac . ' ORDER BY id';
    $con = $this->consulta($query);
    $usuarios = [];
    while ($reg = $this->row($con)) {
        $usuarios[] = $reg;
    }
    // Contar usuarios activos
    $activos = 0;
    foreach ($usuarios as $u) {
        if (intval($u['estusu']) === 1) {
            $activos++;
        }
    }
    // Mostrar tabla de usuarios
    echo '<div class="table-responsive mb-3">';
    echo '<table class="table table-striped table-bordered">';
    echo '<thead class="table-light"><tr>';
    echo '<th>Nombre</th><th>Usuario</th><th>Correo</th><th>Tipo</th><th>Estado</th><th>Acciones</th>';
    echo '</tr></thead><tbody>';
    if (empty($usuarios)) {
        echo '<tr><td colspan="6" class="text-center text-muted">'
           . 'No existen usuarios registrados para esta hacienda.</td></tr>';
    } else {
        foreach ($usuarios as $usu) {
            $estado = intval($usu['estusu']) === 1
                    ? '<span class="badge bg-success">Activo</span>'
                    : '<span class="badge bg-secondary">Inactivo</span>';
            // Traducir tipo de usuario
            $tipo = '';
            switch (intval($usu['tipusu'])) {
                case 1:  $tipo = 'Administrador'; break;
                case 2:  $tipo = 'Operador';      break;
                default: $tipo = $usu['tipusu'];
            }
            echo '<tr>';
            echo '<td>' . htmlspecialchars($usu['nomusu']) . '</td>';
            echo '<td>' . htmlspecialchars($usu['username']) . '</td>';
            echo '<td>' . htmlspecialchars($usu['emailusu']) . '</td>';
            echo '<td>' . htmlspecialchars($tipo) . '</td>';
            echo '<td>' . $estado . '</td>';
            echo '<td>';
            // Botón de editar (siempre disponible)
            echo '<a href="?editarUsuario=' . intval($usu['id'])
               . '" class="btn btn-sm btn-primary me-1"><i class="fas fa-edit"></i> Editar</a>';
            if (intval($usu['estusu']) === 0) {
    echo '<a href="?activarUsuario=' . intval($usu['id']) . '" class="btn btn-sm btn-success">'
       . '<i class="fas fa-user-check"></i> Activar</a>';
}

            // Botón de desactivar (solo si está activo)
            if (intval($usu['estusu']) === 1) {
                echo '<a href="?desactivarUsuario=' . intval($usu['id'])
                   . '" class="btn btn-sm btn-danger" '
                   . 'onclick="return confirm(\'¿Está seguro de desactivar este usuario?\')">'
                   . '<i class="fas fa-user-slash"></i> Desactivar</a>';
            }
            echo '</td>';
            echo '</tr>';
        }
    }
    echo '</tbody></table></div>';
    // Resumen de usuarios activos
    echo '<p><strong>Usuarios activos:</strong> ' . $activos . '/7</p>';
    if ($activos >= 7) {
        echo '<div class="alert alert-warning">'
           . 'Ha alcanzado el máximo de 7 usuarios activos para esta hacienda. '
           . 'Debe desactivar un usuario antes de poder crear uno nuevo.</div>';
    } else {
        // Botón para abrir el formulario de creación
        echo '<button type="button" class="btn btn-success mb-3" onclick="mostrarFormularioUsuario()">';
        echo '<i class="fas fa-plus"></i> Nuevo Usuario</button>';
    }
}
public function mostrarFormularioUsuario($idUsuario = null) {
    $idhac = isset($_SESSION['idhac']) ? intval($_SESSION['idhac']) : 0;
    if ($idhac <= 0) {
        throw new Exception('No se ha definido la hacienda.');
    }
    // Contar usuarios activos
    $queryActivos = 'SELECT COUNT(*) AS total FROM "USUARIOS" WHERE idhac = ' . $idhac . ' AND estusu = 1';
    $conAct = $this->consulta($queryActivos);
    $regAct = $this->row($conAct);
    $activos = intval($regAct['total']);
    // Si se va a crear y ya hay 5 activos, no mostrar el formulario
    if ($idUsuario === null && $activos >= 7) {
        return;
    }
    // Valores por defecto
    $nomusu   = '';
    $username = '';
    $emailusu = '';
    $tipusu   = 2;
    $heading  = 'Crear Nuevo Usuario';
    $buttonName  = 'bttCrearUsuario';
    $buttonLabel = 'Crear Usuario';
    $idHidden = '';
    // Si es edición, cargar los datos del usuario
    if ($idUsuario !== null) {
        $idUsuario = intval($idUsuario);
        $query = 'SELECT * FROM "USUARIOS" WHERE id = ' . $idUsuario . ' AND idhac = ' . $idhac;
        $con   = $this->consulta($query);
        $reg   = $this->row($con);
        if (!$reg) {
            throw new Exception('Usuario no encontrado.');
        }
        $nomusu   = $reg['nomusu'];
        $username = $reg['username'];
        $emailusu = $reg['emailusu'];
        $tipusu   = intval($reg['tipusu']);
        $heading  = 'Editar Usuario';
        $buttonName  = 'bttActualizarUsuario';
        $buttonLabel = 'Actualizar Usuario';
        $idHidden    = '<input type="hidden" name="idusu" value="' . intval($reg['id']) . '">';
    }
    // Ocultar formulario al crear; visible al editar
    $style = ($idUsuario === null) ? 'display:none;' : '';
    echo '<div id="formularioUsuario" style="' . $style . '" class="card card-body mt-3">';
    echo '<h5>' . htmlspecialchars($heading) . '</h5>';
    echo '<form method="post">';
    echo $idHidden;
    echo '<div class="row mb-2">';
    echo '<div class="col-md-6">';
    echo '<label for="nomusu" class="form-label">Nombre</label>';
    echo '<input type="text" name="nomusu" id="nomusu" class="form-control" '
       . 'value="' . htmlspecialchars($nomusu) . '" required>';
    echo '</div>';
    echo '<div class="col-md-6">';
    echo '<label for="username" class="form-label">Usuario</label>';
    echo '<input type="text" name="username" id="username" class="form-control" '
       . 'value="' . htmlspecialchars($username) . '" required>';
    echo '</div>';
    echo '</div>';
    echo '<div class="row mb-2">';
    echo '<div class="col-md-6">';
    echo '<label for="pass" class="form-label">Contraseña'
       . ($idUsuario === null ? '' : ' (dejar en blanco para no cambiar)') . '</label>';
    echo '<input type="password" name="pass" id="pass" class="form-control"'
       . ($idUsuario === null ? ' required' : '') . '>';
    echo '</div>';
    echo '<div class="col-md-6">';
    echo '<label for="emailusu" class="form-label">Correo</label>';
    echo '<input type="email" name="emailusu" id="emailusu" class="form-control" '
       . 'value="' . htmlspecialchars($emailusu) . '">';
    echo '</div>';
    echo '</div>';
    echo '<div class="row mb-3">';
    echo '<div class="col-md-6">';
    echo '<label for="tipusu" class="form-label">Tipo de usuario</label>';
    echo '<select name="tipusu" id="tipusu" class="form-control">';
    $selected1 = ($tipusu === 1) ? ' selected' : '';
    $selected2 = ($tipusu === 2) ? ' selected' : '';
    echo '<option value="1"' . $selected1 . '>Administrador</option>';
    echo '<option value="2"' . $selected2 . '>Operador</option>';
    echo '</select>';
    echo '</div>';
    echo '</div>';
    echo '<button type="submit" name="' . htmlspecialchars($buttonName) . '" class="btn btn-primary me-2">'
       . htmlspecialchars($buttonLabel) . '</button>';
    echo '<button type="button" class="btn btn-secondary" onclick="ocultarFormularioUsuario()">Cancelar</button>';
    echo '</form></div>';
}
/**
 * Crear un nuevo usuario (máx. 5 activos por hacienda).
 * Campos esperados en $data: nomusu, username, pass, emailusu(opc), tipusu(opc)
 */
public function crearUsuarioHacienda($data) {
    $idhac = isset($_SESSION['idhac']) ? intval($_SESSION['idhac']) : 0;
    if ($idhac <= 0) throw new Exception('No se ha definido la hacienda.');

    // Límite de 5 activos
    $qAct = 'SELECT COUNT(*) AS total FROM "USUARIOS" WHERE idhac = '.$idhac.' AND estusu = 1';
    $rAct = $this->row($this->consulta($qAct));
    if (intval($rAct['total']) >= 7) {
        throw new Exception('Se alcanzó el máximo de 7 usuarios activos para esta hacienda.');
    }

    // Datos
    $nomusu   = isset($data['nomusu'])   ? addslashes(trim($data['nomusu']))   : '';
    $username = isset($data['username']) ? addslashes(trim($data['username'])) : '';
    $pass     = isset($data['pass'])     ? addslashes(trim($data['pass']))     : '';
    $emailusu = isset($data['emailusu']) ? addslashes(trim($data['emailusu'])) : '';
    $tipusu   = isset($data['tipusu'])   ? intval($data['tipusu'])             : 2;

    if ($nomusu === '' || $username === '' || $pass === '') {
        throw new Exception('Nombre, usuario y contraseña son obligatorios.');
    }

    // Duplicado de username en la misma hacienda
    $qDup = "SELECT COUNT(*) AS total FROM \"USUARIOS\" WHERE username = '".$username."' AND idhac = ".$idhac;
    $rDup = $this->row($this->consulta($qDup));
    if (intval($rDup['total']) > 0) {
        throw new Exception('El nombre de usuario ya existe en esta hacienda.');
    }

    // Nuevo ID
    $rMax = $this->row($this->consulta('SELECT COALESCE(MAX(id),0) AS maxid FROM "USUARIOS"'));
    $nuevoId = intval($rMax['maxid']) + 1;

    // Insert
    $sql = "INSERT INTO \"USUARIOS\" (id, nomusu, username, pass, emailusu, estusu, idhac, tipusu)
            VALUES (".$nuevoId.", '".$nomusu."', '".$username."', '".$pass."', ".
            ($emailusu === '' ? "NULL" : "'".$emailusu."'").", 1, ".$idhac.", ".$tipusu.")";
    $this->consulta($sql);
}

/**
 * Actualizar datos de un usuario de la hacienda.
 * Campos esperados en $data: idusu, nomusu, username, pass(opc), emailusu(opc), tipusu(opc)
 */
public function actualizarUsuario($data) {
    $idhac = isset($_SESSION['idhac']) ? intval($_SESSION['idhac']) : 0;
    if ($idhac <= 0) throw new Exception('No se ha definido la hacienda.');
    if (!isset($data['idusu']) || !is_numeric($data['idusu'])) {
        throw new Exception('ID de usuario no válido.');
    }
    $idusu = intval($data['idusu']);

    // Verificar pertenencia
    $reg = $this->row($this->consulta('SELECT * FROM "USUARIOS" WHERE id = '.$idusu.' AND idhac = '.$idhac));
    if (!$reg) throw new Exception('El usuario no pertenece a esta hacienda.');

    // Datos
    $nomusu   = isset($data['nomusu'])   ? addslashes(trim($data['nomusu']))   : '';
    $username = isset($data['username']) ? addslashes(trim($data['username'])) : '';
    $emailusu = isset($data['emailusu']) ? addslashes(trim($data['emailusu'])) : '';
    $tipusu   = isset($data['tipusu'])   ? intval($data['tipusu'])             : intval($reg['tipusu']);
    $pass     = isset($data['pass'])     ? trim($data['pass'])                 : '';

    if ($nomusu === '' || $username === '') {
        throw new Exception('Nombre y usuario son obligatorios.');
    }

    // Armar UPDATE
    $updates   = [];
    $updates[] = "nomusu = '".$nomusu."'";
    $updates[] = "username = '".$username."'";
    $updates[] = ($emailusu === '' ? "emailusu = NULL" : "emailusu = '".$emailusu."'");
    $updates[] = "tipusu = ".$tipusu;
    if ($pass !== '') {
        $updates[] = "pass = '".addslashes($pass)."'";
    }

    $sql = 'UPDATE "USUARIOS" SET '.implode(', ', $updates).' WHERE id = '.$idusu;
    $this->consulta($sql);
}

/**
 * Desactivar (inactivar) un usuario de la hacienda (estusu = 0).
 */
public function desactivarUsuario($idUsuario) {
    $idhac = isset($_SESSION['idhac']) ? intval($_SESSION['idhac']) : 0;
    if ($idhac <= 0) throw new Exception('No se ha definido la hacienda.');
    $idUsuario = intval($idUsuario);

    // Verificar pertenencia
    $reg = $this->row($this->consulta('SELECT * FROM "USUARIOS" WHERE id = '.$idUsuario.' AND idhac = '.$idhac));
    if (!$reg) throw new Exception('El usuario no pertenece a esta hacienda.');

    $sql = 'UPDATE "USUARIOS" SET estusu = 0 WHERE id = '.$idUsuario;
    $this->consulta($sql);
}

/**
 * Activar un usuario (estusu = 1) respetando el límite de 5 activos.
 */
public function activarUsuario($idUsuario) {
    $idhac = isset($_SESSION['idhac']) ? intval($_SESSION['idhac']) : 0;
    if ($idhac <= 0) throw new Exception('No se ha definido la hacienda.');
    $idUsuario = intval($idUsuario);

    // Verificar pertenencia
    $reg = $this->row($this->consulta('SELECT * FROM "USUARIOS" WHERE id = '.$idUsuario.' AND idhac = '.$idhac));
    if (!$reg) throw new Exception('El usuario no pertenece a esta hacienda.');

    // Límite de 5 activos
    $qAct = 'SELECT COUNT(*) AS total FROM "USUARIOS" WHERE idhac = '.$idhac.' AND estusu = 1';
    $rAct = $this->row($this->consulta($qAct));
    if (intval($rAct['total']) >= 7) {
        throw new Exception('No se puede activar: ya existen 7 usuarios activos en esta hacienda.');
    }

    $sql = 'UPDATE "USUARIOS" SET estusu = 1 WHERE id = '.$idUsuario;
    $this->consulta($sql);
}

}
?>
