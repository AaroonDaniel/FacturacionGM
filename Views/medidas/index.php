<?php require_once "Views/Templates/header.php"; ?>

<div class="card">
  <div class="card-header">
    <div class="card-title">Lista de Medidas</div>
  </div>
  <div class="card-body">
    <button class="btn btn-success" type="button" onclick="frmMedida()"><i class="fas fa-plus"></i> Nueva medida</button>
    <table id="tblMedidas" class="table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Unidad de medida</th>
          <th>Abreviatura</th>
          <th>Codigo SIAT</th>
          <th>Estado</th>
          <th></th>

        </tr>
      </thead>
    </table>
  </div>
</div>

<div class="modal fade" id="medidaModal" tabindex="-1" role="dialog" aria-labelledby="medidaModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="title">Nueva medida</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="frmMedida">

          <div class="form-group">
            <label for="descripcion_medida" class="col-form-label">Unidad de medida:</label>
            <input type="hidden" id="id_medida" name="id_medida">
            <input type="text" class="form-control" id="descripcion_medida" name="descripcion_medida">
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="descripcion_corta" class="col-form-label">Abreviatura:</label>
                <input type="text" class="form-control" id="descripcion_corta" name="descripcion_corta">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="unidad_siat" class="col-form-label">Codigo SIAT:</label>
                <input type="number" class="form-control" id="unidad_siat" name="unidad_siat">
              </div>
            </div>
          </div>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary" id="btnAccion" onclick="registrarMedida(event)">Guardar</button>
      </div>
    </div>
  </div>
</div>

<?php require_once "Views/Templates/footer.php"; ?>