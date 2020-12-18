<?php
// ------------------------------------------------
// Controlador que realiza la gestión de usuarios
// ------------------------------------------------
include_once 'config.php';
include_once 'modeloUser.php';

/*
 * Inicio Muestra o procesa el formulario (POST)
 */

function  ctlUserInicio(){
    $msg = "";
    $user ="";
    $clave ="";
    if ( $_SERVER['REQUEST_METHOD'] == "POST"){
        if (isset($_POST['user']) && isset($_POST['clave'])){
            $user =$_POST['user'];
            $clave=$_POST['clave'];
            if ( modeloOkUser($user,$clave)){
                $_SESSION['user'] = $user;
                $_SESSION['tipouser'] = modeloObtenerTipo($user);
                if ( $_SESSION['tipouser'] == "Máster"){
                    $_SESSION['modo'] = GESTIONUSUARIOS;
                    header('Location:index.php?orden=VerUsuarios');
                }
                else {
                  // Usuario normal;
                  // PRIMERA VERSIÓN SOLO USUARIOS ADMISTRADORES
                  $msg="Error: Acceso solo permitido a usuarios Administradores.";
                  unset($_SESSION['user']);
                  // $_SESSION['modo'] = GESTIONFICHEROS;
                  // Cambio de modo y redireccion a verficheros
                }
            }
            else {
                $msg="Error: usuario y contraseña no válidos.";
           }  
        }
    }
    
    include_once 'plantilla/facceso.php';
}

// Cierra la sesión y vuelva los datos
function ctlUserCerrar(){
    session_destroy();
    modeloUserSave();
    header('Location:index.php');
}

// Muestro la tabla con los usuario 
function ctlUserVerUsuarios (){
    // Obtengo los datos del modelo
    $usuarios = modeloUserGetAll(); 
    // Invoco la vista 
    include_once 'plantilla/verusuariosp.php';
   
}
//Borra un usuario
function ctlUserBorrar() {
    if (isset($_GET['id'])) {
        modeloUserDel($_GET['id']);       
    }
    header('Location:index.php');
}
//modifica un usuario
function ctlUserModificar() {
    if (isset($_POST['nombre']) && isset($_POST['correo']) && isset($_POST['clave']) && isset($_POST['estado']) && isset($_POST['plan'])) {
        limpiarArrayEntrada($_POST); //Evito la posible inyección de código
        $userdat;          
        $id=$_GET['id'];
        if (comprobarNombre($_POST['nombre'])) {
            $userdat[1]=$_POST['nombre'];
        }else {
            header('Location:index.php?orden=Modificar&id='.$id.'&error=nombre');
            
        }
        if (comrobarCorreo($id,$_POST['correo'])) {
            $userdat[2]=$_POST['correo'];
        }else{
            header('Location:index.php?orden=Modificar&id='.$id.'&error=correo');
           
        }
        if (comprobarClave($_POST['clave'])) {
            $userdat[0]=$_POST['clave'];
        }else{
            header('Location:index.php?orden=Modificar&id='.$id.'&error=clave');
            
        }        
        if (comprobarEstado($_POST['estado'])) {
            $userdat[4]=$_POST['estado'];            
        }
        if (comprobarPlan(intval($_POST['plan']))) {
            $userdat[3]=intval($_POST['plan']);
        }        
        modeloUserUpdate($id,$userdat);
        header('Location:index.php?orden=VerUsuarios');
        exit;
      
    }elseif(isset($_GET['id'])) {
        $usuarios = modeloUserGet($_GET['id']);
        $id=$_GET['id'];
    }
    include_once 'plantilla/fmodificar.php';
    
}
//Muestra los detalles de un usuario
function ctlUserDetalles() {
    if (isset($_GET['id'])) {
        $usuarios = modeloUserGet($_GET['id']); 
    }
    include_once 'plantilla/detalles.php';
}
//crear usuario
function ctlUserAlta() {
    if (isset($_POST) && isset($_POST['identificador'])) {
        limpiarArrayEntrada($_POST); //Evito la posible inyección de código
        $userdat;
        if (comprobarIdentificador($_POST['identificador'])) {
            $id=$_POST['identificador'];
        }else {
            header('Location:index.php?orden=Alta&error=identificador');
            
        }
        if (comprobarNombre($_POST['nombre'])) {
            $userdat[1]=$_POST['nombre'];
        }else {
            header('Location:index.php?orden=Alta&error=nombre');
            
        }
        if (comrobarCorreo($id,$_POST['correo'])) {
            $userdat[2]=$_POST['correo'];
        }else{
            header('Location:index.php?orden=Alta&error=correo');
            
        }
        if (comprobarClave($_POST['clave'])) {
            $userdat[0]=$_POST['clave'];
            if ($_POST['clave']!=$_POST['claveRepetida']) {
                header('Location:index.php?orden=Alta&error=claveRepetida');
                
            }
        }else{
            header('Location:index.php?orden=Alta&error=clave');
            
        }
        if (comprobarEstado($_POST['estado'])) {
            $userdat[4]=$_POST['estado'];
        }        
        if (comprobarPlan(intval($_POST['plan']))) {
            $userdat[3]=intval($_POST['plan']);
        }
        modeloUserAdd($id, $userdat);
        header('Location:index.php?orden=VerUsuarios');
        
    }
    
    include_once 'plantilla/fnuevo.php';    
}
function ctlUserVerRegistro() {
    if (isset($_POST) && isset($_POST['identificador'])) {
        limpiarArrayEntrada($_POST); //Evito la posible inyección de código
        $userdat;
        if (comprobarIdentificador($_POST['identificador'])) {
            $id=$_POST['identificador'];
        }else {
            header('Location:index.php?orden=Registro&error=identificador');
            
        }
        if (comprobarNombre($_POST['nombre'])) {
            $userdat[1]=$_POST['nombre'];
        }else {
            header('Location:index.php?orden=Registro&error=nombre');
            
        }
        if (comrobarCorreoExiste($_POST['correo'])) {
            $userdat[2]=$_POST['correo'];
        }else{
            header('Location:index.php?orden=Registro&error=correo');
            
        }
        if (comprobarClave($_POST['clave'])) {
            $userdat[0]=$_POST['clave'];
            if ($_POST['clave']!=$_POST['claveRepetida']) {
                header('Location:index.php?orden=Registro&error=claveRepetida');
                
            }
        }else{
            header('Location:index.php?orden=Registro&error=clave');
            
        }
        $userdat[4]="B";
        
        if (comprobarPlan(intval($_POST['plan']))) {
            $userdat[3]=intval($_POST['plan']);
        }
        modeloUserAdd($id, $userdat);
        header('Location:index.php?orden=VerUsuarios');
        
    }
    
    include_once 'plantilla/fregistro.php';    
}
