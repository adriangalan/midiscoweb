<?php

// Guardo la salida en un buffer(en memoria)
// No se envia al navegador
$auto = $_SERVER['PHP_SELF'];
ob_start();
// FORMULARIO DE ALTA DE USUARIOS


?>
<div id='aviso'><b><?= (isset($msg))?$msg:"" ?></b></div>
<form name='MODIFICAR' method="POST" action="index.php?orden=Modificar&id=<?=$id?>">
<div class="center">
	<h1>Modificar Usuario</h1>
</div>
Identificador: <?=$id?> <br>
Nombre:<input name="nombre" type="text" value="<?=$usuarios[1] ?>" required><br>
<?=(isset($_GET['error']) && $_GET['error']=="nombre")?"<p>El correo no es valido<p>": "" ?>
Correo electronico: <input name="correo" type="text" value="<?=$usuarios[2] ?>" required><br>
<?=(isset($_GET['error']) && $_GET['error']=="correo")?"<p>El correo no es valido<p>": "" ?>
Contraseña:<input name="clave" type="password" value="<?=$usuarios[0] ?>" size=20>	<br>
<?=(isset($_GET['error']) && $_GET['error']=="clave")?"<p>La clave no es valido<p>": "" ?>
Estado :<br>
<select name="estado" size="2">
	<option value="A" <?=($_SESSION['tusuarios'][$id][4]=="A")?"selected":"" ?>>Activo</option>	
	<option value="B"  <?=($_SESSION['tusuarios'][$id][4]=="B")?"selected":"" ?>>Bloqueado</option>
	<option value="I"  <?=($_SESSION['tusuarios'][$id][4]=="I")?"selected":"" ?>>Desactivado</option>
</select> <br> 
Plan :<br>
<select name="plan" size="2">
	<option value="0" <?=($_SESSION['tusuarios'][$id][3]==0)?"selected":"" ?>>Basico</option>
	<option value="1" <?=($_SESSION['tusuarios'][$id][3]==1)?"selected":"" ?>>Profesional</option>
	<option value="2" <?=($_SESSION['tusuarios'][$id][3]==2)?"selected":"" ?>>Premium</option>
</select><br>
	<input type="submit" value="Modificar Usuario">			
	<button><a href="<?= $auto?>?orden=VerUsuarios">cancelar</a></button>
</form>



<?php 
// Vacio el bufer y lo copio a contenido
// Para que se muestre en div de contenido
$contenido = ob_get_clean();
include_once "principal.php";

?>