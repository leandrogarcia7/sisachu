<?php
require 'negocio/USUARIO.php';
$usu= new USUARIO();

function inicial() {
  $inicial='';
  
  echo $inicial;
}
function login(){
    echo '<div id=contenido>';
    
    echo '</div>';
}
?>
<html>
<head>
  <title>Login</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="generator" content="Bluefish 1.0.7">
  <LINK REL="StyleSheet" HREF="../css/style.css">
 </LINK> 
</head>
<style>
form {
    border: 3px solid #f1f1f1;
}

input[type=text], input[type=password] {
    width: 100%;
    padding: 12px 20px;
    margin: 8px 0;
    display: inline-block;
    border: 1px solid #ccc;
    box-sizing: border-box;
}
select  {
    width: 100%;
    padding: 12px 20px;
    margin: 8px 0;
    display: inline-block;
    border: 1px solid #ccc;
    box-sizing: border-box;
}

button {
    background-color: #4CAF50;
    color: white;
    padding: 14px 20px;
    margin: 8px 0;
    border: none;
    cursor: pointer;
    width: 100%;
}

button:hover {
    opacity: 0.8;
}

.cancelbtn {
    width: auto;
    padding: 10px 18px;
    background-color: #f44336;
}

.imgcontainer {
    text-align: center;
    margin: 24px 0 12px 0;
}

img.avatar {
    width: 40%;
    border-radius: 50%;
}

.container {
    padding: 16px;
}

span.psw {
    float: right;
    padding-top: 16px;
}

/* Change styles for span and cancel button on extra small screens */
@media screen and (max-width: 300px) {
    span.psw {
       display: block;
       float: none;
    }
    .cancelbtn {
       width: 100%;
    }
}
</style>
</head>
<body>

 <center>   <div id="contenido" style="width:50%">
         <form action="interfaces/menu.php" method="POST">
  <div class="imgcontainer">
      <img src="img/login.png" alt="Avatar" class="avatar">
  </div>

  <div class="container">
    <label><b>Username</b></label>
    <input type="text" placeholder="Enter Username" name="uname" required>

    <label><b>Password</b></label>
    <input type="password" placeholder="Enter Password" name="psw" required>
<?php
$usu->mostrarHaciendasSelect();
?>
    <button type="submit" name="bttlogin">Login</button>

  </div>

  <div class="container" style="background-color:#f1f1f1">
    <button type="button" class="cancelbtn">Cancel</button>
    <span class="psw">Olvidaste el <a href="#">password?</a></span>
  </div>
</form>
    </div></center>  
<?php

if(isset($_REQUEST['usu'])){
    echo "<h2>Error en las credenciales de acceso</h2>";
}

if(isset($_REQUEST)){
    login();
}

inicial();
?>
</body>
</html>
