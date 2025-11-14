<?php
class connex 
{
    private $user;
    private $clave;
    private $servidor;
    private $db;
    private $port;
    private $conex;

    function __construct()
    {
        $this->user = 'postgres';
        $this->clave=md5('milagro');
        $this->servidor = 'localhost';
        $this->db = 'sisachu';
        $this->port = '5432';
        $this->conex='';
        $this->base = pg_connect ("dbname=sisachu user=postgres password=neolith27malc") or die("ERROR DE CONEXION");
    }
    
    public function conectar()
    {
        $this->conex = pg_connect ("dbname=sisachu user=postgres password=neolith27malc") or die("ERROR DE CONEXION");
    
        return $this->conex;
    }

    public function consulta($pConsulta)
    {
        $query = pg_query($this->conectar(),$pConsulta);
  
        return $query;

    }
    
    public function fetchAll($sql){
        
        return pg_fetch_all($sql);
        
    }
 public function fila($pConsulta)
    {
        $mostrar = pg_fetch_assoc($pConsulta);
        
        return $mostrar;
    }

    
    public function row($pConsulta)
    {
        $mostrar = pg_fetch_assoc($pConsulta);
        
        return $mostrar;
    }

    public function num_rows($pConsulta)
    {
        $consulta = pg_num_rows($pConsulta);
        return $consulta;
    }
}  
?>
