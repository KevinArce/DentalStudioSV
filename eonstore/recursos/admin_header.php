<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $titulo; ?></title>
    <link rel="stylesheet" href="recursos/css/bulma.min.css">
    <script defer src="recursos/js/all.js"></script>
    <style>
        .wrapper {
            padding: 1rem;
        }
    </style>
  </head>
  <body>
    <nav class="navbar is-info" role="navigation" aria-label="navegacion principal">
        <div class="navbar-brand">
            <a class="navbar-item" href="admin_panel.php">
            <img src="recursos/img/store.png" width="112" height="28">
            </a>
            <a role="button" class="navbar-burger burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
            </a>
        </div>
        <div id="menuPrincipal" class="navbar-menu">
            <div class="navbar-start">
                <a class="navbar-item" href="admin_panel.php">
                    Inicio
                </a>
                <a class="navbar-item" href="admin_categorias.php">
                    Categorías
                </a>
                <a class="navbar-item" href="admin_marcas.php">
                    Marcas
                </a>
                <a class="navbar-item" href="admin_productos.php">
                    Productos
                </a>
                <a class="navbar-item" href="admin_pedidos.php">
                    Pedidos
                </a>
            </div>
            <!-- Menú dependiente de la existencia de sesión -->
            <?php
            if(!isset($_SESSION['usuario'])){
            ?>
            <div class="navbar-end">
                <div class="navbar-item">
                    <div class="buttons">
                        <a class="button is-primary" href="registro.php">
                            <strong>Registrarse</strong>
                        </a>
                        <a class="button is-light" href="iniciar_sesion.php">
                            Iniciar Sesión
                        </a>
                    </div>
                </div>
            </div>
            <?php 
            } else {
            ?>
            <div class="navbar-end">
                <div class="navbar-item">
                    ¡Hola!, <?php echo $_SESSION['usuario'];?>
                </div>
                <a class="navbar-item" href="logout.php">
                    Cerrar Sesión
                </a>
            </div>
            <?php
            }
            ?>
        </div>
    </nav>