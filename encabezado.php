<?php
date_default_timezone_set('America/Guayaquil');

if(isset($_SESSION['nomusu']))
{
	
	Echo "Bienvenido : ".$_SESSION['nomusu'];
        
     /*   ECHO "<script>
function menu(obj){
    var menuDiv = document.getElementById('menu');
    if(menuDiv.style.display == 'block') {
        menuDiv.style.display = 'none';
    } else {
        menuDiv.style.display = 'block';
    }
}</script>";
     /*/   
}
else
header("Location: /login.php");

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

