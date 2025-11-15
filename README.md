# sisachu

Sistema de Administración de Haciendas

## Descripción general

Sisachu es una aplicación PHP orientada a gestionar toda la operativa de una hacienda:
registro de animales, ordeño, control reproductivo, balances contables, movimientos de
leche y entregas, administración de potreros, personal y clientes. El código se organiza en
módulos de negocio escritos como clases PHP (carpeta `negocio/`), scripts de interfaz
HTML/PHP (carpeta `interfaces/`) y recursos front-end complementarios (`js/`, `css/`,
`img/`, `fonts/`, `fpdf/`).

## Arquitectura

| Carpeta | Descripción |
| --- | --- |
| `negocio/` | Capa de lógica de negocio. Cada archivo define una clase que orquesta consultas SQL sobre PostgreSQL (vía la clase base `connex`) y genera HTML para las interfaces. |
| `interfaces/` | Puntos de entrada web. Validan la sesión, instancian las clases de negocio y dibujan vistas con Bootstrap, FPDF o XLSX según corresponda. Muchos scripts contienen funciones auxiliares para componer formularios dinámicos. |
| `js/` | Funciones JavaScript de apoyo para la interfaz (menús, validaciones, cálculos). |
| `css/`, `img/`, `fonts/`, `fpdf/` | Recursos visuales, tipografías, librería FPDF y plantillas. |

