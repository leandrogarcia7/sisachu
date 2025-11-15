<?php
require_once("ENTREGA.php");
/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of CLIENTE
 *
 * @author leand
 */
class CLIENTE extends ENTREGA {
    //put your code here
    
    private $estcli=array('NO ASIGNADO','ACTIVO','INACTIVO');
    public function mostrarInicio(){
        echo '<center>
            <form>
            <b>Buscar por nombre: </b> <input type=text placeholder="GARCIA" name=txtbuscar >
            <button type="submit" name="bttbuscar" > <img src="../img/buscar.jpg" alt=""/>  <BR>BUSCAR CLIENTE</button>
            <br>
            <button type="submit" name="bttcrear" > <img src="../img/anadir.png" alt=""/>  <BR>CREAR CLIENTE</button>
            <button type="submit" name="bttresumen" > <img src="../img/cuadernorojo.png" alt=""/>  <BR>RESUMEN CLIENTE</button>
            </center>';
    }

    public function mostrarCrear(){
        echo '<center><h1>Agregar cliente</h1>
<form method="POST">

  
  <label for="nomcli">Nombre:</label>
  <input type="text" id="nomcli" name="nomcli" required><br><br>
  
  <label for="telcli">Teléfono:</label>
  <input type="text" id="telcli" name="telcli" required><br><br>
  
  <label for="celcli">Celular:</label>
  <input type="text" id="celcli" name="celcli" required><br><br>

  <!-- Campos adicionales para facturación electrónica -->
  <label for="ruccli">RUC/Cédula:</label>
  <input type="text" id="ruccli" name="ruccli" required><br><br>

  <label for="dircli">Dirección:</label>
  <input type="text" id="dircli" name="dircli" required><br><br>

  <label for="emailcli">Correo:</label>
  <input type="email" id="emailcli" name="emailcli" required><br><br>

  <label for="campo_nombre">Nombre Adicional:</label>
  <input type="text" id="campo_nombre" name="campo_nombre"><br><br>

  <label for="campo_valor">Valor Adicional:</label>
  <input type="text" id="campo_valor" name="campo_valor"><br><br>
  
  <label for="estcli">Estado:</label>
        <select name="estcli">';
        
        foreach ($this->estcli as $key => $value) {
//            $selected = $key == $a['tipmat'] ? 'selected' : '';
            echo '<option value="' . $key . '" >' . $value . '</option>';
        }

        echo '</select></td></tr>
  
  <input type="submit" name=bttnuevo value="Agregar">
</form></center>';
    }
    
    
    
     public function nuevo($datos) {
        // Armamos la sentencia de inserción con los nuevos campos requeridos para facturación electrónica.
        // Se agregan campos: ruccli, dircli, emailcli, campo_nombre y campo_valor.
        // Usamos comillas dobles para construir la consulta de inserción y poder interpolar variables PHP directamente.
        $sql = "INSERT INTO public.\"CLIENTE\" (nomcli, telcli, celcli, estcli, idhac, ruccli, dircli, emailcli, campo_nombre, campo_valor) "
             . "VALUES ('{$datos['nomcli']}', '{$datos['telcli']}', '{$datos['celcli']}', {$datos['estcli']}, {$_SESSION['idhac']}, "
             . "'{$datos['ruccli']}', '{$datos['dircli']}', '{$datos['emailcli']}', '{$datos['campo_nombre']}', '{$datos['campo_valor']}')";

        if ($this->consulta($sql)) {
            echo "Cliente creado";
        } else {
            echo "Error cliente " . $sql;
        }

        return 0;
    }

    // Función para listar todos los clientes
    public function listarClientes() {
        return $this->consulta('SELECT codcli, nomcli, telcli, celcli, estcli FROM public."CLIENTE" where idhac='.$_SESSION['idhac'])->fetchAll(PDO::FETCH_ASSOC);
    }
   public function clientesSelect() {
        $clientes = $this->consulta('SELECT codcli, nomcli, telcli, celcli, estcli FROM public."CLIENTE" where idhac='.$_SESSION['idhac'])->fetchAll(PDO::FETCH_ASSOC);
        $html = '<select name="codcli">';
        foreach ($clientes as $cliente) {
            $html .= '<option value="' . $cliente['codcli'] . '">' . $cliente['nomcli'] . '</option>';
        }
        $html .= '</select>';
        return $html;
    }
    // Función para buscar un cliente por código
  
