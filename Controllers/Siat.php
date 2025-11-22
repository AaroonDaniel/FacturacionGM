<?php
class Siat
{
    public function verificarComunicacion()
    {
        $wsdl = "https://pilotosiatservicios.impuestos.gob.bo/v2/FacturacionCodigos?wsdl";
        $opciones = array(
            'http' => array(
                'header' => "apikey: TokenApi eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJnYXJ5LnJhbWlyZXouY29kaWdvQGdtYWlsLmNvbSIsImNvZGlnb1Npc3RlbWEiOiIzNTIxRDAyNjU2QjFENzhGRDkwRSIsIm5pdCI6Ikg0c0lBQUFBQUFBQUFETTJOakkzTWJjME1EUUdBTFIzOFlFS0FBQUEiLCJpZCI6NTA1NzAxNywiZXhwIjoxNzY0NTE0NTk3LCJpYXQiOjE3NTgzMDgxNjcsIm5pdERlbGVnYWRvIjozMzI3NDc5MDEzLCJzdWJzaXN0ZW1hIjoiU0ZFIn0.skiuaKRJlBJ7MKKXoUkvtwDq_iqYzSfec8YxOK0fejfyGKnTEVy0pKfSYzBIEd5vAjE9LBH0py2vlx3Hn3FPOw",
                'timeout' => 5
            )
        );

        $contexto = stream_context_create($opciones);
        try {
            $cliente = new SoapClient($wsdl, [
                'stream_context' => $contexto,
                'cache_wsdl' => WSDL_CACHE_NONE,
                'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP | SOAP_COMPRESSION_DEFLATE
            ]);

            $resultadoSoap = $cliente->verificarComunicacion();

            return [
                "RespuestaComunicacion" => [
                    "transaccion" => true,
                    "mensajesList" => [
                        "descripcion" => "Comunicación Exitosa"
                    ]
                ]
            ];
        } catch (SoapFault $fault) {
            return [
                "RespuestaComunicacion" => [
                    "transaccion" => false,
                    "mensajesList" => [
                        "descripcion" => "Error: " . $fault->getMessage()
                    ]
                ]
            ];
        }
    }
    public function cuis()
    {
        $wsdl = "https://pilotosiatservicios.impuestos.gob.bo/v2/FacturacionCodigos?wsdl";

        $codigoAmbiente = 2;
        $codigoModalidad = 2;
        $codigoPuntoVenta = 0;
        $codigoSistema = "3521D02656B1D78FD90E";
        $codigoSucursal = 0;
        $nit = "3327479013";


        $parametros = array(
            'SolicitudCuis' => array(
                'codigoAmbiente' => $codigoAmbiente,
                'codigoModalidad' => $codigoModalidad,
                'codigoPuntoVenta' => $codigoPuntoVenta,
                'codigoSistema' => $codigoSistema,
                'codigoSucursal' => $codigoSucursal,
                'nit' => $nit
            )
        );

        $opciones = array(
            'http' => array(
                'header' => "apikey: TokenApi eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJnYXJ5LnJhbWlyZXouY29kaWdvQGdtYWlsLmNvbSIsImNvZGlnb1Npc3RlbWEiOiIzNTIxRDAyNjU2QjFENzhGRDkwRSIsIm5pdCI6Ikg0c0lBQUFBQUFBQUFETTJOakkzTWJjME1EUUdBTFIzOFlFS0FBQUEiLCJpZCI6NTA1NzAxNywiZXhwIjoxNzY0NTE0NTk3LCJpYXQiOjE3NTgzMDgxNjcsIm5pdERlbGVnYWRvIjozMzI3NDc5MDEzLCJzdWJzaXN0ZW1hIjoiU0ZFIn0.skiuaKRJlBJ7MKKXoUkvtwDq_iqYzSfec8YxOK0fejfyGKnTEVy0pKfSYzBIEd5vAjE9LBH0py2vlx3Hn3FPOw",
                'timeout' => 5
            )
        );

        $contexto = stream_context_create($opciones);

        try {
            $cliente = new SoapClient($wsdl, [
                'stream_context' => $contexto,
                'cache_wsdl' => WSDL_CACHE_NONE,
                'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP | SOAP_COMPRESSION_DEFLATE
            ]);
            $resultado = $cliente->cuis($parametros);
        } catch (SoapFault $fault) {
            $resultado = false;
        }
        return $resultado;
    }

