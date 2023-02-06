<?php
    $category_id_del=limpiar_cadena($_GET['category_id_del']);

     //Verificar la categoria para ser eliminado

     $check_categoria=conexion();
     $check_categoria=$check_categoria->query("SELECT categoria_id FROM categoria WHERE categoria_id = '$category_id_del'");

    if ($check_categoria->rowCount()==1) {
        
        //Verificar productos para ser eliminado
        $check_producto=conexion();
        $check_producto=$check_producto->query("SELECT categoria_id FROM producto WHERE categoria_id = '$category_id_del' LIMIT 1");
        
        if ($check_producto->rowCount()<=0) {
            //Verificar Usuario
            $eliminar_categoria=conexion();
            $eliminar_categoria=$eliminar_categoria->prepare("DELETE FROM categoria WHERE categoria_id = :id");

            $eliminar_categoria->execute([":id"=>$category_id_del]);
            
            if ($eliminar_categoria->rowCount()==1) {
                echo '
                    <div class="notification is-info is-light">
                        <strong>¡CATEGORIA eliminada con exito!</strong><br>
                        La CATEGORIA fue eliminado con exito
                    </div>
                ';
            } else {
                echo '
                    <div class="notification is-danger is-light">
                        <strong>¡Ocurrio un error inesperado!</strong><br>
                        La CATEGORI no se pudo eliminar, por favor intente nuevamente
                    </div>
                ';
            }

            $eliminar_categoria=null;
        } else {
            echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrio un error inesperado!</strong><br>
                    No podemos eliminar la CATEGORIA ya que tiene productos asociados.
                </div>
            ';
        }
        $check_producto=null;
    
    } else {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                La CATEGORIA que intenta eliminar no existe, por favor intente nuevamente
            </div>
        ';
    }
?>
 