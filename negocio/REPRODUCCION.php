<?php
require_once("ANIMALES.php");
/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of REPRODUCCION
 *
 * @author leand
 */
class REPRODUCCION extends ANIMALES {
    //put your code here
    public $tipres=array('Por revisar','Vacia','Aborto','Parto','Confirmado');
    public $tiprep=array('Pendiente','Inseminación','Monta','Celo');
    public $estsec=array('Pendiente','Producción','Secada');
       public function mostrarInicio(){
       echo '<td>  <form><center>
        <table border =1>
            <tr><th>Buscar por nombre: </th><tH><BR><input type="text" placeholder="Lanita" name="nombre">
                <button type="submit" name="bttbusani"><IMG src=../img/buscar.jpg><br>BUSCAR ANIMAL</button></td></tr>
<tr><td><br>          
<tr><th >Mostrar por fechas:<th>
            <center>Inicio:<input type=date name=fini value="'.date("Y-m").'-01"> Final:<input type=date name=ffin  value="'.date("Y-m-d").'" >
            <br>  <br> <button  type="submit" name="bttlista" > <IMG src=../img/notarojo.png> <BR>LISTAR </button> 
             <button type="submit" value="Imprimir" name="bttimpani" > <IMG src=../img/imprimir.jpg> <BR> IMPRIMIR </button>
              <button type="submit" value="Tabla" name="btttabani" > <IMG src=../img/formu.png width="50" height="50"> <BR> TABLA </button>
             </center></th></tr>
        
        </table></center></form></table>';
         
     }
    public function  listarAnimalesReproduccion($nombre){
   
   $animales= $this->listarAnimales(1,$nombre);
 
    echo '  <form> <center>
        <table border="1" class=table style="width:50%">
            <tr>
                <th>Nombre</th><th>Arete</th><th>Estado</th><th>Especie</th><th>Acción</th>
            </tr>';
    
    foreach ($animales as $a){
      //   $fotos= $ani->mostrarFotosAnimal($a['id'],300);
        if($a['sexani']==1 and $a['idhac']==$_SESSION['idhac'])
        echo '<tr>
                <td><h2>'.$a['nombre'].'</h2></td><td>'.$a['arete'].'</td><td>'.$this->esthac[$a['esthac']].'</td><td>'.$this->espani[$a['espani']].' </td><td><button name=bttani value='.$a['id'].'> <img src=../img/modif.jpg  > <br>Seleccionar</button></td>
                    
            </tr>';
    }
    
    echo '</table>
        
    </center></form>';
}
   
public function mostrarCrear($id){
    // Suponiendo que tienes funciones similares a las de ANIMALES para listar las opciones
   
   $opanimales=$this->listarAnimales(3);
    $opanipadres=$this->listarAnimalesPadres(3);
    
    $amadre=$this->mostrarAnimal($id);
    
    echo '<button onclick="ocultarMostrar(\'nuevopr\')" class="accordion-button collapsed" type="button"><b>Mostrar crear Proceso de Reproducción</b></button>
        <div id=nuevopr style="display:none;"><center> 
        
        <form >
        <h2>Nuevo Proceso de Reproducción</h2>
        
        <table border="1">
            <tr>
                <th>Vaca:</th>
                <td>'.$amadre['nombre'].' <input type=hidden name=idmadre value='.$id.'>  </td>
            </tr>
   <tr>
                <th>Tipo de Reproducción:</th>
                <td>
                    <select name="tiprep">
                        <option value="0">'.$this->tiprep[0].'</option>
                        <option value="1">'.$this->tiprep[1].'</option>
                        <option value="2">'.$this->tiprep[2].'</option>
                         <option value="3">'.$this->tiprep[3].'</option>    
                    </select>
                </td>
            </tr>            


            <tr>
                <th>Fecha del Monta / inseminación:</th>
                <td><input type="date" name="fecpro"></td>
            </tr>
             <tr>
                <th>Fecha de Revisión:</th>
                <td><input type="date" name="fecrev"></td>
            </tr>

  <tr>
                <th>Tipo de Resultado:</th>
                <td>
                    <select name="tipres">
                        <option value="0">'.$this->tipres[0].'</option>
                        <option value="1">'.$this->tipres[1].'</option>
                        <option value="2">'.$this->tipres[2].'</option>
                        <option value="3">'.$this->tipres[3].'</option>
                        <option value="4">'.$this->tipres[4].'</option>  
                         <option value="5">'.$this->tipres[5].'</option>      
                    </select>
                </td>
            </tr>


            <tr>
                <th>Fecha del Vacia / Aborto / Parto:</th>
                <td><input type="date" name="fecres"></td>
            </tr>
            <tr>
                <th>Toro padre:</th>
                <td><select name="idpadre">'.$opanipadres.'</select></td>
            </tr>
            <tr>
                <th>Cría:</th>
                 <td><select name="idcria"><option value=0>En proceso</option>
                 '.$opanimales.'</select></td>
            </tr>
            <tr>
                <th>Detalle:</th>
                <td><input type="text" placeholder="Detalle de la Reproducción" name="detrep" style="width:100%;"></td>
            </tr>
         
          
            <tr>
                <th>Observaciones:</th>
                <td><textarea placeholder="Observaciones" name="obsrep"></textarea></td>
            </tr>
            <tr>
                <th colspan="2"><center><button type="submit" name="bttcrear"><img src="../img/anadir.png" alt=""/> <br>CREAR REPRODUCCION</button></center></th>
            </tr>
        </table>
        </form>
    </center></div>';
    
    echo "<center><h2>Reproducciones anteriores de ".$amadre['nombre']."</h2>";
    //Reproducciones Anteriores
    echo "<table border='1' class='table table-striped '>";
echo "<tr><th>Fecha Proceso</th><th>Fecha Resultado</th><th>Fecha Revisión</th><th>Tipo Resultado</th><th>Cria</th><th>Tipo Proceso</th><th>Padre</th><th>Acción</th></tr>"; // Puedes agregar más encabezados según los campos que quieras mostrar
    
$pConsulta=$this->mostrarReproduccionAnimal($id);
while($reg = $this->row($pConsulta)){
        
  // $opanimalesm=$this->listarAnimales(3,'',$reg['idmadre']);
   $opanimalesp=$this->listarAnimales(3,'',$reg['idpadre']);
   $opanimalesc=$this->listarAnimales(3,'',$reg['idcria']);
   $r='';     
   //rojo para valores faltantes
   
   
   
   if($reg['idcria']==0)$r='<option value=0>En proceso / no existente</option>';
            echo "<form><tr>";
    echo '<td><input type="date" value="'.$reg['fecpro'].'" name="fecpro"></td>';
    echo '<td><input type="date" value="'.$reg['fecres'].'" name="fecres"></td>';
     echo '<td><input type="date" value="'.$reg['fecrev'].'" name="fecrev">
         <br>Fecha secado:<br><input type="date" value="'.$reg['fecsec'].'" name="fecsec">
         </td>';
    echo '<td><select name="tipres">
                        <option value="'.$reg['tipres'].'">'.$this->tipres[$reg['tipres']].'</option>
                        <option value="0">'.$this->tipres[0].'</option>
                        <option value="1">'.$this->tipres[1].'</option>
                        <option value="2">'.$this->tipres[2].'</option>
                        <option value="3">'.$this->tipres[3].'</option>
                        <option value="4">'.$this->tipres[4].'</option>  
                        <option value="5">'.$this->tipres[5].'</option>      
</select>
          <td><select name="idcria" style="width:150px;">'.$r.' '.$opanimalesc.'</select></td>                    
            <td><select name="tiprep">
                        <option value="'.$reg['tiprep'].'">'.$this->tiprep[$reg['tiprep']].'</option>
                        <option value="0">'.$this->tiprep[0].'</option>
                        <option value="1">'.$this->tiprep[1].'</option>
                        <option value="2">'.$this->tiprep[2].'</option>
                         <option value="3">'.$this->tiprep[3].'</option>    
                       </select>                
                 <td><select name="idpadre" style="width:100px;">'.$opanimalesp.'</select></td>           
                  <td>
                   <button name=bttmodrep value="'.$reg['idrep'].'"> <img src=../img/modif.jpg  > <br>Modificar</button>    
                         <button name=btterepani value="'.$reg['idrep'].'" onclick="javascript: return confirm(\'Esta seguro de Eliminar la reproducción del Animal \');"><img src=../img/cancelar.jpg  > <br>Eliminar</button>
                  </td>
                     </tr>  <input type=hidden name=idani value="'.$reg['idmadre'].'"> 
                         <input type=hidden name=idrep value="'.$reg['idrep'].'"> 
                        
                             </form>
                            ';
    
  
        
        
    }
    
   echo '</table></center>';  
}


public function mostrarReproduccionAnimal($id){
       $query = 'SELECT * FROM "REPRODUCCION" where idmadre='.$id.' ORDER BY fecpro DESC;';
        $pConsulta = $this->consulta($query);
        return $pConsulta;
    
}

public function crearReproduccion($datos){
    
    // Suponiendo que no necesitas validar nuevos valores como en el caso de raza y proveedor en la función anterior

    // Validaciones básicas para asegurarte de que los datos existen
    if(!isset($datos['fecpro']) or $datos['fecpro']=='') $datos['fecpro'] = '1900-01-01';
    //igual estos dos campos calcular a fecrev 60 días más y fecres 
    
    // Calcular fecrev y fecres si no están definidos
if (!isset($datos['fecrev']) || $datos['fecrev'] == '' || $datos['fecrev'] == '1900-01-01') {
    $datos['fecrev'] = date('Y-m-d', strtotime($datos['fecpro'] . ' +60 days'));
}

if (!isset($datos['fecres']) || $datos['fecres'] == '' || $datos['fecres'] == '1900-01-01') {
    $datos['fecres'] = date('Y-m-d', strtotime($datos['fecpro'] . ' +280 days'));
}

if (!isset($datos['fecsec']) || $datos['fecsec'] == '' || $datos['fecsec'] == '1900-01-01') {
    $datos['fecsec'] = date('Y-m-d', strtotime($datos['fecpro'] . ' +520 days'));
}


    if(!isset($datos['idpadre'])) $datos['idpadre'] = 0;
    if(!isset($datos['idcria'])) $datos['idcria'] = 0;
    if(!isset($datos['detrep'])) $datos['detrep'] = '';
    if(!isset($datos['tiprep'])) $datos['tiprep'] = 0;
    if(!isset($datos['tipres'])) $datos['tipres'] = 0;
    if(!isset($datos['obsrep'])) $datos['obsrep'] = '';

    $query = 'INSERT INTO "REPRODUCCION" (idmadre, fecpro, fecres, idpadre, idcria, detrep, tiprep, tipres, obsrep,fecrev,fecsec) 
    VALUES ('.$datos['idmadre'].', \''.$datos['fecpro'].'\', \''.$datos['fecres'].'\', '.$datos['idpadre'].', '.$datos['idcria'].', \''.addslashes($datos['detrep']).'\', '.$datos['tiprep'].', '.$datos['tipres'].', \''.addslashes($datos['obsrep']).'\', \''.$datos['fecrev'].'\', \''.$datos['fecsec'].'\');';

    if($con = $this->consulta($query)){
        $query = 'SELECT * FROM "REPRODUCCION" ORDER BY idrep DESC;';
        $pConsulta = $this->consulta($query);
        if($reg = $this->row($pConsulta)){
            return $reg['idrep'];
        }
        echo "Proceso de Reproducción guardado exitosamente";
        return true;
    } else {
        echo "Error al ingresar a la BDD";
        return false;
    }
}
function modificarReproduccion($datos) {
    // Validaciones básicas para asegurarte de que los datos existen
    if(!isset($datos['fecpro']) or $datos['fecpro']=='') $datos['fecpro'] = '1900-01-01';
    if(!isset($datos['fecres']) or $datos['fecres']=='') $datos['fecres'] = '1900-01-01';
    if(!isset($datos['fecrev']) or $datos['fecrev']=='') $datos['fecrev'] = '1900-01-01';
    if(!isset($datos['idpadre'])) $datos['idpadre'] = 0;
    if(!isset($datos['idcria'])) $datos['idcria'] = 0;
    if(!isset($datos['detrep'])) $datos['detrep'] = '';
    if(!isset($datos['tiprep'])) $datos['tiprep'] = 0;
    if(!isset($datos['tipres'])) $datos['tipres'] = 0;
    if(!isset($datos['obsrep'])) $datos['obsrep'] = '';

    // Asegúrate de que el ID de reproducción esté establecido para poder modificar el registro
    if(!isset($datos['idrep'])) {
        echo "ID de reproducción no proporcionado";
        return false;
    }

    $query = 'UPDATE "REPRODUCCION" SET 
        idmadre = '.$datos['idmadre'].',
        fecpro = \''.$datos['fecpro'].'\',
        fecres = \''.$datos['fecres'].'\',
        idpadre = '.$datos['idpadre'].',
        idcria = '.$datos['idcria'].',
        detrep = \''.addslashes($datos['detrep']).'\',
        tiprep = '.$datos['tiprep'].',
        tipres = '.$datos['tipres'].',
        obsrep = \''.addslashes($datos['obsrep']).'\',
        fecrev = \''.$datos['fecrev'].'\'
    WHERE idrep = '.$datos['idrep'].';';

    if($con = $this->consulta($query)){
        echo "Proceso de Reproducción modificado exitosamente";
        return true;
    } else {
        echo "Error al modificar en la BDD";
        return false;
    }
}
function modificarReproduccionTabla($datos) {
    // Validaciones básicas para asegurarte de que los datos existen
    if(!isset($datos['fecpro']) or $datos['fecpro']=='') $datos['fecpro'] = '1900-01-01';
    if(!isset($datos['fecres']) or $datos['fecres']=='') $datos['fecres'] = '1900-01-01';
    if(!isset($datos['fecrev']) or $datos['fecrev']=='') $datos['fecrev'] = '1900-01-01';
    if(!isset($datos['idpadre'])) $datos['idpadre'] = 0;
    if(!isset($datos['idcria'])) $datos['idcria'] = 0;
    if(!isset($datos['detrep'])) $datos['detrep'] = '';
    if(!isset($datos['tiprep'])) $datos['tiprep'] = 0;
    if(!isset($datos['tipres'])) $datos['tipres'] = 0;
    if(!isset($datos['obsrep'])) $datos['obsrep'] = '';

    // Asegúrate de que el ID de reproducción esté establecido para poder modificar el registro
    if(!isset($datos['idrep'])) {
        echo "ID de reproducción no proporcionado";
        return false;
    }

    $query = 'UPDATE "REPRODUCCION" SET 
        fecpro = \''.$datos['fecpro'].'\',
        fecres = \''.$datos['fecres'].'\',
         fecrev = \''.$datos['fecrev'].'\',
        idpadre = '.$datos['idpadre'].',
        idcria = '.$datos['idcria'].',
        tiprep = '.$datos['tiprep'].',
        tipres = '.$datos['tipres'].'
    WHERE idrep = '.$datos['idrep'].';';

    if($con = $this->consulta($query)){
        echo "Proceso de Reproducción modificado exitosamente";
        return true;
    } else {
        echo "Error al modificar en la BDD";
        return false;
    }
}
function eliminarReproduccionTabla($datos) {
   

    $query = 'DELETE from "REPRODUCCION" 
    WHERE idrep = '.$datos.';';

    if($con = $this->consulta($query)){
        echo "Proceso de Reproducción eliminado exitosamente";
        return true;
    } else {
        echo "Error al modificar en la BDD";
        return false;
    }
}

public function mostraTablaReproduccionant($anio){
    $sql='SELECT
    a.nombre AS nombre_animal,estrep,
    EXTRACT(MONTH FROM r.fecpro) AS mes,
    r.tiprep AS tipo_rep,
    r.tipres AS tipo_res
FROM "ANIMALES" a
LEFT JOIN "REPRODUCCION" r ON a.id = r.idmadre
WHERE
    a.sexani = 1
    AND a.esthac = 1
    AND a.espani = 1
    AND a.estrep > 2  and idhac='.$_SESSION['idhac'].'
    AND EXTRACT(YEAR FROM r.fecpro) ='.$anio.' order by estrep,a.nombre';
    
    $stmt=$this->consulta($sql);
    
    $resultados = pg_fetch_all($stmt);
  
    $tabla = array();

// Inicializar la tabla con los nombres de los meses como encabezados
$tabla[] = array('Animal', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');

// Inicializar un arreglo para el seguimiento de los datos por animal
$datos_animal = array();

// Procesar los resultados y llenar la tabla de datos
foreach ($resultados as $fila) {
    $nombre_animal = $fila['nombre_animal'].'<br>'.$this->estrep[$fila['estrep']];
    $mes = $fila['mes'];
    $tipo_rep = $fila['tipo_rep'];
    $tipo_res = $fila['tipo_res'];
    
    // Agregar el nombre del animal si es la primera vez que se encuentra
    if (!isset($datos_animal[$nombre_animal])) {
        $datos_animal[$nombre_animal] = array_fill(1, 12, ''); // Inicializar con celdas vacías para los 12 meses
    }
    
    // Agregar el proceso de reproducción al mes correspondiente
   // $datos_animal[$nombre_animal][$mes] = ($tipo_rep === 'M' ? 'M-' . $tipo_res : 'P-' . $tipo_res);
   /*
    if($tipo_res==0){
        $datos_animal[$nombre_animal][$mes] = '<div style="background-color: red;color: white;">' .$this->tiprep[$tipo_rep].' - '.$this->tipres[$tipo_res]. '</div>';
    }else{
        $datos_animal[$nombre_animal][$mes] = '<div>'.$this->tiprep[$tipo_rep].' - '.$this->tipres[$tipo_res].'</div>';
    }
   */ 
  switch ($tipo_res) {
    case 0:
        $color_fondo = 'red';
        $color_texto = 'white';
        break;
    case 1:
        $color_fondo = 'yellow';
        $color_texto = 'black';
        break;
    case 2:
        $color_fondo = 'orange';
        $color_texto = 'black';
        break;
    case 3:
        $color_fondo = 'green';
        $color_texto = 'white';
        break;
    case 4:
        $color_fondo = 'blue';
        $color_texto = 'white';
        break;
    default:
        $color_fondo = 'transparent';
        $color_texto = 'black';
        break;
}

$contenido_div = '<div style="background-color: ' . $color_fondo . '; color: ' . $color_texto . ';">' .
    $this->tiprep[$tipo_rep] . ' - ' . $this->tipres[$tipo_res] . '</div>';

$datos_animal[$nombre_animal][$mes] = $contenido_div;  
    
    
    
    
    
    
    
}

// Completar la tabla con los datos de los animales
foreach ($datos_animal as $nombre_animal => $datos_meses) {
    $fila_tabla = array_merge(array($nombre_animal), $datos_meses);
    $tabla[] = $fila_tabla;
}
  
   echo '<center><table border="1" class="table table-bordered table-striped " >';
foreach ($tabla as $fila) {
    echo '<tr>';
    foreach ($fila as $indice => $celda) {
        if ($indice === 0) {
            echo '<th>' . $celda . '</th>'; // La primera celda como <th>
        } else {
           echo '<td>' . $celda . '</td>'; // Las celdas restantes como <td>
             // echo $celda ;
        }
    }
    echo '</tr>';
}
echo '</table></center>';


}
public function mostraTablaReproduccion($anio, $mes) {
    $fecha_actual = new DateTime();

    $sql = "SELECT
                a.nombre AS nombre_animal,
                a.arete AS numero_arete,
                a.fecnac AS fecha_nacimiento,
                EXTRACT(MONTH FROM r.fecpro) AS mes,
                EXTRACT(YEAR FROM r.fecpro) AS ano,
                r.fecpro AS fecha_monta,
                r.fecres AS fecha_resultado,
                r.fecrev AS fecha_revision,
                r.tiprep AS tipo_rep,
                r.tipres AS tipo_res,
                r.idrep AS idrep
            FROM \"ANIMALES\" a
            LEFT JOIN \"REPRODUCCION\" r ON a.id = r.idmadre
            WHERE
                a.sexani = 1 AND
                a.esthac = 1 AND
                a.espani = 1 AND
                idhac = {$_SESSION['idhac']} AND
                (
                    EXTRACT(YEAR FROM r.fecpro) = {$anio} 
                    OR EXTRACT(YEAR FROM r.fecres) = {$anio}
                    OR r.idrep IS NULL 
                    OR (r.fecpro IS NULL AND (DATE_PART('year', AGE(a.fecnac)) * 12 + DATE_PART('month', AGE(a.fecnac))) >= 15)
                )
            ORDER BY a.nombre";
//echo $sql;
    $stmt = $this->consulta($sql);
    $resultados = pg_fetch_all($stmt) ?: [];

    $tabla = array();
    $tabla[] = array('N°', 'Arete', 'Animal', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');

    $resumen_meses = array_fill(1, 12, ['Monta' => 0, 'Revisar' => 0, 'Parto' => 0, 'Aborto' => 0]);

    $datos_animal = array();
    $contador = 1;

    foreach ($resultados as $fila) {
        $nombre_animal = $fila['nombre_animal'];
        $arete = $fila['numero_arete'];
        $fecha_nacimiento = new DateTime($fila['fecha_nacimiento']);
        $fecha_monta = $fila['fecha_monta'] ? new DateTime($fila['fecha_monta']) : null;
        $fecha_resultado = $fila['fecha_resultado'] ? new DateTime($fila['fecha_resultado']) : null;
        $fecha_revision = $fila['fecha_revision'] ? new DateTime($fila['fecha_revision']) : null;
        $tipo_res = $fila['tipo_res'];
        $idrep = $fila['idrep'];

        $clave_animal = $arete . '|' . $nombre_animal;

        if (!isset($datos_animal[$clave_animal])) {
            $datos_animal[$clave_animal] = array_fill(1, 12, '');
        }

        // Identificar cuando cumplen 15 meses
        $fecha_15_meses = (clone $fecha_nacimiento)->modify('+15 months');
        if ($fecha_15_meses->format('Y') == $anio) {
            $indice_15_meses = $fecha_15_meses->format('n');
            $datos_animal[$clave_animal][$indice_15_meses] .= '<div style="background-color: gray; color: white;">15 meses</div>';
        }

        if ($tipo_res == 2 && $fecha_resultado && $fecha_resultado->format('Y') == $anio) {
            $indice_aborto = $fecha_resultado->format('n');
            $datos_animal[$clave_animal][$indice_aborto] .= '<div style="background-color: red; color: white;">Aborto - ' . $fecha_resultado->format('d') . '</div>';
            $resumen_meses[$indice_aborto]['Aborto']++;
        } else {
            if ($fecha_monta && $fecha_monta->format('Y') == $anio) {
                $indice_monta = $fecha_monta->format('n');
                $datos_animal[$clave_animal][$indice_monta] .= '<div style="background-color: orange; color: black;">Monta - ' . $fecha_monta->format('d') . '</div>';
                $resumen_meses[$indice_monta]['Monta']++;
            }
            if ($fecha_revision && $fecha_revision->format('Y') == $anio) {
                $indice_revision = $fecha_revision->format('n');
                $datos_animal[$clave_animal][$indice_revision] .= '<div style="background-color: yellow; color: black;">Revisar - ' . $fecha_revision->format('d') . '</div>';
                $resumen_meses[$indice_revision]['Revisar']++;
            }
            if ($fecha_resultado && $fecha_resultado->format('Y') == $anio) {
                $indice_resultado = $fecha_resultado->format('n');
                if ($fecha_resultado > $fecha_actual && $tipo_res != 3) {
                    $datos_animal[$clave_animal][$indice_resultado] .= '<div style="background-color: lightblue; color: black;">Parto Esperado - ' . $fecha_resultado->format('d') . '</div>';
                } elseif ($tipo_res == 3) {
                    $datos_animal[$clave_animal][$indice_resultado] .= '<div style="background-color: green; color: white;">Parto - ' . $fecha_resultado->format('d') . '</div>';
                    $resumen_meses[$indice_resultado]['Parto']++;
                }
            }
        }
    }

    echo '<center><table border="1" class="table table-bordered table-striped">';
    
    echo '<tr><th colspan=15><center>'.$anio.'</center></th></tr>';
    foreach ($tabla as $fila) {
        echo '<tr>';
        foreach ($fila as $indice => $celda) {
            echo ($indice === 0 || $indice === 1 || $indice === 2) ? '<th>' . $celda . '</th>' : '<td>' . $celda . '</td>';
        }
        echo '</tr>';
    }

    if (empty($datos_animal)) {
        echo '<tr><td colspan="15" style="text-align:center;">No hay datos disponibles</td></tr>';
    } else {
        $contador = 1;
        foreach ($datos_animal as $clave_animal => $datos_meses) {
            list($arete, $nombre_animal) = explode('|', $clave_animal);
            echo '<tr><th>' . $contador . '</th><th>' . $arete . '</th><th>' . $nombre_animal . '</th>';
            foreach ($datos_meses as $celda) {
                echo '<td>' . $celda . '</td>';
            }
            echo '</tr>';
            $contador++;
        }
    }

    echo '</table></center>';
}



public function mostraTablaReproduccion3($anio, $mes) {
    $anioAnterior = $anio - 1;
    $fecha_actual = new DateTime();

    $sql = "SELECT
                a.nombre AS nombre_animal,
                a.arete AS numero_arete,
                a.fecnac AS fecha_nacimiento,
                EXTRACT(MONTH FROM r.fecpro) AS mes,
                EXTRACT(YEAR FROM r.fecpro) AS ano,
                r.fecpro AS fecha_monta,
                r.fecres AS fecha_resultado,
                r.fecrev AS fecha_revision,
                r.tiprep AS tipo_rep,
                r.tipres AS tipo_res,
                r.idrep AS idrep
            FROM \"ANIMALES\" a
            LEFT JOIN \"REPRODUCCION\" r ON a.id = r.idmadre
            WHERE
                a.sexani = 1 AND
                a.esthac = 1 AND
                a.espani = 1 AND
                idhac = {$_SESSION['idhac']}
            ORDER BY a.nombre";

    $stmt = $this->consulta($sql);
    $resultados = pg_fetch_all($stmt);

    $tabla = array();
    $tabla[] = array('N°', 'Arete', 'Animal', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre',
                      'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');

    $resumen_meses = array_fill(1, 24, ['Monta' => 0, 'Revisar' => 0, 'Parto' => 0, 'Aborto' => 0]);

    $datos_animal = array();
    $contador = 1;

    foreach ($resultados as $fila) {
        $nombre_animal = $fila['nombre_animal'];
        $arete = $fila['numero_arete'];
        $fecha_monta = $fila['fecha_monta'] ? new DateTime($fila['fecha_monta']) : null;
        $fecha_resultado = $fila['fecha_resultado'] ? new DateTime($fila['fecha_resultado']) : null;
        $fecha_revision = $fila['fecha_revision'] ? new DateTime($fila['fecha_revision']) : null;
        $tipo_res = $fila['tipo_res'];
        $idrep = $fila['idrep'];

        $clave_animal = $arete . '|' . $nombre_animal;

        if (!isset($datos_animal[$clave_animal])) {
            $datos_animal[$clave_animal] = array_fill(1, 24, '');
        }

        if ($tipo_res == 2 && $fecha_resultado && ($fecha_resultado->format('Y') == $anioAnterior || $fecha_resultado->format('Y') == $anio)) {
            $indice_aborto = ($fecha_resultado->format('Y') == $anioAnterior) ? $fecha_resultado->format('n') : $fecha_resultado->format('n') + 12;
            $datos_animal[$clave_animal][$indice_aborto] .= '<div style="background-color: red; color: white;">Aborto - '.$fecha_resultado->format('d').'</div>';
            $resumen_meses[$indice_aborto]['Aborto']++;
        } else {
            if ($fecha_monta && ($fecha_monta->format('Y') == $anioAnterior || $fecha_monta->format('Y') == $anio)) {
                $indice_monta = ($fecha_monta->format('Y') == $anioAnterior) ? $fecha_monta->format('n') : $fecha_monta->format('n') + 12;
                $datos_animal[$clave_animal][$indice_monta] .= '<div style="background-color: orange; color: black;">Monta - '.$fecha_monta->format('d').'</div>';
                $resumen_meses[$indice_monta]['Monta']++;
            }
            if ($fecha_revision && ($fecha_revision->format('Y') == $anioAnterior || $fecha_revision->format('Y') == $anio)) {
                $indice_revision = ($fecha_revision->format('Y') == $anioAnterior) ? $fecha_revision->format('n') : $fecha_revision->format('n') + 12;
                $datos_animal[$clave_animal][$indice_revision] .= '<div style="background-color: yellow; color: black;">Revisar - '.$fecha_revision->format('d').'</div>';
                $resumen_meses[$indice_revision]['Revisar']++;
            }
            if ($fecha_resultado && ($fecha_resultado->format('Y') == $anioAnterior || $fecha_resultado->format('Y') == $anio)) {
                $indice_resultado = ($fecha_resultado->format('Y') == $anioAnterior) ? $fecha_resultado->format('n') : $fecha_resultado->format('n') + 12;
                if ($fecha_resultado > $fecha_actual && $tipo_res != 3) {
                    $datos_animal[$clave_animal][$indice_resultado] .= '<div style="background-color: lightblue; color: black;">Parto- '.$fecha_resultado->format('d').'</div>';
                } elseif ($tipo_res == 3) {
                    $datos_animal[$clave_animal][$indice_resultado] .= '<div style="background-color: green; color: white;">Parto - '.$fecha_resultado->format('d').'</div>';
                    $resumen_meses[$indice_resultado]['Parto']++;
                }
            }
        }
    }

    echo '<center><table border="1" class="table table-bordered table-striped">';
    foreach ($tabla as $fila) {
        echo '<tr>';
        foreach ($fila as $indice => $celda) {
            echo ($indice === 0 || $indice === 1 || $indice === 2) ? '<th>' . $celda . '</th>' : '<td>' . $celda . '</td>';
        }
        echo '</tr>';
    }

    $contador = 1;
    foreach ($datos_animal as $clave_animal => $datos_meses) {
        list($arete, $nombre_animal) = explode('|', $clave_animal);
        echo '<tr><th>' . $contador . '</th><th>' . $arete . '</th><th>' . $nombre_animal . '</th>';
        foreach ($datos_meses as $celda) {
            echo '<td>' . $celda . '</td>';
        }
        echo '</tr>';
        $contador++;
    }

    echo '<tr><th colspan="3">Resumen</th>';
    for ($i = 1; $i <= 24; $i++) {
        $resumen = $resumen_meses[$i];
        echo '<td>';
        echo 'Montas: ' . $resumen['Monta'] . '<br>';
        echo 'Revisiones: ' . $resumen['Revisar'] . '<br>';
        echo 'Partos: <div style="background-color: green; color: white; display: inline;">' . $resumen['Parto'] . '</div><br>';
        echo 'Abortos: ' . $resumen['Aborto'];
        echo '</td>';
    }
    echo '</tr>';

    echo '</table></center>';
}




public function listarReproduccionesFecha($fini,$ffin){
    
}


public function registrarCria() {
    // Calcular las fechas de inicio y fin del rango (1 mes antes y 1 mes después de la fecha actual)
    $fechaInicio = date('Y-m-d', strtotime('-1 month'));
    $fechaFin = date('Y-m-d', strtotime('+1 month'));

    // Consulta para obtener las reproducciones con fecha de resultado en el rango
    $sql = "SELECT r.idrep, r.idmadre, r.fecpro, r.fecres, r.idpadre, 
                   r.detrep, r.tiprep, r.tipres, r.obsrep, a.nombre AS nombre_madre, a.arete AS arete_madre
            FROM \"REPRODUCCION\" r
            INNER JOIN \"ANIMALES\" a ON r.idmadre = a.id
            WHERE r.fecres BETWEEN '$fechaInicio' AND '$fechaFin' 
              AND r.idcria = 0 
              AND a.esthac = 1 
              AND r.tipres NOT IN (1, 2)
              AND a.idhac = {$_SESSION['idhac']}
            ORDER BY r.fecres ASC";
    
    $stmt = $this->consulta($sql);
    $reproducciones = pg_fetch_all($stmt);

    $opraza = $this->listarRazas(2);
    $oppro = $this->listarProveedor(2);

    echo '<div class="container">';
    echo '<h1 class="text-center mb-4">Registrar Cría</h1>';
    
    if (!$reproducciones) {
        echo "<p class='text-center'>No hay reproducciones registradas con parto esperado en el último mes.</p>";
    } else {
        
      foreach ($reproducciones as $rep) { 
    $idrep = $rep['idrep'];
    $nombreMadre = $rep['nombre_madre'];
    $areteMadre = $rep['arete_madre'];
    $fecres = $rep['fecres'];
    
    echo "<form method='POST' class='section-group' enctype='multipart/form-data'>"; // <-- AQUI AGREGO enctype
    echo "<button type='button' class='btn btn-primary w-100' onclick=\"toggleForm('form_$idrep')\">Registrar Cría de $nombreMadre ($areteMadre) - $fecres</button>";
    echo "<div id='form_$idrep' class='reproduccion-form' style='display: none; margin-top: 10px;'>";

    echo "<input type='hidden' name='idmadre' value='{$rep['idmadre']}'>";
    echo "<input type='hidden' name='idpadre' value='{$rep['idpadre']}'>";
    echo "<input type='hidden' name='idrep' value='{$rep['idrep']}'>";
    echo "<input type='hidden' name='esthac' value='1'>";
    echo "<input type='hidden' name='estsal' value='1'>";
    echo "<input type='hidden' name='estrep' value='1'>";
    echo "<input type='hidden' name='espani' value='1'>";

    echo "<div class='form-group'>";
    echo "<label for='nombre'>Nombre:</label>";
    echo "<input type='text' name='nombre' class='form-control' required>";
    echo "</div>";

    echo "<div class='form-group'>";
    echo "<label for='arete'>Arete:</label>";
    echo "<input type='number' name='arete' class='form-control' required>";
    echo "</div>";

    echo "<div class='form-group'>";
    echo "<label for='fecnac'>Fecha de Nacimiento:</label>";
    echo "<input type='date' name='fecnac' class='form-control' value='$fecres' required>";
    echo "</div>";

    echo "<div class='form-group'>";
    echo "<label for='sexani'>Sexo:</label>";
    echo "<select name='sexani' class='form-control'>";
    echo "<option value='1'>Hembra</option>";
    echo "<option value='2'>Macho</option>";
    echo "</select>";
    echo "</div>";

    echo "<div class='form-group'>";
    echo "<label for='idraza'>Raza:</label>";
    echo "<br>$opraza";
    echo "</div>";

    echo "<div class='form-group'>";
    echo "<label for='idprov'>Procedencia:</label>";
    echo "<br>$oppro";
    echo "</div>";

    // ⬇️ NUEVO: Campo de imagen
    echo "<div class='form-group'>";
    echo "<label for='imagen'>Imagen de la Cría:</label>";
    echo "<input type='file' name='fotoa' accept='image/*' class='form-control'>";
    echo "</div>";

    echo "<button type='submit' name='bttRegistrarCria' class='btn btn-success btn-guardar' value='$idrep'>Guardar Cría</button>";
    echo "</div></form>";
}

    }
    echo "</div>";
    
    echo "<script>
        function toggleForm(id) {
            var form = document.getElementById(id);
            if (form.style.display === 'none') {
                form.style.display = 'block';
            } else {
                form.style.display = 'none';
            }
        }
    </script>";
}


public function crearCria($datos){
    
    $datos['feclle']=$datos['fecnac']; 
    /*
    IF($datos['sexani']==2){
        $datos['estrep']=7; 
    }
    */
    $id = $this->crearAnimal($datos);
    if($id){
    // Actualizar la tabla de reproducción con la información de la cría
    $sql = "UPDATE \"REPRODUCCION\"
            SET fecres = '{$datos['fecnac']}', tipres = 3, idcria = $id
            WHERE idrep = {$datos['idrep']}";
            
    if ($this->consulta($sql)) {
    
        $sqlDelete = 'DELETE FROM "ANIMAL_GRUPO" WHERE idani = ' . $datos['idmadre'] . ';';
    if ($this->consulta($sqlDelete)) {
        echo "<div class='mesajeok'>Grupo anterior eliminado correctamente.</div>";
    } else {
        echo "<div class='errores'>Error al eliminar grupo anterior.</div>";
    }

    // Primero buscar el grupo lechero disponible
$sqlBuscarGrupo = 'SELECT id FROM "GRUPO" WHERE estgru = 4 AND idhac = ' . $_SESSION['idhac'] . ' LIMIT 1;';
$grupo = $this->consulta($sqlBuscarGrupo);

if ($grupo && $this->num_rows($grupo) > 0) {
    $datosGrupo = $this->row($grupo);
    $idgru = $datosGrupo['id'];

    // Ahora sí insertar
    $sqlInsert = 'INSERT INTO "ANIMAL_GRUPO" (idani, idgru) VALUES (' . $datos['idmadre'] . ', ' . $idgru . ');';
    
    if ($this->consulta($sqlInsert)) {
        echo "<div class='mesajeok'>Nuevo grupo asignado correctamente.</div>";
    } else {
        echo "<div class='errores'>Error al asignar al grupo lechero.</div>";
    }
} else {
    echo "<div class='errores'>No se encontró grupo lechero disponible.</div>";
}

   
    
        echo "<div class='alert alert-success text-center'>
                <strong>Cría registrada con éxito:</strong><br>
                Nombre: {$datos['nombre']}<br>
                Madre: {$datos['idmadre']}<br>
                Padre: {$datos['idpadre']}<br>
                Fecha de Nacimiento: {$datos['fecnac']}
              </div>";
                
    } else {
        echo "<div class='alert alert-danger text-center'>Error al actualizar la información de reproducción.".$sql."</div>";
    }
    return $id;
}else{
     echo "<div class='alert alert-danger text-center'>Error al crear el animal.</div>";
}



}
public function modificarReproduccionConfirmar($datos) {
    // Verifica que se haya enviado el formulario con el botón de modificar
    if (isset($datos['bttmodrepConfirmar'])) {
        // Recuperar y sanitizar los datos recibidos en $datos
        $idrep  = intval($datos['idrep']);
        $idani  = intval($datos['idani']);
        $fecpro = $datos['fecpro']; // Se espera formato YYYY-MM-DD
        $fecrev = $datos['fecrev']; // Se espera formato YYYY-MM-DD
        $tipres = intval($datos['tipres']);

        // Construir la consulta SQL para actualizar la reproducción
        $sql = "UPDATE \"REPRODUCCION\"
                SET fecpro = '$fecpro',
                    fecrev = '$fecrev',
                    tipres = $tipres
                WHERE idrep = $idrep AND idmadre = $idani";

        if($tipres==2)
        $sql = "UPDATE \"REPRODUCCION\"
                SET fecpro = '$fecpro',
                    fecrev = '$fecrev',
                    fecres = '$fecres',    
                    tipres = $tipres
                WHERE idrep = $idrep AND idmadre = $idani";
        
        
        // Ejecutar la consulta
        $resultado = $this->consulta($sql);
        if ($resultado) {
            return "Reproducción modificada correctamente.";
        } else {
            return "Error al modificar la reproducción.";
        }
    }
}


public function mostrarListadoRegistroMonta($idhac) {
    // Rango de fechas para procesos activos (40 días antes y después de hoy)
    $fechaInicio = date('Y-m-d', strtotime('-960 days'));
    $fechaFin   = date('Y-m-d', strtotime('-21 days'));
     // Inicializamos los grupos
    $grupo1 = []; // Vacas por confirmar Monta (tipres == 0 y (hoy - fecpro) >= 21 días)
    $grupo2 = []; // Vacas lecheras sin monta (tipres == 2 o 3 y (hoy - fecres) >= 21 días)
    $grupo3 = []; // Vacas primera reproducción (sin registros de reproducción, edad >= 13 meses)
    $grupo4 = []; // Vacas por secar que tengan 7 meses luego del parto y no se hayan secado
    // Consulta para animales con procesos activos en reproducción filtrados por idhac
    // Se incluyen los campos tiprep y tipres para evaluar las condiciones de cada grupo

$sqlProcesos = "
    SELECT 
        a.id,
        a.nombre AS nombre_animal,
        a.fecnac,
        r.fecpro,
        r.fecres,
        r.fecrev,
        r.tiprep,
        r.tipres
    FROM \"ANIMALES\" a
    JOIN \"REPRODUCCION\" r ON a.id = r.idmadre
    WHERE
        a.sexani = 1 AND
        a.esthac = 1 AND
        a.espani = 1 AND
        a.idhac = $idhac AND
        r.fecpro = (
            SELECT MAX(r2.fecpro)
            FROM \"REPRODUCCION\" r2
            WHERE r2.idmadre = a.id and r.tipres = 0
        )
    ORDER BY r.fecpro DESC;";

//por ultima fecha el 
    $stmtProcesos = $this->consulta($sqlProcesos);
    $resultadosProcesos = pg_fetch_all($stmtProcesos);

  if ($resultadosProcesos) {
       foreach ($resultadosProcesos as $fila) {
           $id = $fila['id'];
           $nombre = $fila['nombre_animal'];
           $fecnac = $fila['fecnac'];
           $edad_meses = round((strtotime(date('Y-m-d')) - strtotime($fecnac)) / (30 * 24 * 60 * 60));
          $edad_anios = round((strtotime(date('Y-m-d')) - strtotime($fecnac)) / (365.25 * 24 * 60 * 60));
          $segundos_diferencia = strtotime(date('Y-m-d')) - strtotime($fecnac);
// Calcular años y meses
$anios = floor($segundos_diferencia / (365.25 * 24 * 60 * 60));
$meses = floor(($segundos_diferencia - ($anios * 365.25 * 24 * 60 * 60)) / (30 * 24 * 60 * 60));
// Formato de edad: "X años con Y meses"
$edad = "{$anios} años con {$meses} meses";
          
          
           $diferencia= round((strtotime(date('Y-m-d')) - strtotime($fila['fecpro'])) / ( 24 * 60 * 60));
              $grupo1[$id] = [
                       'id' => $id,
                       'nombre' => $nombre,
                       'edad_meses' => $edad_meses,
                       'edad_anios' => $edad_anios,
                        'edad' => $edad,
                       'tiempo' => $diferencia . " días"
                  
                   ];
       
       }
    }

//echo $sqlProcesos;

$sqlProcesos = "
SELECT a.id,
       a.nombre AS nombre_animal,
       a.fecnac,
       r.fecpro,
       r.fecres,
       r.fecrev,
       r.tiprep,
       r.tipres
FROM \"ANIMALES\" a
JOIN \"REPRODUCCION\" r ON a.id = r.idmadre
WHERE a.sexani = 1
  AND a.esthac = 1
  AND a.espani = 1
  AND a.idhac = $idhac
  AND r.idrep = (
    SELECT r2.idrep
FROM \"REPRODUCCION\" r2
WHERE r2.idmadre = a.id
ORDER BY r2.fecpro DESC
LIMIT 1
  )
  AND r.tipres IN (1,2,3)
  AND r.fecres BETWEEN '$fechaInicio' AND '$fechaFin'
ORDER BY  r.fecres 
";



//echo $sqlProcesos;

    $stmtProcesos = $this->consulta($sqlProcesos);
    $resultadosProcesos = pg_fetch_all($stmtProcesos);
    // Procesamos los registros con procesos de reproducción
    if ($resultadosProcesos) {
       foreach ($resultadosProcesos as $fila) {
           $id = $fila['id'];
           $nombre = $fila['nombre_animal'];
           $fecnac = $fila['fecnac'];
           $edad_meses = round((strtotime(date('Y-m-d')) - strtotime($fecnac)) / (30 * 24 * 60 * 60));
             $edad_anios = round((strtotime(date('Y-m-d')) - strtotime($fecnac)) / (365.25 * 24 * 60 * 60));
             
             $segundos_diferencia = strtotime(date('Y-m-d')) - strtotime($fecnac);
// Calcular años y meses
$anios = floor($segundos_diferencia / (365.25 * 24 * 60 * 60));
$meses = floor(($segundos_diferencia - ($anios * 365.25 * 24 * 60 * 60)) / (30 * 24 * 60 * 60));
// Formato de edad: "X años con Y meses"
$edad = "{$anios} años con {$meses} meses";
            $diferencia= round((strtotime(date('Y-m-d')) - strtotime($fila['fecres'])) / ( 24 * 60 * 60));
           $grupo2[$id] = [
                       'id' => $id,
                       'nombre' => $nombre,
                       'edad_meses' => $edad_meses,
                 'edad_anios' => $edad_anios,
                'edad' => $edad,
                       'tiempo' => $diferencia . " días abiertos",
                       'parto' => $fila['fecres'] . " "
                   ];
           
           // Grupo 2: Vacas lecheras sin monta
      
       }
    }


    // Consulta para animales sin procesos (con al menos 13 meses)
    $fechaMinimaNacimiento = date('Y-m-d', strtotime('-13 months'));
    $sqlSinProcesos = "SELECT
                           a.id,
                           a.nombre AS nombre_animal,
                           a.fecnac
                       FROM \"ANIMALES\" a
                       WHERE
                           a.sexani = 1 AND
                           a.esthac = 1 AND
                           a.espani = 1 AND
                           a.idhac = $idhac AND NOT EXISTS (
                               SELECT 1 FROM \"REPRODUCCION\" r WHERE r.idmadre = a.id
                           ) AND a.fecnac <= '$fechaMinimaNacimiento'
                       ORDER BY a.nombre";
    $stmtSinProcesos = $this->consulta($sqlSinProcesos);
    $resultadosSinProcesos = pg_fetch_all($stmtSinProcesos);
    
   
    
    
    // Procesamos las vacas sin procesos para el grupo 3 (primera reproducción)
    if ($resultadosSinProcesos) {
       foreach ($resultadosSinProcesos as $fila) {
           $id = $fila['id'];
           $nombre = $fila['nombre_animal'];
           $fecnac = $fila['fecnac'];
           $edad_meses = round((strtotime(date('Y-m-d')) - strtotime($fecnac)) / (30 * 24 * 60 * 60));
           $edad_anios = round((strtotime(date('Y-m-d')) - strtotime($fecnac)) / (365.25 * 24 * 60 * 60));
           $segundos_diferencia = strtotime(date('Y-m-d')) - strtotime($fecnac);
// Calcular años y meses
$anios = floor($segundos_diferencia / (365.25 * 24 * 60 * 60));
$meses = floor(($segundos_diferencia - ($anios * 365.25 * 24 * 60 * 60)) / (30 * 24 * 60 * 60));
// Formato de edad: "X años con Y meses"
$edad = "{$anios} años con {$meses} meses";
           $grupo3[] = [
                'id' => $id,
                'nombre' => $nombre,
                'edad_meses' => $edad_meses,
                'edad' => $edad,
                 'edad_anios' => $edad_anios,
                'tiempo' => "Sin registro"
           ];
       }
    }
    
    // Ordenar el grupo 3 de mayor a menor edad
    usort($grupo3, function($a, $b) {
         return $b['edad_meses'] - $a['edad_meses'];
    });
    
   $sqlProcesos = "
    SELECT 
        a.id,
        a.nombre AS nombre_animal,
        a.fecnac,
        r.fecpro,
        r.fecres,
        r.fecrev,
        r.fecsec,
        r.tiprep,
        r.tipres,
        r.estsec
    FROM \"ANIMALES\" a
    JOIN \"REPRODUCCION\" r ON a.id = r.idmadre
    WHERE 
        a.idhac = $idhac AND
        a.esthac = 1 AND    
        r.fecsec < CURRENT_DATE AND
        r.tipres = 3 AND
        r.estsec = 1
       
    ORDER BY r.fecres DESC;
";

//echo $sqlProcesos;

//por ultima fecha el 
    $stmtProcesos = $this->consulta($sqlProcesos);
    $resultadosProcesos = pg_fetch_all($stmtProcesos);

  if ($resultadosProcesos) {
       foreach ($resultadosProcesos as $fila) {
           $id = $fila['id'];
           $nombre = $fila['nombre_animal'];
           $fecnac = $fila['fecnac'];
           $edad_meses = round((strtotime(date('Y-m-d')) - strtotime($fecnac)) / (30 * 24 * 60 * 60));
          $edad_anios = round((strtotime(date('Y-m-d')) - strtotime($fecnac)) / (365.25 * 24 * 60 * 60));
          $segundos_diferencia = strtotime(date('Y-m-d')) - strtotime($fecnac);
// Calcular años y meses
$anios = floor($segundos_diferencia / (365.25 * 24 * 60 * 60));
$meses = floor(($segundos_diferencia - ($anios * 365.25 * 24 * 60 * 60)) / (30 * 24 * 60 * 60));
// Formato de edad: "X años con Y meses"
$edad = "{$anios} años con {$meses} meses";
          $diferencia_dias = round((strtotime(date('Y-m-d')) - strtotime($fila['fecres'])) / (24 * 60 * 60));
          $diferencia_meses = round((strtotime(date('Y-m-d')) - strtotime($fila['fecres'])) / (30 * 24 * 60 * 60));
         //  $diferencia= round((strtotime(date('Y-m-d')) - strtotime($fila['fecres'])) / ( 24 * 60 * 60));
           $diferencia='('.$diferencia_dias.')días o ('.$diferencia_meses.') meses';
           
              $grupo4[$id] = [
                       'id' => $id,
                       'nombre' => $nombre,
                       'edad_meses' => $edad_meses,
                       'edad_anios' => $edad_anios,
                        'edad' => $edad,
                       'tiempo' => $diferencia ,
                  'difdias' => $diferencia_dias ,
                  'fecsec' => $fila['fecsec']
                   ];
       
       }
    }
    
      usort($grupo4, function($a, $b) {
         return $b['difdias'] - $a['difdias'];
    });
    
    
    
    
    
    
    
    
    
    // Mostrar los tres grupos
    echo "<center>";
    $opanipadres = $this->listarAnimalesPadres(3);
    
    // Grupo 1: Vacas por confirmar Monta
    // Grupo 1: Vacas por confirmar Monta
echo "<h3>Vacas por confirmar Monta</h3>";
if (empty($grupo1)) {
    echo "<p>No hay vacas por confirmar monta.</p>";
} else {
    foreach ($grupo1 as $a) {
        $p=0;
        //({$a['edad_meses']} meses) ({$a['edad_anios']} años )
        $amadre = $this->mostrarAnimal($a['id']);
        echo "<div style='margin-bottom:10px;'>";
        echo "<button type='button' class='btn btn-primary w-100' onclick=\"ocultarMostrar('nuevopr_{$a['id']}')\">";
        echo "{$a['nombre']}  ({$a['edad']})";
        echo "</button>";
        echo " - Tiempo desde último proceso: {$a['tiempo']}";
        
        // Formulario para registrar un NUEVO proceso de reproducción
        echo "<div id='nuevopr_{$a['id']}' style='display:none;'><center>";
      $pConsulta = $this->mostrarReproduccionAnimal($a['id']);
        if ($pConsulta) {
            while ($reg = $this->row($pConsulta)) {
              
                $r = '';
                if ($reg['idcria'] == 0)
                    $r = '<option value="0">En proceso / no existente</option>';
                
                echo "<form method='post'><table border='1' style='margin-top:10px;'>";
                echo "<tr>";
                echo '<td>Fecha Monta:<input type="date" value="'.$reg['fecpro'].'" name="fecpro">';
                echo '<br>Fecha Revision:<input type="date" value="'.$reg['fecrev'].'" name="fecrev">
                    <br>Fecha Parto:<input type="date" value="'.$reg['fecres'].'" name="fecres"></td>';
                
                
                echo '<td></td>';
                               echo '<td>Estado:<select name="tipres">
                        <option value="'.$reg['tipres'].'">'.$this->tipres[$reg['tipres']].'</option>
                        <option value="0">'.$this->tipres[0].'</option>
                        <option value="1">'.$this->tipres[1].'</option>
                        <option value="2">'.$this->tipres[2].'</option>
                        <option value="3">'.$this->tipres[3].'</option>
                        <option value="4">'.$this->tipres[4].'</option>
                        <option value="5">'.$this->tipres[5].'</option>
                      </select></td>';
                               if($p==0)
                echo '<tr><td colspan=3><center>
                        <button name="bttmodrepConfirmar" value="'.$reg['idrep'].'">
                          <img src="../img/modif.jpg" alt=""/> <br>Modificar
                        </button>    
                     </center>
                      </td></tr>';
                                $p++;
                echo "</tr>";
                echo "<input type='hidden' name='idani' value='".$reg['idmadre']."'>";
                echo "<input type='hidden' name='idrep' value='".$reg['idrep']."'>";
                echo "</table></form>";
            }
        }
        echo "</center></div>";
        
        // FORMULARIO ADICIONAL: Mostrar datos de la última reproducción para confirmar la monta
        
        
        echo "</div>";
    }
}

    //
    // Grupo 2: Vacas lecheras sin monta
    echo "<h3>Vacas lecheras sin monta</h3>";
    if (empty($grupo2)) {
         echo "<p>No hay vacas lecheras sin monta.</p>";
    } else {
         foreach ($grupo2 as $a) {
             $amadre = $this->mostrarAnimal($a['id']);
             echo "<div style='margin-bottom:10px;'>";
             echo "<button type='button' class='btn btn-warning w-100'' onclick=\"ocultarMostrar('nuevopr_{$a['id']}')\">";
             echo "{$a['nombre']}  ({$a['edad']})";
             echo "</button>";
             echo " - {$a['tiempo']} - {$a['parto']}";
             
             echo "<div id='nuevopr_{$a['id']}' style='display:none;'><center>";
             echo "<form method='post'>";
             echo "<h2>Nuevo Proceso de Reproducción</h2>";
             echo "<table border='1'>";
             
             // Vaca
             echo "<tr>";
             echo "<th>Vaca:</th>";
             echo "<td>{$amadre['nombre']} <input type='hidden' name='idmadre' value='{$a['id']}'></td>";
             echo "</tr>";
             
             // Tipo de Reproducción
             echo "<tr>";
             echo "<th>Tipo de Reproducción:</th>";
             echo "<td>";
             echo "<select name='tiprep'>";
             echo "<option value='0'>{$this->tiprep[0]}</option>";
             echo "<option value='1'>{$this->tiprep[1]}</option>";
             echo "<option value='2'>{$this->tiprep[2]}</option>";
             echo "<option value='3'>{$this->tiprep[3]}</option>";
             echo "</select>";
             echo "</td>";
             echo "</tr>";
             
             // Fecha del Monta / Inseminación (fecha actual)
             echo "<tr>";
             echo "<th>Fecha del Monta / Inseminación:</th>";
             echo "<td><input type='date' name='fecpro' value='" . date('Y-m-d') . "'></td>";
             echo "</tr>";
             
             // Toro padre
             echo "<tr>";
             echo "<th>Toro padre:</th>";
             echo "<td><select name='idpadre'>{$opanipadres}</select></td>";
             echo "</tr>";
             
             // Cría (hidden)
             echo "<tr>";
             echo "<td colspan='2'><input type='hidden' name='idcria' value='0'></td>";
             echo "</tr>";
             
             // Detalle
             echo "<tr>";
             echo "<th>Detalle:</th>";
             echo "<td><input type='text' placeholder='Detalle de la Reproducción' name='detrep' style='width:100%;'></td>";
             echo "</tr>";
             
             // Observaciones
             echo "<tr>";
             echo "<th>Observaciones:</th>";
             echo "<td><textarea placeholder='Observaciones' name='obsrep'></textarea></td>";
             echo "</tr>";
             
             // Botón de envío
             echo "<tr>";
             echo "<th colspan='2'><center><button type='submit' name='bttcrearReproduccion'><img src='../img/anadir.png' alt=''/> <br>CREAR REPRODUCCION</button></center></th>";
             echo "</tr>";
             
             echo "</table>";
             echo "</form>";
             echo "</center></div>";
             
             echo "</div>";
         }
    }
    
    // Grupo 3: Vacas primera reproducción
    echo "<h3>Vacas primera reproducción</h3>";
    if (empty($grupo3)) {
         echo "<p>No hay vacas para primera reproducción.</p>";
    } else {
         foreach ($grupo3 as $a) {
             $amadre = $this->mostrarAnimal($a['id']);
             echo "<div style='margin-bottom:10px;'>";
             echo "<button type='button' class='btn btn-secondary w-100'  onclick=\"ocultarMostrar('nuevopr_{$a['id']}')\">";
             echo "{$a['nombre']} ({$a['edad']})";
             echo "</button>";
             echo " - Sin registro de reproducción";
             
             echo "<div id='nuevopr_{$a['id']}' style='display:none;'><center>";
             echo "<form method='post'>";
             echo "<h2>Nuevo Proceso de Reproducción</h2>";
             echo "<table border='1'>";
             
             // Vaca
             echo "<tr>";
             echo "<th>Vaca:</th>";
             echo "<td>{$amadre['nombre']} <input type='hidden' name='idmadre' value='{$a['id']}'></td>";
             echo "</tr>";
             
             // Tipo de Reproducción
             echo "<tr>";
             echo "<th>Tipo de Reproducción:</th>";
             echo "<td>";
             echo "<select name='tiprep'>";
             echo "<option value='0'>{$this->tiprep[0]}</option>";
             echo "<option value='1'>{$this->tiprep[1]}</option>";
             echo "<option value='2'>{$this->tiprep[2]}</option>";
             echo "<option value='3'>{$this->tiprep[3]}</option>";
             echo "</select>";
             echo "</td>";
             echo "</tr>";
             
             // Fecha del Monta / Inseminación (fecha actual)
             echo "<tr>";
             echo "<th>Fecha del Monta / Inseminación:</th>";
             echo "<td><input type='date' name='fecpro' value='" . date('Y-m-d') . "'></td>";
             echo "</tr>";
             
             // Toro padre
             echo "<tr>";
             echo "<th>Toro padre:</th>";
             echo "<td><select name='idpadre'>{$opanipadres}</select></td>";
             echo "</tr>";
             
             // Cría (hidden)
             echo "<tr>";
             echo "<td colspan='2'><input type='hidden' name='idcria' value='0'></td>";
             echo "</tr>";
             
             // Detalle
             echo "<tr>";
             echo "<th>Detalle:</th>";
             echo "<td><input type='text' placeholder='Detalle de la Reproducción' name='detrep' style='width:100%;'></td>";
             echo "</tr>";
             
             // Observaciones
             echo "<tr>";
             echo "<th>Observaciones:</th>";
             echo "<td><textarea placeholder='Observaciones' name='obsrep'></textarea></td>";
             echo "</tr>";
             
             // Botón de envío
             echo "<tr>";
             echo "<th colspan='2'><center><button type='submit' name='bttcrearReproduccion'><img src='../img/anadir.png' alt=''/> <br>CREAR REPRODUCCION</button></center></th>";
             echo "</tr>";
             
             echo "</table>";
             echo "</form>";
             echo "</center></div>";
             
             echo "</div>";
         }
    }
    
    
    
    // Grupo 4: Vacas que hay que secar
    echo "<h3>Vacas que se deben secar</h3>";
  // echo "<h3>Vacas por confirmar Monta</h3>";
if (empty($grupo4)) {
    echo "<p>No hay vacas por secar.</p>";
} else {
    foreach ($grupo4 as $a) {
        $p=0;
        //({$a['edad_meses']} meses) ({$a['edad_anios']} años )
        $amadre = $this->mostrarAnimal($a['id']);
        echo "<div style='margin-bottom:10px;'>";
        echo "<button type='button' class='btn btn-success w-100' onclick=\"ocultarMostrar('nuevoprs_{$a['id']}')\">";
        echo "{$a['nombre']}  ({$a['edad']})";
        echo "</button>";
        echo " - Tiempo desde último parto: {$a['tiempo']}";
        
        // Formulario para registrar un NUEVO proceso de reproducción
        echo "<div id='nuevoprs_{$a['id']}' style='display:none;'><center>";
      $pConsulta = $this->mostrarReproduccionAnimal($a['id']);
        if ($pConsulta) {
            while ($reg = $this->row($pConsulta)) {
              if($reg['estsec']==1){
                  echo "<form method='post'><table border='1' style='margin-top:10px;'>";
                echo "<tr>";
                 echo '<br>Fecha Secado:<input type="date" value="'.$reg['fecsec'].'" name="fecsec">
                    <br>Fecha Parto:<input type="date" value="'.$reg['fecres'].'" name="fecres"></td>';
                echo '<td></td>';
                               echo '<td>Estado Secado:<select name="estsec">
                        <option value="'.$reg['estsec'].'">'.$this->estsec[$reg['estsec']].'</option>
                        <option value="0">'.$this->estsec[0].'</option>
                        <option value="1">'.$this->estsec[1].'</option>
                        <option value="2">'.$this->estsec[2].'</option>
                      </select></td>';   
                  echo '<tr><td colspan=3><center>
                        <button name="bttmodrepSecado" value="'.$reg['idrep'].'">
                          <img src="../img/modif.jpg" alt=""/> <br>Modificar
                        </button>    
                     </center>
                      </td></tr>';
                   echo "</tr>";
                echo "<input type='hidden' name='idani' value='".$reg['idmadre']."'>";
                echo "<input type='hidden' name='idrep' value='".$reg['idrep']."'>";
                echo "</table></form>";
                  
              }
             }
        }
        echo "</center></div>";
        
        // FORMULARIO ADICIONAL: Mostrar datos de la última reproducción para confirmar la monta
        
        
        echo "</div>";
    }
}
    
    
    
    
    
    
    
    
    echo "</center>";
}




//modificarReproduccionSecado
public function modificarReproduccionSecado($datos) {
    // Verifica que se haya enviado el formulario con el botón de modificar
  
        // Recuperar y sanitizar los datos recibidos en $datos
        $idrep  = intval($datos['idrep']);
        $idani  = intval($datos['idani']);
        $fecsec = $datos['fecsec']; // Se espera formato YYYY-MM-DD
        $estsec = intval($datos['estsec']);

        // Construir la consulta SQL para actualizar la reproducción
        $sql = "UPDATE \"REPRODUCCION\"
                SET fecsec = '$fecsec',
                    estsec = $estsec
                WHERE idrep = $idrep AND idmadre = $idani";

        // Ejecutar la consulta
        $resultado = $this->consulta($sql);
        if ($resultado) {
           
            // 1. Eliminar asignación previa
$sqlDelete = 'DELETE FROM "ANIMAL_GRUPO" WHERE idani = ' . $idani . ';';
if($this->consulta($sqlDelete)){
     echo "<div class='mensajeok'>Animal eliminado del grupo de rejo</div>";
}else{
     echo "<div class='errores'>Error al eliminar el animal al grupo de rejo</div>";
}

// 2. Insertar nuevo grupo (lechería)
$sqlInsert = 'INSERT INTO "ANIMAL_GRUPO" (idani, idgru) 
              SELECT ' . $idani . ', id 
              FROM "GRUPO" 
              WHERE estgru = 5 AND idhac = ' . $_SESSION['idhac'] . ';';
if($this->consulta($sqlInsert)){
     echo "<div class='mensajeok'>Animal asignado al grupo de secas</div>";
}else{
     echo "<div class='errores'>Error al asignar el animal al grupo de secas</div>";
}


         
         } else {
                 echo "<div class='alert alert-info'>Error al crear el nuevo dato BDD <br></div>";
        }
        
        
        
 
}



}
