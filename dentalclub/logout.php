<?php
session_start();
include("clases/Carrito.php");
//instancia del Carrito
$cart = new Cart();
$cart->vaciar();
session_unset();
session_destroy();
header("location: index.php");
?>