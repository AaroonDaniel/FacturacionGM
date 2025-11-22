//PEDIDOS
function buscarCliente() {
  let documentoid = document.getElementById("documentoid").value;
  $.ajax({
    type: "POST",
    url: base_url + "Pedidos/buscarCliente",
    data: { documentoid: documentoid },
    dataType: "json",
    success: function (data) {
      if (data == "error") {
        Swal.fire({
          title: "El cliente no existe",
          text: "",
          icon: "error",
          timer: 2000,
        });
      } else {
        document.getElementById("id_cliente").value = data["id_cliente"];
        document.getElementById("complementoid").value = data["complementoid"];
        document.getElementById("razon_social").value = data["razon_social"];
        document.getElementById("cliente_email").value = data["cliente_email"];
      }
    },
  });
}

function buscarProducto() {
  let codigo = document.getElementById("codigo").value;
  $.ajax({
    type: "POST",
    url: base_url + "Pedidos/buscarProducto",
    data: { codigo: codigo },
    dataType: "json",
    success: function (data) {
      //console.log(data)
      if (data == "error") {
        Swal.fire({
          title: "El producto no existe",
          text: "",
          icon: "error",
          timer: 2000,
        });
      } else {
        document.getElementById("nombre_producto").value =
          data["nombre_producto"];
        document.getElementById("descripcion_corta").value =
          data["descripcion_corta"];
        document.getElementById("cantidad").value = 1;
        document.getElementById("precio_venta").value = data["precio_venta"];
        document.getElementById("descProducto").value = "0.00";
        document.getElementById("sTotal").value = data["precio_venta"];
        document.getElementById("unidad_siat").value = data["unidad_siat"];
        document.getElementById("codigoProductoSin").value =
          data["codigoProductoSin"];
      }
    },
  });
}

function calcularstotal() {
  let totalPedido = 0;
  for (var i = 0; i < arrayProductos.length; i++) {
    totalPedido += parseFloat(arrayProductos[i].subTotal);
  }
  document.getElementById("subTotal").value = totalPedido.toFixed(2);
  let descAdicional = document.getElementById("descAdicional").value;
  document.getElementById("total").value = (
    totalPedido - descAdicional
  ).toFixed(2);
  let cantidad = document.getElementById("cantidad").value;
  let precio_venta = document.getElementById("precio_venta").value;
  let descProducto = document.getElementById("descProducto").value;
  document.getElementById("sTotal").value = (
    cantidad * precio_venta -
    descProducto
  ).toFixed(2);
}

var arrayProductos = [];
var detalles = document.getElementById("detalles");
function cargarProductos() {
  let actEconomica = document.getElementById("actEconomica").value;
  let codigo = document.getElementById("codigo").value;
  let codigoProductoSin = document.getElementById("codigoProductoSin").value;
  let nombre_producto = document.getElementById("nombre_producto").value;
  let unidad_siat = document.getElementById("unidad_siat").value;
  let precio_venta = document.getElementById("precio_venta").value;
  let cantidad = parseFloat(document.getElementById("cantidad").value).toFixed(
    2
  );
  let descProducto = document.getElementById("descProducto").value;
  let sTotal = document.getElementById("sTotal").value;

  let detallesobj = {
    actividadEconomica: actEconomica,
    codigoProductoSin: codigoProductoSin,
    codigoProducto: codigo,
    descripcion: nombre_producto,
    cantidad: cantidad,
    unidadMedida: unidad_siat,
    precioUnitario: precio_venta,
    montoDescuento: descProducto,
    subTotal: sTotal,
    numeroSerie: null,
    numeroImei: null,
  };

  arrayProductos.push(detallesobj);
  armarPedido();

  document.getElementById("codigo").value = "";
  document.getElementById("nombre_producto").value = "";
  document.getElementById("precio_venta").value = "";
  document.getElementById("cantidad").value = "";
  document.getElementById("descProducto").value = "";
  document.getElementById("sTotal").value = "";
  document.getElementById("descripcion_corta").value = "";
  document.getElementById("unidad_siat").value = "";
  document.getElementById("codigoProductoSin").value = "";
}