    public function cufd()
    {
        // Validamos errores para que no rompan el JSON
        try {
            $res = array();

            // Verificamos si necesitamos pedir un nuevo CUFD (No existe o ha expirado)
            $necesitaNuevo = !isset($_SESSION['scufd']) || !isset($_SESSION['sfechaVigenciaCufd']);

            if (!$necesitaNuevo) {
                // Si existe, validamos la fecha
                $fechaVigente = substr($_SESSION['sfechaVigenciaCufd'], 0, 16);
                $fechaVigente = str_replace("T", " ", $fechaVigente);

                // Si la fecha actual es mayor a la vigencia, necesitamos uno nuevo
                if ($fechaVigente < date("Y-m-d H:i")) {
                    $necesitaNuevo = true;
                }
            }

            if ($necesitaNuevo) {
                // Intentamos conectar con SIAT
                require_once "Siat.php"; // Usar require_once es mas seguro
                $siat = new Siat();
                $respuestaSiat = $siat->cufd();

                // Validamos que la respuesta de SIAT sea un objeto válido
                if (isset($respuestaSiat->RespuestaCufd->transaccion) && $respuestaSiat->RespuestaCufd->transaccion == true) {
                    $_SESSION['scufd'] = $respuestaSiat->RespuestaCufd->codigo;
                    $_SESSION['scodigoControl'] = $respuestaSiat->RespuestaCufd->codigoControl;
                    $_SESSION['sfechaVigenciaCufd'] = $respuestaSiat->RespuestaCufd->fechaVigencia;
                    $res = $respuestaSiat;
                } else {
                    // Si SIAT responde pero con error (transaccion = false)
                    $res['RespuestaCufd']['transaccion'] = false;
                    $res['RespuestaCufd']['mensajesList']['descripcion'] = "No se pudo obtener CUFD del SIAT.";
                }
            } else {
                // Usamos el de la sesión
                $res['RespuestaCufd']['transaccion'] = true;
                $res['RespuestaCufd']['codigo'] = $_SESSION['scufd'];
                $res['RespuestaCufd']['fechaVigencia'] = $_SESSION['sfechaVigenciaCufd'];
                // Estructura para mantener compatibilidad con el JS
                $res = json_decode(json_encode($res));
            }

            echo json_encode($res);
        } catch (Throwable $th) {
            // CAPTURA DE ERRORES: Si falla la conexión o hay error de código
            $errorData = [
                'RespuestaCufd' => [
                    'transaccion' => false,
                    'mensajesList' => [
                        'descripcion' => 'Error interno al solicitar CUFD: ' . $th->getMessage()
                    ]
                ]
            ];
            echo json_encode($errorData);
        }
        die();
    }
    //Unsolved Case
    public function sincronizarActividades()
    {
        $wsdl = "https://pilotosiatservicios.impuestos.gob.bo/v2/FacturacionSincronizacion?wsdl";

        $codigoAmbiente = 2;
        $codigoPuntoVenta = 0;
        $codigoSistema = "3521D02656B1D78FD90E";
        $codigoSucursal = 0;
        $cuis = $_SESSION['scuis'];
        $nit = "3327479013";

        $parametros  = array(
            'SolicitudSincronizacion' => array(
                'codigoAmbiente' => $codigoAmbiente,
                'codigoPuntoVenta' => $codigoPuntoVenta,
                'codigoSistema' => $codigoSistema,
                'codigoSucursal' => $codigoSucursal,
                'cuis' => $cuis,
                'nit' => $nit
            )
        );
        $opciones = array(
            'http' => array(
                'header' => ' apikey: TokenApi eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJnYXJ5LnJhbWlyZXouY29kaWdvQGdtYWlsLmNvbSIsImNvZGlnb1Npc3RlbWEiOiIzNTIxRDAyNjU2QjFENzhGRDkwRSIsIm5pdCI6Ikg0c0lBQUFBQUFBQUFETTJOakkzTWJjME1EUUdBTFIzOFlFS0FBQUEiLCJpZCI6NTA1NzAxNywiZXhwIjoxNzY0NTE0NTk3LCJpYXQiOjE3NTgzMDgxNjcsIm5pdERlbGVnYWRvIjozMzI3NDc5MDEzLCJzdWJzaXN0ZW1hIjoiU0ZFIn0.skiuaKRJlBJ7MKKXoUkvtwDq_iqYzSfec8YxOK0fejfyGKnTEVy0pKfSYzBIEd5vAjE9LBH0py2vlx3Hn3FPOw',
                'timeout' => 5
            )
        );

        $contexto = stream_context_create($opciones);

        try {
            $cliente = new SoapClient($wsdl, [
                'stream_context' => $contexto,
                'cache_wsdl' => WSDL_CACHE_NONE,
                'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP | SOAP_COMPRESSION_DEFLATE
            ]);
            $resultado = $cliente->sincronizarActividades($parametros);
        } catch (SoapFault $fault) {
            $resultado = $fault->faultstring;
        }
        return $resultado;
    }

