<?php
require_once("ANIMALES.php");
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EMPLEADOS
 *
 * @author LGT-5
 */
class EMPLEADOS extends ANIMALES{
    
     public $id,$detalle;
     
     
     public function mostrarInicio(){
         echo '<center>
             <form>
             <b>Buscar por apellido: </b> <input type=text placeholder=GARCIA name=txtbuscar >
             <button type="submit" name="bttbuscar" > <img src="../img/buscar.jpg" alt=""/>  <BR>BUSCAR EMPLEADO</button>
             <br>
             <button type="submit" name="bttcrear" > <img src="../img/anadir.png" alt=""/>  <BR>CREAR EMPLEADO</button>
             <button type="submit" name="bttresumen" > <img src="../img/cuadernorojo.png" alt=""/>  <BR>RESUMEN EMPLEADO</button>
               </form> </center>';
         
     }
    //put your code here
     
     
  public function mostrarCrear() {
    echo '<center><h1>Agregar empleado</h1>
    <form method="POST">
    <style>
        input[type="number"]{
            font-size: 14px;  width: 60px;
        }
        table {
            width: 80%;
        }
        td {
            padding: 10px;
        }
    </style><center>
    <table border="1" class="table table-striped" style="width:80%;">
        <tr>
            <td>
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required>
            </td>
            <td>
                <label for="apellido">Apellido:</label>
                <input type="text" id="apellido" name="apellido" required>
            </td>
        </tr>
        <tr>
            <td colspan=2>
                <label for="nombrecompleto">Apellidos y nombres completos:</label>
                <input type="text" id="nombrecompleto" name="nombrecompleto" style="width:70%;" required>
            </td>
            </tr>
        <tr>
            <td>
                <label for="fecha_nacimiento">Fecha de nacimiento:</label>
                <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required>
            </td>
            <td>
                <label for="cedula_identidad">Cédula de identidad:</label>
                <input type="text" id="cedula_identidad" name="cedula_identidad" required>
            </td>

        </tr>
        <tr>
            
            <td colspan=2>
                <label for="direccion">Dirección:</label>
                <input type="text" id="direccion" name="direccion" style="width:80%;"required>
            </td>
        </tr>
        


        <tr>
            <td>
                <label for="telefono">Teléfono:</label>
                <input type="text" id="telefono" name="telefono" required>
            </td>
            <td>
                <label for="fecha_ingreso">Fecha de ingreso:</label>
                <input type="date" id="fecha_ingreso" name="fecha_ingreso" required>
            </td>
        </tr>
        
  <tr>
            <tr>
            <td>
                <label for="celular">Celular:</label>
                <input type="text" id="celular" name="celular" >
            </td>
            <td>
                <label for="correo">Correo electrónico:</label>
                <input type="email" id="correo" name="correo" required>
            </td>
        </tr>
        
  <tr>   
            <td colspan=2>
               <center><h2>Datos para rol de pagos</h2></center>
               
            </td>
        </tr>

        <tr>
            <td>
                <label for="salario_base">Salario base:</label>
                <input type="number" id="salario_base" name="salario_base" value=0 step="0.01" min="0" required>
            </td>
            <td>
                <label for="horassuple">Horas suplementarias:$</label>
                <input type="number" step="0.01" name="horassuple" value=0 id="horassuple">
            </td>
        </tr>
        <tr>
            <td>
                <label for="horas_extras">Horas extras:$</label>
                <input type="number" step="0.01" value=0 name="horasextras" id="horas_extras">
            </td>
            <td>
                <label for="dec3">Decimo Tercer Sueldo:$</label>
                <input type="number" step="0.01" value=0 name="dec3" id="dec3">
                <select name="mdec3">
                    <option value="1">Mensualizado</option>
                    <option value="2">Ahorro</option>
                </select>
            </td>
            
        </tr>
        <tr>
            <td>
                <label for="iesspat">Aporte Patronal al IESS:$</label>
                <input type="number" step="0.01" value=0 name="iesspat" id="iesspat">
            </td>
            
           
 <td>
                <label for="dec4">Decimo Cuarto Sueldo:$</label>
                <input type="number" step="0.01" value=0 name="dec4" id="dec4">
                <select name="mdec4">
                    <option value="1">Mensualizado</option>
                    <option value="2">Ahorro</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>
                <label for="iessemp">Aporte del Empleado al IESS:$</label>
                <input type="number" step="0.01" value=0 name="iessemp" id="iessemp">
            </td>
            <td>
                <label for="iessfond">Fondos de reserva:$</label>
                <input type="number" step="0.01" value=0 name="iessfond" id="iessfond">
                <select name="miessfond">
                    <option value="1">Mensualizado</option>
                    <option value="2">Ahorro</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>
                <label for="cargo">Cargo:</label>
                <input type="text" id="cargo" name="cargo" required>
            </td>
            <td>
                <label for="departamento">Departamento:</label>
                <input type="text" id="departamento" name="departamento" required>
            </td>
        </tr>
    </table></center>
    <input type="submit" name=bttnuevo value="Agregar">
    </form></center>';
}



