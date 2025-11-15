<?php
require_once("TIPO.php");

class INGRESOS extends TIPO {
    //put your code here
 public function mostrarInicio() {
    $fechaInicio = date('Y-m-d', strtotime('-1 month'));
    $fechaFin = date('Y-m-d');

    echo '<center><form>
        <!-- Botones principales -->
        <div class="row mb-3">
            <div class="col-md-12">
                <button type="submit" name="bttcrearIngreso" class="btn btn-primary m-2"> 
                    <img src="../img/anadir.png" alt="" width="32"> <br> CREAR INGRESO
                </button>
                
                <button type="submit" name="bttresumenIngreso" class="btn btn-info m-2"> 
                    <img src="../img/cuadernorojo.png" alt="" width="32"> <br> RESUMEN INGRESO
                </button>
            </div>
        </div>

        <!-- Filtros de fecha -->
        <div class="row mb-3">
            <div class="col-md-12">
                <b>Rango de fechas: </b>
                Desde: <input type="date" name="fechaInicio" value="' . $fechaInicio . '" class="form-control d-inline" style="width: auto;">
                Hasta: <input type="date" name="fechaFin" value="' . $fechaFin . '" class="form-control d-inline" style="width: auto;">
            </div>
        </div>

        <!-- Botones de consulta -->
        <div class="row mb-3">
            <div class="col-md-12">
                <button type="submit" name="bttbuscarPorFechas" class="btn btn-success m-1"> 
                    <img src="../img/buscar.jpg" alt="" width="24"> BUSCAR INGRESOS
                </button>
                
                <button type="submit" name="bttLibroDiario" class="btn btn-warning m-1"> 
                    <img src="../img/cuadernorojo.png" alt="" width="24"> LIBRO DIARIO
                </button>
                
                <button type="submit" name="bttBalanceComprobacion" class="btn btn-secondary m-1"> 
                    <img src="../img/formu.png" alt="" width="24"> BALANCE COMPROBACIÓN
                </button>
            </div>
        </div>
        
    </form></center>';
    
    // Agregar JavaScript para cuentas
    $this->generarJavaScriptCuentas();
}
 public function mostrarInicioFactura() {
    $fechaInicio = date('Y-m-d', strtotime('-1 month'));
    $fechaFin = date('Y-m-d');

    echo '<center><form>
               <!-- Botón para Crear Factura Leche -->
        <button type="submit" name="bttcrearFacturaLeche"> 
            <img src="../img/anadir.png" alt=""/> <br> CREAR FACTURA LECHE
        </button>

        <br><br>
        <b>Rango de Fechas para Factura Leche: </b>
        Desde: <input type="date" name="fechaInicioFactura" value="' . $fechaInicio . '">
        Hasta: <input type="date" name="fechaFinFactura" value="' . $fechaFin . '">
             <button type="submit" name="bttbuscarFacturasPorFechas"> 
            <img src="../img/buscar.jpg" alt=""/> <br> BUSCAR FACTURAS POR FECHAS
        </button>
    </form></center>';
}

public function mostrarCrearFacturaLeche($fini, $ffin) {
    // Iniciar sesión para obtener la hacienda
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Construir la consulta SQL
    $query = "SELECT ident, fecent, totent, codcli 
              FROM public.\"ENTREGA\" 
              WHERE fecent BETWEEN '$fini' AND '$ffin' AND idhac=" . $_SESSION['idhac'] . "  
              AND ident NOT IN (
                  SELECT ident FROM public.\"DETALLE_FACTURA_LECHE\"
              )";

    // Ejecutar la consulta
    $stmt = $this->consulta($query);

    // Variables para cálculos con valores predeterminados
    $precioLeche = 0.54;
    $seguroPorLitro = 0.01;
    $retencionPorLitro = 0.01;
    $bonoComprasPorLitro = 0.04;

    $totalLitros = 0;
    $subtotal = 0;
    $seguro = 0;
    $retencion = 0;
    $bonoCompras = 0;
    $comision = 0;
    $totalPagar = 0;

    // Calcular valores iniciales en PHP
    $entregas = [];
    while ($entrega = pg_fetch_assoc($stmt)) {
        $entregas[] = $entrega;
        $totalLitros += $entrega['totent'];
    }

    $subtotal = $totalLitros * $precioLeche;
    $seguro = $totalLitros * $seguroPorLitro;
    $retencion = $totalLitros * $retencionPorLitro;
    $bonoCompras = $totalLitros * $bonoComprasPorLitro;
    $totalPagar = $subtotal - ($seguro + $retencion + $bonoCompras + $comision);

    // Fecha de Factura (Por defecto la fecha actual)
    $fechaFactura = date('Y-m-d');

    // Mostrar Formulario con Bootstrap
    echo '<div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header text-center bg-primary text-white">
                            <h3>Crear Factura de Leche</h3>
                        </div>
                        <div class="card-body">
                            <form method="post" enctype="multipart/form-data">

                                <!-- Fecha de Factura -->
                                <div class="mb-3">
                                    <label class="form-label"><b>Fecha de Factura:</b></label>
                                    <input type="date" class="form-control" id="fechaFactura" name="fechaFactura" value="' . $fechaFactura . '" required>
                                </div>

                                <!-- Fecha de Inicio -->
                                <div class="mb-3">
                                    <label class="form-label"><b>Fecha Inicio:</b></label>
                                    <input type="date" class="form-control" id="fechaInicio" name="fechaInicio" value="' . $fini . '" required>
                                </div>

                                <!-- Fecha de Fin -->
                                <div class="mb-3">
                                    <label class="form-label"><b>Fecha Fin:</b></label>
                                    <input type="date" class="form-control" id="fechaFin" name="fechaFin" value="' . $ffin . '" required>
                                </div>

                                <!-- Tabla de Entregas -->
                                <div class="mb-3">
                                    <label class="form-label"><b>Entregas Disponibles:</b></label>
                                    <table class="table table-bordered table-striped text-center">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>Seleccionar</th>
                                                <th>Fecha</th>
                                                <th>Litros</th>
                                                <th>Cliente</th>
                                            </tr>
                                        </thead>
                                        <tbody>';

    foreach ($entregas as $entrega) {
        echo '<tr>
                <td><input type="checkbox" class="form-check-input chkentrega" name="entregas[]" value="' . $entrega['ident'] . '" checked onchange="recalcularTotales()"></td>
                <td>' . $entrega['fecent'] . '</td>
                <td class="litros">' . $entrega['totent'] . '</td>
                <td>' . $entrega['codcli'] . '</td>
              </tr>';
    }

     echo '                          </tbody>
                                    </table>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label"><b>Total de Litros:</b></label>
                                    <input type="text" class="form-control" id="totalLitros" name="totalLitros" value="' . $totalLitros . '" readonly>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label"><b>Precio de la Leche ($ por litro):</b></label>
                                    <input type="text" class="form-control" id="precioLeche" name="precioLeche" value="' . $precioLeche . '" oninput="recalcularTotales()" required>
                                </div>

                                <!-- Seguro -->
                                <div class="mb-3">
                                    <label class="form-label"><b>Seguro:</b></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="seguroPorLitro" value="' . $seguroPorLitro . '" oninput="recalcularTotales()">
                                        <span class="input-group-text">$/litro</span>
                                        <input type="text" class="form-control" id="seguro" name="seguro" value="' . number_format($seguro, 2) . '" readonly>
                                    </div>
                                </div>

                                <!-- Retención -->
                                <div class="mb-3">
                                    <label class="form-label"><b>Retención:</b></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="retencionPorLitro" value="' . $retencionPorLitro . '" oninput="recalcularTotales()">
                                        <span class="input-group-text">$/litro</span>
                                        <input type="text" class="form-control" id="retencion" name="retencion" value="' . number_format($retencion, 2) . '" readonly>
                                    </div>
                                </div>

                                <!-- Bono en Compras -->
                                <div class="mb-3">
                                    <label class="form-label"><b>Bono en Compras:</b></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="bonoComprasPorLitro" value="' . $bonoComprasPorLitro . '" oninput="recalcularTotales()">
                                        <span class="input-group-text">$/litro</span>
                                        <input type="text" class="form-control" id="bonoCompras" name="bonoCompras" value="' . number_format($bonoCompras, 2) . '" readonly>
                                    </div>
                                </div>
  <div class="mb-3">
                                    <label class="form-label"><b>Diferencia:</b></label>
                                    <input type="text" class="form-control descuentos" id="comision" name="comision" value="' . number_format($comision, 2) . '" oninput="recalcularTotales()" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label"><b>Total a Pagar:</b></label>
                                    <input type="text" class="form-control" id="totalPagar" name="totalPagar" value="' . number_format($totalPagar, 2) . '" readonly>
                                </div>
  <div class="mb-3">
                                    <label class="form-label"><b>Subir Imagen de Factura:</b></label>
                                    <input type="file" class="form-control" name="imagenFactura" accept="image/*" >
                                </div>
                                <div class="text-center">
                                    <button type="submit" name="bttCrearFactura" class="btn btn-success">Crear Factura</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>';
}

public function crearFacturaLeche($datos) {
    // Iniciar sesión (asegúrate de hacerlo al inicio del archivo)
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Verificar autenticación
    if (!isset($_SESSION['id']) || !isset($_SESSION['idhac'])) {
        echo "<script>alert('No estás autenticado. Por favor, inicia sesión.'); window.location.href='login.php';</script>";
        exit;
    }
    $idUsuario = $_SESSION['id'];
    $idHacienda = $_SESSION['idhac'];

    // Conexión a la base de datos
    $conn = $this->conectar();

    try {
        // Iniciar Transacción
        pg_query($conn, "BEGIN");

        // 1. Obtener y validar fechas
        $fechaFactura = isset($datos['fechaFactura']) ? $datos['fechaFactura'] : date('Y-m-d');
        $fechaInicio  = isset($datos['fechaInicio'])  ? $datos['fechaInicio']  : date('Y-m-d');
        $fechaFin     = isset($datos['fechaFin'])     ? $datos['fechaFin']     : date('Y-m-d');

        if (!$fechaFactura || !$fechaInicio || !$fechaFin) {
            throw new Exception('Fechas inválidas.');
        }

        // 2. Recoger los datos enviados por POST
        $totalEntregas       = isset($datos['totalLitros'])       ? $datos['totalLitros']       : 0;
        $precioLeche         = isset($datos['precioLeche'])         ? $datos['precioLeche']         : 0;
        $subtotal            = isset($datos['subtotal'])            ? $datos['subtotal']            : 0;
        $seguro              = isset($datos['seguro'])              ? $datos['seguro']              : 0;
        $retencion           = isset($datos['retencion'])           ? $datos['retencion']           : 0;
        $bonoCompras         = isset($datos['bonoCompras'])         ? $datos['bonoCompras']         : 0;
        $comision            = isset($datos['comision'])            ? $datos['comision']            : 0;
       $totalPagar = isset($datos['totalPagar']) ? floatval(str_replace(',', '', $datos['totalPagar'])) : 0;
        $codcli              = isset($datos['codcli'])              ? $datos['codcli']              : 0;
        // Nuevos campos: valores por litro
        $seguroPorLitro      = isset($datos['seguroPorLitro'])      ? $datos['seguroPorLitro']      : 0.01;
        $retencionPorLitro   = isset($datos['retencionPorLitro'])   ? $datos['retencionPorLitro']   : 0.01;
        $bonoComprasPorLitro = isset($datos['bonoComprasPorLitro']) ? $datos['bonoComprasPorLitro'] : 0.04;

        // 3. Insertar la factura en FACTURA_LECHE
        $queryFactura = "INSERT INTO public.\"FACTURA_LECHE\" (
                            fecfac, fecha_inicio, fecha_fin, total_entregas, 
                            precio_leche, subtotal, seguro, retencion, bono_compras, 
                            comision, total_pagar, codcli, idusu, idhac,
                            seguro_por_litro, retencion_por_litro, bono_compras_por_litro
                        ) VALUES (
                            '$fechaFactura', '$fechaInicio', '$fechaFin', $totalEntregas, 
                            $precioLeche, $subtotal, $seguro, $retencion, $bonoCompras, 
                            $comision, $totalPagar, $codcli, $idUsuario, $idHacienda,
                            $seguroPorLitro, $retencionPorLitro, $bonoComprasPorLitro
                        ) RETURNING idfactura";

        $result = pg_query($conn, $queryFactura);
        if (!$result) {
            throw new Exception('Error al insertar la factura.');
        }
        $idFactura = pg_fetch_result($result, 0, 'idfactura');

        // 4. Insertar los detalles en DETALLE_FACTURA_LECHE sin cantidad de leche
        // Se insertan únicamente los idents seleccionados provenientes del checkbox
        foreach ($datos['entregas'] as $ident) {
            $queryDetalle = "INSERT INTO public.\"DETALLE_FACTURA_LECHE\" (
                                idfactura, ident
                             ) VALUES (
                                $idFactura, $ident
                             )";
            $resultDetalle = pg_query($conn, $queryDetalle);
            if (!$resultDetalle) {
                throw new Exception('Error al insertar el detalle de la factura.');
            }

            // 5. Actualizar cada entrega para asignarle la factura generada
            $queryUpdateEntrega = "UPDATE public.\"ENTREGA\" 
                                   SET estent = 2, idfactura = $idFactura 
                                   WHERE ident = $ident";
            $resultUpdate = pg_query($conn, $queryUpdateEntrega);
            if (!$resultUpdate) {
                throw new Exception('Error al actualizar las entregas.');
            }
        }

