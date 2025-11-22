<?php
class MedidasModel extends Query{
    private $descripcion_medida, $descripcion_corta, $unidad_siat,$id_medida, $estado;
    public function __construct()
    {
        parent::__construct();
    }

    public function getMedidas(){
        $sql = "select * from medidas";
        $data = $this->selectAll($sql);
        return $data;
    }


    public function registrarMedida(string $descripcion_medida, string $descripcion_corta, int $unidad_siat ){
        $this->descripcion_medida = $descripcion_medida;
        $this->descripcion_corta = $descripcion_corta;
        $this->unidad_siat = $unidad_siat;
        $sql = "INSERT INTO medidas(descripcion_medida, descripcion_corta,unidad_siat) VALUES (?,?,?)";
        $datos = array($this->descripcion_medida, $this->descripcion_corta, $this->unidad_siat);
        $data=$this->save($sql,$datos);
        if($data==1){
            $res = "ok";
        }else{
            $res = "Error ";
        }
        return $res;
    } 

    public function editarMedida(int $id){
        $sql = "select * from medidas where id_medida = '".$id."'";
        $data = $this->select($sql);
        return $data;
    }

    public function modificarMedida(string $descripcion_medida, string $descripcion_corta, int $unidad_siat ,int $id_medida ){
        $this->id_medida = $id_medida;
        $this->descripcion_medida = $descripcion_medida;
        $this->descripcion_corta = $descripcion_corta;
        $this->unidad_siat = $unidad_siat;
        $sql = "UPDATE medidas SET descripcion_medida=?,descripcion_corta=? , unidad_siat=?  WHERE id_medida=?";
        $datos = array($this->descripcion_medida, $this->descripcion_corta,$this->unidad_siat, $this->id_medida);
        $data=$this->save($sql,$datos); 
        if($data==1){
            $res = "modificado";
        }else{
            $res = "Error al modificar";
        }
        return $res;
    }

    public function accion(int $estado, int $id){
        $this->id_medida = $id;
        $this->estado = $estado;
        $sql = "UPDATE medidas SET medida_estado = ? WHERE id_medida = ?";
        $datos=array($this->estado, $this->id_medida);
        $data=$this->save($sql,$datos);
        return $data;
    }

    
}
?>