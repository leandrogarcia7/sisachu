<?php
require_once("LECHE.php");
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
class DIARIO extends LECHE{
    
     public $toma= array('SIN DATOS','MAÑANA','TARDE');
  
    
    
       public function mostrarInicioDiario(){
       $opgru= $this->listarGruposOptionTotalLechero();
       
      //  $anim=$this->listarAnimalesGrupo($idgrupo);
        
         echo '<center>
             <form><table border=1 class="table table-striped">
           <tr>  <th><b>Seleccionar grupo para agregar leche: </b> <td>
             <select name=idgru class=select-grade>'.$opgru.'</select></tr>
    <tr>  <th>  Toma: <td><select name=tielec>
                        <option value=0>'.$this->toma[0].'</option>
                        <option value=1>'.$this->toma[1].'</option>
                        <option value=2>'.$this->toma[2].'</option>                      
                   </select>  <tr>  <th> Fecha: <td><input type="date" value="'.date("Y-m-d").'" name="fecdia"> '
                 . '                <tr>  <th colspan=2> <center> <button type="submit" name="bttmostrar" > <img src="../img/anadir.png" alt=""/>  <BR>CREAR DIARIO <BR>DE LECHE</button>  </center>';
                       
echo '</table>  </center>';
         
     }
     
     public function listarAnimalesGrupoDiario($idgrupo,$id){
           $sql='select "ANIMAL_GRUPO".id,"ANIMALES".nombre,"ANIMALES".arete ,idani 
               from "ANIMAL_GRUPO","ANIMALES" where "ANIMAL_GRUPO".idani="ANIMALES".id and idgru='.$idgrupo.'
                and idani not in (select idani from "DIARIO_ANIMAL" where iddia='.$id.') order by nombre;';
       //  echo $sql;
         if($reg=$this->consulta($sql )){
             //echo "<div class=mesajeok >Nuevo dato registrado</div>";
         }else
         {
              echo "<div class=errores >Error al buscar en al BDD</div>".$sql;
         } 
       return  $reg;
         
     }
     
