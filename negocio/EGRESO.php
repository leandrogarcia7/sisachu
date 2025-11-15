<?php
require_once("TIPO.php");


//require_once("negocio/INGRESO.php");
class EGRESO extends TIPO 
{
     public function mostrarInicio() {
        $fechaInicio = date('Y-m-d', strtotime('-1 month'));
        echo '<center><form>
            <button type="submit" name="bttcrearEgreso"> 
                <img src="../img/anadir.png" alt=""/> <br> CREAR EGRESO
            </button>
            <br><br>
            <b>Buscar por rango de fechas: </b>
            Desde: <input type="date" name="fechaInicio" value="' . $fechaInicio . '">
            Hasta: <input type="date" name="fechaFin" value="'. date('Y-m-d') .'">
            <button type="submit" name="bttbuscarPorFechas"> 
                <img src="../img/buscar.jpg" alt=""/> <br> BUSCAR POR FECHAS
            </button>
            <button type="submit" name="bttresumenEgreso"> 
                <img src="../img/cuadernorojo.png" alt=""/> <br> RESUMEN EGRESO
            </button>
        </form></center>';
    }

    public function buscarEgreso($fini, $ffin) {
        $fechaInicio = date('Y-m-d', strtotime($fini));
        $fechaFin = date('Y-m-d', strtotime($ffin));

        $query = 'SELECT "EGRESO".idegr, detegr, montoegr, fecegr, idtipe, obsegr, nompro 
                  FROM "EGRESO","PROVEEDOR","TIPO_EGRESO" 
                  WHERE "TIPO_EGRESO".id="EGRESO".idtipe AND "TIPO_EGRESO".idhac='.$_SESSION['idhac'].' AND "PROVEEDOR".codpro="EGRESO".codpro   
                  AND fecegr BETWEEN \''.$fechaInicio.'\' AND \''.$fechaFin.'\' 
                  ORDER BY fecegr DESC';

        $stmt = $this->consulta($query);

        echo '<table border="1" class="table table-stripted" >
                <tr>
                    <th>ID</th><th>Detalle</th><th>Monto</th><th>Fecha</th><th>Tipo</th><th>Observaciones</th><th>Proveedor</th>
                </tr>';

        while ($row = $this->row($stmt)) {
            echo '<form><tr>
                    <td>' . $row['idegr'] . '</td>
                    <td>' . $row['detegr'] . '</td>   
                    <td>' . $row['montoegr'] . '</td>
                    <td>' . $row['fecegr'] . '</td>
                    <td>' . $row['idtipe'] . '</td>
                    <td>' . $row['obsegr'] . '</td>
                    <td>' . $row['nompro'] . '</td>
                    <td><button name=bttselEgreso value='.$row['idegr'].'> 
                            <img src="../img/modif.jpg" alt="Modificar"> 
                            <br>Seleccionar
                        </button></td>
                    <td><button name=btteliEgreso onclick="javascript: return confirm(\'¿Está seguro de eliminar el egreso?\');" value='.$row['idegr'].'> 
                            <img src="../img/cancelar.jpg" alt="Eliminar"> 
                            <br>Eliminar
                        </button></td>
                  </tr></form>';
        }

        echo '</table>';
    }

    public function obtenerProveedor() {
        $proveedores = array();
        $con = $this->consulta('SELECT codpro, nompro FROM "PROVEEDOR" where idhac='.$_SESSION['idhac']);
        while ($row = $this->row($con)) {
            $proveedores[] = array(
                'codpro' => $row['codpro'],
                'nompro' => $row['nompro']
            );
        }
        return $proveedores;
    }

 public function obtenerCuentasContables($nivel = 1) {
   

    $sql = 'SELECT codcue, detcue,nivel1cue,nivel2cue,nivel3cue,nivel4cue,nivel1cue
            FROM public."CUENTA"
            WHERE nivel1cue = '.$nivel.'
            ORDER BY nivel1cue,nivel2cue,nivel3cue,nivel4cue,nivel1cue;';

    $res = $this->consulta($sql);
    $cuentas = [];

    while ($reg = $this->fila($res)) {
        $cuentas[] = $reg;
    }
    return $cuentas;
}

    public function mostrarCrearEgresos() {
    $tiposEgreso = $this->obtenerTiposEgresoDesdeClaseTipo();
    $proveedores = $this->obtenerProveedor();
    $cuentas = $this->obtenerCuentasContables(); // usa nivel1cue = 1
    $cuentasDebe = $this->obtenerCuentasContables(5);
    $cuentasHaber = $this->obtenerCuentasContables(1);


    echo "<center>
        <form method='post' enctype='multipart/form-data'>
            <table BORDER=1>
                <th colspan=2><center>Crear un nuevo Egreso</center></th>

                <tr><th>Detalle</th><td><input type='text' name='detegr' required></td></tr>

                <tr><th>Monto</th><td><input type='number' name='montoegr' step='0.01' required></td></tr>

                <tr><th>Fecha</th><td><input type='date' name='fecegr' value='" . date('Y-m-d') . "' required></td></tr>

                <tr><th>Tipo Egreso</th><td><select name='idtipe'>";
                    foreach ($tiposEgreso as $tipo) {
                        echo "<option value='" . $tipo['id'] . "'>" . $tipo['dette'] . "</option>";
                    }
    echo        "</select></td></tr>

                <tr><th>Proveedor</th><td><select name='codpro'>";
                    foreach ($proveedores as $prov) {
                        echo "<option value='" . $prov['codpro'] . "'>" . $prov['nompro'] . "</option>";
                    }
    echo        "</select></td></tr>

                <tr><th>Cuenta al DEBE</th><td><select name='codcuedebe'>";
foreach ($cuentasDebe as $cue) {
    echo "<option value='{$cue['codcue']}'>{$cue['nivel1cue']} {$cue['nivel2cue']} {$cue['nivel3cue']} {$cue['nivel4cue']} {$cue['nivel5cue']} - {$cue['detcue']}</option>";
}
echo "</select></td></tr>

<tr><th>Cuenta al HABER</th><td><select name='codcuehaber'>";
foreach ($cuentasHaber as $cue) {
    echo "<option value='{$cue['codcue']}'>{$cue['nivel1cue']} {$cue['nivel2cue']} {$cue['nivel3cue']} {$cue['nivel4cue']} {$cue['nivel5cue']} - {$cue['detcue']}</option>";
}
echo "</select></td></tr>


                <tr><th>Observaciones</th><td><input type='text' name='obsegr'></td></tr>

                <tr><th>Subir Imagen</th><td><input type='file' name='imagenEgreso' accept='image/*'></td></tr>

                <tr><td colspan=2>
                    <center>
                        <button type='submit' name='bttguardarEgreso'>
                            <img src='../img/guardar.jpg' alt='Crear Egreso'><br>CREAR EGRESO
                        </button>
                    </center>
                </td></tr>
            </table>
        </form>
    </center>";
}

    public function texto($cadena) {
    if ($cadena === null || trim($cadena) === '') {
        return 'NULL';
    }
    return "'" . pg_escape_string($cadena) . "'";
}

    
  public function crearEgreso($datos) {
    // 1. Procesar imagen
    $nombreImagen = null;
    if (isset($_FILES['imagenEgreso']) && $_FILES['imagenEgreso']['error'] === UPLOAD_ERR_OK) {
        $imagen = $_FILES['imagenEgreso'];
        $nombreImagen = 'egreso_' . time() . '_' . basename($imagen['name']);
        $rutaImagen = __DIR__ . '/../egresos/' . $nombreImagen;

        if (!move_uploaded_file($imagen['tmp_name'], $rutaImagen)) {
            echo "<div class='errores'>Error al guardar la imagen del egreso.</div>";
            return false;
        }
    }

    // 2. Validar monto
    $datos['montoegr'] = str_replace(',', '.', $datos['montoegr']);
    if (!is_numeric($datos['montoegr']) || $datos['montoegr'] < 0) {
        echo '<div style="background-color: #ffcccc; color: #b30000; padding: 10px; border: 2px solid #b30000; border-radius: 8px; font-weight: bold; text-align: center; margin-bottom: 10px;">
                Error: El monto ingresado no es válido.
              </div>';
        return false;
    }

    // 3. Insertar egreso
    $sql = 'INSERT INTO public."EGRESO" (
                montoegr, fecegr, idtipe, obsegr, detegr, codpro,
                codcuedebe, codcuehaber, imagen
            ) VALUES (
                ' . $datos['montoegr'] . ',
                \'' . $datos['fecegr'] . '\',
                ' . intval($datos['idtipe']) . ',
                ' . $this->texto($datos['obsegr']) . ',
                ' . $this->texto($datos['detegr']) . ',
                ' . intval($datos['codpro']) . ',
                ' . ($datos['codcuedebe'] ?: 'NULL') . ',
                ' . ($datos['codcuehaber'] ?: 'NULL') . ',
                ' . ($nombreImagen ? "'" . $nombreImagen . "'" : 'NULL') . '
            );';

if ($this->consulta($sql)) {
    echo "<div style='
        background-color: #d4edda;
        color: #155724;
        padding: 20px;
        border: 2px solid #c3e6cb;
        border-radius: 10px;
        text-align: center;
        font-size: 20px;
        font-weight: bold;
        max-width: 600px;
        margin: 30px auto;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    '>
        ✅ Egreso registrado correctamente.
    </div>";
    return true;
} else {
    echo "<div class='errores'>No se pudo registrar el egreso.</div>";
    return false;
}

}


