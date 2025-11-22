<?php require_once "Views/Templates/header.php"; ?>

<div class="card">
  <div class="card-header">
    <div class="card-title">Lista de Cajas</div>
  </div>
  <div class="card-body">
    <button class="btn btn-success" type="button" onclick="frmCaja()"><i class="fas fa-plus"></i> Nueva caja</button>
    <table id="tblCajas" class="table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Caja</th>
          <th>Estado</th>
          <th></th>

        </tr>
      </thead>
    </table>
  </div>
</div>

<div class="modal fade" id="cajaModal" tabindex="-1" role="dialog" aria-labelledby="cajaModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="title">Nueva caja</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="frmCaja">
          <div class="form-group">
            <label for="caja" class="col-form-label">Nombre de caja:</label>
            <input type="hidden" id="id_caja" name="id_caja">
            <input type="text" class="form-control" id="caja" name="caja">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary" id="btnAccion" onclick="registrarCaja(event)">Guardar</button>
      </div>
    </div>
  </div>
</div>

<?php require_once "Views/Templates/footer.php"; ?>