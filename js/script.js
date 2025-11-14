/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Other/javascript.js to edit this template
 */

function menu(obj){
    var menuDiv = document.getElementById('menu');
    if(menuDiv.style.display == 'block') {
        menuDiv.style.display = 'none';
    } else {
        menuDiv.style.display = 'block';
    }
    }
 function ocultarMostrar(obj){
    var menuDiv = document.getElementById(obj);
    if(menuDiv.style.display == 'block') {
        menuDiv.style.display = 'none';
    } else {
        menuDiv.style.display = 'block';
    }
    }   
    
    function mostrarDiv(obj){
    if(obj.value==1){
        document.getElementById('tipo1').style.display = 'block';
        document.getElementById('tipo2').style.display = 'none';
        document.getElementById('tipo3').style.display = 'none';
        document.getElementById('tipo4').style.display = 'none';
    }
 if(obj.value==2){
        document.getElementById('tipo1').style.display = 'none';
        document.getElementById('tipo2').style.display = 'block';
        document.getElementById('tipo3').style.display = 'none';
        document.getElementById('tipo4').style.display = 'none';
    }
     if(obj.value==3){
        document.getElementById('tipo1').style.display = 'none';
        document.getElementById('tipo2').style.display = 'none';
        document.getElementById('tipo3').style.display = 'block';
        document.getElementById('tipo4').style.display = 'none';
    }
     if(obj.value==4){
        document.getElementById('tipo1').style.display = 'none';
        document.getElementById('tipo2').style.display = 'none';
        document.getElementById('tipo3').style.display = 'none';
        document.getElementById('tipo4').style.display = 'block';
    }

}


function validateDecimal(input) {
    var value = input.value;
    var regex = /^[0-9]*\.?[0-9]*$/;
    if (!regex.test(value)) {
        alert("Por favor, introduce un número decimal válido con punto.");
        input.value = input.value.slice(0, -1);
    }
}