        // 6. Guardar la imagen de la factura (si se envía)
        if (isset($_FILES['imagenFactura']) && $_FILES['imagenFactura']['error'] === UPLOAD_ERR_OK) {
            $nombreImagen = "factura_" . $idFactura . ".png";
            $rutaImagen   = __DIR__ . "/../factura/" . $nombreImagen;

            if (move_uploaded_file($_FILES['imagenFactura']['tmp_name'], $rutaImagen)) {
                $queryUpdateImagen = "UPDATE public.\"FACTURA_LECHE\" 
                                      SET imagen_factura = '$nombreImagen' 
                                      WHERE idfactura = $idFactura";
                $resultImagen = pg_query($conn, $queryUpdateImagen);
                if (!$resultImagen) {
                    throw new Exception('Error al guardar la imagen de la factura.');
                }
            } else {
                throw new Exception('Error al mover la imagen al directorio de facturas.');
            }
        }

        // Confirmar Transacción
        pg_query($conn, "COMMIT");

        // Retornar el ID de la factura creada
        return $idFactura;
    } catch (Exception $e) {
        // Revertir Transacción en caso de error
        pg_query($conn, "ROLLBACK");
        echo "<script>alert('Error: " . $e->getMessage() . "'); window.history.back();</script>";
    }
}

