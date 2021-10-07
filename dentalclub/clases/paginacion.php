<?php
include("BaseDatos.php");
$objBD = new baseDatos("localhost","dentalclub","root",""); //"server,bd,usuario,password"
//Si se usa XAMPP cambiar la clave a "", Caso contrario dejar "mysql"

$limite = 10;
$pagina = (isset($_GET['pagina'])) ? $_GET['pagina'] : 1;
$tabla = $_GET['tabla'];
$iniciar_en = ($pagina-1)*$limite;

$sql = "SELECT * FROM $tabla LIMIT $iniciar_en, $limite";
$resultado = $objBD->consulta_personalizada($sql);

switch($tabla){
    case "categoria":
        ?>
        <div class="table-container">
            <table class="table is-bordered is-hoverable is-narrow">
                <thead class="has-background-grey-dark">
                    <th class="has-text-light">ID</th>
                    <th class="has-text-light">Nombre</th>
                    <th class="has-text-light">Descripción</th>
                    <th class="has-text-light">Imagen</th>
                    <th class="has-text-light">URL</th>
                    <th class="has-text-light">Acciones</th>
                </thead>
                <tbody>
                    <?php
                        if(count($resultado) == 0){
                            ?>
                            <tr>
                                <td colspan="6">No hay elementos para mostrar</td>
                            </tr>
                            <?php
                        }
                        foreach($resultado as $r){
                            extract($r);
                            echo "
                                <tr>
                                    <td>$id_cat</td>
                                    <td>$nombre</td>
                                    <td>$descripcion</td>
                                    <td>
                                        <figure class=\"image is-48x48\">
                                            <img src=\"$imagen\" alt=\"$nombre\">
                                        </figure>
                                    </td>
                                    <td>$url</td>
                                    <td>
                                        <div class=\"field is-grouped\">
                                            <button class=\"button is-primary is-small\" onclick=\"editar($id_cat)\">
                                                <span class=\"icon is-small\">
                                                    <i class=\"fas fa-edit\"></i>
                                                </span>
                                            </button>
                                            &nbsp;
                                            <button class=\"button is-danger is-small\" onclick=\"eliminar($id_cat)\">
                                                <span class=\"icon is-small\">
                                                    <i class=\"fas fa-trash\"></i>
                                                </span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            ";
                        }
                    ?>
                </tbody>
            </table>
        </div>
    <?php
    break;
    case 'marca':
    ?>
        <div class="table-container">
            <table class="table is-bordered is-hoverable is-narrow is-fullwidth">
                <thead class="has-background-grey-dark">
                    <th class="has-text-light">ID</th>
                    <th class="has-text-light">Nombre</th>
                    <th class="has-text-light">Descripción</th>
                    <th class="has-text-light">Imagen</th>
                    <th class="has-text-light">Acciones</th>
                </thead>
                <tbody>
                    <?php
                        if(count($resultado) == 0){
                            ?>
                            <tr>
                                <td colspan="5">No hay elementos para mostrar</td>
                            </tr>
                            <?php
                        }
                        foreach($resultado as $r){
                            extract($r);
                            echo "
                                <tr>
                                    <td>$id_marca</td>
                                    <td>$nombre</td>
                                    <td>$descripcion</td>
                                    <td>
                                        <figure class=\"image is-48x48\">
                                            <img src=\"$imagen\" alt=\"$nombre\">
                                        </figure>
                                    </td>
                                    <td>
                                        <div class=\"field is-grouped\">
                                            <button class=\"button is-primary is-small\" onclick=\"editar($id_marca)\">
                                                <span class=\"icon is-small\">
                                                    <i class=\"fas fa-edit\"></i>
                                                </span>
                                            </button>
                                            &nbsp;
                                            <button class=\"button is-danger is-small\" onclick=\"eliminar($id_marca)\">
                                                <span class=\"icon is-small\">
                                                    <i class=\"fas fa-trash\"></i>
                                                </span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            ";
                        }
                    ?>
                </tbody>
            </table>
        </div>
    <?php
    break;
    case 'producto':
    ?>
        <div class="table-container">
            <table class="table is-bordered is-hoverable is-narrow is-fullwidth">
                <thead class="has-background-grey-dark">
                    <th class="has-text-light">ID</th>
                    <th class="has-text-light">Nombre</th>
                    <th class="has-text-light">Marca</th>
                    <th class="has-text-light">Modelo</th>
                    <th class="has-text-light">Categoria</th>
                    <th class="has-text-light">Descripción</th>
                    <th class="has-text-light">Precio</th>
                    <th class="has-text-light">Existencias</th>
                    <th class="has-text-light">Imagen</th>
                    <th class="has-text-light">Acciones</th>
                </thead>
                <tbody>
                    <?php
                        if(count($resultado) == 0){
                            ?>
                            <tr>
                                <td colspan="10">No hay elementos para mostrar</td>
                            </tr>
                            <?php
                        }
                        foreach($resultado as $r){
                            extract($r);
                            //$nom_marca = ($objBD->leer("marca","nombre",array("id_marca" => $id_marca)))[0]["nombre"];
                            //$nom_cat = ($objBD->leer("categoria","nombre",array("id_cat" => $id_cat)))[0]["nombre"];
                            echo "
                                <tr>
                                    <td>$id_prod</td>
                                    <td>$nombre</td>
                                    <td></td>
                                    <td>$modelo</td>
                                    <td></td>
                                    <td>$descripcion</td>
                                    <td>$".number_format($precio,2)."</td>
                                    <td>$stock</td>
                                    <td>
                                        <figure class=\"image is-48x48\">
                                            <img src=\"$imagen\" alt=\"$nombre\">
                                        </figure>
                                    </td>
                                    <td>
                                        <div class=\"field is-grouped\">
                                            <button class=\"button is-primary is-small\" onclick=\"editar($id_prod)\">
                                                <span class=\"icon is-small\">
                                                    <i class=\"fas fa-edit\"></i>
                                                </span>
                                            </button>
                                            &nbsp;
                                            <button class=\"button is-danger is-small\" onclick=\"eliminar($id_prod)\">
                                                <span class=\"icon is-small\">
                                                    <i class=\"fas fa-trash\"></i>
                                                </span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            ";
                        }
                    ?>
                </tbody>
            </table>
        </div>
    <?php
    break;
    case 'pedido':
        ?>
        <div class="table-container">
            <table class="table is-bordered is-hoverable is-narrow is-fullwidth">
                <thead class="has-background-grey-dark">
                    <th class="has-text-light">ID</th>
                    <th class="has-text-light">Fecha</th>
                    <th class="has-text-light">Hora</th>
                    <th class="has-text-light">Usuario</th>
                    <th class="has-text-light">Estado</th>
                    <th class="has-text-light">Acciones</th>
                </thead>
                <tbody>
                    <?php
                        if(count($resultado) == 0){
                            ?>
                            <tr>
                                <td colspan="6">No hay elementos para mostrar</td>
                            </tr>
                            <?php
                        }
                        foreach($resultado as $r){
                            extract($r);
                            if($estado == 1)
                                $nom_estado = "Pendiente";
                            elseif($estado == 2)
                                $nom_estado = "En proceso";
                            elseif($estado == 3)
                                $nom_estado = "Completado";
                            echo "
                                <tr>
                                    <td>$id_pedido</td>
                                    <td>$fecha</td>
                                    <td>$hora</td>
                                    <td>$usuario</td>
                                    <td>$nom_estado</td>
                                    <td>
                                        <div class=\"field is-grouped\">
                                            <button class=\"button is-success is-small\" onclick=\"ver($id_pedido)\">
                                                <span class=\"icon is-small\">
                                                    <i class=\"fas fa-eye\"></i>
                                                </span>
                                            </button>
                                            &nbsp;
                                            <button class=\"button is-primary is-small\" onclick=\"editar($id_pedido,$estado)\">
                                                <span class=\"icon is-small\">
                                                    <i class=\"fas fa-edit\"></i>
                                                </span>
                                            </button>
                                            &nbsp;
                                            <button class=\"button is-danger is-small\" onclick=\"eliminar($id_pedido)\">
                                                <span class=\"icon is-small\">
                                                    <i class=\"fas fa-trash\"></i>
                                                </span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            ";
                        }
                    ?>
                </tbody>
            </table>
        </div>
    <?php
    break;
}

