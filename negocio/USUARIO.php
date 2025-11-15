<?php
require_once("BASE.php");
/**
 * @author Leandro <leandrogarciat7@hotmail.com>
 * @abstract Mediante esta clase gestionamos los usuarios
 * 
 */
class USUARIO extends connex{
     public function loginUsuario($param){
       //  $base = new connex();
    $query="SELECT * FROM \"USUARIOS\" WHERE username='".$param['uname']."'";
    $icar=$this->consulta($query);
    $car=$this->row($icar);   
    //$password=md5($param['psw'] . $salt);
    $password=$param['psw'];
   
    if($car['username']==$param['uname'] and $car['pass']==$password and $param['psw']!='' and $param['idhac']==$car['idhac'])
                    {  
        session_destroy();
        session_start();
        $_SESSION['id']=$car['id'];
        $_SESSION['nomusu']=$car['nomusu'];
        $_SESSION['estusu']=$car['estusu'];
        $_SESSION['idhac']=$car['idhac'];
        $_SESSION['emailusu']=$car['emailusu'];
         $_SESSION['username']=$car['username'];
          $_SESSION['tipusu']=$car['tipusu'];
          
          $query = "SELECT litros_terneras, litros_machos FROM \"HACIENDA\" WHERE id = " . $_SESSION['idhac'];
$icar = $this->consulta($query);
$car = $this->row($icar);

// Guardar los valores en variables de sesión
if ($car) {
    $_SESSION['litros_terneras'] = $car['litros_terneras'];
    $_SESSION['litros_machos'] = $car['litros_machos'];
} else {
    // Opcional: Manejo de error si no se encuentra el registro
    $_SESSION['litros_terneras'] = 0;
    $_SESSION['litros_machos'] = 0;
}

          
        return true;
    }else{
        
          session_destroy();
        header("Location: ../login.php?usu=error");
        return false;
    }
    }
    