      public function buscarCliente($txtbuscar) {
        $result = $this->consulta('SELECT codcli, nomcli, telcli, celcli, estcli FROM public."CLIENTE" WHERE idhac='.$_SESSION['idhac'].' and nomcli ILIKE ?', ['%' . $txtbuscar . '%']);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }
  public function buscar($txtbuscar){
         
          echo '  <form> <center>
        <table border="1" class=table style="width:50%">
            <tr>
                <th>Id</th><th>Detalle</th><th>Acción</th>
            </tr>';
    

      //   $fotos= $ani->mostrarFotosAnimal($a['id'],300);
        
        
        
         $con=$this->consulta('select * from "CLIENTE" where idhac='.$_SESSION['idhac'].' and nomcli ilike \''.addslashes($txtbuscar).'%\' order by nomcli');
    

        while($a=$this->row($con)){
                    
        echo '<tr>
                <td><h2>'.$a['codcli'].'</h2></td><td>'.$a['nomcli'].' </td><td><button name=bttsel value='.$a['codcli'].'> <img src=../img/modif.jpg  > <br>Seleccionar</button></td>
                    <td><button name=btteli onclick="javascript: return confirm(\'Esta seguro de Eliminar la Cliente\');" value='.$a['codcli'].'><img src=../img/cancelar.jpg  > <br>Eliminar</button></td>
            </tr>';
    }
    
    echo '</table>
        
    </center></form>';
    }
    
    
    public function mostrarModificar($id) {
        $con = $this->consulta('select * from "CLIENTE" where codcli=' . $id);
        if ($a = $this->row($con)) {
            // Mostrar formulario para modificar
            echo "<center><form><table BORDER=1><th colspan=2><center>Registro de Cliente</center>";
            echo "<tr><th>Código:</th><td><input type=hidden name=codcli value=" . $a['codcli'] . ">" . $a['codcli'] . "</td></tr>";
            echo "<tr><th>Nombre:</th><td><input type=text name=nomcli value='" . $a['nomcli'] . "'></td></tr>";
            echo "<tr><th>Teléfono:</th><td><input type=text name=telcli value=" . $a['telcli'] . "></td></tr>";
            echo "<tr><th>Celular:</th><td><input type=text name=celcli value=" . $a['celcli'] . "></td></tr>";
            // Nuevos campos para facturación electrónica
            echo "<tr><th>RUC/Cédula:</th><td><input type=text name=ruccli value='" . $a['ruccli'] . "'></td></tr>";
            echo "<tr><th>Dirección:</th><td><input type=text name=dircli value='" . $a['dircli'] . "'></td></tr>";
            echo "<tr><th>Correo:</th><td><input type=text name=emailcli value='" . $a['emailcli'] . "'></td></tr>";
            echo "<tr><th>Nombre Adicional:</th><td><input type=text name=campo_nombre value='" . $a['campo_nombre'] . "'></td></tr>";
            echo "<tr><th>Valor Adicional:</th><td><input type=text name=campo_valor value='" . $a['campo_valor'] . "'></td></tr>";
            echo "<tr><th>Estado:</th><td> <select name='estcli'>";
        
        foreach ($this->estcli as $key => $value) {
            $selected = $key == $a['estcli'] ? 'selected' : '';
            echo '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';
        }

        echo '</select></td></tr>';
            echo "<tr><th colspan=2><center><button name=bttmod class=bttmod > <img src='../img/guardar.jpg'> <br>GUARDAR</button></center> </td></tr>";
            echo "</table></form></center>";
        } else {
            echo "<div class=errores >Error al seleccionar  de la BDD Cliente</div>";
        }
    }
    
    // Función para modificar un cliente
    public function modificarCliente($datos) {
        $consulta = "UPDATE \"CLIENTE\" SET ";
        $consulta .= "nomcli = '{$datos['nomcli']}', ";
        $consulta .= "telcli = '{$datos['telcli']}', ";
        $consulta .= "celcli = '{$datos['celcli']}', ";
        // Nuevos campos añadidos para facturación electrónica
        $consulta .= "ruccli = '{$datos['ruccli']}', ";
        $consulta .= "dircli = '{$datos['dircli']}', ";
        $consulta .= "emailcli = '{$datos['emailcli']}', ";
        $consulta .= "campo_nombre = '{$datos['campo_nombre']}', ";
        $consulta .= "campo_valor = '{$datos['campo_valor']}', ";
        $consulta .= "estcli = {$datos['estcli']} ";
        $consulta .= "WHERE codcli = {$datos['codcli']}";

        if ($con = $this->consulta($consulta)) {
            echo "<br>CLIENTE MODIFICADO";
        } else {
            echo "<br>ERROR AL MODIFICAR " . $consulta;
        }
        
        
        
        
    }

    // Función para eliminar un cliente
    public function eliminarCliente($codcli) {
        return $this->consulta('DELETE FROM public."CLIENTE" WHERE codcli = '.$codcli);
    }

    // Función para mostrar clientes en un select de HTML
 
    
}
