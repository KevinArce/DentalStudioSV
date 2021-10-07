<!-- Inclusiones Principales del Header-->
<?php
session_start();
$titulo = "Carrito | Dental Club SV";
include("clases/BaseDatos.php");
include("clases/Carrito.php");

$objBD = new baseDatos("localhost","dentalclub","root","");
//Si se usa XAMPP cambiar la clave a "", Caso contrario dejar "mysql"

//instancia del Carrito
$cart = new Cart();

include("recursos/header.php");
$productos = $objBD->leer("producto","*");

$contenidoCarrito = '
	<div class="message is-warning">
        <div class="message-body">
            <i class="fa fa-info-circle"></i> No hay items en el Carrito.
        </div>
    </div>';
    

if (isset($_POST['vaciar'])) {
    $cart->vaciar();
}

if (isset($_POST['agregar'])) {    
    $cart->agregar($_POST['id'], $_POST['ctd'], [
        'precio' => $_POST['precio'],
        'nombre' => $_POST['nombre']
    ]);
}


if (isset($_POST['actualizar'])) {
    $cart->actualizar($_POST['id'], $_POST['ctd'], [
        'precio' => $_POST['precio'],
        'nombre' => $_POST['nombre']
    ]);
}

if (isset($_POST['quitar'])) {
    $cart->remover($_POST['id'], [
        'precio' => $_POST['precio'],
        'nombre' => $_POST['nombre']
    ]);
}

if (!$cart->estaVacio()) {
    $todosItems = $cart->obtenerItems();

    $contenidoCarrito = '
    <table class="table is-bordered is-hoverable is-fullwidth">
        <thead>
            <tr>
                <th class="has-background-dark has-text-light">Producto</th>
                <th class="has-background-dark has-text-light">Cantidad</th>
                <th class="has-background-dark has-text-light">Precio</th>
            </tr>
        </thead>
        <tbody>';
    foreach ($todosItems as $id => $items) {
        foreach ($items as $item) {
            $contenidoCarrito .= '
            <tr>
                <td>' . $item['atributos']['nombre']. '</td>
                <td><div class="field"><input type="number" value="' . $item['cantidad'] . '" class="input cantidad" style="width:100px"><button class="button is-primary btn-actualizar" data-id="' . $id . '" data-precio="' . $item['atributos']['precio'] . '" data-nombre="' . $item['atributos']['nombre'] . '"><i class="fa fa-sync"></i> Actualizar</button><button class="button is-danger btn-remover" data-id="' . $id . '" data-precio="' . $item['atributos']['precio'] . '" data-nombre="' . $item['atributos']['nombre'] . '"><i class="fa fa-trash"></i></button></div></td>
                <td class="text-right">$' . $item['atributos']['precio'] . '</td>
            </tr>';
        }
    }

    $contenidoCarrito .= '
        </tbody>
    </table>

    <div class="text-right">
        <h3>Total:<br />$' . number_format($cart->obtenerTotalAtributo('precio'), 2, '.', ',') . '</h3>
    </div>

    <p>
        <div class="pull-left">
            <button class="button is-danger btn-vaciar-cart">Vaciar Carrito</button>
            <a href="index.php" class="button is-info">Continuar Comprando</a>
            <a href="checkout.php" class="button is-danger">Procesar Pedido</a>
        </div>
    </p>';
}
?>
<!-- Cuerpo de página -->
<div class="wrapper">
    <h1 class="title is-2 has-text-centered">Carrito de Compras</h1>
    <div class="columns">
        <div class="column"></div>
        <div class="column is-10">
            <div class="table-container">
                <?php echo $contenidoCarrito; ?>
            </div>
        </div>
        <div class="column"></div>
    </div>
</div>
<!-- /Cuerpo de página -->
<!--Inclusiones de pie de página-->
<?php
include("recursos/footer.php");
?>
<script>
$(document).ready(function(){
    $('.btn-actualizar').on('click', function(){
        var $btn = $(this);
        var id = $btn.attr('data-id');
        var ctd = $btn.parent().parent().find('.cantidad').val();
        var precio = $btn.attr('data-precio');
        var nombre = $btn.attr('data-nombre');

        var $form = $('<form action="carrito.php" method="post" />').html('<input type="hidden" name="actualizar" value=""><input type="hidden" name="id" value="'+id+'"><input type="hidden" name="ctd" value="'+ctd+'"><input type="hidden" name="precio" value="'+precio+'"><input type="hidden" name="nombre" value="'+nombre+'">');

        $('body').append($form);
        $form.submit();
    });

    $('.btn-remover').on('click', function(){
        var $btn = $(this);
        var id = $btn.attr('data-id');
        var ctd = $btn.parent().parent().find('.cantidad').val();
        var precio = $btn.attr('data-precio');
        var nombre = $btn.attr('data-nombre');

        var $form = $('<form action="carrito.php" method="post" />').html('<input type="hidden" name="quitar" value=""><input type="hidden" name="id" value="'+id+'"><input type="hidden" name="ctd" value="'+ctd+'"><input type="hidden" name="precio" value="'+precio+'"><input type="hidden" name="nombre" value="'+nombre+'">');
        $('body').append($form);
        
        $form.submit();
    });

    $('.btn-vaciar-cart').on('click', function(){
        var $form = $('<form action="carrito.php" method="post" />').html('<input type="hidden" name="vaciar" value="">');

        $('body').append($form);
        $form.submit();
    });
});
</script>