<?php 
session_start();
if(!isset($_SESSION['usuario']) || $_SESSION['rol'] != 1){
    header("location: index.php");
}

$titulo = "Administrar Productos | Dental Club SV";
include("clases/BaseDatos.php");
$objBD = new baseDatos("localhost","dentalclub","root",""); //"server,bd,usuario,password"
//Si se usa XAMPP cambiar la clave a "", Caso contrario dejar "mysql"

include("recursos/admin_header.php");

//Datos para paginación de tabla
$limite = 10;
$paginas = '';
?>
<!-- Cuerpo de página -->
<div class="wrapper">
    <h1 class="title has-text-centered">Administración de Productos</h1>
    <hr>
    <div class="columns">
        <div class="column is-1"></div>
        <div class="column is-10">
            <div id="contenido-resultado"></div>
            <br>

            <hr>
            <h1 class="title has-text-centered is-4" id="titulo">Agregar un Producto</h1>
            <div class="columns">
                <div class="column is-3"></div>
                <div class="column is-6">
                    <div class="card wrapper" tabindex="1" id="agg">
                        <div class="card-container">
                            <form action="" method="post" id="administrador" onsubmit="return(false)">
                                <input type="hidden" name="id_prod" id="id_prod">
                                <div class="field">
                                    <label class="label">Nombre</label>
                                    <div class="control">
                                        <input class="input" type="text" id="nombre" name="nombre">
                                    </div>
                                </div>
                                </div>
                                <div class="field">
                                    <label class="label">Descripcion</label>
                                    <div class="control">
                                        <textarea class="textarea" id="descripcion" name="descripcion"></textarea>
                                    </div>
                                </div>
                                <div class="field">
                                    <label class="label">Imagen</label>
                                    <div class="control">
                                        <input class="input" type="file" id="imagen" name="imagen" accept="image/*">
                                    </div>
                                </div>
                                <div class="field">
                                    <label class="label">Precio</label>
                                    <p class="control has-icons-left">
                                        <input class="input" type="number" min="0.01" step="0.01" id="precio" name="precio">
                                        <span class="icon is-small is-left">
                                            <i class="fas fa-dollar-sign"></i>
                                        </span>
                                </div>
                                <div class="field">
                                    <label class="label">Stock</label>
                                    <div class="control">
                                        <input class="input" type="number" id="stock" name="stock">
                                    </div>
                                </div>
                                <div class="field is-grouped is-grouped-centered">
                                    <p class="control">
                                        <input class="button is-primary" type="button" onclick="insertar()" value="Enviar" id="enviar">
                                    </p>
                                    <p class="control">
                                        <input class="button is-primary" type="button" onclick="update()" value="Actualizar" id="actualizar">
                                    </p>
                                    <p class="control">
                                        <input class="button is-secondary" type="button" value="Cancelar" id="cancelar">
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="column is-3"></div>
            </div>
        </div>
        <div class="column is-1"></div>
    </div>