    public function mostrarMenu($codusu,$tipo){
        
       
        
        if($tipo==1){
            
            echo '<button onclick="menu(this)" class="accordion-button collapsed" type="button"><b>Mostrar Menu</b></button><div id="menu" style="display:none;">';
            
            echo '<table><td>
<div class="accordion" id="accordionExample" >
  <div class="accordion-item">
    <h2 class="accordion-header" id="headingOne">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
        ANIMALES
      </button>
    </h2>
    <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
      <div class="accordion-body">
         <br><form action=uianimales.php><button class="btn btn-default btn-lg btn-block" type=submit>ANIMALES</button></form>
          <br><form action=uireproduccion.php><button class="btn btn-default btn-lg btn-block" type=submit>REPRODUCCION</button></form> 
        <br><form action=uiraza.php><button class="btn btn-default btn-lg btn-block" type=submit>RAZA</button></form>
        <br><form action=uigrupo.php><button class="btn btn-default btn-lg btn-block" type=submit>GRUPOS</button></form>
        <br><form action=uicontroles.php><button class="btn btn-default btn-lg btn-block" type=submit>CONTROLES</button></form>
         <br><form action=uitratamientos.php><button class="btn btn-default btn-lg btn-block" type=submit>TRATAMIENTOS</button></form>
        <br><form action=uiestancia.php><button class="btn btn-default btn-lg btn-block" type=submit>ESTANCIA</button></form>
      </div>
    </div>
  </div>
  <div class="accordion-item">
    <h2 class="accordion-header" id="headingTwo">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
        CULTIVOS
      </button>
    </h2>
    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
      <div class="accordion-body">
           <br><form action=uipotreros.php><button class="btn btn-default btn-lg btn-block" type=submit>POTREROS</button></form>
        <br><form action=uimaterial.php><button class="btn btn-default btn-lg btn-block" type=submit>MATERIAL</button></form>
        <br><form action=uimaquinaria.php><button class="btn btn-default btn-lg btn-block" type=submit>MAQUINARIA</button></form>
        <br><form action=uitrabajos.php><button class="btn btn-default btn-lg btn-block" type=submit>TRABAJOS</button></form>
      </div>
    </div>
  </div>
  <div class="accordion-item">
    <h2 class="accordion-header" id="headingThree">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
        PRODUCCIÓN
      </button>
    </h2>
    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
      <div class="accordion-body">
           <br><form action=uileche.php><button class="btn btn-default btn-lg btn-block" type=submit>LECHE</button></form>
        <br><form action=uientrega.php><button class="btn btn-default btn-lg btn-block" type=submit>ENTREGA</button></form>
         <br><form action=uifactura.php><button class="btn btn-default btn-lg btn-block" type=submit>FACTURA</button></form>
        <br><form action=uitanque.php><button class="btn btn-default btn-lg btn-block" type=submit>TANQUE</button></form>
        <br><form action=uiexamenes.php><button class="btn btn-default btn-lg btn-block" type=submit>EXÁMENES</button></form>
        <br><form action=uivalidaciones.php><button class="btn btn-default btn-lg btn-block" type=submit>VALIDACIONES</button></form>
         <br><form action=uidiario.php><button class="btn btn-default btn-lg btn-block" type=submit>DIARIO DE LECHE</button></form>
      </div>
    </div>
  </div>
  
    <div class="accordion-item">
    <h2 class="accordion-header" id="headingfour">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour2" aria-expanded="true" aria-controls="collapseFour2">
        RECURSOS HUMANOS
      </button>
    </h2>
    <div id="collapseFour2" class="accordion-collapse collapse" aria-labelledby="headingfour" data-bs-parent="#accordionExample">
      <div class="accordion-body">
        <br><form action=uiproveedores.php><button class="btn btn-default btn-lg btn-block" type=submit>PROVEEDORES</button></form>
        <br><form action=uiclientes.php><button class="btn btn-default btn-lg btn-block" type=submit>CLIENTES</button></form>
        <br><form action=uiempleados.php><button class="btn btn-default btn-lg btn-block" type=submit>EMPLEADOS</button></form>
         <br><form action=uirol.php><button class="btn btn-default btn-lg btn-block" type=submit>ROL</button></form>
      </div>
    </div>
  </div>
  
    <div class="accordion-item">
    <h2 class="accordion-header" id="headingfour">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="true" aria-controls="collapseFour">
        INGRESOS Y EGRESOS
      </button>
    </h2>
    <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingfour" data-bs-parent="#accordionExample">
      <div class="accordion-body">
        <br><form action=uiingresos.php><button class="btn btn-default btn-lg btn-block" type=submit>INGRESOS</button></form>
        <br><form action=uiegresos.php><button class="btn btn-default btn-lg btn-block" type=submit>EGRESOS</button></form>
        <br><form action=uitipoie.php><button class="btn btn-default btn-lg btn-block" type=submit>TIPOS I y E</button></form> 
     <br><form action=uicuentas.php><button class="btn btn-default btn-lg btn-block" type=submit>CUENTAS</button></form>
     <br><form action=uibalance.php><button class="btn btn-default btn-lg btn-block" type=submit>BALANCE</button></form>
      </div>
    </div>
  </div>
  
     <div class="accordion-item">
    <h2 class="accordion-header" id="headingfive">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsefive" aria-expanded="true" aria-controls="collapsefive">
        HACIENDA
      </button>
    </h2>
    <div id="collapsefive" class="accordion-collapse collapse" aria-labelledby="headingfive" data-bs-parent="#accordionExample">
      <div class="accordion-body">
        <br><form action=uihacienda.php><button class="btn btn-default btn-lg btn-block" type=submit>HACIENDA</button></form>
        <br><form action=uisuscripcion.php><button class="btn btn-default btn-lg btn-block" type=submit>SUSCRIPCIÓN</button></form>
        <br><form action=menu.php><button class="btn btn-default btn-lg btn-block" type=submit>MENU PRINCIPAL</button></form>
        <br><form action=salir.php><button class="btn btn-default btn-lg btn-block" type=submit>CERRAR SESIÓN</button></form>
     
      </div>
    </div>
  </div>


</div>
<br><form action=mobile.php><button class="btn btn-default btn-lg btn-block" type=submit>MOBILE</button></form>
<img src=../img/logo.jpg style="width:100%">

<td>

</table>

';
            echo '</div>';
        }
        
       if($tipo==2){
              header("Location: /interfaces/mobile.php");
       //     echo '<button onclick="menu(this)" class="accordion-button collapsed" type="button"><b>Mostrar Menu</b></button><div id="menu" style="display:none;">';
           /* 
            echo '<table><td>
<div class="accordion" id="accordionExample" >
  <div class="accordion-item">
    <h2 class="accordion-header" id="headingOne">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
        ANIMALES
      </button>
    </h2>
    <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
      <div class="accordion-body">
         <br><form action=uianimales.php><button class="btn btn-default btn-lg btn-block" type=submit>ANIMALES</button></form>
          <br><form action=uireproduccion.php><button class="btn btn-default btn-lg btn-block" type=submit>REPRODUCCION</button></form> 
        <br><form action=uiraza.php><button class="btn btn-default btn-lg btn-block" type=submit>RAZA</button></form>
        <br><form action=uigrupo.php><button class="btn btn-default btn-lg btn-block" type=submit>GRUPOS</button></form>
        <br><form action=uicontroles.php><button class="btn btn-default btn-lg btn-block" type=submit>CONTROLES</button></form>
         <br><form action=uitratamientos.php><button class="btn btn-default btn-lg btn-block" type=submit>TRATAMIENTOS</button></form>
        <br><form action=uiestancia.php><button class="btn btn-default btn-lg btn-block" type=submit>ESTANCIA</button></form>
      </div>
    </div>
  </div>
  <div class="accordion-item">
    <h2 class="accordion-header" id="headingTwo">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
        CULTIVOS
      </button>
    </h2>
    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
      <div class="accordion-body">
           <br><form action=uipotreros.php><button class="btn btn-default btn-lg btn-block" type=submit>POTREROS</button></form>
        <br><form action=uimaterial.php><button class="btn btn-default btn-lg btn-block" type=submit>MATERIAL</button></form>
        <br><form action=uimaquinaria.php><button class="btn btn-default btn-lg btn-block" type=submit>MAQUINARIA</button></form>
        <br><form action=uitrabajos.php><button class="btn btn-default btn-lg btn-block" type=submit>TRABAJOS</button></form>
      </div>
    </div>
  </div>
  <div class="accordion-item">
    <h2 class="accordion-header" id="headingThree">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
        PRODUCCIÓN
      </button>
    </h2>
    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
      <div class="accordion-body">
           <br><form action=uileche.php><button class="btn btn-default btn-lg btn-block" type=submit>LECHE</button></form>
        <br><form action=uientrega.php><button class="btn btn-default btn-lg btn-block" type=submit>ENTREGA</button></form>
        <br><form action=uitanque.php><button class="btn btn-default btn-lg btn-block" type=submit>TANQUE</button></form>
        <br><form action=uiexamenes.php><button class="btn btn-default btn-lg btn-block" type=submit>EXÁMENES</button></form>
        <br><form action=uivalidaciones.php><button class="btn btn-default btn-lg btn-block" type=submit>VALIDACIONES</button></form>
         <br><form action=uidiario.php><button class="btn btn-default btn-lg btn-block" type=submit>DIARIO DE LECHE</button></form>
      </div>
    </div>
  </div>
  
    

</div>
<br><form action=mobile.php><button class="btn btn-default btn-lg btn-block" type=submit>MOBILE</button></form>
<img src=../img/logo.jpg style="width:100%">

<td>

</table>

';
            echo '</div>';*/
        }  
        
    }
    
    
   public function mostrarHaciendasSelect(){
       
    $query="SELECT * FROM \"HACIENDA\" WHERE esthac=1 order by id;";
    $icar=$this->consulta($query);
    $sele='<select name=idhac>';
    while($car=$this->row($icar)){
        $sele.='<option value='.$car['id'].'>'.$car['nomhac'].'</option>';
        
    }
    $sele.='</select>';   
      echo $sele;  
   } 
    
   
    
    
}