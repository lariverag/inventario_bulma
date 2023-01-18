<?php
    $user_id_del=limpiar_cadena($_GET['user_id_del']);

    //Verificar el usuario para ser eliminado

    $check_usuario=conexion();
    $check_usuario=$check_usuario->query("SELECT usuario_id FROM usuario WHERE usuario_id = '$user_id_del'");

    if ($check_usuario->rowCount()==1) {
        
        //Verificar el usuario para ser eliminado
        $check_producto=conexion();
        $check_producto=$check_producto->query("SELECT usuario_id FROM producto WHERE usuario_id = '$user_id_del' LIMIT 1");

        if ($check_producto->rowCount()<=0) {
            
            $eliminar_usuario=conexion();
            $eliminar_usuario=$eliminar_usuario->prepare("DELETE FROM usuario WHERE usuario_id = :id");

            $eliminar_usuario->execute([":id"=>$user_id_del]);

            if ($eliminar_usuario->rowCount()==1) {
                echo '
                    <div class="notification is-info is-light">
                        <strong>¡Usuario eliminado con exito!</strong><br>
                        El usuario fue eliminado con exito
                    </div>
                ';
            } else {
                echo '
                    <div class="notification is-danger is-light">
                        <strong>¡Ocurrio un error inesperado!</strong><br>
                        El usuario no se pudo eliminar, por favor intente nuevamente
                    </div>
                ';
            }

            $eliminar_usuario=null;
            

        } else {
            echo '
                <div class="notification is-danger is-light">
                    <strong>¡Ocurrio un error inesperado!</strong><br>
                    El usuario no se puede eliminar ya que tiene productos asociados
                </div>
            ';

        }
        $check_producto=null;

    } else {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El usuario que intenta eliminar no existe
            </div>
        ';
    }

?>