    public function sincronizarListaProductosServicios()
    {
        $wsdl = "https://pilotosiatservicios.impuestos.gob.bo/v2/FacturacionSincronizacion?wsdl";
        $codigoAmbiente = 2;
        $codigoPuntoVenta = 0;
        $codigoSistema = "3521D02656B1D78FD90E";
        $codigoSucursal = 0;
        $cuis = $_SESSION['scuis'];
        $nit = "3327479013";

        $parametros  = array(
            'SolicitudSincronizacion' => array(
                'codigoAmbiente' => $codigoAmbiente,
                'codigoPuntoVenta' => $codigoPuntoVenta,
                'codigoSistema' => $codigoSistema,
                'codigoSucursal' => $codigoSucursal,
                'cuis' => $cuis,
                'nit' => $nit
            )
        );
        $opciones = array(
            'http' => array(
                'header' => ' apikey: TokenApi eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJnYXJ5LnJhbWlyZXouY29kaWdvQGdtYWlsLmNvbSIsImNvZGlnb1Npc3RlbWEiOiIzNTIxRDAyNjU2QjFENzhGRDkwRSIsIm5pdCI6Ikg0c0lBQUFBQUFBQUFETTJOakkzTWJjME1EUUdBTFIzOFlFS0FBQUEiLCJpZCI6NTA1NzAxNywiZXhwIjoxNzY0NTE0NTk3LCJpYXQiOjE3NTgzMDgxNjcsIm5pdERlbGVnYWRvIjozMzI3NDc5MDEzLCJzdWJzaXN0ZW1hIjoiU0ZFIn0.skiuaKRJlBJ7MKKXoUkvtwDq_iqYzSfec8YxOK0fejfyGKnTEVy0pKfSYzBIEd5vAjE9LBH0py2vlx3Hn3FPOw',
                'timeout' => 5
            )
        );

        $contexto = stream_context_create($opciones);

        try {
            $cliente = new SoapClient($wsdl, [
                'stream_context' => $contexto,
                'cache_wsdl' => WSDL_CACHE_NONE,
                'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP | SOAP_COMPRESSION_DEFLATE
            ]);
            $resultado = $cliente->sincronizarListaProductosServicios($parametros);
        } catch (SoapFault $fault) {
            $resultado = $fault->faultstring;
        }
        return $resultado;
    }