public function mostrarFacturaLeche($idFactura) {
    // Iniciar sesión si es necesario
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Conexión a la base de datos
    $conn = $this->conectar();
    
    // 1. Consultar la factura según el id proporcionado
    $queryFactura = "SELECT * FROM public.\"FACTURA_LECHE\" WHERE idfactura = $idFactura";
    $resultFactura = pg_query($conn, $queryFactura);
    if (!$resultFactura) {
        echo "<script>alert('Error al obtener la factura.'); window.history.back();</script>";
        exit;
    }
    $factura = pg_fetch_assoc($resultFactura);
    if (!$factura) {
        echo "<script>alert('Factura no encontrada.'); window.history.back();</script>";
        exit;
    }
    
    // Extraer los datos de la factura
    $fechaFactura         = $factura['fecfac'];
    $fechaInicio          = $factura['fecha_inicio'];
    $fechaFin             = $factura['fecha_fin'];
    $totalLitros          = $factura['total_entregas'];
    $precioLeche          = $factura['precio_leche'];
    $subtotal             = $factura['subtotal'];
    $seguro               = $factura['seguro'];
    $retencion            = $factura['retencion'];
    $bonoCompras          = $factura['bono_compras'];
    $comision             = $factura['comision'];
    $totalPagar           = $factura['total_pagar'];
    $codcli               = $factura['codcli'];
    $seguroPorLitro       = $factura['seguro_por_litro'];
    $retencionPorLitro    = $factura['retencion_por_litro'];
    $bonoComprasPorLitro  = $factura['bono_compras_por_litro'];
    $imagenFactura        = $factura['imagen_factura'];
    
    // 2. Obtener los idents de las entregas asociadas a esta factura
    $queryDetalles = "SELECT ident FROM public.\"DETALLE_FACTURA_LECHE\" WHERE idfactura = $idFactura";
    $resultDetalles = pg_query($conn, $queryDetalles);
    $selectedEntregas = [];
    while ($row = pg_fetch_assoc($resultDetalles)) {
        $selectedEntregas[] = $row['ident'];
    }
    
    // 3. Consultar las entregas disponibles en el rango de fechas.
    // Se muestran las entregas del rango (de la hacienda actual) que no estén asignadas a otra factura
    // y se incluyen también las entregas ya asociadas a la factura actual.
    $idhac = $_SESSION['idhac'];
    $selectedList = !empty($selectedEntregas) ? implode(",", $selectedEntregas) : "0";
    $queryEntregas = "SELECT ident, fecent, totent, codcli 
                      FROM public.\"ENTREGA\" 
                      WHERE (
                              fecent BETWEEN '$fechaInicio' AND '$fechaFin'
                              AND idhac = $idhac 
                              AND ident NOT IN (
                                  SELECT ident FROM public.\"DETALLE_FACTURA_LECHE\" 
                                  WHERE idfactura <> $idFactura
                              )
                            )
                      OR ident IN ($selectedList)
                      ORDER BY fecent ASC";
    $resultEntregas = pg_query($conn, $queryEntregas);
    $entregas = [];
    while ($row = pg_fetch_assoc($resultEntregas)) {
        $entregas[] = $row;
    }
    
    // 4. Mostrar el formulario de edición (similar al de creación)
    echo '<div class="container mt-5">
            <div class="row justify-content-center">
              <div class="col-md-8">
                <div class="card">
                  <div class="card-header text-center bg-primary text-white">
                    <h3>Editar Factura de Leche</h3>
                  </div>
                  <div class="card-body">
                    <form method="post" enctype="multipart/form-data" >
                      <!-- Campo oculto para el id de la factura -->
                      <input type="hidden" name="idfactura" value="' . $idFactura . '">
                      
                      <!-- Fecha de Factura -->
                      <div class="mb-3">
                        <label class="form-label"><b>Fecha de Factura:</b></label>
                        <input type="date" class="form-control" id="fechaFactura" name="fechaFactura" value="' . $fechaFactura . '" required>
                      </div>
                      
                      <!-- Fecha de Inicio -->
                      <div class="mb-3">
                        <label class="form-label"><b>Fecha Inicio:</b></label>
                        <input type="date" class="form-control" id="fechaInicio" name="fechaInicio" value="' . $fechaInicio . '" required>
                      </div>
                      
                      <!-- Fecha de Fin -->
                      <div class="mb-3">
                        <label class="form-label"><b>Fecha Fin:</b></label>
                        <input type="date" class="form-control" id="fechaFin" name="fechaFin" value="' . $fechaFin . '" required>
                      </div>
                      
                      <!-- Tabla de Entregas -->
                      <div class="mb-3">
                        <label class="form-label"><b>Entregas Disponibles:</b></label>
                        <table class="table table-bordered table-striped text-center">
                          <thead class="table-dark">
                            <tr>
                              <th>Seleccionar</th>
                              <th>Fecha</th>
                              <th>Litros</th>
                              <th>Cliente</th>
                            </tr>
                          </thead>
                          <tbody>';
    foreach ($entregas as $entrega) {
        $isChecked = in_array($entrega['ident'], $selectedEntregas) ? 'checked' : '';
        echo '<tr>
                <td><input type="checkbox" class="form-check-input chkentrega" name="entregas[]" value="' . $entrega['ident'] . '" ' . $isChecked . ' onchange="recalcularTotales()"></td>
                <td>' . $entrega['fecent'] . '</td>
                <td class="litros">' . $entrega['totent'] . '</td>
                <td>' . $entrega['codcli'] . '</td>
              </tr>';
    }
    echo '         </tbody>
                        </table>
                      </div>
                      
                      <div class="mb-3">
                        <label class="form-label"><b>Total de Litros:</b></label>
                        <input type="text" class="form-control" id="totalLitros" name="totalLitros" value="' . $totalLitros . '" readonly>
                      </div>
                      
                      <div class="mb-3">
                        <label class="form-label"><b>Precio de la Leche ($ por litro):</b></label>
                        <input type="text" class="form-control" id="precioLeche" name="precioLeche" value="' . $precioLeche . '" oninput="recalcularTotales()" required>
                      </div>
                      
                      <!-- Seguro -->
                      <div class="mb-3">
                        <label class="form-label"><b>Seguro:</b></label>
                        <div class="input-group">
                          <input type="text" class="form-control" id="seguroPorLitro" value="' . $seguroPorLitro . '" oninput="recalcularTotales()">
                          <span class="input-group-text">$/litro</span>
                          <input type="text" class="form-control" id="seguro" name="seguro" value="' . number_format($seguro, 2) . '" readonly>
                        </div>
                      </div>
                      
                      <!-- Retención -->
                      <div class="mb-3">
                        <label class="form-label"><b>Retención:</b></label>
                        <div class="input-group">
                          <input type="text" class="form-control" id="retencionPorLitro" value="' . $retencionPorLitro . '" oninput="recalcularTotales()">
                          <span class="input-group-text">$/litro</span>
                          <input type="text" class="form-control" id="retencion" name="retencion" value="' . number_format($retencion, 2) . '" readonly>
                        </div>
                      </div>
                      
                      <!-- Bono en Compras -->
                      <div class="mb-3">
                        <label class="form-label"><b>Bono en Compras:</b></label>
                        <div class="input-group">
                          <input type="text" class="form-control" id="bonoComprasPorLitro" value="' . $bonoComprasPorLitro . '" oninput="recalcularTotales()">
                          <span class="input-group-text">$/litro</span>
                          <input type="text" class="form-control" id="bonoCompras" name="bonoCompras" value="' . number_format($bonoCompras, 2) . '" readonly>
                        </div>
                      </div>
                      
                      <!-- Diferencia / Comisión -->
                      <div class="mb-3">
                        <label class="form-label"><b>Diferencia:</b></label>
                        <input type="text" class="form-control descuentos" id="comision" name="comision" value="' . number_format($comision, 2) . '" oninput="recalcularTotales()" required>
                      </div>
                      
                      <div class="mb-3">
                        <label class="form-label"><b>Total a Pagar:</b></label>
                        <input type="text" class="form-control" id="totalPagar" name="totalPagar" value="' . number_format($totalPagar, 2) . '" readonly>
                      </div>
                      
                      <div class="mb-3">
                        <label class="form-label"><b>Subir Imagen de Factura:</b></label>';
    if (!empty($imagenFactura)) {
        echo '<a target=_blank href="../factura/' . $imagenFactura . '"><img src="../factura/' . $imagenFactura . '" alt="Imagen Factura" style="max-width:100px;display:block;margin-bottom:10px;"></a>';
    }
    echo '      <input type="file" class="form-control" name="imagenFactura" accept="image/*">
                      </div>
                      <div class="text-center">
                        <button type="submit" name="bttActualizarFactura" class="btn btn-success">Actualizar Factura</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>';
}

