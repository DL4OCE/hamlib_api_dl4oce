<?php 
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 'On');
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);
// die($_SERVER['REQUEST_URI']);
// print_r($_SERVER);
//require_once('src/Bramus/Router/Router.php');
require_once "vendor/autoload.php";
require_once "lib/rigctl/rigctl.php";

$model = 1020;
$device = "/dev/ttyUSB1";
$dummy = true;

$router = new \Bramus\Router\Router();
$router->set404(function() { 
    header('HTTP/1.1 404 Not Found'); 
    echo "Error 404!"; 
});

$router->get('/', function () { echo file_get_contents('usage.html');});
$router->get('/trx/(\d+)/frequency', function($trx_id) { echo get_trx_frequency($trx_id);});
$router->post('/trx/(\d+)/frequency', function($trx_id) { echo set_trx_frequency(json_decode(getRequestBody(), true), $trx_id);});
$router->get('/trx/(\d+)/mode', function($trx_id) { echo get_trx_mode($trx_id);});
$router->post('/trx/(\d+)/mode', function($trx_id) { echo set_trx_mode(json_decode(getRequestBody(), true), $trx_id);});

$router->get('/trx/(\d+)/split_frequency', function($trx_id) { echo get_trx_split_frequency($trx_id);});
$router->post('/trx/(\d+)/split_frequency', function($trx_id) { echo set_trx_split_frequency(json_decode(getRequestBody(), true), $trx_id);});

$router->get('/trx/(\d+)/split_mode', function($trx_id) { echo get_trx_split_mode($trx_id);});
$router->post('/trx/(\d+)/split_mode', function($trx_id) { echo set_trx_split_mode(json_decode(getRequestBody(), true), $trx_id);});

$router->get('/trx/(\d+)/split_frequency_mode', function($trx_id) { echo get_trx_split_frequency_mode($trx_id);});
$router->post('/trx/(\d+)/split_frequency_mode', function($trx_id) { echo set_trx_split_frequency_mode(json_decode(getRequestBody(), true), $trx_id);});

$router->get('/trx/(\d+)/split_vfo', function($trx_id) { echo get_trx_split_vfo($trx_id);});
$router->post('/trx/(\d+)/split_vfo', function($trx_id) { echo set_trx_split_vfo(json_decode(getRequestBody(), true), $trx_id);});

$router->get('/trx/(\d+)/tuning_step', function($trx_id) { echo get_trx_tuningstep($trx_id);});
$router->post('/trx/(\d+)/tuning_step', function($trx_id) { echo set_trx_tuningstep(json_decode(getRequestBody(), true), $trx_id);});

$router->get('/trx/(\d+)/level/(\w+)', function($trx_id, $level_param) { echo get_trx_level($level_param, $trx_id);});
$router->post('/trx/(\d+)/level/(\w+)', function($trx_id, $level_param) { echo set_trx_level($level_param, json_decode(getRequestBody(), true), $trx_id);});




$router->run();

function getRequestBody(){
    $entityBody = file_get_contents('php://input');
    return($entityBody);
}

function build_response($response){
    http_response_code(200);
    echo json_encode(array(
        "REQUEST_URI" => $_SERVER['REQUEST_URI'],
        "REQUEST_BODY" => getRequestBody(),
        "response" => $response
    ));
}

?>