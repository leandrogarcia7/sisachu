
<?php
require_once("BASE.php");
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BALANCE
 *
 * @author Leandro
 */
class BALANCE extends connex {
    //put your code here
    
  public function listarCategorias(){
      $con= new connex();
      $pConsulta=' SELECT codcat, detcat, estcat, tipcat   FROM "CATEGORIA" where estcat=1 order by codcat';
     $resul= $con->consulta($pConsulta);
     return $resul;
  }
  
   public function listarSubCategorias($codcat){
      $con= new connex();
      $pConsulta=' SELECT *  FROM "SUBCATEGORIA" where codcat='.$codcat.' and estsub=1 order by codsub';
     $resul= $con->consulta($pConsulta);
     return $resul;
  }
    public function listarClientes(){
      $con= new connex();
        $pConsulta=' SELECT *   FROM "CLIENTE" where estcli=1 order by nomcli';
     $resul= $con->consulta($pConsulta);
     return $resul;
  }
  
  public function crearCliente($nomcli){
       $con= new connex();
        $pConsulta=' INSERT INTO "CLIENTE"(nomcli, estcli)
    VALUES (\''.$nomcli.'\', 1);';
     $resul= $con->consulta($pConsulta);
     $pConsulta=' SELECT *   FROM "CLIENTE" where estcli=1 order by nomcli';
     $resul= $con->consulta($pConsulta);
         
     return $resul;
  }
  public function mostrarSubcategori($codsub){
      $con= new connex();
      $pConsulta=' SELECT *  FROM "SUBCATEGORIA" where codsub='.$codsub.' ;';
     $resul= $con->consulta($pConsulta);
     $reg=  pg_fetch_assoc($resul);
      return $reg;
  }
  public function crearIngreso($datos){
         $con= new connex();
        $pConsulta=' INSERT INTO public."INGRESOS"(
	 moning, aboing, esting, fecing, codcli, codcat, fecent,  codcueh)
	   VALUES (\''.$datos['moning'].'\', 0, 1, \''.$datos['fecing'].'\', '.$datos['codcli'].', '.$datos['codcat'].',  \''.$datos['fecent'].'\', '.$datos['codcueh'].');';
     $resul= $con->consulta($pConsulta);
     return $resul;
  }
  public function listarCuentas(){
      
      $pConsulta=' SELECT *   FROM "CUENTA"  order by nivel1cue,nivel2cue,nivel3cue,nivel4cue,nivel5cue';
     $resul= $this->consulta($pConsulta);
       return $resul;
  }
 public function crearAbonoIngreso($datos){
         $pConsulta='INSERT INTO public."INGRESO_ABONO"(
	 coding, codusu, montoaboing, fecaboing, estaboing, codcued)
	   VALUES ('.$datos['coding'].','.$datos['codusu'].' ,  \''.$datos['montoaboing'].'\',  \''.$datos['fecaboing'].'\',1, '.$datos['codcued'].' );';
     if($resul= $this->consulta($pConsulta)){
         
         $abono= round($datos['aboing'],2)+ round($datos['montoaboing'],2);
         $sql='update "INGRESOS" set aboing='.$abono.' where coding='.$datos['coding'].' ';
         $this->consulta($sql);
     //    echo $sql;
         
         echo '<div class="alert alert-success"> <center><strong>Creado Ingreso</strong></center></div>';
     }else{
        echo '<div class="alert alert-danger"><center><strong>Peligro!</strong> Error al crear ingreso</center></div>';
     }
     
     
     
     return $resul;
     
     
     
 } 
}

