<?php
require_once("ANIMALES.php");

class POTREROS extends ANIMALES {

    public $estpot= array('POR REGISTRAR','ACTIVO','NO ACTIVO' );
    
       public $pdo; 
    public function __construct() {
        parent::__construct();         // llama al constructor de connex
        $this->pdo = $this->getPDO();  // inicializa la propiedad $pdo
    }
    
    
    
    public function mostrarInicio(){
        echo '<center>
            <form>
            <b>Buscar por nombre: </b> <input type=text placeholder="Nombre Potrero" name=txtbuscar >
            <button type="submit" name="bttbuscar"> <img src="../img/buscar.jpg" alt=""/> <br>BUSCAR POTRERO</button>
            <br>
            <button type="submit" name="bttcrear"> <img src="../img/anadir.png" alt=""/> <br>CREAR POTRERO</button>
            </center>';
    }

    public function mostrarCrear(){
        echo '<center><h1>Agregar Potrero</h1>
<form >
  <label for="nompot">Nombre:</label>
  <input type="text" id="nompot" name="nompot" required><br><br>
  
  <label for="suppot">Superficie:</label>
  <input type="text" id="suppot" name="suppot" VALUE=0 required>m2<br><br>
  
  <label for="tippot">Tipo de Terreno:</label>
  <input type="text" id="tippot" name="tippot" required><br><br>
  
  <label for="cappot">Capacidad de Ganado:</label>
  <input type="number" id="cappot" name="cappot" value=0 required><br><br>
  
  <label for="estpot">Estado:</label>
  ';

       echo ' <select id="estpot" name="estpot" required>';
       foreach ($this->estpot as $key => $value) {
           echo '<option value='.$key.'>'.$value.'</option>';
       }
  echo '</select><br><br>';
  echo '<label for="recpot">Recursos Hídricos:</label>
  <input type="text" id="recpot" name="recpot" required><br><br>

  <label for="obspot">Observaciones:</label>
  <textarea id="obspot" name="obspot"></textarea><br><br>
  <button name=bttnuevo class=bttnuevo > <img src=\'../img/guardar.jpg\'> <br>GUARDAR</button>
 
</form></center><br><br>';
    }

   public function nuevo($datos) {
    $sql = 'INSERT INTO "POTREROS" 
            (nompot, suppot, tippot, cappot, estpot, recpot, obspot, idhac) 
            VALUES 
            (:nompot, :suppot, :tippot, :cappot, :estpot, :recpot, :obspot, :idhac)';

    $stmt = $this->prepare($sql);

    $stmt->bindParam(':nompot', $datos['nompot']);
    $stmt->bindParam(':suppot', $datos['suppot']);
    $stmt->bindParam(':tippot', $datos['tippot']);
    $stmt->bindParam(':cappot', $datos['cappot'], PDO::PARAM_INT);
    $stmt->bindParam(':estpot', $datos['estpot'], PDO::PARAM_INT);
    $stmt->bindParam(':recpot', $datos['recpot']);
    $stmt->bindParam(':obspot', $datos['obspot']);
    $stmt->bindParam(':idhac', $_SESSION['idhac'], PDO::PARAM_INT);

    try {
        $stmt->execute();
        echo "<div class='ok'>✅ Potrero creado correctamente.</div>";
        return true;
    } catch (PDOException $e) {
        echo "<div class='errores'>❌ Error al crear potrero: " . $e->getMessage() . "</div>";
        return false;
    }
}


    public function buscar($txtbuscar){
      
         
          echo '  <form> <center>
        <table border="1" class=table style="width:50%">
            <tr>
                <th>Potrero nombre</th><th>Acción</th>
            </tr>';
    

      //   $fotos= $ani->mostrarFotosAnimal($a['id'],300);
        
        
        
         $con=$this->consulta('select * from "POTREROS" where idhac='.$_SESSION['idhac'].' and nompot ilike \''.addslashes($txtbuscar).'%\' order by nompot');
    

        while($a=$this->row($con)){
                    
        echo '<tr>
                <td>'.$a['nompot'].' </td><td><button name=bttsel value='.$a['idpot'].'> <img src=../img/modif.jpg  > <br>Seleccionar</button></td>
                    <td><button name=btteli onclick="javascript: return confirm(\'Esta seguro de Eliminar la Potrero\');" value='.$a['idpot'].'><img src=../img/cancelar.jpg  > <br>Eliminar</button></td>
            </tr>';
    }
    
    echo '</table>
        
    </center></form>';
   
    }

