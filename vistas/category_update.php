<div class="container is-fluid mb-6">
    <h1 class="title">Categorias</h1>
    <h2 class="subtitle">Actualizar Categoría</h2>
</div>
<div class="container pb-6 pt-6">
    <?php
        include "./inc/btn_back.php";
    
        require_once "./php/main.php";

        $id=(isset($_GET['category_id_up'])) ? $_GET['category_id_up'] : 0;
        $id=limpiar_cadena($id);

        // Verificando usuario //
        $check_categoria=conexion();
        $check_categoria=$check_categoria->query("SELECT * FROM categoria WHERE categoria_id = '$id'");

        if ($check_categoria->rowCount()>0) {
            $datos=$check_categoria->fetch();

    ?>
    
    <div class="form-rest mb-6 mt-6"></div>
    <form action="./php/categoria_actualizar.php" method="POST" autocomplete="off" class="FormularioAjax">
        
        <input type="hidden" name="categoria_id" value="<?php echo $datos['categoria_id']; ?>">
        
        <div class="columns">
            <div class="column">
                <div class="control">
                    <label>Nombre Categoria</label>
                    <input class="input" type="text" name="categoria_nombre" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 ]{4,50}" maxlength="50" required value="<?php echo $datos['categoria_nombre']; ?>">
                </div>
            </div>
            <div class="column">
                <div class="control">
                    <label>Ubicacion</label>
                    <input class="input" type="text" name="categoria_ubicacion" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 ]{5,150}" maxlength="150" value="<?php echo $datos['categoria_ubicacion']; ?>">
                </div>
            </div>
        </div>    
        <p class="has-text-centered">
            <button type="submit" class="button is-info is-rounded">Actualizar</button>
        </p>
    </form>
    <?php
        } else {
           include "./inc/error_alert.php";
        }
    $check_categoria=null;      
    ?>
</div>