> **Dependencias externas:** el proyecto utiliza PostgreSQL como motor de base de datos,
> [FPDF](https://www.fpdf.org/) para generar reportes en PDF y
> [php-xlsxwriter](https://github.com/mk-j/PHP_XLSXWriter) para exportaciones a Excel.

### Clases ausentes

Algunas clases base referenciadas no se incluyen en el repositorio y se esperan en tiempo
de ejecución:

- `USUARIO.php`: clase base utilizada por `HACIENDA`, `ANIMALES` y módulos derivados para
  funciones de sesión, autenticación y menús.
- `TIPO.php`: clase base compartida por `INGRESOS` y `EGRESO` (gestiona catálogos de tipos
  contables).

## Clases de negocio (`negocio/`)

### `connex` (`negocio/BASE.php`)
Clase responsable de abrir conexiones PostgreSQL y ejecutar consultas.

- `__construct()`: configura las credenciales y abre una conexión inicial.
- `conectar()`: devuelve un manejador de conexión PostgreSQL fresco.
- `consulta($pConsulta)`: ejecuta una sentencia SQL y retorna el resource.
- `fetchAll($sql)`: envoltura de `pg_fetch_all`.
- `fila($pConsulta)` / `row($pConsulta)`: devuelven la fila actual como arreglo asociativo.
- `num_rows($pConsulta)`: cuenta registros resultantes.

### `HACIENDA` (`negocio/HACIENDA.php`)
Gestiona la configuración general de la hacienda, dashboard y administración de usuarios.

- `mostrarFormularioHacienda()`: arma el formulario de datos fiscales y certificados. 【F:negocio/HACIENDA.php†L19-L109】
- `actualizarHacienda($data)`: normaliza entradas, maneja carga del certificado digital y actualiza la tabla `HACIENDA`. 【F:negocio/HACIENDA.php†L118-L216】
- `obtenerHacienda($id)`: recupera la información de una hacienda específica.
- `mostrarDashboard()`: compone la vista de panel con tarjetas y métricas.
- `mostrarEstadisticas()`: imprime estadísticas avanzadas de producción, reproducción y finanzas.
- `obtenerEstadisticas()`: calcula totales (animales, producción, ingresos, egresos) para el dashboard.
- `generarReporteMensual($mes, $anio)`: agrega métricas mensuales (producción, ventas, egresos, reproducción).
- `mostrarReporteMensual($reporte, $mes, $anio)`: imprime tablas y tarjetas para un reporte generado.
- `mostrarAlertas()`: lista alertas operativas (certificados por vencer, tareas críticas).
- `obtenerActividadReciente()`: consulta movimientos recientes (producción, compras, tareas).
- `obtenerTareasPendientes()`: arma la lista de tareas a realizar.
- `exportarDatos($tipo, $fecha_inicio, $fecha_fin)`: exporta registros en CSV/JSON según tipo (animales, leche, egresos, etc.).
- `obtenerEstadisticasRapidas($idhac = null)`: consolida cifras rápidas para tarjetas del dashboard.
- `modificarHacienda($datos)`: versión alternativa de actualización para campos básicos.
- `mostrarUsuarios()`: dibuja la tabla de usuarios de la hacienda.
- `mostrarFormularioUsuario($idUsuario = null)`: muestra el formulario (vacío o con datos previos) para crear/editar usuarios.
- `crearUsuarioHacienda($data)`: inserta un nuevo usuario asociado a la hacienda.
- `actualizarUsuario($data)`: persiste cambios de un usuario existente.
- `desactivarUsuario($idUsuario)` / `activarUsuario($idUsuario)`: cambia el estado activo del usuario.

### `ANIMALES` (`negocio/ANIMALES.php`)
Extiende `USUARIO` y centraliza la gestión de animales, fotos, controles veterinarios y reportes.

- `crearAnimal($datos)`: valida razas y proveedores nuevos, aplica valores por defecto e inserta en `ANIMALES`. 【F:negocio/ANIMALES.php†L28-L104】
- `listarRazas($op,$id=0)` / `listarProveedor($op,$id=0)`: generan arreglos o `<select>` con razas y proveedores. 【F:negocio/ANIMALES.php†L107-L134】
- `listarAnimales($op,$nombre='',$id=0)`: devuelve listas HTML o arreglos filtrados por nombre. 【F:negocio/ANIMALES.php†L135-L178】
- `listarAnimalesMadres(...)` / `listarAnimalesPadres(...)`: versiones filtradas por sexo para selección de progenitores.
- `mostrarAnimal($id)`: obtiene un registro individual de `ANIMALES`.
- `mostrarFotoAnimal($id,$msize)` / `mostrarFotoAnimalURL(...)` / `mostrarFotosAnimal(...)`: recuperan las fotos asociadas. 【F:negocio/ANIMALES.php†L190-L222】
- `agregarFotoAnimal($id,$name)`: persiste la ruta de una nueva foto. 【F:negocio/ANIMALES.php†L214-L222】
- `modificarAnimalTabla($datos)` / `modificarAnimal($datos)`: actualizan campos de identificación, genealogía y estado. 【F:negocio/ANIMALES.php†L223-L311】
- `crearControl($d)`: registra un control veterinario en `CONTROLES`. 【F:negocio/ANIMALES.php†L312-L340】
- `listarControles($id)`: renderiza el historial de controles del animal. 【F:negocio/ANIMALES.php†L326-L356】
- `eliminarControl($id)`: elimina un control. 【F:negocio/ANIMALES.php†L350-L356】
- `cuadroResumen()`: construye botones estadísticos por raza, sexo, especie, estado productivo y estado en hacienda. 【F:negocio/ANIMALES.php†L357-L399】
- `eliminarAnimal($id)` / `eliminarFoto($codfot)`: borran registros asociados. 【F:negocio/ANIMALES.php†L400-L411】
- `mostrarImprimirPDF($pdf)` / `mostrarImprimir($tipo)`: generan listados impresos (PDF/HTML) con genealogía. 【F:negocio/ANIMALES.php†L412-L567】
- `excelAnimales($idhac,$writer)`: exporta el censo de animales a CSV/Excel. 【F:negocio/ANIMALES.php†L568-L627】
- `empleadosSelect()` / `empleadosoption()`: selectores de empleados activos. 【F:negocio/ANIMALES.php†L628-L648】
- `clientesSelect()` / `clientesoption()`: selectores de clientes activos. 【F:negocio/ANIMALES.php†L649-L668】
- `tablaModificarSexo($idsex)` / `tablaModificarEstadoHacienda($esthac)` / `tablaModificarReproductivo($estrep)` / `tablaModificarEspecie($espani)` / `tablaModificarRaza($idraza)`: despliegan tablas editables filtradas por sexo, estado, especie o raza. 【F:negocio/ANIMALES.php†L669-L1066】
- `mostraListaReproduccionAtender($anio, $mes)`: muestra hembras que necesitan atención reproductiva inmediata (procesos activos o sin procesos recientes). 【F:negocio/ANIMALES.php†L1067-L1289】
- `mostraListaReproduccion($anio, $mes)`: lista hembras para planificación mensual de reproducción. 【F:negocio/ANIMALES.php†L1230-L1334】
- `subirFoto($datos,$f)`: gestiona la subida física de una foto y enlaza con el registro. 【F:negocio/ANIMALES.php†L1335-L1354】
- `asignarAnimalGrupo($idgru,$idani)`: vincula un animal con un grupo productivo. 【F:negocio/ANIMALES.php†L1355-L1362】

### `GRUPO` (`negocio/GRUPO.php`)
Administra los grupos productivos (categorías de animales).

- `mostrarGrupo($idgru)`: obtiene un grupo específico. 【F:negocio/GRUPO.php†L18-L23】
- `mostrarInicio()`: imprime acciones principales (buscar, crear, resumen). 【F:negocio/GRUPO.php†L24-L36】
- `buscarGrupo($txtbuscar)`: genera tabla de resultados por detalle. 【F:negocio/GRUPO.php†L38-L85】
- `mostrarModificar($id)`: muestra formulario editable del grupo y animales asignados.
- `listarAnimalesGrupo($id)` / `listarAnimalesGrupoNombres($id)`: listados HTML de animales por grupo. 【F:negocio/GRUPO.php†L112-L141】
- `Modificar($datos)`: actualiza datos del grupo. 【F:negocio/GRUPO.php†L142-L151】
- `eliminar($id)`: elimina un grupo. 【F:negocio/GRUPO.php†L153-L163】
- `mostrarCrear()`: formulario para crear grupos. 【F:negocio/GRUPO.php†L165-L188】
- `nuevo($param)`: inserta nuevo grupo. 【F:negocio/GRUPO.php†L188-L200】
- `eliminarasignarAnimalGrupo($id)`: remueve la relación animal-grupo. 【F:negocio/GRUPO.php†L201-L210】
- `listarGrupos()` / `listarGruposOption()` / `listarGruposOptionLeche()` / `listarGruposOptionTotalLechera()` / `listarGruposOptionTotal()` / `listarGruposOptionTotalLechero()`: catálogos para selects según contexto (general, leche diaria, totales). 【F:negocio/GRUPO.php†L210-L271】
- `cantidadRejo($idhac)`: estadística específica por hacienda. 【F:negocio/GRUPO.php†L272-L281】

### `LECHE` (`negocio/LECHE.php`)
Extiende `GRUPO` y controla los registros de ordeño y producción.

- `mostrarInicio()`: panel de acciones (registrar, buscar, resumen) con filtros por grupo y fechas. 【F:negocio/LECHE.php†L45-L82】
- `mostrarCrear($feclecc)` / `mostrarCrearAnt()`: formularios para capturar producción diaria (versión actual y anterior). 【F:negocio/LECHE.php†L83-L340】
- `nuevo($datos)` / `nuevo2($datos)`: guardan mediciones de leche en `LECHE`. 【F:negocio/LECHE.php†L299-L390】
- `mostrarProduccionDia($feclec, $idhac)`: resume producción por grupo y totales. 【F:negocio/LECHE.php†L358-L372】
- `buscarLeches($fec,$fecfin)`: consulta registros por rango de fechas. 【F:negocio/LECHE.php†L391-L427】
- `eliminar($id)`: elimina un registro de leche. 【F:negocio/LECHE.php†L428-L450】
- `obtenerMedidaAnterior(...)`: busca lectura previa del tanque para validaciones. 【F:negocio/LECHE.php†L451-L546】
- `mostrarModificar($id)` / `mostrarModificarAnt($id)`: cargan formularios de edición (versión actual y antigua). 【F:negocio/LECHE.php†L547-L814】
- `Modificar($datos)` / `ModificarAnt($datos)`: actualizan registros. 【F:negocio/LECHE.php†L815-L883】
- `resumenGrupos(...)` / `resumenGruposHistorial(...)`: reportes agregados por grupo y periodo. 【F:negocio/LECHE.php†L896-L983】
- `mostrarIngresarLeche($fechaInicio, $fechaFin,$idgru)`: asistente para ingresar datos por grupo. 【F:negocio/LECHE.php†L984-L1219】
- `obtenerLitrosTanque($idhac, $milimetros)`: convierte lectura de varilla en litros. 【F:negocio/LECHE.php†L1220-L1233】
- `regleche()`: catálogo de tipos de ordeño (`$tiepoleche`). 【F:negocio/LECHE.php†L1234-L1424】
- `contarAnimalesPorEdadYSexo($meses, $sexani)`: conteo de animales por edad y sexo. 【F:negocio/LECHE.php†L1425-L1448】
- `obtenerUltimoRegistroFecha(...)` / `obtenerUltimoRegistro($idhac)`: ayudan a precargar mediciones históricas. 【F:negocio/LECHE.php†L1449-L1468】

### `ENTREGA` (`negocio/ENTREGA.php`)
Gestiona la entrega de leche a clientes.

- `mostrarInicio()`: botones para registrar, buscar y resumir entregas. 【F:negocio/ENTREGA.php†L18-L32】
- `mostrarCrear()`: formulario de registro con cliente, empleado y controles de calidad. 【F:negocio/ENTREGA.php†L33-L79】
- `nuevo($datos)`: inserta la entrega en `ENTREGA` y devuelve el identificador. 【F:negocio/ENTREGA.php†L97-L121】
- `mostrarModificar($id)` / `mostrarModificarAnte($id)`: cargan formularios para editar entregas (versión actual/anterior). 【F:negocio/ENTREGA.php†L123-L236】
- `ModificarEntrega($datos, $files)` / `ModificarAnte($datos)`: guardan cambios realizados. 【F:negocio/ENTREGA.php†L237-L332】
- `buscar($fec,$fecfin)`: listado filtrado por fechas. 【F:negocio/ENTREGA.php†L333-L389】
- `contarLitros($ident,$totent)`: recalcula totales por entrega. 【F:negocio/ENTREGA.php†L390-L418】
- `eliminar($id)`: borra la entrega. 【F:negocio/ENTREGA.php†L419-L441】
- `agregarLecheEntrega($datos)` / `eliminarLecheEntrega($id)`: asocia registros de leche consumidos en la entrega. 【F:negocio/ENTREGA.php†L442-L519】
- `mostrarResumeEntregas(...)` / `resumenEntregas(...)`: resume volúmenes entregados en un rango. 【F:negocio/ENTREGA.php†L520-L607】
- `mostrarIngresarEntregaLeche()`: formulario para cargar entregas con múltiples lotes. 【F:negocio/ENTREGA.php†L608-L697】
- `guardarEntregaConLeches($datos)`: persiste entregas junto con registros de leche seleccionados. 【F:negocio/ENTREGA.php†L698-L756】

### `CLIENTE` (`negocio/CLIENTE.php`)
Extiende `ENTREGA` para gestionar clientes.

- `mostrarInicio()`: renderiza formulario de búsqueda y acciones principales. 【F:negocio/CLIENTE.php†L17-L27】
- `mostrarCrear()`: formulario de alta. 【F:negocio/CLIENTE.php†L28-L73】
- `nuevo($datos)`: inserta un cliente. 【F:negocio/CLIENTE.php†L74-L91】
- `listarClientes()` / `clientesSelect()` / `buscarCliente($txtbuscar)` / `buscar($txtbuscar)`: listados y búsquedas. 【F:negocio/CLIENTE.php†L92-L139】
- `mostrarModificar($id)`: carga datos existentes. 【F:negocio/CLIENTE.php†L140-L170】
- `modificarCliente($datos)`: actualiza registros. 【F:negocio/CLIENTE.php†L171-L196】
- `eliminarCliente($codcli)`: baja lógica/borrado. 【F:negocio/CLIENTE.php†L197-L229】

### `EMPLEADOS` (`negocio/EMPLEADOS.php`)
Maneja los registros de personal.

- `mostrarInicio()`: menú principal para empleados. 【F:negocio/EMPLEADOS.php†L19-L32】
- `mostrarCrear()`: formulario de alta. 【F:negocio/EMPLEADOS.php†L33-L186】
- `nuevo($param)`: inserta empleados con datos laborales. 【F:negocio/EMPLEADOS.php†L187-L225】
- `buscar($txtbuscar)`: tabla filtrada por apellido. 【F:negocio/EMPLEADOS.php†L226-L253】
- `mostrarModificar($id)`: formulario de edición prellenado. 【F:negocio/EMPLEADOS.php†L254-L407】
- `Modificar($datos)`: guarda cambios. 【F:negocio/EMPLEADOS.php†L408-L441】
- `Eliminar($idemp)`: elimina registros. 【F:negocio/EMPLEADOS.php†L442-L476】

### `ESTANCIA` (`negocio/ESTANCIA.php`)
Controla las estancias/pastoreo de grupos en potreros.

- `mostrarInicio()`: acciones para gestionar estancias. 【F:negocio/ESTANCIA.php†L29-L89】
- `crearEstancia($idgru, $idsub, $responsable)`: inicia una estancia con responsable asignado. 【F:negocio/ESTANCIA.php†L90-L133】
- `listarCantidadAnimalesGrupo($idgru)`: cuenta animales por grupo. 【F:negocio/ESTANCIA.php†L134-L147】
- `listarPotrerosOption()`: `<select>` de potreros disponibles. 【F:negocio/ESTANCIA.php†L148-L160】
- `guardarEstancia(...)`: persiste datos completos de una estancia. 【F:negocio/ESTANCIA.php†L161-L188】
- `listarEstancia($idgru, $idsub, $fini, $ffin)`: historial por rango. 【F:negocio/ESTANCIA.php†L189-L289】
- `mostrarEstancia($idest)`: detalle individual. 【F:negocio/ESTANCIA.php†L290-L346】
- `modificarEstancia(...)`: actualiza fechas, estado y responsables. 【F:negocio/ESTANCIA.php†L347-L378】
- `mostrarEstanciasActual($idhac)`: lista estancias en curso. 【F:negocio/ESTANCIA.php†L379-L489】
- `guardarEstanciaMobile($idgru, $idsub, $feciniest)`: versión móvil de inicio rápido. 【F:negocio/ESTANCIA.php†L490-L543】
- `registrarSalidaEstancia($idest)`: marca la salida de un grupo del potrero. 【F:negocio/ESTANCIA.php†L544-L581】

### `DIARIO` (`negocio/DIARIO.php`)
Registra el diario de ordeño y producción animal.

- `mostrarInicioDiario()`: opciones iniciales del diario. 【F:negocio/DIARIO.php†L20-L54】
- `listarAnimalesGrupoDiario($idgrupo,$id)`: despliega animales por grupo para registrar datos. 【F:negocio/DIARIO.php†L40-L54】
- `mostrarDiarioLeche($id)`: vista detallada por animal. 【F:negocio/DIARIO.php†L55-L83】
- `crearDiario($datos)` / `crearDiarioAnimal($datos)`: crean encabezado y detalle de diario. 【F:negocio/DIARIO.php†L84-L203】
- `mostrarDiarioAnimalesTabla($id)` / `mostrarDiarioAnimalesTablaNuevos($id)`: tablas editables de animales y litros. 【F:negocio/DIARIO.php†L122-L311】
- `mostrarIngresarDiarioLeche($idhac)`: formulario para ingresar mediciones por ordeño. 【F:negocio/DIARIO.php†L204-L251】
- `registrarDiario($datos)` / `guardarDiarioAnimal($datos)`: guardan registros ingresados. 【F:negocio/DIARIO.php†L252-L264】
- `modificarDiarioAnimal($datos)` / `modificarlitrosDiarioAnimal($datos)`: editan cantidades. 【F:negocio/DIARIO.php†L265-L287】
- `eliminarDiarioAnimal($datos)` / `eliminarlitrosDiarioAnimal($datos)`: remueven entradas. 【F:negocio/DIARIO.php†L288-L311】
- `mostrarRegistroDiario($id)`: muestra el resumen consolidado. 【F:negocio/DIARIO.php†L485-L561】

### `DASHBOARD` (`negocio/DASHBOARD.php`)
Genera vistas analíticas para un animal específico.

- `mostrarDashboard($idani)`: arma el panel general del animal. 【F:negocio/DASHBOARD.php†L79-L116】
- `mostrarInfoBasica($animal)`: resume información base. 【F:negocio/DASHBOARD.php†L117-L144】
- `mostrarEstadisticasVida($animal)`: calcula eventos clave (edad, producción). 【F:negocio/DASHBOARD.php†L145-L198】
- `mostrarArbolGenealogico($idani)`: dibuja el árbol de padres y abuelos. 【F:negocio/DASHBOARD.php†L199-L327】
- `mostrarProduccionLechera($idani)`: gráficos y tablas de producción histórica. 【F:negocio/DASHBOARD.php†L328-L368】
- `mostrarEstadisticasReproduccion($idani)`: métricas de reproducción. 【F:negocio/DASHBOARD.php†L369-L453】
- `mostrarReproduccionActual($idani)`: estado actual de gestación y procesos. 【F:negocio/DASHBOARD.php†L454-L692】
- `debugAnimal($idani)`: utilitario de depuración con información cruda. 【F:negocio/DASHBOARD.php†L693-L1304】
- `mostrarReproduccionMachos($idani)`: estadísticas enfocadas en machos. 【F:negocio/DASHBOARD.php†L1305-L1436】

### `CONTROLES` (`negocio/CONTROLES.php`)
Reporta controles por animal.

- `mostrarInicio()`: formulario para buscar animales y fechas. 【F:negocio/CONTROLES.php†L16-L33】
- `mostrarControlesAnimal($id)`: imprime controles clínicos y reproductivos de un animal. 【F:negocio/CONTROLES.php†L34-L166】
- `listarControlesFecha($fini,$ffin)`: consulta por rango de fechas. 【F:negocio/CONTROLES.php†L167-L261】

### `INGRESOS` (`negocio/INGRESOS.php`)
Gestiona ingresos económicos y facturación de leche.

- `mostrarInicio()` / `mostrarInicioFactura()`: menús principales (ingresos y facturas de leche). 【F:negocio/INGRESOS.php†L6-L74】
- `mostrarCrearFacturaLeche($fini,$ffin)` / `crearFacturaLeche($datos)` / `mostrarFacturaLeche($idFactura)` / `listarFacturarPorFechas(...)`: flujo completo de facturación de leche. 【F:negocio/INGRESOS.php†L75-L661】
- `modificarFacturaLeche($datos)` / `eliminarFacturaLeche($idFactura)`: mantenimiento de facturas. 【F:negocio/INGRESOS.php†L662-L872】
- `buscarIngreso($fini,$ffin)`: filtro por fechas. 【F:negocio/INGRESOS.php†L873-L913】
- `obtenerCliente()`: lista clientes activos. 【F:negocio/INGRESOS.php†L914-L930】
- `mostrarCrearIngresos()`: formulario para nuevos ingresos. 【F:negocio/INGRESOS.php†L931-L1006】
- `obtenerCuentasContables()` / `obtenerCuentasDebe()` / `obtenerCuentasHaber()`: catálogos contables. 【F:negocio/INGRESOS.php†L1007-L1507】
- `obtenerAnimales()` / `obtenerFacturasSinIngreso()`: datos relacionados a animales y facturas pendientes. 【F:negocio/INGRESOS.php†L1020-L1048】
- `texto($cadena)`: sanitiza strings. 【F:negocio/INGRESOS.php†L1049-L1056】
- `crearIngreso($datos)`: inserta ingresos con partida doble. 【F:negocio/INGRESOS.php†L1057-L1130】
- `mostrarIngreso($id)` / `mostrarIngresoAnt($id)`: muestran ingresos en formatos nuevo/anterior. 【F:negocio/INGRESOS.php†L1131-L1375】
- `eliminarFacturaDeIngreso(...)` / `agregarFacturasAIngreso(...)` / `opcionesFacturasDisponibles(...)`: relacionan facturas con ingresos. 【F:negocio/INGRESOS.php†L1270-L1325】
- `modificarIngreso($datos)` / `modificarIngresoAnt($datos)` / `eliminarIngreso($id)`: mantenimiento de ingresos. 【F:negocio/INGRESOS.php†L1376-L1483】
- `validarPartidaDoble(...)`: asegura que las cuentas no sean iguales. 【F:negocio/INGRESOS.php†L1516-L1527】
- `generarJavaScriptCuentas()` con funciones internas `sugerirCuentas()` y `validarCuentasDiferentes()`: genera script de ayuda para selects contables. 【F:negocio/INGRESOS.php†L1528-L1554】

### `EGRESO` (`negocio/EGRESO.php`)
Procesa egresos contables y retenciones.

- `mostrarInicio()`: panel principal de egresos. 【F:negocio/EGRESO.php†L8-L26】
- `buscarEgreso($fini,$ffin)`: lista egresos por fechas. 【F:negocio/EGRESO.php†L27-L66】
- `obtenerProveedor()`: catálogo de proveedores. 【F:negocio/EGRESO.php†L67-L78】
- `obtenerCuentasContables($nivel = 1)`: recorre el plan contable jerárquico. 【F:negocio/EGRESO.php†L79-L95】
- `mostrarCrearEgresos()`: formulario de creación. 【F:negocio/EGRESO.php†L96-L155】
- `texto($cadena)`: limpieza de cadenas. 【F:negocio/EGRESO.php†L156-L163】
- `crearEgreso($datos)` (versión estructurada) y sobrecarga `crearEgreso($montoegr, ...)`: insertan egresos simples y contables. 【F:negocio/EGRESO.php†L164-L287】【F:negocio/EGRESO.php†L492-L514】
- `mostrarEgreso($id)` / `mostrarEgreso($codegr)`: muestran detalle de egreso (dos variantes). 【F:negocio/EGRESO.php†L228-L312】【F:negocio/EGRESO.php†L567-L577】
- `eliminarEgreso($id)`: borra registros. 【F:negocio/EGRESO.php†L313-L321】
- `modificarEgreso($datos)` y versión extendida: actualizan información. 【F:negocio/EGRESO.php†L322-L371】【F:negocio/EGRESO.php†L555-L566】
- `generarRetencionEgresoProduccion($codegr)` / `generarRetencion($codegr)`: calculan retenciones automáticas. 【F:negocio/EGRESO.php†L372-L491】【F:negocio/EGRESO.php†L589-L630】
- `clave($codegr)`: obtiene claves de autorización. 【F:negocio/EGRESO.php†L430-L491】
- `mostrarRol($username)`: obtiene roles del usuario. 【F:negocio/EGRESO.php†L515-L520】
- `mostrarEgresos($detalleaspi,$param)` / `devolverEgreso($codegr,$codusu)` / `cuentas()` / `mostrarEgresosCuentas($fecha,$detalle)`: listados auxiliares y reversos contables. 【F:negocio/EGRESO.php†L521-L554】

### `BALANCE` (`negocio/BALANCE.php`)
Construye reportes de ingresos vs egresos.

- `listarCategorias()` / `listarSubCategorias($codcat)`: catálogos de cuentas. 【F:negocio/BALANCE.php†L17-L29】
- `listarClientes()`: clientes asociados. 【F:negocio/BALANCE.php†L30-L36】
- `crearCliente($nomcli)`: inserta clientes contables. 【F:negocio/BALANCE.php†L37-L46】
- `mostrarSubcategori($codsub)`: muestra subcategoría específica. 【F:negocio/BALANCE.php†L47-L53】
- `crearIngreso($datos)`: registra ingreso de categoría. 【F:negocio/BALANCE.php†L54-L61】
- `listarCuentas()`: obtiene lista plana de cuentas. 【F:negocio/BALANCE.php†L62-L67】
- `crearAbonoIngreso($datos)`: aplica abonos a ingresos. 【F:negocio/BALANCE.php†L68-L85】

### `CUENTA` (`negocio/CUENTA.php`)
Administra cuentas contables.

- `mostrarInicio()`: opciones principales. 【F:negocio/CUENTA.php†L15-L41】
- `crearCuenta($datos)`: inserta cuentas (con padre e información financiera). 【F:negocio/CUENTA.php†L42-L64】
- `buscarCuenta($criterio)`: búsqueda por código o nombre. 【F:negocio/CUENTA.php†L65-L76】
- `mostrarResultadoCuenta($res)`: imprime resultados. 【F:negocio/CUENTA.php†L77-L113】
- `mostrarCuenta($id)`: muestra detalle. 【F:negocio/CUENTA.php†L114-L133】
- `modificarCuenta($datos)`: actualiza. 【F:negocio/CUENTA.php†L134-L150】
- `eliminarCuenta($id)`: elimina. 【F:negocio/CUENTA.php†L151-L158】
- `mostrarResumenCuentas()`: genera resumen jerárquico. 【F:negocio/CUENTA.php†L159-L188】

### `POTREROS` (`negocio/POTREROS.php`)
Gestiona potreros registrados.

- `__construct()`: inicia conexión e identifica hacienda. 【F:negocio/POTREROS.php†L9-L15】
- `mostrarInicio()`: menú de acciones. 【F:negocio/POTREROS.php†L16-L25】
- `mostrarCrear()`: formulario de alta. 【F:negocio/POTREROS.php†L26-L58】
- `nuevo($datos)`: inserta potrero. 【F:negocio/POTREROS.php†L59-L86】
- `buscar($txtbuscar)`: filtro por nombre. 【F:negocio/POTREROS.php†L87-L117】
- `mostrarModificar($id)`: formulario de edición. 【F:negocio/POTREROS.php†L118-L144】
- `modificarPotrero($datos)`: actualiza. 【F:negocio/POTREROS.php†L145-L167】
- `eliminarPotrero($idPotrero)`: elimina. 【F:negocio/POTREROS.php†L168-L196】

### `MATERIAL` (`negocio/MATERIAL.php`)
Registra insumos y materiales.

- `mostrarInicio()` / `mostrarCrear()`: interfaz básica. 【F:negocio/MATERIAL.php†L12-L44】
- `nuevo($datos)`: inserta material. 【F:negocio/MATERIAL.php†L45-L62】
- `buscar($txtbuscar)`: búsqueda por detalle. 【F:negocio/MATERIAL.php†L63-L97】
- `mostrarModificar($id)`: formulario de edición. 【F:negocio/MATERIAL.php†L98-L124】
- `modificarMaterial($datos)`: actualiza. 【F:negocio/MATERIAL.php†L125-L142】
- `eliminarMaterial($idMaterial)`: elimina. 【F:negocio/MATERIAL.php†L143-L168】

### `MAQUINARIA` (`negocio/MAQUINARIA.php`)
Gestiona maquinaria e implementos (extiende `MATERIAL`).

- `mostrarInicio()` / `mostrarCrear()`: acciones principales. 【F:negocio/MAQUINARIA.php†L12-L40】
- `nuevo($datos)`: inserta maquinaria. 【F:negocio/MAQUINARIA.php†L41-L56】
- `buscar($txtbuscar)`: filtro. 【F:negocio/MAQUINARIA.php†L57-L88】
- `mostrarModificar($id)`: formulario de edición. 【F:negocio/MAQUINARIA.php†L89-L115】
- `modificarMaquinaria($datos)`: guarda cambios. 【F:negocio/MAQUINARIA.php†L116-L132】
- `eliminarMaquinaria($id)`: elimina. 【F:negocio/MAQUINARIA.php†L133-L163】

### `DIARIO` (`negocio/DIARIO.php`)
*(documentado arriba)*

### `potrero` (`negocio/potrero.php`)
Clase independiente con operaciones CRUD directas sobre la tabla `potrero` (usa `$this->conn` aunque no define la conexión).

- `crearPotrero(...)`: inserta un potrero (requiere `conn`). 【F:negocio/potrero.php†L17-L28】
- `modificarPotrero(...)`: actualiza registros. 【F:negocio/potrero.php†L29-L40】
- `eliminarPotrero($id_potrero)`: elimina registros. 【F:negocio/potrero.php†L41-L52】

## Interfaces (`interfaces/`)
Cada script inicia sesión, verifica permisos y coordina las acciones del módulo de negocio correspondiente.

| Archivo | Función principal |
| --- | --- |
| `uianimales.php` | Presenta menús de búsqueda/creación de animales, gestiona exportaciones (PDF/Excel) y define funciones auxiliares (`buscarinicial`, `mostrarCrear`, `listarAnimales`, `mostrarAnimal`, `subirFoto`, `mostrarDiv`, `menu`). 【F:interfaces/uianimales.php†L1-L371】|
| `uiclientes.php` | Panel CRUD para clientes (invoca métodos de `CLIENTE`). 【F:interfaces/uiclientes.php†L1-L55】|
| `uiempleados.php` | Gestión de empleados con formularios y listados (usa `EMPLEADOS`). |
| `uigrupo.php` | Administración de grupos productivos (crear, asignar animales, eliminar). |
| `uileche.php` | Registro de producción de leche, incluye funciones JS `sumar`/`restar` para totales. 【F:interfaces/uileche.php†L1-L70】|
| `uidiario.php` | Entrada al diario de ordeño y gestión de registros por animal. |
| `uicontroles.php` | Consulta de controles veterinarios (incluye funciones `listarAnimales` y `mostrarDiv`). 【F:interfaces/uicontroles.php†L1-L80】|
| `uientrega.php` | Registro y modificación de entregas de leche (usa `ENTREGA`); incluye `calcularMultiplicacion()` para totales de litros. 【F:interfaces/uientrega.php†L1-L120】|
| `uiegresos.php` | Flujo completo de egresos (buscar, crear, modificar, eliminar). 【F:interfaces/uiegresos.php†L1-L52】|
| `uiingresos.php` | Maneja ingresos y facturación; define `inicialCrear`, `listarIngresosFecha`, `ponerMonto`. 【F:interfaces/uiingresos.php†L1-L185】|
| `uifactura.php` | Facturación electrónica (montaje del formulario e invocación a `INGRESOS`). |
| `uiraza.php` | Control de catálogo de razas. |
| `uireproduccion.php` | Vistas para planeación y seguimiento reproductivo. |
| `uihacienda.php` | Dashboard integral de hacienda, configuración, gestión de usuarios y reportes; define funciones JS `mostrarFormularioUsuario` y `ocultarFormularioUsuario`. 【F:interfaces/uihacienda.php†L1-L420】|
| `uisuscripcion.php` | Gestión de suscripciones/planes. |
| `uirol.php` | Administración de roles de usuario. |
| `uitratamientos.php` | Registro de tratamientos veterinarios; reutiliza función `listarAnimales`. 【F:interfaces/uitratamientos.php†L1-L120】|
| `uitrabajos.php` | Control de trabajos/actividades. |
| `uipotreros.php` | CRUD de potreros. |
| `uiestancia.php` | Interfaces para estancias en potreros. |
| `uimaquinaria.php` | Manejo de maquinaria. |
| `uimaterial.php` | Manejo de materiales/insumos. |
| `uiproveedores.php` | Gestión de proveedores. |
| `uitanque.php` | Interfaz de control del tanque de leche. |
| `uitipoie.php` | Administración de tipos de ingreso/egreso. |
| `uibalance.php` | Reportes de balance (estructura vacía en el repositorio actual). |
| `mobile.php` | Interfaz móvil con navegación inicial (`inicio`) y funciones de cálculo (`sumar`, `restar`). 【F:interfaces/mobile.php†L1-L140】|
| `menu.php` | Plantilla JavaScript para mostrar/ocultar el menú lateral. 【F:interfaces/menu.php†L1-L80】|
| `jqingresos.php` | Endpoints AJAX para crear clientes, cargar subcategorías y montos. 【F:interfaces/jqingresos.php†L1-L80】|
| `obtener_litros_tanque.php` | Servicio para convertir lectura de varilla del tanque a litros usando `LECHE::obtenerLitrosTanque`. |

> Varios scripts utilizan la plantilla común `encabezado.php`, Bootstrap 5 y el menú generado por `USUARIO::mostrarMenu`.

## JavaScript (`js/`)

- `script.js`: funciones de interfaz (`menu`, `ocultarMostrar`, `mostrarDiv`) y validación decimal. 【F:js/script.js†L1-L46】
- `javascript.js`: animación jQuery para alternar formularios de login. 【F:js/javascript.js†L1-L2】
- `mobile.php` y `uileche.php` incluyen funciones `sumar` y `restar` para cálculos de litros.

## Tablas de base de datos referenciadas

Las clases operan sobre tablas con nombres en mayúsculas (PostgreSQL), entre las que destacan: `ANIMALES`, `RAZA`, `PROVEEDOR`, `CONTROLES`, `LECHE`, `ENTREGA`, `CLIENTE`, `EMPLEADOS`, `GRUPO`, `REPRODUCCION`, `HACIENDA`, `INGRESO`, `EGRESO`, `CUENTA`, `MATERIAL`, `POTRERO`, `ANIMAL_GRUPO`, `FOTO`.

## Flujo general

1. Los scripts de `interfaces/` validan la sesión (`$_SESSION['idhac']`) y muestran menús.
2. Según las acciones del usuario (`$_REQUEST`/`$_POST`), invocan métodos de las clases en `negocio/`.
3. Las clases construyen consultas SQL mediante `connex::consulta()` y generan HTML directamente.
4. Para reportes exportables se utilizan FPDF (PDF) o XLSXWriter (Excel/CSV).

