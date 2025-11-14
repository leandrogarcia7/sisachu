<?php
require_once("ANIMALES.php");

/**
 * Clase DASHBOARD para mostrar información completa de un animal
 * Incluye árbol genealógico, estadísticas y producción lechera
 * 
 * @author Leandro García - Sistema Sisachu
 */
class DASHBOARD extends ANIMALES {
    
    public $relaciones = array(
        'padre' => 'Padre',
        'madre' => 'Madre', 
        'abuelo_paterno' => 'Abuelo Paterno',
        'abuela_paterna' => 'Abuela Paterna',
        'abuelo_materno' => 'Abuelo Materno',
        'abuela_materna' => 'Abuela Materna',
        'hermanos' => 'Hermanos',
        'hermanas' => 'Hermanas',
        'tios_paternos' => 'Tíos Paternos',
        'tias_paternas' => 'Tías Paternas',
        'tios_maternos' => 'Tíos Maternos',
        'tias_maternas' => 'Tías Maternas',
        'hijos' => 'Hijos',
        'hijas' => 'Hijas',
        'sobrinos' => 'Sobrinos',
        'sobrinas' => 'Sobrinas'
    );
    
    public $iconos_estado = array(
        0 => '❓', // POR REGISTRAR - interrogación
        1 => '✅', // NORMAL - check verde
        2 => '💰', // VENDIDO - dinero
        3 => '💀', // MUERTO - calavera
        4 => '❌', // PERDIDO - X roja
        5 => '🏠'  // FUERA DE PREDIO - casa
    );
    
    public $colores_estado = array(
        0 => '#ffc107', // POR REGISTRAR - amarillo
        1 => '#28a745', // NORMAL - verde
        2 => '#17a2b8', // VENDIDO - azul
        3 => '#dc3545', // MUERTO - rojo
        4 => '#fd7e14', // PERDIDO - naranja
        5 => '#6c757d'  // FUERA DE PREDIO - gris
    );
    
    // Arrays para reproducción
    public $tipres = array('Por revisar','Vacia','Aborto','Parto','Confirmado');
    public $tiprep = array('Pendiente','Inseminación','Monta','Celo');
    public $estsec = array('Pendiente','Producción','Secada');
    
    // Colores para estados reproductivos
    public $colores_tipres = array(
        0 => '#ffc107', // Por revisar - amarillo
        1 => '#dc3545', // Vacia - rojo
        2 => '#fd7e14', // Aborto - naranja
        3 => '#28a745', // Parto - verde
        4 => '#007bff'  // Confirmado - azul
    );
    
    public $colores_tiprep = array(
        0 => '#6c757d', // Pendiente - gris
        1 => '#17a2b8', // Inseminación - cyan
        2 => '#28a745', // Monta - verde
        3 => '#ffc107'  // Celo - amarillo
    );
    
    public $colores_estsec = array(
        0 => '#ffc107', // Pendiente - amarillo
        1 => '#28a745', // Producción - verde
        2 => '#6c757d'  // Secada - gris
    );
    
    /**
     * Función principal para mostrar el dashboard completo
     */
    public function mostrarDashboard($idani) {
        $animal = $this->mostrarAnimal($idani);
        if (!$animal) {
            echo "<div class='alert alert-danger'>Animal no encontrado</div>";
            return false;
        }
        
        echo "<div class='container-fluid'>";
        echo "<div class='row'>";
        
        // Columna izquierda - Información básica y foto
        echo "<div class='col-md-4'>";
        $this->mostrarInfoBasica($animal);
        $this->mostrarEstadisticasVida($animal);
        echo "</div>";
        echo "</form>";
    
        
        // Columna central - Árbol genealógico
        echo "<div class='col-md-5'>";
        $this->mostrarArbolGenealogico($idani);
        echo "</div>";
        
        // Columna derecha - Producción lechera y reproducción
        echo "<div class='col-md-3'>";
        $this->mostrarProduccionLechera($idani);
        $this->mostrarEstadisticasReproduccion($idani);
        $this->mostrarReproduccionActual($idani);
        $this->mostrarReproduccionMachos($idani);
        echo "</div>";
        
        echo "</div>"; // row
        echo "</div>"; // container
    }
    
    /**
     * Muestra información básica del animal con foto
     */
    public function mostrarInfoBasica($animal) {
        $foto = $this->mostrarFotoAnimal($animal['id'], 300);
        $edad = $this->calcularEdad($animal['fecnac']);
        
        echo "<div class='card mb-3'>";
        echo "<div class='card-header'><h3>{$animal['nombre']}</h3></div>";
        echo "<div class='card-body'>";
        
        if ($foto) {
            echo "<div class='text-center mb-3'>{$foto}</div>";
        }
        
        echo "<table class='table table-sm'>";
        echo "<tr><td><b>Arete:</b></td><td>{$animal['arete']}</td></tr>";
        echo "<tr><td><b>Edad:</b></td><td>{$edad}</td></tr>";
        echo "<tr><td><b>Sexo:</b></td><td>{$this->sexani[$animal['sexani']]}</td></tr>";
        echo "<tr><td><b>Especie:</b></td><td>{$this->espani[$animal['espani']]}</td></tr>";
        echo "<tr><td><b>Estado:</b></td><td>{$this->esthac[$animal['esthac']]}</td></tr>";
        echo "<tr><td><b>Est. Reproductivo:</b></td><td>{$this->estrep[$animal['estrep']]}</td></tr>";
        echo "<tr><td><b>Salud:</b></td><td>{$this->estsal[$animal['estsal']]}</td></tr>";
        echo "</table>";
        
        echo "</div></div>";
    }
    
    /**
     * Muestra estadísticas de vida del animal
     */
    public function mostrarEstadisticasVida($animal) {
        $stats = $this->calcularEstadisticasVida($animal['id']);
        
        echo "<div class='card mb-3'>";
        echo "<div class='card-header'><h5>Estadísticas de Vida</h5></div>";
        echo "<div class='card-body'>";
        
        echo "<div class='row text-center'>";
        echo "<div class='col-6'>";
        echo "<h4 class='text-primary'>{$stats['dias_vida']}</h4>";
        echo "<small>Días de Vida</small>";
        echo "</div>";
        echo "<div class='col-6'>";
        echo "<h4 class='text-success'>{$stats['controles']}</h4>";
        echo "<small>Controles</small>";
        echo "</div>";
        echo "</div>";
        
        echo "<hr>";
        
        echo "<div class='row text-center'>";
        echo "<div class='col-6'>";
        echo "<h4 class='text-info'>{$stats['reproducciones']}</h4>";
        echo "<small>Reproducciones</small>";
        echo "</div>";
        echo "<div class='col-6'>";
        echo "<h4 class='text-warning'>{$stats['crias']}</h4>";
        echo "<small>Crías</small>";
        echo "</div>";
        echo "</div>";
        
        // Añadir nietos si existen
        if ($stats['nietos'] > 0) {
            echo "<hr>";
            echo "<div class='text-center'>";
            echo "<h4 class='text-info'>{$stats['nietos']}</h4>";
            echo "<small>Nietos</small>";
            echo "</div>";
        }
        
        if ($stats['peso_actual'] > 0) {
            echo "<hr>";
            echo "<div class='text-center'>";
            echo "<h4 class='text-secondary'>{$stats['peso_actual']} kg</h4>";
            echo "<small>Peso Estimado Actual</small>";
            echo "</div>";
        }
        
        echo "</div></div>";
    }
    