   public function nuevo($param) {
        
  $nombre = $_REQUEST['nombre'];
  $apellido = $_REQUEST['apellido'];
  $fecha_nacimiento = $_REQUEST['fecha_nacimiento'];
  $cedula_identidad = $_REQUEST['cedula_identidad'];
  $direccion = $_REQUEST['direccion'];
  $telefono = $_REQUEST['telefono'];
  $fecha_ingreso = $_REQUEST['fecha_ingreso'];
  $salario_base = $_REQUEST['salario_base'];
  $cargo = $_REQUEST['cargo'];
  $departamento = $_REQUEST['departamento'];
  //$compensacion_salarial = $_REQUEST['compensacion_salarial'];
    $horasextras = $_REQUEST['horasextras'];
    $nombrecompleto = $_REQUEST['nombrecompleto'];
  $celular = $_REQUEST['celular'];
$correo = $_REQUEST['correo'];
$horassuple = $_REQUEST['horassuple'];

    $dec3 = $_REQUEST['dec3'];
    $mdec3 = $_REQUEST['mdec3'];
    $dec4 = $_REQUEST['dec4'];
    $mdec4 = $_REQUEST['mdec4'];
    $iesspat = $_REQUEST['iesspat'];
    $iessemp = $_REQUEST['iessemp'];
    $iessfond = $_REQUEST['iessfond'];
    $miessfond = $_REQUEST['miessfond'];
  
   $sql = "INSERT INTO \"EMPLEADOS\" (nombre, apellido, nombrecompleto, fecha_nacimiento, cedula_identidad, direccion, telefono, fecha_ingreso, salario_base,  horasExtras, cargo, departamento, dec3, mdec3, dec4, mdec4, iesspat, iessemp, iessfond, miessfond, celular, correo, horassuple) 
                           VALUES ('$nombre', '$apellido', '$nombrecompleto', '$fecha_nacimiento', '$cedula_identidad', '$direccion', '$telefono', '$fecha_ingreso', '$salario_base',  '$horasextras', '$cargo', '$departamento', '$dec3', '$mdec3', '$dec4', '$mdec4', '$iesspat', '$iessemp', '$iessfond', '$miessfond', '$celular', '$correo', '$horassuple')";

    if($res1=$this->consulta($sql)){
        echo "<div class=mesajeok >Nuevo dato registrado</div>";
    }else{
        echo "<div class=errores >Error al crear el nuevo dato BDD ".$sql." - ".pg_result_error($res1)."</div>";
    }    
         
     }   
     
