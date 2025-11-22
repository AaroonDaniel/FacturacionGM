<?php require_once "Views/Templates/header.php"; ?>
<div class="card">
    <div class="card-header">
        <div class="card-title">Nuevo pedido</div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="form-group row col-md-9">
                <div class="form-group col-md-3">
                    <label for="nrofactura">Nro. Factura</label>
                    <input type="number" id="nrofactura" name="nrofactura" class="form-control">
                    <input type="hidden" id="nickuser" name="nickuser" value="<?=$_SESSION['nick']?>">
                </div>
                <div class="form-group col-md-3">
                    <label for="actEconomica">Act. Economica</label>
                    <input type="number" id="actEconomica" name="actEconomica" class="form-control" value="620000" readonly>

                </div>
                <div class="form-group col-md-3">
                    <label for="tipoDocumento">Tipo documento</label>
                    <select id="tipoDocumento" name="tipoDocumento" class="form-control">
                        <option value="1">Celula</option>
                        <option value="5">NIT</option>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label for="documentoid">NIT/ CI</label>
                    <div class="input-group-append">
                        <input type="text" id="documentoid" name="documentoid" class="form-control">
                        <button class="btn btn-outline-secondary" onclick="buscarCliente()"><i class="fas fa-search"></i></button>
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <label for="razon_social">Razon social</label>
                    <input type="hidden" id="id_cliente" name="id_cliente"> 
                    <input type="hidden" id="complementoid" name="complementoid">
                    <input type="text" id="razon_social" name="razon_social" class="form-control" readonly>

                </div>
                <div class="form-group col-md-6">
                    <label for="cliente_email">Correo electronico</label>
                    <input type="text" id="cliente_email" name="cliente_email" class="form-control" readonly>

                </div>
            </div>
            <div class="form-group col-md-3">
                <div class="card">
                    <div class="input-group">
                        <span class="input-group-text"> S. total</span>
                        <input type="number" class="form-control" id="subTotal" name="subTotal" min="0" value="0.00" step="0.01" style="text-align: right;" readonly>
                    </div>
                    <div class="input-group">
                        <span class="input-group-text"> Desc. Add.</span>
                        <input type="number" class="form-control" id="descAdicional" name="descAdicional" min="0.00" value="0.00" step="0.01" style="text-align: right;" onchange="calcularstotal()" onkeyup="calcularstotal()">
                    </div>
                    <div class="input-group">
                        <span class="input-group-text">Total:</span>
                        <input type="number" class="form-control" id="total" name="total" min="0.00" value="0.00" step="0.01" style="text-align: right;" readonly>
                    </div>
                    <div class="input-group">
                        <span class="badge badge-secondary" id="comunicacionSiat">DESCONETADO</span>
                        <p>
                            <?php
                            // Verificar si la variable de sesión 'scuis' está definida
                            if (isset($_SESSION['scuis']) && !empty($_SESSION['scuis'])) {
                                echo "CUIS: " . $_SESSION['scuis'];
                            } else {
                                echo "CUIS inexistente";
                            }
                            ?>
                        </p>
                        <span class="badge badge-secondary" id="cufd">No existe CUFD vigente</span>
                        <input type="hidden" id="cufdValor" name="cufdValor" value="<?=$_SESSION['scufd']?>">
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="card">
        <div class="card-header">
            <div class="card-title">Agregar productos</div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="form-group col-md-2">
                    <label for="codigo">Cod. Producto</label>
                    <div class="input-group-append">
                        <input type="text" class="form-control" id="codigo" name="codigo">
                        <input type="hidden" id="codigoProductoSin" name="codigoProductoSin">
                        <button class="btn btn-outline-secondary" type="button" onclick="buscarProducto()">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group col-md-3">
                    <label for="nombre_producto">Producto</label>
                    <input type="text" class="form-control" id="nombre_producto" name="nombre_producto">
                </div>

                <div class="form-group col-md-1">
                    <label for="descripcion_corta">U. Med</label>
                    <input type="hidden" id="unidad_siat" name="unidad_siat">
                    <input type="text" class="form-control" id="descripcion_corta" name="descripcion_corta">
                </div>

                <div class="form-group col-md-1">
                    <label for="cantidad">Cantidad</label>
                    <input type="number" class="form-control" id="cantidad" name="cantidad" onchange="calcularstotal()">
                </div>

                <div class="form-group col-md-1">
                    <label for="precio_venta">Precio</label>
                    <input type="text" class="form-control" id="precio_venta" name="precio_venta" onchange="calcularstotal()" onkeyup="calcularstotal()">
                </div>

                <div class="form-group col-md-1">
                    <label for="descProducto">Descuento</label>
                    <input type="number" class="form-control" id="descProducto" name="descProducto" onchange="calcularstotal()" onkeyup="calcularstotal()">
                </div>

                <div class="form-group col-md-2">
                    <label for="sTotal">S. Total</label>
                    <input type="text" class="form-control" id="sTotal" name="sTotal">
                </div>
                <div class="form-group col-md-1">
                    <label for="">&nbsp;</label>
                    <div class="input-group">
                        <button class="btn btn-info" type="button" onclick="cargarProductos()"><i class="fas fa-plus"></i></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button class="btn btn-success" type="button" onclick="emitirFactura()">Emitir factura</button>

        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Codigo</th>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Descuento</th>
                        <th>S. Total</th>
                    </tr>
                </thead>
                <tbody id="detalles">

                </tbody>
            </table>
        </div>
    </div>
</div>
<?php require_once "Views/Templates/footer.php"; ?>
<script src="<?=base_url ?>Assets/js/facturar.js"></script>