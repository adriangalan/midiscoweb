<?php 
include_once 'config.php';
/* DATOS DE USUARIO
• Identificador ( 5 a 10 caracteres, no debe existir previamente, solo letras y números)
• Contraseña ( 8 a 15 caracteres, debe ser segura)
• Nombre ( Nombre y apellidos del usuario)
• Correo electrónico ( Valor válido de dirección correo, no debe existir previamente)
• Tipo de Plan (0-Básico |1-Profesional |2- Premium| 3- Máster)
• Estado: (A-Activo | B-Bloqueado |I-Inactivo )
*/
// Inicializo el modelo 
// Cargo los datos del fichero a la session
function modeloUserInit(){
    
    /*
    $tusuarios = [ 
         "admin"  => ["12345"      ,"Administrado"   ,"admin@system.com"   ,3,"A"],
         "user01" => ["user01clave","Fernando Pérez" ,"user01@gmailio.com" ,0,"A"],
         "user02" => ["user02clave","Carmen García"  ,"user02@gmailio.com" ,1,"B"],
         "yes33" =>  ["micasa23"   ,"Jesica Rico"    ,"yes33@gmailio.com"  ,2,"I"]
        ];
    */
    if (! isset ($_SESSION['tusuarios'] )){
    $datosjson = @file_get_contents(FILEUSER) or die("ERROR al abrir fichero de usuarios");
    $tusuarios = json_decode($datosjson, true);
    $_SESSION['tusuarios'] = $tusuarios;
   }

      
}

// Comprueba usuario y contraseña (boolean)
function modeloOkUser($user,$clave){
    $resu=false;
    if (isset( $_SESSION['tusuarios'][$user])) {
        $userdat=$_SESSION['tusuarios'][$user];
        $userclave=$userdat[0];
        $resu= ($clave==$userclave);
    }
    return $resu;
}

// Devuelve el plan de usuario (String)
function modeloObtenerTipo($user){
    $nplan=$_SESSION['tusuarios'][$user][3];
    return PLANES[$nplan]; // Máster
}

// Borrar un usuario (boolean)*
function modeloUserDel($user){
    unset($_SESSION['tusuarios'][$user]);    
}
// Añadir un nuevo usuario (boolean)
function modeloUserAdd($userid,$userdat){
    $_SESSION['tusuarios'][$userid]=[$userdat[0],
        $userdat[1],
        $userdat[2],
        $userdat[3],
        $userdat[4]];     
}

// Actualizar un nuevo usuario (boolean)
function modeloUserUpdate ($userid,$userdat){ 
   
    $_SESSION['tusuarios'][$userid]=[$userdat[0],
 $userdat[1],
 $userdat[2],
 $userdat[3],
 $userdat[4]]; 
                
                                      
   
}

// Tabla de todos los usuarios para visualizar
function modeloUserGetAll (){
    // Genero lo datos para la vista que no muestra la contraseña ni los códigos de estado o plan
    // sino su traducción a texto
    $tuservista=[];
    foreach ($_SESSION['tusuarios'] as $clave => $datosusuario){
        $tuservista[$clave] = [$datosusuario[1],
                               $datosusuario[2],
                               PLANES[$datosusuario[3]],
                               ESTADOS[$datosusuario[4]]
                               ];
    }
    return $tuservista;
}
// Datos de un usuario para visualizar
function modeloUserGet ($user){
    $tuservista=$_SESSION['tusuarios'][$user];
    $tuservista[3]=PLANES[$tuservista[3]];  
    $tuservista[4]=ESTADOS[$tuservista[4]];
    return $tuservista;
}

// Vuelca los datos al fichero
function modeloUserSave(){
    
    $datosjon = json_encode($_SESSION['tusuarios']);
    file_put_contents(FILEUSER, $datosjon) or die ("Error al escribir en el fichero.");
    fclose($fich);
}

//chequeos

//identificador
function comprobarIdentificador($identificador) {
    $resultado=false;    
    if (strlen($identificador) >= 5  && strlen($identificador) <= 10 && ctype_alnum($identificador) && !array_key_exists($identificador, $_SESSION['tusuarios'] )) {
        $resultado=true;
    }
    return $resultado;
}

//nombre
function comprobarNombre($nombre) {
    return ( strlen($nombre) <= 20);  
}
//contraseña 
function comprobarClave($clave) :bool { 
    $resu=false;
    if (( strlen($clave) >= 8  && strlen($clave) <= 15) && hayMayusculas($clave) && hayMinusculas($clave) && hayNoAlfanumerico($clave) ) {
        $resu=true;
    }
    return $resu;
}


// Funciones auxilires contraseña 


function hayMayusculas ($valor){
    for ($i=0; $i<strlen($valor); $i++){
        if ( ctype_upper($valor[$i]) )
            return true;
    }
    return false;
}

function hayMinusculas ($valor){
    for ($i=0; $i<strlen($valor); $i++){
        if ( ctype_lower($valor[$i]))
            return true;
    }
    return false;
}



function hayNoAlfanumerico ($valor){
    for ($i=0; $i<strlen($valor); $i++){
        if ( !ctype_alnum($valor[$i]) )
            return true;
    }
    return false;
}

//correo
function comrobarCorreo($userid,$correo) {
    $resultado=false;
    if (filter_var($correo, FILTER_VALIDATE_EMAIL)) {        
        foreach ($_SESSION['tusuarios'] as $clave => $datosusuario){
            if ($datosusuario[2]==$correo && $userid!=$clave) {
                $resultado=false;
                break;
            }else{
                $resultado=true;
            }
        }
    }
    return $resultado;
}
function comrobarCorreoExiste($correo) {
    $resultado=false;
    if (filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        foreach ($_SESSION['tusuarios'] as $clave => $datosusuario){
            if ($datosusuario[2]==$correo) {
                 $resultado=false;
                 break;
            }else{
                $resultado=true;
            }
         }
    }
    return $resultado;
}

//Estado
function comprobarEstado($param) {
    $resul=false;
    if ($param=="A" || $param=="B" || $param=="I") {
        $resul=true;
    }
    return $resul;
}

//Plan
function comprobarPlan($param) {
    $resul=false;
    if ($param>=0 || $param<=3) {
        $resul=true;
    }
    return $resul;
}














/*
 *  Funciones para limpiar la entreda de posibles inyecciones
 */
function limpiarEntrada(string $entrada):string{
    $salida = trim($entrada); // Elimina espacios antes y después de los datos
    $salida = stripslashes($salida); // Elimina backslashes \
    $salida = htmlspecialchars($salida); // Traduce caracteres especiales en entidades HTML
    return $salida;
}
// Función para limpiar todos elementos de un array
function limpiarArrayEntrada(array &$entrada){
    
    foreach ($entrada as $key => $value ) {
        $entrada[$key] = limpiarEntrada($value);
    }
}