function armarPedido() {
  detalles.innerHTML = "";
  arrayProductos.forEach((detalle) => {
    let fila = document.createElement("tr");
    fila.innerHTML =
      "<td>" +
      detalle.codigoProducto +
      "</td>" +
      "<td>" +
      detalle.descripcion +
      "</td>" +
      "<td>" +
      detalle.precioUnitario +
      "</td>" +
      "<td>" +
      detalle.cantidad +
      "</td>" +
      "<td>" +
      detalle.montoDescuento +
      "</td>" +
      "<td>" +
      detalle.subTotal +
      "</td>";

    let tdEliminar = document.createElement("td");
    let botonEliminar = document.createElement("button");
    botonEliminar.classList.add("btn", "btn-danger");
    botonEliminar.innerHTML = '<i class="fas fa-trash"></i>';
    botonEliminar.onclick = () => {
      eliminarProducto(detalle.codigoProducto);
    };

    tdEliminar.appendChild(botonEliminar);
    fila.appendChild(tdEliminar);
    detalles.appendChild(fila);
  });
  calcularstotal();
}

function eliminarProducto(codigo) {
  arrayProductos = arrayProductos.filter((detalle) => {
    if (codigo != detalle.codigoProducto) {
      return detalle;
    }
  });
  armarPedido();
}

// CÓDIGO SEGURO: Verifica la existencia del elemento
function verificarComunicacion() {
  let statusElement = document.getElementById("comunicacionSiat");
  if (!statusElement) return;

  $.ajax({
    type: "POST",
    url: base_url + "Pedidos/verificarComunicacion",
    cache: false,
    dataType: "json",
    success: function (data) {
      if (data.RespuestaComunicacion.transaccion == true) {
        statusElement.innerHTML = data.RespuestaComunicacion.mensajesList.descripcion;
        statusElement.className = "badge badge-success";
      } else {
        statusElement.innerHTML = "DESCONECTADO";
        statusElement.className = "badge badge-secondary";
      }
    },
  });
}

function cuis() {
  $.ajax({
    type: "POST",
    url: base_url + "Pedidos/cuis",
    cache: false,
    dataType: "json",
    success: function (data) {
      console.log(data);
    },
  });
}

verificarComunicacion();
cuis();

// CÓDIGO SEGURO: Verifica la existencia del elemento
function cufd() {
  let cufdElement = document.getElementById("cufd");
  if (!cufdElement) return;

  $.ajax({
    type: "POST",
    url: base_url + "Pedidos/cufd",
    cache: false,
    dataType: "json",
    success: function (data) {
      if (data.RespuestaCufd.transaccion == true) {
        cufdElement.innerHTML = "CUFD vigente " + data.RespuestaCufd.fechaVigencia.substring(0, 16);
      } else {
        cufdElement.innerHTML = "No existe CUFD vigente";
      }
    },
  });
}

cufd();

// SOLUCIÓN DEFINITIVA: FUNCIONES DE ANULACIÓN/REVERSIÓN CON AJAX POST

function confirmarAnulacion(cuf, id_factura) {
  Swal.fire({
    title: "¿Estás seguro de anular la factura?",
    text: "La anulación será enviada a SIAT y el estado local se cambiará a 'Anulada' (0). Esta acción es reversible.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6",
    confirmButtonText: "Sí, Anular",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.isConfirmed) {
      // --- INICIO Loader ---
      Swal.fire({
        title: "Procesando Anulación...",
        text: "Comunicando con el servicio SIAT, por favor espere.",
        icon: "info",
        allowOutsideClick: false,
        showConfirmButton: false, 
        didOpen: () => {
          Swal.showLoading();
        },
      });
      // --- FIN Loader ---

      // Llamada AJAX al controlador
      $.ajax({
        type: "POST",
        url: base_url + "Pedidos/anulacionFactura", // URL limpia
        data: { cuf: cuf, id_factura: id_factura }, // Datos enviados por POST
        dataType: "json",
        success: function (data) {
          Swal.close();

          let mensaje =
            data.RespuestaServicioFacturacion.codigoDescripcion ||
            "Respuesta de SIAT";
          let iconoSwal = "error"; 
          let tituloSwal = "Error de Anulación";

          if (
            data.RespuestaServicioFacturacion.transaccion == true &&
            data.update_db_status == "success"
          ) {
            tituloSwal = "Anulación Exitosa";
            mensaje = mensaje + ". Estado local actualizado a 'Anulada' (0).";
            iconoSwal = "success";
          } else {
            if (
              data.RespuestaServicioFacturacion.mensajesList &&
              data.RespuestaServicioFacturacion.mensajesList.descripcion
            ) {
              mensaje =
                data.RespuestaServicioFacturacion.mensajesList.descripcion;
            }
            if (data.update_db_status == "error_db") {
              mensaje +=
                ". ADVERTENCIA: La anulación en SIAT fue exitosa, pero falló la actualización local de estado (inconsistencia).";
            }
          }

          Swal.fire({
            title: tituloSwal,
            text: mensaje,
            icon: iconoSwal,
          });

          // Recargar la tabla si la transacción en SIAT fue exitosa (el estado SIAT es la verdad)
          if (data.RespuestaServicioFacturacion.transaccion == true) {
            if (typeof tblFacturas !== "undefined") {
              tblFacturas.ajax.reload();
            } else {
              setTimeout(() => {
                window.location.reload();
              }, 1500);
            }
          }
        },
        error: function (jqXHR, textStatus, errorThrown) {
          Swal.close();
          Swal.fire({
            title: "Error de Conexión",
            text:
              "No se pudo comunicar con el servidor (AJAX/Red). Detalles: " +
              textStatus +
              " " +
              errorThrown,
            icon: "error",
          });
        },
      });
    }
  });
}