public function listarFacturarPorFechas($fechaInicio, $fechaFin) {
    // Iniciar sesión si no se ha iniciado
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Obtener la conexión mediante el método conectar()
    $conn = $this->conectar();
    
    // Consulta para obtener las facturas en el rango indicado y contar el número de entregas asociadas
    $query = "SELECT f.*, 
                     (SELECT COUNT(*) FROM public.\"DETALLE_FACTURA_LECHE\" d 
                      WHERE d.idfactura = f.idfactura) AS num_entregas
              FROM public.\"FACTURA_LECHE\" f 
              WHERE f.fecfac BETWEEN '$fechaInicio' AND '$fechaFin'
              ORDER BY f.fecfac DESC";
              
    $result = pg_query($conn, $query);
    if (!$result) {
        echo "<p>Error al obtener las facturas.</p>";
        return;
    }
    
    // Inicialización de acumuladores para los totales
    $totalNumEntregas = 0;
    $totalLitros = 0;
    $totalPrecio = 0;
    $totalSeguro = 0;
    $totalRetencion = 0;
    $totalBono = 0;
    $totalComision = 0;
    $totalTotalPagar = 0;
    
    echo ' <form><table class="table table-bordered table-striped text-center">';
    echo '<thead class="table-dark">
            <tr>
                <th>Fecha Factura</th>
                <th>Fecha Inicio</th>
                <th>Fecha Fin</th>
                <th># Entregas</th>
                <th>Total Litros</th>
                <th>Precio por Litro</th>
                <th>Seguro</th>
                <th>Retención</th>
                <th>Bono Compras</th>
                <th>Diferencia</th>
                <th>Total a Pagar</th>
                <th>Seleccionar</th>
                <th>Eliminar</th>
            </tr>
          </thead>';
    echo '<tbody>';
    while ($a = pg_fetch_assoc($result)) {
        echo '<tr>';
        echo '<td>' . $a['fecfac'] . '</td>';
        echo '<td>' . $a['fecha_inicio'] . '</td>';
        echo '<td>' . $a['fecha_fin'] . '</td>';
        echo '<td>' . $a['num_entregas'] . '</td>';
        echo '<td>' . $a['total_entregas'] . '</td>';
        echo '<td>' . $a['precio_leche'] . '</td>';
        echo '<td>' . number_format($a['seguro'], 2) . '</td>';
        echo '<td>' . number_format($a['retencion'], 2) . '</td>';
        echo '<td>' . number_format($a['bono_compras'], 2) . '</td>';
        echo '<td>' . number_format($a['comision'], 2) . '</td>';
        echo '<td>' . number_format($a['total_pagar'], 2) . '</td>';
        echo '<td>
                <button name="bttselFactura" value="' . $a['idfactura'] . '">
                    <img src="../img/modif.jpg" alt="Modificar"><br>Seleccionar
                </button>
              </td>';
        echo '<td>
                <button name="btteliFactura" onclick="return confirm(\'Esta seguro de Eliminar la Factura de Leche\');" value="' . $a['idfactura'] . '">
                    <img src="../img/cancelar.jpg" alt="Eliminar"><br>Eliminar
                </button>
              </td>';
        echo '</tr>';
        
        // Acumulación de totales
        $totalNumEntregas += $a['num_entregas'];
        $totalLitros += $a['total_entregas'];
        $totalPrecio += $a['precio_leche'];
        $totalSeguro += $a['seguro'];
        $totalRetencion += $a['retencion'];
        $totalBono += $a['bono_compras'];
        $totalComision += $a['comision'];
        $totalTotalPagar += $a['total_pagar'];
    }
    echo '</tbody>';
    
    // Fila de totales: Se dejan en blanco las columnas de fechas y botones
    echo '<tfoot>';
    echo '<tr>';
    echo '<td colspan="3" class="text-end"><strong>Totales:</strong></td>';
    echo '<td>' . $totalNumEntregas . '</td>';
    echo '<td>' . $totalLitros . '</td>';
    echo '<td>' . $totalPrecio . '</td>';
    echo '<td>' . number_format($totalSeguro, 2) . '</td>';
    echo '<td>' . number_format($totalRetencion, 2) . '</td>';
    echo '<td>' . number_format($totalBono, 2) . '</td>';
    echo '<td>' . number_format($totalComision, 2) . '</td>';
    echo '<td>' . number_format($totalTotalPagar, 2) . '</td>';
    echo '<td colspan="2"></td>';
    echo '</tr>';
    echo '</tfoot>';
    echo '</table></form>';
}


public function modificarFacturaLeche($datos) {
    // Iniciar sesión si es necesario
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Obtener la conexión a la base de datos
    $conn = $this->conectar();
    
    // Verificar que se haya enviado el id de la factura
    if (!isset($datos['idfactura'])) {
        echo "<script>alert('ID de factura no proporcionado.'); window.history.back();</script>";
        exit;
    }
    $idFactura = $datos['idfactura'];
    
    // Iniciar la transacción
    pg_query($conn, "BEGIN");
    
    try {
        // Recoger y asignar los datos enviados por el formulario (puedes agregar validaciones adicionales)
        $fechaFactura        = isset($datos['fechaFactura']) ? $datos['fechaFactura'] : date('Y-m-d');
        $fechaInicio         = isset($datos['fechaInicio'])  ? $datos['fechaInicio']  : date('Y-m-d');
        $fechaFin            = isset($datos['fechaFin'])     ? $datos['fechaFin']     : date('Y-m-d');
        $totalLitros         = isset($datos['totalLitros'])  ? $datos['totalLitros']  : 0;
        $precioLeche         = isset($datos['precioLeche'])  ? $datos['precioLeche']  : 0;
        $subtotal            = isset($datos['subtotal'])     ? $datos['subtotal']     : 0;
        $seguro              = isset($datos['seguro'])       ? $datos['seguro']       : 0;
        $retencion           = isset($datos['retencion'])    ? $datos['retencion']    : 0;
        $bonoCompras         = isset($datos['bonoCompras'])  ? $datos['bonoCompras']  : 0;
        $comision            = isset($datos['comision'])     ? $datos['comision']     : 0;
      //  $totalPagar          = isset($datos['totalPagar'])   ? $datos['totalPagar']   : 0;
         $totalPagar = isset($datos['totalPagar']) ? floatval(str_replace(',', '', $datos['totalPagar'])) : 0;
        $codcli              = isset($datos['codcli'])       ? $datos['codcli']       : 0;
        // Valores unitarios
        $seguroPorLitro      = isset($datos['seguroPorLitro'])      ? $datos['seguroPorLitro']      : 0;
        $retencionPorLitro   = isset($datos['retencionPorLitro'])   ? $datos['retencionPorLitro']   : 0;
        $bonoComprasPorLitro = isset($datos['bonoComprasPorLitro']) ? $datos['bonoComprasPorLitro'] : 0;
        
        // 1. Actualizar la factura en FACTURA_LECHE
        $updateFacturaQuery = "UPDATE public.\"FACTURA_LECHE\"
                              SET fecfac = '$fechaFactura',
                                  fecha_inicio = '$fechaInicio',
                                  fecha_fin = '$fechaFin',
                                  total_entregas = $totalLitros,
                                  precio_leche = $precioLeche,
                                  subtotal = $subtotal,
                                  seguro = $seguro,
                                  retencion = $retencion,
                                  bono_compras = $bonoCompras,
                                  comision = $comision,
                                  total_pagar = $totalPagar,
                                  codcli = $codcli,
                                  seguro_por_litro = $seguroPorLitro,
                                  retencion_por_litro = $retencionPorLitro,
                                  bono_compras_por_litro = $bonoComprasPorLitro
                              WHERE idfactura = $idFactura";
        $resultUpdate = pg_query($conn, $updateFacturaQuery);
        if (!$resultUpdate) {
            throw new Exception("Error al actualizar la factura.");
        }
        
        // 2. Obtener los detalles actuales (los idents asociados a la factura)
        $queryCurrentDetails = "SELECT ident FROM public.\"DETALLE_FACTURA_LECHE\" WHERE idfactura = $idFactura";
        $resultCurrent = pg_query($conn, $queryCurrentDetails);
        if (!$resultCurrent) {
            throw new Exception("Error al obtener los detalles actuales de la factura.");
        }
        $currentDetails = [];
        while ($row = pg_fetch_assoc($resultCurrent)) {
            $currentDetails[] = $row['ident'];
        }
        
        // 3. Obtener los nuevos idents enviados en el formulario
        $newEntregas = isset($datos['entregas']) ? $datos['entregas'] : [];
        
        // Calcular la diferencia
        $removedEntregas = array_diff($currentDetails, $newEntregas);
        $addedEntregas = array_diff($newEntregas, $currentDetails);
        
        // 4. Procesar las entregas removidas: eliminar el detalle y liberar la entrega
        foreach ($removedEntregas as $ident) {
            $deleteQuery = "DELETE FROM public.\"DETALLE_FACTURA_LECHE\" WHERE idfactura = $idFactura AND ident = $ident";
            $resultDelete = pg_query($conn, $deleteQuery);
            if (!$resultDelete) {
                throw new Exception("Error al eliminar el detalle para la entrega $ident.");
            }
            $updateEntregaLiberar = "UPDATE public.\"ENTREGA\" 
                                      SET estent = 1, idfactura = NULL
                                      WHERE ident = $ident";
            $resultLiberar = pg_query($conn, $updateEntregaLiberar);
            if (!$resultLiberar) {
                throw new Exception("Error al liberar la entrega $ident.");
            }
        }
        
        // 5. Procesar las entregas agregadas: insertar nuevo detalle y asignar la entrega
        foreach ($addedEntregas as $ident) {
            $insertQuery = "INSERT INTO public.\"DETALLE_FACTURA_LECHE\" (idfactura, ident)
                            VALUES ($idFactura, $ident)";
            $resultInsert = pg_query($conn, $insertQuery);
            if (!$resultInsert) {
                throw new Exception("Error al insertar el detalle para la entrega $ident.");
            }
            $updateEntregaAsignar = "UPDATE public.\"ENTREGA\"
                                     SET estent = 2, idfactura = $idFactura
                                     WHERE ident = $ident";
            $resultAsignar = pg_query($conn, $updateEntregaAsignar);
            if (!$resultAsignar) {
                throw new Exception("Error al actualizar la entrega $ident.");
            }
        }
        
        // 6. Actualizar la imagen de la factura, si se ha subido una nueva
        if (isset($_FILES['imagenFactura']) && $_FILES['imagenFactura']['error'] === UPLOAD_ERR_OK) {
            $nombreImagen = "factura_" . $idFactura . ".png";
            $rutaImagen = __DIR__ . "/../factura/" . $nombreImagen;
            if (move_uploaded_file($_FILES['imagenFactura']['tmp_name'], $rutaImagen)) {
                $queryUpdateImagen = "UPDATE public.\"FACTURA_LECHE\"
                                      SET imagen_factura = '$nombreImagen'
                                      WHERE idfactura = $idFactura";
                $resultImagen = pg_query($conn, $queryUpdateImagen);
                if (!$resultImagen) {
                    throw new Exception("Error al actualizar la imagen de la factura.");
                }
            } else {
                throw new Exception("Error al mover la imagen de la factura.");
            }
        }
        
        // Confirmar la transacción
        pg_query($conn, "COMMIT");
        
        // Puedes redirigir o retornar true en caso de éxito
         echo "<script>alert('Registro modificado'); </script>";
        return true;
    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        pg_query($conn, "ROLLBACK");
        echo "<script>alert('Error: " . $e->getMessage() . "'); window.history.back();</script>";
        exit;
    }
}