public function mostrarEgreso($id) {
    // Obtener datos del egreso
    $sql = 'SELECT e.*, t.dette, p.nompro, cd.detcue AS cuenta_debe, ch.detcue AS cuenta_haber
            FROM public."EGRESO" e
            LEFT JOIN public."TIPO_EGRESO" t ON t.id = e.idtipe
            LEFT JOIN public."PROVEEDOR" p ON p.codpro = e.codpro
            LEFT JOIN public."CUENTA" cd ON cd.codcue = e.codcuedebe
            LEFT JOIN public."CUENTA" ch ON ch.codcue = e.codcuehaber
            WHERE e.idegr = ' . intval($id) . ';';
    
    $res = $this->consulta($sql);
    $egreso = $this->fila($res);

    if (!$egreso) {
        echo "<div class='errores'>No se encontró el egreso.</div>";
        return;
    }

    // Listas para selects
    $tiposEgreso = $this->obtenerTiposEgresoDesdeClaseTipo();
    $proveedores = $this->obtenerProveedor();
    $cuentasDebe = $this->obtenerCuentasContables(5);
    $cuentasHaber = $this->obtenerCuentasContables(1);

    echo "<center>
        <form method='post' enctype='multipart/form-data'>
            <input type='hidden' name='idegr' value='{$egreso['idegr']}'>
            <table BORDER=1>
                <th colspan=2><center>Editar Egreso</center></th>

                <tr><th>Detalle</th><td><input type='text' name='detegr' value='{$egreso['detegr']}' required></td></tr>

                <tr><th>Monto</th><td><input type='number' name='montoegr' step='0.01' value='{$egreso['montoegr']}' required></td></tr>

                <tr><th>Fecha</th><td><input type='date' name='fecegr' value='{$egreso['fecegr']}' required></td></tr>

                <tr><th>Tipo Egreso</th><td><select name='idtipe'>";
    foreach ($tiposEgreso as $tipo) {
        $selected = ($tipo['id'] == $egreso['idtipe']) ? 'selected' : '';
        echo "<option value='{$tipo['id']}' $selected>{$tipo['dette']}</option>";
    }
    echo "</select></td></tr>

                <tr><th>Proveedor</th><td><select name='codpro'>";
    foreach ($proveedores as $prov) {
        $selected = ($prov['codpro'] == $egreso['codpro']) ? 'selected' : '';
        echo "<option value='{$prov['codpro']}' $selected>{$prov['nompro']}</option>";
    }
    echo "</select></td></tr>

                <tr><th>Cuenta al DEBE</th><td><select name='codcuedebe'>";
    foreach ($cuentasDebe as $cue) {
        $selected = ($cue['codcue'] == $egreso['codcuedebe']) ? 'selected' : '';
        echo "<option value='{$cue['codcue']}' $selected>{$cue['codcue']} - {$cue['detcue']}</option>";
    }
    echo "</select></td></tr>

                <tr><th>Cuenta al HABER</th><td><select name='codcuehaber'>";
    foreach ($cuentasHaber as $cue) {
        $selected = ($cue['codcue'] == $egreso['codcuehaber']) ? 'selected' : '';
        echo "<option value='{$cue['codcue']}' $selected>{$cue['codcue']} - {$cue['detcue']}</option>";
    }
    echo "</select></td></tr>

                <tr><th>Observaciones</th><td><input type='text' name='obsegr' value='{$egreso['obsegr']}'></td></tr>";

    if ($egreso['imagen']) {
        echo "<tr><th>Imagen Actual</th><td>
                <img src='../egresos/{$egreso['imagen']}' style='max-width:300px;'><br><br>
                Subir nueva imagen: <input type='file' name='imagenEgreso' accept='image/*'>
              </td></tr>";
    } else {
        echo "<tr><th>Subir Imagen</th><td><input type='file' name='imagenEgreso' accept='image/*'></td></tr>";
    }

    echo "<tr><td colspan=2><center>
            <button name='bttmodificarEgreso'>
                <img src='../img/guardar.jpg' alt='Guardar'><br>GUARDAR CAMBIOS
            </button></center></td></tr>
            </table>
        </form>
    </center>";
}


   public function eliminarEgreso($id) {
    if ($this->consulta('delete from "EGRESO" where idegr=' . $id)) {
        echo "<div class=mesajeok >Egreso Eliminado</div>";
    } else {
        echo "<div class=errores >Error al eliminar el Egreso de la BDD</div>";
    }
}


