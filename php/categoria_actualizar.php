<?php
    require_once "main.php";

    $id=limpiar_cadena($_POST['categoria_id']);

    //Verificar el usuario
    $check_categoria=conexion();
    $check_categoria=$check_categoria->query("SELECT * FROM categoria WHERE categoria_id='$id'");
    
    if ($check_categoria->rowCount()<=0) {
        echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrio un error inesperado!</strong><br>
                    La CATETORIA no existe en el sistema.
                </div>
                ';
        exit();
    } else {
        $datos=$check_categoria->fetch();
    }

    $check_categoria=null;

    # Almacenamos los datos en variables y les aplicamos la funcion limpiar cadena#
    $nombre=limpiar_cadena($_POST['categoria_nombre']);
    $ubicacion=limpiar_cadena($_POST['categoria_ubicacion']);
    

    # Verificando los campos obligatorios: PASO 3 #

    if($nombre == "" || $ubicacion == "" ){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No has llenado todos los campos que son obligatios.
            </div>
        ';
        exit();
    }

    # Verificando integridad de los datos: PASO 4 #

    if (verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 ]{4,50}",$nombre)) {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El NOMBRE no coincide con el formato solicitado.
            </div>
        ';
        exit();
    }

    if ($ubicacion!="") {
        if (verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 ]{5,150}",$ubicacion)) {
            echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrio un error inesperado!</strong><br>
                    La UBICACION no coincide con el formato solicitado.
                </div>
            ';
            exit();
        }
    }

    # Verificando nombre de la CATEGORIA #

    if ($nombre!=$datos['categoria_nombre']) {
        $check_nombre=conexion();
        $check_nombre=$check_nombre->query("SELECT categoria_nombre FROM categoria WHERE categoria_nombre = '$nombre'");
        if ($check_nombre->rowCount()>0) {
                echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrio un error inesperado!</strong><br>
                    La CATEGORIA ya existe, por favor elija otra.
                </div>
                ';
            exit();
        }
        $check_nombre=null;    
    }

    # Actualizar los datos en la base de datos#

    $actualizar_categoria=conexion();
    $actualizar_categoria=$actualizar_categoria->prepare("UPDATE categoria SET categoria_nombre = :nombre, categoria_ubicacion = :ubicacion WHERE categoria_id = :id");

    $marcadores = [
        ":nombre"=>$nombre,
        ":ubicacion"=>$ubicacion,
        ":id"=>$id
    ];

    if ($actualizar_categoria->execute($marcadores)) {
        echo '
            <div class="notification is-info is-light">
                <strong>¡Categoria Actualizada!</strong><br>
                La CATEGORIA se actualizó con exito.
            </div>
        ';
    } else {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Usuario no actualizado!</strong><br>
                No se pudo actualizar la CATEGORIA, intente de nuevo.
            </div>
        ';
    }

    $actualizar_categoria=null;

?>