function confirmarReversion(cuf, id_factura) {
  Swal.fire({
    title: "¿Revertir Anulación?",
    text: "La reversión será enviada a SIAT. Si es exitosa, el estado local se actualizará a 'Revalidada' (3) y la factura NO podrá anularse nuevamente.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    cancelButtonColor: "#d33",
    confirmButtonText: "Sí, Revertir",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.isConfirmed) {
      // --- INICIO Loader ---
      Swal.fire({
        title: "Procesando Reversión...",
        text: "Comunicando con el servicio SIAT para revalidar, por favor espere.",
        icon: "info",
        allowOutsideClick: false,
        showConfirmButton: false, // Quitar botón de OK mientras carga
        didOpen: () => {
          Swal.showLoading();
        },
      });
      // --- FIN Loader ---

      // Llamada AJAX al controlador
      $.ajax({
        type: "POST",
        url: base_url + "Pedidos/reversionAnulacionFactura", // URL limpia
        data: { cuf: cuf, id_factura: id_factura }, // Datos enviados por POST
        dataType: "json",
        success: function (data) {
          Swal.close(); // Cerrar el loader

          let mensaje =
            data.RespuestaServicioFacturacion.codigoDescripcion ||
            "Respuesta de SIAT";
          let tituloSwal = "Error de Reversión";
          let iconoSwal = "error";

          if (
            data.RespuestaServicioFacturacion.transaccion == true &&
            data.update_db_status == "success"
          ) {
            tituloSwal = "Reversión Exitosa";
            mensaje =
              "Factura revalidada en SIAT y estado local actualizado a 'Revalidada' (3).";
            iconoSwal = "success";
          } else {
            // Manejar errores
            if (
              data.RespuestaServicioFacturacion.mensajesList &&
              data.RespuestaServicioFacturacion.mensajesList.descripcion
            ) {
              mensaje =
                data.RespuestaServicioFacturacion.mensajesList.descripcion;
            }
            if (data.update_db_status == "error_db") {
              mensaje +=
                ". ADVERTENCIA: La reversión en SIAT fue exitosa, pero falló la actualización local de estado (inconsistencia).";
            }
          }

          Swal.fire({
            title: tituloSwal,
            text: mensaje,
            icon: iconoSwal,
          });

          // Recargar la tabla si la transacción en SIAT fue exitosa
          if (data.RespuestaServicioFacturacion.transaccion == true) {
            if (typeof tblFacturas !== "undefined") {
              tblFacturas.ajax.reload();
            } else {
              setTimeout(() => {
                window.location.reload();
              }, 1500);
            }
          }
        },
        error: function (jqXHR, textStatus, errorThrown) {
          Swal.close();
          Swal.fire({
            title: "Error de Conexión",
            text:
              "No se pudo comunicar con el servidor (AJAX/Red). Detalles: " +
              textStatus +
              " " +
              errorThrown,
            icon: "error",
          });
        },
      });
    }
  });
}

