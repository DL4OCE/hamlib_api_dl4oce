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
$device = "/dev/ttyUSB0";

$router = new \Bramus\Router\Router();

$router->set404(function() { 
    header('HTTP/1.1 404 Not Found'); 
    echo "Error 404!"; 
});

// $router->set404(function () {
//     header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
//     echo '404, route not found!';
// });

// // custom 404
// $router->set404('/test(/.*)?', function () {
//     header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
//     echo '<h1><mark>404, route not found!</mark></h1>';
// });

// $router->set404('/api(/.*)?', function() {
//     header('HTTP/1.1 404 Not Found');
//     header('Content-Type: application/json');

//     $jsonArray = array();
//     $jsonArray['status'] = "404";
//     $jsonArray['status_text'] = "route not defined";

//     echo json_encode($jsonArray);
// });

$router->get('/', function () {
    echo file_get_contents('usage.html');
});

$router->get('/trx/(\d+)/qrg', function($trx_id) {
    echo get_trx_qrg($trx_id);
});

$router->post('/trx/(\d+)/qrg', function($trx_id) {
    echo set_trx_qrg(json_decode(getRequestBody(), true), $trx_id);
});

$router->get('/trx/(\d+)/mode', function($trx_id) {
    echo get_trx_mode($trx_id);
});

$router->post('/trx/(\d+)/mode', function($trx_id) {
    echo set_trx_mode(json_decode(getRequestBody(), true), $trx_id);
});

$router->run();

function getRequestBody(){
    $entityBody = file_get_contents('php://input');
    return($entityBody);
}

function build_response($response){
    http_response_code(200);
    // $response_complete = array(
    //     "test" => "testvalue",
    //     "response" => $response
    // );
    // echo json_encode($response_complete);
    echo json_encode(array(
        "REQUEST_URI" => $_SERVER['REQUEST_URI'],
        "REQUEST_BODY" => getRequestBody(),
        "response" => $response
    ));
}

?>