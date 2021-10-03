<!-- Inclusiones Principales del Header-->
<?php
session_start();
$titulo = "Productos | EON Store";
include("clases/BaseDatos.php");
include("clases/Carrito.php");

$objBD = new baseDatos("localhost","eonstore","root","");
//Si se usa XAMPP cambiar la clave a "", Caso contrario dejar "mysql"

//instancia del Carrito
$cart = new Cart();

include("recursos/header.php");

//Condiciones para mostrar los productos
if(isset($_GET['cat'])){
    $id_tabla = $_GET['cat'];
    $campo = "id_cat";
    $tabla = "categoria";
} elseif(isset($_GET['marca'])){
    $id_tabla = $_GET['marca'];
    $campo = "id_marca";
    $tabla = "marca";
} else {
    $id_tabla = null;
    $campo = null;
    $tabla = null;
}

if($id_tabla != null && $campo != null)
    $nom_pag = $objBD->leer($tabla,"nombre",array($campo => $id_tabla))[0]["nombre"];

?>
<!-- Cuerpo de página -->
<div class="wrapper">
    <h1 class="title has-text-centered"><?php echo $nom_pag ?></h1>
    <hr>
    <div class="columns is-multiline">
    <?php
        $productos = $objBD->leer("producto","*",array($campo => $id_tabla));
        foreach($productos as $p){
            extract($p);
            ?>
            <div class="column is-3">
                <div class="card has-background-light">
                    <div class="card-image">
                        <figure class="image is-4by3">
                            <img src="<?php echo $imagen ?>" alt="<?php echo  $nombre ?>">
                        </figure>
                    </div>
                    <div class="card-content">
                        <div class="media-content">
                            <p class="title is-4 has-text-centered"><?php echo $nombre ?></p>
                            <p class="subtitle has-text-centered">$ <?php echo $precio ?></p>
                            <form>
                                <input type="hidden" class="id-producto" value="<?php echo $id_prod ?>">
                                <input type="hidden" class="precio" value="<?php echo $precio ?>">
                                <input type="hidden" class="nombre" value="<?php echo $nombre ?>">
                                <div class="field">
                                    <label class="label">Cantidad</label>
                                    <div class="control">
                                        <input class="input cantidad" type="number" min="1">
                                    </div>
                                </div>
                                <div class="field">
                                    <button class="button is-info add-to-cart"><i class="fa fa-shopping-cart"></i> Añadir al Carro</button>
                                </div>
                            </form>
                        </div>  
                    </div>
                </div>
            </div>
            <?php
        }
    ?>
    </div>
</div>
<!-- /Cuerpo de página -->
<!--Inclusiones de pie de página-->
<?php
include("recursos/footer.php");
?>
<script>
    $(document).ready(function(){
        $('.add-to-cart').click(function(e){
            e.preventDefault();

            var $btn = $(this);
            var id = $btn.parent().parent().find('.id-producto').val();
            var precio = $btn.parent().parent().find('.precio').val();
            var ctd = $btn.parent().parent().find('.cantidad').val();
            var nombre = $btn.parent().parent().find('.nombre').val();
            var $form = $('<form action="carrito.php" method="post" />').html('<input type="hidden" name="agregar" value=""><input type="hidden" name="id" value="' + id + '"><input type="hidden" name="ctd" value="' + ctd + '"><input type="hidden" name="precio" value="' + precio + '"><input type="hidden" name="nombre" value="' + nombre + '">');

            $('body').append($form);
            $form.submit();
        });
    });
</script>