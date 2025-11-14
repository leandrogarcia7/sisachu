<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of potrero
 *
 * @author LGT-5
 */
class potrero {
    //put your code here
    
 // Función para crear un nuevo potrero
    public function crearPotrero($nombre_potrero, $tamanio_hectareas, $ubicacion, $fecha_siembra, $tipo_cultivo, $rendimiento, $estado) {
        $query = "INSERT INTO potrero (nombre_potrero, tamanio_hectareas, ubicacion, fecha_siembra, tipo_cultivo, rendimiento, estado) VALUES ('$nombre_potrero', $tamanio_hectareas, '$ubicacion', '$fecha_siembra', '$tipo_cultivo', $rendimiento, '$estado')";

        $result = pg_query($this->conn, $query);

        if (!$result) {
            return false;
        } else {
            return true;
        }
    }   
     public function modificarPotrero($id_potrero, $nombre_potrero, $tamanio_hectareas, $ubicacion, $fecha_siembra, $tipo_cultivo, $rendimiento, $estado) {
        $query = "UPDATE potrero SET nombre_potrero = '$nombre_potrero', tamanio_hectareas = $tamanio_hectareas, ubicacion = '$ubicacion', fecha_siembra = '$fecha_siembra', tipo_cultivo = '$tipo_cultivo', rendimiento = $rendimiento, estado = '$estado' WHERE id_potrero = $id_potrero";

        $result = pg_query($this->conn, $query);

        if (!$result) {
            return false;
        } else {
            return true;
        }
    }  
    
      public function eliminarPotrero($id_potrero) {
        $query = "DELETE FROM potrero WHERE id_potrero = $id_potrero";

        $result = pg_query($this->conn, $query);

        if (!$result) {
            return false;
        } else {
            return true;
        }
    }
    
    
    
}
