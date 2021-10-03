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
            <a class="navbar-item" href="index.php">
            <img src="recursos/img/store.png" width="120" height="100">
            </a>
            <a role="button" class="navbar-burger burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
            </a>
        </div>
        <div id="menuPrincipal" class="navbar-menu">
            <div class="navbar-start">
                <a class="navbar-item" href="index.php">
                    Inicio
                </a>
                <div class="navbar-item has-dropdown is-hoverable">
                    <a class="navbar-link">
                    Categorías
                    </a>
                    <div class="navbar-dropdown">
                        <?php
                        $categorias = $objBD->leer("categoria","nombre,id_cat");
                        foreach($categorias as $c){
                            extract($c);
                            echo '
                                <a class="navbar-item" href="productos.php?cat='.$id_cat.'">'.$nombre.'</a>    
                            ';
                        }
                        ?>
                    </div>
                </div>
                <div class="navbar-item has-dropdown is-hoverable">
                    <a class="navbar-link">
                    Marcas
                    </a>
                    <div class="navbar-dropdown">
                        <?php
                        $marcas = $objBD->leer("marca","nombre,id_marca");
                        foreach($marcas as $m){
                            extract($m);
                            echo '
                                <a class="navbar-item" href="productos.php?marca='.$id_marca.'">'.$nombre.'</a>    
                            ';
                        }
                        ?>
                    </div>
                </div>
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
                <a class="navbar-item" href="carrito.php">
                    <span class="icon is-small is-left">
                        <i class="fas fa-shopping-cart"></i>
                    </span>
                    &nbsp;&nbsp;&nbsp;Carrito (<?php echo $cart->getTotalItem() ?>)
                </a>
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