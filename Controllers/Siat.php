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
        $wsdl = "https://pilotosiatservicios.impuestos.gob.bo/v2/FacturacionCodigos?wsdl";
        $codigoAmbiente = 2;
        $codigoModalidad = 2;
        $codigoPuntoVenta = 0;
        $codigoSistema = "3521D02656B1D78FD90E";
        $codigoSucursal = 0;
        $cuis = $_SESSION['scuis'];
        $nit = "3327479013";

        $parametros = array(
            'SolicitudCufd' => array(
                'codigoAmbiente' => $codigoAmbiente,
                'codigoModalidad' => $codigoModalidad,
                'codigoPuntoVenta' => $codigoPuntoVenta,
                'codigoSistema' => $codigoSistema,
                'codigoSucursal' => $codigoSucursal,
                'cuis' => $cuis,
                'nit' => $nit
            )
        );
        $opciones = array(
            'http' => array(
                'header' => 'apikey: TokenApi eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJnYXJ5LnJhbWlyZXouY29kaWdvQGdtYWlsLmNvbSIsImNvZGlnb1Npc3RlbWEiOiIzNTIxRDAyNjU2QjFENzhGRDkwRSIsIm5pdCI6Ikg0c0lBQUFBQUFBQUFETTJOakkzTWJjME1EUUdBTFIzOFlFS0FBQUEiLCJpZCI6NTA1NzAxNywiZXhwIjoxNzY0NTE0NTk3LCJpYXQiOjE3NTgzMDgxNjcsIm5pdERlbGVnYWRvIjozMzI3NDc5MDEzLCJzdWJzaXN0ZW1hIjoiU0ZFIn0.skiuaKRJlBJ7MKKXoUkvtwDq_iqYzSfec8YxOK0fejfyGKnTEVy0pKfSYzBIEd5vAjE9LBH0py2vlx3Hn3FPOw',
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
            $resultado = $cliente->cufd($parametros);
        } catch (SoapFault $fault) {
            $resultado = $fault->faultstring;
        }
        return $resultado;
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

    public function sincronizarListaLeyendasFactura(){
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

    public function sincronizarParametricaMotivoAnulacion(){
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

    public function recepcionFactura($archivo, $fechaEmision, $hashArchivo){
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


    public function anulacionFactura($cuf, $codigoMotivo){
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

    public function reversionAnulacionFactura($cuf){
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
