<div class="container is-fluid mb-6">
    <h1 class="title">Categorias</h1>
    <h2 class="subtitle">Nueva Categoria</h2>
</div>
<div class="container pb-6 pt-6">
    
    <div class="form-rest mb-6 mt-6"></div>

    <form action="./php/categoria_guardar.php" method="POST" autocomplete="off" class="FormularioAjax">
        <div class="columns">
            <div class="column">
                <div class="control">
                    <label>Nombre Categoria</label>
                    <input class="input" type="text" name="categoria_nombre" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 ]{4,50}" maxlength="50" required>
                </div>
            </div>
            <div class="column">
                <div class="control">
                    <label>Ubicacion</label>
                    <input class="input" type="text" name="categoria_ubicacion" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 ]{5,150}" maxlength="150">
                </div>
            </div>
        </div>    
        <p class="has-text-centered">
            <button type="submit" class="button is-info is-rounded">Guardar</button>
        </p>
    </form>
</div>