<?php
require_once "Config/Config.php";
require_once "Config/App/autoload.php";

$ruta = !empty($_GET['url']) ? $_GET['url'] : "Home/index";
$array = explode("/", $ruta);

$controller = $array[0];
$metodo = "index";
$parametro = "";

// Método
if (!empty($array[1])) {
    $metodo = $array[1];
}

// Parámetros
if (!empty($array[2])) {
    for ($i = 2; $i < count($array); $i++) {
        $parametro .= $array[$i] . ",";
    }
    $parametro = rtrim($parametro, ",");
}

// Ruta del controlador
$dirControllers = "Controllers/" . $controller . ".php";

if (file_exists($dirControllers)) {
    require_once $dirControllers;

    if (class_exists($controller)) {
        $controllerObj = new $controller();

        if (method_exists($controllerObj, $metodo)) {
            if (!empty($parametro)) {
                $controllerObj->$metodo($parametro);
            } else {
                $controllerObj->$metodo();
            }
        } else {
            echo "❌ El método '$metodo' no existe en el controlador '$controller'.";
        }
    } else {
        echo "❌ La clase '$controller' no se encontró en el archivo.";
    }
} else {
    echo "❌ El controlador '$controller' no existe en 'Controllers/'.";
}