    /**
     * Muestra el árbol genealógico completo
     */
    public function mostrarArbolGenealogico($idani) {
        $arbol = $this->construirArbolGenealogico($idani);
        
        echo "<div class='card'>";
        echo "<div class='card-header'>";
        echo "<h5>Árbol Genealógico</h5>";
        // Agregar leyenda de estados
        $this->mostrarLeyendaEstados();
        echo "</div>";
        echo "<div class='card-body' style='max-height: 600px; overflow-y: auto;'>";
        
        // Abuelos
        echo "<div class='row mb-2'>";
        echo "<div class='col-3'>";
        $this->mostrarAnimalMini($arbol['abuelo_paterno'], 'Abuelo Paterno');
        echo "</div>";
        echo "<div class='col-3'>";
        $this->mostrarAnimalMini($arbol['abuela_paterna'], 'Abuela Paterna');
        echo "</div>";
        echo "<div class='col-3'>";
        $this->mostrarAnimalMini($arbol['abuelo_materno'], 'Abuelo Materno');
        echo "</div>";
        echo "<div class='col-3'>";
        $this->mostrarAnimalMini($arbol['abuela_materna'], 'Abuela Materna');
        echo "</div>";
        echo "</div>";
        
        // Padres
        echo "<div class='row mb-3'>";
        echo "<div class='col-6'>";
        $this->mostrarAnimalMini($arbol['padre'], 'Padre');
        echo "</div>";
        echo "<div class='col-6'>";
        $this->mostrarAnimalMini($arbol['madre'], 'Madre');
        echo "</div>";
        echo "</div>";
        
        // Animal actual (destacado)
        echo "<div class='row mb-3'>";
        echo "<div class='col-12 text-center'>";
        $animal_actual = $this->mostrarAnimal($idani);
        echo "<div class='border border-primary rounded p-2' style='background-color: #f8f9fa;'>";
        echo "<strong>{$animal_actual['nombre']}</strong><br>";
        echo "<small>Arete: {$animal_actual['arete']}</small>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
        
        // Hermanos (medios hermanos que comparten padre o madre)
        if (!empty($arbol['hermanos']) || !empty($arbol['hermanas'])) {
            echo "<div class='row mb-2'>";
            echo "<div class='col-12'><h6>Medios Hermanos</h6></div>";
            
            $hermanos_total = array_merge($arbol['hermanos'], $arbol['hermanas']);
            foreach ($hermanos_total as $hermano) {
                echo "<div class='col-4 mb-1'>";
                $this->mostrarAnimalMini($hermano, $this->sexani[$hermano['sexani']]);
                echo "</div>";
            }
            echo "</div>";
        }
        
        // Tíos
        if (!empty($arbol['tios_paternos']) || !empty($arbol['tias_paternas']) || 
            !empty($arbol['tios_maternos']) || !empty($arbol['tias_maternas'])) {
            echo "<div class='row mb-2'>";
            echo "<div class='col-12'><h6>Tíos</h6></div>";
            
            $tios_total = array_merge(
                $arbol['tios_paternos'], $arbol['tias_paternas'],
                $arbol['tios_maternos'], $arbol['tias_maternas']
            );
            
            foreach ($tios_total as $tio) {
                echo "<div class='col-4 mb-1'>";
                $this->mostrarAnimalMini($tio, 'Tío/a');
                echo "</div>";
            }
            echo "</div>";
        }
        
        // Hijos (mostrar todos juntos para debug)
        $hijos_machos = $this->obtenerDescendencia($idani, 2);
        $hijas_hembras = $this->obtenerDescendencia($idani, 1);
        $todos_los_hijos = array_merge($hijos_machos, $hijas_hembras);
        
        if (!empty($todos_los_hijos)) {
            echo "<div class='row mb-2'>";
            echo "<div class='col-12'><h6>Descendencia (".count($todos_los_hijos)." total)</h6></div>";
            
            foreach ($todos_los_hijos as $hijo) {
                echo "<div class='col-4 mb-1'>";
                $this->mostrarAnimalMini($hijo, $this->sexani[$hijo['sexani']]);
                echo "</div>";
            }
            echo "</div>";
        } else {
            echo "<div class='row mb-2'>";
            echo "<div class='col-12'><small class='text-muted'>Sin descendencia registrada</small></div>";
            echo "</div>";
        }
        
        // Nietos (nueva sección)
        $nietos = $this->obtenerNietos($idani);
        if (!empty($nietos)) {
            echo "<hr class='my-3' style='border-top: 2px solid #007bff;'>";
            echo "<div class='row mb-2'>";
            echo "<div class='col-12'><h6><i class='fas fa-baby'></i> Nietos (".count($nietos)." total)</h6></div>";
            
            foreach ($nietos as $nieto) {
                echo "<div class='col-4 mb-1'>";
                // Mostrar nieto con información del padre/madre
                $relacion_texto = 'Nieto/a';
                if (isset($nieto['padre_nieto'])) {
                    $tipo_padre = $nieto['sexo_padre_nieto'] == 1 ? 'hija' : 'hijo';
                    $relacion_texto = "Nieto/a (vía {$tipo_padre} {$nieto['padre_nieto']})";
                }
                $this->mostrarAnimalMiniNieto($nieto, $relacion_texto);
                echo "</div>";
            }
            echo "</div>";
        }
        
        echo "</div></div>";
    }
    
    /**
     * Muestra producción lechera si aplica
     */
    public function mostrarProduccionLechera($idani) {
        if (!$this->esVacaLechera($idani)) {
            return;
        }
        
        $produccion = $this->obtenerProduccionLechera($idani);
        
        echo "<div class='card mb-3'>";
        echo "<div class='card-header'><h6>Producción Lechera</h6></div>";
        echo "<div class='card-body'>";
        
        if (empty($produccion) || $produccion['total_litros'] == 0) {
            echo "<p class='text-muted'>Sin registros de producción</p>";
        } else {
            echo "<div class='mb-2'>";
            echo "<strong>Última Semana:</strong><br>";
            echo "<span class='text-primary'>{$produccion['promedio_semanal']} L/día</span>";
            echo "</div>";
            
            echo "<div class='mb-2'>";
            echo "<strong>Último Mes:</strong><br>";
            echo "<span class='text-success'>{$produccion['promedio_mensual']} L/día</span>";
            echo "</div>";
            
            echo "<div class='mb-2'>";
            echo "<strong>Máxima Producción:</strong><br>";
            echo "<span class='text-warning'>{$produccion['maxima']} L</span>";
            echo "</div>";
            
            echo "<div>";
            echo "<strong>Total Acumulado:</strong><br>";
            echo "<span class='text-info'>{$produccion['total_litros']} L</span>";
            echo "</div>";
        }
        
        echo "</div></div>";
    }
    
