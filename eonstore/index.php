<!-- Inclusiones Principales del Header-->
<?php
session_start();
$titulo = "Inicio | Dental Club SV";
include("clases/BaseDatos.php");
include("clases/Carrito.php");
$objBD = new baseDatos("localhost","eonstore","root","");
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
        <?php
        $categorias = $objBD->leer("categoria","*");
        foreach($categorias as $c){
            extract($c);
            echo '
                <div class="column">
                    <a href="productos.php?cat='.$id_cat.'">
                        <div class="card">
                            <div class="card-image">
                                <figure class="image is-4by3">
                                    <img src="'.$imagen.'" alt="'.$nombre.'">
                                </figure>
                            </div>
                            <div class="card-content">
                                <div class="media-content">
                                    <p class="title is-4 has-text-centered">'.$nombre.'</p>
                                </div>  
                            </div>
                        </div>
                    </a>
                </div>
            ';
        }
        ?>
    </div>
</div>
<!-- /Cuerpo de página -->
<!--Inclusiones de pie de página-->
<?php
include("recursos/footer.php");
?>