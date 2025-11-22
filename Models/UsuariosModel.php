<?php
class UsuariosModel extends Query
{
    private $nick, $nombre, $clave, $id_usuario, $estado;

    public function __construct()
    {
        parent::__construct();
    }

    public function getUsuarios()
    {
        $sql = "SELECT * FROM usuarios";
        $data = $this->selectAll($sql);
        return $data;
    }

    public function getUsuario(string $nick, string $clave)
    {
        $sql = "SELECT * FROM usuarios WHERE nick = '$nick' AND clave = '$clave'";
        $data = $this->select($sql);
        return $data;
    }

    public function registrarUsuario(string $nick, string $nombre, string $clave)
    {
        $this->nick = $nick;
        $this->nombre = $nombre;
        $this->clave = $clave;

        // Verificar si ya existe
        $verificar = "SELECT * FROM usuarios WHERE nick = '$this->nick'";
        $existe = $this->select($verificar);

        if (empty($existe)) {
            $sql = "INSERT INTO usuarios(nick, nombre, clave) VALUES (?,?,?)";
            $datos = array($this->nick, $this->nombre, $this->clave);
            $data = $this->save($sql, $datos);

            if ($data == 1) {
                $res = "ok";
            } else {
                $res = "Error";
            }
        } else {
            $res = "existe"; // Retorna esto si ya existe
        }
        return $res;
    }

    public function editarUsuario(int $id)
    {
        $sql = "SELECT * FROM usuarios WHERE id_usuario = '$id'";
        $data = $this->select($sql);
        return $data;
    }

    public function modificarUsuario(string $nick, string $nombre, int $id_usuario)
    {
        $this->id_usuario = $id_usuario;
        $this->nick = $nick;
        $this->nombre = $nombre;

        $sql = "UPDATE usuarios SET nick=?, nombre=? WHERE id_usuario=?";

        $datos = array($this->nick, $this->nombre, $this->id_usuario);
        $data = $this->save($sql, $datos);

        if ($data == 1) {
            $res = "modificado";
        } else {
            $res = "Error al modificar";
        }
        return $res;
    }

    public function accion(int $estado, int $id)
    {
        $this->id_usuario = $id;
        $this->estado = $estado;
        $sql = "UPDATE usuarios SET usuario_estado = ? WHERE id_usuario = ?";
        $datos = array($this->estado, $this->id_usuario);
        $data = $this->save($sql, $datos);
        return $data;
    }
}
