<?php 
session_start();
if(!isset($_SESSION['usuario']) || $_SESSION['rol'] != 1){
    header("location: index.php");
}

$titulo = "Inicio | Dental Club SV";
include("clases/BaseDatos.php");
$objBD = new baseDatos("localhost","eonstore","root",""); //"server,bd,usuario,password"
//Si se usa XAMPP cambiar la clave a "", Caso contrario dejar "mysql"

include("recursos/admin_header.php");

//Contadores
$categorias = count($objBD->leer("categoria","*"));
$marcas = count($objBD->leer("marca","*"));
$productos = count($objBD->leer("producto","*"));

$pendientes = count($objBD->leer("pedido","*",array("estado" => "1")));
$proceso = count($objBD->leer("pedido","*",array("estado" => "2")));
$completado = count($objBD->leer("pedido","*",array("estado" => "3")));

?>
<!-- Cuerpo de página -->
<div class="wrapper">
    <h1 class="title has-text-centered">¡Bienvenido a Dental Club SV!</h1>
    <hr>
    <h1 class="title is-4 has-text-centered">Administrar la Tienda</h1>
    <div class="columns">
        <div class="column">
            <a href="admin_categorias.php">
                <div class="card has-background-primary">
                    <div class="card-content">
                        <div class="media">
                            <div class="media-left">
                                <figure class="image is-48x48">
                                    <img src="recursos/img/categorias.png" alt="Categorías">
                                </figure>
                            </div>
                            <div class="media-content">
                                <p class="title is-4 has-text-white">Categorías</p>
                                <p class="subtitle is-6 has-text-white"><?php echo $categorias;?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="column">
            <a href="admin_marcas.php">
                <div class="card has-background-info">
                    <div class="card-content">
                        <div class="media">
                            <div class="media-left">
                                <figure class="image is-48x48">
                                    <img src="recursos/img/marcas.png" alt="Marcas">
                                </figure>
                            </div>
                            <div class="media-content">
                                <p class="title is-4 has-text-white">Marcas</p>
                                <p class="subtitle is-6 has-text-white"><?php echo $marcas;?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="column">
            <a href="admin_productos.php">
                <div class="card has-background-link">
                    <div class="card-content">
                        <div class="media">
                            <div class="media-left">
                                <figure class="image is-48x48">
                                    <img src="recursos/img/productos.png" alt="Productos">
                                </figure>
                            </div>
                            <div class="media-content">
                                <p class="title is-4 has-text-white">Productos</p>
                                <p class="subtitle is-6 has-text-white"><?php echo $productos;?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <h1 class="title is-4 has-text-centered">Resumen de pedidos</h1>
    <div class="columns">
        <div class="column">
            <div class="card has-background-danger">
                <div class="card-content">
                    <div class="media">
                        <div class="media-left">
                            <figure class="image is-48x48">
                                <img src="recursos/img/pendiente.png" alt="Pendientes">
                            </figure>
                        </div>
                        <div class="media-content">
                            <p class="title is-4 has-text-white">Pedidos Pendientes</p>
                            <p class="subtitle is-6 has-text-white"><?php echo $pendientes;?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="column">
            <div class="card has-background-warning">
                <div class="card-content">
                    <div class="media">
                        <div class="media-left">
                            <figure class="image is-48x48">
                                <img src="recursos/img/proceso.png" alt="En Proceso">
                            </figure>
                        </div>
                        <div class="media-content">
                            <p class="title is-4">Pedidos en Proceso</p>
                            <p class="subtitle is-6"><?php echo $proceso;?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="column">
            <div class="card has-background-success">
                <div class="card-content">
                    <div class="media">
                        <div class="media-left">
                            <figure class="image is-48x48">
                                <img src="recursos/img/completado.png" alt="Completado">
                            </figure>
                        </div>
                        <div class="media-content">
                            <p class="title is-4 has-text-white">Pedidos Completados</p>
                            <p class="subtitle is-6 has-text-white"><?php echo $completado;?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Cuerpo de página -->
<!--Inclusiones de pie de página-->
<?php
include("recursos/footer.php");
?>