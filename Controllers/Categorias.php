<?php
class Categorias extends Controller
{
    public function __construct()
    {
        session_start();
        parent::__construct();
    }
    public function index()
    {

        $this->views->getView($this, "index");
    }

    public function listar()
    {
        $data = $this->model->getCategorias();
        for ($i = 0; $i < count($data); $i++) {
            if ($data[$i]['categoria_estado'] == 1) {
                $data[$i]['categoria_estado'] = '<span class="badge badge-success">Activo</span>';
                $data[$i]['acciones'] = '<button class="btn btn-warning" type="button" onclick="btnEditarCategoria(' . $data[$i]['id_categoria'] . ')" title="Editar"><i class="fas fa-edit"></i></button> 
            <button class="btn btn-danger" type="button" onclick="btnInactivarCategoria(' . $data[$i]['id_categoria'] . ')" title="Inactivar"><i class="fas fa-trash-alt" ></i></button>';
            } else {
                $data[$i]['categoria_estado'] = '<span class="badge badge-secondary">Inactivo</span>';
                $data[$i]['acciones'] = '<button class="btn btn-warning" type="button" onclick="btnEditarCategoria(' . $data[$i]['id_categoria'] . ')" title="Editar"><i class="fas fa-edit"></i></button> 
            <button class="btn btn-success" type="button" onclick="btnActivarCategoria(' . $data[$i]['id_categoria'] . ')" title="Activar"><i class="fas fa-arrow-circle-up" ></i></button>';
            }
        }
        echo json_encode($data);
        die();
    }

    public function registrar()
    {
        $id_categoria = $_POST['id_categoria'];
        $nombre_categoria = $_POST['nombre_categoria'];
        $codigoProductoSin = $_POST['codigoProductoSin'];
        if (empty($nombre_categoria) || empty($codigoProductoSin)) {
            $msg = "Todos los campos son obligatorios";
        } else {
            if ($id_categoria == "") {
                $data = $this->model->registrarCategoria($nombre_categoria, $codigoProductoSin);
                if ($data == "ok") {
                    $msg = "si";
                } else {
                    $msg = "Error al registrar categoria";
                }
            } else {
                $data = $this->model->modificarCategoria($nombre_categoria, $codigoProductoSin, $id_categoria);
                if ($data == "modificado") {
                    $msg = "mod";
                } else {
                    $msg = "Error al modificar categoria";
                }
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function editar($id)
    {
        $data = $this->model->editarCategoria($id);
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