    public function sincronizarParametricaUnidadMedida()
    {
        $wsdl = "https://pilotosiatservicios.impuestos.gob.bo/v2/FacturacionSincronizacion?wsdl";
        $codigoAmbiente = 2;
        $codigoPuntoVenta = 0;
        $codigoSistema = "3521D02656B1D78FD90E";
        $codigoSucursal = 0;
        $cuis = $_SESSION['scuis'];
        $nit = "3327479013";

        $parametros  = array(
            'SolicitudSincronizacion' => array(
                'codigoAmbiente' => $codigoAmbiente,
                'codigoPuntoVenta' => $codigoPuntoVenta,
                'codigoSistema' => $codigoSistema,
                'codigoSucursal' => $codigoSucursal,
                'cuis' => $cuis,
                'nit' => $nit
            )
        );
        $opciones = array(
            'http' => array(
                'header' => ' apikey: TokenApi eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJnYXJ5LnJhbWlyZXouY29kaWdvQGdtYWlsLmNvbSIsImNvZGlnb1Npc3RlbWEiOiIzNTIxRDAyNjU2QjFENzhGRDkwRSIsIm5pdCI6Ikg0c0lBQUFBQUFBQUFETTJOakkzTWJjME1EUUdBTFIzOFlFS0FBQUEiLCJpZCI6NTA1NzAxNywiZXhwIjoxNzY0NTE0NTk3LCJpYXQiOjE3NTgzMDgxNjcsIm5pdERlbGVnYWRvIjozMzI3NDc5MDEzLCJzdWJzaXN0ZW1hIjoiU0ZFIn0.skiuaKRJlBJ7MKKXoUkvtwDq_iqYzSfec8YxOK0fejfyGKnTEVy0pKfSYzBIEd5vAjE9LBH0py2vlx3Hn3FPOw',
                'timeout' => 5
            )
        );

        $contexto = stream_context_create($opciones);

        try {
            $cliente = new SoapClient($wsdl, [
                'stream_context' => $contexto,
                'cache_wsdl' => WSDL_CACHE_NONE,
                'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP | SOAP_COMPRESSION_DEFLATE
            ]);
            $resultado = $cliente->sincronizarParametricaUnidadMedida($parametros);
        } catch (SoapFault $fault) {
            $resultado = $fault->faultstring;
        }
        return $resultado;
    }

    public function sincronizarListaLeyendasFactura()
    {
        $wsdl = "https://pilotosiatservicios.impuestos.gob.bo/v2/FacturacionSincronizacion?wsdl";

        $codigoAmbiente = 2;
        $codigoPuntoVenta = 0;
        $codigoSistema = "3521D02656B1D78FD90E";
        $codigoSucursal = 0;
        $cuis = $_SESSION['scuis'];
        $nit = "3327479013";

        $parametros  = array(
            'SolicitudSincronizacion' => array(
                'codigoAmbiente' => $codigoAmbiente,
                'codigoPuntoVenta' => $codigoPuntoVenta,
                'codigoSistema' => $codigoSistema,
                'codigoSucursal' => $codigoSucursal,
                'cuis' => $cuis,
                'nit' => $nit
            )
        );
        $opciones = array(
            'http' => array(
                'header' => ' apikey: TokenApi eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJnYXJ5LnJhbWlyZXouY29kaWdvQGdtYWlsLmNvbSIsImNvZGlnb1Npc3RlbWEiOiIzNTIxRDAyNjU2QjFENzhGRDkwRSIsIm5pdCI6Ikg0c0lBQUFBQUFBQUFETTJOakkzTWJjME1EUUdBTFIzOFlFS0FBQUEiLCJpZCI6NTA1NzAxNywiZXhwIjoxNzY0NTE0NTk3LCJpYXQiOjE3NTgzMDgxNjcsIm5pdERlbGVnYWRvIjozMzI3NDc5MDEzLCJzdWJzaXN0ZW1hIjoiU0ZFIn0.skiuaKRJlBJ7MKKXoUkvtwDq_iqYzSfec8YxOK0fejfyGKnTEVy0pKfSYzBIEd5vAjE9LBH0py2vlx3Hn3FPOw',
                'timeout' => 5
            )
        );

        $contexto = stream_context_create($opciones);

        try {
            $cliente = new SoapClient($wsdl, [
                'stream_context' => $contexto,
                'cache_wsdl' => WSDL_CACHE_NONE,
                'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP | SOAP_COMPRESSION_DEFLATE
            ]);
            $resultado = $cliente->sincronizarListaLeyendasFactura($parametros);
        } catch (SoapFault $fault) {
            $resultado = $fault->faultstring;
        }
        return $resultado;
    }