function emitirFactura() {
  Swal.fire({
    title: "Enviando Factura a SIAT...",
    text: "Por favor espere, la validación con Impuestos Nacionales puede tardar unos segundos.",
    icon: "info",
    allowOutsideClick: false, // Evita que se cierre haciendo clic fuera
    showConfirmButton: false, // Oculta el botón de confirmación
    didOpen: () => {
      Swal.showLoading(); // Muestra el indicador de carga
    },
  });
  let id_cliente = document.getElementById("id_cliente").value;
  let numeroFactura = document.getElementById("nrofactura").value;
  let cuf = "123456";
  let cufd = document.getElementById("cufdValor").value;
  var diferencia = new Date().getTimezoneOffset() * 60000;
  let fechaEmision = new Date(Date.now() - diferencia)
    .toISOString()
    .slice(0, -1);
  let nombreRazonSocial = document.getElementById("razon_social").value;
  let codigoTipoDocumentoIdentidad =
    document.getElementById("tipoDocumento").value;
  let numeroDocumento = document.getElementById("documentoid").value;
  let complemento = document.getElementById("complementoid").value;
  let codigoCliente = document.getElementById("documentoid").value;
  let codigoMetodoPago = 1;
  let numeroTarjeta = null;
  let montoTotal = document.getElementById("total").value;
  let montoGiftCard = 0;
  let descuentoAdicional = document.getElementById("descAdicional").value;
  let codigoExcepcion = null;
  let cafc = null;
  let codigoMoneda = 1;
  let tipoCambio = 1;
  let leyenda =
    "Ley N° 453: Puedes acceder a la reclamación cuando tus derechos han sido vulnerados.";
  let usuario = document.getElementById("nickuser").value;
  let codigoDocumentoSector = 1;

  var factura = [];
  factura.push({
    cabecera: {
      nitEmisor: "3327479013",
      razonSocialEmisor: "FERRETERIA EL FERRETERO",
      municipio: "LA PAZ",
      telefono: "72578583",
      numeroFactura: numeroFactura,
      cuf: cuf,
      cufd: cufd,
      codigoSucursal: 0,
      direccion: "Calle 4 Ceja El Alto",
      codigoPuntoVenta: 0,
      fechaEmision: fechaEmision,
      nombreRazonSocial: nombreRazonSocial,
      codigoTipoDocumentoIdentidad: codigoTipoDocumentoIdentidad,
      numeroDocumento: numeroDocumento,
      complemento: complemento,
      codigoCliente: codigoCliente,
      codigoMetodoPago: codigoMetodoPago,
      numeroTarjeta: numeroTarjeta,
      montoTotal: montoTotal,
      montoTotalSujetoIva: montoTotal,
      codigoMoneda: codigoMoneda,
      tipoCambio: tipoCambio,
      montoTotalMoneda: montoTotal,
      montoGiftCard: montoGiftCard,
      descuentoAdicional: descuentoAdicional,
      codigoExcepcion: codigoExcepcion,
      cafc: cafc,

      leyenda: leyenda,
      usuario: usuario,
      codigoDocumentoSector: codigoDocumentoSector,
    },
  });

  arrayProductos.forEach(function (prod) {
    factura.push({
      detalle: prod,
    });
  });

  //console.log(factura)

  var datos = { factura };
  $.ajax({
    type: "POST",
    url: base_url + "Pedidos/emitirFactura",
    data: { factura: datos, id_cliente: id_cliente },
    dataType: "json",
    success: function (data) {
      Swal.close(); 

      let esOfflinePendiente = data.RespuestaServicioFacturacion.offline === true;

      if (data.RespuestaServicioFacturacion.transaccion == true) {
        Swal.fire({
          title: data.RespuestaServicioFacturacion.codigoDescripcion,
          text: esOfflinePendiente ? data.RespuestaServicioFacturacion.mensajesList.descripcion : "Codigo recepcion " + data.RespuestaServicioFacturacion.codigoRecepcion,
          icon: "success",
          timer: 2000,
          showConfirmButton: false,
        });
        setTimeout(() => {
          window.location.href = base_url + "Pedidos";
        }, 2000);
      } else {
        let errorText = "SIAT rechazó la factura.";
         if (data.RespuestaServicioFacturacion.mensajesList && data.RespuestaServicioFacturacion.mensajesList.descripcion) {
             errorText = data.RespuestaServicioFacturacion.mensajesList.descripcion;
         }
        Swal.fire({
          title: data.RespuestaServicioFacturacion.codigoDescripcion || "Error",
          text: errorText,
          icon: "error",
        });
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
        Swal.close();
        Swal.fire({
            title: "Error de Conexión",
            text: "No se pudo conectar con el servidor para emitir la factura. Detalles: " + textStatus + " " + errorThrown,
            icon: "error",
        });
    }
  });
}