    /**
     * Muestra estadísticas reproductivas completas
     */
    public function mostrarEstadisticasReproduccion($idani) {
        $animal = $this->mostrarAnimal($idani);
        
        // Solo mostrar para hembras
        if ($animal['sexani'] != 1) {
            return;
        }
        
        $stats = $this->calcularEstadisticasReproduccion($idani);
        
        echo "<div class='card mb-3'>";
        echo "<div class='card-header'><h6><i class='fas fa-chart-pie'></i> Estadísticas Reproductivas</h6></div>";
        echo "<div class='card-body'>";
        
        if ($stats['total_procesos'] > 0) {
            // Resumen general
            echo "<div class='row text-center mb-3'>";
            echo "<div class='col-4'>";
            echo "<h5 class='text-primary'>{$stats['total_procesos']}</h5>";
            echo "<small>Total Procesos</small>";
            echo "</div>";
            echo "<div class='col-4'>";
            echo "<h5 class='text-success'>{$stats['total_partos']}</h5>";
            echo "<small>Partos</small>";
            echo "</div>";
            echo "<div class='col-4'>";
            echo "<h5 class='text-danger'>{$stats['total_abortos']}</h5>";
            echo "<small>Abortos</small>";
            echo "</div>";
            echo "</div>";
            
            // Porcentajes de éxito
            echo "<div class='mb-3'>";
            echo "<strong>Tasa de Éxito:</strong> ";
            $tasa_exito = $stats['total_procesos'] > 0 ? round(($stats['total_partos'] / $stats['total_procesos']) * 100, 1) : 0;
            $color_tasa = $tasa_exito >= 70 ? 'success' : ($tasa_exito >= 50 ? 'warning' : 'danger');
            echo "<span class='badge badge-{$color_tasa}'>{$tasa_exito}%</span>";
            echo "</div>";
            
            // Desglose por tipo de resultado
            echo "<div class='mb-2'><strong>Resultados:</strong></div>";
            foreach ($stats['por_resultado'] as $tipo => $cantidad) {
                if ($cantidad > 0) {
                    $color = $this->colores_tipres[$tipo];
                    $porcentaje = round(($cantidad / $stats['total_procesos']) * 100, 1);
                    echo "<div class='d-flex justify-content-between align-items-center mb-1'>";
                    echo "<span style='color: {$color};'><i class='fas fa-circle'></i> {$this->tipres[$tipo]}</span>";
                    echo "<span class='badge' style='background-color: {$color}; color: white;'>{$cantidad} ({$porcentaje}%)</span>";
                    echo "</div>";
                }
            }
            
            // Promedio entre partos
            if ($stats['promedio_dias_entre_partos'] > 0) {
                echo "<div class='mt-3'>";
                echo "<strong>Intervalo entre Partos:</strong><br>";
                echo "<span class='text-info'>{$stats['promedio_dias_entre_partos']} días promedio</span>";
                echo "</div>";
            }
            
            // Información adicional
            if ($stats['edad_primer_parto']) {
                echo "<div class='mt-2'>";
                echo "<strong>Edad Primer Parto:</strong><br>";
                echo "<small class='text-secondary'>{$stats['edad_primer_parto']} meses</small>";
                echo "</div>";
            }
            
            if ($stats['ultimo_parto']) {
                echo "<div class='mt-2'>";
                echo "<strong>Último Parto:</strong><br>";
                echo "<small class='text-secondary'>{$stats['ultimo_parto']}</small>";
                echo "</div>";
            }
            
        } else {
            echo "<p class='text-muted'>Sin registros reproductivos</p>";
        }
        
        echo "</div></div>";
    }
    
    /**
     * Muestra el estado reproductivo actual
     */
    public function mostrarReproduccionActual($idani) {
        $animal = $this->mostrarAnimal($idani);
        
        // Solo mostrar para hembras
        if ($animal['sexani'] != 1) {
            return;
        }
        
        $repro_actual = $this->obtenerReproduccionActual($idani);
        
        echo "<div class='card mb-3'>";
        echo "<div class='card-header'><h6><i class='fas fa-heartbeat'></i> Estado Actual</h6></div>";
        echo "<div class='card-body'>";
        
        if ($repro_actual) {
            // Tipo de proceso
            echo "<div class='mb-2'>";
            echo "<strong>Último Proceso:</strong><br>";
            $color_proceso = $this->colores_tiprep[$repro_actual['tiprep']];
            echo "<span class='badge' style='background-color: {$color_proceso}; color: white;'>";
            echo "{$this->tiprep[$repro_actual['tiprep']]}";
            echo "</span>";
            echo "</div>";
            
            // Fecha del proceso
            if ($repro_actual['fecpro']) {
                echo "<div class='mb-2'>";
                echo "<strong>Fecha Proceso:</strong><br>";
                echo "<span>{$repro_actual['fecpro']}</span>";
                echo "</div>";
            }
            
            // Estado del resultado
            echo "<div class='mb-2'>";
            echo "<strong>Resultado:</strong><br>";
            $color_resultado = $this->colores_tipres[$repro_actual['tipres']];
            echo "<span class='badge' style='background-color: {$color_resultado}; color: white;'>";
            echo "{$this->tipres[$repro_actual['tipres']]}";
            echo "</span>";
            echo "</div>";
            
            // Fechas importantes
            if ($repro_actual['fecrev'] && $repro_actual['fecrev'] != '1900-01-01') {
                echo "<div class='mb-2'>";
                echo "<strong>Fecha Revisión:</strong><br>";
                echo "<span class='text-warning'>{$repro_actual['fecrev']}</span>";
                echo "</div>";
            }
            
            if ($repro_actual['fecres'] && $repro_actual['fecres'] != '1900-01-01') {
                echo "<div class='mb-2'>";
                echo "<strong>Fecha Resultado:</strong><br>";
                echo "<span class='text-info'>{$repro_actual['fecres']}</span>";
                echo "</div>";
            }
            
            // Estado de secado si aplica
            if (isset($repro_actual['estsec'])) {
                echo "<div class='mb-2'>";
                echo "<strong>Estado Secado:</strong><br>";
                $color_secado = $this->colores_estsec[$repro_actual['estsec']];
                echo "<span class='badge' style='background-color: {$color_secado}; color: white;'>";
                echo "{$this->estsec[$repro_actual['estsec']]}";
                echo "</span>";
                echo "</div>";
            }
            
            // Días desde último proceso
            if ($repro_actual['fecpro']) {
                $dias_desde = (new DateTime())->diff(new DateTime($repro_actual['fecpro']))->days;
                echo "<div class='mt-2'>";
                echo "<small class='text-muted'>Hace {$dias_desde} días</small>";
                echo "</div>";
            }
            
        } else {
            echo "<p class='text-muted'>Sin procesos reproductivos registrados</p>";
        }
        
        echo "</div></div>";
    }
    
