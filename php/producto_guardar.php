<?php
    # #
    require_once "../inc/session_start.php";
    #Invocamos el archivo main.php para hacer uso de las fucnionalidades que este contiene: PASO 1#
    require_once "main.php";

    # Almacenando datos: PASO 2 #

    $codigo = limpiar_cadena($_POST['producto_codigo']);
    $nombre = limpiar_cadena($_POST['producto_nombre']);

    $precio = limpiar_cadena($_POST['producto_precio']);
    $stock = limpiar_cadena($_POST['producto_stock']);
    $categoria = limpiar_cadena($_POST['producto_categoria']);

    # Verificando los campos obligatorios: PASO 3 #

    if($codigo=="" || $nombre=="" || $precio=="" || $stock=="" || $categoria==""){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No has llenado todos los campos que son obligatios.
            </div>
        ';
        exit();
    }

    # Verificando integridad de los datos: PASO 4 #

    if (verificar_datos("[a-zA-Z0-9- ]{1,70}",$codigo)) {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El CODIGO DE BARRAS no coincide con el formato solicitado.
            </div>
        ';
        exit();
    }

    if (verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,70}",$nombre)) {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            El NOMBRE DEL PRODUCTO no coincide con el formato solicitado.
        </div>
    ';
    exit();
    }

    if (verificar_datos("[0-9.]{1,25}",$precio)) {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El PRECIO no coincide con el formato solicitado.
            </div>
        ';
        exit();
    }

    if (verificar_datos("[0-9]{1,25}",$stock)) {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El STOCK no coincide con el formato solicitado.
            </div>
        ';
        exit();
    }
    # Verificando el Codigo del Producto #
    $check_codigo=conexion();
    $check_codigo=$check_codigo->query("SELECT producto_codigo FROM producto WHERE producto_codigo='$codigo'");
    if ($check_codigo->rowCount()>0) {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El CODIGO ingresado ya existe, por favor elija otro.
            </div>
        ';
    exit();
    }
    $check_codigo=null;


    # Verificando el Nombre del Producto #
    $check_nombre=conexion();
    $check_nombre=$check_nombre->query("SELECT producto_nombre FROM producto WHERE producto_nombre = '$nombre'");
    if ($check_nombre->rowCount()>0) {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El NOMBRE DEL PRODUCTO ingresado ya existe, por favor elija otro.
            </div>
        ';
    exit();
    }
    $check_nombre=null;

    # Verificando el id de la Categoria exista #
    $check_categoria=conexion();
    $check_categoria=$check_categoria->query("SELECT categoria_id FROM categoria WHERE categoria_id = '$categoria'");
    if ($check_categoria->rowCount()<=0) {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                La CATEGORIA seleccionada no existe.
            </div>
        ';
    exit();
    }
    $check_categoria=null;

    # Directorio de Imagenes #

    $img_dir = "../img/producto/";


    # Comprobando si se selecciono una imagen #

    if ($_FILES['producto_foto']['name'] !="" && $_FILES['producto_foto']['size']>0) {
        # Creando Directorio #
        if (!file_exists($img_dir)) {
            if (mkdir($img_dir,0777)) {
                echo '
                    <div class="notification is-danger is-light">
                        <strong>¡Ocurrio un error inesperado!</strong><br>
                        Error al crear el directorio.
                    </div>
                ';
                exit(); 
            }
        }
        # Verificando el formato d elas imagenes #
        if (mime_content_type($_FILES['producto_foto']['tmp_name'])!="image/jpeg" && mime_content_type($_FILES['producto_foto']['tpm_name']) != "image/png") {
            echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrio un error inesperado!</strong><br>
                    La imagen que ha seleccionado es de un formato no permitido.
                </div>
            ';
            exit();
        }

        # Verificar el peso de la imagen #

        if (($_FILES['producto_foto']['size']/1024) > 3072) {
            echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrio un error inesperado!</strong><br>
                    La imagen que ha seleccionado supera el tamaño permitido.
                </div>
            ';
            exit();
        }

        # Verificar la extension de la imagen #

        switch (mime_content_type($_FILES['producto_foto']['tmp_name'])) {
            case 'image/jpeg':
                $img_ext=".jpg";
                break;
            case 'image/png':
                $img_ext=".png";
                break;
        }

        chmod($img_dir,0777);

        $img_nombre = renombrar_fotos($nombre);
        $foto=$img_nombre.$img_ext;

        if (!move_uploaded_file($_FILES['producto_foto']['tmp_name'],$img_dir.$foto)) {
            echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrio un error inesperado!</strong><br>
                    No se ha podido cargar a IMAGEN en este momento.
                </div>
            ';
            exit();
        }
    } else {
        $foto="";
    }

    # Guardando datos desde el formulario: PASO 5#

    $guardar_producto=conexion();
    $guardar_producto=$guardar_producto->prepare("INSERT INTO producto(producto_codigo, producto_nombre, producto_precio, producto_stock, producto_foto, categoria_id, usuario_id) 
                                                VALUES (:codigo, :nombre, :precio, :stock, :foto, :categoria, :usuario)");

    $marcadores=[
        ":codigo"=>$codigo,
        ":nombre"=>$nombre,
        ":precio"=>$precio,
        ":stock"=>$stock, 
        ":foto"=>$foto,
        ":categoria"=>$categoria,
        ":usuario"=>$_SESSION['id']
    ];

    $guardar_producto->execute($marcadores);

    # Confirmanado los datos guardados: PASO 6 #

    if ($guardar_producto->rowCount()==1) {
        echo '
                <div class="notification is-info is-light">
                    <strong>¡Producto Registrado!</strong><br>
                    El PRODUCTO se registro con éxito.
                </div>
                ';
    } else {

        if (is_file($img_dir.$foto)) {
            chmod($img_dir.$foto,0777);
            unlink($img_dir.$foto);
        }
        echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrio un error inesperado!</strong><br>
                    No se pudo guardar el PRODUCTO, Por favor intente nuevamente.
                </div>
                ';

    }
    $guardar_producto=null;
    
?>