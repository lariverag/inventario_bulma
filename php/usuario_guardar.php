<?php
    require_once "main.php";

    # Almacenando datos #

    $nombre = limpiar_cadena($_POST['usuario_nombre']);
    $apellido = limpiar_cadena($_POST['usuario_apellido']);

    $usuario = limpiar_cadena($_POST['usuario_usuario']);
    $email = limpiar_cadena($_POST['usuario_email']);
    
    $clave_1 = limpiar_cadena($_POST['usuario_clave_1']);
    $clave_2 = limpiar_cadena($_POST['usuario_clave_2']);

    # Verificando los campos obligatorios #

    if($nombre=="" || $apellido=="" || $usuario=="" || $clave_1=="" || $clave_2==""){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No has llenado todos los campos que son obligatios.
            </div>
        ';
        exit();

    }

    # Verificando integridad de los datos #

    if (verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}",$nombre)) {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El NOMBRE no coincide con el formato solicitado.
            </div>
        ';
        exit();
    }

    if (verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}",$apellido)) {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El APELLIDO no coincide con el formato solicitado.
            </div>
        ';
        exit();
    }

    if (verificar_datos("[a-zA-Z0-9]{4,20}",$usuario)) {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El USUARIO no coincide con el formato solicitado.
            </div>
        ';
        exit();
    }

    if (verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$clave_1) || verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$clave_2)) {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                Las CLAVES no coincide con el formato solicitado.
            </div>
        ';
        exit();
    }

    # Verificando el Email #

    if ($email!="") {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $check_email=conexion();
            $check_email=$check_email->query("SELECT usuario_email FROM usuario WHERE usuario_email='$email'");
            if ($check_email->rowCount()>0) {
                echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrio un error inesperado!</strong><br>
                    El E-MAIL ingresado ya se encuentra registrado, por favor elija otro.
                </div>
                ';
            exit();
            }
            $check_email=null;
        } else {
            echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrio un error inesperado!</strong><br>
                    El E-MAIL ingresado no valido.
                </div>
                ';
            exit();
        }
        
    }

    # Verificando Usuario #

    $check_usuario=conexion();
    $check_usuario=$check_usuario->query("SELECT usuario_usuario FROM usuario WHERE usuario_usuario='$usuario'");
            if ($check_usuario->rowCount()>0) {
                echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrio un error inesperado!</strong><br>
                    El USUARIO ingresado ya se existe, por favor elija otro.
                </div>
                ';
            exit();
            }
            $check_usuario=null;
    
    # Verificando confirmacion de claves #

    if ($clave_1 != $clave_2) {
        echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrio un error inesperado!</strong><br>
                    Las CLAVES que ha ingresado no coinciden, por favor verifique.
                </div>
                ';
        exit();
    } else {
        $clave=password_hash($clave_1, PASSWORD_BCRYPT, ["cost"=>10]);
    }

    # Guardando datos desde el formulario#

    $guardar_usuario=conexion();
    $guardar_usuario=$guardar_usuario->prepare("INSERT INTO usuario(usuario_nombre, usuario_apellido, usuario_usuario, usuario_clave, usuario_email) 
                                                VALUES (:nombre, :apellido, :usuario, :clave, :email)");

    $marcadores=[
        ":nombre"=>$nombre,
        ":apellido"=>$apellido,
        ":usuario"=>$usuario,
        ":clave"=>$clave, 
        ":email"=>$email
    ];

    $guardar_usuario->execute($marcadores);

    if ($guardar_usuario->rowCount()==1) {
        echo '
                <div class="notification is-info is-light">
                    <strong>¡Usuario Registrado!</strong><br>
                    El USUARIO se registro con éxito.
                </div>
                ';
        exit();
    } else {
        echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrio un error inesperado!</strong><br>
                    No se pudo registrar el usuario, Por favor intente nuevamente.
                </div>
                ';
        exit();
    }
    $guardar_usuario=null;
    

?>