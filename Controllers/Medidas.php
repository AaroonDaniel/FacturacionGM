<?php
class Medidas extends Controller
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
        $data = $this->model->getMedidas();
        for ($i = 0; $i < count($data); $i++) {
            if ($data[$i]['medida_estado'] == 1) {
                $data[$i]['medida_estado'] = '<span class="badge badge-success">Activo</span>';
                $data[$i]['acciones'] = '<button class="btn btn-warning" type="button" onclick="btnEditarMedida(' . $data[$i]['id_medida'] . ')" title="Editar"><i class="fas fa-edit"></i></button> 
            <button class="btn btn-danger" type="button" onclick="btnInactivarMedida(' . $data[$i]['id_medida'] . ')" title="Inactivar"><i class="fas fa-trash-alt" ></i></button>';
            } else {
                $data[$i]['medida_estado'] = '<span class="badge badge-secondary">Inactivo</span>';
                $data[$i]['acciones'] = '<button class="btn btn-warning" type="button" onclick="btnEditarMedida(' . $data[$i]['id_medida'] . ')" title="Editar"><i class="fas fa-edit"></i></button> 
            <button class="btn btn-success" type="button" onclick="btnActivarMedida(' . $data[$i]['id_medida'] . ')" title="Activar"><i class="fas fa-arrow-circle-up" ></i></button>';
            }
        }
        echo json_encode($data);
        die();
    }

    public function registrar()
    {
        $id_medida = $_POST['id_medida'];
        $descripcion_medida = $_POST['descripcion_medida'];
        $descripcion_corta = $_POST['descripcion_corta'];
        $unidad_siat = $_POST['unidad_siat'];
        if (empty($descripcion_medida) || empty($descripcion_corta)|| empty($unidad_siat)) {
            $msg = "Todos los campos son obligatorios";
        } else {
            if ($id_medida == "") {
                $data = $this->model->registrarMedida($descripcion_medida, $descripcion_corta, $unidad_siat);
                if ($data == "ok") {
                    $msg = "si";
                } else {
                    $msg = "Error al registrar medida";
                }
            } else {
                $data = $this->model->modificarMedida($descripcion_medida, $descripcion_corta, $unidad_siat, $id_medida);
                if ($data == "modificado") {
                    $msg = "mod";
                } else {
                    $msg = "Error al modificar medida";
                }
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function editar($id)
    {
        $data = $this->model->editarMedida($id);
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
