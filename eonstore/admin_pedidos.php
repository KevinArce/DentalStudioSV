<?php 
session_start();
if(!isset($_SESSION['usuario']) || $_SESSION['rol'] != 1){
    header("location: index.php");
}

$titulo = "Administrar Pedidos | EON Store";
include("clases/BaseDatos.php");
$objBD = new baseDatos("localhost","eonstore","root",""); //"server,bd,usuario,password"
//Si se usa XAMPP cambiar la clave a "", Caso contrario dejar "mysql"

include("recursos/admin_header.php");

//Datos para paginación de tabla
$limite = 10;
$registros = $objBD->leer("pedido","COUNT(*) as cuenta")[0]['cuenta'];
$paginas = ceil($registros/$limite);
?>
<!-- Cuerpo de página -->
<div class="wrapper">
    <h1 class="title has-text-centered">Administración de Pedidos</h1>
    <hr>
    <div class="columns">
        <div class="column is-1"></div>
        <div class="column is-10">
            <div id="contenido-resultado"></div>
            <br>
            <nav class="pagination is-small" role="navigation" aria-label="paginacion">
                <ul class="pagination-list" id="paginacion">
                    <?php
                if(!empty($paginas)){
                    for($i=1;$i<=$paginas;$i++){
                        if($i == 1){
                            ?>
                    <li>
                        <a href="clases/paginacion.php?tabla=pedido&pagina=<?php echo $i;?>"
                            class="pagination-link is-current" id="<?php echo $i ?>"><?php echo $i;?></a>
                    </li>
                    <?php
                        }else{
                            ?>
                    <li>
                        <a href="clases/paginacion.php?tabla=pedido&pagina=<?php echo $i;?>"
                            class="pagination-link" id="<?php echo $i ?>"><?php echo $i;?></a>
                    </li>
                    <?php
                        }
                    }
                }
            ?>
                </ul>
            </nav>
        </div>
        <div class="column is-1"></div>
    </div>
</div>
<!-- Modal de edición -->
<div class="modal" id="modal_estados">
    <div class="modal-background"></div>
    <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title">Editar Estado de Pedido</p>
            <button class="delete borrar" aria-label="close"></button>
        </header>
        <section class="modal-card-body">
            <form>
                <div class="field">
                    <input type="text" class="input" name="id_pedido" id="id_pedido" readonly>
                </div>
                <div class="field">
                    <select name="estado" id="estado" class="input">
                        <option value="1">Pendiente</option>
                        <option value="2">En Proceso</option>
                        <option value="3">Completado</option>
                    </select>
                </div>
            </form>
        </section>
        <footer class="modal-card-foot">
            <button class="button is-success" id="guardar_cambios">Guardar Cambios</button>
            <button class="button borrar">Cancelar</button>
        </footer>
    </div>
</div>
<!-- Modal de vista -->
<div class="modal" id="modal_vista">
    <div class="modal-background"></div>
    <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title">Ver Pedido</p>
            <button class="delete borrar2" aria-label="close"></button>
        </header>
        <section class="modal-card-body">
            <div class="table-container">
                <table class="table is-hoverable is-fullwidth is-bordered">
                    <thead>
                        <tr>
                            <th class="has-background-dark has-text-light">Producto</th>
                            <th class="has-background-dark has-text-light">Cantidad</th>
                        </tr>
                    </thead>
                    <tbody id="resAjax">
                        
                    </tbody>
                </table>
            </div>
        </section>
        <footer class="modal-card-foot">
            <button class="button borrar2">Cerrar</button>
        </footer>
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
    $("#contenido-resultado").load("clases/paginacion.php?tabla=pedido&pagina=1");
    $("#paginacion li a").click(function(e) {
        e.preventDefault();
        $("#contenido-resultado").html("cargando...");
        $("#paginacion li a").removeClass('is-current');
        $(this).addClass('is-current');
        var numPag = this.id;
        $("#contenido-resultado").load("clases/paginacion.php?tabla=pedido&pagina="+ numPag);
    });
});

function eliminar(id){
    Swal.fire({
        title: '¿Estás seguro que deseas eliminar este pedido?',
        text: "Se eliminará de manera permanente",
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
                            "tabla"  : "pedido",
                            "id_pedido" : id
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

function editar(id,estado){
    $("#id_pedido").val(id);
    $("#estado").val(estado);
    $("#modal_estados").addClass("is-active");
}

$(".borrar").click(function(e){
    $("#modal_estados").removeClass("is-active");
})

$(".borrar2").click(function(e){
    $("#modal_vista").removeClass("is-active");
})

$("#guardar_cambios").click(function(e){
    id = $("#id_pedido").val()
    estado = $("#estado").val()
    $.ajax({
        url: "clases/ajax.php?solicitud=actualizar",
        type: "post",
        data: {
                "datos": {
                    "tabla"  : "pedido",
                    "id_pedido" : id,
                    'estado' : estado
                }
        },
        success: function(result){
            if(result == '1'){
                Toast.fire({
                    icon: 'success',
                    title: 'Elemento actualizado correctamente'
                })
            }else{
                console.log(result)
            }
        }
    })
})

var html = "";
function ver(id){
    $.ajax({
        url: "clases/ajax.php?solicitud=ver",
        type: "post",
        data: {
                "datos": {
                    "id_pedido" : id,
                }
        },
        success: function(result){
            var obj = JSON.parse(result);
            html = "";
            obj.forEach(element => anadir(element));
            $("#resAjax").html(html);
            $("#modal_vista").addClass("is-active");
        }
    })

}

function anadir(element){
    html += "<tr>"
    html += "<td>"+element.nombre+"</td>"
    html += "<td>"+element.cantidad+"</td>"
    html += "</tr>"
}
</script>