    /**
     * Construye el árbol genealógico completo
     */
    private function construirArbolGenealogico($idani) {
        $animal = $this->mostrarAnimal($idani);
        $arbol = array();
        
        // Obtener padres
        $arbol['padre'] = $animal['idpadre'] > 0 ? $this->mostrarAnimal($animal['idpadre']) : null;
        $arbol['madre'] = $animal['idmadre'] > 0 ? $this->mostrarAnimal($animal['idmadre']) : null;
        
        // Obtener abuelos
        if ($arbol['padre']) {
            $arbol['abuelo_paterno'] = $arbol['padre']['idpadre'] > 0 ? $this->mostrarAnimal($arbol['padre']['idpadre']) : null;
            $arbol['abuela_paterna'] = $arbol['padre']['idmadre'] > 0 ? $this->mostrarAnimal($arbol['padre']['idmadre']) : null;
        } else {
            $arbol['abuelo_paterno'] = null;
            $arbol['abuela_paterna'] = null;
        }
        
        if ($arbol['madre']) {
            $arbol['abuelo_materno'] = $arbol['madre']['idpadre'] > 0 ? $this->mostrarAnimal($arbol['madre']['idpadre']) : null;
            $arbol['abuela_materna'] = $arbol['madre']['idmadre'] > 0 ? $this->mostrarAnimal($arbol['madre']['idmadre']) : null;
        } else {
            $arbol['abuelo_materno'] = null;
            $arbol['abuela_materna'] = null;
        }
        
        // Obtener hermanos (medios hermanos que comparten padre o madre)
        $arbol['hermanos'] = $this->obtenerHermanos($idani, 2); // Machos
        $arbol['hermanas'] = $this->obtenerHermanos($idani, 1); // Hembras
        
        // Obtener tíos
        $arbol['tios_paternos'] = $this->obtenerTios($animal['idpadre'], 2);
        $arbol['tias_paternas'] = $this->obtenerTios($animal['idpadre'], 1);
        $arbol['tios_maternos'] = $this->obtenerTios($animal['idmadre'], 2);
        $arbol['tias_maternas'] = $this->obtenerTios($animal['idmadre'], 1);
        
        // Obtener descendencia (donde el animal actual es padre O madre)
        $arbol['hijos'] = $this->obtenerDescendencia($idani, 2);
        $arbol['hijas'] = $this->obtenerDescendencia($idani, 1);
        
        // Obtener sobrinos (hijos de hermanos)
        $arbol['sobrinos'] = $this->obtenerSobrinos($idani, 2);
        $arbol['sobrinas'] = $this->obtenerSobrinos($idani, 1);
        
        return $arbol;
    }
    
    /**
     * Obtiene hermanos del animal (animales que comparten padre O madre)
     */
    private function obtenerHermanos($idani, $sexo) {
        $animal = $this->mostrarAnimal($idani);
        $hermanos = array();
        
        // Solo buscar hermanos si al menos uno de los padres es conocido (arete != 0)
        if ($animal['idpadre'] > 0 || $animal['idmadre'] > 0) {
            $condiciones = array();
            
            // Si tiene padre conocido (no genérico)
            if ($animal['idpadre'] > 0) {
                $padre = $this->mostrarAnimal($animal['idpadre']);
                if ($padre && $padre['arete'] != 0) {
                    $condiciones[] = 'idpadre = '.$animal['idpadre'];
                }
            }
            
            // Si tiene madre conocida (no genérica)
            if ($animal['idmadre'] > 0) {
                $madre = $this->mostrarAnimal($animal['idmadre']);
                if ($madre && $madre['arete'] != 0) {
                    $condiciones[] = 'idmadre = '.$animal['idmadre'];
                }
            }
            
            // Si hay al menos una condición válida
            if (!empty($condiciones)) {
                $query = 'SELECT * FROM "ANIMALES" WHERE 
                          ('.implode(' OR ', $condiciones).') AND 
                          sexani = '.$sexo.' AND 
                          id != '.$idani.' AND 
                          idhac = '.$_SESSION['idhac'].'
                          ORDER BY fecnac';
                
                $result = $this->consulta($query);
                while ($row = $this->row($result)) {
                    $hermanos[] = $row;
                }
            }
        }
        
        return $hermanos;
    }
    
    /**
     * Obtiene tíos del animal
     */
    private function obtenerTios($idpadre, $sexo) {
        $tios = array();
        
        if ($idpadre > 0) {
            $padre = $this->mostrarAnimal($idpadre);
            
            // Verificar que el padre no sea genérico (arete != 0) y tenga padres conocidos
            if ($padre && $padre['arete'] != 0 && $padre['idpadre'] > 0 && $padre['idmadre'] > 0) {
                $abuelo = $this->mostrarAnimal($padre['idpadre']);
                $abuela = $this->mostrarAnimal($padre['idmadre']);
                
                // Verificar que los abuelos tampoco sean genéricos
                if ($abuelo && $abuela && $abuelo['arete'] != 0 && $abuela['arete'] != 0) {
                    $query = 'SELECT * FROM "ANIMALES" WHERE 
                              idpadre = '.$padre['idpadre'].' AND 
                              idmadre = '.$padre['idmadre'].' AND 
                              sexani = '.$sexo.' AND 
                              id != '.$idpadre.' AND 
                              idhac = '.$_SESSION['idhac'].'
                              ORDER BY fecnac';
                    
                    $result = $this->consulta($query);
                    while ($row = $this->row($result)) {
                        $tios[] = $row;
                    }
                }
            }
        }
        
        return $tios;
    }
    
    /**
     * Obtiene descendencia del animal (animales que tienen como padre O madre al animal actual)
     */
    private function obtenerDescendencia($idani, $sexo) {
        $descendencia = array();
        
        // Buscar animales que tienen como padre O madre al animal actual
        $query = 'SELECT * FROM "ANIMALES" WHERE 
                  (idpadre = '.$idani.' OR idmadre = '.$idani.') AND 
                  sexani = '.$sexo.' AND 
                  idhac = '.$_SESSION['idhac'].'
                  ORDER BY fecnac';
        
        // DEBUG: Mostrar la consulta (comentar después de revisar)
        // echo "<small>DEBUG - Consulta descendencia: " . $query . "</small><br>";
        
        $result = $this->consulta($query);
        while ($row = $this->row($result)) {
            $descendencia[] = $row;
        }
        
        return $descendencia;
    }
    
    /**
     * Función de DEBUG para mostrar información del animal
     */
    public function debugAnimal($idani) {
        $animal = $this->mostrarAnimal($idani);
        echo "<div class='alert alert-info'>";
        echo "<h5>DEBUG - Animal ID: {$idani}</h5>";
        echo "<p>Nombre: {$animal['nombre']}</p>";
        echo "<p>Sexo: {$this->sexani[$animal['sexani']]}</p>";
        echo "<p>idhac: {$animal['idhac']}</p>";
        
        // Buscar hijos
        $query_hijos = 'SELECT id, nombre, sexani, idpadre, idmadre FROM "ANIMALES" WHERE 
                        (idpadre = '.$idani.' OR idmadre = '.$idani.') AND 
                        idhac = '.$_SESSION['idhac'];
        
        echo "<p><strong>Consulta hijos:</strong> {$query_hijos}</p>";
        
        $result = $this->consulta($query_hijos);
        $contador = 0;
        echo "<p><strong>Hijos encontrados:</strong></p><ul>";
        while ($row = $this->row($result)) {
            $contador++;
            echo "<li>ID: {$row['id']}, Nombre: {$row['nombre']}, Sexo: {$this->sexani[$row['sexani']]}, Padre: {$row['idpadre']}, Madre: {$row['idmadre']}</li>";
        }
        echo "</ul>";
        echo "<p>Total hijos: {$contador}</p>";
        echo "</div>";
    }
    