          public function buscar($txtbuscar){
         
          echo '  <form> <center>
        <table border="1" class=table style="width:50%">
            <tr>
                <th>Id</th><th>Detalle</th><th>Acción</th>
            </tr>';
    

      //   $fotos= $ani->mostrarFotosAnimal($a['id'],300);
        
        
        
         $con=$this->consulta('select * from "EMPLEADOS" where apellido ilike \''.addslashes($txtbuscar).'%\' and idhac='.$_SESSION['idhac'].' order by apellido');
    

        while($a=$this->row($con)){
                    
        echo '<tr>
                <td><h2>'.$a['idemp'].'</h2></td><td>'.$a['apellido'].' '.$a['nombre'].'</td><td><button name=bttsel value='.$a['idemp'].'> <img src=../img/modif.jpg  > <br>Seleccionar</button></td>
                    <td><button name=btteli onclick="javascript: return confirm(\'Esta seguro de Eliminar la Empleado\');" value='.$a['idemp'].'><img src=../img/cancelar.jpg  > <br>Eliminar</button></td>
            </tr>';
    }
    
    echo '</table>
        
    </center></form>';
    }
   public function mostrarModificar($id){
    $con = $this->consulta('SELECT * FROM "EMPLEADOS" WHERE idemp='.$id);

    if ($a = $this->row($con)) {
        echo '<center><h1>Actualizar empleado</h1>
        <form  method="POST">
        <style>
            input[type="number"] {
                font-size: 14px;
                width: 60px;
            }
            table {
                width: 80%;
            }
            td {
                padding: 10px;
            }
        </style>
        <center>
        <table border="1" class="table table-striped">
            <input type="hidden" name="idemp" value="'.$a['idemp'].'">
            <tr>
                <td>
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" value="'.$a['nombre'].'" required>
                </td>
                <td>
                    <label for="apellido">Apellido:</label>
                    <input type="text" id="apellido" name="apellido" value="'.$a['apellido'].'" required>
                </td>
            </tr>
            <tr>
                <td colspan=2>
                    <label for="nombrecompleto">Apellidos y nombres completos:</label>
                    <input type="text" id="nombrecompleto" name="nombrecompleto" value="'.$a['nombrecompleto'].'" style="width:70%;" required>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="fecha_nacimiento">Fecha de nacimiento:</label>
                    <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="'.$a['fecha_nacimiento'].'" required>
                </td>
                <td>
                    <label for="cedula_identidad">Cédula de identidad:</label>
                    <input type="text" id="cedula_identidad" name="cedula_identidad" value="'.$a['cedula_identidad'].'" required>
                </td>
            </tr>
            <tr>
                <td colspan=2>
                    <label for="direccion">Dirección:</label>
                    <input type="text" id="direccion" name="direccion" value="'.$a['direccion'].'" style="width:80%;" required>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="telefono">Teléfono:</label>
                    <input type="text" id="telefono" name="telefono" value="'.$a['telefono'].'" required>
                </td>
                <td>
                    <label for="fecha_ingreso">Fecha de ingreso:</label>
                    <input type="date" id="fecha_ingreso" name="fecha_ingreso" value="'.$a['fecha_ingreso'].'" required>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="celular">Celular:</label>
                    <input type="text" id="celular" name="celular" value="'.$a['celular'].'">
                </td>
                <td>
                    <label for="correo">Correo electrónico:</label>
                    <input type="email" id="correo" name="correo" value="'.$a['correo'].'" required>
                </td>
            </tr>
            <tr>
                <td colspan=2>
                   <center><h2>Datos para rol de pagos</h2></center>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="salario_base">Salario base:</label>
                    <input type="number" id="salario_base" name="salario_base" value="'.$a['salario_base'].'" step="0.01" min="0" required>
                </td>
                <td>
                    <label for="horassuple">Horas suplementarias:$</label>
                    <input type="number" step="0.01" name="horassuple" id="horassuple" value="'.$a['horassuple'].'">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="horas_extras">Horas extras:$</label>
                    <input type="number" step="0.01" name="horas_extras" id="horas_extras" value="'.$a['horasextras'].'">
                </td>
                <td>
                    <label for="dec3">Decimo Tercer Sueldo:$</label>
                    <input type="number" step="0.01" name="dec3" id="dec3" value="'.$a['dec3'].'">
                    <select name="mdec3">
                        <option value="1" '.($a['mdec3'] == 1 ? "selected" : "").'>Mensualizado</option>
                        <option value="2" '.($a['mdec3'] == 2 ? "selected" : "").'>Ahorro</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="iesspat">Aporte Patronal al IESS:$</label>
                    <input type="number" step="0.01" name="iesspat" id="iesspat" value="'.$a['iesspat'].'">
                </td>
                <td>
                    <label for="dec4">Decimo Cuarto Sueldo:$</label>
                    <input type="number" step="0.01" name="dec4" id="dec4" value="'.$a['dec4'].'">
                    <select name="mdec4">
                        <option value="1" '.($a['mdec4'] == 1 ? "selected" : "").'>Mensualizado</option>
                        <option value="2" '.($a['mdec4'] == 2 ? "selected" : "").'>Ahorro</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="iessemp">Aporte del Empleado al IESS:$</label>
                    <input type="number" step="0.01" name="iessemp" id="iessemp" value="'.$a['iessemp'].'">
                </td>
                <td>
                    <label for="iessfond">Fondos de reserva:$</label>
                    <input type="number" step="0.01" name="iessfond" id="iessfond" value="'.$a['iessfond'].'">
                    <select name="miessfond">
                        <option value="1" '.($a['miessfond'] == 1 ? "selected" : "").'>Mensualizado</option>
                        <option value="2" '.($a['miessfond'] == 2 ? "selected" : "").'>Ahorro</option>
                    </select>
                </td>
            </tr>
            <tr>
                 <td>
                <label for="cargo">Cargo:</label>
                <input type="text" id="cargo" name="cargo" value="'.$a['cargo'].'" required>
            </td>
            <td>
                <label for="departamento">Departamento:</label>
                <input type="text" id="departamento" name="departamento" value="'.$a['departamento'].'" required>
            </td>



            <tr>
                <td colspan=2>
                    <input type="submit" name=bttmod value="Guardar">
                </td>
            </tr>
        </table>
        </center>
        </form></center>';
    }
}

    
public function Modificar($datos){
    $consulta = "UPDATE \"EMPLEADOS\" SET ";
    $consulta .= "nombre = '{$datos['nombre']}', ";
    $consulta .= "apellido = '{$datos['apellido']}', ";
    $consulta .= "nombrecompleto = '{$datos['nombrecompleto']}', ";
    $consulta .= "fecha_nacimiento = '{$datos['fecha_nacimiento']}', ";
    $consulta .= "cedula_identidad = '{$datos['cedula_identidad']}', ";
    $consulta .= "direccion = '{$datos['direccion']}', ";
    $consulta .= "telefono = '{$datos['telefono']}', ";
    $consulta .= "fecha_ingreso = '{$datos['fecha_ingreso']}', ";
    $consulta .= "celular = '{$datos['celular']}', ";
    $consulta .= "correo = '{$datos['correo']}', ";
    $consulta .= "salario_base = {$datos['salario_base']}, ";
    $consulta .= "horassuple = {$datos['horassuple']}, ";
    $consulta .= "horasextras = {$datos['horas_extras']}, ";
    $consulta .= "dec3 = {$datos['dec3']}, ";
    $consulta .= "mdec3 = {$datos['mdec3']}, ";
    $consulta .= "iesspat = {$datos['iesspat']}, ";
    $consulta .= "dec4 = {$datos['dec4']}, ";
    $consulta .= "mdec4 = {$datos['mdec4']}, ";
    $consulta .= "iessemp = {$datos['iessemp']}, ";
    $consulta .= "iessfond = {$datos['iessfond']}, ";
    $consulta .= "miessfond = {$datos['miessfond']}, ";
    $consulta .= "cargo = '{$datos['cargo']}', ";
    $consulta .= "departamento = '{$datos['departamento']}' ";
    $consulta .= "WHERE idemp = {$datos['idemp']}";

    if ($con = $this->consulta($consulta)) {
        echo "<br>EMPLEADO MODIFICADO";
    } else {
        echo "<br>ERROR AL MODIFICAR ".$consulta;
    }
}

    public function Eliminar($idemp){
        
             if($this->consulta('delete from "EMPLEADOS"  where idemp='.$idemp)){
             echo "<div class=mesajeok >Datos Eliminados</div>";
         }else
         {
              echo "<div class=errores >Error al eliminar  de la BDD</div>";
         }
    }
}