     public function mostrarDiarioLeche($id){     
         
   $query = 'select * from "DIARIO","GRUPO" where "GRUPO".id="DIARIO".idgru and  iddia='.$id.';';
    $result = $this->consulta($query);
    if ($row = pg_fetch_assoc($result)) {
    $diario=$row;
    }else{
        echo "Error en mostrar el Diario ";
        return 0;
    } 
         echo "<center>Grupo: ".$diario['detalle']." de la fecha ".$diario['fecdia']." toma ".$this->toma[$diario['tielec']]."</center>";
         $anim=$this->listarAnimalesGrupoDiario($diario['idgru'],$id);
         
         echo "<br><br><center><form>Seleccinar Animal:<select name=idani class=select-grande onchange=document.getElementById('litInput').focus()> ";
         
         while($reg=$this->row($anim)){
             echo "<option value=".$reg['idani'].">".$reg['nombre']." - ".$reg['arete']." </option>";
             
         }
         echo '</select>               
                   Litros: <input type="number" name="lit" size=5 id="litInput"> 
                   <button type="submit" name="bttguardartoma" > <img src="../img/guardar.jpg" alt=""/>    <BR>GUARDAR</button> 
                   <input type="hidden" name="iddia" value='.$id.'>
                   </form></center>'   ;
         //mostrar todas las tomas ingresadas de ese DIARIO
       $this-> mostrarDiarioAnimalesTabla($id);
     }
     
     
     public function crearDiario($datos){
         
         //validar si existe ya el DIARIO
       $query = 'select * from "DIARIO" where fecdia=\''.$datos['fecdia'].'\' and idgru='.$datos['idgru'].' and tielec= '.$datos['tielec'].';';
    $result = $this->consulta($query);
    if ($row = pg_fetch_assoc($result)) {
        ECHO "Diario ya creado ".$row['iddia'];
    return $row['iddia'];
    }  
         
           // Validaciones básicas para asegurarte de que los datos existen
    if(!isset($datos['idgru']) || empty($datos['idgru'])) {
        echo "ID de grupo no proporcionado.";
        return false;
    }
    if(!isset($datos['tielec']) || empty($datos['tielec'])) {
        echo "Toma no proporcionada.";
        return false;
    }
    if(!isset($datos['fecdia']) || empty($datos['fecdia'])) {
        echo "Fecha no proporcionada.";
        return false;
    }

    // Aquí asumo que el iddia es autoincremental (o serial en PostgreSQL) y que el idusu es una variable global o de sesión.
    // Si no es el caso, asegúrate de ajustar el código según tus necesidades.
    $query = 'INSERT INTO "DIARIO" (idusu, fecdia, idgru, tielec) VALUES ('.$_SESSION['id'].', \''.$datos['fecdia'].'\', '.$datos['idgru'].', '.$datos['tielec'].') RETURNING iddia;';

    $result = $this->consulta($query);
    if ($row = pg_fetch_assoc($result)) {
        echo "Diario de leche creado exitosamente con ID: " . $row['iddia'];
        return $row['iddia'];
    } else {
        echo "Error al ingresar a la BDD.";
        return false;
    }
         
     }
     function mostrarDiarioAnimalesTabla($id){

    // Construcción de la consulta SQL
    $query = 'SELECT A.nombre, D.lit  FROM "ANIMALES" A INNER JOIN "DIARIO_ANIMAL" D ON A.id = D.idani  WHERE D.iddia = '.$id.' order by nombre;';

    $result = $this->consulta($query);

    if (!$result) {
        echo "Error al consultar la BDD.";
        return false;
    }
$totalLitros = 0;
$numVacas = 0;
$litrosVacas = [];
    // Mostrar resultados en una tabla
    echo '<table border="1" class="table table-striped">';
    echo '<thead><tr><th>Nombre de la Vaca</th><th>Litros Ingresados</th></tr></thead>';
    echo '<tbody>';
    while ($row = $this->row($result)) {
        echo '<tr>';
        echo '<td>' . $row['nombre'] . '</td>';
        echo '<td>' . $row['lit'] . '</td>';
        echo '</tr>';
         $totalLitros += $row['lit'];
    $numVacas++;
    $litrosVacas[$row['nombre']] = $row['lit'];
    }
    $promedio = $totalLitros / $numVacas;

// Cálculo de la desviación estándar
$suma = 0;
foreach ($litrosVacas as $litros) {
    $suma += pow($litros - $promedio, 2);
}
$desviacionEstandar = sqrt($suma / $numVacas);

// Mejores 3 y peores 3 vacas
arsort($litrosVacas); // Ordena de mayor a menor
$mejores3 = array_slice($litrosVacas, 0, 3, true);
$peores3 = array_slice($litrosVacas, -3, 3, true);
    echo '</tbody>';
    echo '</table>';

    // Cuadro resumen
echo '<h3>Resumen</h3>';
echo 'Total de litros: ' . $totalLitros . '<br>';
echo 'Número de vacas ordeñadas: ' . $numVacas . '<br>';
echo 'Litros promedio: ' . $promedio . '<br>';
echo 'Desviación estándar: ' . $desviacionEstandar . '<br>';
echo 'Mejores 3 vacas: ' . implode(', ', array_keys($mejores3)) . '<br>';
echo 'Peores 3 vacas: ' . implode(', ', array_keys($peores3)) . '<br>';
    
    
    return true;
}
     