public function modificarEgreso($datos) {
    // Construcción de la sentencia SQL para actualizar
    $sql = 'UPDATE "EGRESO" SET 
            detegr = \'' . $datos['detegr'] . '\',
            montoegr = ' . $datos['montoegr'] . ',
            fecegr = \'' . $datos['fecegr'] . '\',
            idtipe = ' . $datos['idtipe'] . ',
            obsegr = \'' . $datos['obsegr'] . '\',
            codpro = ' . $datos['codpro'] . '    
            WHERE idegr = ' . $datos['idegr'] . ';';

    // Ejecución de la consulta
    if ($res1 = $this->consulta($sql)) {
        echo "<div class=mesajeok>Egreso actualizado exitosamente</div>";
    } else {
        echo "<div class=errores>Error al actualizar el egreso en BDD " . $sql . " - " . pg_result_error($res1) . "</div>";
    } 
}


    
    
	/*
    	public $ruc='1703276830001';
	public $ambiente='1';
	public $tipoEmision='1';
	public $razonSocial='CRUZ VELASQUEZ GLADIS INES';
	public $nombreComercial='UNIDAD EDUCATIVA "RINCON DEL SABER"';
	public $codfac=0;
	public $codDoc='07';
	public $estab='001';
	public $ptoEmi='002';
	public $secuencial='000000000';
	public $dirMatriz= "LAUREANO CRUZ OE9-35 Y JULIAN ESTRELLA";

	public $xsdstring="../../connex/exml/comprobanteRetencionUERS.xml";
	public $cclave='';
        
        public $mail='';
        public $nomrep='';
    */
    /*
    
    
    
    
    
    
    
    
     public function generarRetencionEgresoProduccion($codegr){
        //validar pago para generar factura
        //1. el padre debe registrar factura    listo
        //2. debe tener un pago de detalle con factura listo
        //3. debe tener registrado ruc o cedula 
        //4. debe tener un correo v�lido 
        //5. con esto se puede crear un codigo para factura y agregarlo a la clave
            
        $clave=$this->clave($codegr);
        $this->cclave=$clave;
        $nf=''.$clave.'.xml';
	$factura='../../retencion/'.$nf;
        $xml=$this->generarRetencion($codegr);
        if($xml){
        $xml->save($factura);
        //$comando='/usr/bin/java -jar /var/www/sri/dist/sri.jar /var/www/firmar/gladys_ines_cruz_velasquez.p12 mbaPOLI7 /var/www/html/saepu/facturas/'.$nf.' /var/www/html/saepu/facturas/ f'.$nf.'';
        $comando='/usr/bin/java -jar /var/www/sri/dist/sri.jar /var/www/firmar/gladys_ines_cruz_velasquez.p12 mbaPOLI7 /var/www/html/saepu/retencion/'.$nf.' /var/www/html/saepu/retencion/ f'.$nf.'';
        $comando='java -jar C:\sri-master\dist/sri.jar C:\firmar\gladys_ines_cruz_velasquez.p12 mbaPOLI7 C:\xampp\htdocs\saepu\retencion/'.$nf.' C:\xampp\htdocs\saepu\retencion\ f'.$nf.'';
        $salida=exec($comando);
        
        $linfirmar='/var/www/html/saepu/retencion/r'.$nf.'';
   	$linfirmar='C:/xampp/htdocs/saepu/retencion/'.$this->cclave.'.xml';
         $valid=1;

$xml = file_get_contents($linfirmar);
if($valid==1){

    
$sriRecepcionComprobantesOfflineServiceValidar = new SriRecepcionComprobantesOfflineServiceValidar();
// sample call for SriRecepcionComprobantesOfflineServiceValidar::validarComprobante()
if($sriRecepcionComprobantesOfflineServiceValidar->validarComprobante(new SriRecepcionComprobantesOfflineStructValidarComprobante($xml))){
 //   print_r($sriRecepcionComprobantesOfflineServiceValidar->getResult());
    $a=$sriRecepcionComprobantesOfflineServiceValidar->getResult();
    
      $pdff='C:/xampp/htdocs/saepu/interfaces/colecturia/pdf/'.$this->cclave.'.pdf';
     $pdff='/var/www/html/saepu/interfaces/colecturia/pdf/'.$this->cclave.'.pdf';
    
    
    if($a->RespuestaRecepcionComprobante->estado=='RECIBIDA'){ echo "Recibida por el SRI"; }else{ echo 'Error: '.$a->RespuestaRecepcionComprobante->estado;
    echo "<br>";print_r($a);
    }
     //$this->modificarFacturaTexto($this->codfac, 'respuesta', ''.$a->RespuestaRecepcionComprobante->estado);
     $this->modificarFacturaEnvio($this->codfac, $this->cclave, time(),$linfirmar,$pdff,''.$a->RespuestaRecepcionComprobante->estado, $this->mail);
    //enviar correo con la factura recibida
     $this->enviarCorreoFactura($linfirmar,$pdff);
// validar si fue recibida o devuelta
}else
    print_r($sriRecepcionComprobantesOfflineServiceValidar->getLastError());
$a=$sriRecepcionComprobantesOfflineServiceValidar->getLastError();
}   
            
        }
        
    
        
}
    
    
    public function clave($codegr){
            
	$codpago2=str_pad($codegr, 8, "0", STR_PAD_LEFT); 	
	
	$creado=time();
        //crear una nueva factura
        // $conexion = new connex();
        //validar si existe la clave
          if( $consulta = $this->consulta('Begin; insert into "RETENCION" (codegr,creado) values ('.$codegr.','.$creado.')')){
              $consulta = $this->consulta('select * from "RETENCION" where codegr='.$codegr.' and  creado= '.$creado.';');
              $r= pg_fetch_assoc($consulta);
              $codfac=str_pad($r['codret'], 9, "0", STR_PAD_LEFT); 
              $this->codfac=$r['codret'];
              $consulta = $this->consulta('commit;');
          }else{
              return false;
          }
        //el de 9 digitos hay que cambiar y crear 
        
	//$clave="<claveAcceso>";
	$clave='';
	$clave.=date("dmY");//Fecha de Emisi�n ddmmaaaa
	$clave.="01"; //tabla 3
	$clave.=$this->ruc; //ruc
	$clave.=$this->ambiente; //ambiente 1 pruebas 2 produccion
	$clave.="001002" ; //numero serial de las facturas de 6
	$clave.=$codfac ; //Nmero del Comprobante (secuencial)  de 9
	$clave.=$codpago2;//digo Numrico  de 8
	$clave.=$this->tipoEmision;//tipo de emision 1
	//aplicar el modulo11 para verificar factor de chequeo ponderado 2
	
	
	//$clave.="";//D�gito Verificador (m�dulo 11
	
	//$clave.="</claveAcceso>";
	//echo $clave."<br>";
	$mod11="234567";$m=0;$acu=0;
	for($i=47;$i>=0;$i--){
		$v=$clave[$i]*$mod11[$m];
		$acu+=$v;
		//echo "<br>".$i.": ".$clave[$i]." x ".$mod11[$m]."=".$v;
		$m++;
		if($m>5)$m=0;
	}
	//echo "<br> Total: ".$acu;
	$dig= $acu%11 ;
	$dig= 11-$dig;
	if($dig==11)$dig=0;
	if($dig==10)$dig=1;
	//echo "<br>Digito ".$dig;
	$clave.=$dig;
	//$clave="<claveAcceso>".$clave."</claveAcceso>";
	echo "<br>".$clave."<br>";
        
	return $clave;
	
	
}
    
    
    
    
public function crearEgreso($montoegr, $encargadoegr,$detalegr,$codusu,$codperiodo,$codcuedebe,$codcuehaber,$fecegr,$ncheque,$negreso){
	
	$conexion = new connex();
	
		$consulta = $conexion->consulta('begin; SELECT max(codegr) FROM "EGRESO"');
		$row = $conexion->row($consulta);
		$codegr=$row['max'] + 1;
		if($ncheque=='')
			$ncheque=0;	
		if($negreso=='')
			$negreso=0;	

		if($consulta = $conexion->consulta("INSERT INTO \"EGRESO\"(montoegr, fecegr, encargadoegr, estegr, codperiodo,detalegr,codcuedebe,codcuehaber,ncheque,negreso)
    VALUES (".$montoegr.", '".$fecegr."', '".$encargadoegr."', '1', ".$codperiodo.",'".$detalegr."',".$codcuedebe.",".$codcuehaber.",".$ncheque.",".$negreso.");
    ")){echo "Egreso Creado";}else{echo "Error al crear";}
                $consulta = $conexion->consulta("
    INSERT INTO \"AUDITORIA_COLECT\"(fecaudi, codusu, tabla, codcambio, montonuevo)
   			 		VALUES ('".date("d-m-Y")."',".$codusu." , 'EGRESO', ".$codegr.",".$montoegr.");
   			 		commit;
   			 		");
	
	return $codegr;
}
    public function mostrarRol($username)
{
		$conexion = new connex();
			$consulta = $conexion->consulta('SELECT rolusuario, codusu,codrol FROM "USUARIO"  WHERE username=\''.$username.'\';');
			return $consulta;
}
    public function mostrarEgresos($detalleaspi,$param)
{
		$conexion = new connex();
			$consulta = $conexion->consulta('SELECT * FROM "EGRESO" where '.$param.' ilike \''.$detalleaspi.'%\' and estegr=\'1\' order by fecegr,codegr ');
			return $consulta;
}
    public function devolverEgreso($codegr,$codusu)
{
		$conexion = new connex();
			$consulta = $conexion->consulta("begin; 
			UPDATE \"EGRESO\" SET
			estegr = '0' 
			WHERE codegr=".$codegr.";
			 INSERT INTO \"AUDITORIA_COLECT\"(fecaudi, codusu, tabla, codcambio, montonuevo)
   			 		VALUES ('".date("d-m-Y")."',".$codusu." , 'EGRESO', ".$codegr.",0);
   			 		commit;");
			return $consulta;
}
    public function cuentas()
{
		$conexion = new connex();
			$consulta = $conexion->consulta('SELECT * FROM "CUENTA" order by nivel1cue,nivel2cue,nivel3cue,nivel4cue,nivel5cue ;');
			return $consulta;
}

//mostrar ergresos con cuenta general para cambiar y fecha con detalle
    public function mostrarEgresosCuentas($fecha,$detalle)
{
		$conexion = new connex();
			$consulta = $conexion->consulta('SELECT * FROM "EGRESO" 
where fecegr >=\''.$fecha.'\' and (detalegr like \''.$detalle.'%\' or encargadoegr like \''.$detalle.'%\')  order by fecegr,codegr ');
			return $consulta;
}
//para modificar los egresos
    public function modificarEgreso($codegr,$codcuedebe,$codcuehaber,$fecegr,$montoegr,$encargadoegr,$detalegr,$negreso,$ncheque)
{
		$conexion = new connex();
			$consulta = $conexion->consulta("begin; 
			UPDATE \"EGRESO\" SET
			fecegr='".$fecegr."', montoegr=".$montoegr.",
			codcuedebe = ".$codcuedebe.", codcuehaber=".$codcuehaber." ,encargadoegr='".$encargadoegr."',
			detalegr='".$detalegr."',negreso=".$negreso.", ncheque=".$ncheque."
			WHERE codegr=".$codegr.";
			 		commit;");
			return $consulta;
}
    public function mostrarEgreso($codegr)
{
		$conexion = new connex();
			$consulta = $conexion->consulta("begin; 
			UPDATE \"EGRESO\" SET
			codcuedebe = ".$codcuedebe.", codcuehaber=".$codcuehaber." 
			WHERE codegr=".$codegr.";
			 		commit;");
			return $consulta;
}
//mostrarFacturasMes($fini,$ffin);
  public function mostrarFacturasMes($fini,$ffin)
{
		$conexion = new connex();
			$consulta = $conexion->consulta('select "PAGO".codpag,montodetpago,detaling,repralu,rucfam,fecpago,codfactura from "PAGO","FAMILIA","ALUMNO","INGRESO","DETALLE_PAGO"
where "PAGO".codfam="FAMILIA".codfam and 
"DETALLE_PAGO".codpago="PAGO".codpag and "INGRESO".coding="DETALLE_PAGO".coding 
and "ALUMNO".codalu="INGRESO".codalu and fecpago<=\''.$ffin.'\' and fecpago>=\''.$fini.'\'
order by codpag ');
			return $consulta;
}

public function generarRetencion($codegr){
		
            // $pdf=new FPDF();
           $pdf=new PDF_Code128();
        $pdf->AddPage('P','A4');
	$pdf->SetAutoPageBreak('false' , 1);
	$pdf->SetFont('Arial','',9);
       // $pdf->Line(10,7,290,7);
	//	$pdf->Line(10,27,290,27);
		$pdf->Image("logo.jpg",10,7.5,25,25);
                
                           
            $xml = new DOMDocument();
		$xml->load($this->xsdstring);
		$this->dirMatriz=utf8_encode("LAUREANO CRUZ OE9-35 Y JULIAN ESTRELLA");
		//$clave=$this->clave($codpago);
                $clave=$this->cclave;
                $this->transaccion=str_pad($this->codfac, 9, "0", STR_PAD_LEFT);
		//ingresar la informacion inicial
		$x = $xml->documentElement;
		$a=$x->getElementsByTagName("infoTributaria");
		
		//llenar los datos de informacin tributaria
		//$transaccion=str_pad($codpago, 9, "0", STR_PAD_LEFT);
		$a->item(0)->getElementsByTagName("claveAcceso")->item(0)->nodeValue=$clave;
		$a->item(0)->getElementsByTagName("ambiente")->item(0)->nodeValue=$this->ambiente;
		$a->item(0)->getElementsByTagName("tipoEmision")->item(0)->nodeValue=$this->tipoEmision;
		$a->item(0)->getElementsByTagName("razonSocial")->item(0)->nodeValue=$this->razonSocial;
		$a->item(0)->getElementsByTagName("nombreComercial")->item(0)->nodeValue=$this->nombreComercial;
		
		$a->item(0)->getElementsByTagName("ruc")->item(0)->nodeValue=$this->ruc;
		$a->item(0)->getElementsByTagName("codDoc")->item(0)->nodeValue=$this->codDoc;
		$a->item(0)->getElementsByTagName("estab")->item(0)->nodeValue=$this->estab;
		$a->item(0)->getElementsByTagName("ptoEmi")->item(0)->nodeValue=$this->ptoEmi;
		$a->item(0)->getElementsByTagName("secuencial")->item(0)->nodeValue=$this->transaccion;
		$a->item(0)->getElementsByTagName("dirMatriz")->item(0)->nodeValue=($this->dirMatriz);
//pdf agregar informacion tributaria
                $sal=5;
                $pdf->Cell(100,$sal,"",0,0,"L");$pdf->Cell(100,$sal,"R.U.C.: ".$this->ruc,0,1,"L");
                $pdf->Cell(100,$sal,"",0,0,"L");$pdf->Cell(100,$sal,"RETENCION",0,1,"L");
                $pdf->Cell(100,$sal,"",0,0,"L");$pdf->Cell(100,$sal,"No. ".$this->estab."-".$this->ptoEmi."-".($this->transaccion),0,1,"L");
                $pdf->Cell(100,$sal,"",0,0,"L");   $pdf->Cell(100,$sal,"N�MERO DE AUTORIZACI�N",0,1,"L");
                
                $pdf->Cell(100,$sal,"",0,0,"L");$pdf->Cell(100,$sal,$this->cclave,0,1,"L");
                $pdf->Cell(100,$sal,$this->razonSocial,0,0,"L");
                $pdf->SetFont('Arial','',8);
                $pdf->Cell(50,$sal,"FECHA Y HORA DE AUTORIZACI�N:",0,0,"L");
                 $pdf->SetFont('Arial','',9);
                $pdf->Cell(50,$sal, date("d/m/Y h:i:s"),0,1,"L");
                $pdf->Cell(100,$sal,$this->nombreComercial,0,0,"L");$pdf->Cell(50,$sal,"AMBIENTE:",0,0,"L");
                IF($this->ambiente==2)
                    $pdf->Cell(50,$sal,"PRODUCCION",0,1,"L");
                else
                    $pdf->Cell(50,$sal,"PRUEBAS",0,1,"L");
                    $pdf->SetFont('Arial','',8);
                $pdf->Cell(100,$sal,"Direcci�n Matriz: ".$this->dirMatriz,0,0,"L");
                   $pdf->SetFont('Arial','',9);
                $pdf->Cell(50,$sal,"EMISI�N:",0,0,"L");$pdf->Cell(50,$sal, ("NORMAL"),0,1,"L");
                  $pdf->SetFont('Arial','',8);
                $pdf->Cell(100,$sal,"Direcci�n Sucursal: ".$this->dirMatriz,0,0,"L");
                   $pdf->SetFont('Arial','',9);
                $pdf->Cell(100,$sal,"CLAVE DE ACCESO",0,1,"L");
                
                 $pdf->Ln($sal*2);
                
                 $pdf->Cell(100,$sal,"OBLIGADO A LLEVAR            SI",0,0,"C");$pdf->Cell(100,$sal,$this->cclave,0,1,"C");
    		$fecha=date("d-m-Y");
		
		$consulta = $this->consulta('SELECT *  FROM public."RETENCION" where codegr='.$codegr.' ;');
		
		if(!$reg=pg_fetch_assoc($consulta))
		{ echo "El pago no requiere factura electronica";return false;}
                //validacion de datos previa al envio del archivo
                //crear el codigo de barras
                //
         $reg=$_REQUEST;
                $code=$this->cclave;
                $pdf->Code128(110,55,$code,95,10);
                
		//rellenar el xml con los datos del pago
                //$x = $xml->documentElement;
		$a=$x->getElementsByTagName("infoCompRetencion");
                if($reg['rucfam']=='')$reg['rucfam']=$reg['repced'];
                
                
		if(strlen($reg['rucfam'])==10)$tipco='05';
		if(strlen($reg['rucfam'])==13)$tipco='04';
                
                if(strlen($reg['rucfam'])==0){ echo "Cedula incorrecta";return false;}

                    
		//llenar los datos de informacin tributaria
		if($reg['nombrefam']=='')$reg['nombrefam']=$reg['repralu'];
		$a->item(0)->getElementsByTagName("fechaEmision")->item(0)->nodeValue=date("d/m/Y",strtotime($reg['fecemi']));
		$a->item(0)->getElementsByTagName("dirEstablecimiento")->item(0)->nodeValue=($this->dirMatriz);
		//$a->item(0)->getElementsByTagName("contribuyenteEspecial")->item(0)->nodeValue="";
		$a->item(0)->getElementsByTagName("obligadoContabilidad")->item(0)->nodeValue="SI";
		$a->item(0)->getElementsByTagName("tipoIdentificacionSujetoRetenido")->item(0)->nodeValue=$tipco;
		$a->item(0)->getElementsByTagName("razonSocialSujetoRetenido")->item(0)->nodeValue=utf8_encode($reg['nombrefam']);
		$a->item(0)->getElementsByTagName("identificacionSujetoRetenido")->item(0)->nodeValue=$reg['rucfam'];
		$a->item(0)->getElementsByTagName("dirEstablecimiento")->item(0)->nodeValue=utf8_encode($reg['dir1fam']);
                $a->item(0)->getElementsByTagName("periodoFiscal")->item(0)->nodeValue=utf8_encode($reg['periodoFiscal']);
//para llenar los campos adicionales
                $de=$x->getElementsByTagName("infoAdicional");
               $de->item(0)->getElementsByTagName("campoAdicional")->item(1)->nodeValue=$reg['emailalu'];
               $de->item(0)->getElementsByTagName("campoAdicional")->item(0)->nodeValue=utf8_encode($reg['nombrefam']);
               $de->item(0)->getElementsByTagName("campoAdicional")->item(2)->nodeValue='Obser';
//llenar pdf factura
                $this->nomrep=$reg['nombrefam'];
                
                if($reg['emailalu']==''){
                   $this->nomrep= 'colecturia.uers@rincondelsaber.com';
                }
                $this->mail=$reg['emailalu'];
                
                $pdf->Cell(55,$sal,"Raz�n Social / Nombres y Apellidos",0,0,"L");$pdf->Cell(145,$sal,utf8_encode($reg['nombrefam']),0,1,"L");
                
                $pdf->Cell(55,$sal,"Identificaci�n",0,0,"L");$pdf->Cell(150,$sal,$reg['rucfam'],0,1,"L");
               $pdf->Cell(55,$sal,"Fecha",0,0,"L");$pdf->Cell(50,$sal,$fecha,0,1,"L");
                
                $pdf->Cell(55,$sal,"Direccion:",0,0,"L");$pdf->Cell(150,$sal,$reg['dir1fam'],0,1,"L");
                
                
                //debo mandar a los dos impuestos
                         
                $ims=$x->getElementsByTagName("impuestos");
                //// para el iva
                $iva=$x->getElementsByTagName("impuesto");
                 $iva->item(0)->getElementsByTagName("codigo")->item(0)->nodeValue=$_REQUEST['iva'];
                $iva->item(0)->getElementsByTagName("codigoRetencion")->item(0)->nodeValue=$_REQUEST['codigoRetencion'];
                $iva->item(0)->getElementsByTagName("baseImponible")->item(0)->nodeValue=$_REQUEST['baseImponible'];
               $por=0;
                switch ($_REQUEST['codigoRetencion']){
                    case 9:{ $por=10.00;  break;}
                    case 10:{ $por=20.00;  break;}
                    case 1:{ $por=30.00;  break;}
                    case 11:{ $por=50.00;  break;}
                    case 2:{ $por=70.00;  break;}
                    case 3:{ $por=100.00;  break;}
                }
                $iva->item(0)->getElementsByTagName("porcentajeRetener")->item(0)->nodeValue=$por;
                $iva->item(0)->getElementsByTagName("valorRetenido")->item(0)->nodeValue=$_REQUEST['valorRetenido'];
                $iva->item(0)->getElementsByTagName("codDocSustento")->item(0)->nodeValue='01';
              $iva->item(0)->getElementsByTagName("numDocSustento")->item(0)->nodeValue=$_REQUEST['numDocSustento'];
                   $iva->item(0)->getElementsByTagName("fechaEmisionDocSustento")->item(0)->nodeValue=$_REQUEST['fecemi'];
              //------------------para la renta
              
                       $renta=$x->getElementsByTagName("impuesto");
                 $renta->item(1)->getElementsByTagName("codigo")->item(0)->nodeValue=$_REQUEST['renta'];
                $renta->item(1)->getElementsByTagName("codigoRetencion")->item(0)->nodeValue=$_REQUEST['codrenta'];
                $renta->item(1)->getElementsByTagName("baseImponible")->item(0)->nodeValue=$_REQUEST['baseImponible'];
         
                $renta->item(1)->getElementsByTagName("porcentajeRetener")->item(0)->nodeValue=$_REQUEST['porrenta'];
                
                $vrenta= number_format($_REQUEST['baseImponible']*$_REQUEST['porrenta']/100,2);
                
                
                $renta->item(1)->getElementsByTagName("valorRetenido")->item(0)->nodeValue=$vrenta;
                $renta->item(1)->getElementsByTagName("codDocSustento")->item(0)->nodeValue='01';
              $renta->item(1)->getElementsByTagName("numDocSustento")->item(0)->nodeValue=$_REQUEST['numDocSustento'];
              $renta->item(1)->getElementsByTagName("fechaEmisionDocSustento")->item(0)->nodeValue=$_REQUEST['fecemi'];
          
	      //agregar el campo de informacion adicional en el formato para solo asignar directo
                
                  
                 $pdf->Output("pdf/ret".$this->cclave.".pdf",'F'); 
                 
                 
                 
                 
                 
//creamos en la tabla factura con la clave y los tiempos para actualizar
                
               // $this->crearFactura($clave,$codpago);
                
                    //crear el pdf de la factura
       
	//encabezado

       
                
                
                
		return $xml;
		
		
	}
*/	
}

	