public function eliminarFacturaLeche($idFactura) {
    // Iniciar sesión si es necesario
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Obtener la conexión a la base de datos
    $conn = $this->conectar();
    
    // Verificar que se haya recibido un ID de factura
    if (empty($idFactura)) {
        echo "<script>alert('ID de factura no proporcionado.'); window.history.back();</script>";
        exit;
    }
    
    // Iniciar la transacción
    pg_query($conn, "BEGIN");
    
    try {
        // 1. Obtener los detalles asociados a la factura (los idents de las entregas)
        $queryDetalles = "SELECT ident FROM public.\"DETALLE_FACTURA_LECHE\" WHERE idfactura = $idFactura";
        $resultDetalles = pg_query($conn, $queryDetalles);
        if (!$resultDetalles) {
            throw new Exception("Error al obtener los detalles de la factura.");
        }
        $detalles = [];
        while ($row = pg_fetch_assoc($resultDetalles)) {
            $detalles[] = $row['ident'];
        }
        
        // 2. Para cada entrega asociada, actualizar su estado y eliminar la asociación con la factura
        foreach ($detalles as $ident) {
            $updateEntrega = "UPDATE public.\"ENTREGA\"
                              SET estent = 1, idfactura = NULL
                              WHERE ident = $ident";
            $resultUpdate = pg_query($conn, $updateEntrega);
            if (!$resultUpdate) {
                throw new Exception("Error al actualizar la entrega con ident: $ident.");
            }
        }
        
        // 3. Eliminar los detalles de la factura en DETALLE_FACTURA_LECHE
        $deleteDetalles = "DELETE FROM public.\"DETALLE_FACTURA_LECHE\" WHERE idfactura = $idFactura";
        $resultDeleteDetalles = pg_query($conn, $deleteDetalles);
        if (!$resultDeleteDetalles) {
            throw new Exception("Error al eliminar los detalles de la factura.");
        }
        
        // 4. Eliminar la factura de FACTURA_LECHE
        $deleteFactura = "DELETE FROM public.\"FACTURA_LECHE\" WHERE idfactura = $idFactura";
        $resultDeleteFactura = pg_query($conn, $deleteFactura);
        if (!$resultDeleteFactura) {
            throw new Exception("Error al eliminar la factura.");
        }
        
        // Confirmar la transacción
        pg_query($conn, "COMMIT");
         echo "<script>alert('Registros eliminados y actualizados');</script>";
        return true;
    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        pg_query($conn, "ROLLBACK");
        echo "<script>alert('Error: " . $e->getMessage() . "'); window.history.back();</script>";
        exit;
    }
}

   public function buscarIngreso($fini, $ffin) {
    // Sanitiza y valida las fechas de inicio y fin
    $fechaInicio = date('Y-m-d', strtotime($fini));
    $fechaFin = date('Y-m-d', strtotime($ffin));

    // Query SQL para buscar ingresos en el rango de fechas
    $query = 'SELECT "INGRESO".id,deting,montoing,fecing,idtipi,obsing,nomcli FROM "INGRESO","CLIENTE","TIPO_INGRESO" WHERE "TIPO_INGRESO".id="INGRESO".idtipi and "TIPO_INGRESO".idhac='.$_SESSION['idhac'].' and "CLIENTE".codcli="INGRESO".codcli   and fecing BETWEEN \''.$fechaInicio.'\' AND \''.$fechaFin.'\' ORDER BY fecing DESC';
//echo $query;
    // Prepara la consulta con parámetros
    $stmt = $this->consulta($query);

    // Muestra los resultados en una tabla o como desees
    echo '<table border="1" class="table table-stripted" >
            <tr>
                <th>ID</th><th>Detalle</th><th>Monto</th><th>Fecha</th><th>Tipo</th><th>Observaciones</th><th>Cliente</th>
            </tr>';

    while ($row = $this->row($stmt)) {
        echo '<form><tr>
                <td>' . $row['id'] . '</td>
                 <td>' . $row['deting'] . '</td>   
                <td>' . $row['montoing'] . '</td>
                <td>' . $row['fecing'] . '</td>
                <td>' . $row['idtipi'] . '</td>
                <td>' . $row['obsing'] . '</td>
                <td>' . $row['nomcli'] . '</td>
            <td><button name=bttseling value='.$row['id'].'> 
                    <img src="../img/modif.jpg" alt="Modificar"> 
                    <br>Seleccionar
                </button></td>
                <td><button name=btteliing onclick="javascript: return confirm(\'¿Está seguro de eliminar el ingreso?\');" value='.$row['id'].'> 
                    <img src="../img/cancelar.jpg" alt="Eliminar"> 
                    <br>Eliminar
                </button></td>          
                    
            </tr></form>';
    }

    echo '</table>';
}

 public function obtenerCliente() {
        $tiposIngreso = array();

        // Realiza la consulta para obtener los tipos de ingreso
        $con = $this->consulta('SELECT codcli,nomcli FROM "CLIENTE" where idhac='.$_SESSION['idhac']);

        // Recorre los resultados y almacena los tipos de ingreso en un arreglo
        while ($row = $this->row($con)) {
            $tiposIngreso[] = array(
                'codcli' => $row['codcli'],
                'nomcli' => $row['nomcli']
            );
        }

        return $tiposIngreso;
    }
    
public function mostrarCrearIngresos() {
    // Recupera tipos de ingreso, clientes, animales, facturas, y cuentas contables
    $tiposIngreso = $this->obtenerTiposIngresoDesdeClaseTipo();
    $clientes = $this->obtenerCliente();
    $animales = $this->obtenerAnimales();
    $facturas = $this->obtenerFacturasSinIngreso();
    $cuentas = $this->obtenerCuentasContables(); // Debes crear esta función

    echo "<center>
        <form method='post' enctype='multipart/form-data'>
            <table BORDER=1>
                <th colspan=2><center>Crear un nuevo Ingreso</center></th>

                <tr><th>Detalle</th><td><input type='text' name='deting' required></td></tr>

                <tr><th>Monto</th><td><input type='number' name='montoing' step='0.01' required></td></tr>

                <tr><th>Fecha</th><td><input type='date' name='fecing' value='" . date('Y-m-d') . "'></td></tr>

                <tr><th>Tipo de Ingreso</th><td>
                    <select name='idtipi'>";
                        foreach ($tiposIngreso as $tipo) {
                            echo "<option value='" . $tipo['id'] . "'>" . $tipo['detalle'] . "</option>";
                        }
    echo        "</select>
                </td></tr>

                <tr><th>Cliente</th><td> 
                    <select name='codcli'>";
                        foreach ($clientes as $cli) {
                            echo "<option value='" . $cli['codcli'] . "'>" . $cli['nomcli'] . "</option>";
                        }
    echo        "</select>
                </td></tr>

                <tr><th>Asociar Facturas</th><td>
                    <select name='facturas[]' multiple size='5'>";
                        foreach ($facturas as $fac) {
                            echo "<option value='" . $fac['idfactura'] . "'>Factura #" . $fac['idfactura'] . " " . $fac['fecfac'] . " - $" . number_format($fac['total_pagar'], 2) . "</option>";
                        }
    echo        "</select>
                    <br><small>(Puede seleccionar varias facturas)</small>
                </td></tr>

                <tr><th>Asociar Animal</th><td>
                    <select name='idanimal'>
                        <option value=''>- Sin Animal -</option>";
                        foreach ($animales as $ani) {
                            echo "<option value='" . $ani['id'] . "'>" . $ani['nombre'] . "  ({$ani['arete']}) </option>";
                        }
    echo        "</select>
                </td></tr>

                <tr><th>Cuenta Contable</th><td>
                    <select name='codcue'>
                        <option value=''>- Seleccionar cuenta -</option>";
                        foreach ($cuentas as $cue) {
                            echo "<option value='" . $cue['codcue'] . "'>" . $cue['detcue'] . "</option>";
                        }
    echo        "</select>
                </td></tr>

                <tr><th>Subir Imagen</th><td><input type='file' name='imagenIngreso' accept='image/*'></td></tr>

                <tr><th>Observaciones</th><td><input type='text' name='obsing'></td></tr>

                <tr><th colspan=2>
                    <center><button type='submit' name='bttnuevoIngreso' class='bttnuevo'> 
                        <img src='../img/guardar.jpg' alt='Crear Ingreso'><br>CREAR INGRESO
                    </button></center> 
                </th></tr>
            </table>
        </form>
    </center>";
}