    /**
     * Obtiene sobrinos (hijos de hermanos)
     */
    private function obtenerSobrinos($idani, $sexo) {
        // Solo obtener hermanos de padres conocidos (no genéricos)
        $hermanos = array_merge(
            $this->obtenerHermanos($idani, 1), 
            $this->obtenerHermanos($idani, 2)
        );
        
        $sobrinos = array();
        
        foreach ($hermanos as $hermano) {
            // Solo buscar sobrinos si el hermano no es genérico
            if ($hermano['arete'] != 0) {
                $campo_padre = ($hermano['sexani'] == 2) ? 'idpadre' : 'idmadre';
                
                $query = 'SELECT * FROM "ANIMALES" WHERE 
                          '.$campo_padre.' = '.$hermano['id'].' AND 
                          sexani = '.$sexo.' AND
                          idhac = '.$_SESSION['idhac'];
                
                $result = $this->consulta($query);
                while ($row = $this->row($result)) {
                    $sobrinos[] = $row;
                }
            }
        }
        
        return $sobrinos;
    }
    
    /**
     * Obtiene nietos del animal (hijos de los hijos)
     */
    private function obtenerNietos($idani) {
        $nietos = array();
        
        // Primero obtener todos los hijos del animal actual
        $hijos = array_merge(
            $this->obtenerDescendencia($idani, 1), // Hijas
            $this->obtenerDescendencia($idani, 2)  // Hijos
        );
        
        // Para cada hijo, buscar sus descendientes (nietos del animal original)
        foreach ($hijos as $hijo) {
            $nietos_por_hijo = array_merge(
                $this->obtenerDescendencia($hijo['id'], 1), // Nietas
                $this->obtenerDescendencia($hijo['id'], 2)  // Nietos
            );
            
            // Agregar información del padre/madre para referencia
            foreach ($nietos_por_hijo as &$nieto) {
                $nieto['padre_nieto'] = $hijo['nombre']; // Nombre del padre/madre del nieto
                $nieto['sexo_padre_nieto'] = $hijo['sexani']; // Sexo del padre/madre
            }
            
            $nietos = array_merge($nietos, $nietos_por_hijo);
        }
        
        // Eliminar duplicados si un animal aparece por ambos lados
        $nietos_unicos = array();
        $ids_vistos = array();
        
        foreach ($nietos as $nieto) {
            if (!in_array($nieto['id'], $ids_vistos)) {
                $nietos_unicos[] = $nieto;
                $ids_vistos[] = $nieto['id'];
            }
        }
        
        // Ordenar por fecha de nacimiento
        usort($nietos_unicos, function($a, $b) {
            return strcmp($a['fecnac'], $b['fecnac']);
        });
        
        return $nietos_unicos;
    }
    
    /**
     * Muestra la leyenda de estados en el árbol genealógico
     */
    private function mostrarLeyendaEstados() {
        echo "<div class='mt-2'>";
        echo "<small><strong>Estados:</strong> ";
        
        foreach ($this->iconos_estado as $estado => $icono) {
            $color = $this->colores_estado[$estado];
            $nombre = $this->esthac[$estado];
            
            echo "<span style='display: inline-block; margin: 0 5px;'>";
            echo "<span style='background-color: {$color}; color: white; border-radius: 50%; ";
            echo "width: 16px; height: 16px; display: inline-flex; align-items: center; ";
            echo "justify-content: center; font-size: 8px; margin-right: 3px; ";
            echo "box-shadow: 0 1px 2px rgba(0,0,0,0.2);'>{$icono}</span>";
            echo "<span style='font-size: 10px;'>{$nombre}</span>";
            echo "</span>";
        }
        
        echo "</small>";
        echo "</div>";
    }
    
    /**
     * Muestra un animal en formato mini para el árbol
     */
    private function mostrarAnimalMini($animal, $relacion) {
        if (!$animal) {
            echo "<div class='border rounded p-1 text-center' style='min-height: 60px; background-color: #f8f9fa;'>";
            echo "<small class='text-muted'>{$relacion}<br>No registrado</small>";
            echo "</div>";
            return;
        }
        
        $edad = $this->calcularEdadSimple($animal['fecnac']);
        $color_borde = ($animal['sexani'] == 1) ? 'border-danger' : 'border-primary';
        
        // Obtener icono y color del estado
        $icono_estado = isset($this->iconos_estado[$animal['esthac']]) ? $this->iconos_estado[$animal['esthac']] : '❓';
        $color_estado = isset($this->colores_estado[$animal['esthac']]) ? $this->colores_estado[$animal['esthac']] : '#6c757d';
        $nombre_estado = $this->esthac[$animal['esthac']];
        
        echo "<form method='post' style='margin: 0; padding: 0;'>";
        echo "<div class='border rounded p-1 text-center {$color_borde}' style='min-height: 70px; transition: all 0.3s; position: relative;' ";
        echo "onmouseover='this.style.backgroundColor=\"#e9ecef\"' ";
        echo "onmouseout='this.style.backgroundColor=\"white\"'>";
        
        // Icono de estado en la esquina superior derecha
        echo "<div style='position: absolute; top: 2px; right: 2px; background-color: {$color_estado}; ";
        echo "border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; ";
        echo "font-size: 10px; color: white; box-shadow: 0 1px 3px rgba(0,0,0,0.3);' ";
        echo "title='{$nombre_estado}'>{$icono_estado}</div>";
        
        echo "<small><strong>{$animal['nombre']}</strong><br>";
        echo "#{$animal['arete']}<br>";
        echo "{$edad}<br>";
        echo "<span class='text-muted'>{$relacion}</span></small><br>";
        
        // Botón Ver solo si el animal está vivo (no muerto, no perdido)
        if ($animal['esthac'] != 3 && $animal['esthac'] != 4) {
            echo "<button type='submit' name='bttdashboard' value='{$animal['id']}' ";
            echo "class='btn btn-sm btn-outline-primary mt-1' ";
            echo "style='font-size: 10px; padding: 2px 6px;' ";
            echo "title='Ver dashboard de {$animal['nombre']}'>";
            echo "<img src='../img/ver.jpg' width='12' height='12'> Ver";
            echo "</button>";
        } else {
            echo "<small class='text-muted mt-1 d-block'>{$nombre_estado}</small>";
        }
        
        echo "</div>";
        echo "</form>";
    }
    