    public function sincronizarParametricaMotivoAnulacion()
    {
        $wsdl = "https://pilotosiatservicios.impuestos.gob.bo/v2/FacturacionSincronizacion?wsdl";

        $codigoAmbiente = 2;
        $codigoPuntoVenta = 0;
        $codigoSistema = "3521D02656B1D78FD90E";
        $codigoSucursal = 0;
        $cuis = $_SESSION['scuis'];
        $nit = "3327479013";

        $parametros  = array(
            'SolicitudSincronizacion' => array(
                'codigoAmbiente' => $codigoAmbiente,
                'codigoPuntoVenta' => $codigoPuntoVenta,
                'codigoSistema' => $codigoSistema,
                'codigoSucursal' => $codigoSucursal,
                'cuis' => $cuis,
                'nit' => $nit
            )
        );
        $opciones = array(
            'http' => array(
                'header' => ' apikey: TokenApi eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJnYXJ5LnJhbWlyZXouY29kaWdvQGdtYWlsLmNvbSIsImNvZGlnb1Npc3RlbWEiOiIzNTIxRDAyNjU2QjFENzhGRDkwRSIsIm5pdCI6Ikg0c0lBQUFBQUFBQUFETTJOakkzTWJjME1EUUdBTFIzOFlFS0FBQUEiLCJpZCI6NTA1NzAxNywiZXhwIjoxNzY0NTE0NTk3LCJpYXQiOjE3NTgzMDgxNjcsIm5pdERlbGVnYWRvIjozMzI3NDc5MDEzLCJzdWJzaXN0ZW1hIjoiU0ZFIn0.skiuaKRJlBJ7MKKXoUkvtwDq_iqYzSfec8YxOK0fejfyGKnTEVy0pKfSYzBIEd5vAjE9LBH0py2vlx3Hn3FPOw',
                'timeout' => 5
            )
        );

        $contexto = stream_context_create($opciones);

        try {
            $cliente = new SoapClient($wsdl, [
                'stream_context' => $contexto,
                'cache_wsdl' => WSDL_CACHE_NONE,
                'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP | SOAP_COMPRESSION_DEFLATE
            ]);
            $resultado = $cliente->sincronizarParametricaMotivoAnulacion($parametros);
        } catch (SoapFault $fault) {
            $resultado = $fault->faultstring;
        }
        return $resultado;
    }

