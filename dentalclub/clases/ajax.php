<?php
include("BaseDatos.php");

class AjaxRequest{
    private $objBD; 
    public $resultado;

    public function __construct(){
        $this->objBD = new baseDatos("localhost","dentalclub","root","");
    }

    public function procesar_solicitud($request,$datos = null){
        if($request == "correo"){
            $correos = $this->objBD->leer("usuario","correo");
            $this->resultado = count($correos);
        }elseif($request == "registrar"){
            $this->resultado = $this->objBD->insertar("usuario",$datos);
        }elseif($request== "actualizar"){
            extract($datos);
            $this->resultado = $this->objBD->actualizar("pedido",['estado' => $estado],['id_pedido' => $id_pedido]);
        }elseif($request== "ver"){
            extract($datos);
            $this->resultado = json_encode($this->objBD->consulta_personalizada("SELECT productoXpedido.*, pedido.usuario, pedido.fecha, pedido.hora, producto.nombre FROM productoXpedido INNER JOIN pedido ON pedido.id_pedido = productoXpedido.id_pedido INNER JOIN producto on producto.id_prod = productoXpedido.id_producto WHERE productoXpedido.id_pedido = $id_pedido"));
        }elseif($request == "editar"){
            extract($datos);
            unset($datos['tabla']);
            $this->resultado = json_encode($this->objBD->leer($tabla,"*",$datos)[0]);
        }elseif($request == "eliminar"){
            extract($datos);
            unset($datos['tabla']);
            $this->resultado = $this->objBD->borrar($tabla,$datos);
        }elseif($request == "insertar"){
            if($_POST["tabla"] == "categoria"){
                $directorio = "recursos/img/";
            }elseif($_POST["tabla"] == "marca"){
                $directorio = "recursos/img/marcas/";
            }else{
                $directorio = "recursos/img/productos/";
            }
            $archivo_destino = $directorio .$_FILES["imagen"]["name"];
            $subidaOk = 1;
            $esImagen = getimagesize($_FILES["imagen"]["tmp_name"]);
            if($esImagen !== false){
                $subidaOk = 1;
            }else{
                $subidaOk = 0;
            }

            if(file_exists($archivo_destino)){
                $archivo_destino = $directorio .time().$_FILES["imagen"]["name"];
            }

            if(move_uploaded_file($_FILES["imagen"]["tmp_name"],"../".$archivo_destino)){
                $_POST["imagen"] = $archivo_destino;
            }else{
                $this->resultado = 0;
                die();    
            }
            $tabla = $_POST["tabla"];
            unset($_POST["tabla"]);
            $this->resultado = $this->objBD->insertar($tabla,$_POST);
        }elseif($request == "upd_no_img"){
            extract($datos);
            unset($datos['tabla']);
            switch($tabla){
                case "categoria":
                    unset($datos['id_cat']);
                    $this->resultado = $this->objBD->actualizar($tabla,$datos,array("id_cat" => $id_cat));
                break;
                case "marca":
                    unset($datos['id_marca']);
                    $this->resultado = $this->objBD->actualizar($tabla,$datos,array("id_marca" => $id_marca));
                break;
                case "producto":
                    unset($datos['id_prod']);
                    $this->resultado = $this->objBD->actualizar($tabla,$datos,array("id_prod" => $id_prod));
                break;
            }
        }elseif($request == "upd_si_img"){
            if($_POST["tabla"] == "categoria"){
                $directorio = "recursos/img/";
            }elseif($_POST["tabla"] == "marca"){
                $directorio = "recursos/img/marcas/";
            }else{
                $directorio = "recursos/img/productos/";
            }
            $archivo_destino = $directorio .$_FILES["imagen"]["name"];
            $subidaOk = 1;
            $esImagen = getimagesize($_FILES["imagen"]["tmp_name"]);
            if($esImagen !== false){
                $subidaOk = 1;
            }else{
                $subidaOk = 0;
            }

            if(file_exists($archivo_destino)){
                $archivo_destino = $directorio .time().$_FILES["imagen"]["name"];
            }

            if(move_uploaded_file($_FILES["imagen"]["tmp_name"],"../".$archivo_destino)){
                $_POST["imagen"] = $archivo_destino;
            }else{
                $this->resultado = 0;
                die();    
            }
            $tabla = $_POST["tabla"];
            unset($_POST["tabla"]);
            switch($tabla){
                case "categoria":
                    $id_cat = $_POST["id_cat"];
                    unset($_POST["id_cat"]);
                    $this->resultado = $this->objBD->actualizar($tabla,$_POST,array("id_cat" => $id_cat));
                break;
                case "marca":
                    $id_marca = $_POST["id_marca"];
                    unset($_POST["id_marca"]);
                    $this->resultado = $this->objBD->actualizar($tabla,$_POST,array("id_marca" => $id_marca));
                break;
                case "producto":
                    $id_prod = $_POST["id_prod"];
                    unset($_POST["id_prod"]);
                    $this->resultado = $this->objBD->actualizar($tabla,$_POST,array("id_prod" => $id_prod));
                break;
            }
        }
    }
}

$ar = new AjaxRequest();
$solicitud = $_GET['solicitud'];
$datos = (isset($_POST['datos'])) ? $_POST["datos"] : null;
$ar->procesar_solicitud($solicitud,$datos);
echo $ar->resultado;
?>