    /**
     * Muestra un nieto en formato mini con información especial
     */
    private function mostrarAnimalMiniNieto($animal, $relacion) {
        if (!$animal) {
            echo "<div class='border rounded p-1 text-center' style='min-height: 60px; background-color: #f8f9fa;'>";
            echo "<small class='text-muted'>{$relacion}<br>No registrado</small>";
            echo "</div>";
            return;
        }
        
        $edad = $this->calcularEdadSimple($animal['fecnac']);
        $color_borde = ($animal['sexani'] == 1) ? 'border-danger' : 'border-primary';
        
        // Obtener icono y color del estado
        $icono_estado = isset($this->iconos_estado[$animal['esthac']]) ? $this->iconos_estado[$animal['esthac']] : '❓';
        $color_estado = isset($this->colores_estado[$animal['esthac']]) ? $this->colores_estado[$animal['esthac']] : '#6c757d';
        $nombre_estado = $this->esthac[$animal['esthac']];
        
        echo "<form method='post' style='margin: 0; padding: 0;'>";
        echo "<div class='border rounded p-1 text-center {$color_borde}' style='min-height: 80px; transition: all 0.3s; position: relative; background: linear-gradient(135deg, #e3f2fd 0%, #ffffff 100%);' ";
        echo "onmouseover='this.style.backgroundColor=\"#e1f5fe\"' ";
        echo "onmouseout='this.style.backgroundColor=\"\"'>";
        
        // Icono de estado en la esquina superior derecha
        echo "<div style='position: absolute; top: 2px; right: 2px; background-color: {$color_estado}; ";
        echo "border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; ";
        echo "font-size: 10px; color: white; box-shadow: 0 1px 3px rgba(0,0,0,0.3);' ";
        echo "title='{$nombre_estado}'>{$icono_estado}</div>";
        
        // Icono especial para nietos
        echo "<div style='position: absolute; top: 2px; left: 2px; background-color: #007bff; ";
        echo "border-radius: 50%; width: 18px; height: 18px; display: flex; align-items: center; justify-content: center; ";
        echo "font-size: 10px; color: white; box-shadow: 0 1px 3px rgba(0,0,0,0.3);' ";
        echo "title='Nieto/a'><i class='fas fa-baby' style='font-size: 8px;'></i></div>";
        
        echo "<small><strong>{$animal['nombre']}</strong><br>";
        echo "#{$animal['arete']}<br>";
        echo "{$edad}<br>";
        
        // Mostrar información del padre/madre si está disponible
        if (isset($animal['padre_nieto'])) {
            $icono_padre = $animal['sexo_padre_nieto'] == 1 ? '👩' : '👨';
            echo "<span class='text-muted' style='font-size: 9px;'>{$icono_padre} {$animal['padre_nieto']}</span><br>";
        }
        
        echo "<span class='text-info' style='font-size: 9px;'>Nieto/a</span></small><br>";
        
        // Botón Ver solo si el animal está vivo
        if ($animal['esthac'] != 3 && $animal['esthac'] != 4) {
            echo "<button type='submit' name='bttdashboard' value='{$animal['id']}' ";
            echo "class='btn btn-sm btn-outline-info mt-1' ";
            echo "style='font-size: 10px; padding: 2px 6px;' ";
            echo "title='Ver dashboard de {$animal['nombre']}'>";
            echo "<img src='../img/ver.jpg' width='12' height='12'> Ver";
            echo "</button>";
        } else {
            echo "<small class='text-muted mt-1 d-block'>{$nombre_estado}</small>";
        }
        
        echo "</div>";
        echo "</form>";
    }
    
    /**
     * Calcula estadísticas de vida del animal
     */
    private function calcularEstadisticasVida($idani) {
        $animal = $this->mostrarAnimal($idani);
        $stats = array();
        
        // Días de vida
        $fecha_nac = new DateTime($animal['fecnac']);
        $fecha_actual = new DateTime();
        $stats['dias_vida'] = $fecha_actual->diff($fecha_nac)->days;
        
        // Controles veterinarios
        $query = 'SELECT COUNT(*) as total FROM "CONTROLES" WHERE idani = '.$idani;
        $result = $this->consulta($query);
        $row = $this->row($result);
        $stats['controles'] = $row['total'];
        
        // Reproducciones
        $campo = ($animal['sexani'] == 1) ? 'idmadre' : 'idpadre';
        $query = 'SELECT COUNT(*) as total FROM "REPRODUCCION" WHERE '.$campo.' = '.$idani;
        $result = $this->consulta($query);
        $row = $this->row($result);
        $stats['reproducciones'] = $row['total'];
        
        // Crías
        $query = 'SELECT COUNT(*) as total FROM "ANIMALES" WHERE '.$campo.' = '.$idani.' AND esthac = 1';
        $result = $this->consulta($query);
        $row = $this->row($result);
        $stats['crias'] = $row['total'];
        
        // Nietos (hijos de los hijos)
        $nietos = $this->obtenerNietos($idani);
        $nietos_vivos = 0;
        foreach ($nietos as $nieto) {
            if ($nieto['esthac'] == 1) {
                $nietos_vivos++;
            }
        }
        $stats['nietos'] = $nietos_vivos;
        
        // Peso estimado actual mejorado para ganado vacuno
        $stats['peso_actual'] = $this->calcularPesoEstimadoVacuno($animal);
        
        return $stats;
    }
    
    /**
     * Verifica si es una vaca lechera
     */
    private function esVacaLechera($idani) {
        $animal = $this->mostrarAnimal($idani);
        return ($animal['sexani'] == 1 && in_array($animal['estrep'], [4, 5])); // Leche o Seca
    }
    
    /**
     * Obtiene datos de producción lechera
     */
    private function obtenerProduccionLechera($idani) {
        if (!$this->esVacaLechera($idani)) {
            return array();
        }
        
        $produccion = array(
            'promedio_semanal' => 0,
            'promedio_mensual' => 0,
            'maxima' => 0,
            'total_litros' => 0
        );
        
        // Últimos 7 días
        $query = 'SELECT AVG(da.lit) as promedio FROM "DIARIO_ANIMAL" da 
                  INNER JOIN "DIARIO" d ON da.iddia = d.iddia 
                  WHERE da.idani = '.$idani.' AND 
                  d.fecdia >= CURRENT_DATE - INTERVAL \'7 days\'';
        $result = $this->consulta($query);
        if ($row = $this->row($result)) {
            $produccion['promedio_semanal'] = round($row['promedio'], 1);
        }
        
        // Últimos 30 días
        $query = 'SELECT AVG(da.lit) as promedio FROM "DIARIO_ANIMAL" da 
                  INNER JOIN "DIARIO" d ON da.iddia = d.iddia 
                  WHERE da.idani = '.$idani.' AND 
                  d.fecdia >= CURRENT_DATE - INTERVAL \'30 days\'';
        $result = $this->consulta($query);
        if ($row = $this->row($result)) {
            $produccion['promedio_mensual'] = round($row['promedio'], 1);
        }
        
        // Máxima producción
        $query = 'SELECT MAX(da.lit) as maxima FROM "DIARIO_ANIMAL" da WHERE idani = '.$idani;
        $result = $this->consulta($query);
        if ($row = $this->row($result)) {
            $produccion['maxima'] = round($row['maxima'], 1);
        }
        
        // Total acumulado
        $query = 'SELECT SUM(da.lit) as total FROM "DIARIO_ANIMAL" da WHERE idani = '.$idani;
        $result = $this->consulta($query);
        if ($row = $this->row($result)) {
            $produccion['total_litros'] = round($row['total'], 0);
        }
        
        return $produccion;
    }
    
