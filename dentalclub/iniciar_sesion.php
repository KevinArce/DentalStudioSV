<!-- Inclusiones Principales del Header-->
<?php
session_start();
if(isset($_SESSION["usuario"])){
    header("location: index.php");
}
$titulo = "Iniciar Sesión | Dental Club SV";
include("clases/BaseDatos.php");
$objBD = new baseDatos("localhost","dentalclub","root",""); //"server,bd,usuario,password"
//Si se usa XAMPP cambiar la clave a "", Caso contrario dejar "mysql"

include("recursos/header.php");
?>
<!-- Cuerpo de página -->
<div class="wrapper">
    <h1 class="title has-text-centered">¡Inicia Sesión para continuar!</h1>
    <hr>
    <div class="columns">
        <div class="column is-3"></div>
        <div class="column is-6">
            <div class="card has-background-light wrapper">
                <form action="" method="post">                  
                    <div class="field">
                        <label class="label">Email</label>
                        <div class="control has-icons-left">
                            <input class="input" type="email" id="email" name="correo" placeholder="alguien@ejemplo.com" required>
                            <span class="icon is-small is-left">
                                <i class="fas fa-envelope"></i>
                            </span>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Contraseña</label>
                        <p class="control has-icons-left">
                            <input class="input" type="password" id="pass" name="password" placeholder="•••••••••••" required>
                            <span class="icon is-small is-left">
                                <i class="fas fa-lock"></i>
                            </span>
                        </p>
                    </div>
                    <div class="field is-grouped">
                        <div class="control">
                            <button class="button is-link" type="submit" name="entrar">Entrar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="column is-3"></div>
    </div>
</div>
<!-- /Cuerpo de página -->
<!--Inclusiones de pie de página-->
<?php
include("recursos/footer.php");
?>
<?php
if(isset($_POST['entrar'])){
    unset($_POST['entrar']);
    $resultado = $objBD->leer("usuario","*",$_POST);
    if(count($resultado) > 0){
        $_SESSION['usuario'] = $resultado[0]["nombre"];
        $_SESSION['correo'] = $resultado[0]["correo"];
        $_SESSION['rol'] = $resultado[0]['rol'];
        if($_SESSION['rol'] == '1'){
            if(isset($_GET["service"])){
                echo "
                    <script>
                        window.location = ".$_GET['service'].".php;
                    </script>
                ";
            }else{
                echo "
                    <script>
                        window.location = 'admin_panel.php';
                    </script>
                ";
            }
        }else{
            if(isset($_GET["service"])){
                echo "
                    <script>
                        window.location = ".$_GET['service'].".php;
                    </script>
                ";
            }else{
                echo "
                    <script>
                        window.location = 'index.php';
                    </script>
                ";
            }
        }
    }else{
        echo "
            <script>
                Swal.fire({
                    title: '¡Error!',
                    text: 'Los datos ingresados no son correctos',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                })
            </script>
        ";
    }
}
?>