public function obtenerCuentasContables() {
    $sql = "SELECT codcue, detcue 
            FROM public.\"CUENTA\"
            WHERE nivel1cue = 1
            ORDER BY codcue ASC";
    $res = $this->consulta($sql);

    $cuentas = [];
    while ($reg = $this->fila($res)) {
        $cuentas[] = $reg;
    }
    return $cuentas;
}
public function obtenerAnimales() {
    $idhac = $_SESSION['idhac']; // Asegúrate de tener la hacienda en sesión
    $sql = "SELECT id, nombre AS nombre ,arete
            FROM public.\"ANIMALES\"
            WHERE estani = 1 AND idhac = $idhac
            ORDER BY nombre ASC";
    $res = $this->consulta($sql);

    $animales = [];
    while ($reg = $this->fila($res)) {
        $animales[] = $reg;
    }
    return $animales;
}

public function obtenerFacturasSinIngreso() {
    $idhac = $_SESSION['idhac'];
    $sql = "SELECT idfactura, total_pagar,fecfac
            FROM public.\"FACTURA_LECHE\"
            WHERE estfac = 1 AND idhac = $idhac
            ORDER BY idfactura DESC";
    $res = $this->consulta($sql);

    $facturas = [];
    while ($reg = $this->fila($res)) {
        $facturas[] = $reg;
    }
    return $facturas;
}
public function texto($cadena) {
    if ($cadena === null || $cadena === '') {
        return 'NULL';
    }
    return "'" . pg_escape_string($cadena) . "'";
}


public function crearIngreso($datos) {
    // 1. Procesar imagen
    $nombreImagen = null;
    if (isset($_FILES['imagenIngreso']) && $_FILES['imagenIngreso']['error'] === UPLOAD_ERR_OK) {
        $imagen = $_FILES['imagenIngreso'];
        $nombreImagen = 'ingreso_' . time() . '_' . basename($imagen['name']);
        $rutaImagen = __DIR__ . '/../ingresos/' . $nombreImagen;
            echo 'archivo';
        if (!move_uploaded_file($imagen['tmp_name'], $rutaImagen)) {
            echo "<div class='errores'>Error al guardar la imagen del ingreso.</div>";
            return false;
        }
    }

    // 2. Insertar ingreso principal
  
    // Validar y corregir monto en formato dólar
$datos['montoing'] = str_replace(',', '.', $datos['montoing']); // por si usan coma
if (!is_numeric($datos['montoing']) || $datos['montoing'] < 0) {
    echo '<div style="background-color: #ffcccc; color: #b30000; padding: 10px; border: 2px solid #b30000; border-radius: 8px; font-weight: bold; text-align: center; margin-bottom: 10px;">
            Error: El monto ingresado no es un valor válido en dólares.
          </div>';
    return false;
}


    $sql = 'INSERT INTO public."INGRESO" (montoing, fecing, idtipi, obsing, deting, codcli, imagen, codcue)
            VALUES (' . $datos['montoing'] . ',
                    \'' . $datos['fecing'] . '\',
                    ' . $datos['idtipi'] . ',
                    ' . $this->texto($datos['obsing']) . ',
                    ' . $this->texto($datos['deting']) . ',
                    ' . $datos['codcli'] . ',
                    ' . ($nombreImagen ? "'$nombreImagen'" : 'NULL') . ',
                    ' . ($datos['codcue'] ?: 'NULL') . ')
            RETURNING id;';
ECHO $sql;
    $res = $this->consulta($sql);
    $reg = $this->fila($res);
    $idingreso = $reg['id'];

    if (!$idingreso) {
        echo "<div class='errores'>No se pudo crear el ingreso.</div>";
        return false;
    }

    // 3. Insertar facturas asociadas
    if (!empty($datos['facturas'])) {
        foreach ($datos['facturas'] as $idfactura) {
            // Relacionar ingreso-factura
            $sqlInsertFactura = 'INSERT INTO public."INGRESO_FACTURA" (idingreso, idfactura)
                                 VALUES (' . $idingreso . ', ' . intval($idfactura) . ');';
            $this->consulta($sqlInsertFactura);

            // Actualizar estado de la factura a 2 (para que no vuelva a salir)
            $sqlUpdateFactura = 'UPDATE public."FACTURA_LECHE"
                                 SET estfac = 2
                                 WHERE idfactura = ' . intval($idfactura) . ';';
            $this->consulta($sqlUpdateFactura);
        }
    }

    // 4. Insertar animal asociado si existe
    if (!empty($datos['idanimal'])) {
        $sqlAnimal = 'INSERT INTO public."INGRESO_ANIMAL" (idingreso, idanimal)
                      VALUES (' . $idingreso . ', ' . intval($datos['idanimal']) . ');';
        $this->consulta($sqlAnimal);
    }

    echo "<div class='ok'>Ingreso creado exitosamente.</div>";
    return true;
}