require_once("TIPO.php");


//require_once("negocio/INGRESO.php");
class EGRESO extends TIPO 
{
     public function mostrarInicio() {
        $fechaInicio = date('Y-m-d', strtotime('-1 month'));
        echo '<center><form>
            <button type="submit" name="bttcrearEgreso"> 
                <img src="../img/anadir.png" alt=""/> <br> CREAR EGRESO
            </button>
            <br><br>
            <b>Buscar por rango de fechas: </b>
            Desde: <input type="date" name="fechaInicio" value="' . $fechaInicio . '">
            Hasta: <input type="date" name="fechaFin" value="'. date('Y-m-d') .'">
            <button type="submit" name="bttbuscarPorFechas"> 
                <img src="../img/buscar.jpg" alt=""/> <br> BUSCAR POR FECHAS
            </button>
            <button type="submit" name="bttresumenEgreso"> 
                <img src="../img/cuadernorojo.png" alt=""/> <br> RESUMEN EGRESO
            </button>
        </form></center>';
    }

    public function buscarEgreso($fini, $ffin) {
        $fechaInicio = date('Y-m-d', strtotime($fini));
        $fechaFin = date('Y-m-d', strtotime($ffin));

        $query = 'SELECT "EGRESO".idegr, detegr, montoegr, fecegr, idtipe, obsegr, nompro 
                  FROM "EGRESO","PROVEEDOR","TIPO_EGRESO" 
                  WHERE "TIPO_EGRESO".id="EGRESO".idtipe AND "TIPO_EGRESO".idhac='.$_SESSION['idhac'].' AND "PROVEEDOR".codpro="EGRESO".codpro   
                  AND fecegr BETWEEN \''.$fechaInicio.'\' AND \''.$fechaFin.'\' 
                  ORDER BY fecegr DESC';

        $stmt = $this->consulta($query);

        echo '<table border="1" class="table table-stripted" >
                <tr>
                    <th>ID</th><th>Detalle</th><th>Monto</th><th>Fecha</th><th>Tipo</th><th>Observaciones</th><th>Proveedor</th>
                </tr>';

        while ($row = $this->row($stmt)) {
            echo '<form><tr>
                    <td>' . $row['idegr'] . '</td>
                    <td>' . $row['detegr'] . '</td>   
                    <td>' . $row['montoegr'] . '</td>
                    <td>' . $row['fecegr'] . '</td>
                    <td>' . $row['idtipe'] . '</td>
                    <td>' . $row['obsegr'] . '</td>
                    <td>' . $row['nompro'] . '</td>
                    <td><button name=bttselEgreso value='.$row['idegr'].'> 
                            <img src="../img/modif.jpg" alt="Modificar"> 
                            <br>Seleccionar
                        </button></td>
                    <td><button name=btteliEgreso onclick="javascript: return confirm(\'¿Está seguro de eliminar el egreso?\');" value='.$row['idegr'].'> 
                            <img src="../img/cancelar.jpg" alt="Eliminar"> 
                            <br>Eliminar
                        </button></td>
                  </tr></form>';
        }

        echo '</table>';
    }