    public function recepcionFactura($archivo, $fechaEmision, $hashArchivo)
    {
        $wsdl = "https://pilotosiatservicios.impuestos.gob.bo/v2/ServicioFacturacionCompraVenta?wsdl";

        $codigoAmbiente = 2;
        $codigoDocumentoSector = 1;
        $codigoEmision = 1;
        $codigoModalidad = 2;
        $codigoPuntoVenta = 0;
        $codigoSistema = "3521D02656B1D78FD90E";
        $codigoSucursal = 0;
        $cufd = $_SESSION['scufd'];
        $cuis = $_SESSION['scuis'];
        $nit = "3327479013";
        $tipoFacturaDocumento = 1;
        $archivo = $archivo;
        $fechaEnvio = $fechaEmision;
        $hashArchivo = $hashArchivo;

        $parametros = array(
            'SolicitudServicioRecepcionFactura' => array(
                'codigoAmbiente' => $codigoAmbiente,
                'codigoDocumentoSector' => $codigoDocumentoSector,
                'codigoEmision' => $codigoEmision,
                'codigoModalidad' => $codigoModalidad,
                'codigoPuntoVenta' => $codigoPuntoVenta,
                'codigoSistema' => $codigoSistema,
                'codigoSucursal' => $codigoSucursal,
                'cufd' => $cufd,
                'cuis' => $cuis,
                'nit' => $nit,
                'tipoFacturaDocumento' => $tipoFacturaDocumento,
                'archivo' => $archivo,
                'fechaEnvio' => $fechaEnvio,
                'hashArchivo' => $hashArchivo
            )
        );

        $opciones = array(
            'http' => array(
                'header' => ' apikey: TokenApi eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJnYXJ5LnJhbWlyZXouY29kaWdvQGdtYWlsLmNvbSIsImNvZGlnb1Npc3RlbWEiOiIzNTIxRDAyNjU2QjFENzhGRDkwRSIsIm5pdCI6Ikg0c0lBQUFBQUFBQUFETTJOakkzTWJjME1EUUdBTFIzOFlFS0FBQUEiLCJpZCI6NTA1NzAxNywiZXhwIjoxNzY0NTE0NTk3LCJpYXQiOjE3NTgzMDgxNjcsIm5pdERlbGVnYWRvIjozMzI3NDc5MDEzLCJzdWJzaXN0ZW1hIjoiU0ZFIn0.skiuaKRJlBJ7MKKXoUkvtwDq_iqYzSfec8YxOK0fejfyGKnTEVy0pKfSYzBIEd5vAjE9LBH0py2vlx3Hn3FPOw',
                'timeout' => 5
            )
        );

        $contexto = stream_context_create($opciones);

        try {
            $cliente = new SoapClient($wsdl, [
                'stream_context' => $contexto,
                'cache_wsdl' => WSDL_CACHE_NONE,
                'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP | SOAP_COMPRESSION_DEFLATE
            ]);
            $resultado = $cliente->recepcionFactura($parametros);
        } catch (SoapFault $fault) {
            $resultado = $fault->faultstring;
        }
        return $resultado;
    }


    public function anulacionFactura($cuf, $codigoMotivo)
    {
        $wsdl = "https://pilotosiatservicios.impuestos.gob.bo/v2/ServicioFacturacionCompraVenta?wsdl";

        $codigoAmbiente = 2;
        $codigoDocumentoSector = 1;
        $codigoEmision = 1;
        $codigoModalidad = 2;
        $codigoPuntoVenta = 0;
        $codigoSistema = "3521D02656B1D78FD90E";
        $codigoSucursal = 0;
        $cufd = $_SESSION['scufd'];
        $cuis = $_SESSION['scuis'];
        $nit = "3327479013";
        $tipoFacturaDocumento = 1;
        $codigoMotivo = $codigoMotivo;
        $cuf = $cuf;

        $parametros = array(
            'SolicitudServicioAnulacionFactura' => array(
                'codigoAmbiente' => $codigoAmbiente,
                'codigoDocumentoSector' => $codigoDocumentoSector,
                'codigoEmision' => $codigoEmision,
                'codigoModalidad' => $codigoModalidad,
                'codigoPuntoVenta' => $codigoPuntoVenta,
                'codigoSistema' => $codigoSistema,
                'codigoSucursal' => $codigoSucursal,
                'cufd' => $cufd,
                'cuis' => $cuis,
                'nit' => $nit,
                'tipoFacturaDocumento' => $tipoFacturaDocumento,
                'codigoMotivo' => $codigoMotivo,
                'cuf' => $cuf
            )
        );

        $opciones = array(
            'http' => array(
                'header' => ' apikey: TokenApi eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJnYXJ5LnJhbWlyZXouY29kaWdvQGdtYWlsLmNvbSIsImNvZGlnb1Npc3RlbWEiOiIzNTIxRDAyNjU2QjFENzhGRDkwRSIsIm5pdCI6Ikg0c0lBQUFBQUFBQUFETTJOakkzTWJjME1EUUdBTFIzOFlFS0FBQUEiLCJpZCI6NTA1NzAxNywiZXhwIjoxNzY0NTE0NTk3LCJpYXQiOjE3NTgzMDgxNjcsIm5pdERlbGVnYWRvIjozMzI3NDc5MDEzLCJzdWJzaXN0ZW1hIjoiU0ZFIn0.skiuaKRJlBJ7MKKXoUkvtwDq_iqYzSfec8YxOK0fejfyGKnTEVy0pKfSYzBIEd5vAjE9LBH0py2vlx3Hn3FPOw',
                'timeout' => 5
            )
        );

        $contexto = stream_context_create($opciones);

        try {
            $cliente = new SoapClient($wsdl, [
                'stream_context' => $contexto,
                'cache_wsdl' => WSDL_CACHE_NONE,
                'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP | SOAP_COMPRESSION_DEFLATE
            ]);
            $resultado = $cliente->anulacionFactura($parametros);
        } catch (SoapFault $fault) {
            $resultado = $fault->faultstring;
        }
        return $resultado;
    }