public function mostrarIngreso($id) {
    // 1. Obtener datos principales del ingreso
    $sql = 'SELECT i.*, 
                   t.detti AS tipo_ingreso, 
                   c.nomcli, 
                   cu.detcue AS cuenta
            FROM public."INGRESO" i
            LEFT JOIN public."TIPO_INGRESO" t ON t.id = i.idtipi
            LEFT JOIN public."CLIENTE" c ON c.codcli = i.codcli
            LEFT JOIN public."CUENTA" cu ON cu.codcue = i.codcue
            WHERE i.id = ' . intval($id) . ';';
    
    $res = $this->consulta($sql);
    $ingreso = $this->fila($res);

    if (!$ingreso) {
        echo "<div class='errores'>No se encontró el ingreso.</div>";
        return;
    }

    // 2. Cargar listas para selects
    $tiposIngreso = $this->obtenerTiposIngresoDesdeClaseTipo(); // con dettip
    $clientes = $this->obtenerCliente();
    $cuentas = $this->obtenerCuentasContables();

    // 3. Obtener facturas asociadas
    $sqlFacturas = 'SELECT f.idfactura, f.fecfac, f.total_pagar
                    FROM public."INGRESO_FACTURA" ifa
                    JOIN public."FACTURA_LECHE" f ON f.idfactura = ifa.idfactura
                    WHERE ifa.idingreso = ' . intval($id) . ';';
    $resFact = $this->consulta($sqlFacturas);
    $facturas = [];
    while ($reg = $this->fila($resFact)) {
        $facturas[] = $reg;
    }

    // 4. Obtener animal asociado (si existe)
    $sqlAnimal = 'SELECT a.id, a.nombre, a.arete
                  FROM public."INGRESO_ANIMAL" ia
                  JOIN public."ANIMALES" a ON a.id = ia.idanimal
                  WHERE ia.idingreso = ' . intval($id) . ';';
    $resAnimal = $this->consulta($sqlAnimal);
    $animal = $this->fila($resAnimal);

    echo "<center>
        <form method='post' enctype='multipart/form-data'>
            <input type='hidden' name='idingreso' value='{$ingreso['id']}'>
            <table BORDER=1 style='max-width: 800px;'>

            <tr><th colspan=2><center>Editar Ingreso</center></th></tr>

            <tr><th>Detalle</th><td><input type='text' name='deting' value='{$ingreso['deting']}' required></td></tr>

            <tr><th>Monto</th><td><input type='number' step='0.01' name='montoing' value='{$ingreso['montoing']}' required></td></tr>

            <tr><th>Fecha</th><td><input type='date' name='fecing' value='{$ingreso['fecing']}' required></td></tr>

            <tr><th>Tipo de Ingreso</th><td>
                <select name='idtipi' required>";
                    foreach ($tiposIngreso as $tipo) {
                        $selected = ($tipo['id'] == $ingreso['idtipi']) ? 'selected' : '';
                        echo "<option value='{$tipo['id']}' $selected>{$tipo['detalle']}</option>";
                    }
    echo    "</select></td></tr>

            <tr><th>Cliente</th><td>
                <select name='codcli' required>";
                    foreach ($clientes as $cli) {
                        $selected = ($cli['codcli'] == $ingreso['codcli']) ? 'selected' : '';
                        echo "<option value='{$cli['codcli']}' $selected>{$cli['nomcli']}</option>";
                    }
    echo    "</select></td></tr>

            <tr><th>Cuenta Contable</th><td>
                <select name='codcue'>";
                    echo "<option value=''>- Seleccionar cuenta -</option>";
                    foreach ($cuentas as $cue) {
                        $selected = ($cue['codcue'] == $ingreso['codcue']) ? 'selected' : '';
                        echo "<option value='{$cue['codcue']}' $selected>{$cue['detcue']}</option>";
                    }
    echo    "</select></td></tr>

            <tr><th>Observaciones</th><td><input type='text' name='obsing' value='{$ingreso['obsing']}'></td></tr>

        <tr><th>Animal Asociado</th><td>
    <select name='idanimal'>
        <option value=''>- Sin Animal -</option>";
        $animales = $this->obtenerAnimales(); // Asegúrate de que tienes esta función
        foreach ($animales as $ani) {
            $selected = (!empty($animal) && $ani['id'] == $animal['id']) ? 'selected' : '';
            echo "<option value='{$ani['id']}' $selected>{$ani['nombre']} ({$ani['arete']})</option>";
        }
echo    "</select>
</td></tr>";

    echo    "

           <tr><th>Facturas Asociadas</th><td>";
    if (count($facturas) > 0) {
        echo "<ul>";
        foreach ($facturas as $fac) {
            echo "<li>
                    Factura #{$fac['idfactura']} - Fecha: {$fac['fecfac']} - Valor: $" . number_format($fac['total_pagar'], 2) . "
                    <button type='submit' name='eliminar_factura' value='{$fac['idfactura']}' style='background-color:red;color:white;margin-left:10px;'>Eliminar</button>
                  </li>";
        }
        echo "</ul>";
    } else {
        echo "No hay facturas asociadas.";
    }
echo "</td></tr>";
echo "<tr><th>Agregar Facturas</th><td>
    <select name='nuevas_facturas[]' multiple size='5'>
        " . $this->opcionesFacturasDisponibles($id) . "
    </select>
    <br><small>(Puede seleccionar varias nuevas facturas)</small>
</td></tr>
";

    if ($ingreso['imagen']) {
        echo "<tr><th>Imagen Actual</th><td>
                <img src='../ingresos/{$ingreso['imagen']}' style='max-width:300px; max-height:300px;'><br><br>
                Subir nueva imagen: <input type='file' name='imagenIngreso' accept='image/*'>
             </td></tr>";
    } else {
        echo "<tr><th>Subir Imagen</th><td><input type='file' name='imagenIngreso' accept='image/*'></td></tr>";
    }

    echo "<tr><th colspan=2>
            <center><button type='submit' name='btteditarIngreso' class='bttnuevo'> 
                <img src='../img/guardar.jpg' alt='Guardar Cambios'><br>GUARDAR CAMBIOS
            </button></center> 
          </th></tr>

            </table>
        </form>
    </center>";
}

public function eliminarFacturaDeIngreso($idingreso, $idfactura) {
    // 1. Eliminar la relación
    $sql = 'DELETE FROM public."INGRESO_FACTURA"
            WHERE idingreso = ' . intval($idingreso) . '
              AND idfactura = ' . intval($idfactura) . ';';
    $this->consulta($sql);

    // 2. Opcional: volver el estado de la factura a 1
    $sqlUpdate = 'UPDATE public."FACTURA_LECHE"
                  SET estfac = 1
                  WHERE idfactura = ' . intval($idfactura) . ';';
    $this->consulta($sqlUpdate);

    echo "<div class='ok'>Factura eliminada del ingreso.</div>";
}

public function agregarFacturasAIngreso($idingreso, $facturas) {
    foreach ($facturas as $idfactura) {
        $sql = 'INSERT INTO public."INGRESO_FACTURA" (idingreso, idfactura)
                VALUES (' . intval($idingreso) . ', ' . intval($idfactura) . ');';
        $this->consulta($sql);

        // Actualizar estado de factura
        $sqlUpdate = 'UPDATE public."FACTURA_LECHE"
                      SET estfac = 2
                      WHERE idfactura = ' . intval($idfactura) . ';';
        $this->consulta($sqlUpdate);
    }

    echo "<div class='ok'>Facturas agregadas al ingreso.</div>";
}

