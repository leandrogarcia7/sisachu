<?php
// Habilitar reporte de errores para debug
error_reporting(E_ALL);
ini_set('display_errors', 1);


require_once("../negocio/USUARIO.php");
require_once("../negocio/HACIENDA.php");

session_start();
require_once("../encabezado.php");   

// Verificar sesión
if(!isset($_SESSION['idhac'])){
    header("Location: ../login.php");
    exit;
}

$hacienda = new HACIENDA();
 $x = 0; // Variable para controlar si se ejecutó alguna acción

    // Actualizar datos de hacienda
    if(isset($_REQUEST['bttActualizarHacienda'])) {
    //    ECHO 'entra actualizar';
        $hacienda->actualizarHacienda($_REQUEST);
        $x++;
    }

    // Crear usuario
    if(isset($_REQUEST['bttCrearUsuario'])) {
        $hacienda->crearUsuarioHacienda($_REQUEST);
        $x++;
    }

    // Actualizar usuario
    if(isset($_REQUEST['bttActualizarUsuario'])) {
        $hacienda->actualizarUsuario($_REQUEST);
        $x++;
    }
    
    // Desactivar
if (isset($_REQUEST['desactivarUsuario'])) {
    $hacienda->desactivarUsuario($_REQUEST['desactivarUsuario']);
}

// Activar
if (isset($_REQUEST['activarUsuario'])) {
    $hacienda->activarUsuario($_REQUEST['activarUsuario']);
}

   // Mostrar formulario de edición de usuario
    if(isset($_REQUEST['editarUsuario'])) {
        echo '<script>
                $(document).ready(function() {
                    $("#v-pills-users-tab").click();
                    mostrarFormularioUsuario();
                });
              </script>';
        $hacienda->mostrarFormularioUsuario($_REQUEST['editarUsuario']);
        $x++;
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Gestión de Hacienda - Sistema</title>
    
    <style>
        .dashboard-card {
            transition: transform 0.2s;
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
        }
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
        }
        .nav-pills .nav-link {
            border-radius: 10px;
            margin-bottom: 5px;
        }
        .nav-pills .nav-link.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .alert-hacienda {
            border-left: 4px solid #007bff;
            border-radius: 0 10px 10px 0;
        }
        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
        }
        .btn-custom {
            border-radius: 20px;
            padding: 8px 20px;
        }
    </style>
    
    <script>
        
  
    
    // Validación simple de campos requeridos
    $(document).ready(function() {
        $('form').submit(function(e) {
            var form = $(this);
            var hasRequired = false;
            
            form.find('input[required], select[required]').each(function() {
                if($(this).val() === '') {
                    hasRequired = true;
                    $(this).addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid');
                }
            });
            
            if(hasRequired) {
                e.preventDefault();
                alert('Por favor complete todos los campos requeridos');
            }
        });
    });
</script>

</head>

