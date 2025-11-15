<?php
require '../negocio/USUARIO.php';
require '../negocio/ENTREGA.php';
require '../negocio/DIARIO.php';
require '../negocio/ESTANCIA.php';
require_once("../negocio/REPRODUCCION.php");
$usu= new USUARIO();

   session_start();
if(isset($_REQUEST['bttlogin'])){
    
}else
require_once("../encabezado.php"); 


function inicio() {
  
   echo '<body>';
    echo '<div class="container text-center mt-4">';
    echo '<h1 class="mb-4">Gestión Ganadera</h1>';
    echo '<form method="POST" action="">'; // Formulario único

    echo '<div class="row gy-3">';
    // Botón: Registro de Leche
    echo '<div class="col-12">';
    echo '<button type="submit" name="accion" value="registro_leche" class="btn btn-primary w-100 menu-btn">';
    echo '<img src="../img/eleche.png" alt="Registro de Leche"> Medida tanque';
    echo '</button>';
    echo '</div>';

        // Botón: Registro de Leche
    echo '<div class="col-12">';
    echo '<button type="submit" name="accion" value="Registro_Diario" class="btn btn-primary w-100 menu-btn">';
    echo '<img src="../img/ordeno.png" alt="Registro de Leche"> Ordeño';
    echo '</button>';
    echo '</div>';
    //Registro_Entrega
    echo '<div class="col-12">';
    echo '<button type="submit" name="accion" value="Registro_Entrega" class="btn btn-primary w-100 menu-btn">';
    echo '<img src="../img/entrega.png" alt="Registro de Leche"> Entrega';
    echo '</button>';
    echo '</div>';
     // Botón: Estado Animales  Registro_Diario
    echo '<div class="col-12">';
    echo '<button type="submit" name="accion" value="estado_animales" class="btn btn-secondary w-100 menu-btn">';
    echo '<img src="../img/eleche.png" alt="Estado vacuno"> Estado animal';
    echo '</button>';
    echo '</div>';
    
    // Botón: Registro de Cria
    echo '<div class="col-12">';
    echo '<button type="submit" name="accion" value="registro_cria" class="btn btn-secondary w-100 menu-btn">';
    echo '<img src="../img/eleche.png" alt="Registro de Cria"> Nacimiento Cria';
    echo '</button>';
    echo '</div>';

    // Botón: Registro de Monta
    echo '<div class="col-12">';
    echo '<button type="submit" name="accion" value="registro_monta" class="btn btn-success w-100 menu-btn">';
    echo '<img src="../img/monta.png" alt="Registro de Monta"> Monta';
    echo '</button>';
    echo '</div>';

    // Botón: Estancia
echo '<div class="col-12">';
echo '<button type="submit" name="accion" value="estancia" class="btn btn-success w-100 menu-btn">';
echo '<img src="../img/estancia.png" alt="Estancia"> Estancia';
echo '</button>';
echo '</div>';

    
    // Botón: Trabajo en Potreros
    echo '<div class="col-12">';
    echo '<button type="submit" name="accion" value="trabajo_potreros" class="btn btn-warning w-100 menu-btn">';
    echo '<img src="../img/eleche.png" alt="Trabajo en Potreros"> Trabajo en Potreros';
    echo '</button>';
    echo '</div>';
    echo '</div>';

     // Botón adicional para ir a la versión de escritorio
    echo '<div class="footer-btn">';
    echo '<a href="menu.php" class="btn btn-dark w-100">Ir a la versión de escritorio</a>';
    echo '</div>';
    
    echo '</form>';
    echo '</div>';
}

    echo '<!DOCTYPE html>';
    echo '<html lang="en">';
    echo '<head>';
    echo '<meta charset="UTF-8">';
    echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
    echo '<title>Gestión Ganadera</title>';
    echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">';
    echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">';
    echo '<style>';
    echo 'body { background-color: #f8f9fa; }';
    echo '.menu-btn { height: 100px; font-size: 1.2rem; display: flex; align-items: center; justify-content: center; }';
    echo '.menu-btn img { max-height: 60%; margin-right: 10px; }';
    echo '</style>
   <br> <script>    
function sumar(obj1,obj2,obj3){
  
   x = obj1.value;  
   y = obj2.value;  
   suma=parseFloat(x)+parseFloat(y);  
   text= suma;  
     obj3.value = text;  
}
function restar(obj1,obj2,obj3,obj4){
  
   x = obj1.value;  
   y = obj2.value;  
   z = obj3.value;  
   suma=parseFloat(x)+parseFloat(y)+parseFloat(z);  
   text= suma;  
     obj4.value = text;  
}
</script>
      <script src="../js/script.js"></script>
      <link href="../css/style.css" rel="stylesheet" type="text/css"/>';
    echo '</head>';

