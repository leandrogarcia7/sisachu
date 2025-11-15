<?php
require_once("../negocio/BALANCE.php");
function crearCliente($nomcli){
$bla=new BALANCE();
$result=$bla->crearCliente($nomcli);
echo '<select id=codcli name=codcli onchange=document.crearing.bttnueving.disabled=false;><option value=0>Ingresado Seleccione</option>';
//         $result=$bal->listarClientes();
        while($reg=  pg_fetch_assoc($result)){
            echo '<option  value='.$reg['codcli'].'>'.$reg['nomcli'].'</option>';
        }
        echo '</select><br><input type=text id=nomcli><input type=button value=CREAR onclick=crearCliente(document.crearing.nomcli); >';
}
function  mostrarSubcategoria($codcat){
    $bla=new BALANCE();
    echo '<select id=codsub name=codsub>';
         $result=$bla->listarSubCategorias($codcat);
        while($reg=  pg_fetch_assoc($result)){
            echo '<option  value='.$reg['codsub'].'>'.$reg['detsub'].'</option>';
        }
        echo '</select>';
}
function mostrarMonto($codsub){
    $bla=new BALANCE();
$reg=$bla->mostrarSubcategori($codsub);
echo $reg['cossub'];
}
if(isset($_REQUEST['nomcli'])){
    crearCliente($_REQUEST['nomcli']);
}

if(isset($_REQUEST['codcat'])){
    mostrarSubcategoria($_REQUEST['codcat']);
}
if(isset($_REQUEST['lcodsub'])){
    mostrarMonto($_REQUEST['lcodsub']);
}
?>