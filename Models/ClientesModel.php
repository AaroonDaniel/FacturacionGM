<?php
class ClientesModel extends Query{
    private $documentoid, $complementoid, $razon_social, $cliente_email ,$id_cliente, $estado;
    public function __construct()
    {
        parent::__construct();
    }

    public function getClientes(){
        $sql = "select * from clientes";
        $data = $this->selectAll($sql);
        return $data;
    }


    public function registrarCliente(string $documentoid, string $complementoid, string $razon_social, string $cliente_email){
        $this->documentoid = $documentoid;
        $this->complementoid = $complementoid;
        $this->razon_social = $razon_social;
        $this->cliente_email = $cliente_email;
        $sql = "INSERT INTO clientes(documentoid, complementoid, razon_social, cliente_email) VALUES (?,?,?,?)";
        $datos = array($this->documentoid, $this->complementoid, $this->razon_social, $this->cliente_email);
        $data=$this->save($sql,$datos);
        if($data==1){
            $res = "ok";
        }else{
            $res = "Error ";
        }
        return $res;
    } 

    public function editarCliente(int $id){
        $sql = "select * from clientes where id_cliente = '".$id."'";
        $data = $this->select($sql);
        return $data;
    }

    public function modificarCliente(string $documentoid, string $complementoid, string $razon_social, string $cliente_email, int $id_cliente ){
        $this->id_cliente = $id_cliente;
        $this->documentoid = $documentoid;
        $this->complementoid = $complementoid;
        $this->razon_social = $razon_social;
        $this->cliente_email = $cliente_email;
        
        $sql = "UPDATE clientes SET documentoid=?,complementoid=?, razon_social=?, cliente_email=?  WHERE id_cliente=?";
        $datos = array($this->documentoid, $this->complementoid, $this->razon_social, $this->cliente_email , $this->id_cliente);
        $data=$this->save($sql,$datos); 
        if($data==1){
            $res = "modificado";
        }else{
            $res = "Error al modificar";
        }
        return $res;
    }

    public function accion(int $estado, int $id){
        $this->id_cliente = $id;
        $this->estado = $estado;
        $sql = "UPDATE clientes SET cliente_estado = ? WHERE id_cliente = ?";
        $datos=array($this->estado, $this->id_cliente);
        $data=$this->save($sql,$datos);
        return $data;
    }

    
}
?>