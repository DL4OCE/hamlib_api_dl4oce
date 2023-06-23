<?php 

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 'On');
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

// print_r($_SERVER);

//require_once('src/Bramus/Router/Router.php');
require_once "vendor/autoload.php";
require_once "lib/rigctl/rigctl.php";

$model = 120;
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



$router->mount('/trx', function() use ($router) {
    // $router->get('/', function() {
    //     // prepareResult();
    // });

    $router->get('/(\d+)/qrg', function($trx_id) {
        echo get_trx_qrg($trx_id);
        // echo 'trx id ' . htmlentities($id);
    });

});

$router->get('/', function () {
    echo '<h1>rigctl, rot   ctl, rigmem, rigsmtr, rigswr API</h1>
    Have a look at the <a href="https://github.com/DL4OCE/rigctl_api">rigctl_api git repository</a> for source code and documentation!';
});


$router->run();


?>