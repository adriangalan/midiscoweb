<?php

// Guardo la salida en un buffer(en memoria)
// No se envia al navegador
$auto = $_SERVER['PHP_SELF'];
ob_start();
// FORMULARIO DE ALTA DE USUARIOS
?>
<div id='aviso'><b><?= (isset($msg))?$msg:"" ?></b></div>
<form name='ALTA' method="POST" action="index.php?orden=Alta">
<div class="center">
	<h1>Alta de  Usuario</h1>
</div>
Identificador: <input name="identificador" type="text"  required><br>
<?=(isset($_GET['error']) && $_GET['error']=="identificador")?"<p>El identificador  no es valido<p>": "" ?>
Nombre: <input name="nombre" type="text"  required><br>
<?=(isset($_GET['error']) && $_GET['error']=="nombre")?"<p>El nombre no es valido<p>": "" ?>
Correo electronico: <input name="correo" type="text" required><br>
<?=(isset($_GET['error']) && $_GET['error']=="correo")?"<p>El correo no es valido<p>": "" ?>
Contraseña:<input name="clave" type="password"  size=20  required>	<br>
<?=(isset($_GET['error']) && $_GET['error']=="clave")?"<p>La clave no es valido<p>": "" ?>
Repetir Contraseña:<input name="claveRepetida" type="password"  size=20 required><br>
<?=(isset($_GET['error']) && $_GET['error']=="claveRepetida")?"<p>La clave no es la misma<p>": "" ?>
Plan :<br>
<select name="plan" size="2">
	<option value="0" selected>Basico</option>
	<option value="1" >Profesional</option>
	<option value="2" >Premium</option>
</select><br>
Estado :<br>
<select name="estado" size="2">
	<option value="A" >Activo</option>	
	<option value="B" selected>Bloqueado</option>
	<option value="I" >Desactivado</option>
</select> <br> 
	<input type="submit" value="Alta">			
	<button><a href="<?= $auto?>?orden=VerUsuarios">cancelar</a></button>
</form>
<?php 
// Vacio el bufer y lo copio a contenido
// Para que se muestre en div de contenido
$contenido = ob_get_clean();
include_once "principal.php";

?>