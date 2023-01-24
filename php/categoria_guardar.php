<?php
    #Invocamos el archivo main.php para hacer uso de las fucnionalidades que este contiene: PASO 1#
    require_once "main.php";

    # Almacenando datos: PASO 2 #

    $nombre = limpiar_cadena($_POST['categoria_nombre']);
    $ubicacion = limpiar_cadena($_POST['categoria_ubicacion']);

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
        
        # Guardando datos desde el formulario: PASO 5 #

        $guardar_categoria=conexion();
        $guardar_categoria=$guardar_categoria->prepare("INSERT INTO categoria(categoria_nombre, categoria_ubicacion) 
                                                    VALUES (:nombre, :ubicacion)");
    
        $marcadores=[
            ":nombre" => $nombre,
            ":ubicacion" => $ubicacion
        ];
    
        $guardar_categoria->execute($marcadores);

        # Confirmanado los datos guardados: PASO 6 #
    
        if ($guardar_categoria->rowCount()==1) {
            echo '
                    <div class="notification is-info is-light">
                        <strong>¡Categoria ingresada con exito!</strong><br>
                        La CATEGORIA se registro con éxito.
                    </div>
                    ';
        } else {
            echo '
                    <div class="notification is-danger is-light">
                        <strong>¡Ocurrio un error inesperado!</strong><br>
                        No se pudo registrar la categoria, Por favor intente nuevamente.
                    </div>
                    ';
        }
        $guardar_categoria=null;
?>