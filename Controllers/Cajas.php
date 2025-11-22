<?php
class Cajas extends Controller
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
        $data = $this->model->getCajas();
        for ($i = 0; $i < count($data); $i++) {
            if ($data[$i]['caja_estado'] == 1) {
                $data[$i]['caja_estado'] = '<span class="badge badge-success">Activo</span>';
                $data[$i]['acciones'] = '<button class="btn btn-warning" type="button" onclick="btnEditarCaja(' . $data[$i]['id_caja'] . ')" title="Editar"><i class="fas fa-edit"></i></button> 
            <button class="btn btn-danger" type="button" onclick="btnInactivarCaja(' . $data[$i]['id_caja'] . ')" title="Inactivar"><i class="fas fa-trash-alt" ></i></button>';
            } else {
                $data[$i]['caja_estado'] = '<span class="badge badge-secondary">Inactivo</span>';
                $data[$i]['acciones'] = '<button class="btn btn-warning" type="button" onclick="btnEditarCaja(' . $data[$i]['id_caja'] . ')" title="Editar"><i class="fas fa-edit"></i></button> 
            <button class="btn btn-success" type="button" onclick="btnActivarCaja(' . $data[$i]['id_caja'] . ')" title="Activar"><i class="fas fa-arrow-circle-up" ></i></button>';
            }
        }
        echo json_encode($data);
        die();
    }

    public function registrar()
    {
        $id_caja = $_POST['id_caja'];
        $caja = $_POST['caja'];

        if (empty($caja)) {
            $msg = "Todos los campos son obligatorios";
        } else {
            if ($id_caja == "") {
                $data = $this->model->registrarCaja($caja);
                if ($data == "ok") {
                    $msg = "si";
                } else {
                    $msg = "Error al registrar caja";
                }
            } else {
                $data = $this->model->modificarCaja($caja, $id_caja);
                if ($data == "modificado") {
                    $msg = "mod";
                } else {
                    $msg = "Error al modificar caja";
                }
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function editar($id)
    {
        $data = $this->model->editarCaja($id);
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
