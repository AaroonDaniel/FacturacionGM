<?php
class Productos extends Controller
{
    public function __construct()
    {
        session_start();
        parent::__construct();
    }
    public function index()
    {
        $data['categorias']= $this->model->getCategorias();
        $data['medidas']= $this->model->getMedidas();
        $this->views->getView($this, "index", $data);
    }

    public function listar()
    {
        $data = $this->model->getProductos();
        for ($i = 0; $i < count($data); $i++) {
            if ($data[$i]['producto_estado'] == 1) {
                $data[$i]['producto_estado'] = '<span class="badge badge-success">Activo</span>';
                $data[$i]['acciones'] = '<button class="btn btn-warning" type="button" onclick="btnEditarProducto(' . $data[$i]['id_producto'] . ')" title="Editar"><i class="fas fa-edit"></i></button> 
            <button class="btn btn-danger" type="button" onclick="btnInactivarProducto(' . $data[$i]['id_producto'] . ')" title="Inactivar"><i class="fas fa-trash-alt" ></i></button>';
            } else {
                $data[$i]['producto_estado'] = '<span class="badge badge-secondary">Inactivo</span>';
                $data[$i]['acciones'] = '<button class="btn btn-warning" type="button" onclick="btnEditarProducto(' . $data[$i]['id_producto'] . ')" title="Editar"><i class="fas fa-edit"></i></button> 
            <button class="btn btn-success" type="button" onclick="btnActivarProducto(' . $data[$i]['id_producto'] . ')" title="Activar"><i class="fas fa-arrow-circle-up" ></i></button>';
            }
        }
        echo json_encode($data);
        die();
    }

    public function registrar()
    {
        $id_producto = $_POST['id_producto'];
        $codigo = $_POST['codigo'];
        $nombre_producto = $_POST['nombre_producto'];
        $costo_compra = $_POST['costo_compra'];
        $precio_venta = $_POST['precio_venta'];
        $cantidad = $_POST['cantidad'];
        $id_categoria = $_POST['id_categoria'];
        $id_medida = $_POST['id_medida'];

        if (empty($codigo) || empty($nombre_producto)) {
            $msg = "Todos los campos son obligatorios";
        } else {
            if ($id_producto == "") {
                $data = $this->model->registrarProducto($codigo, $nombre_producto, $costo_compra, $precio_venta, $cantidad, $id_categoria, $id_medida);
                if ($data == "ok") {
                    $msg = "si";
                } else {
                    $msg = "Error al registrar producto";
                }
            } else {
                $data = $this->model->modificarProducto($codigo, $nombre_producto, $costo_compra, $precio_venta, $cantidad, $id_categoria, $id_medida, $id_producto);
                if ($data == "modificado") {
                    $msg = "mod";
                } else {
                    $msg = "Error al modificar producto";
                }
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function editar($id)
    {
        $data = $this->model->editarProducto($id);
        echo json_encode($data);
        die();
    }

    public function inactivar($id)
    {
        $this->model->accion(0, $id);
    }

    public function activar($id)
    {
        $this->model->accion(1, $id);
    }
}