    public function obtenerProveedor() {
        $proveedores = array();
        $con = $this->consulta('SELECT codpro, nompro FROM "PROVEEDOR" where idhac='.$_SESSION['idhac']);
        while ($row = $this->row($con)) {
            $proveedores[] = array(
                'codpro' => $row['codpro'],
                'nompro' => $row['nompro']
            );
        }
        return $proveedores;
    }

 public function obtenerCuentasContables($nivel = 1) {
   

    $sql = 'SELECT codcue, detcue,nivel1cue,nivel2cue,nivel3cue,nivel4cue,nivel1cue
            FROM public."CUENTA"
            WHERE nivel1cue = '.$nivel.'
            ORDER BY nivel1cue,nivel2cue,nivel3cue,nivel4cue,nivel1cue;';

    $res = $this->consulta($sql);
    $cuentas = [];

    while ($reg = $this->fila($res)) {
        $cuentas[] = $reg;
    }
    return $cuentas;
}

    public function mostrarCrearEgresos() {
    $tiposEgreso = $this->obtenerTiposEgresoDesdeClaseTipo();
    $proveedores = $this->obtenerProveedor();
    $cuentas = $this->obtenerCuentasContables(); // usa nivel1cue = 1
    $cuentasDebe = $this->obtenerCuentasContables(5);
    $cuentasHaber = $this->obtenerCuentasContables(1);


    echo "<center>
        <form method='post' enctype='multipart/form-data'>
            <table BORDER=1>
                <th colspan=2><center>Crear un nuevo Egreso</center></th>

                <tr><th>Detalle</th><td><input type='text' name='detegr' required></td></tr>

                <tr><th>Monto</th><td><input type='number' name='montoegr' step='0.01' required></td></tr>

                <tr><th>Fecha</th><td><input type='date' name='fecegr' value='" . date('Y-m-d') . "' required></td></tr>

                <tr><th>Tipo Egreso</th><td><select name='idtipe'>";
                    foreach ($tiposEgreso as $tipo) {
                        echo "<option value='" . $tipo['id'] . "'>" . $tipo['dette'] . "</option>";
                    }
    echo        "</select></td></tr>

                <tr><th>Proveedor</th><td><select name='codpro'>";
                    foreach ($proveedores as $prov) {
                        echo "<option value='" . $prov['codpro'] . "'>" . $prov['nompro'] . "</option>";
                    }
    echo        "</select></td></tr>

                <tr><th>Cuenta al DEBE</th><td><select name='codcuedebe'>";
foreach ($cuentasDebe as $cue) {
    echo "<option value='{$cue['codcue']}'>{$cue['nivel1cue']} {$cue['nivel2cue']} {$cue['nivel3cue']} {$cue['nivel4cue']} {$cue['nivel5cue']} - {$cue['detcue']}</option>";
}
echo "</select></td></tr>

<tr><th>Cuenta al HABER</th><td><select name='codcuehaber'>";
foreach ($cuentasHaber as $cue) {
    echo "<option value='{$cue['codcue']}'>{$cue['nivel1cue']} {$cue['nivel2cue']} {$cue['nivel3cue']} {$cue['nivel4cue']} {$cue['nivel5cue']} - {$cue['detcue']}</option>";
}
echo "</select></td></tr>


                <tr><th>Observaciones</th><td><input type='text' name='obsegr'></td></tr>

                <tr><th>Subir Imagen</th><td><input type='file' name='imagenEgreso' accept='image/*'></td></tr>

                <tr><td colspan=2>
                    <center>
                        <button type='submit' name='bttguardarEgreso'>
                            <img src='../img/guardar.jpg' alt='Crear Egreso'><br>CREAR EGRESO
                        </button>
                    </center>
                </td></tr>
            </table>
        </form>
    </center>";
}

    public function texto($cadena) {
    if ($cadena === null || trim($cadena) === '') {
        return 'NULL';
    }
    return "'" . pg_escape_string($cadena) . "'";
}

    
  public function crearEgreso($datos) {
    // 1. Procesar imagen
    $nombreImagen = null;
    if (isset($_FILES['imagenEgreso']) && $_FILES['imagenEgreso']['error'] === UPLOAD_ERR_OK) {
        $imagen = $_FILES['imagenEgreso'];
        $nombreImagen = 'egreso_' . time() . '_' . basename($imagen['name']);
        $rutaImagen = __DIR__ . '/../egresos/' . $nombreImagen;

        if (!move_uploaded_file($imagen['tmp_name'], $rutaImagen)) {
            echo "<div class='errores'>Error al guardar la imagen del egreso.</div>";
            return false;
        }
    }

    // 2. Validar monto
    $datos['montoegr'] = str_replace(',', '.', $datos['montoegr']);
    if (!is_numeric($datos['montoegr']) || $datos['montoegr'] < 0) {
        echo '<div style="background-color: #ffcccc; color: #b30000; padding: 10px; border: 2px solid #b30000; border-radius: 8px; font-weight: bold; text-align: center; margin-bottom: 10px;">
                Error: El monto ingresado no es válido.
              </div>';
        return false;
    }

    // 3. Insertar egreso
    $sql = 'INSERT INTO public."EGRESO" (
                montoegr, fecegr, idtipe, obsegr, detegr, codpro,
                codcuedebe, codcuehaber, imagen
            ) VALUES (
                ' . $datos['montoegr'] . ',
                \'' . $datos['fecegr'] . '\',
                ' . intval($datos['idtipe']) . ',
                ' . $this->texto($datos['obsegr']) . ',
                ' . $this->texto($datos['detegr']) . ',
                ' . intval($datos['codpro']) . ',
                ' . ($datos['codcuedebe'] ?: 'NULL') . ',
                ' . ($datos['codcuehaber'] ?: 'NULL') . ',
                ' . ($nombreImagen ? "'" . $nombreImagen . "'" : 'NULL') . '
            );';

if ($this->consulta($sql)) {
    echo "<div style='
        background-color: #d4edda;
        color: #155724;
        padding: 20px;
        border: 2px solid #c3e6cb;
        border-radius: 10px;
        text-align: center;
        font-size: 20px;
        font-weight: bold;
        max-width: 600px;
        margin: 30px auto;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    '>
        ✅ Egreso registrado correctamente.
    </div>";
    return true;
} else {
    echo "<div class='errores'>No se pudo registrar el egreso.</div>";
    return false;
}

}


