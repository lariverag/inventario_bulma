<?php
    # Conexion a la Base de Datos inventario #
    function conexion(){
        $pdo = new PDO('mysql:host=localhost;dbname=inventario','root','');
        return $pdo;
    }
    
    # Prueba de insercion a la base de datos tabla categoria#

    /* $pdo = new PDO('mysql:host=localhost;dbname=inventario','root','');
    $pdo->query("INSERT INTO categoria(categoria_nombre, categoria_ubicacion) VALUES ('prueba', 'texto ubicacion')");*/


    # Funcion para Verificar datos #

    function verificar_datos($filtro, $cadena){
        if (preg_match("/^".$filtro."$/",$cadena)) {
            return false;
        } else {
            return true;
        }
    }

    #Funcion para limpiar cadenas de texto#

    function limpiar_cadena($cadena){
        $cadena=trim($cadena);
        $cadena=stripslashes($cadena);
        $cadena=str_ireplace("<script>","",$cadena);
        $cadena=str_ireplace("</script>","",$cadena);
        $cadena=str_ireplace("<script src>","",$cadena);
        $cadena=str_ireplace("<script type=>","",$cadena);
        $cadena=str_ireplace("SELECT * FROM","",$cadena);
        $cadena=str_ireplace("DELETE FROM","",$cadena);
        $cadena=str_ireplace("INSERT INTO","",$cadena);
        $cadena=str_ireplace("DROP TABLE","",$cadena);
        $cadena=str_ireplace("DROP DATABASE","",$cadena);
        $cadena=str_ireplace("TRUNCATE TABLE","",$cadena);
        $cadena=str_ireplace("SHOW TABLES","",$cadena);
        $cadena=str_ireplace("SHOW DATABASES","",$cadena);
        $cadena=str_ireplace("<?php","",$cadena);
        $cadena=str_ireplace("<?","",$cadena);
        $cadena=str_ireplace("--","",$cadena);
        $cadena=str_ireplace("^","",$cadena);
        $cadena=str_ireplace("<","",$cadena);
        $cadena=str_ireplace("[","",$cadena);
        $cadena=str_ireplace("]","",$cadena);
        $cadena=str_ireplace("==","",$cadena);
        $cadena=str_ireplace(";","",$cadena);
        $cadena=str_ireplace("::","",$cadena);
        $cadena=trim($cadena);
        $cadena=stripslashes($cadena);
        return $cadena;
    }

    # Funcion para renombrar fotos #

    function renombrar_fotos($nombre){
        $nombre = str_ireplace(" ","_",$nombre);
        $nombre = str_ireplace("/","_",$nombre);
        $nombre = str_ireplace("#","_",$nombre);
        $nombre = str_ireplace("-","_",$nombre);
        $nombre = str_ireplace("$","_",$nombre);
        $nombre = str_ireplace(".","_",$nombre);
        $nombre = str_ireplace(",","_",$nombre);
        $nombre=$nombre."_".rand(0,100);
        return $nombre;

    }

    # Probar la funcion para evitar que no hayan conflictos con los nombres de las fotos #

    /*$foto="Play Station 5 black/edition";
    echo renombrar_fotos($foto);*/

    # Funcion paginador de Tablas #

    function paginador_tablas($pagina, $nPaginas, $url){
        

    }
?>