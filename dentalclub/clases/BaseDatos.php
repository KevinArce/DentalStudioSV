<?php
class baseDatos {
    
    private $dbh;
    public function __construct($host,$dbname,$user,$pass)
    {
        try {
            $this->dbh = new PDO('mysql:host='.$host.';dbname='.$dbname, $user, $pass);
            $this->dbh->exec("SET CHARACTER SET utf8");
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch (PDOException $e) {
            echo "Error: ".$e->getMessage();
            die();
        }
    }
    public function leer($tabla,$campos,$condicion = null,$criterio = null){
        try {
            $sQuery = "select $campos from $tabla where ";
            $aCondiciones = array();
            if($condicion != null){
                foreach($condicion as $k => $v){
                    $aCondiciones[] = $k."= :".$k;
                }
                $cond = implode(" and ",$aCondiciones);
                if($criterio != null){
                    $query = $this->dbh->prepare($sQuery.$cond." ".$criterio);
                }else{
                $query = $this->dbh->prepare($sQuery.$cond);
                }
                foreach($condicion as $k => &$v){
                    $query->bindParam(":".$k,$v);
                }
            }else{
                if($criterio != null){
                    $query = $this->dbh->prepare("select $campos from $tabla"." ".$criterio);
                }else{
                    $query = $this->dbh->prepare("select $campos from $tabla");
                }
            }
            $query->execute();
            $arrResultados = array();
            while($row = $query->fetch(PDO::FETCH_ASSOC)){
                $arrResultados[] = $row;
            }
            return $arrResultados;
        } catch (PDOException $e) {
            return "Error: ".$e->getMessage();
            die();
        }
    }
    public function insertar($tabla,$valores){
        try {
            $sQuery = "insert into $tabla(";
            foreach($valores as $k => $v){
                $aCampos[] = $k;
                $aPlaceholder[] = ":".$k;
            }
            $campos = implode(",",$aCampos);
            $placeholder = implode(",",$aPlaceholder);
            $sQuery.= $campos.") Values (".$placeholder.")";
            $query = $this->dbh->prepare($sQuery);
            foreach ($valores as $k => &$v) {
                $query->bindParam(":".$k,$v);
            }
            $query->execute();
            return $query->rowCount();
        } catch (PDOException $e) {
            return "Error: ".$e->getMessage();
            die();   
        }
    }
    public function borrar($tabla,$condicion = null){
        try {
            $sQuery = "delete from $tabla where ";
            $aCondiciones = array();
            if($condicion != null){
                foreach($condicion as $k => $v){
                    $aCondiciones[] = $k."= :".$k;
                }
                $cond = implode(" and ",$aCondiciones);
                $query = $this->dbh->prepare($sQuery.$cond);
                foreach($condicion as $k => &$v){
                    $query->bindParam(":".$k,$v);
                }
            }else{
                $query = $this->dbh->prepare("delete from $tabla");
            }
            $query->execute();
            return $query->rowCount();
        } catch (PDOException $e) {
            return "Error: ".$e->getMessage();
            die();   
        }
    }
    public function consulta_personalizada($sQuery){
        try{
            $query = $this->dbh->prepare($sQuery);
            $query->execute();
            $arrResultados = array();
            while($row = $query->fetch(PDO::FETCH_ASSOC)){
                $arrResultados[] = $row;
            }
            return $arrResultados;
        } catch (PDOException $e) {
            return "Error: ".$e->getMessage();
            die();   
        }
    }
	
    public function actualizar($tabla,$campos,$condicion = null){
        try {
            $sQuery = "UPDATE $tabla SET ";
            $arrUpdate = array();
            $strUpdate = "";
            foreach($campos as $nombre => $valor){
                $arrUpdate[] = $nombre." = :".$nombre;
            }
            $strUpdate = implode(",",$arrUpdate);
            $sQuery .= $strUpdate;
            if($condicion != null){
                $sQuery .= " WHERE ";
                foreach($condicion as $k => $v){
                    $aCondiciones[] = $k."= :".$k;
                }
                $cond = implode(" and ",$aCondiciones);
                $sQuery .= $cond;
                $query = $this->dbh->prepare($sQuery);
                foreach ($campos as $k => &$v) {
                    $query->bindParam(":".$k,$v);
                }
                foreach ($condicion as $k => &$v) {
                    $query->bindParam(":".$k,$v);
                }
            }else{
                $query = $this->dbh->prepare($sQuery);
                foreach ($campos as $k => &$v) {
                    $query->bindParam(":".$k,$v);
                }
            }
            $query->execute();
            return $query->rowCount();
        } catch (PDOException $e) {
            return "Error: ".$e->getMessage();
            die();   
        }
    }
}
?>