public function opcionesFacturasDisponibles($idingreso) {
    $idhac = $_SESSION['idhac'];

    $sql = 'SELECT idfactura, fecfac, total_pagar
            FROM public."FACTURA_LECHE"
            WHERE estfac = 1 
              AND idfactura NOT IN (
                SELECT idfactura FROM public."INGRESO_FACTURA" WHERE idingreso = ' . intval($idingreso) . '
              )
              AND idhac = ' . intval($idhac) . '
            ORDER BY fecfac DESC;';
    
    $res = $this->consulta($sql);

    $opciones = "";
    while ($reg = $this->fila($res)) {
        $opciones .= "<option value='{$reg['idfactura']}'>Factura #{$reg['idfactura']} {$reg['fecfac']} - $" . number_format($reg['total_pagar'], 2) . "</option>";
    }

    return $opciones;
}



   public function mostrarIngresoAnt($id) {
    // Consulta para obtener los detalles del ingreso con el ID especificado
    $sql = 'SELECT * FROM "INGRESO" WHERE id = ' . $id;
    $clientes = $this->obtenerCliente();
    $resultado = $this->consulta($sql);
    $ingreso = pg_fetch_assoc($resultado);
    
    if($ingreso) {
        $tiposIngreso = $this->obtenerTiposIngresoDesdeClaseTipo();
        
        echo "<center>
            <form method='post'>
                <table BORDER=1>
                    <th colspan=2><center>Editar Ingreso</center></th>
                    <tr><th>ID</th><td><input type='hidden' name='id' value='" . $ingreso['id'] . "' >" . $ingreso['id'] . "</td></tr>
                    <tr><th>Detalle</th><td><input type='text' name='deting' value='" . $ingreso['deting'] . "' required></td></tr>
                    <tr><th>Monto</th><td><input type='number' name='montoing' step='0.01' value='" . $ingreso['montoing'] . "' required></td></tr>
                    <tr><th>Fecha</th><td><input type='date' name='fecing' value='" . $ingreso['fecing'] . "'></td></tr>
                    <tr><th>Tipo de Ingreso</th><td>
                        <select name='idtipi'>";
        
        foreach ($tiposIngreso as $tipo) {
            $selected = ($ingreso['idtipi'] == $tipo['id']) ? "selected" : "";
            echo "<option value='" . $tipo['id'] . "' " . $selected . ">" . $tipo['detalle'] . "</option>";
        }
        
        echo "</select></td></tr>
     <tr><th>Cliente</th><td>        
 <select name='codcli'>";
        
        foreach ($clientes as $tipo) {
            $selected = ($ingreso['codcli'] == $tipo['codcli']) ? "selected" : "";
            echo "<option value='" . $tipo['codcli'] . "' " . $selected . ">" . $tipo['nomcli'] . "</option>";
        }
        
        echo "</select></td></tr>

                    <tr><th>Observaciones</th><td><input type='text' name='obsing' value='" . $ingreso['obsing'] . "'></td></tr>
                    <tr><th colspan=2>
                        <center><button type='submit' name='bttmoding' class='bttmoding'> 
                            <img src='../img/guardar.jpg' alt='GUARDAR CAMBIOS'><br>  GUARDAR CAMBIOS
                        </button></center> 
                    </th></tr>
                </table>
            </form>
        </center>";
    } else {
        echo "<div class=errores>Ingreso no encontrado</div>";
    }
}
public function modificarIngreso($datos) {
    // 1. Procesar imagen nueva si la subieron

    $nombreImagen = null;
    if (isset($_FILES['imagenIngreso']) && $_FILES['imagenIngreso']['error'] === UPLOAD_ERR_OK) {
        $imagen = $_FILES['imagenIngreso'];
        $nombreImagen = 'ingreso_' . time() . '_' . basename($imagen['name']);
        $rutaImagen = __DIR__ . '/../ingresos/' . $nombreImagen;

        if (!move_uploaded_file($imagen['tmp_name'], $rutaImagen)) {
            echo "<div class='errores'>Error al guardar la nueva imagen del ingreso.</div>";
            return false;
        }
    }

    // 2. Validar monto en formato dólar
    $datos['montoing'] = str_replace(',', '.', $datos['montoing']);
    if (!is_numeric($datos['montoing']) || $datos['montoing'] < 0) {
        echo '<div style="background-color: #ffcccc; color: #b30000; padding: 10px; border: 2px solid #b30000; border-radius: 8px; font-weight: bold; text-align: center; margin-bottom: 10px;">
                Error: El monto ingresado no es un valor válido en dólares.
              </div>';
        return false;
    }

    // 3. Actualizar ingreso principal
    $sql = 'UPDATE public."INGRESO" SET 
                montoing = ' . $datos['montoing'] . ',
                fecing = \'' . $datos['fecing'] . '\',
                idtipi = ' . intval($datos['idtipi']) . ',
                obsing = ' . $this->texto($datos['obsing']) . ',
                deting = ' . $this->texto($datos['deting']) . ',
                codcli = ' . intval($datos['codcli']) . ',
                codcue = ' . ($datos['codcue'] ? intval($datos['codcue']) : 'NULL');

    if ($nombreImagen) {
        $sql .= ', imagen = \'' . $nombreImagen . '\'';
    }

    $sql .= ' WHERE id = ' . intval($datos['idingreso']) . ';';

    if (!$this->consulta($sql)) {
        echo "<div class='errores'>Error al actualizar el ingreso.</div>";
        return false;
    }

    // 4. Agregar nuevas facturas si las seleccionaron
    if (!empty($datos['nuevas_facturas'])) {
        foreach ($datos['nuevas_facturas'] as $idfactura) {
            $sqlInsert = 'INSERT INTO public."INGRESO_FACTURA" (idingreso, idfactura)
                          VALUES (' . intval($datos['idingreso']) . ', ' . intval($idfactura) . ');';
            $this->consulta($sqlInsert);

            $sqlUpdateFactura = 'UPDATE public."FACTURA_LECHE"
                                 SET estfac = 2
                                 WHERE idfactura = ' . intval($idfactura) . ';';
            $this->consulta($sqlUpdateFactura);
        }
    }

    // 5. Actualizar animal asociado
    // Primero borramos cualquier asociación anterior
    $sqlBorrarAnimal = 'DELETE FROM public."INGRESO_ANIMAL" WHERE idingreso = ' . intval($datos['idingreso']) . ';';
    $this->consulta($sqlBorrarAnimal);

    // Luego si se seleccionó un animal nuevo, lo insertamos
    if (!empty($datos['idanimal'])) {
        $sqlInsertAnimal = 'INSERT INTO public."INGRESO_ANIMAL" (idingreso, idanimal)
                            VALUES (' . intval($datos['idingreso']) . ', ' . intval($datos['idanimal']) . ');';
        $this->consulta($sqlInsertAnimal);
    }

    echo "<div class='ok'>Ingreso actualizado exitosamente.</div>";
    return true;
}


   public function modificarIngresoAnt($datos) {
    // Construcción de la sentencia SQL para actualizar
    $sql = 'UPDATE "INGRESO" SET 
            deting = \'' . $datos['deting'] . '\',
            montoing = ' . $datos['montoing'] . ',
            fecing = \'' . $datos['fecing'] . '\',
            idtipi = ' . $datos['idtipi'] . ',
            obsing = \'' . $datos['obsing'] . '\',
             codcli = ' . $datos['codcli'] . '    
            WHERE id = ' . $datos['id'] . ';';

    // Ejecución de la consulta
    if ($res1 = $this->consulta($sql)) {
        echo "<div class=mesajeok>Ingreso actualizado exitosamente</div>";
    } else {
        echo "<div class=errores>Error al actualizar el ingreso en BDD " . $sql . " - " . pg_result_error($res1) . "</div>";
    } 
}

    public function eliminarIngreso($id) {
    if ($this->consulta('delete from "INGRESO" where id=' . $id)) {
        echo "<div class=mesajeok >Ingreso Eliminado</div>";
    } else {
        echo "<div class=errores >Error al eliminar el Ingreso de la BDD</div>";
    }
}

  //funciones para la factura de leche

// ======================== NUEVAS FUNCIONES PARA PARTIDA DOBLE ========================

// Función específica para cuentas de DEBE (Activos y Gastos)
public function obtenerCuentasDebe() {
    $sql = "SELECT codcue, detcue, nivel1cue
            FROM public.\"CUENTA\"
            WHERE nivel3cue != 0 
            AND (nivel1cue = 1 OR nivel1cue = 5)  -- 1=Activos, 5=Gastos
            ORDER BY nivel1cue, codcue ASC";
    $res = $this->consulta($sql);

    $cuentas = [];
    while ($reg = $this->fila($res)) {
        $cuentas[] = $reg;
    }
    return $cuentas;
}

// Función específica para cuentas de HABER (Pasivos, Patrimonio, Ingresos)
public function obtenerCuentasHaber() {
    $sql = "SELECT codcue, detcue, nivel1cue
            FROM public.\"CUENTA\"
            WHERE nivel3cue != 0 
            AND (nivel1cue = 2 OR nivel1cue = 3 OR nivel1cue = 4)  -- 2=Pasivos, 3=Patrimonio, 4=Ingresos
            ORDER BY nivel1cue, codcue ASC";
    $res = $this->consulta($sql);

    $cuentas = [];
    while ($reg = $this->fila($res)) {
        $cuentas[] = $reg;
    }
    return $cuentas;
}

// Función para validar que no se usen las mismas cuentas
public function validarPartidaDoble($codcuedebe, $codcuehaber) {
    if ($codcuedebe == $codcuehaber) {
        return [
            'valido' => false, 
            'mensaje' => 'La cuenta DEBE y HABER no pueden ser la misma.'
        ];
    }
    
    return ['valido' => true, 'mensaje' => 'Partida doble válida.'];
}

// Función JavaScript para autocompletar cuentas
public function generarJavaScriptCuentas() {
    echo "<script>
        // Autocompletar cuentas según tipo de ingreso
        function sugerirCuentas(tipoIngreso) {
            const sugerencias = {
                1: {debe: 11, haber: 41}, // Venta leche
                2: {debe: 11, haber: 42}, // Venta animales  
                3: {debe: 11, haber: 21}  // Préstamos
            };
            
            if (sugerencias[tipoIngreso]) {
                const debeSelect = document.querySelector('select[name=\"codcuedebe\"]');
                const haberSelect = document.querySelector('select[name=\"codcuehaber\"]');
                if (debeSelect) debeSelect.value = sugerencias[tipoIngreso].debe;
                if (haberSelect) haberSelect.value = sugerencias[tipoIngreso].haber;
            }
        }
        
        // Validar que las cuentas sean diferentes
        function validarCuentasDiferentes() {
            const debe = document.querySelector('select[name=\"codcuedebe\"]');
            const haber = document.querySelector('select[name=\"codcuehaber\"]');
            
            if (debe && haber && debe.value && haber.value && debe.value === haber.value) {
                alert('La cuenta DEBE y HABER no pueden ser la misma');
                haber.value = '';
                return false;
            }
            return true;
        }
        
        // Agregar eventos cuando se cargue la página
        document.addEventListener('DOMContentLoaded', function() {
            const tipSelect = document.querySelector('select[name=\"idtipi\"]');
            if (tipSelect) {
                tipSelect.addEventListener('change', function() {
                    sugerirCuentas(this.value);
                });
            }
            
            const debeSelect = document.querySelector('select[name=\"codcuedebe\"]');
            const haberSelect = document.querySelector('select[name=\"codcuehaber\"]');
            
            if (debeSelect) debeSelect.addEventListener('change', validarCuentasDiferentes);
            if (haberSelect) haberSelect.addEventListener('change', validarCuentasDiferentes);
        });
    </script>";
}
    
}
