<?php 
session_start();
if(!isset($_SESSION['usuario']) || $_SESSION['rol'] != 1){
    header("location: index.php");
}

$titulo = "Inicio | Dental Club SV";
include("clases/BaseDatos.php");
$objBD = new baseDatos("localhost","dentalclub","root",""); //"server,bd,usuario,password"
//Si se usa XAMPP cambiar la clave a "", Caso contrario dejar "mysql"

include("recursos/admin_header.php");

//Contadores
$productos = count($objBD->leer("producto","*"));

?>
<!-- Cuerpo de página -->
<div class="wrapper">
    <h1 class="title has-text-centered">¡Bienvenido a Dental Club SV!</h1>
    <hr>
    <h1 class="title is-4 has-text-centered">Administrar Inventario</h1>
    <div class="columns">
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
                                <p class="title is-4 has-text-white">Inventario</p>
                                <p class="subtitle is-6 has-text-white"><?php echo $productos;?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <h1 class="title is-4 has-text-centered">Control de Pacientes</h1>
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
                            <p class="title is-4 has-text-white">Odontograma</p>
                            <p class="subtitle is-6 has-text-white"><?php echo '$Variable ODONTOGRAMA';?></p>
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
                            <p class="title is-4 has-text-white">Pacientes</p>
                            <p class="subtitle is-6 has-text-white"><?php echo '$Variable Pacientes';?></p>
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