<!-- Inclusiones Principales del Header-->
<?php
session_start();
$titulo = "Carrito | Dental Club SV";
include("clases/BaseDatos.php");
include("clases/Carrito.php");
date_default_timezone_set('America/El_Salvador');
$objBD = new baseDatos("localhost","dentalclub","root","");
//Si se usa XAMPP cambiar la clave a "", Caso contrario dejar "mysql"
if(!isset($_SESSION["usuario"])){
    echo "
    <script>
        alert('debes iniciar sesión para continuar');
        window.location = 'index.php';
    <script>";
}
//instancia del Carrito
$cart = new Cart();

include("recursos/header.php");
if (!$cart->estaVacio()) {
    $todosItems = $cart->obtenerItems();
}
$arrProdsXpedido = array();

//Insertando nuevo pedido
$pedido = array(
    "usuario" => $_SESSION['correo'],
    "fecha" => date("Y-m-d"),
    "hora" => date("H:i:s"),
    "estado" => 1
);

$res = $objBD->insertar("pedido",$pedido);
if($res == 1){
    $lastId = $objBD->consulta_personalizada("SELECT MAX(id_pedido) as id FROM pedido LIMIT 1")[0]['id'];
    //Preparando array para insertar
    $inserts = 0;
    $cItems = 0;
    foreach($todosItems as $id => $items){
        foreach($items as $item){
            $arrProdsXpedido = [
                'id_pedido' => $lastId,
                'cantidad' => $item['cantidad'],
                'id_producto' => $id
            ];
            $res2 = $objBD->insertar("productoXpedido",$arrProdsXpedido);
            if($res2 == 1){
                $inserts++;
            }
            $cItems++;
        }
    }
    if($inserts == $cItems){
        echo '
        <div class="message is-success">
            <div class="message-body">
                <i class="fa fa-info-circle"></i> ¡Pedido Procesado Correctamente!
            </div>
        </div>
        ';
        $cart->vaciar();
    }else{
        echo '
        <div class="message is-danger">
            <div class="message-body">
                <i class="fa fa-info-circle"></i> ¡Algo ha Salido mal!
            </div>
        </div>
        ';
    }
}else{
    var_dump($res);
}
?>

<!-- Cuerpo de página -->
<div class="wrapper">
</div>
<!-- /Cuerpo de página -->
<!--Inclusiones de pie de página-->
<?php
include("recursos/footer.php");
?>