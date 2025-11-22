<?php
class Clientes extends Controller
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
        $data = $this->model->getClientes();
        for ($i = 0; $i < count($data); $i++) {
            if ($data[$i]['cliente_estado'] == 1) {
                $data[$i]['cliente_estado'] = '<span class="badge badge-success">Activo</span>';
                $data[$i]['acciones'] = '<button class="btn btn-warning" type="button" onclick="btnEditarCliente(' . $data[$i]['id_cliente'] . ')" title="Editar"><i class="fas fa-edit"></i></button> 
            <button class="btn btn-danger" type="button" onclick="btnInactivarCliente(' . $data[$i]['id_cliente'] . ')" title="Inactivar"><i class="fas fa-trash-alt" ></i></button>';
            } else {
                $data[$i]['cliente_estado'] = '<span class="badge badge-secondary">Inactivo</span>';
                $data[$i]['acciones'] = '<button class="btn btn-warning" type="button" onclick="btnEditarCliente(' . $data[$i]['id_cliente'] . ')" title="Editar"><i class="fas fa-edit"></i></button> 
            <button class="btn btn-success" type="button" onclick="btnActivarCliente(' . $data[$i]['id_cliente'] . ')" title="Activar"><i class="fas fa-arrow-circle-up" ></i></button>';
            }
        }
        echo json_encode($data);
        die();
    }

    public function registrar()
    {
        $id_cliente = $_POST['id_cliente'];
        $documentoid = $_POST['documentoid'];
        $complementoid = $_POST['complementoid'];
        $razon_social = $_POST['razon_social'];
        $cliente_email = $_POST['cliente_email'];

        if (empty($documentoid) || empty($razon_social)) {
            $msg = "Todos los campos son obligatorios";
        } else {
            if ($id_cliente == "") {
                $data = $this->model->registrarCliente($documentoid, $complementoid, $razon_social, $cliente_email);
                if ($data == "ok") {
                    $msg = "si";
                } else {
                    $msg = "Error al registrar cliente";
                }
            } else {
                $data = $this->model->modificarCliente($documentoid, $complementoid, $razon_social, $cliente_email, $id_cliente);
                if ($data == "modificado") {
                    $msg = "mod";
                } else {
                    $msg = "Error al modificar cliente";
                }
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function editar($id)
    {
        $data = $this->model->editarCliente($id);
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