</div>
<!-- /Cuerpo de página -->
<!--Inclusiones de pie de página-->
<?php
include("recursos/footer.php");
?>
<script>

    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 2000,
        timerProgressBar: true,
        onOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        },
        onClose: (toast) => {
            window.location.reload()
        }
    });

    $(document).ready(function () {
        $("#actualizar").hide();
        $("#cancelar").hide();
        $("#contenido-resultado").load("clases/paginacion.php?tabla=producto&pagina=1");
        $("#paginacion li a").click(function(e) {
            e.preventDefault();
            $("#contenido-resultado").html("cargando...");
            $("#paginacion li a").removeClass('is-current');
            $(this).addClass('is-current');
            var numPag = this.id;
            $("#contenido-resultado").load("clases/paginacion.php?tabla=prod&pagina="+ numPag);
        });
    });

    $("#cancelar").click(function(e){
        $("#titulo").html("Agregar Nuevo Producto");
        $("#actualizar").hide();
        $("#enviar").show();
        $(this).closest('form').find("input[type=text], input[type=file], textarea").val("");
        $("#id_prod").val("");
        $("#agg").focus();
        $("#cancelar").hide();
    });

    function update() {
        validacion = 0;

        id_prod = $("#id_prod").val();
        nombre = $("#nombre").val();
        descripcion = $("#descripcion").val();
        precio = $("#precio").val();
        stock = $("#stock").val();

        if(nombre)
            validacion++

        if(descripcion)
            validacion++
        
        if(precio)
            validacion++

        if(stock)
            validacion++

        if(validacion == 4){
            if($("#imagen").get(0).files.length === 0){
                $.ajax({
                    url: "clases/ajax.php?solicitud=upd_no_img",
                    type: "post",
                    data: {
                        "datos" : {
                            'tabla'       : "producto",
                            'id_prod'     : id_prod,
                            'nombre'      : nombre,
                            'descripcion' : descripcion,
                            'precio'      : precio,
                            'stock'       : stock
                        }
                    },
                    success: function(result){
                        if(result == '1'){
                            Toast.fire({
                                icon: 'success',
                                title: 'Elemento actualizado correctamente'
                            })
                        }else{
                            Toast.fire({
                                icon: 'error',
                                title: 'Ha ocurrido un error'
                            })
                            console.log(result);
                        }
                    }
                })
            }else{
                fd = new FormData();
                imagen = $("#imagen")[0].files[0];
                fd.append("id_prod",id_prod);
                fd.append("imagen",imagen);
                fd.append("nombre",nombre);
                fd.append("descripcion",descripcion);
                fd.append("precio",precio);
                fd.append("stock",stock);
                fd.append("tabla","producto");

                $.ajax({
                    url: "clases/ajax.php?solicitud=upd_si_img",
                    type: "POST",
                    data: fd,
                    processData: false,
                    contentType: false,
                    success:function(result){
                        if(result == '1'){
                            Toast.fire({
                                icon: 'success',
                                title: 'Elemento actualizado correctamente'
                            })
                        }else{
                            Toast.fire({
                                icon: 'error',
                                title: 'Ha ocurrido un error'
                            })
                            console.log(result);
                        }
                    }
                })
            }
        } else {
            Swal.fire({
                title: '¡Error!',
                text: 'Debe rellenar todos los campos',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            })
        }
    }

    function insertar(){
        validacion = 0;

        nombre = $("#nombre").val()
        descripcion = $("#descripcion").val()
        precio = $("#precio").val();
        stock = $("#precio").val();

        if(nombre)
            validacion++;

        if(descripcion)
            validacion++;

        if(precio)
            validacion++;

        if(stock)
            validacion++

        if($("#imagen").get(0).files.length !== 0)
            validacion++;

        if(validacion == 3){
            fd = new FormData();
            imagen = $("#imagen")[0].files[0];
            fd.append("imagen",imagen);
            fd.append("nombre",nombre);
            fd.append("descripcion",descripcion);
            fd.append("precio",precio);
            fd.append("stock",stock);
            fd.append("tabla","producto");

            $.ajax({
                url: "clases/ajax.php?solicitud=insertar",
                type: "POST",
                data: fd,
                processData: false,
                contentType: false,
                success:function(result){
                    if(result == '1'){
                        Toast.fire({
                            icon: 'success',
                            title: 'Elemento insertado correctamente'
                        })
                    }else{
                        Toast.fire({
                            icon: 'error',
                            title: 'Ha ocurrido un error'
                        })
                        console.log(result);
                    }
                }
            })
        } else {
            Swal.fire({
                title: '¡Error!',
                text: 'Debe rellenar todos los campos',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            })
        }
    }

    function editar(id){
        $.ajax({
            url: "clases/ajax.php?solicitud=editar",
            type: "post",
            data: {
                    "datos": {
                        "tabla"  : "producto",
                        "id_prod" : id
                    }
                  },
            success: function(result){
                var obj = JSON.parse(result);
                console.log(obj);
                $("#id_prod").val(obj.id_prod);
                $("#nombre").val(obj.nombre);
                $("#descripcion").val(obj.descripcion);
                $("#precio").val(obj.precio);
                $("#stock").val(obj.stock);
                $("#titulo").html("Editar Producto");
                $("#actualizar").show();
                $("#cancelar").show();
                $("#enviar").hide();
                $("#agg").focus();
            }
        })
    }

    function eliminar(id){
        Swal.fire({
            title: '¿Estás seguro que deseas eliminar esta categoría?',
            text: "Se eliminarán TODOS los pedidos que tengan este producto de manera permanente",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
            }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: "clases/ajax.php?solicitud=eliminar",
                    type: "post",
                    data: {
                            "datos": {
                                "tabla"  : "producto",
                                "id_prod" : id
                          }
                    },
                    success: function(result){
                        if(result == '1'){
                            Toast.fire({
                                icon: 'success',
                                title: 'Elemento eliminado correctamente'
                            })
                        }else{
                            console.log(result)
                        }
                    }
                })
            }
        })
    }
</script>