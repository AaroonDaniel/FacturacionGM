<?php
class Usuarios extends Controller
{
    public function __construct()
    {
        session_start();
        parent::__construct();
    }
    public function index()
    {
        $data['usuarios'] = $this->model->getUsuarios();
        $this->views->getView($this, "index", $data);
    }

    public function validar()
    {
        if (empty($_POST['nick']) || empty($_POST['clave'])) {
            $msg = "Todos los campos son obligatorios";
        } else {
            $nick = $_POST['nick'];
            $clave = $_POST['clave'];

            $data = $this->model->getUsuario($nick, md5($clave));
            if ($data) {
                $_SESSION['id_usuario'] = $data['id_usuario'];
                $_SESSION['nick'] = $data['nick'];
                $_SESSION['nombre'] = $data['nombre'];
                $msg = "ok";
            } else {
                $msg = "Usuario o clave incorrecta";
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function listar()
    {
        $data = $this->model->getUsuarios();
        for ($i = 0; $i < count($data); $i++) {
            if ($data[$i]['usuario_estado'] == 1) {
                $data[$i]['usuario_estado'] = '<span class="badge badge-success">Activo</span>';
                $data[$i]['acciones'] = '<button class="btn btn-warning" type="button" onclick="btnEditarUsuario(' . $data[$i]['id_usuario'] . ')" title="Editar"><i class="fas fa-edit"></i></button> 
            <button class="btn btn-danger" type="button" onclick="btnInactivarUsuario(' . $data[$i]['id_usuario'] . ')" title="Inactivar"><i class="fas fa-trash-alt" ></i></button>';
            } else {
                $data[$i]['usuario_estado'] = '<span class="badge badge-secondary">Inactivo</span>';
                $data[$i]['acciones'] = '<button class="btn btn-warning" type="button" onclick="btnEditarUsuario(' . $data[$i]['id_usuario'] . ')" title="Editar"><i class="fas fa-edit"></i></button> 
            <button class="btn btn-success" type="button" onclick="btnActivarUsuario(' . $data[$i]['id_usuario'] . ')" title="Activar"><i class="fas fa-arrow-circle-up" ></i></button>';
            }
            
        }
        echo json_encode($data);
        die();
    }

    public function registrar()
    {
        $id_usuario = $_POST['id_usuario'];
        $nick = $_POST['nick'];
        $nombre = $_POST['nombre'];
        $clave = $_POST['clave'];
        $confirmar = $_POST['confirmar'];
        if (empty($nick) || empty($nombre) ) {
            $msg = "Todos los campos son obligatorios";
        } else {
            if ($id_usuario == "") {
                if ($clave != $confirmar) {
                    $msg = "Las contraseñas no coinciden";
                } else {
                    $data = $this->model->registrarUsuario($nick, $nombre, md5($clave));
                    if ($data == "ok") {
                        $msg = "si";
                    } else {
                        $msg = "Error al registrar usuario";
                    }
                }
            }else{
                $data=$this->model->modificarUsuario($nick, $nombre, $id_usuario);
                if($data=="modificado"){
                    $msg="mod";
                }else{
                    $msg="Error al modificar usuario";
                }
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function editar($id)
    {
        $data = $this->model->editarUsuario($id);
        echo json_encode($data);
        die();
    }

    public function inactivar($id)
    {
        $this->model->accion(0,$id);
    }

    public function activar($id)
    {
        $this->model->accion(1,$id);
    }
}