    public function reversionAnulacionFactura($cuf)
    {
        $wsdl = "https://pilotosiatservicios.impuestos.gob.bo/v2/ServicioFacturacionCompraVenta?wsdl";
        $codigoAmbiente = 2;
        $codigoDocumentoSector = 1;
        $codigoEmision = 1;
        $codigoModalidad = 2;
        $codigoPuntoVenta = 0;
        $codigoSistema = "3521D02656B1D78FD90E";
        $codigoSucursal = 0;
        $cufd = $_SESSION['scufd'];
        $cuis = $_SESSION['scuis'];
        $nit = "3327479013";
        $tipoFacturaDocumento = 1;
        $cuf = $cuf;

        $parametros = array(
            'SolicitudServicioReversionAnulacionFactura' => array(
                'codigoAmbiente' => $codigoAmbiente,
                'codigoDocumentoSector' => $codigoDocumentoSector,
                'codigoEmision' => $codigoEmision,
                'codigoModalidad' => $codigoModalidad,
                'codigoPuntoVenta' => $codigoPuntoVenta,
                'codigoSistema' => $codigoSistema,
                'codigoSucursal' => $codigoSucursal,
                'cufd' => $cufd,
                'cuis' => $cuis,
                'nit' => $nit,
                'tipoFacturaDocumento' => $tipoFacturaDocumento,
                'cuf' => $cuf
            )
        );

        $opciones = array(
            'http' => array(
                'header' => ' apikey: TokenApi eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJnYXJ5LnJhbWlyZXouY29kaWdvQGdtYWlsLmNvbSIsImNvZGlnb1Npc3RlbWEiOiIzNTIxRDAyNjU2QjFENzhGRDkwRSIsIm5pdCI6Ikg0c0lBQUFBQUFBQUFETTJOakkzTWJjME1EUUdBTFIzOFlFS0FBQUEiLCJpZCI6NTA1NzAxNywiZXhwIjoxNzY0NTE0NTk3LCJpYXQiOjE3NTgzMDgxNjcsIm5pdERlbGVnYWRvIjozMzI3NDc5MDEzLCJzdWJzaXN0ZW1hIjoiU0ZFIn0.skiuaKRJlBJ7MKKXoUkvtwDq_iqYzSfec8YxOK0fejfyGKnTEVy0pKfSYzBIEd5vAjE9LBH0py2vlx3Hn3FPOw',
                'timeout' => 5
            )
        );

        $contexto = stream_context_create($opciones);

        try {
            $cliente = new SoapClient($wsdl, [
                'stream_context' => $contexto,
                'cache_wsdl' => WSDL_CACHE_NONE,
                'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP | SOAP_COMPRESSION_DEFLATE
            ]);
            $resultado = $cliente->reversionAnulacionFactura($parametros);
        } catch (SoapFault $fault) {
            $resultado = $fault->faultstring;
        }
        return $resultado;
    }
}