public function mostrarEgreso($id) {
    // Obtener datos del egreso
    $sql = 'SELECT e.*, t.dette, p.nompro, cd.detcue AS cuenta_debe, ch.detcue AS cuenta_haber
            FROM public."EGRESO" e
            LEFT JOIN public."TIPO_EGRESO" t ON t.id = e.idtipe
            LEFT JOIN public."PROVEEDOR" p ON p.codpro = e.codpro
            LEFT JOIN public."CUENTA" cd ON cd.codcue = e.codcuedebe
            LEFT JOIN public."CUENTA" ch ON ch.codcue = e.codcuehaber
            WHERE e.idegr = ' . intval($id) . ';';
    
    $res = $this->consulta($sql);
    $egreso = $this->fila($res);

    if (!$egreso) {
        echo "<div class='errores'>No se encontró el egreso.</div>";
        return;
    }

    // Listas para selects
    $tiposEgreso = $this->obtenerTiposEgresoDesdeClaseTipo();
    $proveedores = $this->obtenerProveedor();
    $cuentasDebe = $this->obtenerCuentasContables(5);
    $cuentasHaber = $this->obtenerCuentasContables(1);

    echo "<center>
        <form method='post' enctype='multipart/form-data'>
            <input type='hidden' name='idegr' value='{$egreso['idegr']}'>
            <table BORDER=1>
                <th colspan=2><center>Editar Egreso</center></th>

                <tr><th>Detalle</th><td><input type='text' name='detegr' value='{$egreso['detegr']}' required></td></tr>

                <tr><th>Monto</th><td><input type='number' name='montoegr' step='0.01' value='{$egreso['montoegr']}' required></td></tr>

                <tr><th>Fecha</th><td><input type='date' name='fecegr' value='{$egreso['fecegr']}' required></td></tr>

                <tr><th>Tipo Egreso</th><td><select name='idtipe'>";
    foreach ($tiposEgreso as $tipo) {
        $selected = ($tipo['id'] == $egreso['idtipe']) ? 'selected' : '';
        echo "<option value='{$tipo['id']}' $selected>{$tipo['dette']}</option>";
    }
    echo "</select></td></tr>

                <tr><th>Proveedor</th><td><select name='codpro'>";
    foreach ($proveedores as $prov) {
        $selected = ($prov['codpro'] == $egreso['codpro']) ? 'selected' : '';
        echo "<option value='{$prov['codpro']}' $selected>{$prov['nompro']}</option>";
    }
    echo "</select></td></tr>

                <tr><th>Cuenta al DEBE</th><td><select name='codcuedebe'>";
    foreach ($cuentasDebe as $cue) {
        $selected = ($cue['codcue'] == $egreso['codcuedebe']) ? 'selected' : '';
        echo "<option value='{$cue['codcue']}' $selected>{$cue['codcue']} - {$cue['detcue']}</option>";
    }
    echo "</select></td></tr>

                <tr><th>Cuenta al HABER</th><td><select name='codcuehaber'>";
    foreach ($cuentasHaber as $cue) {
        $selected = ($cue['codcue'] == $egreso['codcuehaber']) ? 'selected' : '';
        echo "<option value='{$cue['codcue']}' $selected>{$cue['codcue']} - {$cue['detcue']}</option>";
    }
    echo "</select></td></tr>

                <tr><th>Observaciones</th><td><input type='text' name='obsegr' value='{$egreso['obsegr']}'></td></tr>";

    if ($egreso['imagen']) {
        echo "<tr><th>Imagen Actual</th><td>
                <img src='../egresos/{$egreso['imagen']}' style='max-width:300px;'><br><br>
                Subir nueva imagen: <input type='file' name='imagenEgreso' accept='image/*'>
              </td></tr>";
    } else {
        echo "<tr><th>Subir Imagen</th><td><input type='file' name='imagenEgreso' accept='image/*'></td></tr>";
    }

    echo "<tr><td colspan=2><center>
            <button name='bttmodificarEgreso'>
                <img src='../img/guardar.jpg' alt='Guardar'><br>GUARDAR CAMBIOS
            </button></center></td></tr>
            </table>
        </form>
    </center>";
}


   public function eliminarEgreso($id) {
    if ($this->consulta('delete from "EGRESO" where idegr=' . $id)) {
        echo "<div class=mesajeok >Egreso Eliminado</div>";
    } else {
        echo "<div class=errores >Error al eliminar el Egreso de la BDD</div>";
    }
}