    /**
     * Calcula estadísticas reproductivas completas
     */
    private function calcularEstadisticasReproduccion($idani) {
        $stats = array(
            'total_procesos' => 0,
            'total_partos' => 0,
            'total_abortos' => 0,
            'total_vacias' => 0,
            'total_confirmados' => 0,
            'total_por_revisar' => 0,
            'por_resultado' => array(0 => 0, 1 => 0, 2 => 0, 3 => 0, 4 => 0),
            'por_proceso' => array(0 => 0, 1 => 0, 2 => 0, 3 => 0),
            'promedio_dias_entre_partos' => 0,
            'ultimo_parto' => null,
            'edad_primer_parto' => null
        );
        
        // Obtener todos los registros de reproducción
        $query = 'SELECT * FROM "REPRODUCCION" WHERE idmadre = '.$idani.' ORDER BY fecpro ASC';
        $result = $this->consulta($query);
        
        $fechas_partos = array();
        $primer_parto = true;
        
        while ($row = $this->row($result)) {
            $stats['total_procesos']++;
            
            // Contar por tipo de resultado
            $tipres = $row['tipres'];
            $stats['por_resultado'][$tipres]++;
            
            switch ($tipres) {
                case 1: // Vacía
                    $stats['total_vacias']++;
                    break;
                case 2: // Aborto
                    $stats['total_abortos']++;
                    break;
                case 3: // Parto
                    $stats['total_partos']++;
                    if ($row['fecres'] && $row['fecres'] != '1900-01-01') {
                        $fechas_partos[] = $row['fecres'];
                        $stats['ultimo_parto'] = $row['fecres'];
                        
                        // Calcular edad del primer parto
                        if ($primer_parto) {
                            $animal = $this->mostrarAnimal($idani);
                            $fecha_nac = new DateTime($animal['fecnac']);
                            $fecha_parto = new DateTime($row['fecres']);
                            $edad_meses = $fecha_nac->diff($fecha_parto)->days / 30.44;
                            $stats['edad_primer_parto'] = round($edad_meses, 1);
                            $primer_parto = false;
                        }
                    }
                    break;
                case 4: // Confirmado
                    $stats['total_confirmados']++;
                    break;
                case 0: // Por revisar
                    $stats['total_por_revisar']++;
                    break;
            }
            
            // Contar por tipo de proceso
            $tiprep = $row['tiprep'];
            $stats['por_proceso'][$tiprep]++;
        }
        
        // Calcular promedio entre partos
        if (count($fechas_partos) > 1) {
            $total_dias = 0;
            for ($i = 1; $i < count($fechas_partos); $i++) {
                $fecha1 = new DateTime($fechas_partos[$i-1]);
                $fecha2 = new DateTime($fechas_partos[$i]);
                $total_dias += $fecha1->diff($fecha2)->days;
            }
            $stats['promedio_dias_entre_partos'] = round($total_dias / (count($fechas_partos) - 1));
        }
        
        return $stats;
    }
    
    /**
     * Obtiene la información de la reproducción actual
     */
    private function obtenerReproduccionActual($idani) {
        $query = 'SELECT * FROM "REPRODUCCION" WHERE idmadre = '.$idani.' 
                  ORDER BY fecpro DESC LIMIT 1';
        $result = $this->consulta($query);
        return $this->row($result);
    }
    
    /**
     * Obtiene estado reproductivo actual (función de compatibilidad)
     */
    private function obtenerEstadoReproductivo($idani) {
        $animal = $this->mostrarAnimal($idani);
        if ($animal['sexani'] != 1) { // Solo hembras
            return null;
        }
        
        $query = 'SELECT * FROM "REPRODUCCION" WHERE idmadre = '.$idani.' 
                  ORDER BY fecpro DESC LIMIT 1';
        $result = $this->consulta($query);
        $repro = $this->row($result);
        
        if (!$repro) {
            return null;
        }
        
        $estado = array();
        $estado['tipo_proceso'] = $this->tiprep[$repro['tiprep']];
        $estado['fecha_proceso'] = $repro['fecpro'];
        $estado['estado_actual'] = $this->tipres[$repro['tipres']];
        
        // Calcular fecha esperada según el tipo de resultado
        if ($repro['tipres'] == 0) { // Por revisar
            $estado['fecha_esperada'] = date('Y-m-d', strtotime($repro['fecpro'] . ' +60 days'));
            $estado['color_estado'] = 'warning';
        } elseif ($repro['tipres'] == 4) { // Confirmado
            $estado['fecha_esperada'] = date('Y-m-d', strtotime($repro['fecpro'] . ' +280 days'));
            $estado['color_estado'] = 'success';
        } else {
            $estado['fecha_esperada'] = null;
            $estado['color_estado'] = 'secondary';
        }
        
        return $estado;
    }
    
    /**
     * Calcula edad en formato legible
     */
    private function calcularEdad($fecha_nac) {
        $fecha_nac = new DateTime($fecha_nac);
        $fecha_actual = new DateTime();
        $diff = $fecha_actual->diff($fecha_nac);
        
        if ($diff->y > 0) {
            return $diff->y . ' año' . ($diff->y > 1 ? 's' : '') . 
                   ($diff->m > 0 ? ' y ' . $diff->m . ' mes' . ($diff->m > 1 ? 'es' : '') : '');
        } elseif ($diff->m > 0) {
            return $diff->m . ' mes' . ($diff->m > 1 ? 'es' : '');
        } else {
            return $diff->d . ' día' . ($diff->d > 1 ? 's' : '');
        }
    }

    
    /**
     * Calcula edad simple para mostrar en el árbol
     */
    private function calcularEdadSimple($fecha_nac) {
        $fecha_nac = new DateTime($fecha_nac);
        $fecha_actual = new DateTime();
        $diff = $fecha_actual->diff($fecha_nac);
        
        if ($diff->y > 0) {
            return $diff->y . 'a';
        } elseif ($diff->m > 0) {
            return $diff->m . 'm';
        } else {
            return $diff->d . 'd';
        }
    }
    
