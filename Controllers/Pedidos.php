<?php
class Pedidos extends Controller
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
        $data = $this->model->getFacturas();
        for ($i = 0; $i < count($data); $i++) {
            $id_factura = $data[$i]['id_factura'];
            $cuf = $data[$i]['cuf'];

            // Lógica de visualización de estados y botones
            if ($data[$i]['factura_estado'] == 1) {
                // Estado 1: Valida. Se puede anular.
                $data[$i]['factura_estado'] = '<a href="https://pilotosiat.impuestos.gob.bo/consulta/QR?nit=3327479013&cuf=' . $cuf . '&numero=' . $data[$i]['numeroFactura'] . '&t=2" title="Imprimir" target="_blank"><span class="badge badge-success">Valida</span></a>';
                // Llama a la nueva función JS para confirmación
                $data[$i]['acciones'] = '<a href="' . base_url . 'Pedidos/imprimirFactura?id=' . $id_factura . '" class="btn btn-primary btn-sm" title="Imprimir" target="_blank"><i class="fas fa-print"></i></a>
                <button class="btn btn-danger btn-sm" title="Anular Factura" onclick="confirmarAnulacion(\'' . $cuf . '\', ' . $id_factura . ')"><i class="fas fa-ban"></i></button>
                ';
            } elseif ($data[$i]['factura_estado'] == 0) {
                // Estado 0: Anulada. Se puede revertir (Revalidar).
                $data[$i]['factura_estado'] = '<a href="https://pilotosiat.impuestos.gob.bo/consulta/QR?nit=3327479013&cuf=' . $cuf . '&numero=' . $data[$i]['numeroFactura'] . '&t=2" title="Imprimir" target="_blank"><span class="badge badge-danger">Anulada</span>';
                // Llama a la nueva función JS para confirmación de reversión
                $data[$i]['acciones'] = '<a href="' . base_url . 'Pedidos/imprimirFactura?id=' . $id_factura . '" class="btn btn-primary btn-sm" title="Imprimir" target="_blank"><i class="fas fa-print"></i></a>
                <button class="btn btn-success btn-sm" title="Revertir Anulación" onclick="confirmarReversion(\'' . $cuf . '\', ' . $id_factura . ')"><i class="fas fa-check"></i></button>
                ';
            } elseif ($data[$i]['factura_estado'] == 3) {
                // Estado 3: Revalidada (FINAL). Botón deshabilitado.
                $data[$i]['factura_estado'] = '<a href="https://pilotosiat.impuestos.gob.bo/consulta/QR?nit=3327479013&cuf=' . $cuf . '&numero=' . $data[$i]['numeroFactura'] . '&t=2" title="Imprimir" target="_blank"><span class="badge badge-success">Revalidada</span></a>';
                $data[$i]['acciones'] = '<a href="' . base_url . 'Pedidos/imprimirFactura?id=' . $id_factura . '" class="btn btn-primary btn-sm" title="Imprimir" target="_blank"><i class="fas fa-print"></i></a>
                <button class="btn btn-secondary btn-sm" title="Acción no disponible" disabled><i class="fas fa-ban"></i></button>';
            } else {
                // Otros estados
                $data[$i]['factura_estado'] = '<span class="badge badge-secondary">Inactivo</span>';
                $data[$i]['acciones'] = '<a href="' . base_url . 'Pedidos/imprimirFactura?id=' . $id_factura . '" class="btn btn-primary btn-sm" title="Imprimir" target="_blank"><i class="fas fa-print"></i></a>
                <button class="btn btn-secondary btn-sm" title="Sin Acción" disabled><i class="fas fa-ban"></i></button>';
            }
        }
        echo json_encode($data);
        die();
    }

    public function nuevo_pedido()
    {
        $this->views->getView($this, "nuevo_pedido");
    }

    public function buscarCliente()
    {
        $documentoid = $_POST['documentoid'];
        $data = $this->model->buscarCliente($documentoid);
        if ($data) {
            echo json_encode($data);
        } else {
            $msg = "error";
            echo json_encode($msg);
        }
    }
    public function buscarProducto()
    {
        $codigo = $_POST["codigo"];
        $data = $this->model->buscarProducto($codigo);
        if ($data) {
            echo json_encode($data);
        } else {
            $msg = "error";
            echo json_encode($msg);
        }
    }

    public function verificarComunicacion()
    {
        require_once "Siat.php";
        $siat = new Siat();
        $res = $siat->verificarComunicacion();
        echo json_encode($res);
    }


    public function cuis()
    {
        if (!isset($_SESSION['scuis'])) {
            require "Siat.php";
            $siat = new Siat();
            $res = $siat->cuis();
            if ($res->RespuestaCuis->mensajesList->codigo == 980) {
                $_SESSION['scuis'] = $res->RespuestaCuis->codigo;
                $_SESSION['sfechaVigenciaCuis'] = $res->RespuestaCuis->fechaVigencia;
                echo $res->RespuestaCuis->codigo;
            } else {
                echo "error al solicitar el codigo cuis";
            }
        } else {
            echo $_SESSION['scuis'];
        }
    }

    public function cufd()
    {
        if (!isset($_SESSION['scufd'])) {
            require "Siat.php";
            $siat = new Siat();
            $res = $siat->cufd();
            if ($res->RespuestaCufd->transaccion == true) {
                $_SESSION['scufd'] = $res->RespuestaCufd->codigo;
                $_SESSION['scodigoControl'] = $res->RespuestaCufd->codigoControl;
                $_SESSION['sfechaVigenciaCufd'] = $res->RespuestaCufd->fechaVigencia;
            } else {
                $res = false;
            }
        } else {
            $fechaVigente = substr($_SESSION['sfechaVigenciaCufd'], 0, 16);
            $fechaVigente = str_replace("T", " ", $fechaVigente);
            if ($fechaVigente < date("Y-m-d H:i")) {
                require "Siat.php";
                $siat = new Siat();
                $res = $siat->cufd();
                if ($res->RespuestaCufd->transaccion == true) {
                    $_SESSION['scufd'] = $res->RespuestaCufd->codigo;
                    $_SESSION['scodigoControl'] = $res->RespuestaCufd->codigoControl;
                    $_SESSION['sfechaVigenciaCufd'] = $res->RespuestaCufd->fechaVigencia;
                } else {
                    $res = false;
                }
            } else {
                $res['RespuestaCufd']['transaccion'] = true;
                $res['RespuestaCufd']['codigo'] = $_SESSION['scufd'];
                $res['RespuestaCufd']['fechaVigencia'] = $_SESSION['sfechaVigenciaCufd'];
            }
        }
        echo json_encode($res);
    }
    public function sincronizarActividades()
    {
        require "Siat.php";
        $siat = new Siat();
        $res = $siat->sincronizarActividades();
        echo json_encode($res);
    }

    public function sincronizarListaProductosServicios()
    {
        require "Siat.php";
        $siat = new Siat();
        $res = $siat->sincronizarListaProductosServicios();
        echo json_encode($res);
    }

    public function sincronizarParametricaUnidadMedida()
    {
        require "Siat.php";
        $siat = new Siat();
        $res = $siat->sincronizarParametricaUnidadMedida();
        echo json_encode($res);
    }

    public function sincronizarListaLeyendasFactura()
    {
        require "Siat.php";
        $siat = new Siat();
        $res = $siat->sincronizarListaLeyendasFactura();
        echo json_encode($res);
    }

    public function sincronizarParametricaMotivoAnulacion()
    {
        require "Siat.php";
        $siat = new Siat();
        $res = $siat->sincronizarParametricaMotivoAnulacion();
        echo json_encode($res);
    }
    
    // MÉTODO MODIFICADO: Ahora recibe datos por POST
    public function anulacionFactura()
    {
        $cuf = $_POST['cuf'] ?? null;
        $id_factura = $_POST['id_factura'] ?? null;
        
        if (is_null($cuf) || is_null($id_factura)) {
            echo json_encode(['RespuestaServicioFacturacion' => ['transaccion' => false, 'codigoDescripcion' => 'ERROR', 'mensajesList' => ['descripcion' => 'Datos CUF o ID de factura faltantes.']]], JSON_FORCE_OBJECT);
            die();
        }

        require "Siat.php";
        $siat = new Siat();
        $res = $siat->anulacionFactura($cuf, 1); // Código de motivo de anulación: 1

        $response = json_decode(json_encode($res), true);
        
        if (isset($res->RespuestaServicioFacturacion->transaccion) && $res->RespuestaServicioFacturacion->transaccion == true) {
            // SIAT OK: Actualiza el estado local a 0 (Anulada)
            $update_db = $this->model->accion(0, $id_factura);
            $response['update_db_status'] = $update_db ? "success" : "error_db";
        } else {
            // Falla en SIAT
            $response['update_db_status'] = "error_siat";
        }
        echo json_encode($response);
        die();
    }

    // MÉTODO MODIFICADO: Ahora recibe datos por POST
    public function reversionAnulacionFactura()
    {
        $cuf = $_POST['cuf'] ?? null;
        $id_factura = $_POST['id_factura'] ?? null;
        
        if (is_null($cuf) || is_null($id_factura)) {
             echo json_encode(['RespuestaServicioFacturacion' => ['transaccion' => false, 'codigoDescripcion' => 'ERROR', 'mensajesList' => ['descripcion' => 'Datos CUF o ID de factura faltantes.']]], JSON_FORCE_OBJECT);
             die();
        }

        require "Siat.php";
        $siat = new Siat();
        $res = $siat->reversionAnulacionFactura($cuf);
        
        $response = json_decode(json_encode($res), true);

        if (isset($res->RespuestaServicioFacturacion->transaccion) && $res->RespuestaServicioFacturacion->transaccion == true) {
            // SIAT OK: Actualiza el estado local a 3 (Revalidada)
            $update_db = $this->model->accion(3, $id_factura);
            $response['update_db_status'] = $update_db ? "success" : "error_db";
        } else {
            // Falla en SIAT
            $response['update_db_status'] = "error_siat";
        }
        echo json_encode($response);
        die();
    }


    public function emitirFactura()
    {
        $datos = $_POST['factura'];
        $valores = $datos['factura'][0]['cabecera'];
        $nitEmisor = $valores['nitEmisor'];
        $nitEmisor = str_pad($nitEmisor, 13, "0", STR_PAD_LEFT);
        $fechaEmision = $valores['fechaEmision'];
        $fechafinal = str_replace("T", "", $fechaEmision);
        $fechafinal = str_replace("-", "", $fechafinal);
        $fechafinal = str_replace(":", "", $fechafinal);
        $fechafinal = str_replace(".", "", $fechafinal);
        //20250923173220568
        $sucursal = 0;
        $sucursal = str_pad($sucursal, 4, "0", STR_PAD_LEFT);
        $modalidad = 2;
        $tipoEmision = 1;
        $tipoFactura = 1;
        $tipoDocSector = 1;
        $tipoDocSector = str_pad($tipoDocSector, 2, "0", STR_PAD_LEFT);
        $numeroFactura = $valores['numeroFactura'];
        $numeroFactura = str_pad($numeroFactura, 10, "0", STR_PAD_LEFT);
        $puntoVenta = 0;
        $puntoVenta = str_pad($puntoVenta, 4, "0", STR_PAD_LEFT);
        $cadena = $nitEmisor . $fechafinal . $sucursal . $modalidad . $tipoEmision . $tipoFactura . $tipoDocSector . $numeroFactura . $puntoVenta;
        $modulo11 = $this->obtenerModulo11($cadena);
        $cadena .= $modulo11;
        $base16 = $this->base16($cadena);
        $cuf = $base16 . $_SESSION['scodigoControl'];
        $datos['factura'][0]['cabecera']['cuf'] = $cuf;
        $temporal = $datos['factura'];
        $xml_temporal = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
        <facturaComputarizadaCompraVenta 
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" 
        xsi:noNamespaceSchemaLocation="facturaComputarizadaCompraVenta.xsd">
        </facturaComputarizadaCompraVenta>');

        $this->formato_xml($temporal, $xml_temporal);
        
        // OPTIMIZACIÓN: Generación de XML, compresión y hash en memoria.
        $archivoxml = $xml_temporal->asXML();
        $archivoGzip = gzencode($archivoxml, 1); // Nivel 1 de compresión es más rápido.
        
        $hashArchivo = hash('sha256', $archivoxml);
        require "Siat.php";
        $siat = new Siat();
        
        // Llamada síncrona al servicio externo.
        $resFactura = $siat->recepcionFactura($archivoGzip, $fechaEmision, $hashArchivo);
        
        if ($resFactura->RespuestaServicioFacturacion->transaccion == true) {
            $id_cliente = $_POST['id_cliente'];
            $numeroFactura = $valores['numeroFactura'];
            $codigoMetodoPago = 1;
            $montoTotal = $valores['montoTotal'];
            $montoTotalSujetoIva = $valores['montoTotal'];
            $descuentoAdicional = $valores['descuentoAdicional'];
            $productos = $archivoxml;
            $codigoRecepcion = $resFactura->RespuestaServicioFacturacion->codigoRecepcion;
            $data = $this->model->guardarFactura($id_cliente, $numeroFactura, $cuf, $fechaEmision, $codigoMetodoPago, $montoTotal, $montoTotalSujetoIva, $descuentoAdicional, $productos, $codigoRecepcion);
            if ($data == "error") {
                $resFactura->RespuestaServicioFacturacion->transaccion = false;
                $resFactura->RespuestaServicioFacturacion->mensajesList->descripcion = "Error al guardar en la base de datos";
            }
            echo json_encode($resFactura);
        } else {
            echo json_encode($resFactura);
        }
    }

    public function calculaDigitoMod11(string $cadena, int $numDig, int $limMult, bool $x10): string
    {
        if (!$x10) {
            $numDig = 1;
        }
        for ($n = 1; $n <= $numDig; $n++) {
            $suma = 0;
            $mult = 2;
            for ($i = strlen($cadena) - 1; $i >= 0; $i--) {
                $suma += ($mult * substr($cadena, $i, 1));
                if (++$mult > $limMult) {
                    $mult = 2;
                }
            }
            if ($x10) {
                $dig = (($suma * 10) % 11) % 10;
            } else {
                $dig = $suma % 11;
            }
            if ($dig == 10) {
                $cadena .= "1";
            } elseif ($dig == 11) {
                $cadena .= "0";
            } elseif ($dig < 10) {
                $cadena .= $dig;
            }
        }
        return substr($cadena, strlen($cadena) - $numDig, strlen($cadena));
    }

    public function obtenerModulo11(String $cadena): string
    {
        $vDigito = $this->calculaDigitoMod11($cadena, 1, 9, false);
        return $vDigito;
    }

    public function base16($number): string
    {
        $hexvalores = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F');
        $hexval = '';
        while ($number != '0') {
            $hexval = $hexvalores[round(bcmod($number, '16'))] . $hexval;
            $number = bcdiv($number, '16', 0);
        }
        return $hexval;
    }

    public function formato_xml($temporal, &$xml_temporal)
    {
        $xsi = "http://www.w3.org/2001/XMLSchema-instance";

        foreach ($temporal as $llave => $valor) {
            if (is_array($valor)) {
                if (!is_numeric($llave)) {
                    $subnodo = $xml_temporal->addChild($llave);
                    $this->formato_xml($valor, $subnodo);
                } else {
                    $this->formato_xml($valor, $xml_temporal);
                }
            } else {
                if ($valor === null || $valor === '') {
                    $hijo = $xml_temporal->addChild($llave);
                    $hijo->addAttribute('xsi:nil', 'true', $xsi);
                } else {
                    $xml_temporal->addChild($llave, htmlspecialchars($valor));
                }
            }
        }
    }

    public function imprimirFactura()
    {
        $id_factura = $_GET['id'];
        $data = $this->model->getFactura($id_factura);
        $xml = $data['productos'];
        $archivoxml = new SimpleXMLElement($xml);
        $valor = $archivoxml->cabecera[0];
        $razonSocialEmisor = $valor->razonSocialEmisor;
        $nitEmisor = $valor->nitEmisor;
        $codigoSucursal = $valor->codigoSucursal;
        if ($codigoSucursal == 0) {
            $sucursal = "CASA MATRIZ";
        } else {
            $sucursal = "SUCURSAL N. " . $codigoSucursal;
        }
        $numeroFactura = $valor->numeroFactura;
        $codigoPuntoVenta = $valor->codigoPuntoVenta;
        $cuf = $valor->cuf;
        $direccion = $valor->direccion;
        $telefono = $valor->telefono;
        $municipio = $valor->municipio;
        $fechaEmision = substr(str_replace("T", " ", $valor->fechaEmision), 0, -4);
        $numeroDocumento = $valor->numeroDocumento;
        $complemento = $valor->complemento;
        if ($complemento != "") {
            $nitcliente = $numeroDocumento . '-' . $complemento;
        } else {
            $nitcliente = $numeroDocumento;
        }
        $nombreRazonSocial = $valor->nombreRazonSocial;
        $descuentoAdicional = $valor->descuentoAdicional;
        $montoTotal = $valor->montoTotal;
        $montoGiftCard = $valor->montoGiftCard;
        $leyenda = $valor->leyenda;
        require('Assets/fpdf/fpdf.php');
        $pdf = new FPDF('P', 'mm', 'Letter');
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 9);
        //$pdf->Cell(98, 5, utf8_decode(''),1,0,'L');
        //$pdf->Cell(98, 5, utf8_decode(''),1,1,'L');

        $pdf->Cell(60, 4, utf8_decode($razonSocialEmisor), 0, 0, 'C');
        $pdf->Cell(38, 4, utf8_decode(''), 0, 0, 'L');
        $pdf->Cell(30, 4, utf8_decode(''), 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(36.5, 4, utf8_decode('NIT'), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(0, 4, utf8_decode($nitEmisor), 0, 1, 'L');

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(60, 4, utf8_decode($sucursal), 0, 0, 'C');
        $pdf->Cell(38, 4, utf8_decode(''), 0, 0, 'L');
        $pdf->Cell(30, 4, utf8_decode(''), 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(36.5, 4, utf8_decode('FACTURA N°'), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(0, 4, utf8_decode($numeroFactura), 0, 1, 'L');

        $pdf->Cell(60, 4, utf8_decode('No. Punto de Venta ' . $codigoPuntoVenta), 0, 0, 'C');
        $pdf->Cell(38, 4, utf8_decode(''), 0, 0, 'L');
        $pdf->Cell(30, 4, utf8_decode(''), 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(36.5, 4, utf8_decode('CÓD. AUTORIZACIÓN'), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 8);
        $y = $pdf->GetY();
        $pdf->MultiCell(0, 3, utf8_decode($cuf), 0, 'L');

        $pdf->SetY($y + 4);
        $pdf->Cell(60, 4, utf8_decode($direccion), 0, 1, 'C');
        $pdf->Cell(60, 4, utf8_decode('Teléfono: ' . $telefono), 0, 1, 'C');
        $pdf->Cell(60, 4, utf8_decode($municipio), 0, 1, 'C');

        $pdf->Ln(8);
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 6, utf8_decode('FACTURA'), 0, 1, 'C');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(0, 6, utf8_decode('(Con Derecho a Crédito Fiscal)'), 0, 1, 'C');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(41, 5, utf8_decode('Fecha:'), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(57, 5, utf8_decode($fechaEmision), 0, 0, 'L');
        $pdf->Cell(40, 5, utf8_decode(''), 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(29, 5, utf8_decode('NIT/CI/CEX:    '), 0, 0, 'R');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(0, 5, utf8_decode($nitcliente), 0, 1, 'L');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(41, 5, utf8_decode('Nombre/Razón Social:'), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(57, 5, utf8_decode($nombreRazonSocial), 0, 0, 'L');
        $pdf->Cell(40, 5, utf8_decode(''), 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(29, 5, utf8_decode('Cod. Cliente:    '), 0, 0, 'R');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(0, 5, utf8_decode($numeroDocumento), 0, 1, 'L');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 8);
        $y = $pdf->GetY();
        $pdf->MultiCell(29, 4, utf8_decode('CÓDIGO PRODUCTO / SERVICIO'), 1, 'C');
        $pdf->SetY($y);
        $pdf->SetX(39);
        $pdf->MultiCell(21, 4, utf8_decode("\nCANTIDAD\n "), 1, 'C');
        $pdf->SetY($y);
        $pdf->SetX(60);
        $pdf->MultiCell(19, 4, utf8_decode("\nUNIDAD DE MEDIDA"), 1, 'C');
        $pdf->SetY($y);
        $pdf->SetX(79);
        $pdf->MultiCell(51, 4, utf8_decode("\nDESCRIPCIÓN\n "), 1, 'C');
        $pdf->SetY($y);
        $pdf->SetX(130);
        $pdf->MultiCell(25, 4, utf8_decode("\nPRECIO UNITARIO"), 1, 'C');
        $pdf->SetY($y);
        $pdf->SetX(155);
        $pdf->MultiCell(25, 4, utf8_decode("\nDESCUENTO\n "), 1, 'C');
        $pdf->SetY($y);
        $pdf->SetX(180);
        $pdf->MultiCell(0, 4, utf8_decode("\nSUBTOTAL\n "), 1, 'C');

        $pdf->SetFont('Arial', '', 8);

        $lista_productos = $archivoxml->detalle;
        $subTotal = 0;
        foreach ($lista_productos as $p) {
            $pdf->Cell(29, 7, utf8_decode($p->codigoProducto), 1, 0, 'L');
            $pdf->Cell(21, 7, number_format(floatval($p->cantidad), 2), 1, 0, 'R');
            $pdf->Cell(19, 7, utf8_decode("UNIDAD"), 1, 0, 'L');
            $pdf->Cell(51, 7, utf8_decode($p->descripcion), 1, 0, 'L');
            $pdf->Cell(25, 7, number_format(floatval($p->precioUnitario), 2), 1, 0, 'R');
            $pdf->Cell(25, 7, number_format(floatval($p->montoDescuento), 2), 1, 0, 'R');
            $pdf->Cell(0, 7, number_format(floatval($p->subTotal), 2), 1, 1, 'R');
            $subTotal += $p->subTotal;
        }

        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(120, 4, utf8_decode(''), 0, 0, 'L');
        $pdf->Cell(50, 4, utf8_decode("SUBTOTAL Bs"), 1, 0, 'R');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(0, 4, number_format($subTotal, 2), 1, 1, 'R');

        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(120, 4, utf8_decode(''), 0, 0, 'L');
        $pdf->Cell(50, 4, utf8_decode("DESCUENTO Bs"), 1, 0, 'R');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(0, 4, number_format(floatval($descuentoAdicional), 2), 1, 1, 'R');

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(120, 4, utf8_decode('Son: Trescientos nueve 51/100 Bolivianos'), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(50, 4, utf8_decode("TOTAL Bs"), 1, 0, 'R');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(0, 4, number_format(floatval($montoTotal), 2), 1, 1, 'R');

        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(120, 4, utf8_decode(''), 0, 0, 'L');
        $pdf->Cell(50, 4, utf8_decode("MONTO GIFT CARD Bs"), 1, 0, 'R');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(0, 4, number_format(floatval($montoGiftCard), 2), 1, 1, 'R');

        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(120, 4, utf8_decode(''), 0, 0, 'L');
        $pdf->Cell(50, 4, utf8_decode("MONTO A PAGAR Bs"), 1, 0, 'R');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(0, 4, number_format(floatval($montoTotal), 2), 1, 1, 'R');

        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(120, 4, utf8_decode(''), 0, 0, 'L');
        $pdf->Cell(50, 4, utf8_decode("IMPORTE BASE CRÉDITO FISCAL"), 1, 0, 'R');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(0, 4, number_format(floatval($montoTotal), 2), 1, 1, 'R');

        $pdf->Ln(7);
        $y = $pdf->GetY();
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(170, 7, utf8_decode('ESTA FACTURA CONTRIBUYE AL DESARROLLO DEL PAÍS, EL USO ILÍCITO SERÁ SANCIONADO PENALMENTE DE ACUERDO A LEY'), 0, 1, 'C');
        $pdf->SetFont('Arial', '', 7.5);
        $pdf->Cell(170, 5, utf8_decode($leyenda), 0, 1, 'C');
        $pdf->Cell(170, 5, utf8_decode('"Este documento es la Representación Gráfica de un Documento Fiscal Digital emitido en una modalidad de facturación en línea"'), 0, 1, 'C');

        $pdf->Image("Assets/img/qrfactura.png", 180, $y, 23);

        $pdf->Output("factura.pdf", "I");
    }

}