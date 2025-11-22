<?php
class PedidosModel extends Query
{
    private $id_factura, $factura_estado;
    public function __construct()
    {
        parent::__construct();
    }

    public function buscarCliente(string $documentoid)
    {
        $sql = "select * from clientes where documentoid='" . $documentoid . "'";
        $data = $this->select($sql);
        return $data;
    }

    public function accion(int $estado, int $id){
        $this->id_factura = $id;
        $this->factura_estado = $estado;
        $sql = "UPDATE facturas SET factura_estado = ? WHERE id_factura = ?";
        $datos=array($this->factura_estado, $this->id_factura);
        $data=$this->save($sql,$datos);
        return $data;
    }

    public function buscarProducto(string $codigo)
    {
        $sql = "SELECT p.*, m.descripcion_corta, m.unidad_siat, c.codigoProductoSin
            FROM productos p
            INNER JOIN medidas m ON m.id_medida = p.id_medida
            inner join categorias c on c.id_categoria=p.id_categoria
            WHERE p.codigo = '" . $codigo . "'";
        $data = $this->select($sql);
        return $data;
    }

    public function getFacturas()
    {
        $sql = "select f.*,c.razon_social, c.documentoid 
        from facturas f
        inner join clientes c on c.id_cliente=f.id_cliente";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getFactura(int $id_factura)
    {
        $sql = "select * 
        from facturas  where id_factura=$id_factura";
        $data = $this->select($sql);
        return $data;
    }

    public function guardarFactura(int $id_cliente, int $numeroFactura, string $cuf, string $fechaEmision, int $codigoMetodoPago, float $montoTotal, float $montoTotalSujetoIva, float $descuentoAdicional, string $productos, string $codigoRecepcion)
    {
        $sql = "INSERT INTO facturas (id_cliente, numeroFactura, cuf, fechaEmision, codigoMetodoPago, montoTotal, montoTotalSujetoIva, descuentoAdicional, productos, codigoRecepcion) VALUES (?,?,?,?,?,?,?,?,?,?)";

        $datos = array(
            $id_cliente,
            $numeroFactura,
            $cuf,
            $fechaEmision,
            $codigoMetodoPago,
            $montoTotal,
            $montoTotalSujetoIva,
            $descuentoAdicional,
            $productos,
            $codigoRecepcion
        );

        $data = $this->save($sql, $datos);
        return $data == 1 ? "ok" : "error";
    }
}
