<?php require_once "Views/Templates/header.php"; ?>

<div class="card">
    <div class="card-header">
        <div class="card-title">Lista de Productos</div>
    </div>
    <div class="card-body">
        <button class="btn btn-success" type="button" onclick="frmProducto()"><i class="fas fa-plus"></i> Nueva producto</button>
        <table id="tblProductos" class="table">
            <thead>
                <tr>
                    <th>Codigo</th>
                    <th>Cod. SIAT</th>
                    <th>Nombre de producto</th>
                    <th>Nombre de categoria/th>
                    <th>U.medida</th>
                    <th>Costo</th>
                    <th>Precio</th>
                    <th>Cantidad </th>
                    <th>Estado</th>
                    <th></th>

                </tr>
            </thead>
        </table>
    </div>
</div>

<div class="modal fade" id="productoModal" tabindex="-1" role="dialog" aria-labelledby="productoModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="title">Nuevo producto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="frmProducto">
                    <div class="form-group">
                        <label for="nombre_producto" class="col-form-label">Producto:</label>
                        <input type="hidden" id="id_producto" name="id_producto">
                        <input type="text" class="form-control" id="nombre_producto" name="nombre_producto">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="codigo" class="col-form-label">Código:</label>
                                <input type="text" class="form-control" id="codigo" name="codigo">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="cantidad" class="col-form-label">Cantidad:</label>
                                <input type="number" class="form-control" id="cantidad" name="cantidad">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="costo_compra" class="col-form-label">Costo:</label>
                                <input type="text" class="form-control" id="costo_compra" name="costo_compra">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="precio_venta" class="col-form-label">Precio:</label>
                                <input type="number" class="form-control" id="precio_venta" name="precio_venta">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_categoria" class="col-form-label">Categoría:</label>
                                <select name="id_categoria" id="id_categoria" class="form-control">
                                    <?php foreach ($data['categorias'] as $c) { ?>
                                        <option value="<?= $c['id_categoria'] ?>"><?= $c['nombre_categoria'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_medida" class="col-form-label">U. medida:</label>
                                <select name="id_medida" id="id_medida" class="form-control">
                                    <?php foreach ($data['medidas'] as $c) { ?>
                                        <option value="<?= $c['id_medida'] ?>"><?= $c['descripcion_medida'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary" id="btnAccion" onclick="registrarProducto(event)">Guardar</button>
            </div>
        </div>
    </div>
</div>

<?php require_once "Views/Templates/footer.php"; ?>