    /**
     * Calcula el peso estimado actual de un animal vacuno de forma realista
     * @param array $animal - Array con los datos del animal
     * @return float - Peso estimado en kg
     */
    private function calcularPesoEstimadoVacuno($animal) {
        // Solo calcular para animales vacunos
        if ($animal['espani'] != 1) {
            return 0;
        }
        
        $fecha_nac = new DateTime($animal['fecnac']);
        $fecha_actual = new DateTime();
        $edad_dias = $fecha_actual->diff($fecha_nac)->days;
        $edad_meses = round($edad_dias / 30.44);
        
        // Validar que la fecha de nacimiento sea válida
        if ($edad_dias < 0) {
            return 0;
        }
        
        $sexo = $animal['sexani']; // 1 = Hembra, 2 = Macho
        
        // Pesos base - usar peso registrado o promedio según sexo
        $peso_nacimiento = !empty($animal['pesonac']) && $animal['pesonac'] > 0 ? 
                          $animal['pesonac'] : ($sexo == 1 ? 35 : 40);
        $peso_llegada = !empty($animal['pesolle']) && $animal['pesolle'] > 0 ? 
                       $animal['pesolle'] : $peso_nacimiento;
        
        // Usar el peso más reciente como referencia
        $peso_base = $peso_llegada;
        
        // Cálculo por etapas de crecimiento según edad
        if ($edad_meses <= 6) {
            // Terneros (0-6 meses): crecimiento acelerado
            $ganancia_diaria = $sexo == 1 ? 0.7 : 0.8; // kg/día
            $peso_estimado = $peso_base + ($edad_dias * $ganancia_diaria);
            
        } elseif ($edad_meses <= 12) {
            // Destete (6-12 meses): crecimiento moderado-alto
            $peso_6_meses = $peso_base + (180 * ($sexo == 1 ? 0.7 : 0.8));
            $dias_adicionales = $edad_dias - 180;
            $ganancia_diaria = $sexo == 1 ? 0.6 : 0.7;
            $peso_estimado = $peso_6_meses + ($dias_adicionales * $ganancia_diaria);
            
        } elseif ($edad_meses <= 24) {
            // Juvenil (12-24 meses): desarrollo continuo
            $peso_12_meses = $sexo == 1 ? 250 : 300;
            $edad_anos = $edad_dias / 365.25;
            $factor_crecimiento = $sexo == 1 ? 150 : 200; // kg adicionales por año
            $peso_estimado = $peso_12_meses + (($edad_anos - 1) * $factor_crecimiento);
            
        } elseif ($edad_meses <= 36) {
            // Pre-adulto (24-36 meses): maduración
            $peso_24_meses = $sexo == 1 ? 380 : 500;
            $edad_anos = $edad_dias / 365.25;
            $factor_crecimiento = $sexo == 1 ? 60 : 80; // Crecimiento más lento
            $peso_estimado = $peso_24_meses + (($edad_anos - 2) * $factor_crecimiento);
            
        } else {
            // Adultos (más de 36 meses): peso estable con variaciones mínimas
            if ($sexo == 1) {
                // Hembras adultas: 450-550 kg según raza
                $peso_base_adulto = 500;
                $variacion_max = 50;
            } else {
                // Machos adultos: 600-800 kg según raza
                $peso_base_adulto = 700;
                $variacion_max = 100;
            }
            
            // Pequeña variación basada en edad (simula condición corporal)
            $factor_edad = min(($edad_dias - 1095) / 365.25, 2); // Máximo 2 años adicionales
            $variacion = ($factor_edad * 0.1 * $variacion_max);
            
            $peso_estimado = $peso_base_adulto + $variacion;
        }
        
        // Aplicar límites realistas
        if ($sexo == 1) {
            // Hembras: 25-650 kg (desde terneras hasta vacas grandes)
            $peso_estimado = max(25, min($peso_estimado, 650));
        } else {
            // Machos: 30-900 kg (desde terneros hasta toros grandes)
            $peso_estimado = max(30, min($peso_estimado, 900));
        }
        
        return round($peso_estimado, 1);
    }
    
    /**
     * Muestra estadísticas reproductivas para machos (como padres)
     */
    public function mostrarReproduccionMachos($idani) {
        $animal = $this->mostrarAnimal($idani);
        
        // Solo mostrar para machos
        if ($animal['sexani'] != 2) {
            return;
        }
        
        $stats = $this->calcularEstadisticasPadre($idani);
        
        echo "<div class='card mb-3'>";
        echo "<div class='card-header'><h6><i class='fas fa-male'></i> Estadísticas como Padre</h6></div>";
        echo "<div class='card-body'>";
        
        if ($stats['total_servicios'] > 0) {
            // Resumen general
            echo "<div class='row text-center mb-3'>";
            echo "<div class='col-4'>";
            echo "<h5 class='text-primary'>{$stats['total_servicios']}</h5>";
            echo "<small>Servicios</small>";
            echo "</div>";
            echo "<div class='col-4'>";
            echo "<h5 class='text-success'>{$stats['total_crias']}</h5>";
            echo "<small>Crías</small>";
            echo "</div>";
            echo "<div class='col-4'>";
            echo "<h5 class='text-info'>{$stats['crias_vivas']}</h5>";
            echo "<small>Vivas</small>";
            echo "</div>";
            echo "</div>";
            
            // Tasa de fertilidad
            echo "<div class='mb-3'>";
            echo "<strong>Tasa de Fertilidad:</strong> ";
            $tasa_fertilidad = $stats['total_servicios'] > 0 ? round(($stats['total_crias'] / $stats['total_servicios']) * 100, 1) : 0;
            $color_tasa = $tasa_fertilidad >= 80 ? 'success' : ($tasa_fertilidad >= 60 ? 'warning' : 'danger');
            echo "<span class='badge badge-{$color_tasa}'>{$tasa_fertilidad}%</span>";
            echo "</div>";
            
            // Distribución por sexo de las crías
            if ($stats['crias_machos'] > 0 || $stats['crias_hembras'] > 0) {
                echo "<div class='mb-2'><strong>Distribución de Crías:</strong></div>";
                echo "<div class='d-flex justify-content-between align-items-center mb-1'>";
                echo "<span><i class='fas fa-mars' style='color: #007bff;'></i> Machos</span>";
                echo "<span class='badge badge-primary'>{$stats['crias_machos']}</span>";
                echo "</div>";
                echo "<div class='d-flex justify-content-between align-items-center mb-1'>";
                echo "<span><i class='fas fa-venus' style='color: #e83e8c;'></i> Hembras</span>";
                echo "<span class='badge badge-secondary'>{$stats['crias_hembras']}</span>";
                echo "</div>";
            }
            
            // Última monta
            if ($stats['ultima_monta']) {
                echo "<div class='mt-3'>";
                echo "<strong>Última Monta:</strong><br>";
                echo "<span class='text-info'>{$stats['ultima_monta']}</span>";
                echo "</div>";
            }
            
        } else {
            echo "<p class='text-muted'>Sin registros como padre</p>";
        }
        
        echo "</div></div>";
    }
    
    /**
     * Calcula estadísticas reproductivas para machos
     */
    private function calcularEstadisticasPadre($idani) {
        $stats = array(
            'total_servicios' => 0,
            'total_crias' => 0,
            'crias_vivas' => 0,
            'crias_machos' => 0,
            'crias_hembras' => 0,
            'total_abortos' => 0,
            'ultima_monta' => null,
            'primera_monta' => null
        );
        
        // Obtener todos los servicios como padre
        $query = 'SELECT * FROM "REPRODUCCION" WHERE idpadre = '.$idani.' ORDER BY fecpro ASC';
        $result = $this->consulta($query);
        
        while ($row = $this->row($result)) {
            $stats['total_servicios']++;
            
            // Guardar fechas de montas
            if (!$stats['primera_monta']) {
                $stats['primera_monta'] = $row['fecpro'];
            }
            $stats['ultima_monta'] = $row['fecpro'];
            
            // Contar resultados
            switch ($row['tipres']) {
                case 2: // Aborto
                    $stats['total_abortos']++;
                    break;
                case 3: // Parto exitoso
                    $stats['total_crias']++;
                    
                    // Verificar si la cría está viva y contar por sexo
                    if ($row['idcria'] > 0) {
                        $cria = $this->mostrarAnimal($row['idcria']);
                        if ($cria && $cria['esthac'] == 1) { // Viva
                            $stats['crias_vivas']++;
                            if ($cria['sexani'] == 1) {
                                $stats['crias_hembras']++;
                            } else {
                                $stats['crias_machos']++;
                            }
                        }
                    }
                    break;
            }
        }
        
        return $stats;
    }
}
?>