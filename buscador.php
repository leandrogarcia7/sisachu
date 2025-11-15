
<?php
// URL de la página web que deseas revisar
require 'negocio/USUARIO.php';
$usu= new USUARIO();

if(isset($_REQUEST['revisar'])){
    $sql = "SELECT numero_inicio, url FROM PRUEBA WHERE tipo = 'Encontrado'";
$result = $usu->consulta($sql);   
  
    if (!$result) {
        echo "Error en la consulta: " . pg_last_error($conn);
        pg_close($conn);
        return;
    }

    // Mostrar los resultados en una tabla HTML
    if (pg_num_rows($result) > 0) {
        echo "<table border='1'>";
        echo "<tr><th>Número</th><th>URL</th></tr>";

        while ($row = pg_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>{$row['numero_inicio']}</td>";
            echo "<td><a href='{$row['url']}' target='_blank'>{$row['url']}</a></td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
       echo "No se encontraron resultados.";
    }    
    
}else{
    





$sql = "SELECT numero_inicio, tipo FROM PRUEBA ORDER BY id DESC LIMIT 1";
$result = $usu->consulta($sql);

if (pg_num_rows($result) > 0) {
    // Obtener el número de inicio y tipo
    $row = pg_fetch_assoc($result);
    $a = $row["numero_inicio"];
    $tipo = $row["tipo"];
} else {
    echo "No se encontraron registros en la base de datos.";
    exit();
}

if($a>3032818){exit();
}else{
for ($i = 0; $i < 200; $i++) {
    $b = $a + $i;

    $url = "http://atencionciudadana.educacion.gob.ec/publico_consulta_usuario/" . $b;

    // Obtener el contenido HTML de la página web
    $html = file_get_contents($url);

    // Nombre que deseas buscar en el HTML
    $nombre = "XIMENA LEMOS";

    // Verificar si el nombre está presente en el HTML
    if (strpos($html, $nombre) !== false) {
        echo "<br><a target='_blank' href='" . $url . "'>link " . $b . " </a>";

        // Guardar los resultados en la base de datos
        $sql_insert = "INSERT INTO prueba (numero_inicio, tipo, url) VALUES ($b, 'Encontrado', '$url')";
        $usu->consulta($sql_insert);
      
    }
     $nombre = "RINCON DEL SABER";
    
      if (strpos($html, $nombre) !== false) {
        echo "<br><a target='_blank' href='" . $url . "'>link " . $b . " </a>";

        // Guardar los resultados en la base de datos
        $sql_insert = "INSERT INTO prueba (numero_inicio, tipo, url) VALUES ($b, 'Encontrado', '$url')";
        $usu->consulta($sql_insert);
      
    }
     $nombre = "RINCÓN DEL SABER";
    
      if (strpos($html, $nombre) !== false) {
        echo "<br><a target='_blank' href='" . $url . "'>link " . $b . " </a>";

        // Guardar los resultados en la base de datos
        $sql_insert = "INSERT INTO prueba (numero_inicio, tipo, url) VALUES ($b, 'Encontrado', '$url')";
        $usu->consulta($sql_insert);
      
    }
    
    
    
    
    
    
    
}

 $sql_insert = "INSERT INTO prueba (numero_inicio, tipo, url) VALUES ($b, 'Fin', '')";
        $usu->consulta($sql_insert);


}

}
?>

