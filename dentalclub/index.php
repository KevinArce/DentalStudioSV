<!-- Inclusiones Principales del Header-->
<?php
session_start();
$titulo = "Inicio | Dental Club SV";
include("clases/BaseDatos.php");
include("clases/Carrito.php");
$objBD = new baseDatos("localhost","dentalclub","root","");
//Si se usa XAMPP cambiar la clave a "", Caso contrario dejar "mysql"

//instancia del Carrito
$cart = new Cart();

include("recursos/header.php");
?>
<!-- Cuerpo de página -->
<div class="wrapper">
    <h1 class="title has-text-centered">¡Bienvenido a Dental Club SV!</h1>
    <hr>
    <div class="columns">

    </div>
</div>
<!-- /Cuerpo de página -->
<!--Inclusiones de pie de página-->
<?php
include("recursos/footer.php");
?>