?>


<?php
$leche=new LECHE();
$repro=new REPRODUCCION();
$diario =new DIARIO();
$entrega=new ENTREGA(); 
$estancia = new ESTANCIA();

IF (isset($_REQUEST['bttnuevoregleche'])){    $leche->nuevo($_REQUEST);}


if(isset($_REQUEST['bttRegistrarCria'])){
    $d['id']=$repro->crearCria($_POST);
    
    $repro->subirFoto($d,$_FILES);
    
    
    $repro->registrarCria(); }
//bttmodrepConfirmar
if(isset($_REQUEST['bttcrearReproduccion'])){$repro->crearReproduccion($_REQUEST);  $repro->mostrarListadoRegistroMonta($_SESSION['idhac']); }
if(isset($_REQUEST['bttmodrepConfirmar'])){$repro->modificarReproduccionConfirmar($_POST); $repro->mostrarListadoRegistroMonta($_SESSION['idhac']);}
//bttmodrepSecado
if(isset($_REQUEST['bttmodrepSecado'])){$repro->modificarReproduccionSecado($_POST); $repro->mostrarListadoRegistroMonta($_SESSION['idhac']);}
//bttmostrarDia
if(isset($_REQUEST['bttmostrarDia'])){$diario->registrarDiario($_POST);   $diario->mostrarIngresarDiarioLeche($_SESSION['idhac']); }
//bttguadarAnimalDiario
if(isset($_REQUEST['bttguadarAnimalDiario'])){$diario->guardarDiarioAnimal($_POST);   $diario->mostrarIngresarDiarioLeche($_SESSION['idhac']); }
//bttmoddiario
if(isset($_REQUEST['bttmoddiario'])){$diario->modificarDiarioAnimal($_POST);   $diario->mostrarIngresarDiarioLeche($_SESSION['idhac']); }
//bttelidiario
if(isset($_REQUEST['bttelidiario'])){$diario->eliminarDiarioAnimal($_POST);   $diario->mostrarIngresarDiarioLeche($_SESSION['idhac']); }
//bttcrearEntrega
if(isset($_REQUEST['bttcrearEntrega'])){$entrega->guardarEntregaConLeches($_POST);    $entrega->mostrarIngresarEntregaLeche($_SESSION['idhac']);  }

if(isset($_REQUEST['bttcrearest'])){
$estancia->guardarEstanciaMobile($_POST['idgru'], $_POST['idsub'], $_POST['feciniest']);

}

if (isset($_POST['bttsalidaEstancia'])) {
    $estancia->registrarSalidaEstancia($_POST['idest']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    $accion = $_POST['accion'];

    switch ($accion) {
        case 'registro_leche':
            //echo "Función de Registro de Leche activada.";
            $leche->regleche();
            // Llama a la clase o lógica relacionada con el registro de leche
            break;//
        case 'estado_animales':
           // echo "Función de Registro de Entrega activada.";
           //$leche->mostraListaReproduccion(date('Y'), date('m'));
           $leche->mostraListaReproduccionAtender(date('Y'), date('m')); 
            // Llama a la clase o lógica relacionada con el registro de entrega
            break;
        case 'registro_cria':{
            $repro->registrarCria();
       
            // Llama a la clase o lógica relacionada con el registro de entrega
            break; }
        case 'registro_monta':
            echo "Función de Registro de Monta activada.:";
          //  echo date('Y-m-d H:i:s');
            $repro->mostrarListadoRegistroMonta($_SESSION['idhac']);
            // Llama a la clase o lógica relacionada con el registro de monta
            break;
        case 'Registro_Diario':
            echo "Función de Registro diario leche";
         $diario->mostrarIngresarDiarioLeche($_SESSION['idhac']); 
            // Llama a la clase o lógica relacionada con trabajo en potreros
            break;
           case 'Registro_Entrega':
            echo "Función de Registro entrega Leche";
              
         $entrega->mostrarIngresarEntregaLeche($_SESSION['idhac']); 
            // Llama a la clase o lógica relacionada con trabajo en potreros
            break;
        
        case 'estancia':
            echo "Función de Registro estancia";
              
         $estancia->mostrarEstanciasActual($_SESSION['idhac']); 
            // Llama a la clase o lógica relacionada con trabajo en potreros
            break; 
        
        default:
            echo "Acción desconocida.";
            break;
    }
}

inicio();
?>
        </body>
</html>
