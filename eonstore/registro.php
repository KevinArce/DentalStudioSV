<!-- Inclusiones Principales del Header-->
<?php
session_start();
if(isset($_SESSION["usuario"])){
    header("location: index.php");
}
$titulo = "Regístrate | EON Store";
include("clases/BaseDatos.php");
$objBD = new baseDatos("localhost","eonstore","root","");
//Si se usa XAMPP cambiar la clave a "", Caso contrario dejar "mysql"

include("recursos/header.php");
?>
<!-- Cuerpo de página -->
<div class="wrapper">
    <h1 class="title has-text-centered">¡Forma parte de EON Store!</h1>
    <hr>
    <div class="columns">
        <div class="column is-3"></div>
        <div class="column is-6">
            <div class="card has-background-light wrapper">
                <form id="registro" action="" method="post">
                    <div class="field">
                        <label class="label">Nombre</label>
                        <div class="control has-icons-left">
                            <input class="input" type="text" placeholder="Ej. Juan Pérez" id="nombre" name="nombre" required autofocus>
                            <span class="icon is-small is-left">
                                <i class="fas fa-user"></i>
                            </span>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Email</label>
                        <div class="control has-icons-left">
                            <input class="input" type="email" id="email" name="email" placeholder="alguien@ejemplo.com" required>
                            <span class="icon is-small is-left">
                                <i class="fas fa-envelope"></i>
                            </span>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Contraseña</label>
                        <p class="control has-icons-left">
                            <input class="input" type="password" id="pass1" name="pass1" placeholder="•••••••••••" required>
                            <span class="icon is-small is-left">
                                <i class="fas fa-lock"></i>
                            </span>
                        </p>
                    </div>
                    <div class="field">
                        <label class="label">Confirmar Contraseña</label>
                        <p class="control has-icons-left">
                            <input class="input" type="password" id="pass2" name="pass2" placeholder="•••••••••••" required>
                            <span class="icon is-small is-left">
                                <i class="fas fa-lock"></i>
                            </span>
                        </p>
                    </div>
                    <div class="field">
                        <div class="control">
                            <label class="checkbox">
                                <input type="checkbox" name="terms" id="terms" required>
                                Acepto los <a href="#">términos y condiciones</a>
                            </label>
                        </div>
                    </div>
                    <div class="field is-grouped">
                        <div class="control">
                            <button class="button is-link">Registrarse</button>
                        </div>
                        <div class="control">
                            <a href="index.php" class="button is-link is-light">Cancelar</a>
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
<script>
$("#registro").submit(function(e){
    e.preventDefault();
    
    //Capturamos los datos
    var nombre = $("#nombre").val();
    var email = $("#email").val();
    var pass1 = $("#pass1").val();
    var pass2 = $("#pass2").val();
    var validacion = 0;
    
    //verificamos que las contraseñas coincidan
    if(pass1 != pass2){
        Swal.fire({
            title: '¡Error!',
            text: 'Las contraseñas no coinciden',
            icon: 'error',
            confirmButtonText: 'Aceptar'
        })
        validacion++;   
    }

    //verificamos que el correo no esté en uso
    $.ajax({
        url: "clases/ajax.php?solicitud=correo",
        type: "POST",
        success: function(result){
            if(result > 0 ){
                console.log(result);
                validacion++;
                Swal.fire({
                    title: '¡Error!',
                    text: 'El correo ya está en uso',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                })
            }
        }
    })

    //Si pasa todas las validaciones
    if(validacion == 0){
        $.ajax({
            url: "clases/ajax.php?solicitud=registrar",
            type: "POST",
            data: {
                    'datos' :{
                        'nombre'  : nombre,
                        'correo'  : email,
                        'password': pass2,
                        'rol'     : 0
                    }
                  },
            success: function(response){
                if(response == '1'){
                    Swal.fire({
                        title: '¡Éxito!',
                        text: 'El usuario se ha registrado con éxito',
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    }).then((result) => {
                        window.location = "iniciar_sesion.php"
                    })
                }else{
                    console.log(response)
                }
            }
        })
        
    }
})
</script>