public function modificarEgreso($datos) {
    // Construcción de la sentencia SQL para actualizar
    $sql = 'UPDATE "EGRESO" SET 
            detegr = \'' . $datos['detegr'] . '\',
            montoegr = ' . $datos['montoegr'] . ',
            fecegr = \'' . $datos['fecegr'] . '\',
            idtipe = ' . $datos['idtipe'] . ',
            obsegr = \'' . $datos['obsegr'] . '\',
            codpro = ' . $datos['codpro'] . '    
            WHERE idegr = ' . $datos['idegr'] . ';';

    // Ejecución de la consulta
    if ($res1 = $this->consulta($sql)) {
        echo "<div class=mesajeok>Egreso actualizado exitosamente</div>";
    } else {
        echo "<div class=errores>Error al actualizar el egreso en BDD " . $sql . " - " . pg_result_error($res1) . "</div>";
    } 
}


    
    
	/*
    	public $ruc='1703276830001';
	public $ambiente='1';
	public $tipoEmision='1';
	public $razonSocial='CRUZ VELASQUEZ GLADIS INES';
	public $nombreComercial='UNIDAD EDUCATIVA "RINCON DEL SABER"';
	public $codfac=0;
	public $codDoc='07';
	public $estab='001';
	public $ptoEmi='002';
	public $secuencial='000000000';
	public $dirMatriz= "LAUREANO CRUZ OE9-35 Y JULIAN ESTRELLA";

	public $xsdstring="../../connex/exml/comprobanteRetencionUERS.xml";
	public $cclave='';
        
        public $mail='';
        public $nomrep='';
    */
    /*
    
    
    
    
    
    
    
    
     public function generarRetencionEgresoProduccion($codegr){
        //validar pago para generar factura
        //1. el padre debe registrar factura    listo
        //2. debe tener un pago de detalle con factura listo
        //3. debe tener registrado ruc o cedula 
        //4. debe tener un correo v�lido 
        //5. con esto se puede crear un codigo para factura y agregarlo a la clave
            
        $clave=$this->clave($codegr);
        $this->cclave=$clave;
        $nf=''.$clave.'.xml';
	$factura='../../retencion/'.$nf;
        $xml=$this->generarRetencion($codegr);
        if($xml){
        $xml->save($factura);
        //$comando='/usr/bin/java -jar /var/www/sri/dist/sri.jar /var/www/firmar/gladys_ines_cruz_velasquez.p12 mbaPOLI7 /var/www/html/saepu/facturas/'.$nf.' /var/www/html/saepu/facturas/ f'.$nf.'';
        $comando='/usr/bin/java -jar /var/www/sri/dist/sri.jar /var/www/firmar/gladys_ines_cruz_velasquez.p12 mbaPOLI7 /var/www/html/saepu/retencion/'.$nf.' /var/www/html/saepu/retencion/ f'.$nf.'';
        $comando='java -jar C:\sri-master\dist/sri.jar C:\firmar\gladys_ines_cruz_velasquez.p12 mbaPOLI7 C:\xampp\htdocs\saepu\retencion/'.$nf.' C:\xampp\htdocs\saepu\retencion\ f'.$nf.'';
        $salida=exec($comando);
        
        $linfirmar='/var/www/html/saepu/retencion/r'.$nf.'';
   	$linfirmar='C:/xampp/htdocs/saepu/retencion/'.$this->cclave.'.xml';
         $valid=1;

$xml = file_get_contents($linfirmar);
if($valid==1){

    
$sriRecepcionComprobantesOfflineServiceValidar = new SriRecepcionComprobantesOfflineServiceValidar();
// sample call for SriRecepcionComprobantesOfflineServiceValidar::validarComprobante()
if($sriRecepcionComprobantesOfflineServiceValidar->validarComprobante(new SriRecepcionComprobantesOfflineStructValidarComprobante($xml))){
 //   print_r($sriRecepcionComprobantesOfflineServiceValidar->getResult());
    $a=$sriRecepcionComprobantesOfflineServiceValidar->getResult();
    
      $pdff='C:/xampp/htdocs/saepu/interfaces/colecturia/pdf/'.$this->cclave.'.pdf';
     $pdff='/var/www/html/saepu/interfaces/colecturia/pdf/'.$this->cclave.'.pdf';
    
    
    if($a->RespuestaRecepcionComprobante->estado=='RECIBIDA'){ echo "Recibida por el SRI"; }else{ echo 'Error: '.$a->RespuestaRecepcionComprobante->estado;
    echo "<br>";print_r($a);
    }
     //$this->modificarFacturaTexto($this->codfac, 'respuesta', ''.$a->RespuestaRecepcionComprobante->estado);
     $this->modificarFacturaEnvio($this->codfac, $this->cclave, time(),$linfirmar,$pdff,''.$a->RespuestaRecepcionComprobante->estado, $this->mail);
    //enviar correo con la factura recibida
     $this->enviarCorreoFactura($linfirmar,$pdff);
// validar si fue recibida o devuelta
}else
    print_r($sriRecepcionComprobantesOfflineServiceValidar->getLastError());
$a=$sriRecepcionComprobantesOfflineServiceValidar->getLastError();
}   
            
        }
        
    
        
}
    
    
    public function clave($codegr){
            
	$codpago2=str_pad($codegr, 8, "0", STR_PAD_LEFT); 	
	
	$creado=time();
        //crear una nueva factura
        // $conexion = new connex();
        //validar si existe la clave
          if( $consulta = $this->consulta('Begin; insert into "RETENCION" (codegr,creado) values ('.$codegr.','.$creado.')')){
              $consulta = $this->consulta('select * from "RETENCION" where codegr='.$codegr.' and  creado= '.$creado.';');
              $r= pg_fetch_assoc($consulta);
              $codfac=str_pad($r['codret'], 9, "0", STR_PAD_LEFT); 
              $this->codfac=$r['codret'];
              $consulta = $this->consulta('commit;');
          }else{
              return false;
          }
        //el de 9 digitos hay que cambiar y crear 
        
	//$clave="<claveAcceso>";
	$clave='';
	$clave.=date("dmY");//Fecha de Emisi�n ddmmaaaa
	$clave.="01"; //tabla 3
	$clave.=$this->ruc; //ruc
	$clave.=$this->ambiente; //ambiente 1 pruebas 2 produccion
	$clave.="001002" ; //numero serial de las facturas de 6
	$clave.=$codfac ; //Nmero del Comprobante (secuencial)  de 9
	$clave.=$codpago2;//digo Numrico  de 8
	$clave.=$this->tipoEmision;//tipo de emision 1
	//aplicar el modulo11 para verificar factor de chequeo ponderado 2
	
	
	//$clave.="";//D�gito Verificador (m�dulo 11
	
	//$clave.="</claveAcceso>";
	//echo $clave."<br>";
	$mod11="234567";$m=0;$acu=0;
	for($i=47;$i>=0;$i--){
		$v=$clave[$i]*$mod11[$m];
		$acu+=$v;
		//echo "<br>".$i.": ".$clave[$i]." x ".$mod11[$m]."=".$v;
		$m++;
		if($m>5)$m=0;
	}
	//echo "<br> Total: ".$acu;
	$dig= $acu%11 ;
	$dig= 11-$dig;
	if($dig==11)$dig=0;
	if($dig==10)$dig=1;
	//echo "<br>Digito ".$dig;
	$clave.=$dig;
	//$clave="<claveAcceso>".$clave."</claveAcceso>";
	echo "<br>".$clave."<br>";
        
	return $clave;
	
	
}
    
    
    
    
public function crearEgreso($montoegr, $encargadoegr,$detalegr,$codusu,$codperiodo,$codcuedebe,$codcuehaber,$fecegr,$ncheque,$negreso){
	
	$conexion = new connex();
	
		$consulta = $conexion->consulta('begin; SELECT max(codegr) FROM "EGRESO"');
		$row = $conexion->row($consulta);
		$codegr=$row['max'] + 1;
		if($ncheque=='')
			$ncheque=0;	
		if($negreso=='')
			$negreso=0;	

		if($consulta = $conexion->consulta("INSERT INTO \"EGRESO\"(montoegr, fecegr, encargadoegr, estegr, codperiodo,detalegr,codcuedebe,codcuehaber,ncheque,negreso)
    VALUES (".$montoegr.", '".$fecegr."', '".$encargadoegr."', '1', ".$codperiodo.",'".$detalegr."',".$codcuedebe.",".$codcuehaber.",".$ncheque.",".$negreso.");
    ")){echo "Egreso Creado";}else{echo "Error al crear";}
                $consulta = $conexion->consulta("
    INSERT INTO \"AUDITORIA_COLECT\"(fecaudi, codusu, tabla, codcambio, montonuevo)
   			 		VALUES ('".date("d-m-Y")."',".$codusu." , 'EGRESO', ".$codegr.",".$montoegr.");
   			 		commit;
   			 		");
	
	return $codegr;
}
    public function mostrarRol($username)
{
		$conexion = new connex();
			$consulta = $conexion->consulta('SELECT rolusuario, codusu,codrol FROM "USUARIO"  WHERE username=\''.$username.'\';');
			return $consulta;
}
    public function mostrarEgresos($detalleaspi,$param)
{
		$conexion = new connex();
			$consulta = $conexion->consulta('SELECT * FROM "EGRESO" where '.$param.' ilike \''.$detalleaspi.'%\' and estegr=\'1\' order by fecegr,codegr ');
			return $consulta;
}
    public function devolverEgreso($codegr,$codusu)
{
		$conexion = new connex();
			$consulta = $conexion->consulta("begin; 
			UPDATE \"EGRESO\" SET
			estegr = '0' 
			WHERE codegr=".$codegr.";
			 INSERT INTO \"AUDITORIA_COLECT\"(fecaudi, codusu, tabla, codcambio, montonuevo)
   			 		VALUES ('".date("d-m-Y")."',".$codusu." , 'EGRESO', ".$codegr.",0);
   			 		commit;");
			return $consulta;
}
    public function cuentas()
{
		$conexion = new connex();
			$consulta = $conexion->consulta('SELECT * FROM "CUENTA" order by nivel1cue,nivel2cue,nivel3cue,nivel4cue,nivel5cue ;');
			return $consulta;
}

//mostrar ergresos con cuenta general para cambiar y fecha con detalle
    public function mostrarEgresosCuentas($fecha,$detalle)
{
		$conexion = new connex();
			$consulta = $conexion->consulta('SELECT * FROM "EGRESO" 
where fecegr >=\''.$fecha.'\' and (detalegr like \''.$detalle.'%\' or encargadoegr like \''.$detalle.'%\')  order by fecegr,codegr ');
			return $consulta;
}
//para modificar los egresos
    public function modificarEgreso($codegr,$codcuedebe,$codcuehaber,$fecegr,$montoegr,$encargadoegr,$detalegr,$negreso,$ncheque)
{
		$conexion = new connex();
			$consulta = $conexion->consulta("begin; 
			UPDATE \"EGRESO\" SET
			fecegr='".$fecegr."', montoegr=".$montoegr.",
			codcuedebe = ".$codcuedebe.", codcuehaber=".$codcuehaber." ,encargadoegr='".$encargadoegr."',
			detalegr='".$detalegr."',negreso=".$negreso.", ncheque=".$ncheque."
			WHERE codegr=".$codegr.";
			 		commit;");
			return $consulta;
}
    public function mostrarEgreso($codegr)
{
		$conexion = new connex();
			$consulta = $conexion->consulta("begin; 
			UPDATE \"EGRESO\" SET
			codcuedebe = ".$codcuedebe.", codcuehaber=".$codcuehaber." 
			WHERE codegr=".$codegr.";
			 		commit;");
			return $consulta;
}
//mostrarFacturasMes($fini,$ffin);
  public function mostrarFacturasMes($fini,$ffin)
{
		$conexion = new connex();
			$consulta = $conexion->consulta('select "PAGO".codpag,montodetpago,detaling,repralu,rucfam,fecpago,codfactura from "PAGO","FAMILIA","ALUMNO","INGRESO","DETALLE_PAGO"
where "PAGO".codfam="FAMILIA".codfam and 
"DETALLE_PAGO".codpago="PAGO".codpag and "INGRESO".coding="DETALLE_PAGO".coding 
and "ALUMNO".codalu="INGRESO".codalu and fecpago<=\''.$ffin.'\' and fecpago>=\''.$fini.'\'
order by codpag ');
			return $consulta;
}

public function generarRetencion($codegr){
		
            // $pdf=new FPDF();
           $pdf=new PDF_Code128();
        $pdf->AddPage('P','A4');
	$pdf->SetAutoPageBreak('false' , 1);
	$pdf->SetFont('Arial','',9);
       // $pdf->Line(10,7,290,7);
	//	$pdf->Line(10,27,290,27);
		$pdf->Image("logo.jpg",10,7.5,25,25);
                
                           
            $xml = new DOMDocument();
		$xml->load($this->xsdstring);
		$this->dirMatriz=utf8_encode("LAUREANO CRUZ OE9-35 Y JULIAN ESTRELLA");
		//$clave=$this->clave($codpago);
                $clave=$this->cclave;
                $this->transaccion=str_pad($this->codfac, 9, "0", STR_PAD_LEFT);
		//ingresar la informacion inicial
		$x = $xml->documentElement;
		$a=$x->getElementsByTagName("infoTributaria");
		
		//llenar los datos de informacin tributaria
		//$transaccion=str_pad($codpago, 9, "0", STR_PAD_LEFT);
		$a->item(0)->getElementsByTagName("claveAcceso")->item(0)->nodeValue=$clave;
		$a->item(0)->getElementsByTagName("ambiente")->item(0)->nodeValue=$this->ambiente;
		$a->item(0)->getElementsByTagName("tipoEmision")->item(0)->nodeValue=$this->tipoEmision;
		$a->item(0)->getElementsByTagName("razonSocial")->item(0)->nodeValue=$this->razonSocial;
		$a->item(0)->getElementsByTagName("nombreComercial")->item(0)->nodeValue=$this->nombreComercial;
		
		$a->item(0)->getElementsByTagName("ruc")->item(0)->nodeValue=$this->ruc;
		$a->item(0)->getElementsByTagName("codDoc")->item(0)->nodeValue=$this->codDoc;
		$a->item(0)->getElementsByTagName("estab")->item(0)->nodeValue=$this->estab;
		$a->item(0)->getElementsByTagName("ptoEmi")->item(0)->nodeValue=$this->ptoEmi;
		$a->item(0)->getElementsByTagName("secuencial")->item(0)->nodeValue=$this->transaccion;
		$a->item(0)->getElementsByTagName("dirMatriz")->item(0)->nodeValue=($this->dirMatriz);
//pdf agregar informacion tributaria
                $sal=5;
                $pdf->Cell(100,$sal,"",0,0,"L");$pdf->Cell(100,$sal,"R.U.C.: ".$this->ruc,0,1,"L");
                $pdf->Cell(100,$sal,"",0,0,"L");$pdf->Cell(100,$sal,"RETENCION",0,1,"L");
                $pdf->Cell(100,$sal,"",0,0,"L");$pdf->Cell(100,$sal,"No. ".$this->estab."-".$this->ptoEmi."-".($this->transaccion),0,1,"L");
                $pdf->Cell(100,$sal,"",0,0,"L");   $pdf->Cell(100,$sal,"N�MERO DE AUTORIZACI�N",0,1,"L");
                
                $pdf->Cell(100,$sal,"",0,0,"L");$pdf->Cell(100,$sal,$this->cclave,0,1,"L");
                $pdf->Cell(100,$sal,$this->razonSocial,0,0,"L");
                $pdf->SetFont('Arial','',8);
                $pdf->Cell(50,$sal,"FECHA Y HORA DE AUTORIZACI�N:",0,0,"L");
                 $pdf->SetFont('Arial','',9);
                $pdf->Cell(50,$sal, date("d/m/Y h:i:s"),0,1,"L");
                $pdf->Cell(100,$sal,$this->nombreComercial,0,0,"L");$pdf->Cell(50,$sal,"AMBIENTE:",0,0,"L");
                IF($this->ambiente==2)
                    $pdf->Cell(50,$sal,"PRODUCCION",0,1,"L");
                else
                    $pdf->Cell(50,$sal,"PRUEBAS",0,1,"L");
                    $pdf->SetFont('Arial','',8);
                $pdf->Cell(100,$sal,"Direcci�n Matriz: ".$this->dirMatriz,0,0,"L");
                   $pdf->SetFont('Arial','',9);
                $pdf->Cell(50,$sal,"EMISI�N:",0,0,"L");$pdf->Cell(50,$sal, ("NORMAL"),0,1,"L");
                  $pdf->SetFont('Arial','',8);
                $pdf->Cell(100,$sal,"Direcci�n Sucursal: ".$this->dirMatriz,0,0,"L");
                   $pdf->SetFont('Arial','',9);
                $pdf->Cell(100,$sal,"CLAVE DE ACCESO",0,1,"L");
                
                 $pdf->Ln($sal*2);
                
                 $pdf->Cell(100,$sal,"OBLIGADO A LLEVAR            SI",0,0,"C");$pdf->Cell(100,$sal,$this->cclave,0,1,"C");
    		$fecha=date("d-m-Y");
		
		$consulta = $this->consulta('SELECT *  FROM public."RETENCION" where codegr='.$codegr.' ;');
		
		if(!$reg=pg_fetch_assoc($consulta))
		{ echo "El pago no requiere factura electronica";return false;}
                //validacion de datos previa al envio del archivo
                //crear el codigo de barras
                //
         $reg=$_REQUEST;
                $code=$this->cclave;
                $pdf->Code128(110,55,$code,95,10);
                
		//rellenar el xml con los datos del pago
                //$x = $xml->documentElement;
		$a=$x->getElementsByTagName("infoCompRetencion");
                if($reg['rucfam']=='')$reg['rucfam']=$reg['repced'];
                
                
		if(strlen($reg['rucfam'])==10)$tipco='05';
		if(strlen($reg['rucfam'])==13)$tipco='04';
                
                if(strlen($reg['rucfam'])==0){ echo "Cedula incorrecta";return false;}

                    
		//llenar los datos de informacin tributaria
		if($reg['nombrefam']=='')$reg['nombrefam']=$reg['repralu'];
		$a->item(0)->getElementsByTagName("fechaEmision")->item(0)->nodeValue=date("d/m/Y",strtotime($reg['fecemi']));
		$a->item(0)->getElementsByTagName("dirEstablecimiento")->item(0)->nodeValue=($this->dirMatriz);
		//$a->item(0)->getElementsByTagName("contribuyenteEspecial")->item(0)->nodeValue="";
		$a->item(0)->getElementsByTagName("obligadoContabilidad")->item(0)->nodeValue="SI";
		$a->item(0)->getElementsByTagName("tipoIdentificacionSujetoRetenido")->item(0)->nodeValue=$tipco;
		$a->item(0)->getElementsByTagName("razonSocialSujetoRetenido")->item(0)->nodeValue=utf8_encode($reg['nombrefam']);
		$a->item(0)->getElementsByTagName("identificacionSujetoRetenido")->item(0)->nodeValue=$reg['rucfam'];
		$a->item(0)->getElementsByTagName("dirEstablecimiento")->item(0)->nodeValue=utf8_encode($reg['dir1fam']);
                $a->item(0)->getElementsByTagName("periodoFiscal")->item(0)->nodeValue=utf8_encode($reg['periodoFiscal']);
//para llenar los campos adicionales
                $de=$x->getElementsByTagName("infoAdicional");
               $de->item(0)->getElementsByTagName("campoAdicional")->item(1)->nodeValue=$reg['emailalu'];
               $de->item(0)->getElementsByTagName("campoAdicional")->item(0)->nodeValue=utf8_encode($reg['nombrefam']);
               $de->item(0)->getElementsByTagName("campoAdicional")->item(2)->nodeValue='Obser';
//llenar pdf factura
                $this->nomrep=$reg['nombrefam'];
                
                if($reg['emailalu']==''){
                   $this->nomrep= 'colecturia.uers@rincondelsaber.com';
                }
                $this->mail=$reg['emailalu'];
                
                $pdf->Cell(55,$sal,"Raz�n Social / Nombres y Apellidos",0,0,"L");$pdf->Cell(145,$sal,utf8_encode($reg['nombrefam']),0,1,"L");
                
                $pdf->Cell(55,$sal,"Identificaci�n",0,0,"L");$pdf->Cell(150,$sal,$reg['rucfam'],0,1,"L");
               $pdf->Cell(55,$sal,"Fecha",0,0,"L");$pdf->Cell(50,$sal,$fecha,0,1,"L");
                
                $pdf->Cell(55,$sal,"Direccion:",0,0,"L");$pdf->Cell(150,$sal,$reg['dir1fam'],0,1,"L");
                
                
                //debo mandar a los dos impuestos
                         
                $ims=$x->getElementsByTagName("impuestos");
                //// para el iva
                $iva=$x->getElementsByTagName("impuesto");
                 $iva->item(0)->getElementsByTagName("codigo")->item(0)->nodeValue=$_REQUEST['iva'];
                $iva->item(0)->getElementsByTagName("codigoRetencion")->item(0)->nodeValue=$_REQUEST['codigoRetencion'];
                $iva->item(0)->getElementsByTagName("baseImponible")->item(0)->nodeValue=$_REQUEST['baseImponible'];
               $por=0;
                switch ($_REQUEST['codigoRetencion']){
                    case 9:{ $por=10.00;  break;}
                    case 10:{ $por=20.00;  break;}
                    case 1:{ $por=30.00;  break;}
                    case 11:{ $por=50.00;  break;}
                    case 2:{ $por=70.00;  break;}
                    case 3:{ $por=100.00;  break;}
                }
                $iva->item(0)->getElementsByTagName("porcentajeRetener")->item(0)->nodeValue=$por;
                $iva->item(0)->getElementsByTagName("valorRetenido")->item(0)->nodeValue=$_REQUEST['valorRetenido'];
                $iva->item(0)->getElementsByTagName("codDocSustento")->item(0)->nodeValue='01';
              $iva->item(0)->getElementsByTagName("numDocSustento")->item(0)->nodeValue=$_REQUEST['numDocSustento'];
                   $iva->item(0)->getElementsByTagName("fechaEmisionDocSustento")->item(0)->nodeValue=$_REQUEST['fecemi'];
              //------------------para la renta
              
                       $renta=$x->getElementsByTagName("impuesto");
                 $renta->item(1)->getElementsByTagName("codigo")->item(0)->nodeValue=$_REQUEST['renta'];
                $renta->item(1)->getElementsByTagName("codigoRetencion")->item(0)->nodeValue=$_REQUEST['codrenta'];
                $renta->item(1)->getElementsByTagName("baseImponible")->item(0)->nodeValue=$_REQUEST['baseImponible'];
         
                $renta->item(1)->getElementsByTagName("porcentajeRetener")->item(0)->nodeValue=$_REQUEST['porrenta'];
                
                $vrenta= number_format($_REQUEST['baseImponible']*$_REQUEST['porrenta']/100,2);
                
                
                $renta->item(1)->getElementsByTagName("valorRetenido")->item(0)->nodeValue=$vrenta;
                $renta->item(1)->getElementsByTagName("codDocSustento")->item(0)->nodeValue='01';
              $renta->item(1)->getElementsByTagName("numDocSustento")->item(0)->nodeValue=$_REQUEST['numDocSustento'];
              $renta->item(1)->getElementsByTagName("fechaEmisionDocSustento")->item(0)->nodeValue=$_REQUEST['fecemi'];
          
	      //agregar el campo de informacion adicional en el formato para solo asignar directo
                
                  
                 $pdf->Output("pdf/ret".$this->cclave.".pdf",'F'); 
                 
                 
                 
                 
                 
//creamos en la tabla factura con la clave y los tiempos para actualizar
                
               // $this->crearFactura($clave,$codpago);
                
                    //crear el pdf de la factura
       
	//encabezado

       
                
                
                
		return $xml;
		
		
	}
*/	
}

	