<body>
    <?php
    $usu = new USUARIO();
    $usu->mostrarMenu(1, 1);
    ?>
    
    <center><h2 class="titulointerface">GESTIÓN DE HACIENDA</h2></center>
    <!-- Muestra la hora actual en Ecuador al inicio de la página -->


    <?php
    // Mostrar alertas importantes
  /*  try {
        $hacienda->mostrarAlertas();
    } catch (Exception $e) {
        echo '<div class="alert alert-warning">Error al cargar alertas: ' . $e->getMessage() . '</div>';
    }*/
    ?>
    
    <div class="container-fluid">
      
        
        <!-- Navegación por pestañas -->
        <div class="row">
            <div class="col-md-2">
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist">
                    <button class="nav-link active" id="v-pills-dashboard-tab" data-bs-toggle="pill" 
                            data-bs-target="#v-pills-dashboard" type="button" role="tab">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </button>
                    <button class="nav-link" id="v-pills-config-tab" data-bs-toggle="pill" 
                            data-bs-target="#v-pills-config" type="button" role="tab">
                        <i class="fas fa-cog"></i> Configuración
                    </button>
                    <button class="nav-link" id="v-pills-users-tab" data-bs-toggle="pill" 
                            data-bs-target="#v-pills-users" type="button" role="tab">
                        <i class="fas fa-users"></i> Usuarios
                    </button>
                    <button class="nav-link" id="v-pills-reports-tab" data-bs-toggle="pill" 
                            data-bs-target="#v-pills-reports" type="button" role="tab">
                        <i class="fas fa-chart-bar"></i> Reportes
                    </button>
                </div>
            </div>
            
            <div class="col-md-10">
                <div class="tab-content" id="v-pills-tabContent">
                    
                    <!-- Dashboard -->
                    <div class="tab-pane fade show active" id="v-pills-dashboard" role="tabpanel">
                        <?php 
                        try {
                            $hacienda->mostrarDashboard(); 
                        } catch (Exception $e) {
                            echo '<div class="alert alert-danger">Error al cargar dashboard: ' . $e->getMessage() . '</div>';
                        }
                        ?>
                    </div>
                    
                    <!-- Configuración -->
                    <div class="tab-pane fade" id="v-pills-config" role="tabpanel">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="fas fa-cog"></i> Configuración de Hacienda</h5>
                            </div>
                            <div class="card-body">
                                <?php 
                                try {
                                    $hacienda->mostrarFormularioHacienda(); 
                                    
                                } catch (Exception $e) {
                                    echo '<div class="alert alert-danger">Error al cargar configuración: ' . $e->getMessage() . '</div>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    
<!-- Usuarios -->
<div class="tab-pane fade" id="v-pills-users" role="tabpanel">
    <div class="card">
        <div class="card-header">
            <h5><i class="fas fa-users"></i> Gestión de Usuarios</h5>
        </div>
        <div class="card-body">
            <?php 
            try {
                // ✅ Primero mostrar listado de usuarios
                $hacienda->mostrarUsuarios(); 
                
                // ✅ Luego generar el formulario oculto (indispensable)
                $hacienda->mostrarFormularioUsuario(); 
            } catch (Exception $e) {
                echo '<div class="alert alert-danger">Error al cargar usuarios: ' . $e->getMessage() . '</div>';
            }
            ?>
        </div>
    </div>
</div>



                    
                    <!-- Reportes -->
                    <div class="tab-pane fade" id="v-pills-reports" role="tabpanel">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="fas fa-chart-bar"></i> Reportes y Exportación</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Generar Reporte Mensual</h6>
                                        <form method="post" class="mb-4">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <select name="mes" class="form-control" required>
                                                        <option value="">Seleccionar Mes</option>
                                                        <?php
                                                        $meses = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                                                                      'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
                                                        for($i = 1; $i <= 12; $i++) {
                                                            $selected = (date('n') == $i) ? 'selected' : '';
                                                            echo '<option value="'.$i.'" '.$selected.'>'.$meses[$i].'</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <select name="anio" class="form-control" required>
                                                        <?php
                                                        $anio_actual = date('Y');
                                                        for($i = $anio_actual; $i >= ($anio_actual - 5); $i--) {
                                                            echo '<option value="'.$i.'">'.$i.'</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <button type="submit" name="bttGenerarReporte" class="btn btn-primary btn-custom mt-2">
                                                <i class="fas fa-file-alt"></i> Generar Reporte
                                            </button>
                                        </form>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <h6>Exportar Datos</h6>
                                        <form method="post" class="mb-4">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <select name="tipo_exportacion" class="form-control mb-2" required>
                                                        <option value="">Tipo de Datos</option>
                                                        <option value="animales">Animales</option>
                                                        <option value="leche">Producción de Leche</option>
                                                        <option value="entregas">Entregas</option>
                                                        <option value="financiero">Movimientos Financieros</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="date" name="fecha_inicio" class="form-control" 
                                                           value="<?php echo date('Y-m-01'); ?>" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="date" name="fecha_fin" class="form-control" 
                                                           value="<?php echo date('Y-m-d'); ?>" required>
                                                </div>
                                            </div>
                                            <button type="submit" name="bttExportar" class="btn btn-success btn-custom mt-2">
                                                <i class="fas fa-download"></i> Exportar a Excel
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        
        
     
        
        function editarUsuario(id) {
            window.location.href = '?editarUsuario=' + id;
        }
        
        // Validación de formularios
        $(document).ready(function() {
            $('form').submit(function(e) {
                var form = $(this);
                var hasRequired = false;
                
                form.find('input[required], select[required]').each(function() {
                    if($(this).val() === '') {
                        hasRequired = true;
                        $(this).addClass('is-invalid');
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });
                
                if(hasRequired) {
                    e.preventDefault();
                    alert('Por favor complete todos los campos requeridos');
                }
            });
        });
    </script>
<script>
function mostrarFormularioUsuario() {
    const formDiv = document.getElementById('formularioUsuario');
    if (formDiv) {
        formDiv.style.display = 'block';
        formDiv.scrollIntoView({ behavior: 'smooth' });
    }
}

function ocultarFormularioUsuario() {
    const formDiv = document.getElementById('formularioUsuario');
    if (formDiv) {
        formDiv.style.display = 'none';
    }
}
</script>


</body>
</html>

<?php
// Procesamiento de formularios con manejo de errores
try {
   

 

    // Generar reporte mensual
    if(isset($_REQUEST['bttGenerarReporte'])) {
        echo '<script>
                $(document).ready(function() {
                    $("#v-pills-reports-tab").click();
                });
              </script>';
        $reporte = $hacienda->generarReporteMensual($_REQUEST['mes'], $_REQUEST['anio']);
        $hacienda->mostrarReporteMensual($reporte, $_REQUEST['mes'], $_REQUEST['anio']);
        $x++;
    }

    // Exportar datos
    if(isset($_REQUEST['bttExportar'])) {
        $hacienda->exportarDatos($_REQUEST['tipo_exportacion'], $_REQUEST['fecha_inicio'], $_REQUEST['fecha_fin']);
        $x++;
    }

} catch (Exception $e) {
    echo '<div class="alert alert-danger">Error en el procesamiento: ' . $e->getMessage() . '</div>';
}
?>