     function crearDiarioAnimal($datos) {
    // Validaciones básicas para asegurarte de que los datos existen
    if(!isset($datos['idani']) || empty($datos['idani'])) {
        echo "ID de animal no proporcionado.";
        return false;
    }
    if(!isset($datos['lit']) || empty($datos['lit'])) {
        echo "Litros no proporcionados.";
        return false;
    }
    if(!isset($datos['iddia']) || empty($datos['iddia'])) {
        echo "ID de diario no proporcionado.";
        return false;
    }

    // Construcción de la consulta SQL
    $query = 'INSERT INTO "DIARIO_ANIMAL" (idani, iddia, lit) VALUES ('.$datos['idani'].', '.$datos['iddia'].', '.$datos['lit'].');';

    if($this->consulta($query)){
        echo "Diario de animal creado exitosamente.";
        return $datos['iddia'];
    } else {
        echo "Error al ingresar a la BDD.";
        return false;
    }
}
public function mostrarIngresarDiarioLeche($idhac){
    
    $opgru= $this->listarGruposOptionTotalLechero();
    
    echo '<div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8 col-sm-12">
                <div class="card shadow">
                    <div class="card-header text-center bg-primary text-white">
                        <h5>Seleccionar grupo para agregar leche</h5>
                    </div>
                    <div class="card-body">
                        <form method="post">
                            <div class="mb-3">
                                <label for="idgru" class="form-label"><b>Grupo:</b></label>
                                <select name="idgru" class="form-select">'.$opgru.'</select>
                            </div>

                            <div class="mb-3">
                                <label for="tielec" class="form-label"><b>Toma:</b></label>
                                <select name="tielec" class="form-select">
                                 
                                    <option value="1">'.$this->toma[1].'</option>
                                    <option value="2">'.$this->toma[2].'</option>                      
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="fecdia" class="form-label"><b>Fecha:</b></label>
                                <input type="date" class="form-control" value="'.date("Y-m-d").'" name="fecdia">
                            </div>

                            <div class="text-center">
                                <button type="submit" name="bttmostrarDia" class="btn btn-success">
                                    <img src="../img/anadir.png" alt="" class="me-2" style="width: 20px; height: 20px;"> 
                                    CREAR DIARIO DE LECHE
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
      </div>';
//validar 
    
}

public function registrarDiario($datos){
    $id=$this->crearDiario($datos);
    $this->mostrarDiarioAnimalesTablaNuevos($id);
    $this->mostrarRegistroDiario($id);
    
}

public function guardarDiarioAnimal($datos){
    $id=$this->crearDiarioAnimal($datos);
    $this->mostrarDiarioAnimalesTablaNuevos($id);
    $this->mostrarRegistroDiario($id);
    
}
public function modificarDiarioAnimal($datos){
    
    $this->modificarlitrosDiarioAnimal($datos);
     $this->mostrarDiarioAnimalesTablaNuevos($datos['iddia']);
    $this->mostrarRegistroDiario($datos['iddia']);
}

public function modificarlitrosDiarioAnimal($datos){
    $query = 'UPDATE "DIARIO_ANIMAL" 
          SET lit = '.$datos['lit'].' 
          WHERE iddiaani = '.$datos['iddiaani'].';';
$result = $this->consulta($query);
    
    

    if (!$result) {
        echo '<div class="alert alert-danger">Error al modificar el registro.</div> '.$query;
        return false;
    }
    return $datos['iddia'];
    
}

public function eliminarDiarioAnimal($datos){
    
    $this->eliminarlitrosDiarioAnimal($datos);
     $this->mostrarDiarioAnimalesTablaNuevos($datos['iddia']);
    $this->mostrarRegistroDiario($datos['iddia']);
}

public function eliminarlitrosDiarioAnimal($datos){
    $query = 'delete from "DIARIO_ANIMAL" 
          WHERE iddiaani = '.$datos['iddiaani'].';';
$result = $this->consulta($query);
    
    

    if (!$result) {
        echo '<div class="alert alert-danger">Error al eliminar el registro.</div> ';
        return false;
    }
    return $datos['iddia'];
    
}



function mostrarDiarioAnimalesTablaNuevos($id) {
    // Obtener detalles del Diario
    $query = 'SELECT * FROM "DIARIO","GRUPO" WHERE "GRUPO".id="DIARIO".idgru AND iddia='.$id.';';
    $result = $this->consulta($query);
    
    if ($row = pg_fetch_assoc($result)) {
        $diario = $row;
    } else {
        echo '<div class="alert alert-danger text-center">Error al mostrar el Diario.</div>';
        return 0;
    }

    echo '<div class="container mt-4">';
    echo '<div class="card shadow">';
    echo '<div class="card-header bg-primary text-white text-center">';
    echo '<h5>Grupo: ' . $diario['detalle'] . ' - Fecha: ' . $diario['fecdia'] . ' - Toma: ' . $this->toma[$diario['tielec']] . '</h5>';
    echo '</div>';
    echo '<div class="card-body">';

    // **Consulta corregida**: Obtener solo los animales que NO tienen registro en "DIARIO_ANIMAL"
   
    
    $sql='SELECT 
    "ANIMAL_GRUPO".id, idani,
    "ANIMALES".nombre, 
    "ANIMALES".arete, 
    (SELECT D.lit 
     FROM "DIARIO_ANIMAL" D 
     WHERE D.idani = "ANIMALES".id 
     ORDER BY D.iddia DESC 
     LIMIT 1) AS ultimo_litros
FROM "ANIMAL_GRUPO"
INNER JOIN "ANIMALES" ON "ANIMAL_GRUPO".idani = "ANIMALES".id
WHERE "ANIMAL_GRUPO".idgru = '.$diario['idgru'].'
AND "ANIMALES".id NOT IN (SELECT idani FROM "DIARIO_ANIMAL" WHERE iddia = '.$id.')
ORDER BY "ANIMALES".nombre;
';
    $sql='SELECT 
    "ANIMAL_GRUPO".id, 
    "ANIMAL_GRUPO".idani,
    "ANIMALES".nombre, 
    "ANIMALES".arete, 
    -- Últimos litros registrados del animal
    (SELECT D.lit 
     FROM "DIARIO_ANIMAL" D 
     WHERE D.idani = "ANIMALES".id 
     ORDER BY D.iddia DESC 
     LIMIT 1) AS ultimo_litros,
    
    -- Última fecha de reproducción del animal
    (SELECT R.fecres 
     FROM "REPRODUCCION" R 
     WHERE R.idmadre = "ANIMALES".id and tipres=3
     ORDER BY R.fecres DESC 
     LIMIT 1) AS ultimo_parto,

(SELECT R.fecpro 
     FROM "REPRODUCCION" R 
     WHERE R.idmadre = "ANIMALES".id and tipres!=3 and tipres!=4
     ORDER BY R.fecres DESC 
     LIMIT 1) AS ultima_monta


FROM "ANIMAL_GRUPO"
INNER JOIN "ANIMALES" ON "ANIMAL_GRUPO".idani = "ANIMALES".id
WHERE "ANIMAL_GRUPO".idgru = '.$diario['idgru'].'
AND "ANIMALES".id NOT IN (SELECT idani FROM "DIARIO_ANIMAL" WHERE iddia = '.$id.')
ORDER BY "ANIMALES".nombre;
';
    
    
    $result = $this->consulta($sql);

    if (!$result) {
        echo '<div class="alert alert-danger">Error al consultar los animales.</div> ';
        return false;
    }

    // Si no hay resultados, mostrar un mensaje amigable
    if (pg_num_rows($result) == 0) {
        echo '<div class="alert alert-warning text-center">No hay nuevos animales para registrar.</div>';
        return false;
    }

    echo '<table class="table table-striped table-responsive">';
    echo '<thead class="table-dark"><tr><th>Nombre</th><th>Arete</th><th>Litros</th></tr></thead>';
    echo '<tbody>';

    while ($row = pg_fetch_assoc($result)) {
       $mensaje='';
        
        if  ($row['ultimo_parto']< $row['ultima_monta']){
             $fecha_parto = new DateTime($row['ultima_monta']);
            $hoy = new DateTime();
            $dias_transcurridos = $fecha_parto->diff($hoy)->days;
            $dias_en_ciclo = $dias_transcurridos % 21;
            $dias_faltantes = ($dias_en_ciclo != 0) ? (21 - $dias_en_ciclo) : 0;
            $mensaje='Día para celo: '.$dias_faltantes;
             if($dias_faltantes<3 or $dias_faltantes>18){
                  echo '<tr bgcolor=red>';
             }else{
                 echo '<tr>';
             }
        }else{
            $fecha_parto = new DateTime($row['ultimo_parto']);
            $hoy = new DateTime();
            $dias_transcurridos = $fecha_parto->diff($hoy)->days;
            $dias_en_ciclo = $dias_transcurridos % 21;
            $dias_faltantes = ($dias_en_ciclo != 0) ? (21 - $dias_en_ciclo) : 0;
             $mensaje='Día para celo: '.$dias_faltantes;
             
             if($dias_faltantes<3 or $dias_faltantes>18){
                  echo '<tr bgcolor=red>';
             }else{
                 echo '<tr>';
             }
        }
        
        $estrellita='';
         IF($row['ultimo_litros']>7){
             $estrellita='<i class="bi bi-star-fill text-warning">**</i>';
         }
             
         
         
        echo '<td>' . $row['nombre'] . ' '.$estrellita.'  '.$mensaje.' </td>';
        echo '<td>' . $row['arete'] . '</td>';
        
        echo '<td>
        <form method="POST">
        <table><tr><td>
            <input type="hidden" name="idani" value="'.$row['idani'].'">
            <input type="hidden" name="iddia" value="'.$id.'">
            <input type="number" name="lit" value="'.$row['ultimo_litros'].'" class="form-control litros-input  me-2"   style="width: 70px;" 
                   min="0" step="1" required
                   onfocus="this.value=\'\';"
                   onblur="if(this.value==\'\') this.value=\''.$row['ultimo_litros'].'\';">
     </td> ';

        
        
        // Botón de Guardar con bloqueo al hacer clic
        echo '<td>
                    <button type="submit" name=bttguadarAnimalDiario class="btn btn-success guardar-btn">
                        <i class="fas fa-save"></i> Guardar
                    </button>
                   </td></table>           
                </form>
              </td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
    echo '</div></div></div>';

    // **JavaScript para bloquear botón y cambiar texto al hacer clic**
    echo '<script>
            document.querySelectorAll(".litros-form").forEach(form => {
                form.addEventListener("submit", function(event) {
                    let button = form.querySelector(".guardar-btn");
                    button.disabled = true;
                    button.innerHTML = "<i class=\'fas fa-spinner fa-spin\'></i> Guardando...";
                });
            });
          </script>';

    return true;
}




public function mostrarRegistroDiario($id){
     // Construcción de la consulta SQL
    $query = 'SELECT A.id, A.nombre, D.lit,iddiaani FROM "ANIMALES" A 
              INNER JOIN "DIARIO_ANIMAL" D ON A.id = D.idani  
              WHERE D.iddia = '.$id.' ORDER BY A.nombre;';

    $result = $this->consulta($query);

    if (!$result) {
        echo '<div class="alert alert-danger">Error al consultar la base de datos.</div>';
        return false;
    }

    $totalLitros = 0;
    $numVacas = 0;
    $litrosVacas = [];

    echo '<div class="container mt-4">';
    echo '<div class="card shadow">';
    echo '<div class="card-header bg-primary text-white text-center">';
    echo '<h5>Registro Diario de Producción</h5>';
    echo '</div>';
    echo '<div class="card-body">';

    echo '<table class="table table-striped table-responsive">';
    echo '<thead class="table-dark"><tr><th>Nombre de la Vaca</th><th>Litros Ingresados</th></tr></thead>';
    echo '<tbody>';

    while ($row = $this->row($result)) {
        $totalLitros += $row['lit'];
        $numVacas++;
        $litrosVacas[$row['nombre']] = $row['lit'];

        echo '<tr>';
        echo '<td>' . $row['nombre'] . '</td>';
        
        // Formulario en cada fila
        echo '<td>
                <form method="POST" >
                 <div class="d-flex align-items-center">
                    <input type="hidden" name="iddiaani" value="'.$row['iddiaani'].'">
                    <input type="hidden" name="idani" value="'.$row['id'].'">
                    <input type="hidden" name="iddia" value="'.$id.'">
                    <input type="number" name="lit" class="form-control" value="'.$row['lit'].'" min="0" step="1" required onfocus="this.value=\'\';"
                   onblur="if(this.value==\'\') this.value=\''.$row['lit'].'\';">
            ';
        
        // Botón de Guardar
        echo '
                    <button type="submit" name=bttmoddiario class="btn btn-success guardar-btn">
                        <i class="fas fa-save"></i> Guardar
                    </button>
<button type="submit" name=bttelidiario class="btn btn-danger">
                <i class="fas fa-trash"></i> Eliminar
            </button>                    
</div>
                </form>
              </td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
    echo '</div></div></div>';

    if ($numVacas > 0) {
        $promedio = $totalLitros / $numVacas;

        // Cálculo de la desviación estándar
        $suma = 0;
        foreach ($litrosVacas as $litros) {
            $suma += pow($litros - $promedio, 2);
        }
        $desviacionEstandar = sqrt($suma / $numVacas);

        // Mejores 3 y peores 3 vacas
        arsort($litrosVacas); // Ordena de mayor a menor
        $mejores3 = array_slice($litrosVacas, 0, 3, true);
        $peores3 = array_slice($litrosVacas, -3, 3, true);

        // Sección de resumen
        echo '<div class="container mt-4">';
        echo '<div class="card shadow">';
        echo '<div class="card-header bg-info text-white text-center"><h5>Resumen de Producción</h5></div>';
        echo '<div class="card-body">';
        echo '<p><b>Total de litros:</b> ' . $totalLitros . '</p>';
        echo '<p><b>Número de vacas ordeñadas:</b> ' . $numVacas . '</p>';
        echo '<p><b>Litros promedio:</b> ' . number_format($promedio, 2) . '</p>';
        echo '<p><b>Desviación estándar:</b> ' . number_format($desviacionEstandar, 2) . '</p>';
        echo '<p><b>Mejores 3 vacas:</b> ' . implode(', ', array_keys($mejores3)) . '</p>';
        echo '<p><b>Peores 3 vacas:</b> ' . implode(', ', array_keys($peores3)) . '</p>';
        echo '</div></div></div>';
    }

    return true;
}

}