    public function mostrarModificar($id) {
             $con = $this->consulta('select * from "POTREROS" where idpot=' . $id.' and idhac='.$_SESSION['idhac']);
        if ($a = $this->row($con)) {
            // Mostrar formulario para modificar
            echo "<center><form><table BORDER=1><th colspan=2><center>Registro de Potrero</center>";
            //echo "<input type=hidden name=idpot value=" . $a['idpot'] . ">";
            echo "<tr><th>Nombre:</th><td><input type=hidden name=idpot value=" . $a['idpot'] . "><input type=text name=nompot value='" . $a['nompot'] . "'></td></tr>";
            echo "<tr><th>Superficie:</th><td><input type=text name=suppot value=" . $a['suppot'] . "></td></tr>";
            echo "<tr><th>Tipo de Terreno:</th><td><input type=text name=tippot value=" . $a['tippot'] . "></td></tr>";
            echo "<tr><th>Capacidad de Ganado:</th><td><input type=text name=cappot value=" . $a['cappot'] . "></td></tr>";
              echo ' <tr><th>Estado:<td><select id="estpot" name="estpot" required>';
       foreach ($this->estpot as $key => $value) {
           $s='';
           if($key==$a['estpot']) $s=' selected=selected ';
           echo '<option value='.$key.' '.$s.' >'.$value.'</option>';
       }
  echo '</select>';
             echo "<tr><th>Recursos Hídricos:</th><td><input type=text name=recpot value=" . $a['recpot'] . "></td></tr>";
              echo "<tr><th>Observaciones:</th><td><input type=text name=obspot value=" . $a['obspot'] . "></td></tr>";
              
            echo "<tr><th colspan=2><center><button name=bttmod class=bttmod > <img src='../img/guardar.jpg'> <br>GUARDAR</button></center> </td></tr>";
            echo "</table></form></center>";
        } else {
            echo "<div class=errores >Error al seleccionar  de la BDD Cliente</div>";
        }
    }

    public function modificarPotrero($datos) {
        // Implementación similar a la de la función modificarCliente de la clase CLIENTE
        // ,  cappot, estpot, recpot, obspot
            $consulta = "UPDATE \"POTREROS\" SET ";
        $consulta .= "nompot = '{$datos['nompot']}', ";
        $consulta .= "suppot = '{$datos['suppot']}', ";
        $consulta .= "tippot = '{$datos['tippot']}', ";
        $consulta .= "cappot = '{$datos['cappot']}', ";
        $consulta .= "recpot = '{$datos['recpot']}', ";
        $consulta .= "obspot = '{$datos['obspot']}', ";
        $consulta .= "estpot = {$datos['estpot']} ";
        $consulta .= "WHERE idpot = {$datos['idpot']}";

        if ($con = $this->consulta($consulta)) {
            echo "<br>POTRERO MODIFICADO";
        } else {
            echo "<br>ERROR AL MODIFICAR " . $consulta;
        }
        
        
        
    }

   public function eliminarPotrero($idPotrero) {
    $sql = 'DELETE FROM "POTREROS" WHERE idpot = :idpot AND idhac = :idhac';

    $stmt = $this->prepare($sql);
    $stmt->bindParam(':idpot', $idPotrero, PDO::PARAM_INT);
    $stmt->bindParam(':idhac', $_SESSION['idhac'], PDO::PARAM_INT);

    try {
        $stmt->execute();
        echo "<div class='ok'>🗑️ Potrero eliminado correctamente.</div>";
    } catch (PDOException $e) {
        echo "<div class='errores'>❌ Error al eliminar potrero: " . $e->getMessage() . "</div>";
    }
}


    // Otros métodos que puedan ser necesarios
}
?>
