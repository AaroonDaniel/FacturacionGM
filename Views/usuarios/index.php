<?php require_once "Views/Templates/header.php"; ?>

<div class="card">
  <div class="card-header">
    <div class="card-title">Lista de Usuarios</div>
  </div>
  <div class="card-body">
    <button class="btn btn-success" type="button" onclick="frmUsuario()"><i class="fas fa-plus"></i> Nuevo usuario</button>
    <table id="tblUsuarios" class="table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nick</th>
          <th>Nombre</th>
          <th>Estado</th>
          <th></th>

        </tr>
      </thead>
    </table>
  </div>
</div>

<div class="modal fade" id="usuarioModal" tabindex="-1" role="dialog" aria-labelledby="usuarioModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="title">Nuevo usuario</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="frmUsuario">
          <div class="form-group">
            <label for="nick" class="col-form-label">Nick de usuario:</label>
            <input type="hidden" id="id_usuario" name="id_usuario">
            <input type="text" class="form-control" id="nick" name="nick">
          </div>
          <div class="form-group">
            <label for="nombre" class="col-form-label">Nombre:</label>
            <input type="text" class="form-control" id="nombre" name="nombre">
          </div>

          <div class="row" id="claves">
            <div class="col-md-6">
              <div class="form-group">
                <label for="clave" class="col-form-label">Clave:</label>
                <input type="password" class="form-control" id="clave" name="clave">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="confirmar" class="col-form-label">Confirmar:</label>
                <input type="password" class="form-control" id="confirmar" name="confirmar">
              </div>
            </div>
          </div>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary" id="btnAccion" onclick="registrarUsuario(event)">Guardar</button>
      </div>
    </div>
  </div>
</div>

<?php require_once "Views/Templates/footer.php"; ?>